@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">Delivery Order</div>

        <form class="was-validated" @submit.prevent="create()">
          <div class="card-body">
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label>No. Dokumen</label>
                  <input type="text" class="form-control" v-model="no_delivery_order" required>
                  <div class="invalid-feedback">Required</div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>Tgl. Dokumen</label>
                  <input type="text" class="form-control" value="{{ date('d M Y') }}" readonly>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Sales</label>
                  <multiselect
                    v-model="sales"
                    :options="listSales"
                    placeholder="Select Sales"
                    label="name"
                    track-by="id" required>
                  </multiselect>
                  <div class="invalid-feedback">Required</div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label>No. Polisi</label>
                  <input type="text" class="form-control" v-model="no_polisi" required>
                  <div class="invalid-feedback">Required</div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>Driver</label>
                  <input type="text" class="form-control" v-model="driver" required>
                  <div class="invalid-feedback">Required</div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <table class="table table-hover">
                  <thead>
                  <tr>
                    <th>No.</th>
                    <th width="30%">Code</th>
                    <th width="40%">Product Name</th>
                    <th>QTY</th>
                    <th><button type="button" class="btn btn-primary btn-sm" @click="addRow()">Add</button></th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr v-for="(row, index) in products">
                    <td>@{{ index+1 }}</td>
                    <td>
                      <multiselect
                        :id="'select'+index"
                        v-model="row.product"
                        :options="listProducts"
                        placeholder="Select Product"
                        label="code"
                        track-by="id" required>
                      </multiselect>
                      <div class="invalid-feedback">Required</div>
                    </td>
                    <td>
                      <input type="text" class="form-control" v-model="row.product.name" readonly>
                    </td>
                    <td>
                      <input type="text" class="form-control" v-model="row.qty" required>
                      <div class="invalid-feedback">Required</div>
                    </td>
                    <td>
                      <button type="button" class="btn btn-danger btn-sm" @click="removeRow(index)">X</button>
                    </td>
                  </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>

{{-- @component('components.modal_book_edit', ['title' => 'Edit Book'])
@endcomponent --}}

@endsection

@push('scripts')
<script type="text/javascript">
$(document).ready(function() {
  var tables = $('#books-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: 'book/get-data',
    columns: [
      { data: 'id', name: 'id' },
      { data: 'title', name: 'title' },
      { data: 'categories.name', name: 'categories.name' },
      { data: 'qty', name: 'qty' },
      { data: 'price', name: 'price' },
      /* ACTION */ {
        render: function (data, type, row) {
          return "<button id='modal-edit' class='btn btn-sm btn-primary' data-id='"+row.id+"' data-title='"+row.title+"' data-categories-id='"+row.categories_id+"' data-qty='"+row.qty+"' data-price='"+row.price+"'>Edit</button>&nbsp;<button onclick='checkDelete("+row.id+")' class='btn btn-sm btn-danger'>Delete</button>";
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
  app.title = ($(this).data('title'));
  app.categories_id = ($(this).data('categories-id'));
  app.qty = ($(this).data('qty'));
  app.price = ($(this).data('price'));
  let obj = app.listCategories.find(o => o['id'] == app.categories_id);
  app.categories_id = obj;
  $("#modal-book-edit").modal('show');
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
    no_delivery_order: '',
    sales: '',
    no_polisi: '',
    driver: '',
    products: [
      {
        product: '',
        qty: ''
      }
    ],
    listSales: [],
    listProducts: [],
  },
  created() {
    this.getSales();
    this.getProduct();
  },
  methods: {
    async create() {
      try {
        const response = await axios.post('delivery-order', {
          no_delivery_order: this.no_delivery_order,
          sales_id: this.sales.id,
          no_polisi: this.no_polisi,
          driver: this.driver,
          products: this.products,
        });
        this.initForm();
        Toast.fire({
          type: 'success',
          title: 'Created'
        });
        console.log(response);
      } catch(error) {
        console.error(error);
      }
    },
    async getSales() {
      try {
        const response = await axios.get('user/get-sales');
        this.listSales = response.data;
        console.log(response);
      } catch (error) {
        console.error(error);
      }
    },
    async getProduct() {
      try {
        const response = await axios.get('product/get-data');
        this.listProducts = response.data;
        console.log(response);
      } catch (error) {
        console.error(error);
      }
    },
    initForm() {
      this.no_delivery_order = '';
      this.sales = '',
      this.no_polisi = '';
      this.driver = '';
      this.amount = '';
      this.products = [{
        product: '',
        qty: '',
      }];
    },
    async update(id) {
      try {
        const response = await axios.patch('book/'+app.id, {
          title: this.title,
          categories: this.categories_id,
          qty: this.qty,
          price: this.price,
        });
        $('#modal-book-edit').modal('hide');
        $('#books-table').DataTable().ajax.reload();
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
        const response = await axios.delete('book/'+id);
        $('#books-table').DataTable().ajax.reload();
        Toast.fire({
          type: 'success',
          title: 'Deleted'
        });
        console.log(response);
      } catch (error) {
        console.error(error);
      }
    },
    addRow() {
      this.products.push({product: '', qty: ''});
    },
    removeRow(index) {
      this.products.splice(index, 1);
    },
  }
})
</script>
@endpush