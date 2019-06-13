@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">Create Invoice</div>

        <form class="was-validated" @submit.prevent="create()">
          <div class="card-body">
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label class="font-weight-bold">No. Dokumen</label>
                  <multiselect
                    v-model="delivery_order"
                    :options="listDeliveryOrders"
                    placeholder="Select No. Dokumen"
                    label="no_delivery_order"
                    track-by="id"
                    @input="setDetail()" required>
                  </multiselect>
                  <div class="invalid-feedback">Required</div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="font-weight-bold">Tgl. Dokumen</label>
                  <input type="text" class="form-control" v-model="tgL_dokumen" readonly>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="font-weight-bold">Customer</label>
                  <multiselect
                    v-model="store"
                    :options="listStores"
                    :custom-label="customLabel"
                    placeholder="Select Customer"
                    label="name"
                    track-by="id">
                  </multiselect>
                  <div class="invalid-feedback">Required</div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="font-weight-bold">Sales</label>
                  <input type="text" class="form-control" v-model="sales.name" readonly>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label class="font-weight-bold">No. Polisi</label>
                  <input type="text" class="form-control" v-model="vehicle.no_polisi" readonly>
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
                    <th width="25%">Code</th>
                    <th width="35%">Product Name</th>
                    <th width="15%">QTY</th>
                    <th>Price</th>
                    <th>Total</th>
                  </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(row, index) in products">
                      <td>@{{ index+1 }}</td>
                      <td>
                        <input type="text" class="form-control" v-model="row.product.code" readonly>
                      </td>
                      <td>
                        <input type="text" class="form-control" v-model="row.product.name" readonly>
                      </td>
                      <td>
                        <input type="text" class="form-control" v-model="row.qty" required>
                        <div class="invalid-feedback">Required</div>
                      </td>
                      <td>
                        <span>@{{ row.product.price | formatPrice }}</span>
                      </td>
                      <td>
                        <span>@{{ row.total | formatPrice }}</span>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="5" class="text-right font-weight-bold">Grand Total</td>
                      <td>
                        <span>@{{ grandTotal | formatPrice }}</span>
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
    delivery_order: '',
    tgL_dokumen: '',
    store: '',
    sales: '',
    vehicle: '',
    products: [
      {
        product: '',
        qty: '',
        total: '',
      }
    ],
    listDeliveryOrders: [],
    listStores: [],
  },
  created() {
    this.getDeliveryOrder();
    this.getStore();
  },
  methods: {
    async create() {
      try {
        const response = await axios.post('/invoice', {
          stores_id: this.store.id,
          sales_id: this.sales.id,
          delivery_orders_id: this.delivery_order.id,
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
    async getDeliveryOrder() {
      try {
        const response = await axios.get('/delivery-order/get-data');
        this.listDeliveryOrders = response.data;
        console.log(response);
      } catch (error) {
        console.error(error);
      }
    },
    async getDeliveryOrderProduct() {
      try {
        const response = await axios.get('/delivery-order/get-data-product/'+this.delivery_order.id);
        let product = response.data;
        this.products = product;
        for(let i = 0; i < product.length; i++) {
          this.products[i].product = product[i].product;
          this.products[i].qtyTmp = product[i].qty;
          this.products[i].total = '';
        }
        console.log(response);
      } catch (error) {
        console.error(error);
      }
    },
    async getStore() {
      try {
        const response = await axios.get('/store/get-data');
        this.listStores = response.data;
        console.log(response);
      } catch (error) {
        console.error(error);
      }
    },
    setDetail() {
      if(this.delivery_order) {
        this.tgL_dokumen = this.humanDate(this.delivery_order.created_at);
        this.sales = this.delivery_order.sales;
        this.vehicle = this.delivery_order.vehicle;
        this.getDeliveryOrderProduct();
      }
      else {
        this.initForm();
      }
    },
    initForm() {
      this.delivery_order = '';
      this.tgL_dokumen = '';
      this.sales = '',
      this.vehicle = '',
      this.products = [{
        product: '',
        qty: '',
      }];
    },
    customLabel({name, location}) {
      return `${name} - [${location}]`;
    }
  },
  computed: {
    checkQty() {
      let product = this.products;
      for(let i = 0; i < product.length; i++) {
        if(product[i].qty > product[i].qtyTmp) {
          product[i].qty = '';
          return alert('Quantity is not enough');
        }
      }
    },
    grandTotal() {
      return this.products.reduce(function(grandTotal, row) {
        return grandTotal + row.total;
      }, 0);
    }
  },
  watch: {
    products: {
      deep: true,
      handler: function(val) {
        for(let i = 0; i < val.length; i++) {
          val[i].total = val[i].qty * val[i].product.price;
          this
        }
        this.checkQty;
      }
    }
  }
})
</script>
@endpush