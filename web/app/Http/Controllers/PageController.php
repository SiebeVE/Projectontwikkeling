<?php
/**
 * Created by PhpStorm.
 * User: denis
 * Date: 20-5-2016
 * Time: 17:12
 */
namespace App\Http\Controllers;

//use Carbon\Carbon;
use Illuminate\Http\Request;
use App\User;


use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
    public function info() {
        return view('info');
    }

    public function contact() {
        return view('contact');
    }
}