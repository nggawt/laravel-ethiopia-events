<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Message;
use App\Repo\traits\Messages;

class MessagesController extends Controller
{
    use Messages;

    private $itemsRule = [
        "name" => "required|string|min:3",//|email|max:7",
        "title" => "required|string|min:3|max:50",
        "body" => "required|string|min:12",
    ];

       /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('admin');
    }

    public function index(Message $message) {
    	// $this->authorize('posts', $post);
    	// $this->authorize('view', $post);
    	// if(Gate::can('view', 2)){
			return $message->all();//$this->getConfirmedPosts(Post::all());
    	// }

	}
}
