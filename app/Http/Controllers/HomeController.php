<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\boards;
use App\board_lists;
use App\board_members;
use Illuminate\Support\Facades\Auth;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
      $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
      // get board(id) where current user is member on it
      $board_member = board_members::where('user_id', Auth::user()->id)->get();

      $allBoards = [];

      foreach($board_member as $bm) {
        array_push($allBoards, boards::where('id', $bm->board_id)->get());
      }

      return view('home', ['all_boards' => $allBoards]);
    }
}
