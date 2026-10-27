<?php

namespace Tests\Feature;

use App\Corpus;
use App\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Las pantallas principales se siguen generando sin errores.
 */
class WebTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_public_pages(): void
    {
        $this->get('/')->assertOk()->assertSee('spamHamRatio-chart', false)->assertSee('google.visualization', false);
        $this->get('/login')->assertOk();
        $this->get('/registration')->assertOk();
        $this->get('/register/info')->assertOk();
        $this->get('/password/reset')->assertOk();
    }

    public function test_language_switch(): void
    {
        $this->from('/login')->get('/lang/es')->assertRedirect('/login');
        $this->assertSame('es', session('lang'));
    }

    public function test_login_by_name_or_email_and_logout(): void
    {
        $this->post('/login', ['name' => 'Jane', 'password' => 'secret'])->assertRedirect('/user/home');
        $this->assertAuthenticated();
        $this->get('/logout')->assertRedirect('/');
        $this->assertGuest();

        $this->post('/login', ['name' => 'jane@warcrepository.com', 'password' => 'secret'])->assertRedirect('/user/home');
        $this->assertAuthenticated();
    }

    public function test_unverified_users_cannot_log_in(): void
    {
        User::where('name', 'Jane')->update(['verified' => 0]);

        $this->post('/login', ['name' => 'Jane', 'password' => 'secret']);
        $this->assertGuest();
    }

    public function test_user_pages(): void
    {
        $jane = User::where('name', 'Jane')->first();
        $john = User::where('name', 'John')->first();

        $this->actingAs($jane);
        $this->get('/user/home')->assertOk();
        $this->get('/user/edit')->assertOk();
        $this->get('/user/list')->assertOk();
        $this->get('/user/list?searchUsers=john')->assertOk()->assertSee('John');
        $this->get('/user/show/'.$john->id)->assertOk();
        $this->get('/corpus/search-corpus')->assertOk();
        $this->get('/user/admin')->assertRedirect('/user/home');

        $corpus = Corpus::where('user_id', $jane->id)->first();
        $this->post('/corpus/filter-setup', ['corpus' => [$corpus->uuid]])->assertOk();
        $this->put('/corpus/update/'.$corpus->uuid, ['description' => 'Nueva'])->assertRedirect('/user/home');
        $this->assertSame('Nueva', $corpus->fresh()->description);
        $this->get('/corpus/deleteCorpus/'.$corpus->uuid)->assertRedirect('/user/home');
        $this->assertSame('trash', $corpus->fresh()->status);
    }

    public function test_admin_pages(): void
    {
        $this->actingAs(User::where('role', 'admin')->first());

        $this->get('/user/home')->assertOk()->assertSee('John');
        $john = User::where('name', 'John')->first();
        $this->get('/user/ban/'.$john->id)->assertRedirect('user/home');
        $this->assertSame(0, (int) $john->fresh()->verified);
    }

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get('/user/home')->assertRedirect('/login');
    }

    public function test_registration_sends_verification_mail(): void
    {
        \Mail::fake();

        $this->post('/register', [
            'name' => 'Newuser1',
            'email' => 'new@warcrepository.com',
            'password' => 'Secret_1',
        ])->assertRedirect('/register/info');

        \Mail::assertSent(\App\Mail\EmailVerification::class);
        $user = User::where('email', 'new@warcrepository.com')->first();
        $this->assertSame(0, (int) $user->verified);

        $this->get('/register/verify/'.$user->email_token)->assertRedirect('/user/home');
        $this->assertSame(1, (int) $user->fresh()->verified);
    }

    public function test_registration_validation_errors(): void
    {
        $this->from('/registration')->post('/register', ['name' => 'x', 'email' => 'bad', 'password' => 'a'])
            ->assertRedirect('/registration')
            ->assertSessionHasErrors(['name', 'email', 'password']);
    }

    public function test_password_reset_mail_link(): void
    {
        \Notification::fake();

        $this->post('/password/email', ['email' => 'jane@warcrepository.com'])->assertRedirect();

        $jane = User::where('name', 'Jane')->first();
        \Notification::assertSentTo($jane, \Illuminate\Auth\Notifications\ResetPassword::class, function ($notification) use ($jane) {
            $url = $notification->toMail($jane)->actionUrl;
            $this->assertSame(url('password/reset', $notification->token), $url);
            $this->get($url)->assertOk();

            return true;
        });
    }
}
