<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use App\User;
use App\Job;
use App\Department;
use App\Equipment;

class EquipmentController extends Controller
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

        if (!is_null($result) && count($result) != 0)
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
            $equipment = Equipment::all();

            $data = array(
                'title' => "Equipment Information Viewer",
                'desc' => "View information on equipment.",
                'equipment' => $equipment,
                'links'=>$this->operator_links,
                'active'=>'Equipment'
            );

            return view('equipment.index')->with($data);
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
                'title' => "Register New Equipment",
                'desc' => "For registering new equipment.",
                'links' => $this->operator_links,
                'active' => 'Equipment'
            );

            return view('equipment.create')->with($data);
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
                'serialNumber' => 'required',
                'desc' => 'required',
                'model' => 'required',
            ]);

            $result = Equipment::where('serial_number', $request->input('serialNumber'))->get();

            if (count($result) == 0)
            {
                $equipment = new Equipment();
                $equipment->serial_number = $request->input('serialNumber');
                $equipment->description = $request->input('desc');
                $equipment->model = $request->input('model');
                $equipment->save();

                return redirect('/equipment')->with('success', 'Equipment Registered');
            }

            $data = array(
                'error'=>'Duplicate Serial Number',
                'search'=>$request->input('serialNumber')
            );

            return redirect('/equipment')->with($data);
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
            $equip = Equipment::find($id);

            if (!is_null($equip))
            {
                $data = array(
                    'title' => "Equipment Viewer.",
                    'desc' => "View equipment information.",
                    'equipment' => $equip,
                    'links' => $this->operator_links,
                    'active' => 'Equipment'
                );

                return view('equipment.show')->with($data);
            }
            return "Error completing that request.";
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
            $equip = Equipment::find($id);
            if (!is_null($equip))
            {
                $data = array(
                    'title' => "Edit Existing Equipment",
                    'desc' => "For editing existing equipment.",
                    'equipment'=>$equip,
                    'links' => $this->operator_links,
                    'active' => 'Equipment'
                );

                return view('equipment.edit')->with($data);
            }

            return redirect('/equipment');
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
                'serialNumber' => 'required',
                'desc' => 'required',
                'model' => 'required',
            ]);

            $equip = Equipment::where('serial_number', $request->input('serialNumber'))->where('id', '!=', $id)->get();
            if (count($equip) == 0)
            {
                $newEquip = Equipment::find($id);
                $newEquip->serial_number = $request->input('serialNumber');
                $newEquip->description = $request->input('desc');
                $newEquip->model = $request->input('model');
                $newEquip->save();

                return redirect('/equipment')->with('success', 'Equipment Updated');
            }

            $data = array(
                'error'=>'Duplicate Serial Number',
                'search'=>$request->input('serialNumber')
            );

            return redirect('/equipment')->with($data);
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
            $equipment = Equipment::find($id);
            $equipment->delete();

            $affected = DB::table('affected_hardware')->where('equipment_id', '=', $id)->delete();

            return redirect('/equipment')->with('success', 'Equipment Deleted');
        }
        return redirect('login')->with('error', 'Please log in first.');
    }
}
