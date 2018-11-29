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
        $info = DB::table('users')->join('jobs', 'users.job_id', '=', 'jobs.id')->select('users.*', 'jobs.access_level')->get();

        $data = array(
            'title' => "User information page.",
            'desc' => "Displays information on user accounts.",
            'info' => $info
        );

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

        $this->validate($request, [
            'isCaller' => 'required',
            'empID' => 'required',
            'firstName' => 'required',
            'lastName' => 'required',
            'job-select' => 'required',
            'phone' => 'required'
        ]);


        // Caller account.
        if ($request->input('isCaller') == 'true')
        {
            $this->validate($request, [
                'department-select' => 'required',
            ]);

            $info = DB::table('users')->select('users.id')->where('users.employee_id', '=', $request->input('empID'))->get();

            if (count($info) ==  0)
            {
                $caller = new User;
                $caller->employee_id = $request->input('empID');
                $caller->username = '---';
                $caller->password = '';
                $caller->forename = $request->input('firstName');
                $caller->surname = $request->input('lastName');
                $caller->job_id = $request->input('job-select');
                $caller->phone_number = $request->input('phone');
                $caller->save();

                return redirect('/users')->with('success', 'Caller Added');
            }

            $data = array(
                'error'=>'Duplicate Employee ID',
                'search'=>$request->input('empID')
            );

            return redirect('/users')->with($data);
        }

        // System account.
        elseif ($request->input('isCaller') == 'false')
        {
            $this->validate($request, [
                'username' => 'required',
                'password' => 'required',
                'password2' => 'required'
            ]);


            $info = DB::table('users')->select('users.id')->where('users.employee_id', '=', $request->input('empID'))->get();

            if (count($info) ==  0)
            {
                $user = new User;
                $user->employee_id = $request->input('empID');
                $user->username = $request->input('username');
                $user->password = $request->input('password');
                $user->forename = $request->input('firstName');
                $user->surname = $request->input('lastName');
                $user->job_id = $request->input('job-select');
                $user->phone_number = $request->input('phone');
                $user->save();

                return redirect('/users')->with('success', 'Caller Added');
            }

            $data = array(
                'error'=>'Duplicate Employee ID',
                'search'=>$request->input('empID')
            );

            return redirect('/users')->with($data);
        }

        return "123";
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
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
