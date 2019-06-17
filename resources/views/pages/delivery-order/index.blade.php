@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">List Delivery Order</div>

        <div class="card-body">
          <div class="form-group row">
            <div class="col-md-12">
              <table class="table table-bordered" id="delivery-orders-table">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th>No. Dokumen</th>
                    <th>Tgl. Dokumen</th>
                    <th>Supervisor</th>
                    <th>Sales</th>
                    <th>No. Polisi</th>
                    <th>Driver</th>
                    <th>Status</th>
                    <th></th>
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
@endsection

@push('scripts')
<script type="text/javascript">
$(document).ready(function() {
  var tables = $('#delivery-orders-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
      url: '/delivery-order/get-datatables',
      data: function(d) {
        d.params = {
          id: {{ Auth::user()->id }},
        }
      }
    },
    order: [[ 2, 'desc' ]],
    columns: [
      { data: null, name: null, searchable: false, orderable: false },
      { data: 'no_delivery_order', name: 'no_delivery_order' },
      { data: 'created_at', name: 'created_at',
        render: function(data) {
          return moment(data).format('DD MMM YYYY');
        }
      },
      { data: 'spv.name', name: 'spv.name' },
      { data: 'sales.name', name: 'sales.name' },
      { data: 'vehicle.no_polisi', name: 'vehicle.no_polisi' },
      { data: 'vehicle.driver', name: 'vehicle.driver' },
      { data: 'status', name: 'status',
        render: function(data) {
          if(data == '0') {
            return '<span class="badge badge-pill badge-danger">New</span>';
          }
          else if(data == '1') {
            return '<span class="badge badge-pill badge-warning">On Progress</span>';
          }
          else if(data == '2') {
            return '<span class="badge badge-pill badge-success">Complete</span>';
          }
        }
      },
      /* ACTION */ {
        render: function (data, type, row) {
          return "<a href='/delivery-order/view/"+row.id+"' class='btn btn-sm btn-primary'>Show</a>";
        }, orderable: false, searchable: false
      },
    ]
  });
  tables.on('draw.dt', function () {
      var info = tables.page.info();
      tables.column(0, { search: 'applied', order: 'applied', page: 'applied' }).nodes().each(function (cell, i) {
          cell.innerHTML = i + 1 + info.start;
      });
  });
});

var app = new Vue({
  el: '#app',
  data: {
  },
  created() {
  },
  methods: {
  }
})
</script>
@endpush