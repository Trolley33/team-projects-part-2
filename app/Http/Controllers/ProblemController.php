<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Problem;
use App\ProblemType;
use App\ResolvedProblem;
use App\Call;
use App\User;
use App\Job;
use App\Equipment;
use App\Software;
use App\Importance;
use App\AffectedHardware;
use App\AffectedSoftware;
use App\Reassignments;

class ProblemController extends Controller
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
            // Get intial caller for problem.
            $ongoing = DB::select(DB::raw(
                'SELECT problems.id as pID, problems.created_at, problem_types.description as ptDesc, problem_types.id as ptID, problems.description, IFNULL(parents.description,0) as pDesc, problems.importance, users.forename, users.surname, users.id as uID, calls.id as cID, IFNULL(specialists.forename,0) as sForename, IFNULL(specialists.surname,0) as sSurname, IFNULL(specialists.id,0) as sID, importance.text, importance.class, importance.level
                FROM problems
                JOIN calls
                ON (
                    problems.id = calls.problem_id
                    AND calls.created_at = (
                        SELECT MIN(created_at)
                        FROM calls
                        WHERE problem_id = problems.id
                    )
                )
                JOIN users
                ON users.id = calls.caller_id
                JOIN problem_types
                ON problem_types.id = problems.problem_type
                JOIN importance
                ON importance.id = problems.importance
                LEFT JOIN users specialists
                ON specialists.id = problems.assigned_to
                LEFT JOIN problem_types parents
                ON problem_types.parent = parents.id
                LEFT JOIN resolved_problems rp ON rp.problem_id = problems.id
                WHERE rp.problem_id IS NULL'
            ));
                
            $resolved = DB::select(DB::raw(
                'SELECT problems.id as pID, problems.created_at, problem_types.description as ptDesc, problem_types.id as ptID, problems.description, IFNULL(parents.description,0) as pDesc, problems.importance, users.forename, users.surname, users.surname, users.id as uID, calls.id as cID, IFNULL(specialists.forename,0) as sForename, IFNULL(specialists.surname,0) as sSurname, IFNULL(specialists.id,0) as sID, rp.created_at as solved_at
                FROM problems
                JOIN calls
                ON (
                    problems.id = calls.problem_id
                    AND calls.created_at = (
                        SELECT MIN(created_at)
                        FROM calls
                        WHERE problem_id = problems.id
                    )
                )
                JOIN users
                ON users.id = calls.caller_id
                JOIN problem_types
                ON problem_types.id = problems.problem_type
                LEFT JOIN users specialists
                ON specialists.id = problems.assigned_to
                LEFT JOIN problem_types parents
                ON problem_types.parent = parents.id
                JOIN resolved_problems rp ON rp.problem_id = problems.id'
            ));

            $data = array(
                'title' => "Problem Viewer",
                'desc' => "Displays all problems.",
                'ongoing' => $ongoing,
                'resolved' => $resolved,
                'links' => PagesController::getOperatorLinks(),
                'active' => 'Problems'
            );

            return view('problems.index')->with($data);
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
            $users = User::join('jobs', 'users.job_id', '=', 'jobs.id')->join('departments', 'jobs.department_id', '=', 'departments.id')->select('users.*', 'jobs.title', 'departments.name')->get();

            $data = array(
                'title' => "Problem Creator",
                'desc' => "Create a New Problem",
                'users' => $users,
                'links' => PagesController::getOperatorLinks(),
                'active' => 'Problems'
            );

            return view('problems.select_user_for_problem')->with($data);
        }
        return redirect('login')->with('error', 'Please log in first.');
    }

    public function select_problem_type($user_id)
    {
        if (PagesController::hasAccess(1))
        {
            $problem_types = ProblemType::leftJoin('problem_types as parents', 'problem_types.parent', '=', 'parents.id')->selectRaw('problem_types.*, IFNULL(parents.description,0) as parent_description')->get();
            
            $user = User::find($user_id);
	    if (!is_null($user))
	    {
            $data = array(
                'title' => "Create Problem",
                'desc' => " ",
                'user'=>$user,
                'problem_types'=>$problem_types,
                'links' => PagesController::getOperatorLinks(),
                'active' => 'Problems'
            );

            return view('problems.select_problem_type_for_problem')->with($data);
	    }
	    return redirect('/problems/create')->with('error', 'Invalid User selected.');
        }
        return redirect('login')->with('error', 'Please log in first.'); 
    }

    public function add_problem_details($user_id, $problem_type_id)
    {
        if (PagesController::hasAccess(1))
        {
            $pt = ProblemType::find($problem_type_id);
            $parent = ProblemType::find($pt->parent);
            $user = User::find($user_id);
            $importance = Importance::orderBy('level')->get();
            if (!is_null($pt) && !is_null($user) && !is_null($importance))
            {
                $data = array(
                    'title' => "Create Problem",
                    'desc' => " ",
                    'user'=>$user,
                    'importance'=>$importance,
                    'problem_type'=>$pt,
                    'parent'=>$parent,
                    'links' => PagesController::getOperatorLinks(),
                    'active' => 'Problems'
                );

                return view('problems.add_problem_details')->with($data);
            }

            return redirect('/problems/create')->with('error', 'Invalid User/Problem Type/Importance selected.');
        }
        return redirect('login')->with('error', 'Please log in first.'); 
    }

    public function select_specialist_for_problem(Request $request, $user_id, $problem_type_id)
    {
        if (PagesController::hasAccess(1))
        {
            $this->validate($request, [
                'desc' => 'required',
                'notes' => 'required',
                'importance' => 'required',
            ]);

            $user = User::find($user_id);
            $problem_type = ProblemType::find($problem_type_id);
            $parent = ProblemType::find($problem_type->parent);
            /* Outdated.
            $specialists = User::join('speciality', 'users.id', '=', 'speciality.specialist_id')->join('problem_types', 'speciality.problem_type_id', '=', 'problem_types.id')->leftJoin('problem_types as parents', 'problem_types.parent', '=', 'parents.id')->selectRaw('speciality.id as sID, problem_types.id as pID, problem_types.description, IFNULL(parents.description,0) as parent_description, problem_types.parent, users.*')->get();
            */
            $specialists = User::join('speciality', 'users.id', '=', 'speciality.specialist_id')->join('problem_types', 'speciality.problem_type_id', '=', 'problem_types.id')->leftJoin('problem_types as parents', 'problem_types.parent', '=', 'parents.id')->leftJoin('problems', 'problems.assigned_to', '=', 'users.id')->selectRaw('speciality.id as sID, problem_types.id as pID, problem_types.description, IFNULL(parents.description,0) as parent_description, problem_types.parent, IFNULL(COUNT(problems.id), 0) as jobs, users.*')->groupBy('users.id', 'speciality.id')->get();

            if (!is_null($user) && !is_null($problem_type))
            {
                $data = array(
                    'title' => "Edit Assigned Specialist",
                    'desc' => "",
                    'problem_description'=>$request->input('desc'),
                    'problem_notes'=>$request->input('notes'),
                    'problem_importance'=>$request->input('importance'),
                    'user'=>$user,
                    'parent'=>$parent,
                    'problem_type'=>$problem_type,
                    'specialists'=>$specialists,
                    'links' => PagesController::getOperatorLinks(),
                    'active' => 'Problems'
                );

                return view('problems.select_specialist_for_problem')->with($data);
            }

            return redirect('/problems/create')->with('error', 'Invalid User/Problem Type selected.');
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
                'desc' => 'required',
                'notes' => 'required',
                'importance' => 'required',
                'user_id' => 'required',
                'problem_type_id' => 'required',
                'submit' =>'required'
            ]);

            $operator = PagesController::getCurrentUser();


            $problem = new Problem();
            $problem->description = $request->input('desc');
            $problem->notes = $request->input('notes');
            $problem->problem_type = $request->input('problem_type_id');
            $problem->logged_by = $operator->id;
            $problem->importance = $request->input('importance');
            // Assign Problem to Current Operator.
            if ($request->input('submit') == "Assign Problem to You")
            {
                $problem->assigned_to = $operator->id;
            }

            // Assign Problem to Selected Specialist.
            else if ($request->input('submit') == "Assign Specialist")
            {
                $this->validate($request, [
                    'specialist' => 'required'
                ]);
                $problem->assigned_to = $request->input('specialist');
            }

            $problem->save();

            $call = new Call();
            $call->problem_id = $problem->id;
            $call->caller_id = $request->input('user_id');
            $call->notes = "Initial call.";
            $call->save();

            return redirect('/problems/'.$problem->id)->with('success', 'Problem Created');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (PagesController::hasAccess(1) || PagesController::hasAccess(2))
        {
            if (PagesController::hasAccess(1))
            {
                $links = PagesController::getOperatorLinks();
            }
            if (PagesController::hasAccess(2))
            {
                $links = PagesController::getSpecialistLinks();
            }
            $problem = Problem::find($id);
            $type = ProblemType::find($problem->problem_type);
            $parent = ProblemType::find($type->parent);

            $callers = DB::table('problems')->join('calls', 'problems.id', '=', 'calls.problem_id')->join('users', 'users.id', '=', 'calls.caller_id')->select('calls.id as cID', 'calls.notes', 'calls.created_at as cAT', 'users.*')->where('problems.id', '=', $id)->get();

            $assigned = DB::table('problems')->join('users', 'problems.assigned_to', '=', 'users.id')->select('users.*')->where('problems.id', '=', $id)->get()->first();

            $resolved = Problem::join('resolved_problems', 'resolved_problems.problem_id', '=', 'problems.id')->select('resolved_problems.solution_notes', 'resolved_problems.created_at')->where('problems.id', '=', $id)->get()->first();

            $hardware = Equipment::join('affected_hardware', 'affected_hardware.equipment_id', '=', 'equipment.id')->join('problems', 'problems.id', '=', 'affected_hardware.problem_id')->where('problems.id', '=', $id)->select('equipment.*')->get();

            $software = Software::join('affected_software', 'affected_software.software_id', '=', 'software.id')->join('problems', 'problems.id', '=', 'affected_software.problem_id')->where('problems.id', '=', $id)->select('software.*')->get();

            $importance = Importance::find($problem->importance);

            if (!is_null($problem) && !is_null($callers))
            {
                $data = array(
                    'title' => "Problem Viewer.",
                    'desc' => "Shows information on a problem.",
                    'problem' => $problem,
                    'problem_type' => $type,
                    'parent' => $parent,
                    'callers' => $callers,
                    'specialist' => $assigned,
                    'resolved' => $resolved,
                    'hardware' => $hardware,
                    'software' => $software,
                    'importance' => $importance,
                    'links' => $links,
                    'active' => 'Problems'
                );
                if (PagesController::hasAccess(1))
                {
                    return view('problems.show')->with($data);
                }
                elseif (PagesController::hasAccess(2))
                {
                    return view('problems.show_specialist')->with($data);
                }
            }
            if (PagesController::hasAccess(1))
            {
                return redirect('/problems')->with('error', 'Invalid/corrupted problem selected.');
            }
            elseif (PagesController::hasAccess(2))
            {
                return redirect('/specialist')->with('error', 'Invalid/corrupted problem selected.');
            }
        }
        return redirect('login')->with('error', 'Please log in first.');
    }

    public function select_user_for_call($id)
    {
        if (PagesController::hasAccess(1))
        {
            $problem = Problem::find($id);
            $users = User::join('jobs', 'users.job_id', '=', 'jobs.id')->join('departments', 'jobs.department_id', '=', 'departments.id')->select('users.*', 'jobs.title', 'departments.name')->get();

            if (!is_null($problem))
            {
                $caller = DB::select(DB::raw('
                    SELECT users.* FROM users
                    JOIN calls
                    ON calls.caller_id = users.id AND calls.created_at = (
                        SELECT MIN(created_at)
                        FROM calls
                        WHERE problem_id = '.$problem->id.'
                    );'));
                

                $data = array(
                    'title' => "Add Call to Problem.",
                    'desc' => "Select a user to create a call for.",
                    'problem' => $problem,
                    'caller' => $caller,
                    'users' => $users,
                    'links' => PagesController::getOperatorLinks(),
                    'active' => 'Problems'
                );
                return view('problems.select_user_for_call')->with($data);
            }
            return redirect('/calls/create')->with('error', 'Invalid User or Problem Selected');
        }
        return redirect('login')->with('error', 'Please log in first.');
    }

    public function add_call($id, $caller_id)
    {
        if (PagesController::hasAccess(1))
        {
            $problem = Problem::find($id);
            $user = User::find($caller_id);

            if (!is_null($problem) && !is_null($user))
            {
                $data = array(
                    'title' => "Create Call for Problem.",
                    'desc' => "Add a call from a user to a problem.",
                    'problem' => $problem,
                    'user' => $user,
                    'links' => PagesController::getOperatorLinks(),
                    'active' => 'Problems'
                );
                return view('problems.add_call')->with($data);
            }
            return redirect('/calls/create')->with('error', 'Invalid Problem Selected');
        }
        return redirect('login')->with('error', 'Please log in first.');
    }

    public function select_equipment_to_add($id)
    {
        if (PagesController::hasAccess(1) || PagesController::hasAccess(2))
        {
            if (PagesController::hasAccess(1))
            {
                $links = PagesController::getOperatorLinks();
            }
            if (PagesController::hasAccess(2))
            {
                $links = PagesController::getSpecialistLinks();
            }

            $problem = Problem::find($id);

            $equipment = Equipment::all();
            $affected_hardware = AffectedHardware::where('problem_id', '=', $id)->get();

            if (!is_null($problem))
            {
                $data = array(
                    'title' => "Add Affected Equipment to Problem.",
                    'desc' => "Select equipment affected by problem.",
                    'problem' => $problem,
                    'equipment' => $equipment,
                    'affected' => $affected_hardware,
                    'links' => $links,
                    'active' => 'Problems'
                );
                return view('problems.select_equipment_to_add')->with($data);
            }
            if (PagesController::hasAccess(1))
            {
                return redirect('/problems')->with('error', 'Invalid problem selected.');
            }
            elseif (PagesController::hasAccess(2))
            {
                return redirect('/specialist')->with('error', 'Invalid problem selected.');
            }
        }
        return redirect('login')->with('error', 'Please log in first.');
    }

    public function append_equipment(Request $request)
    {
        if (PagesController::hasAccess(1) || PagesController::hasAccess(2))
        {            

            $this->validate($request, [
                'problem-id' => 'required',
                'equipment' => 'required',
            ]);

            $equipments = $request->input('equipment');
            $problem_id = $request->input('problem-id');

            foreach ($equipments as $e)
            {
                $a_h = new AffectedHardware();
                $a_h->problem_id = $problem_id;
                $a_h->equipment_id = $e;
                $a_h->save();
            }

            return redirect('/problems/'.$request->input('problem-id'))->with('success', 'Equipment Added');
        }
        return redirect('login')->with('error', 'Please log in first.');
    }

    public function select_equipment_to_remove($id)
    {
        if (PagesController::hasAccess(1) || PagesController::hasAccess(2))
        {
            if (PagesController::hasAccess(1))
            {
                $links = PagesController::getOperatorLinks();
            }
            if (PagesController::hasAccess(2))
            {
                $links = PagesController::getSpecialistLinks();
            }
            $problem = Problem::find($id);

            $equipment = Equipment::join('affected_hardware', 'equipment.id', '=', 'affected_hardware.equipment_id')->where('affected_hardware.problem_id', '=', $id)->get();


            if (!is_null($problem))
            {
                $data = array(
                    'title' => "Remove Equipment from Problem.",
                    'desc' => "Select a user to create a call for.",
                    'problem' => $problem,
                    'equipment' => $equipment,
                    'links' => $links,
                    'active' => 'Problems'
                );

                return view('problems.select_equipment_to_remove')->with($data);
            }
            if (PagesController::hasAccess(1))
            {
                return redirect('/problems')->with('error', 'Invalid problem selected.');
            }
            elseif (PagesController::hasAccess(2))
            {
                return redirect('/specialist')->with('error', 'Invalid problem selected.');
            }
        }
        return redirect('login')->with('error', 'Please log in first.');
    }

    public function delete_equipment(Request $request)
    {
        if (PagesController::hasAccess(1) || PagesController::hasAccess(2))
        {            
            $this->validate($request, [
                'problem-id' => 'required',
                'equipment' => 'required',
            ]);

            $equipments = $request->input('equipment');
            $problem_id = $request->input('problem-id');

            foreach ($equipments as $e)
            {
                $a_h = AffectedHardware::find($e);
                $a_h->delete();
            }

            return redirect('/problems/'.$request->input('problem-id'))->with('success', 'Equipment Removed');

        }
        return redirect('login')->with('error', 'Please log in first.');
    }

    public function select_software_to_add($id)
    {
        if (PagesController::hasAccess(1) || PagesController::hasAccess(2))
        {
            if (PagesController::hasAccess(1))
            {
                $links = PagesController::getOperatorLinks();
            }
            if (PagesController::hasAccess(2))
            {
                $links = PagesController::getSpecialistLinks();
            }
            $problem = Problem::find($id);

            $software = Software::all();
            $affected_software = AffectedSoftware::where('problem_id', '=', $id)->get();

            if (!is_null($problem))
            {
                $data = array(
                    'title' => "Add Software to Problem.",
                    'desc' => "",
                    'problem' => $problem,
                    'software' => $software,
                    'affected' => $affected_software,
                    'links' => $links,
                    'active' => 'Problems'
                );
                return view('problems.select_software_to_add')->with($data);
            }
            if (PagesController::hasAccess(1))
            {
                return redirect('/problems')->with('error', 'Invalid problem selected.');
            }
            elseif (PagesController::hasAccess(2))
            {
                return redirect('/specialist')->with('error', 'Invalid problem selected.');
            }
        }
        return redirect('login')->with('error', 'Please log in first.');
    }

    public function append_software(Request $request)
    {
        if (PagesController::hasAccess(1) || PagesController::hasAccess(2))
        {            
            $this->validate($request, [
                'problem-id' => 'required',
                'software' => 'required',
            ]);

            $softwares = $request->input('software');
            $problem_id = $request->input('problem-id');

            foreach ($softwares as $s)
            {
                $a_s = new AffectedSoftware();
                $a_s->problem_id = $problem_id;
                $a_s->software_id = $s;
                $a_s->save();
            }

            return redirect('/problems/'.$request->input('problem-id'))->with('success', 'Software Added');
        }
        return redirect('login')->with('error', 'Please log in first.');
    }

    public function select_software_to_remove($id)
    {
        if (PagesController::hasAccess(1) || PagesController::hasAccess(2))
        {
            if (PagesController::hasAccess(1))
            {
                $links = PagesController::getOperatorLinks();
            }
            if (PagesController::hasAccess(2))
            {
                $links = PagesController::getSpecialistLinks();
            }
            $problem = Problem::find($id);

            $software = Software::join('affected_software', 'software.id', '=', 'affected_software.software_id')->where('affected_software.problem_id', '=', $id)->get();

            if (!is_null($problem))
            {
                $data = array(
                    'title' => "Remove Software from Problem.",
                    'desc' => "",
                    'problem' => $problem,
                    'software' => $software,
                    'links' => $links,
                    'active' => 'Problems'
                );

                return view('problems.select_software_to_remove')->with($data);
            }
            if (PagesController::hasAccess(1))
            {
                return redirect('/problems')->with('error', 'Invalid problem selected.');
            }
            elseif (PagesController::hasAccess(2))
            {
                return redirect('/specialist')->with('error', 'Invalid problem selected.');
            }
        }
        return redirect('login')->with('error', 'Please log in first.');
    }

    public function delete_software(Request $request)
    {
        if (PagesController::hasAccess(1) || PagesController::hasAccess(2))
        {            
            $this->validate($request, [
                'problem-id' => 'required',
                'software' => 'required',
            ]);

            $softwares = $request->input('software');
            $problem_id = $request->input('problem-id');

            foreach ($softwares as $s)
            {
                $a_s = AffectedSoftware::find($s);
                $a_s->delete();
            }

            return redirect('/problems/'.$request->input('problem-id'))->with('success', 'Software Removed');

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
        if (PagesController::hasAccess(1) || PagesController::hasAccess(2))
        {
            if (PagesController::hasAccess(1))
            {
                $links = PagesController::getOperatorLinks();
            }
            if (PagesController::hasAccess(2))
            {
                $links = PagesController::getSpecialistLinks();
            }
            $problem = Problem::find($id);
            $type = ProblemType::find($problem->problem_type);
            $parent = ProblemType::find($type->parent);
            if (!is_null($problem))
            {
                $assigned = DB::table('problems')->join('users', 'problems.assigned_to', '=', 'users.id')->select('users.*')->where('problems.id', '=', $id)->get()->first();

                $resolved = DB::table('problems')->join('resolved_problems', 'problems.id', '=', 'resolved_problems.problem_id')->select('resolved_problems.solution_notes', 'resolved_problems.created_at')->where('problems.id', '=', $id)->get()->first();

                $importance = Importance::orderBy('level')->get();

                $data = array(
                    'title' => "Edit Existing Problem",
                    'desc' => "For editing a problem.",
                    'problem'=>$problem,
                    'problem_type' => $type,
                    'parent' => $parent,
                    'specialist'=>$assigned,
                    'resolved'=>$resolved,
                    'importance'=>$importance,
                    'links' => $links,
                    'active' => 'Problems'
                );

                return view('problems.edit')->with($data);
            }
            if (PagesController::hasAccess(1))
            {
                return redirect('/problems')->with('error', 'Invalid problem selected.');
            }
            elseif (PagesController::hasAccess(2))
            {
                return redirect('/specialist')->with('error', 'Invalid problem selected.');
            }
        }
        return redirect('login')->with('error', 'Please log in first.');
    }

    public function edit_problem_type ($id)
    {
       if (PagesController::hasAccess(1) || PagesController::hasAccess(2))
        {
            if (PagesController::hasAccess(1))
            {
                $links = PagesController::getOperatorLinks();
            }
            if (PagesController::hasAccess(2))
            {
                $links = PagesController::getSpecialistLinks();
            }
            $problem = Problem::find($id);
            if (!is_null($problem))
            {
                $problem_types = ProblemType::leftJoin('problem_types as parents', 'problem_types.parent', '=', 'parents.id')->selectRaw('problem_types.*, IFNULL(parents.description,0) as parent_description')->get();
                
                $data = array(
                    'title' => "Edit Problem Type",
                    'desc' => " ",
                    'problem'=>$problem,
                    'problem_types'=>$problem_types,
                    'links' => $links,
                    'active' => 'Problems'
                );

                return view('problems.edit_problem_type')->with($data);
            }
            if (PagesController::hasAccess(1))
            {
                return redirect('/problems')->with('error', 'Invalid problem selected.');
            }
            elseif (PagesController::hasAccess(2))
            {
                return redirect('/specialist')->with('error', 'Invalid problem selected.');
            }
        }
        return redirect('login')->with('error', 'Please log in first.'); 
    }

    public function add_problem_type ($problem_id, $type_id)
    {
        if (PagesController::hasAccess(1) || PagesController::hasAccess(2))
        {
            $problem = Problem::find($problem_id);
            $problem->problem_type = $type_id;
            $problem->save();

            return redirect('/problems/'.$problem_id.'/edit')->with('success', 'Problem Type Changed');
        }

        return redirect('/login')->with('error', 'Please log in first.');
    }

    public function edit_specialist ($id)
    {
       if (PagesController::hasAccess(1))
        {
            $problem = Problem::find($id);
            $assigned = User::find($problem->assigned_to);
            $problem_type = ProblemType::find($problem->problem_type);
            $parent = ProblemType::find($problem_type->parent);
            if (!is_null($problem))
            {
                $specialists = User::join('speciality', 'users.id', '=', 'speciality.specialist_id')->join('problem_types', 'speciality.problem_type_id', '=', 'problem_types.id')->leftJoin('problem_types as parents', 'problem_types.parent', '=', 'parents.id')->leftJoin('problems', 'problems.assigned_to', '=', 'users.id')->selectRaw('speciality.id as sID, problem_types.id as pID, problem_types.description, IFNULL(parents.description,0) as parent_description, problem_types.parent, IFNULL(COUNT(problems.id), 0) as jobs, users.*')->groupBy('users.id', 'speciality.id')->get();

                if (is_null($parent))
                {
                    $parent = $problem_type;
                }

                $data = array(
                    'title' => "Edit Assigned Specialist",
                    'desc' => "",
                    'problem'=>$problem,
                    'assigned'=>$assigned,
                    'parent'=>$parent,
                    'type'=>$problem_type,
                    'specialists'=>$specialists,
                    'links' => PagesController::getOperatorLinks(),
                    'active' => 'Problems'
                );

                return view('problems.edit_specialist')->with($data);
            }
            return redirect('/problems');
        }
        if (PagesController::hasAccess(2))
        {
            $problem = Problem::find($id);
            if (!is_null($problem))
            {
                $data = array(
                    'title' => "Request Problem be Re-assigned?",
                    'desc' => "Doing so will remove the problem from your list of problems.",
                    'problem'=>$problem,
                    'links' => PagesController::getSpecialistLinks(),
                    'active' => 'Problems'
                );

                return view('problems.request_reassign')->with($data);
            }
            return redirect('/specialist');
        }
        return redirect('login')->with('error', 'Please log in first.'); 
    }

    public function add_specialist($id, $specialist_id)
    {
        if (PagesController::hasAccess(1))
        {
            $problem = Problem::find($id);
            $user = User::find($specialist_id);
            $problem->assigned_to = $user->id;
            // If this was a reassigning, remove the request.
            Reassignments::where('problem_id', '=', $problem->id)->delete();
            $problem->save();

            return redirect('/problems/'.$id.'/edit')->with('success', 'Problem Assigned to '.$user->forename.' '.$user->surname.'.');
        }

        return redirect('/login')->with('error', 'Please log in first.');
    }

    public function add_operator($id)
    {
        if (PagesController::hasAccess(1))
        {
            $problem = Problem::find($id);
            $user = PagesController::getCurrentUser();
            $problem->assigned_to = $user->id;
            $problem->save();

            return redirect('/problems/'.$id.'/edit')->with('success', 'Problem Assigned to You');
        }

        return redirect('/login')->with('error', 'Please log in first.');
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
        if (PagesController::hasAccess(1) || PagesController::hasAccess(2))
        {            
            $this->validate($request, [
                'desc' => 'required',
                'notes' => 'required',
                'solved' => 'required',
                'importance' => 'required'
            ]);
            // Find current problem, and change the fields.
            $problem = Problem::find($id);
            $problem->description = $request->input('desc');
            $problem->notes = $request->input('notes');
            $problem->importance = $request->input('importance');
            $problem->save();

            
            if ($request->input('solved') == 'false')
            {
                $resolved = ResolvedProblem::where('problem_id', '=', $id);
                $resolved->delete();
            }
            else if ($request->input('solved') == 'true')
            {
                $user = PagesController::getCurrentUser();
                $this->validate($request, [
                    'solution_notes'=>'required'
                ]);
                $resolved = ResolvedProblem::where('problem_id', '=', $id)->get()->first();

                if (!is_null($user))
                {
                    if (is_null($resolved))
                    {
                        $resolved = new ResolvedProblem();
                        $resolved->problem_id = $id;
                    }
                    $resolved->solution_notes=$request->input('solution_notes');
                    $resolved->solved_by=$user->id;
                    $resolved->save();
                }
            }

            return redirect('/problems/'.$id)->with('success', 'Problem Updated');
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
            $problem = Problem::find($id);
            $problem->delete();

            $affected_hardware = AffectedHardware::where('problem_id', '=', $id);
            $affected_hardware->delete();

            $affected_software = AffectedSoftware::where('problem_id', '=', $id);
            $affected_software->delete();

            $calls = Call::where('problem_id', '=', $id);
            $calls->delete();

            $resolved = ResolvedProblem::where('problem_id', '=', $id);
            $resolved->delete();

            return redirect('/problems/')->with('success', 'Problem Permantly Deleted');
        }
    }
}
