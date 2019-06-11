@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">View Delivery Order</div>

        <div class="card-body">
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label>No. Dokumen</label>
                <input type="text" class="form-control" v-model="no_delivery_order" readonly>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Tgl. Dokumen</label>
                <input type="text" class="form-control" v-model="tgl_dokumen" readonly>
              </div>
            </div>
            <div class="col-md-3 offset-md-3 text-right">
              <a href="javascript:history.go(-1)" class="btn btn-info">Back</a>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Sales</label>
                <input type="text" class="form-control" v-model="sales.name" readonly>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label>No. Polisi</label>
                <input type="text" class="form-control" v-model="no_polisi" readonly>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Driver</label>
                <input type="text" class="form-control" v-model="driver" readonly>
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
                    <input type="text" class="form-control" v-model="row.qty" readonly>
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
    no_delivery_order: '',
    tgl_dokumen: '',
    sales: '',
    no_polisi: '',
    driver: '',
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
        const response = await axios.get('/delivery-order/'+this.id);
        let data = response.data.data;
        let product = response.data.product;
        this.no_delivery_order = data.no_delivery_order;
        this.tgl_dokumen = this.humanDate(data.created_at);
        this.sales = data.sales;
        this.no_polisi = data.no_polisi;
        this.driver = data.driver;
        for(let i = 0; i < product.length; i++) {
          this.products[i] = {
            product: product[i].product,
            qty: product[i].qty,
          };
        }
        console.log(response);
      } catch (error) {
        console.error(error);
      }
    },
  }
})
</script>
@endpush