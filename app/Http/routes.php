<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function()
{
    if (Auth::check())
    {

        return redirect('/month');
    }
    else
    {
        return view('auth/login');
    }
});

// Authentication routes...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

// Registration routes...
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');
Route::get('register/verify/{confirmationCode}', [
    'as' => 'confirmation_path',
    'uses' => 'RegistrationController@confirm'
]);

Route::get('impressum', ['as' => 'impressum', function(){
    return view('impressum');
}]);

Route::get('screenshots', ['as' => 'screenshots', function(){
    return view('screenshots');
}]);

Route::get('demo', ['as' => 'demo', function(){
    return view('demo');
}]);

// Password reset link request routes...
Route::get('password/email', 'Auth\PasswordController@getEmail');
Route::post('password/email', 'Auth\PasswordController@postEmail');

// Password reset routes...
Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
Route::post('password/reset', 'Auth\PasswordController@postReset');

Route::group(['middleware' => 'auth'], function () {

    Route::resource('password', 'PasswordController', ['only' => ['show', 'update']]);

    Route::resource('company', 'CompanyController');

    Route::resource('company.department', 'DepartmentController');

    Route::resource('company.oncall', 'OnCallController', ['only' => ['index', 'create', 'store', 'edit', 'update']]);

    Route::get('company/{company}/oncallsorder', ['as' => 'company.oncallssorder', 'uses' => 'OnCallController@show']);

    Route::get('company/{company}/overview', ['as' => 'overviewOncalls', 'uses' => 'ViewOnCallController@index']);

    Route::post('/changeOncallOrder', 'OnCallController@changeOncallOrder');

    Route::resource('role', 'RoleController');

    Route::resource('permission', 'PermissionController');

    Route::post('getdepartmentlist', ['as' => 'getdepartmentlist', 'uses' => 'PositionController@getdepartmentlist']);

    Route::resource('company.department.position', 'PositionController', ['only' => ['index', 'create', 'show','store', 'edit', 'update']]);

    Route::post('/changeOrder', 'PositionController@changeOrder');

    Route::resource('company.department.section', 'SectionController', ['only' => ['index', 'create', 'show','store', 'edit', 'update']]);

    Route::resource('company.department.user', 'UserController', ['only' => ['index', 'create', 'show','store', 'edit', 'update']]);

    Route::resource('password', 'PasswordController', ['only' => ['index', 'update']]);

    Route::resource('company.department.entry', 'EntryController', ['only' => ['index', 'create', 'show', 'store', 'edit', 'update']]);

    Route::resource('company.department.holiday', 'HolidayController', ['only' => ['index', 'create', 'store', 'edit', 'update']]);

    Route::resource('company.department.comment', 'CommentController', ['only' => ['index', 'create', 'store', 'edit', 'update']]);

    Route::resource('company.department.blog', 'CommentController', ['only' => ['index', 'create', 'store', 'edit', 'update']]);

    Route::resource('company.department.info', 'InfoController', ['only' => ['index']]);

    Route::get('pdf/month/{department}', [ 'as' => 'pdfmonth', 'uses' => 'PdfController@month' ]);

    Route::get('pdf/day/{department}', [ 'as' => 'pdfday', 'uses' => 'PdfController@day' ]);

    Route::get('admin/settings', function(){
        if (Auth::user()->hasRole('admin')){
            return view('admin/settings');
        };
        return view('errors/forbidden');
    });

    Route::get('home', 'EventController@showMonth');

    Route::get('forbidden', [ 'as' => 'forbidden', function(){
        //here i need to insert the logout-command !!!!!
        return view('errors/forbidden');
    }]);

    Route::get('month', 'EventController@showMonth');
    Route::post('month', 'EventController@showMonth');

    Route::get('day', 'EventController@showDay');
    Route::post('day', 'EventController@showDay');

    Route::get('week', 'EventController@showWeek');
    Route::post('week', 'EventController@showWeek');

    Route::post('stats', 'StatsController@index');
    Route::get('stats', 'StatsController@index');


    Route::get('planDepartment', 'PlanController@indexDepartment');
    Route::post('planDepartment', 'PlanController@indexDepartment');

    Route::get('planCompany', 'PlanController@indexCompany');
    Route::post('planCompany', 'PlanController@indexCompany');

    Route::post('insertPlanEvent', 'PlanController@insertPlanEvent');
    Route::post('deletePlanEvent', 'PlanController@deletePlanEvent');
    Route::post('updateUserEvents', 'PlanController@updateUserEvents');
    Route::post('updateStats', 'PlanController@updateStats');
    Route::post('approvePlanEvents', 'PlanController@approvePlanEvents');

    Route::get('start', 'StartController@index');
    Route::post('start', 'StartController@destroy');
    Route::get('start.create', 'StartController@create');

    //routes for Ajax-functions
    Route::post('insertEvents', 'EventController@insertEvents');
    Route::post('modalinsertEvents', 'EventController@modalinsertEvents');
    Route::post('modalChangeUser', 'ViewOnCallController@changeUser');
    Route::post('modalGetUsers', 'ViewOnCallController@GetUsers');
    Route::post('modalUserHasEvent', 'ViewOnCallController@modalUserHasEvent');

    Route::post('insertcomment', 'CommentController@insertcomment');

    Route::get('test',function () {
        //Vergleich der verschiedenene Abfragen MySql Abfrage gegenübergestellt!!!
        $events = \App\Event::all();
        $result = "nix!";
        $time_start = microtime(true);
        if($ev = $events->where('date', '2016-03-18')->where('user_id', '1115') and $ev->count()>0) {
            $result1 = $ev->first()->user->lastname;
        }
        $time_end = microtime(true);
        $time1 = $time_end - $time_start;

        $time_start = microtime(true);
        if($ev = $events->filter(function($item) { if($item->date == '2016-03-18' && $item->user_id == '1115') return true;}) and $ev->count()>0) {
            $result2 = $ev->first()->user->lastname;
        }
        $time_end = microtime(true);
        $time2 = $time_end - $time_start;

        $time_start = microtime(true);
        $event = \App\Event::where('date', '=', '2016-03-18')
                            ->where('user_id', '=', '1115')
                            ->get();//dd($event);;
        $result3 = $event->first()->user->lastname;
        $time_end = microtime(true);
        $time3 = $time_end - $time_start;

        return $result1.", where Zeit: ".$time1." / ".$result2.", filter Zeit: ".$time2." / ".$result3.", MySql Zeit: ".$time3;
    });

});

