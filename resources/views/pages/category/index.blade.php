@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="card">
        <div class="card-header">Category</div>

        <div class="card-body">
          <div class="form-group row">
            <div class="col-md-3">
              <button @click="create()" class="btn btn-primary">Create</button>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-12">
              <table class="table table-bordered" id="categories-table">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th>Name</th>
                    <th>Book(s) QTY</th>
                    <th>Action</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@component('components.modal_category_create', ['title' => 'Create Category'])
@endcomponent
@component('components.modal_category_edit', ['title' => 'Edit Category'])
@endcomponent

@endsection

@push('scripts')
<script type="text/javascript">
$(document).ready(function() {
  var tables = $('#categories-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: 'category/get-datatables',
    order: [[ 1, 'asc' ]],
    columns: [
      { data: 'id', name: 'id', searchable: false },
      { data: 'name', name: 'name' },
      { data: 'books_count', name: 'books_count', searchable: false },
      /* ACTION */ {
        render: function (data, type, row) {
          return "<button id='modal-edit' class='btn btn-sm btn-primary' data-id='"+row.id+"' data-name='"+row.name+"'>Edit</button>&nbsp;<button onclick='checkDelete("+row.id+")' class='btn btn-sm btn-danger'>Delete</button>";
        }, orderable: false, searchable: false
      },
    ]
  });
  tables.on('order.dt search.dt', function () {
    tables.column(0, {search:'applied', order:'applied'}).nodes().each(function (cell, i) {
      cell.innerHTML = i+1;
    });
  }).draw();
});

$(document).on('click', '#modal-edit',function() {
  app.id = ($(this).data('id'));
  app.name = ($(this).data('name'));
  $("#modal-category-edit").modal('show');
});

function checkDelete(id) {
  Swal.fire({
    title: 'Are you sure?',
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes'
  }).then((result) => {
    if(result.value) {
      app.delete(id);
    }
  })
}

var app = new Vue({
  el: '#app',
  data: {
    id: '',
    name: '',
  },
  methods: {
    async create(submit) {
      if(submit) {
        try {
          const response = await axios.post('category', {
            name: this.name,
          });
          $("#modal-category-create").modal('hide');
          $('#categories-table').DataTable().ajax.reload();
          Toast.fire({
            type: 'success',
            title: 'Created'
          });
          console.log(response);
        } catch(error) {
          console.error(error);
        }
      }
      else {
        $("#modal-category-create").modal('show');
      }
    },
    async update(id) {
      try {
        const response = await axios.patch('category/'+app.id, {
          name: this.name,
        });
        $("#modal-category-edit").modal('hide');
        $('#categories-table').DataTable().ajax.reload();
        Toast.fire({
          type: 'success',
          title: 'Updated'
        });
        console.log(response);
      } catch(error) {
        console.error(error);
      }
    },
    async delete(id) {
      try {
        const response = await axios.delete('category/'+id);
        $('#categories-table').DataTable().ajax.reload();
        Toast.fire({
          type: 'success',
          title: 'Deleted'
        });
        console.log(response);
      } catch(error) {
        console.error(error);
      }
    }
  }
})
</script>
@endpush