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
        // If user is allowed to view this page.
        if (PagesController::hasAccess(1))
        {
            // Using left-joins and IFNULL columns, get all departments and the number of people employed by each department.
            $info = Department::leftJoin('jobs', 'departments.id', '=', 'jobs.department_id')->leftJoin('users', 'jobs.id', '=', 'users.job_id')->selectRaw('departments.id, departments.name, IFNULL(COUNT(users.id),0) as employees')->groupBy('departments.id')->get();

            // Supply data to view.
            $data = array(
                'title' => "Department Info Page.",
                'desc' => "Displays information on departments.",
                'info' => $info,
                'links' => PagesController::getOperatorLinks(),
                'active' => 'Departments'
            );

            return view('departments.index')->with($data);
        }

        // No access redirects to login page.
        return redirect('login')->with('error', 'Please log in first.');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // If user is allowed to view this page.
        if (PagesController::hasAccess(1))
        {
            // Supply data to view.
            $data = array(
                'title' => "Create New Department",
                'desc' => "For making a new department catagory.",
                'links' => PagesController::getOperatorLinks(),
                'active' => 'Departments'
            );

            return view('departments.create')->with($data);
        }
        // No access redirects to login page.
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
        // If user is allowed to view this page.
        if (PagesController::hasAccess(1))
        {            
            // If all required data has been submitted.
            $this->validate($request, [
                'deptName' => 'required'
            ]);
            // Check that no departments with given name already exist.
            $department = Department::where('name', $request->input('deptName'))->get();

            if (count($department) == 0)
            {
                // Create new department object and supply required data.
                $newDepartment = new Department();
                $newDepartment->name = $request->input('deptName');
                $newDepartment->save();
                return redirect('/departments')->with('success', 'Department Added');
            }
            // If duplicate department found, redirect.
            $data = array(
                'error'=>'Duplicate Department Name',
                'search'=>$request->input('deptName')
            );

            return redirect('/departments')->with($data);
        }
        // No access redirects to login page.
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
        // If user is allowed to view this page.
        if (PagesController::hasAccess(1))
        {
            // Finds department using given ID.
            $department = Department::find($id);
            if (!is_null($department))
            {
                // Using left-joins and IFNULL columns, select all jobs in the department, and number of people employed in each job.
                $jobs = Department::leftJoin('jobs', 'departments.id', '=', 'jobs.department_id')->leftJoin('users', 'jobs.id', '=', 'users.job_id')->selectRaw('jobs.id, jobs.title, IFNULL(COUNT(users.id),0) as employees')->where('jobs.department_id', '=', $id)->groupBy('jobs.id')->get();

                // Supply data to view.
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
            // If no department found, redirect to departments page.
            return redirect('/departments')->with('error', 'Department not found.');
        }
        // No access redirects to login page.
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
        // If user is allowed to view this page.
        if (PagesController::hasAccess(1))
        {
            // Find department using given ID.
            $department = Department::find($id);
            if (!is_null($department))
            {
                // Supply data to view.
                $data = array(
                    'title' => "Edit Existing Department",
                    'desc' => "For making a new department catagory.",
                    'department'=>$department,
                    'links' => PagesController::getOperatorLinks(),
                    'active' => 'Departments'
                );

                return view('departments.edit')->with($data);
            }
            // If no department found, redirect to departments page.
            return redirect('/departments')->with('error', 'Department not found.');
        }
        // No access redirects to login.
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
        // If user is allowed to view this page.
        if (PagesController::hasAccess(1))
        {            
            // Validate that all required data was submitted.
            $this->validate($request, [
                'deptName' => 'required'
            ]);

            // Check for existing departments with new name.
            $department = Department::where('name', $request->input('deptName'))->get();
            if (count($department) == 0)
            {
                // Find department using supplied ID.
                $newDepartment = Department::find($id);
                if (!is_null($newDepartment))
                {
                    // Update details.
                    $newDepartment->name = $request->input('deptName');
                    $newDepartment->save();
                    return redirect('/departments')->with('success', 'Department Updated');
                }

                return redirect('/departments')->with('error', 'Department not found.');
            }
            // Duplicate department name shows user where existing department already is using 'search'.
            $data = array(
                'error'=>'Duplicate Department Name',
                'search'=>$request->input('deptName')
            );

            return redirect('/departments')->with($data);
        }
        // No access redirects to login.
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
        // If user is allowed to view this page.
        if (PagesController::hasAccess(1))
        {
            // Find department using supplied ID.
            $department = Department::find($id);
            $department->delete();

            // Get all jobs related to department, and delete them.
            $jobs = Job::join('users', 'users.job_id', '=', 'jobs.id')->where('jobs.department_id', $id)->delete();

            return redirect('/departments')->with('success', 'Department Deleted');
        }
        // No access redirects to login.
        return redirect('login')->with('error', 'Please log in first.');
    }
}