Route::get('/entrust', function()
{
    /*
     * Neuanlage
    */
    /*$admin = new \App\Role();
    $admin->name = 'admin';
    $admin->save();

    $companyadmin = new \App\Role();
    $companyadmin->name = 'companyadmin';
    $companyadmin->save();

    $localadmin = new \App\Role();
    $localadmin->name = 'localadmin';
    $localadmin->save();

    $user = new \App\Role();
    $user->name = 'user';
    $user->save();

    $guest = new \App\Role();
    $guest->name = 'guest';
    $guest->save();

	$changeevents = new \App\Permission();
    $changeevents->name = 'changeevents';
    $changeevents->display_name = 'Can changeevents';
    $changeevents->save();

    $changeownevents = new \App\Permission();
    $changeownevents->name = 'changeownevents';
    $changeownevents->display_name = 'Can changeownevents';
    $changeownevents->save();

    $changeroles = new \App\Permission();
    $changeroles->name = 'changeroles';
    $changeroles->display_name = 'Can changeroles';
    $changeroles->save();

    $changepermissions = new \App\Permission();
    $changepermissions->name = 'changepermissions';
    $changepermissions->display_name = 'Can changepermissions';
    $changepermissions->save();

    $changedepartment = new \App\Permission();
    $changedepartment->name = 'changedepartment';
    $changedepartment->display_name = 'Can changedepartment';
    $changedepartment->save();

    $changehospital = new \App\Permission();
    $changehospital->name = 'changehospital';
    $changehospital->display_name = 'Can changehospital';
    $changehospital->save();

    $edituser = new \App\Permission();
    $edituser->name = 'edituser';
    $edituser->display_name = 'Can edituser';
    $edituser->save();

    $login = new \App\Permission();
    $login->name = 'login';
    $login->display_name = 'Can login';
    $login->save();

    $admin->attachPermission(array($changeevents, $changeownevents, $changeroles, $changepermissions, $changehospital, $changedepartment, $edituser, $login ));
    $companyadmin->attachPermission(array($changeevents, $changeownevents, $changedepartment, $edituser, $login ));
    $localadmin->attachPermission(array($changeevents, $changeownevents, $edituser, $login));
    $user->attachPermission($changeownevents, $login);
    $guest->attachPermission($login);*/

    /*
     * Ändern von existierenden Rollen und Permissions (Voraussetzung user (1), user(2) existieren))
    */
    /*$changeevents = \App\Permission::find(1);
    $changeownevents = \App\Permission::find(2);
    $changeroles = \App\Permission::find(3);
    $changepermissions = \App\Permission::find(4);
    $changehospital = \App\Permission::find(5);
    $changedepartment = \App\Permission::find(6);
    $edituser = \App\Permission::find(7);
    $login = \App\Permission::find(8);

    \App\Role::findorfail(1)->attachPermissions(array($changeevents, $changeownevents, $changeroles, $changepermissions, $changehospital, $changedepartment, $edituser, $login ));
    \App\Role::findorfail(2)->attachPermissions(array($changeevents, $changeownevents, $changedepartment, $edituser, $login ));
    \App\Role::findorfail(3)->attachPermissions(array($changeevents, $changeownevents, $edituser, $login));
    \App\Role::findorfail(4)->attachPermissions($changeownevents, $login);
    \App\Role::findorfail(5)->attachPermission($login);

    $user1 = \App\User::find(1);
    $user2 = \App\User::find(2);

    $admin = \App\Role::findorfail(1);
    $userrole = \App\Role::findorfail(4);

    $user1->attachRole($admin);
    $user2->attachRole($userrole);

    $changesettings = new \App\Permission();
    $changesettings->name = 'changesettings';
    $changesettings->display_name = 'Can changesettings';
    $changesettings->save();

    \App\Role::findorfail(1)->attachPermissions(array($changesettings));
    \App\Role::findorfail(2)->attachPermissions(array($changesettings));
    \App\Role::findorfail(3)->attachPermissions(array($changesettings));

    return 'Woohoo!';*/
});
