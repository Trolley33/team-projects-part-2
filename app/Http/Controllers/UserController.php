<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = array(
            'title' => "User information page.",
            'desc' => "Displays information on user accounts.",
            'info' => User::all());
        return view('users.index')->with($data);
    }


    /**
     * Show the form for creating a new caller resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_caller()
    {
        $departments = DB::table('departments')->select('departments.*')->where('departments.id', '!=', '1')->get();

        $jobs = DB::table('jobs')->select('jobs.*')->where('jobs.access_level', '=', '0')->get();

        $data = array(
            'title' => "Create New Caller Account",
            'desc' => "For creating employee accounts.",
            'departments' => $departments,
            'jobs' => $jobs
        );
        return view('users.create_caller')->with($data);
    }

    /**
     * Show the form for creating a new tech resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_tech_support()
    {
        $jobs = DB::table('jobs')->select('jobs.*')->where('jobs.access_level', '!=', '0')->get();

        $data = array(
            'title' => "Create New Tech Support Account",
            'desc' => "For creating system related accounts.",
            'jobs' => $jobs
        );
        return view('users.create_tech_support')->with($data);
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
}
