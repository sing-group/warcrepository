<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * La API debe responder igual que con Dingo + tymon/jwt-auth 0.5 (Laravel 5.3).
 */
class ApiTest extends TestCase
{
    use RefreshDatabase;

    private function user(): User
    {
        return User::factory()->create([
            'email' => 'john@warcrepository.com',
            'password' => bcrypt('secret'),
        ]);
    }

    private function login(): string
    {
        $this->user();

        return $this->postJson('/api/login', ['email' => 'john@warcrepository.com', 'password' => 'secret'])
            ->assertOk()
            ->json('token');
    }

    /**
     * Token firmado como lo hacía tymon/jwt-auth 0.5 (HS256, sin claim "prv").
     */
    private function legacyToken(User $user, int $expiresIn = 3600): string
    {
        $encode = fn (string $data) => rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
        $now = time();
        $header = $encode(json_encode(['typ' => 'JWT', 'alg' => 'HS256']));
        $payload = $encode(json_encode([
            'sub' => $user->id,
            'iss' => 'http://localhost/api/login',
            'iat' => $now,
            'exp' => $now + $expiresIn,
            'nbf' => $now,
            'jti' => md5('legacy'.$now),
        ]));
        $signature = $encode(hash_hmac('sha256', "$header.$payload", config('jwt.secret'), true));

        return "$header.$payload.$signature";
    }

    public function test_login_returns_token(): void
    {
        $this->user();

        $response = $this->postJson('/api/login', ['email' => 'john@warcrepository.com', 'password' => 'secret']);

        $response->assertOk();
        $this->assertSame(['token'], array_keys($response->json()));
    }

    public function test_login_with_bad_credentials(): void
    {
        $this->user();

        $this->postJson('/api/login', ['email' => 'john@warcrepository.com', 'password' => 'wrong'])
            ->assertStatus(401)
            ->assertExactJson(['error' => 'invalid_credentials']);
    }

    public function test_me_returns_transformed_user(): void
    {
        $token = $this->login();
        $user = User::first();

        $this->getJson('/api/me', ['Authorization' => "Bearer $token"])
            ->assertOk()
            ->assertExactJson(['data' => [
                'id' => $user->uuid,
                'name' => $user->name,
                'email' => 'john@warcrepository.com',
                'created_at' => $user->created_at->format('d-m-Y'),
            ]]);
    }

    public function test_token_query_parameter_is_accepted(): void
    {
        $token = $this->login();

        $this->getJson('/api/me?token='.$token)->assertOk();
    }

    public function test_tokens_issued_by_the_old_app_still_work(): void
    {
        $user = $this->user();

        $this->getJson('/api/me', ['Authorization' => 'Bearer '.$this->legacyToken($user)])
            ->assertOk()
            ->assertJsonPath('data.id', $user->uuid);
    }

    public function test_users_returns_collection(): void
    {
        $token = $this->login();
        User::factory()->count(2)->create();

        $response = $this->getJson('/api/users', ['Authorization' => "Bearer $token"]);

        $response->assertOk()->assertJsonCount(3, 'data');
        $this->assertSame(['id', 'name', 'email', 'created_at'], array_keys($response->json('data.0')));
    }

    public function test_protected_routes_without_token(): void
    {
        foreach (['/api/me', '/api/users'] as $uri) {
            $this->getJson($uri)
                ->assertStatus(401)
                ->assertHeader('WWW-Authenticate', 'dingo')
                ->assertExactJson([
                    'message' => 'Failed to authenticate because of bad credentials or an invalid authorization header.',
                    'status_code' => 401,
                ]);
        }

        $this->postJson('/api/corpus')->assertStatus(401);
    }

    public function test_expired_token(): void
    {
        $user = $this->user();

        $this->getJson('/api/me', ['Authorization' => 'Bearer '.$this->legacyToken($user, -10)])
            ->assertStatus(401)
            ->assertExactJson(['message' => 'Token has expired', 'status_code' => 401]);
    }

    public function test_refresh_token(): void
    {
        $token = $this->login();

        $response = $this->getJson('/api/token', ['Authorization' => "Bearer $token"]);

        $response->assertOk();
        $this->assertSame(['token'], array_keys($response->json()));
        $this->assertNotSame($token, $response->json('token'));
    }

    public function test_refresh_without_token(): void
    {
        $this->getJson('/api/token')
            ->assertStatus(405)
            ->assertExactJson(['message' => 'Token is not valid', 'status_code' => 405]);
    }

    public function test_create_user(): void
    {
        $response = $this->postJson('/api/user', [
            'name' => 'Newuser',
            'email' => 'new@warcrepository.com',
            'password' => 'Secret_1',
        ]);

        $response->assertOk()
            ->assertJsonPath('data.name', 'Newuser')
            ->assertJsonPath('data.email', 'new@warcrepository.com');

        $user = User::where('email', 'new@warcrepository.com')->first();
        $this->assertTrue(password_verify('Secret_1', $user->password));
        $this->assertMatchesRegularExpression('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/', $user->uuid);
    }

    public function test_unknown_api_route_uses_dingo_error_format(): void
    {
        $this->getJson('/api/nothing-here')
            ->assertStatus(404)
            ->assertJsonStructure(['message', 'status_code']);
    }
}
