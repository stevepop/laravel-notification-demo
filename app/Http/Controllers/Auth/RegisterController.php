<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserHasSignedUp;
use App\User;
use App\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * @var User
     */
    private $user;
    /**
     * @var Role
     */
    private $role;

    /**
     * Create a new controller instance.
     *
     * @param User $user
     * @param Role $role
     */
    public function __construct(User $user, Role $role)
    {
        $this->middleware('guest');
        $this->user = $user;
        $this->role = $role;
    }

    /**
     * Show Registration form
     */
    public function showRegistrationForm()
    {
        $roles = $this->role->orderBy('name')->pluck('name', 'id');

        return view('auth.register', compact('roles'));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|exists:roles,id', // validating role
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user = $this->user->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        $user->roles()->attach($data['role']);

        // Fire event!
        event(new UserHasSignedUp($user));

        return $user;
    }
}
