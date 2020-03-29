<?php

namespace App\Http\Controllers;

use App\ReplayMessage;
use Illuminate\Http\Request;

class ReplayMessageController extends Controller
{
	private $replay_ruls = [

		// 'title' => 'required|string|min:3|max:120',
		'content' => 'required|string|email|unique:users,email|max:255'
	];

	function __construct()
	{
        //$this->middleware('auth:admin');// , ['except' => 'authAdmin' , 'index']
		
	}

	public function store(Request $request){
		
	}
}
