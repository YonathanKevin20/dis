@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card" v-if="loaded">
        <div class="card-header">Average Time - <b>@{{ details[0].sales.name }}</b></div>

        <div class="card-body">
          <div class="form-group row" v-for="detail in details">
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-3">
                  <div class="form-group text-right">
                    <label class="font-weight-bold">Tgl. Dokumen</label><br>
                    <span>@{{ detail.created_at | humanDate }}</span>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label class="font-weight-bold">No. Dokumen</label><br>
                    <span>@{{ detail.no_delivery_order }}</span>
                  </div>
                </div>
              </div>
              <div class="row" v-for="invoice in detail.invoice">
                <div class="col-md-3">
                  <div class="form-group text-right">
                    <label class="font-weight-bold">Tgl. Invoice</label><br>
                    <span>@{{ invoice.created_at | humanDate }}</span><br><br>
                    <label class="font-weight-bold">Customer</label><br>
                    <span>@{{ invoice.store.name }} - [@{{ invoice.store.location }}]</span>
                  </div>
                </div>
                <div class="col-md-9">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th width="25%">Code</th>
                        <th width="35%">Product Name</th>
                        <th width="15%">QTY</th>
                        <th>Price</th>
                        <th>Total</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(row, index) in invoice.invoice_product">
                        <td>@{{ index+1 }}</td>
                        <td>@{{ row.product.code }}</td>
                        <td>@{{ row.product.name }}</td>
                        <td>@{{ row.qty}}</td>
                        <td>@{{ row.product.price | currency }}</td>
                        <td>@{{ row.total | currency }}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <hr style="border-top: 2.5px solid rgb(0,0,0,.75);">
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
    deliver_orders_id: '',
    details: '',
    loaded: false,
  },
  mounted() {
    this.getAvgTime();
  },
  methods: {
    async getAvgTime() {
      try {
        const response = await axios.get('/statistic/get-avg-time-sales', {
          params: {
            sales_id: {{ $sales_id }},
          }
        });
        this.deliver_orders_id = response.data;
        if(response.data[0]) {
          this.getDetail();
        }
        console.log(response);
      } catch (error) {
        console.error(error);
      }
    },
    async getDetail() {
      this.loaded = false;
      try {
        const response = await axios.get('/user/detail-avg-time', {
          params: {
            deliver_orders_id: this.deliver_orders_id,
          }
        });
        this.details = response.data;
        this.loaded = true;
        console.log(response);
      } catch (error) {
        console.error(error);
      }
    },
  }
})
</script>
@endpush