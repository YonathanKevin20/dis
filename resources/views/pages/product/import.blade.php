@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">Import Target</div>

        <div class="card-body">
          <div class="form-group row">
            <label class="col-md-4 col-form-label text-md-right">File</label>
            <div class="col-md-8">
              <input type="file" id="file" class="btn btn-default" ref="file" @change="handleFileUpload()">
              <button @click="submitFile()" class="btn btn-primary" :disabled="disabled">Import File</button>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-6">
              <a :href="url_download.xlsx" class="btn btn-warning float-right">Download Template XLSX</a>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-12">
              <table class="table table-bordered" id="import-targets-table">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th>Tahun</th>
                    <th>Bulan</th>
                    <th>Code</th>
                    <th>QTY</th>
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
  var tables = $('#import-targets-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: '/import-target/get-datatables',
    order: [[ 1, 'asc' ]],
    columns: [
      { data: null, name: null, searchable: false, orderable: false },
      { data: 'year', name: 'year' },
      { data: 'month', name: 'month' },
      { data: 'product.code', name: 'product.code' },
      { data: 'qty', name: 'qty' },
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
    file: '',
    disabled: true,
    url_download: {
      xlsx: '/product/download-template-xlsx',
    },
  },
  created() {
  },
  methods: {
    async submitFile() {
      let formData = new FormData();
      formData.append('file', this.file);
      const { data } = await axios.post('/product/import', formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })
      if(data.success) {
        Toast.fire({
          type: 'success',
          title: 'Imported'
        });
        $('#import-targets-table').DataTable().ajax.reload();
      }
      else {
        alert('error');
        console.log(data.console);
      }
    },
    handleFileUpload: function() {
      this.file = this.$refs.file.files[0];
      if(this.file) {
        this.disabled = false;
      }
    },
  },
})
</script>
@endpush