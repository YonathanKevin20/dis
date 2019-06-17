<div class="modal fade" tabindex="-1" role="dialog" id="modal-store-edit">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{ $title }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form class="was-validated" @submit.prevent="update">
        <div class="modal-body">
          <div class="form-group">
            <label>Name</label>
            <input type="text" class="form-control" v-model="name" required>
            <div class="invalid-feedback">Required</div>
          </div>
          <div class="form-group">
            <label>Location</label>
            <input type="text" class="form-control" v-model="location" required>
            <div class="invalid-feedback">Required</div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>