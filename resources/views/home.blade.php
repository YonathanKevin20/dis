@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">Dashboard</div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-4">
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title">Total Revenue</h5>
                  <p class="card-text">@{{ total.revenue | currency }}</p>
                  {{-- <a href="#" class="btn btn-primary">Go somewhere</a> --}}
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title">Total Items Sold</h5>
                  <p class="card-text">@{{ total.sold }}</p>
                  {{-- <a href="#" class="btn btn-primary">Go somewhere</a> --}}
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title">@{{ total.store.new }}</h5>
                  <p class="card-text">New Customers</p>
                  <h5 class="card-title">@{{ total.store.existing }}</h5>
                  <p class="card-text">Existing Customers</p>
                </div>
              </div>
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
    total: {
      revenue: 0,
      sold: 0,
      store: {
        new: 0,
        existing: 0,
      },
    }
  },
  created() {
    this.getRevenue();
    this.getItemsSold();
    this.getStore();
  },
  methods: {
    async getRevenue() {
      try {
        const response = await axios.get('/statistic/get-revenue');
        this.total.revenue = response.data;
        console.log(response);
      } catch (error) {
        console.error(error);
      }
    },
    async getItemsSold() {
      try {
        const response = await axios.get('/statistic/get-items-sold');
        this.total.sold = response.data.length;
        console.log(response);
      } catch (error) {
        console.error(error);
      }
    },
    async getStore() {
      try {
        const response = await axios.get('/statistic/get-store');
        let new_store = response.data.new_store;
        let existing_store = response.data.existing_store;
        this.total.store.new = new_store;
        this.total.store.existing = existing_store;
        console.log(response);
      } catch (error) {
        console.error(error);
      }
    },
  }
})
</script>
@endpush