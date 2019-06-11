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
    ajax: '/delivery-order/get-datatables',
    order: [[ 2, 'asc' ]],
    columns: [
      { data: 'id', name: 'id', searchable: false, orderable: false },
      { data: 'no_delivery_order', name: 'no_delivery_order' },
      { data: 'created_at',
        name: 'created_at',
        render: function(data) {
          return moment(data).format('DD MMM YYYY');
        }
      },
      { data: 'spv.name', name: 'spv.name' },
      { data: 'sales.name', name: 'sales.name' },
      { data: 'vehicle.no_polisi', name: 'vehicle.no_polisi' },
      { data: 'vehicle.driver', name: 'vehicle.driver' },
      /* ACTION */ {
        render: function (data, type, row) {
          return "<a href='/delivery-order/view/"+row.id+"' class='btn btn-sm btn-primary'>Show</a>";
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