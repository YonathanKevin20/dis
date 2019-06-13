@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">View Invoice</div>

        <div class="card-body">
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label class="font-weight-bold">No. Dokumen</label>
                <input type="text" class="form-control" v-model="delivery_order.no_delivery_order" readonly>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label class="font-weight-bold">Tgl. Dokumen</label>
                <input type="text" class="form-control" v-model="tgl_dokumen" readonly>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label class="font-weight-bold">Customer</label><br>
                <span>@{{ store.name }} - [@{{ store.location }}]</span>
              </div>
            </div>
            <div class="col-md-3 text-right">
              <a href="javascript:history.go(-1)" class="btn btn-secondary">Back</a>
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
                      <span>@{{ row.qty }}</span>
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
        </div>

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
    id: {{ $id }},
    delivery_order: '',
    tgl_dokumen: '',
    store: '',
    sales: '',
    vehicle: '',
    products: [
      {
        product: '',
        qty: ''
      }
    ],
  },
  created() {
    this.initForm();
  },
  methods: {
    async initForm() {
      try {
        const response = await axios.get('/invoice/'+this.id);
        let data = response.data.data;
        let product = response.data.product;
        this.delivery_order = data.delivery_order;
        this.tgl_dokumen = this.humanDate(data.delivery_order.created_at);
        this.store = data.store;
        this.sales = data.sales;
        this.products = product;
        console.log(response);
      } catch (error) {
        console.error(error);
      }
    },
  },
  computed: {
    grandTotal() {
      return this.products.reduce(function(grandTotal, row) {
        return grandTotal + row.total;
      }, 0);
    }
  },
})
</script>
@endpush