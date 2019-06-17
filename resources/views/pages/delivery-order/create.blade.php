@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">Create Delivery Order</div>

        <form class="was-validated" @submit.prevent="create()">
          <div class="card-body">
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label class="font-weight-bold">No. Dokumen</label>
                  <input type="text" class="form-control" v-model="no_delivery_order" required>
                  <div class="invalid-feedback">Required</div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="font-weight-bold">Tgl. Dokumen</label>
                  <input type="text" class="form-control" value="{{ date('d M Y') }}" readonly>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="font-weight-bold">Sales</label>
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
              <div class="col-md-6">
                <div class="form-group">
                  <label class="font-weight-bold">No. Polisi</label>
                  <multiselect
                    v-model="vehicle"
                    :options="listVehicles"
                    placeholder="Select Vehicle"
                    label="no_polisi"
                    track-by="id" required>
                  </multiselect>
                  <div class="invalid-feedback">Required</div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="font-weight-bold">Driver</label>
                  <input type="text" class="form-control" v-model="vehicle.driver" readonly>
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
                        :custom-label="customLabelProduct"
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
            <div class="row">
              <div class="col-md-12 text-right">
                <button type="submit" class="btn btn-primary">Save</button>
              </div>
            </div>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
var app = new Vue({
  el: '#app',
  data: {
    no_delivery_order: '{{ $no_delivery_order }}',
    sales: '',
    vehicle: '',
    products: [
      {
        product: '',
        qty: ''
      }
    ],
    listSales: [],
    listVehicles: [],
    listProducts: [],
  },
  created() {
    this.getSales();
    this.getVehicle();
    this.getProduct();
  },
  methods: {
    async create() {
      try {
        const response = await axios.post('/delivery-order', {
          no_delivery_order: this.no_delivery_order,
          sales_id: this.sales.id,
          vehicle_id: this.vehicle.id,
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
        const response = await axios.get('/user/get-sales');
        this.listSales = response.data;
        console.log(response);
      } catch (error) {
        console.error(error);
      }
    },
    async getVehicle() {
      try {
        const response = await axios.get('/vehicle/get-data');
        this.listVehicles = response.data;
        console.log(response);
      } catch (error) {
        console.error(error);
      }
    },
    async getProduct() {
      try {
        const response = await axios.get('/product/get-data');
        this.listProducts = response.data;
        console.log(response);
      } catch (error) {
        console.error(error);
      }
    },
    initForm() {
      this.no_delivery_order = '';
      this.sales = '',
      this.vehicle = '',
      this.amount = '';
      this.products = [{
        product: '',
        qty: '',
      }];
    },
    customLabelProduct({code, name}) {
      return `${code} - [${name}]`;
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