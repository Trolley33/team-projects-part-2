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

    public function operator_homepage()
    {
        $data = array(
            'title' => "Operator Homepage",
            'desc' => "Please select a task."
        );
        return view('pages.operator.homepage')->with($data);
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
}
