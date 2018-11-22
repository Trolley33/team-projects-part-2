<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function index()
    {
        $data = array(
            'title' => "Make-It-All Helpdesk",
            'desc' => "For submitting and receiving tehnical queries."
        );
        return view('pages.index')->with($data);
    }

    public function login()
    {
        $data = array(
            'title' => "Login Page",
            'desc' => "Please log in with your user credentials below."
        );

        return view('pages.login')->with($data);
    }

    public function FAQ()
    {
        $data = array(
            'title' => "FAQ",
            'desc' => ""
        );

        return view('pages.faq')->with($data);
    }

    public function verify()
    {
        // do database query
        if ($_POST['username'] == "alice" && $_POST['password'] == "password")
        {
            $data = array(
                'title' => "Operator Homepage",
                'desc' => "Please select a task."
            );
            return redirect('operator/homepage')->with($data);
        }
    }

    public function register()
    {
        $data = array(
           'title' => "Register Page",
           'desc' => "Please enter your details below.",
           'username' => ""
       );

       return view('pages.register')->with($data);
    }

    public function registerPOST ()
    {
       $data = array(
            'title' => "Register Page",
            'desc' => "Please enter your details below.",
            'username' => $_POST['username']
        );
        return view('pages.register')->with($data);
    }

    // ==== Operator pages. ====
    public function operator_homepage()
    {
        $data = array(
            'title' => "Operator Homepage",
            'desc' => "Please select a task."
        );
        return view('pages.operator.homepage')->with($data);
    }

    public function caller_info()
    {
        $data = array(
            'title' => "Caller Details",
            'desc' => "Search for Caller Details"
        );
        return view('pages.operator.caller_info')->with($data);
    }

    public function caller_info_from_name($name)
    {
        $data = array(
            'title' => "Caller Details",
            'desc' => "",
            'name' => $name
        );
        return view('pages.operator.display_caller_info')->with($data);
    }

    public function log_call()
    {
        $data = array(
            'title' => "Log New Call",
            'desc' => ""
        );

        return view('pages.operator.log_call')->with($data);
    }
    // ==== Problem pages. ====

    public function view_problems()
    {
        $data = array(
            'title' => "Problem Viewer",
            'desc' => "Shows all ongoing and resolved problems."
        );

        return view('pages.problems.view_all')->with($data);
    }

    // Single problem
    public function view_problem($id)
    {
        $data = array(
            'title' => "Problem Viewer",
            'desc' => "Shows all ongoing and resolved problems.",
            'id' => $id
        );

        return view('pages.problems.view_problem')->with($data);
    }
}
