<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use App\User;
use App\Job;
use App\Department;
use App\Software;

class SoftwareController extends Controller
{

    public $operator_links = [
        ['href'=>'back','text'=>'back'],
        ['href'=>'operator','text'=>'Home'],
        ['href'=>'problems','text'=>'Problems'],
        ['href'=>'users','text'=>'Users'],
        ['href'=>'departments','text'=>'Departments'],
        ['href'=>'jobs','text'=>'Jobs'],
        ['href'=>'equipment','text'=>'Equipment'],
        ['href'=>'software','text'=>'Software'],
        ['href'=>'specialities','text'=>'Specialities']
    ];

    // Workaround function for authemtication.
    public function hasAccess($level)
    {
        if (isset($_COOKIE['csrf']))
        {
            $cookie = $_COOKIE['csrf'];
        }
        else
        {
            return false;
        }
        
        $result = DB::table('users')->select('users.id')->where('users.remember_token', '=', $cookie)->get();

        if (!is_null($result))
        {
            $id = $result->first()->id;
            $user = User::find($id);
            $job = Job::find($user->job_id);
            if ($job->access_level == $level)
            {
                return true;
            }
        }

        return false;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if ($this->hasAccess(1))
        {
            $software = Software::all();

            $data = array(
                'title' => "Software Information Viewer",
                'desc' => "View information on software.",
                'software' => $software,
                'links'=>$this->operator_links,
                'active'=>'Software'
            );

            return view('software.index')->with($data);
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
        if ($this->hasAccess(1))
        {
            $data = array(
                'title' => "Register New Software",
                'desc' => "For registering new software.",
                'links' => $this->operator_links,
                'active' => 'Software'
            );

            return view('software.create')->with($data);
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
        if ($this->hasAccess(1))
        {            
            $this->validate($request, [
                'name' => 'required',
                'desc' => 'required'
            ]);

            $result = Software::where('name', $request->input('name'))->get();

            if (count($result) == 0)
            {
                $software = new Software();
                $software->name = $request->input('name');
                $software->description = $request->input('desc');
                $software->save();

                return redirect('/software')->with('success', 'Software Registered');
            }

            $data = array(
                'error'=>'Duplicate Name',
                'search'=>$request->input('name')
            );

            return redirect('/software')->with($data);
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
        if ($this->hasAccess(1))
        {
            $result = Software::find($id);

            if (!is_null($result))
            {
                $data = array(
                    'title' => "Software Viewer.",
                    'desc' => "View Software information.",
                    'software' => $result,
                    'links' => $this->operator_links,
                    'active' => 'Software'
                );

                return view('software.show')->with($data);
            }
            return redirect('/software');
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
        if ($this->hasAccess(1))
        {
            $result = Software::find($id);
            if (!is_null($result))
            {
                $data = array(
                    'title' => "Edit Existing Software",
                    'desc' => "For editing existing Software.",
                    'software'=>$result,
                    'links' => $this->operator_links,
                    'active' => 'Software'
                );

                return view('software.edit')->with($data);
            }

            return redirect('/software');
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
        if ($this->hasAccess(1))
        {            
            $this->validate($request, [
                'name' => 'required',
                'desc' => 'required'
            ]);

            $result = Software::where('name', $request->input('name'))->where('id', '!=', $id)->get();

            if (count($result) == 0)
            {
                $newSoft = Software::find($id);
                $newSoft->name = $request->input('name');
                $newSoft->description = $request->input('desc');
                $newSoft->save();

                return redirect('/software')->with('success', 'Software Updated');
            }

            $data = array(
                'error'=>'Duplicate Name',
                'search'=>$request->input('name')
            );

            return redirect('/software')->with($data);
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
        if ($this->hasAccess(1))
        {
            $software = Software::find($id);
            $software->delete();

            $affected = DB::table('affected_software')->where('software_id', '=', $id)->delete();

            return redirect('/software')->with('success', 'Software Deleted');
        }
        return redirect('login')->with('error', 'Please log in first.');
    }
}
