<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
	/**
	 * Give back dummy json on get request
	 *
	 * @return Response
	 */
	public function get()
	{
		return response()->json(["status" => "ok"]);
	}

	/**
	 * Give back dummy json on post request
	 *
	 * @return Response
	 */
	public function post()
	{
		if (isset($_POST["appkey"]) && $_POST["appkey"] == "projecta" && isset($_POST["requested"]))
		{
			$requested = $_POST["requested"];
			switch ($requested)
			{
				case "user":
					$data = DB::table('users')->select('name', 'email')->get();
					break;
				default:
					$data = ["status" => "Ok, but nothing requested"];
					break;
			}
		}
		else
		{
			$data = ["status" => "failed"];
		}

		return response()->json($data);
	}
}
