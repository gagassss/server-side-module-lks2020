<div class="modal fade" id="add-new-card-to-list" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add new Card to {{$list[0]->name}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="/list/add-new-card" method="POST">
          @csrf
          <input type="hidden" value="{{ $list[0]->id }}" name="list_id">
          <input type="hidden" value="{{ $board[0]->id }}" name="board_id">
          <div class="form-group">
            <label for="list">Name</label>
            <input type="text" class="form-control" id="list" name="card_name">
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>