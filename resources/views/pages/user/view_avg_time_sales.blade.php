@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">Average Time</div>

        <div class="card-body">
          <div class="form-group row">
            <div class="col-md-12">
{{--               <table class="table table-bordered" id="stores-table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>No. Delivery Order</th>
                    <th>Location</th>
                    <th width="15%"></th>
                  </tr>
                </thead>
              </table> --}}
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

@component('components.modal_store_create', ['title' => 'Create Customer'])
@endcomponent
@component('components.modal_store_edit', ['title' => 'Edit Customer'])
@endcomponent

@endsection

@push('scripts')
<script type="text/javascript">
// $(document).ready(function() {
//   var tables = $('#stores-table').DataTable({
//     processing: true,
//     serverSide: true,
//     ajax: {
//       url: '/store/get-datatables',
//     },
//     order: [[ 1, 'asc' ]],
//     columns: [
//       { data: null, name: null, searchable: false, orderable: false },
//       { data: 'name', name: 'name' },
//       { data: 'location', name: 'location' },
//       /* ACTION */ {
//         render: function (data, type, row) {
//           return "<button id='modal-edit' class='btn btn-sm btn-primary' data-id='"+row.id+"' data-name='"+row.name+"' data-location='"+row.location+"'>Edit</button>&nbsp;<button onclick='checkDelete("+row.id+")' class='btn btn-sm btn-danger'>Delete</button>";
//         }, orderable: false, searchable: false
//       },
//     ]
//   });
//   tables.on('draw.dt', function () {
//       var info = tables.page.info();
//       tables.column(0, { search: 'applied', order: 'applied', page: 'applied' }).nodes().each(function (cell, i) {
//           cell.innerHTML = i + 1 + info.start;
//       });
//   });
// });

$(document).on('click', '#modal-edit',function() {
  app.id = ($(this).data('id'));
  app.name = ($(this).data('name'));
  app.location = ($(this).data('location'));
  $("#modal-store-edit").modal('show');
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
    sales_id: {{ $sales_id }},

    name: '',
    location: '',
  },
  mounted() {
    this.getAvgTime();
  },
  methods: {
    async getAvgTime() {
      try {
        const response = await axios.get('/statistic/get-avg-time-sales/', {
          params: {
            sales_id: this.sales_id,
          }
        });
        let data = response.data.model;
        console.log(data);
      } catch (error) {
        console.error(error);
      }
    },
    async create(submit) {
      if(submit) {
        try {
          const response = await axios.post('/store', {
            name: this.name,
            location: this.location,
          });
          this.initForm();
          $("#modal-store-create").modal('hide');
          $('#stores-table').DataTable().ajax.reload();
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
        this.initForm();
        $("#modal-store-create").modal('show');
      }
    },
    async update(id) {
      try {
        const response = await axios.patch('/store/'+app.id, {
          name: this.name,
          location: this.location,
        });
        $("#modal-store-edit").modal('hide');
        $('#stores-table').DataTable().ajax.reload();
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
        const response = await axios.delete('/store/'+id);
        $('#stores-table').DataTable().ajax.reload();
        Toast.fire({
          type: 'success',
          title: 'Deleted'
        });
        console.log(response);
      } catch(error) {
        console.error(error);
      }
    },
    initForm() {
      this.id = '';
      this.name = '';
      this.location = '';
    },
  }
})
</script>
@endpush