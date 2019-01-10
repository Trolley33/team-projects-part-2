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
            // Grab all equipment information.
            $equipment = Equipment::all();

            // Supply data to view.
            $data = array(
                'title' => "Equipment Information Viewer",
                'desc' => "View information on equipment.",
                'equipment' => $equipment,
                'links'=>PagesController::getOperatorLinks(),
                'active'=>'Equipment'
            );
            return view('equipment.index')->with($data);
        }
        // No access redirects to login.
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
                'title' => "Register New Equipment",
                'desc' => "For registering new equipment.",
                'links' => PagesController::getOperatorLinks(),
                'active' => 'Equipment'
            );
            return view('equipment.create')->with($data);
        }
        // No access redirects to login.
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
            // Validate the required data has been submitted.
            $this->validate($request, [
                'serialNumber' => 'required',
                'desc' => 'required',
                'model' => 'required',
            ]);

            // Check for existing equipment with duplicate serial number.
            $result = Equipment::where('serial_number', $request->input('serialNumber'))->get();
            if (count($result) == 0)
            {
                // Create new equipment object and add data.
                $equipment = new Equipment();
                $equipment->serial_number = $request->input('serialNumber');
                $equipment->description = $request->input('desc');
                $equipment->model = $request->input('model');
                $equipment->save();

                return redirect('/equipment')->with('success', 'Equipment Registered');
            }
            // If duplicate serial no. show user the existing entry using 'search'.
            $data = array(
                'error'=>'Duplicate Serial Number',
                'search'=>$request->input('serialNumber')
            );

            return redirect('/equipment')->with($data);
        }
        // No access redirects to login.
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
            // Find equipment using supplied ID.
            $equip = Equipment::find($id);

            if (!is_null($equip))
            {
                // Supply data to view.
                $data = array(
                    'title' => "Equipment Viewer.",
                    'desc' => "View equipment information.",
                    'equipment' => $equip,
                    'links' => PagesController::getOperatorLinks(),
                    'active' => 'Equipment'
                );
                return view('equipment.show')->with($data);
            }
            return redirect('/equipment')->with('error', 'Equipment not found.');
        }
        // No access redirects to login.
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
            // Find equipment using supplied ID.
            $equip = Equipment::find($id);
            if (!is_null($equip))
            {
                // Supply data to view.
                $data = array(
                    'title' => "Edit Existing Equipment",
                    'desc' => "For editing existing equipment.",
                    'equipment'=>$equip,
                    'links' => PagesController::getOperatorLinks(),
                    'active' => 'Equipment'
                );
                return view('equipment.edit')->with($data);
            }

            return redirect('/equipment')->with('error', 'Equipment not found.');
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
            // Validate required data has been submitted.
            $this->validate($request, [
                'serialNumber' => 'required',
                'desc' => 'required',
                'model' => 'required',
            ]);
            // Check for equipment with the same serial number inputted (not including this piece of equipment).
            $equip = Equipment::where('serial_number', $request->input('serialNumber'))->where('id', '!=', $id)->get();
            if (count($equip) == 0)
            {
                // Find equipment using ID supplied.
                $equipment = Equipment::find($id);
                if (!is_null($equipment))
                {
                    $equipment->serial_number = $request->input('serialNumber');
                    $equipment->description = $request->input('desc');
                    $equipment->model = $request->input('model');
                    $equipment->save();

                    return redirect('/equipment')->with('success', 'Equipment Updated');
                }

                return redirect('/equipment')->with('Equipment not found');
            }

            // If duplicate serial no. show user the existing entry using 'search'.
            $data = array(
                'error'=>'Duplicate Serial Number',
                'search'=>$request->input('serialNumber')
            );

            return redirect('/equipment')->with($data);
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
            // Find equipment using supplied ID and delete it.
            $equipment = Equipment::find($id);
            $equipment->delete();
            // Find entries where this equipment was affected by a problem, and delete them.
            $affected = DB::table('affected_hardware')->where('equipment_id', '=', $id)->delete();

            return redirect('/equipment')->with('success', 'Equipment Deleted');
        }
        // No access redirects to login.
        return redirect('login')->with('error', 'Please log in first.');
    }
}
