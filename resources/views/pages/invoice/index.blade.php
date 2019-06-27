@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">List Invoice</div>

        <div class="card-body">
          <div class="form-group row">
            <div class="col-md-12">
              <table class="table table-bordered" id="invoices-table">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th>No. Dokumen</th>
                    <th>Tgl. Invoice</th>
                    <th>Customer</th>
                    <th>Sales</th>
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
  var tables = $('#invoices-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
      url: '/invoice/get-datatables',
      data: function(d) {
        d.params = {
          id: {{ Auth::user()->id }},
        }
      }
    },
    order: [[ 2, 'asc' ]],
    columns: [
      { data: null, name: null, searchable: false, orderable: false },
      { data: 'delivery_order.no_delivery_order', name: 'deliveryOrder.no_delivery_order' },
      { data: 'created_at', name: 'created_at',
        render: function(data) {
          return moment(data).format('DD MMM YYYY');
        }
      },
      { data: 'store.name', name: 'store.name' },
      { data: 'sales.name', name: 'sales.name' },
      /* ACTION */ {
        render: function (data, type, row) {
          return "<a href='/invoice/view/"+row.id+"' class='btn btn-sm btn-primary'>Show</a>";
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
  mounted() {
  },
  methods: {
  }
})
</script>
@endpush