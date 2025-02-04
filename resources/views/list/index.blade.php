@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-12 pb-4">
    <button type="button" class="btn btn-primary my-3" data-toggle="modal" data-target="#add-new-task-to-list">Add new Task to {{$list[0]->name}}</button>
    </div>
    @if (session('message'))
    <div class="col-8">
      <div class="alert alert-{{session('type')}}" role="alert">
        <strong>{{session('message')}}</strong>
      </div>
    </div>
    @endif
    <div class="col-8 cards-table">
      <h2>Tasks</h2>
      <table class="table table-hover">
        <thead class="thead-dark">
          <tr>
            <th scope="col">no</th>
            <th scope="col">task id</th>
            <th scope="col">task</th>
            <th scope="col">action</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($cards as $card)              
            <tr>
              <td>{{$loop->iteration}}</td>
              <td>{{$card->id}}</td>
              <td> <input type="text" v-on:keyup.enter="updateCard({{$list[0]->id}}, {{$card->id}})" id="card-input{{$card->id}}" class="form-control" v-on:dblclick="removeReadonlyAttr({{$card->id}})" readonly value="{{$card->task}}"></td>
              <td>dad</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection

@extends('/partials/add-card-to-list')

