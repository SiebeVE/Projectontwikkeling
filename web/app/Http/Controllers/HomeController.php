<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Project;
use App\Tag;

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
        $projectsWithRelationships = $projects->load('tags');
        $json = json_encode($projectsWithRelationships);
        $tags = array();
        $tag_names = array();

        foreach($projects as $project) {
            $project_tags = $project->tags;
            foreach($project_tags as $project_tag)
            {
                if(!in_array($project_tag->name, $tag_names)) {
                    array_push($tags, $project_tag);
                    array_push($tag_names, $project_tag->name);
                }
            }
        }

        foreach( $projects as $project ) {
            array_push($lat_array, $project->latitude);
            array_push($lng_array, $project->longitude);
        }

        return view('welcome', compact('lat_array', 'lng_array', 'json', 'tags'));
    }
}
