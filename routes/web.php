<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use Illuminate\Http\Request;
use \App\boards;
use \App\board_lists;
use \App\board_members;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use \App\card;

function checkifLoggedIn() {
    if (!Auth::user()) {
        return redirect('/');
    }
}

Route::get('/', function () {
    if (!Auth::user()) {
        return view('auth/login');
    } else {
        return redirect('/home');
    }
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/board/{id}', function(Request $request, $id) {
    checkifLoggedIn();
    $list_members = [];
    if (sizeof(DB::table('board_members')->where(['user_id' => Auth::user()->id, 'board_id' => $id])->get()) > 0) {
        $board_members = DB::table('board_members')->where(['board_id' => $id])->get();
        foreach($board_members as $index=>$bm) {
            $current_user = DB::table('users')->where(['id' => $bm->user_id])->get()[0];
            array_push($list_members, (object) [
                'user_id' => $current_user->id,
                'username' => $current_user->username
            ]);
        }
        $board = DB::table('boards')->where(['id' => $id])->get();
        $board_lists = DB::table('board_lists')->where(['board_id' => $id])->get();
        return view('/board/index', ['board_lists' => $board_lists, 'board' => $board, 'list_members' => $list_members]);
    } else {
        return redirect('/home');
    }
});
Route::post('/board/add-new', function(Request $request) {
    $request->validate([
        'name' => 'required'
    ]);

    if (sizeof(boards::where('name', $request->name)->get()) > 0) {
        return redirect('/home'.'/'.$request->id)->with(['message' => 'Duplicate board name.', 'type' => 'danger']);
    }
 
    $newBoard = new boards();
    $newBoard->name = $request->name;
    $newBoard->creator_id = $request->creator_id;
    $newBoard->save();
    //return $newBoard->id;
    
    $newMember = new board_members();
    $newMember->board_id = $newBoard->id;
    $newMember->user_id = $request->creator_id;
    $newMember->save();
    
    return redirect('/home'.'/'.$request->id)->with(['message' => 'Adding new board success.', 'type' => 'success']);

});
Route::post('/board/update-name/{id}/{new_name}', function(Request $request, $id, $new_name) {
    boards::where('id', $id)->update(['name' => $new_name]);
    return 'success';
});

Route::post('/board/delete/{id}', function(Request $request, $id) {
    $board = DB::table('boards')->where(['creator_id' => Auth::user()->id,  'id' => $id])->get();
    if (sizeof($board) > 0) {
        $current_board_list = board_lists::where('board_id', $id)->get();
        if (sizeof($current_board_list) > 0) {
            $current_board_list = card::where('list_id', $current_board_list[0]->id)->delete();
        }
        board_members::where('board_id', $id)->delete();
        board_lists::where('board_id', $id)->delete();
        boards::where('id', $id)->delete();
        return 'success';
    } else {
        return 'error';
    }
});

Route::post('/member/delete/{id}/{board_id}', function(Request $request, $id, $board_id) {
    $board = DB::table('boards')->where(['creator_id' => $id, 'id' => $board_id])->get();
    if (sizeof($board) > 0) {
        return 'error';
    } else {
        board_members::where(['user_id' => $id, 'board_id' => $board_id])->delete();
        return 'success';
    }
});

Route::post('/board/add-new-member', function(Request $request) {
    $user = DB::table('users')->where(['username' => $request->username])->get();
    
    if (sizeof($user) > 0) {
        if (Auth::user()->username == $request->username) {
            return redirect('/board'.'/'.$request->id)->with(['message' => 'The username is already registered as a member.', 'type' => 'danger']);
        } else {
            $newMember = new board_members();
            $newMember->board_id = intval($request->id);
            $newMember->user_id = $user[0]->id;
            $newMember->save();
            return redirect('/board'.'/'.$request->id)->with(['message' => 'Add new member success.', 'type' => 'success']);
        }
    } else {
        return redirect('/board'.'/'.$request->id)->with(['message' => 'Username not found.', 'type' => 'danger']);
    }
});

Route::post('/board/add-new-list/', function (Request $request) {
    if(sizeof(board_lists::where('name', $request->list)->get()) > 0 ) {
        return redirect('/board'.'/'.$request->id)->with(['message' => 'Duplicate name list.', 'type' => 'danger']);
    }
    $greaterOrder = DB::table('board_lists')->orderBy('order', 'desc')->first();
    $new_list = new board_lists;
    $new_list->board_id = $request->id;
    $new_list->name = $request->list;
    if ($greaterOrder == null) {
        $new_list->order = 1;
    } else {
        $new_list->order = $greaterOrder->order+1;
    }
    $new_list->save();
    return redirect('/board'.'/'.$request->id)->with(['message' => 'Adding list success.', 'type' => 'success']);
});

Route::post('/board/delete-list', function(Request $request) {
    if (sizeof(card::where('list_id', $request->list_id)->get()) > 0) {
        card::where('list_id', $request->list_id)->delete();
    }
    board_lists::where(['id' => $request->list_id])->delete();
    return 'deleted';
});

Route::post('/board/update-list', function(Request $request) {
    board_lists::where(['id' => $request->list_id])->update([
        'name' => $request->new_name
    ]);
    return 'updated';
});

Route::get('/board/{board_id}/list/{list_id}', function(Request $request, $board_id, $list_id) {
    $board = DB::table('boards')->where('id', $board_id)->get();
    $board_member = DB::table('board_members')->where(['board_id' => $board_id, 'user_id' => Auth::user()->id])->get();
    $list = DB::table('board_lists')->where(['board_id' => $board_id, 'id' => $list_id])->get();
    $cards = DB::table('cards')->where(['list_id' => $list_id])->get();
    if (sizeof($board_member) > 0) {

        return view('/list/index', [
            'board' => $board,
            'list' => $list,
            'cards' => $cards
        ]); 
    } else {
        return redirect('/home');
    }
});


Route::post('/list/add-new-card', function(Request $request) {
    if (sizeof(card::where('task', $request->card_name)->get()) > 0) {
        return redirect('/board'.'/'.$request->board_id.'/list/'.$request->list_id)->with(['message' => 'Duplicate card name.', 'type' => 'danger']);
    } else {
        $greaterOrder = DB::table('cards')->orderBy('order', 'desc')->first();
        $new_card = new card();
        $new_card->list_id = $request->list_id;
        $new_card->task = $request->card_name;
        if ($greaterOrder == null) {
            $new_card->order = 1;
        } else {
            $new_card->order = $greaterOrder->order+1;
        }
        $new_card->save();

        return redirect('/board'.'/'.$request->board_id.'/list/'.$request->list_id)->with(['message' => 'Adding card success.', 'type' => 'success']);
        
    }

});

Route::post('/list/delete-card', function(Request $request) {
    $current_card = DB::table('cards')->where(['id' => $request->card_id, 'list_id' => $request->list_id])->get();
    if (sizeof( $current_card) > 0) {
        DB::table('cards')->where(['list_id' => $request->list_id, 'id' => $request->card_id])->delete();
        return 'deleted';
    }
});


Route::post('/list/update-card', function (Request $request) {
    $current_card = DB::table('cards')->where(['id' => $request->card_id, 'list_id' => $request->list_id])->get();
    if (sizeof(DB::table('cards')->where(['task' => $request->name])->get()) > 0) {
        return 'duplicate';
    }
    if (sizeof( $current_card) > 0) {
        DB::table('cards')->where(['list_id' => $request->list_id, 'id' => $request->card_id])->update([
            'task' => $request->name
        ]);
    }
});



