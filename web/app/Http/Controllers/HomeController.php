<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Project;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index']]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lat_array = array();
        $lng_array = array();

        $projects = Project::all();
        $json = json_encode($projects);

        foreach( $projects as $project ) {
            array_push($lat_array, $project->latitude);
            array_push($lng_array, $project->longitude);
        }

        return view('welcome', compact('lat_array', 'lng_array', 'json'));
    }
}
