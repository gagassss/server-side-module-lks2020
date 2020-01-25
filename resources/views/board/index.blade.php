@extends('layouts.app')

@section('content')
<div class="row pt-5 mx-5">
  @if (session('message'))
  <div class="col-12">
    <div class="alert alert-{{session('type')}}" role="alert">
      <strong>{{session('message')}}</strong>
    </div>
  </div>
  @endif
  <div class="row mb-5">
    <div class="col d-flex mb-5">
      <h2>{{$board[0]->name}}</h2> <button data-toggle="modal" data-target="#add-new-list-modal" type="button" class="btn btn-primary ml-2  rounded-circle"> <i class="material-icons"> add </i></button>
    </div>
  </div>
  <div class="col-12">
    <ul class="list-group list-group-flush">
      @foreach ($board_lists as $board_list)
        <li class="list-group-item"><input type="text" readonly  v-on:keyup.enter="updateList({{$board_list->id}}, {{$board[0]->id}}, {{$board_list->order}})" v-on:dblclick="removeReadonlyAttr({{$board_list->id}})" id="list-input{{$board_list->id}}" class="board-name form-control" value="{{$board_list->name}}"> <small class="helper-text muted text-secondary">double click on input field to update board list name or make it blank to delete it and then hit enter.</small></li>
      @endforeach
    </ul>
  </div>
</div>

  <div class="row mx-5">
    <div class="col-12 list-members-table mt-5">
      <div class="row mb-4">
        <div class="col d-flex">
          <h2>Team Members</h2> <button data-toggle="modal" data-target="#add-new-member-modal" type="button" class="btn btn-primary ml-2 rounded-circle"> <i class="material-icons"> add </i></button> 
        </div>
      </div>

      <ul class="list-group list-group-flush">
        @foreach ($list_members as $member) 
          <li style="" class="list-group-item d-flex justify-content-between align-items-center">{{$member->username}} <button class="btn btn-danger" v-on:click="deleteMember({{$member->user_id}}, {{$board[0]->id}})">Delete</button></li>
        @endforeach
      </ul>
    </div>
  </div>

@endsection

@extends('partials/add-member-modal')
@extends('partials/add-list-modal')
