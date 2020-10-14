<?php

namespace App\Http\Controllers;

use App\Transformers\UserTransformer;
use App\User;
use App\Corpus;
use Dingo\Api\Http\Response;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Webpatser\Uuid\Uuid;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    use Helpers;

    /**
     * Search the corpus from the auth user and pass the variables to the user.index view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        if(auth()->user()->role === 'admin') {
            $users = User::where('users.role','!=','admin')->get();
            return view('user.admin.index', compact('users'));
        }else{
            $publicCorpus = \DB::select('SELECT * FROM corpus WHERE user_id = ? AND status = ?',[auth()->user()->id,'public']);
            $privateCorpus = \DB::select('SELECT * FROM corpus WHERE user_id = ? AND status = ?',[auth()->user()->id,'private']);
            $trashCorpus = \DB::select('SELECT * FROM corpus WHERE user_id = ? AND status = ?',[auth()->user()->id,'trash']);
            return view('user.index',compact(['publicCorpus','privateCorpus','trashCorpus']));
        }
    }

    /**
     * redirect to user.show view of a particular user
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(User $user){
        $corpus = $user->corpus()->where('status','=','public')->get();
        if($user->id === auth()->user()->id){
            return $this->index();
        }else{
            return view('user.show', compact(['user','corpus']));
        }
    }

    /**
     * Test method to create a user
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Response User created
     */
    protected function create(Request $request)
    {
        $data = $request;
        try {
            $data['uuid'] = Uuid::generate(4)->string;// UUID type 4, random generation
        } catch (\Exception $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        $data['password'] = bcrypt($request->input('password'));
        $user = User::create($data->all());
        return $this->response->item($user, new UserTransformer())->setStatusCode(200);
    }

    /**
     * Return a list with all the users
     */
    public function users()
    {
        return $this->response->collection(User::all(), new UserTransformer());
    }

    /**
     * Return the current user
     */
    public function me()
    {
        try {
            $user = JWTAuth::parseToken()->toUser();

            if (!$user) {
                return $this->response->errorUserNotFound('User not found');
            }
        } catch (TokenInvalidException $e) {
            return $this->response->error("Token is invalid", 401);
        } catch (TokenExpiredException $e) {
            return $this->response->error("Token has expired", 401);
        } catch (TokenBlackListedException $e) {
            return $this->response->error("Token is blacklisted", 401);
        }
        return $this->response->item($user, new UserTransformer)->setStatusCode(200);
    }

    /**
     * Update de attributes of a user
     * @param User $user Instance of a model User you want to modify
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(User $user, Request $request)
    {
        if($user == auth()->user()){
            $this->validate($request, [
                'name' => array('min:6','required','max:15','regex:/(^[A-Za-z0-9 ]+$)+/'),
                'email' => 'required|email|max:255',
                'password' => array('min:6','regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/'),
                'institution' => 'min:3|max:40',
            ],['password.regex' => 'Password must contains at less uppercase/lowercase letters, a number and a symbol',
                'name.regex' => 'user_name can only contain letters and numbers',
            ]);

            if($request->get('name') != $user->name){
                if(User::where('name', $request->get('name'))->first()){
                    $request->session()->flash('unique_user', 'User name must to be unique');
                    return redirect('user/edit');
                }
            }
            if ($request->hasFile('photoProfile')) {
                $destinationPath = 'images/uploads/';
                if(Storage::disk('local')->exists($user->photo)){
                    if(Storage::disk('local')->delete($destinationPath . $user->photo)){
                        $request->session()->flash('user_photo', 'The old image profile was deleted ' . $user->photo);
                    }else{
                        $request->session()->flash('user_photo', 'The old image profile could no be deleted ' . $user->photo);
                    }
                }
                $file = $request->file('photoProfile');
                $filename = md5(microtime() . $file->getClientOriginalName()) . "." . $file->getClientOriginalExtension();
                $file->move($destinationPath, $filename);
                $request->offsetSet('photo', $filename);
            }
            if(!empty($request->get('old_password'))) {
                if (Hash::check($request->get('old_password'), $user->getAuthPassword()) && ($request->get('password') === $request->get('password_confirmation'))) {
                    $user->password = bcrypt($request->get('password'));
                    if ($user->save()) {
                        $request->session()->flash('password_warning', 'Passwords updated successful!');
                    }
                } else {
                    $request->session()->flash('password_warning', 'Passwords do not match!');
                }
            }
            $user->update($request->except('password'));
        }
        return redirect('user/edit');

    }

    /**
     * redirect to user.edit view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(){
        return view('user.edit');
    }

    /**
     * delete a User redirect to user.home view
     * @param User $user instance of a model User you want to delete
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function delete(User $user){
        $user->delete();
        return redirect('user/home');
    }

    /**
     * Ban a user redirect to user.home view
     * @param User $user instance of a model User you want to ban
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function ban(User $user){

        $user->verified = 0;
        $user->save();
        return redirect('user/home');
    }

    /**
     * unban a baned user and redirect to user.home view
     * @param User $user instance of a model User you want to unban
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function unban(User $user){
        $user->verified = 1;
        $user->save();
        return redirect('user/home');
    }

    /**
     * unsuscribe a user and redirect to welcome page
     * @param User $user instance of a model User that wants to unsuscribe
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function unsuscribe(User $user){
        if($user == auth()->user()){
            $user->delete();
        }
        return redirect('/');
    }

    /**
     * restore the default data on demo users and redirect to user.home view
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function restartDemoUsers(){
        //TODO implements method to restart demo users to defalult values
        $john = User::where('email', 'john@warcrepository.com')->get();
        $jane = User::where('email', 'jane@warcrepository.com')->get();
        $corpuses = Corpus::where('user_id', '=', $john[0]->id)->orWhere('user_id', '=', $jane[0]->id)->get();
        foreach ($corpuses as $corpus) {
            $corpus->delete();
        }
        \Artisan::call('db:seed',array('--class' => 'CorpusSeeder'));
        return redirect('user/home');
    }

    /**
     * Searh users using the parameters of the request and redirect to user.list view
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function listUsers(Request $request){
        if($request->searchUsers == null) {
            $users = User::where('users.role','!=','admin')->where('users.name','!=',auth()->user()->name)->orderBy('name')->paginate(10);
        }else{
            $users = User::where('users.role','!=','admin')
                ->where('users.email','LIKE',"%{$request->searchUsers}%")
                ->orWhere('users.name','LIKE',"%{$request->searchUsers}%")->orderBy('name')->paginate(10);
        }
        return view('user.list', compact('users'));
    }
}
