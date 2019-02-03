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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if (PagesController::hasAccess(1))
        {
            $software = Software::all();

            $data = array(
                'title' => "Software Information Viewer",
                'desc' => "View information on software.",
                'software' => $software,
                'links'=>PagesController::getOperatorLinks(),
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
        if (PagesController::hasAccess(1))
        {
            $data = array(
                'title' => "Register New Software",
                'desc' => "For registering new software.",
                'links' => PagesController::getOperatorLinks(),
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
        if (PagesController::hasAccess(1))
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
        if (PagesController::hasAccess(1))
        {
            $result = Software::find($id);

            if (!is_null($result))
            {
                $data = array(
                    'title' => "Software Viewer.",
                    'desc' => "View Software information.",
                    'software' => $result,
                    'links' => PagesController::getOperatorLinks(),
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
        if (PagesController::hasAccess(1))
        {
            $result = Software::find($id);
            if (!is_null($result))
            {
                $data = array(
                    'title' => "Edit Existing Software",
                    'desc' => "For editing existing Software.",
                    'software'=>$result,
                    'links' => PagesController::getOperatorLinks(),
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
        // If user is allowed to view this page.
        if (PagesController::hasAccess(1))
        {            
            // Find software using ID supplied.
            $software = Software::find($id);
            if (!is_null($software))
            {
                // Get info, or just set back to default values.
                // ($x ?? $y assigns $y iff $x is null)
                $name = $request->input('name') ?? $software->name;
                $desc = $request->input('desc') ?? $software->description;

                // Check for software with the same serial number inputted (not including this piece of software).
                $result = Software::where('name', $name)->where('id', '!=', $id)->get();
                if (count($result) == 0)
                {
                    $software->name = $name;
                    $software->description = $desc;
                    $software->save();

                    return redirect('/software')->with('success', 'Software Updated');
                }

                // If duplicate serial no. show user the existing entry using 'search'.
                $data = array(
                    'error'=>'Duplicate Name',
                    'search'=>$request->input('name')
                );
                return redirect('/software')->with($data);
            }
            return redirect('/software')->with('Software not found.');

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
        if (PagesController::hasAccess(1))
        {
            $software = Software::find($id);
            $software->delete();

            $affected = DB::table('affected_software')->where('software_id', '=', $id)->delete();

            return redirect('/software')->with('success', 'Software Deleted');
        }
        return redirect('login')->with('error', 'Please log in first.');
    }
}
