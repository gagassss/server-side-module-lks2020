@extends('layouts.app')

@section('content')
<div class="container">
    {{-- <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div> --}}

    <div class="row justify-content-center pt-5">      
        <div class="col-12 pb-5">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-new-board-modal">Add new board</button>
        </div>
        @error('name')
          <div class="col-8">
            <div class="alert alert-danger" role="alert">
              <strong>error when adding new board, {{ $message }}</strong>
            </div>
          </div>
        @enderror
        
        @if (session('message'))
        <div class="col-8">
          <div class="alert alert-{{session('type')}}" role="alert">
            <strong>{{session('message')}}</strong>
          </div>
        </div>
        @endif

        <div class="col-8" id="board-list-table">
            <table class="table">
                <thead class="thead-dark">
                  <tr>
                    <th scope="col">id</th>
                    <th scope="col">name</th>
                    <th scope="col">action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($all_boards as $board)
                    <tr>
                      <th scope="row board-id">{{$board[0]->id}}</th>
                    <td><input type="text" v-on:keyup.enter="changeNameBoard({{$board[0]->id}})" id="inputNameBoard{{$board[0]->id}}" readonly ondblclick="return vm.inputNewNameBoard({{$board[0]->id}})" class="board-name form-control" id="{{$board[0]->id}}" value="{{$board[0]->name}}"> <small class="helper-text muted text-secondary">double click on input field to update board name and or make it blank to delete it then hit enter.</small></td>
                    <td> <a href="/board/{{$board[0]->id}}" class="btn btn-primary">Detail</a> </td>
                  </tr>
                  @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@extends('partials/modal')
