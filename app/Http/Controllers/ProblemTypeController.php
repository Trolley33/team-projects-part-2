<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\ProblemType;

class ProblemTypeController extends Controller
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
            $parents = ProblemType::where('parent', '=', '-1')->get();

            $data = array(
                'title' => "Speciality Viewer",
                'desc' => "View information on specialist's problem_types.",
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
        //
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
        if (PagesController::hasAccess(1))
        {
            $problem_type = ProblemType::find($id);
            if (!is_null($problem_type))
            {
                $specialists = Speciality::join('users', 'users.id', '=', 'speciality.specialist_id')->join('problem_types', 'problem_types.id', '=', 'speciality.problem_type_id')->where('problem_types.id', '=', $id)->orWhere('problem_types.parent', '=', $id)->get();

                if ($problem_type->parent == '-1')
                {
                    $types = ProblemType::where('parent', '=', $id)->get();

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
            return "Something else";
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
