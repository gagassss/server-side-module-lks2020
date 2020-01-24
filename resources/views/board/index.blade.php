@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-12 pb-4">
      <h2>Board : {{$board[0]->name}}</h2>
      <button type="button" class="btn btn-primary my-3" data-toggle="modal" data-target="#add-new-member-modal">Add new Member</button>
    </div>
    @if (session('message'))
    <div class="col-8">
      <div class="alert alert-{{session('type')}}" role="alert">
        <strong>{{session('message')}}</strong>
      </div>
    </div>
    @endif
    <div class="col-8 list-members-table">
      <h2>Team Members</h2>
      <table class="table table-hover">
        <thead class="thead-dark">
          <tr>
            <th scope="col">no</th>
            <th scope="col">id</th>
            <th scope="col">username</th>
            <th scope="col">action</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($list_members as $member)
          <tr>
            <th scope="row">{{$loop->iteration}}</th>
            <td>{{$member->user_id}}</td>
            <td>{{$member->username}}</td>
            <td><button class="btn btn-danger" v-on:click="deleteMember({{$member->user_id}}, {{$board[0]->id}})">Delete</button></td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  <div class="row pt-5">
    <div class="col-12 pb-4">
      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-new-list-modal">Add new List</button>
    </div>

    <div class="col-8 list-table">
      <h2>Board List</h2>
      <table class="table table-hover">
        <thead class="thead-dark">
          <tr>
            <th scope="col">no</th>
            <th scope="col">id</th>
            <th scope="col">name</th>
            <th scope="col">action</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($board_lists as $board_list)
          <tr>
            <th scope="row">{{$loop->iteration}}</th>
            <td>{{$board_list->id}}</td>
            <td><input type="text" readonly  v-on:keyup.enter="updateList({{$board_list->id}}, {{$board[0]->id}}, {{$board_list->order}})" v-on:dblclick="removeReadonlyAttr({{$board_list->id}})" id="list-input{{$board_list->id}}" class="board-name form-control" value="{{$board_list->name}}"> <small class="helper-text muted text-secondary">double click on input field to update board list name or make it blank to delete it and then hit enter.</small></td>
            <td><a class="btn btn-primary" href="/board/{{$board[0]->id}}/list/{{$board_list->id}}">Detail</a></td>  
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection

@extends('partials/add-member-modal')
@extends('partials/add-list-modal')
