<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use App\User;
use App\Job;
use App\Department;

class DepartmentController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (PagesController::hasAccess(1))
        {
            $info = Department::leftJoin('jobs', 'departments.id', '=', 'jobs.department_id')->leftJoin('users', 'jobs.id', '=', 'users.job_id')->selectRaw('departments.id, departments.name, IFNULL(COUNT(users.id),0) as employees')->groupBy('departments.id')->get();

            $data = array(
                'title' => "Department Info Page.",
                'desc' => "Displays information on departments.",
                'info' => $info,
                'links' => PagesController::getOperatorLinks(),
                'active' => 'Departments'
            );

            return view('departments.index')->with($data);
        }

        return redirect('login')->with('error', 'Please log in first.');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (PagesController::hasAccess(1))
        {
            $data = array(
                'title' => "Create New Department",
                'desc' => "For making a new department catagory.",
                'links' => PagesController::getOperatorLinks(),
                'active' => 'Departments'
            );

            return view('departments.create')->with($data);
        }
        return redirect('login')->with('error', 'Please log in first.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (PagesController::hasAccess(1))
        {            
            $this->validate($request, [
                'deptName' => 'required'
            ]);

            $department = Department::where('name', $request->input('deptName'))->get();

            if (count($department) == 0)
            {
                $newDepartment = new Department();
                $newDepartment->name = $request->input('deptName');
                $newDepartment->save();
                return redirect('/departments')->with('success', 'Department Added');
            }

            $data = array(
                'error'=>'Duplicate Department Name',
                'search'=>$request->input('deptName')
            );

            return redirect('/departments')->with($data);
        }
        return redirect('login')->with('error', 'Please log in first.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (PagesController::hasAccess(1))
        {
            $department = Department::find($id);

            $jobs = Department::leftJoin('jobs', 'departments.id', '=', 'jobs.department_id')->leftJoin('users', 'jobs.id', '=', 'users.job_id')->selectRaw('jobs.id, jobs.title, IFNULL(COUNT(users.id),0) as employees')->where('jobs.department_id', '=', $id)->groupBy('jobs.id')->get();

            $data = array(
                'title' => $department->name,
                'desc' => "View information on a department.",
                'department' => $department,
                'jobs' => $jobs,
                'links' => PagesController::getOperatorLinks(),
                'active' => 'Departments'
            );

            return view('departments.show')->with($data);
        }

        return redirect('login')->with('error', 'Please log in first.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (PagesController::hasAccess(1))
        {
            $department = Department::find($id);
            if (!is_null($department))
            {
                $data = array(
                    'title' => "Edit Existing Department",
                    'desc' => "For making a new department catagory.",
                    'department'=>$department,
                    'links' => PagesController::getOperatorLinks(),
                    'active' => 'Departments'
                );

                return view('departments.edit')->with($data);
            }
            return redirect('/departments');
        }
        return redirect('login')->with('error', 'Please log in first.');
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
        if (PagesController::hasAccess(1))
        {            
            $this->validate($request, [
                'deptName' => 'required'
            ]);

            $department = Department::where('name', $request->input('deptName'))->get();

            if (count($department) == 0)
            {
                $newDepartment = Department::find($id);
                $newDepartment->name = $request->input('deptName');
                $newDepartment->save();
                return redirect('/departments')->with('success', 'Department Updated');
            }

            $data = array(
                'error'=>'Duplicate Department Name',
                'search'=>$request->input('deptName')
            );

            return redirect('/departments')->with($data);
        }
        return redirect('login')->with('error', 'Please log in first.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (PagesController::hasAccess(1))
        {
            $department = Department::find($id);
            $department->delete();

            $jobs = Job::join('users', 'users.job_id', '=', 'jobs.id')->where('jobs.department_id', $id)->delete();

            return redirect('/departments')->with('success', 'Department Deleted');
        }
        return redirect('login')->with('error', 'Please log in first.');
    }
}
