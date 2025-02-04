<div class="modal fade" id="add-new-list-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add new List</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="/board/add-new-list" method="POST">
          @csrf
          <input type="hidden" value="{{ $board[0]->id }}" name="id">
          <div class="form-group">
            <label for="list">Name</label>
            <input type="text" class="form-control" id="list" name="list">
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>