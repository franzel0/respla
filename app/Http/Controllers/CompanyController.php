<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\CompanyAdminRequest;
use App\Http\Controllers\Controller;
use Session;
use App\Classes\lists;
use Auth;
use DB;

class CompanyController extends Controller
{
    /*
    * Inject AdminRequest to authorize user
    *
    private $request;

    public function __construct(CompanyAdminRequest $request)
    {
        $this->request = $request;
    }  


     /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(CompanyAdminRequest $request)
    {
        $company = \App\Company::find(Auth::user()->company->id);

        $company_list = Lists::companies();

        $companycustomdatesitems = \App\Company::find(Auth::user()->department->company->id)->customdatesitems;

        $customdates = \App\CustomDatesItems::all();

        return view('admin/company', compact('company_list', 'company', 'customdates', 'companycustomdatesitems'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(CompanyAdminRequest $request)
    {
        $company_list = Lists::companies();

        $companycustomdatesitems = collect([0]);

        $customdates = \App\CustomDatesItems::all();

        return view('admin/company', compact('company_list', 'customdates', 'companycustomdatesitems'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(CompanyAdminRequest $request)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);

        $input = $request->all();

        \App\Company::create($input);

        Session::flash('flash_message', 'Klinik hinzugefügt!');

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(CompanyAdminRequest $request, $id)
    {
        $company_list = Lists::companies();

        $company = \App\Company::find($id);

        $companycustomdatesitems = \App\Company::find($id)->customdatesitems;

        $customdates = \App\CustomDatesItems::all();

        return view('admin/company', compact('company_list', 'company', 'customdates', 'companycustomdatesitems'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(CompanyAdminRequest $request, $id)
    {
        $formcompany = \App\Company::findOrFail($id);

        return view('admin/company')->withFormcompany($formcompany);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(CompanyAdminRequest $request, $id)
    {
        $company = \App\Company::findOrFail($id);

        $this->validate($request, [
            'name' => 'required'
        ]);
    
        $input = $request->all();
    
        $company->fill($input)->save();

        $input = $request->only('customdate');
        $customdates = $input['customdate'];

        $h = "Ach: ";
        for ($i=1; $i <= 20; $i++) { 
            if (isset($customdates[$i]))
            {
                // ->attach simply adds eintry without checking if it already exists. so this will bie checked before ->attach is executed
                $result = DB::table('companies_customdatesitems')->where('companies_id', '=', $company->id)->where('customdatesitems_id', '=', $i)->count();
                if ($result == 0)
                {
                    $company->customdatesitems()->attach($i);
                }
            }
            else
            {
                $company->customdatesitems()->detach($i);  
                
            }
        }

        //  return $h;
        

        Session::flash('flash_message', 'Klinik geändert!');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(CompanyAdminRequest $request, $id)
    {
        //
    }
}
