@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">Dashboard</div>
        <div class="card-body">
{{--           <div class="row mb-3">
            <div class="col-md-3">
              <label>Month</label>
              <select class="custom-select" @change="getMonth()" v-model="month">
                <option value="all">All</option>
                <option v-for="(value, index) in listMonths" :value="index">@{{ value }}</option>
              </select>
            </div>
          </div> --}}
          <div class="row mb-3">
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
          <div class="row">
            <div class="col-md-12">
              <line-chart
                v-if="loaded"
                :chart-id="product.id"
                :chart-data="datacollection"
                :chart-options="options">
              </line-chart>
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
Vue.component('line-chart', {
  extends: VueChartJs.Line,
  props: ['chartData', 'chartOptions'],
  mounted() {
    this.renderChart(this.chartData, this.chartOptions);
  },
});

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
    },
    loaded: false,
    datacollection: {
      labels: [],
      datasets: [
        {
          label: 'Realitation',
          backgroundColor: 'transparent',
          borderColor: '#2babab',
          borderWidth: 2,
          fill: false,
          data: [],
        },
        {
          label: 'Target',
          backgroundColor: 'transparent',
          borderColor: '#ff6384',
          borderWidth: 2,
          fill: false,
          data: [],
        }
      ]
    },
    options: {
      title: {
        display: true,
        text: 'Product Chart',
      },
      scales: {
        yAxes: [{
          ticks: {
            beginAtZero: true,
          }
        }],
        xAxes: [{
          ticks: {
            autoSkip: false,
            maxRotation: 90,
            minRotation: 30,
          }
        }]
      },
      responsive: true,
      maintainAspectRatio: false
    },
    product: {
      id: 'product-chart',
      url: '/statistic/get-chart',
    },
    listMonths: moment.months(),
    month: 'all',
  },
  mounted() {
    this.getRevenue();
    this.getItemsSold();
    this.getStore();
    this.getChartData();
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
        this.total.sold = response.data;
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
    async getChartData() {
      this.loaded = false;
      try {
        const response = await axios.get(this.product.url, {
          params: {
            // month: this.month,
          }
        });
        let label = response.data.label;
        let dataRealitation = response.data.dataRealitation;
        let dataTarget = response.data.dataTarget;
        this.datacollection.labels = label;
        this.datacollection.datasets[0].data = dataRealitation;
        this.datacollection.datasets[1].data = dataTarget;
        this.loaded = true;
        console.log(response);
      } catch (error) {
        console.error(error);
      }
    },
    getMonth() {
    }
  }
})
</script>
@endpush