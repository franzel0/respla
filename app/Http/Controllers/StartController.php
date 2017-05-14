<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\database\factories\ModelFactory;
use App\Company;
use App\Department;
use App\Position;
use App\Entry;
use App\Section;
use App\User;
use DB;
use Storage;


class StartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$options to fill company list
        $options = \App\Company::where('id', '>', 1)->orderBy('name')->lists('name', 'id');

        return view('start', compact('options'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $company_names = ['Waldklinik', 'St. Stefanus Hospital', 'Staedtische Kliniken', 'Ev. Hospital', 'Wotan Stift'];
        $department_names =['Chirurgie', 'Innere Medizin', 'Anästhesie', 'Gynäkologie', 'Urologie', 'Orthopädie'];
        $position_names = ['Chefarzt', 'Oberarzt', 'Stationsarzt', 'Student', 'Casemanager'];
        $section_names = ['Station1', 'Station2', 'Station3', 'Station4', 'Ambulanz', 'Op'];
        $entry_names =   [0 => ['name' => 'urlaub',
                                'bgcolor' => '#4C1208',
                                'textcolor' => '',
                                'shorttext' => 'U',
                                'right' => 1,
                                'present' => 0,
                                'onweekend' => 0,],
                            1 => ['name' => 'Krank',
                                'bgcolor' => '#69682B',
                                'textcolor' => '',
                                'shorttext' => 'K',
                                'right' => 0,
                                'present' => 0,
                                'onweekend' => 0,],
                            2 => ['name' => 'Fortbildung',
                                'bgcolor' => '#E0A84F',
                                'textcolor' => '',
                                'shorttext' => 'FB',
                                'right' => 1,
                                'present' => 0,
                                'onweekend' => 0,],
                            3 => ['name' => 'Termin',
                                'bgcolor' => '',
                                'textcolor' => '#948D82',
                                'shorttext' => 'T',
                                'right' => 0,
                                'present' => 1,
                                'onweekend' => 0,],
                            4 => ['name' => 'Ambulanz',
                                'bgcolor' => '',
                                'textcolor' => '#647D77',
                                'shorttext' => 'A',
                                'right' => 0,
                                'present' => 1,
                                'onweekend' => 0,]];
        for ($c=1; $c <= 1; $c++) {
            $company =  new Company;
            $company->name = $company_names[rand(0,4)];
            //$companies = $companies->add($company);
            $company->save();

            //create department_names
            for ($d=0; $d <=rand(3,5)  ; $d++) {
                $department = new Department;
                $department->name = $department_names[$d];
                $department->active = 1;
                $company->departments()->save($department);

                //create positions
                $positions = new \Illuminate\Database\Eloquent\Collection;
                for ($p=0; $p < 5; $p++) {
                    $position = new Position;
                    $position->name = $position_names[$p];
                    $position->priority = $p+1;
                    $department->positions()->save($position);
                    $positions = $positions->add($position);
                }

                //create sections
                $sections = new \Illuminate\Database\Eloquent\Collection;
                for ($s=0; $s < 5; $s++) {
                    $section = new Section;
                    $section->fullname = $section_names[$s];
                    $section->shortname = substr($section->fullname, 0, 2);
                    $department->sections()->save($section);
                    $sections = $sections->add($section);
                }

                //create entries
                $entries = new \Illuminate\Database\Eloquent\Collection;
                for ($e=0; $e < 5; $e++) {
                    $entry = new Entry;
                    $entry->name = $entry_names[$e]['name'];
                    $entry->shorttext = $entry_names[$e]['shorttext'];
                    $entry->present = $entry_names[$e]['present'];
                    $entry->right = $entry_names[$e]['right'];
                    $entry->onweekend = $entry_names[$e]['onweekend'];
                    $entry->bgcolor = $entry_names[$e]['bgcolor'];
                    $entry->textcolor = $entry_names[$e]['textcolor'];
                    $department->entries()->save($entry);
                    $entries = $entries->add($entry);
                }

                //create users
                $users = new \Illuminate\Database\Eloquent\Collection;
                for ($u=0; $u < rand(5,10); $u++) {
                    $user = factory(\App\User::class)->make();
                    $user->position_id = $positions[rand(0,4)]['id'];
                    $user->section_id = $sections[rand(0,4)]['id'];
                    $department->users()->save($user);

                    //add a role
                    $role = \App\Role::find(rand(2,4));
                    $user->attachRole($role);

                    //create events
                    $events = factory(\App\Event::class, 100)->make()->unique('date');
                    foreach($events as $e) {
                        $e->entry_id = $entries->random()->id;
                    };
                    $user->events()->saveMany($events);
                }
            }
        }
        //Write the demo data to democompany.json and demousers.json
        //$company = \App\Company::find(44);
        if (Storage::exists('democompany.json'))
        {
            Storage::delete('democompany.json');
        }
        Storage::put('democompany.json', $company->toJson());
        if (Storage::exists('demousers.json'))
        {
            Storage::delete('demousers.json');
        }
        $file = new \Illuminate\Database\Eloquent\Collection;
        $file = $file->add($company->sameCompanyUsers()->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')->where('role_user.role_id', 2)->first());
        $file = $file->add($company->sameCompanyUsers()->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')->where('role_user.role_id', 3)->first());
        $file = $file->add($company->sameCompanyUsers()->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')->where('role_user.role_id', 4)->first());
        Storage::put('demousers.json', $file);

        //$options to fill company list
        $options = \App\Company::where('id', '>', 1)->orderBy('name')->lists('name', 'id');

        return view('start', compact('company', 'options'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $this->validate($request, [
            'company' => 'required|exists:companies,id',
        ]);

        $deletedcompany = \App\Company::find($request->company);
        $departments = \App\Company::find($request->company)->departments()->get();

        foreach ($departments as $d){
        $users = $d->users;
        foreach ($users as $user) {
            // delete events
            DB::table('events')->where('user_id', $user->id)->delete();
            //delete role
            DB::table('role_user')->where('user_id', $user->id)->delete();
            //and finally delete user him/herself
            $user->delete();
        }
        $d->positions()->delete();
        $d->sections()->delete();
        $d->entries()->delete();
        }
        \App\Company::find($request->company)->delete();
    $info = "Gelöscht!";
    //$options to fill company list
    $options = \App\Company::where('id', '>', 1)->orderBy('name')->lists('name', 'id');

    return view('start', compact('options', 'deletedcompany', 'info'));
    }
}
