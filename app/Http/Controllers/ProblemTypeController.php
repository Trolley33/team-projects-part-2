<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\ProblemType;
use App\Speciality;
use App\User;
use App\Job;


class ProblemTypeController extends Controller
{ 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // If user has operator access.
        if (PagesController::hasAccess(1))
        {
            // Parent problem types displayed here, and their sub problem types are displayed when a parent problem is viewed.
            $parents = ProblemType::leftJoin('speciality', 'speciality.problem_type_id', '=', 'problem_types.id')->leftJoin('problems', 'problems.problem_type', '=', 'problem_types.id')->selectRaw('problem_types.*, IFNULL(COUNT(speciality.specialist_id),0) as specialists, IFNULL(COUNT(problems.id),0) as problems')->where('problem_types.parent', '=', '-1')->groupBy('problem_types.id')->get();

            // Supply data to view.
            $data = array(
                'title' => "Speciality Viewer",
                'desc' => "View information on specialist's problem types.",
                'parents' => $parents,
                'links'=>PagesController::getOperatorLinks(),
                'active'=>'Problem Types'
            );

            return view('problem_types.index')->with($data);
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
        // If user has operator access.
        if (PagesController::hasAccess(1))
        {
            // Get all parent problem types.
            $types = ProblemType::where('parent', '=', '-1')->get();
            // By default don't select anything in particular.
            $selected = null;

            // If specified, grab the specified parent problem type,
            // for the dropdown box.
            if (isset($_GET['type']) && !empty($_GET['type']))
            {
                $selected = ProblemType::find($_GET['type']);

            }

            // Supply data to view.
            $data = array(
                'title' => "Create New Problem Type",
                'desc' => "For making a new problem type.",
                'selected'=>$selected,
                'types'=>$types,
                'links'=>PagesController::getOperatorLinks(),
                'active'=>'Problem Types'
            );

            return view('problem_types.create')->with($data);
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
        // If user has operator access.
        if (PagesController::hasAccess(1))
        {
            // Make sure required data is supplied.
            $this->validate($request, [
                'parent-select' => 'required',
                'desc' => 'required'
            ]);
            // Check no problem type with this description already exists.
            $result = ProblemType::where('description', $request->input('desc'))->get();
            if (count($result) == 0)
            {
                // Create new problem type object, with given information.
                $problem_type = new ProblemType();
                $problem_type->parent = $request->input('parent-select');
                $problem_type->description = $request->input('desc');
                $problem_type->save();

                // Redirect based on if parent type made or sub-type.
                if ($request->input('parent-select') == '-1')
                {
                    return redirect('/problem_types')->with('success', 'Problem Type Created');
                }
                return redirect('/problem_types/'.$request->input('parent-select'))->with('success', 'Problem Type Created');
            }

            $data = array(
                'error'=>'Duplicate Problem Type Description'
            );

            return redirect('/problem_types')->with($data);
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
        // If user has operator access.
        if (PagesController::hasAccess(1))
        {
            // Get problem object from ID, check it exists.
            $problem_type = ProblemType::find($id);
            if (!is_null($problem_type))
            {
                // Get all specialists with this problem type.
                $specialists = Speciality::join('users', 'users.id', '=', 'speciality.specialist_id')->join('problem_types', 'problem_types.id', '=', 'speciality.problem_type_id')->where('problem_types.id', '=', $id)->orWhere('problem_types.parent', '=', $id)->get();

                // If type is parent, get sub types and show relevant page.
                if ($problem_type->parent == '-1')
                {
                    $types = ProblemType::leftJoin('speciality', 'speciality.problem_type_id', '=', 'problem_types.id')->leftJoin('problems', 'problems.problem_type', '=', 'problem_types.id')->selectRaw('problem_types.*, IFNULL(COUNT(speciality.specialist_id),0) as specialists, IFNULL(COUNT(problems.id),0) as problems')->where('problem_types.parent', '=', $id)->groupBy('problem_types.id')->get();

                    $data = array(
                        'title' => "Problem Type Viewer",
                        'desc' => "View information on parent.",
                        'parent'=>$problem_type,
                        'types' => $types,
                        'specialists'=>$specialists,
                        'links'=>PagesController::getOperatorLinks(),
                        'active'=>'Problem Types'
                    );

                    return view('problem_types.show_parent')->with($data);
                }
                // If type is subtype, get parent and show relevant page.
                else
                {
                    $parent = ProblemType::find($problem_type->parent);

                    $data = array(
                        'title' => "Problem Type Viewer.",
                        'desc' => "View information on problem types.",
                        'problem_type' => $problem_type,
                        'parent'=>$parent,
                        'specialists'=>$specialists,
                        'links' => PagesController::getOperatorLinks(),
                        'active' => 'Problem Types'
                    );

                    return view('problem_types.show_child')->with($data);
                }
            }
            return redirect('/problem_types')->with('error', 'Sorry, something went wrong.');
        }
        return redirect('login')->with('error', 'Please log in first.');
    }

    /**
     * Display compacted, modal friendly version of resource.
     */
    public function show_compact($id)
    {
        // If operator or specialist are viewing.
        if (PagesController::hasAccess(1) || PagesController::hasAccess(2))
        {
            // Get problem type from ID, check if it exists.
            $type = ProblemType::find($id);
            if (!is_null($type))
            {
                // Get all specialists with this problem type.
                $specialists = Speciality::join('users', 'users.id', '=', 'speciality.specialist_id')->join('problem_types', 'problem_types.id', '=', 'speciality.problem_type_id')->where('problem_types.id', '=', $id)->orWhere('problem_types.parent', '=', $id)->get();

                // Find parent (or null if none).
                $parent = ProblemType::find($type->parent);

                // Get who is viewing page, and ultimately their access level. This is done to prevent specialist from having option to click certain links.
                $viewer = PagesController::getCurrentUser();
                $job = Job::find($viewer->job_id);
                $level = $job->access_level;
                $data = array(
                    'title' => "Problem Type Viewer",
                    'desc' => "View information on parent.",
                    'parent'=>$parent,
                    'type' => $type,
                    'level'=>$level,
                    'specialists'=>$specialists,
                    'links'=>PagesController::getOperatorLinks(),
                    'active'=>'Problem Types'
                );

                return view('problem_types.show_compact')->with($data);
            }
            return redirect('/problem_types')->with('error', 'Sorry, something went wrong.');
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
        // If user has operator access.
        if (PagesController::hasAccess(1))
        {
            // Get problem type object, check it exists.
            $problem_type = ProblemType::find($id);
            if (!is_null($problem_type))
            {
                // Get sub problem types if applicable.
                $types = ProblemType::where('parent', '=', '-1')->get();
                $data = array(
                    'title' => "Edit Existing Problem Type",
                    'desc' => "For editing problem types.",
                    'problem_type'=>$problem_type,
                    'types'=>$types,
                    'links' => PagesController::getOperatorLinks(),
                    'active' => 'Problem Types'
                );

                // Redirect based on parent/child.
                if ($problem_type->parent == -1)
                {
                    return view('problem_types.edit_parent')->with($data);
                }
                else 
                {
                    return view('problem_types.edit_child')->with($data);
                }

            }
            return redirect('/problem_types')->with('error', 'Sorry, something went wrong.');
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
        // Get type object, check it exists.
        $problem_type = ProblemType::find($id);
        if (!is_null($problem_type))
        {
            // If user has operator access.
            if (PagesController::hasAccess(1))
            {
                // If description is blank, use previous description.
                $desc = $request->input('desc') ?? $problem_type->description;

                // Find duplicate types with the provided the description.
                $result = ProblemType::where('description', $desc)->where('id', '!=', $id)->get();

                if (count($result) == 0)
                {
                    // These fields are required, as otherwise the dropdown was modified in some way.
                    $this->validate($request, [
                        'isParent' => 'required',
                        'parent-select' => 'required',
                        'desc' => 'required'
                    ]);


                    // Parent problem.
                    if ($request->input('isParent') == 'true')
                    {
                        $problem_type->description = $request->input('desc');
                        $problem_type->save();
                    }

                    // Child problem.
                    elseif ($request->input('isParent') == 'false')
                    {
                        $problem_type->parent = $request->input('parent-select');
                        $problem_type->description = $request->input('desc');
                        $problem_type->save();
                    }

                    return redirect("/problem_types/$id")->with('success', 'Problem Type Info Updated');
                }

                $data = array(
                    'error'=>'Duplicate Problem Type Description'
                );

                return redirect('/problem_types')->with($data);
            }
            return redirect('login')->with('error', 'Please log in first.');
        }
        return redirect('/problem_types')->with('error', 'Sorry, something went wrong.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $problem_type = ProblemType::find($id);
        if (!is_null($problem_type))
        {
            if (PagesController::hasAccess(1))
            {
                $problem_type->delete();
                
                if ($problem_type->parent == '-1')
                {
                    $sub_problems = ProblemType::where('problem_types.parent', '=', $id)->delete();
                    return redirect('/problem_types')->with('success', 'Problem Type Deleted');
                }
                else
                {
                    return redirect('/problem_types/'.$problem_type->parent)->with('success', 'Problem Type Deleted');
                }
            }
            return redirect('login')->with('error', 'Please log in first.');
        }
        return redirect('/problem_types')->with('error', 'Sorry, something went wrong.');
    }
}
