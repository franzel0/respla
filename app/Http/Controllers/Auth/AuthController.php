<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;

use App\Http\Requests;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Auth;
use Log;
use Mail;


class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    protected $username = 'name';

    protected $redirectPath = '/month';
    
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
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
            'company_name' => 'required|max:255',
            'department_name' => 'required|max:255',
            'section_fullname' => 'required|max:255',
            'section_shortname' => 'required|max:255',
            'position_name' => 'required|max:255',
            'entry_name' => 'required|max:255',
            'entry_shorttext' => 'required|max:255',
            'bgcolor' => 'required|max:7', // regex: /#([a-fA-F0-9]{3}){1,2}\b/
            'textcolor' => 'required|max:7',
            'name' => 'required|max:255|unique:users,name',
            'lastname' => 'required|max:255',
            'firstname' => 'required|max:255',
            'email' => 'required|email|max:255',//to be inserted later omitted für testing purposes |unique:users,email
            'password' => 'required|confirmed|min:6',
            'password_confirmation' => 'required|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    public function postLogin(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255', 
            'password' => 'required|max:60',
        ]);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        $throttles = $this->isUsingThrottlesLoginsTrait();

        if ($throttles && $this->hasTooManyLoginAttempts($request)) {

            return $this->sendLockoutResponse($request);
        }

        $credentials = array('name' => $request->name, 'password' => $request->password, 'active' => 1, 'confirmed' => 1);

        if (Auth::attempt($credentials, $request->has('remember'))) {
            
            Log::info('User: '.Auth::user()->id." logged in from: ".$request->getClientIp(true));
            return $this->handleUserWasAuthenticated($request, $throttles);
        }

        // Log unsuccessful logins / does not work

        Log::info('unsuccessful login attempt by: '.$request->name." from: ".$request->getClientIp(true));

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        if ($throttles) {
            $this->incrementLoginAttempts($request);
        }

        return redirect($this->loginPath())
            ->withInput($request->only($this->loginUsername(), 'remember'))
            ->withErrors(['message' => 'Etwas stimmt nicht. Bitte versuchen Sie es erneut.']);
    } 

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postRegister(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        $confirmation_code = str_random(30);

        //Auth::login($this->create($request->all()));
        
        // Creating the company
        $company = new \App\Company;

        $company->name = $request->company_name;

        $company->save();

        //Creating a department
        $department = new \App\Department;

        $department->name = $request->department_name;

        $newdepartment = $company->departments()->save($department);

        //Creating a section

        $section = new \App\Section;

        $section->fullname = $request->section_fullname;
        $section->shortname = $request->section_shortname;

        $newsection = $newdepartment->sections()->save($section);

        //Creating a position

        $position = new \App\Position;

        $position->name = $request->position_name;

        $newposition = $newdepartment->positions()->save($position);

        //Creating a new entry

        //First creat values for checkboxes
        if($request->has('right'))
        {
            $right = 1;
        }
        else
        {
            $right = 0;
        }

        if($request->has('present'))
        {
            $present = 1;
        }
        else
        {
            $present = 0;
        }

        if($request->has('onweekend'))
        {
            $onweekend = 1;
        }
        else
        {
            $onweekend = 0;
        }

        $entry = new \App\Entry([
            'name' => $request->entry_name,
            'bgcolor' => $request->bgcolor,
            'textcolor' => $request->textcolor,
            'shorttext' => $request->entry_shorttext,
            'right' => $right,
            'present' => $present,
            'onweekend' =>$onweekend,
            ]);

        $newdepartment->entries()->save($entry);
        
        //Creating User
        $user = new \App\User([
            'name' => $request->name,
            'lastname' => $request->lastname,
            'firstname' => $request->firstname,
            'company_id' => $company->id,
            'section' => $newsection->id,
            'position' => $newposition->id,
            'active' => 1,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'confirmation_code' => $confirmation_code
        ]);

        $newdepartment->users()->save($user);

        //Attach Role Company Admin to user
        $user->roles()->attach(2);

        //Confirmation Mail

        $data = array('email' => $request->email, 'name' => $request->name);

        Mail::send('emails.verify', ['confirmation_code' => $confirmation_code], function($message) use ($data) {
            $message->to($data['email'], $data['name'])
                ->subject('Bitte bestätigen Sie Ihre Anmeldung!');
        });

        $request->session()->flash('verification', 'Vielen Dank für Ihre Anmeldung. Bitte schauen Sie in Ihr E-Mail Fach, um die Registrieung abzuschliessen..');

        return redirect('/');
    }  
}
