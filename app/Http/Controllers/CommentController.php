<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Classes\lists;
use Session;
use Auth;   
use Carbon;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($company_id, $department_id)
    {
        $company_list = Lists::companies();
        
        $department_list = \App\Company::find($company_id)->departments->sortBy('name')->lists('name', 'id');

        if($department_id == 0) $department_id = Lists::firstdepartment_id($company_id);

        $comments_list = Lists::comments($department_id);

        return view('comment', compact('company_list', 'company_id', 'department_list', 'department_id', 'comment_list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /*
     *
     * Insert the comment from Ajax-Request
     *
    */
    public function insertcomment(Request $request)
    {
        $comment = \App\Department::find(Auth::user()->department_id)->comments()->firstorNew(['id' => $request->id]);
        $comment->text = $request->text;
        $comment->date = $request->date;
        $comment->save();
        return; 
    }

}
