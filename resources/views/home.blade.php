@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">Dashboard{{--  <br> {{ date("Y-m-d H:i:s", strtotime("-1 month")) }} <br> {{ date('Y-m-d', strtotime(date('Y-m')." -1 month")) }} --}}</div>
        <div class="card-body">
          <div class="row mb-3">
            <div class="col-md-3">
              <label>Month</label>
              <select class="custom-select" @change="getMonth()" v-model="month">
                <option value="all">All</option>
                <option v-for="(value, index) in listMonths" :value="index">@{{ value }}</option>
              </select>
            </div>
          </div>
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
          <div class="row mb-3">
            <div class="col-md-12">
              <line-chart
                v-if="product.loaded"
                :chart-id="product.id"
                :chart-data="chartProduct.datacollection"
                :chart-options="chartProduct.options">
              </line-chart>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-12">
              <bar-chart
                v-if="revenueProduct.loaded"
                :chart-id="revenueProduct.id"
                :chart-data="chartRevenueProduct.datacollection"
                :chart-options="chartRevenueProduct.options">
              </bar-chart>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-6">
              <doughnut-chart
                v-if="storeLocation.loaded"
                :chart-id="storeLocation.id"
                :chart-data="chartStoreLocation.datacollection"
                :chart-options="chartStoreLocation.options">
              </doughnut-chart>
            </div>
            <div class="col-md-6">
              <bar-chart
                v-if="itemsSoldStore.loaded"
                :chart-id="itemsSoldStore.id"
                :chart-data="chartItemsSoldStore.datacollection"
                :chart-options="chartItemsSoldStore.options">
              </bar-chart>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-6">
              <bar-chart
                v-if="avgTimeSales.loaded"
                :chart-id="avgTimeSales.id"
                :chart-data="chartAvgTimeSales.datacollection"
                :chart-options="chartAvgTimeSales.options">
              </bar-chart>
            </div>
            <div class="col-md-6">
              <table class="table table-hover">
                <thead class="thead-dark">
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Sales</th>
                    <th scope="col">Days(s)</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(person, index) in sales">
                    <th scope="row">@{{ index+1 }}</th>
                    <td><a :href="url_avg_time+person.id" target="_blank">@{{ person.name }}</a></td>
                    <td>@{{ person.day }}</td>
                  </tr>
                  <tr>
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
<!-- Vue-ChartJS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>
<script src="https://unpkg.com/vue-chartjs/dist/vue-chartjs.min.js"></script>
<script type="text/javascript">
Vue.component('line-chart', {
  extends: VueChartJs.Line,
  mixins: [VueChartJs.mixins.reactiveProp],
  props: ['chartData', 'chartOptions'],
  mounted() {
    this.renderChart(this.chartData, this.chartOptions);
  },
  watch: {
    chartData() {
      this.$data._chart.update()
    }
  },
});

Vue.component('bar-chart', {
  extends: VueChartJs.Bar,
  mixins: [VueChartJs.mixins.reactiveProp],
  props: ['chartData', 'chartOptions'],
  mounted() {
    this.renderChart(this.chartData, this.chartOptions);
  },
  watch: {
    chartData() {
      this.$data._chart.update()
    }
  },
});

Vue.component('doughnut-chart', {
  extends: VueChartJs.Doughnut,
  mixins: [VueChartJs.mixins.reactiveProp],
  props: ['chartData', 'chartOptions'],
  mounted() {
    this.renderChart(this.chartData, this.chartOptions);
  },
  watch: {
    chartData() {
      this.$data._chart.update()
    }
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
    chartProduct: {
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
    },
    chartRevenueProduct: {
      datacollection: {
        labels: [],
        datasets: [
          {
            label: 'Revenue',
            backgroundColor: '#ffcd56',
            borderColor: '#ffcd56',
            borderWidth: 1,
            data: [],
          }
        ],
      },
      options: {
        title: {
          display: true,
          text: 'Revenue Product Chart',
        },
        tooltips: {
           callbacks: {
              label: function(t, d) {
                 var xLabel = d.datasets[t.datasetIndex].label;
                 var yLabel = t.yLabel.toString();
                 yLabel = yLabel.split(/(?=(?:...)*$)/);
                 yLabel = yLabel.join(',');
                 return xLabel + ': Rp' + yLabel;
              }
           }
        },
        scales: {
          yAxes: [{
            ticks: {
              beginAtZero: true,
              userCallback: function(value, index, values) {
                  // Convert the number to a string and splite the string every 3 charaters from the end
                  value = value.toString();
                  value = value.split(/(?=(?:...)*$)/);
                  // Convert the array to a string and format the output
                  value = value.join(',');
                  return 'Rp' + value;
              },
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
    },
    chartStoreLocation: {
      datacollection: {
        labels: [],
        datasets: [
          {
            label: 'Location',
            backgroundColor: [
              '#ff6384',
              '#ffcd56',
              '#4bc0c0',
              '#36a2eb',
            ],
            data: [],
          }
        ],
      },
      options: {
        title: {
          display: true,
          text: 'Customer/Store Location',
        },
        responsive: true,
        maintainAspectRatio: false
      },
    },
    chartItemsSoldStore: {
      datacollection: {
        labels: [],
        datasets: [
          {
            label: 'QTY',
            backgroundColor: '#c9cbcf',
            borderColor: '#c9cbcf',
            borderWidth: 1,
            data: [],
          }
        ],
      },
      options: {
        title: {
          display: true,
          text: 'Items Sold Chart',
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
    },
    chartAvgTimeSales: {
      datacollection: {
        labels: [],
        datasets: [
          {
            label: 'Day(s)',
            backgroundColor: '#4bc0c0',
            borderColor: '#4bc0c0',
            borderWidth: 1,
            data: [],
          }
        ],
      },
      options: {
        title: {
          display: true,
          text: 'Average Time Chart',
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
    },
    product: {
      id: 'product-chart',
      url: '/statistic/get-chart',
      loaded: false,
    },
    revenueProduct: {
      id: 'revenue-product-chart',
      url: '/statistic/get-revenue-product',
      loaded: false,
    },
    storeLocation: {
      id: 'store-location-chart',
      url: '/statistic/get-store-location',
      loaded: false,
    },
    itemsSoldStore: {
      id: 'items-sold-store-chart',
      url: '/statistic/get-items-sold-store',
      loaded: false,
    },
    avgTimeSales: {
      id: 'avg-time-store-chart',
      url: '/statistic/get-avg-time-sales',
      loaded: false,
    },
    sales: [],
    url_avg_time: '/user/view-avg-time/',
    listMonths: moment.months(),
    month: {{ date('m')-1 }},
  },
  mounted() {
    this.getRevenue();
    this.getItemsSold();
    this.getStore();
    this.getChartData();
    this.getChartRevenueProduct();
    this.getChartStoreLocation();
    this.getChartItemsSoldStore();
    this.getChartAvgTimeSales();
  },
  methods: {
    async getRevenue() {
      try {
        const response = await axios.get('/statistic/get-revenue', {
          params: {
            month: this.month,
          }
        });
        this.total.revenue = response.data;
        console.log(response);
      } catch (error) {
        console.error(error);
      }
    },
    async getItemsSold() {
      try {
        const response = await axios.get('/statistic/get-items-sold', {
          params: {
            month: this.month,
          }
        });
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
      this.product.loaded = false;
      try {
        const response = await axios.get(this.product.url, {
          params: {
            month: this.month,
          }
        });
        let label = response.data.label;
        let dataRealitation = response.data.dataRealitation;
        let dataTarget = response.data.dataTarget;
        this.chartProduct.datacollection.labels = label;
        this.chartProduct.datacollection.datasets[0].data = dataRealitation;
        this.chartProduct.datacollection.datasets[1].data = dataTarget;
        this.product.loaded = true;
        console.log(response);
      } catch (error) {
        console.error(error);
      }
    },
    async getChartRevenueProduct() {
      this.revenueProduct.loaded = false;
      try {
        const response = await axios.get(this.revenueProduct.url, {
          params: {
            month: this.month,
          }
        });
        let label = response.data.label;
        let data = response.data.data;
        this.chartRevenueProduct.datacollection.labels = label;
        this.chartRevenueProduct.datacollection.datasets[0].data = data;
        this.revenueProduct.loaded = true;
        console.log(response);
      } catch (error) {
        console.error(error);
      }
    },
    async getChartStoreLocation() {
      this.storeLocation.loaded = false;
      try {
        const response = await axios.get(this.storeLocation.url, {
          params: {
          }
        });
        let label = response.data.label;
        let data = response.data.data;
        this.chartStoreLocation.datacollection.labels = label;
        this.chartStoreLocation.datacollection.datasets[0].data = data;
        this.storeLocation.loaded = true;
        console.log(response);
      } catch (error) {
        console.error(error);
      }
    },
    async getChartItemsSoldStore() {
      this.itemsSoldStore.loaded = false;
      try {
        const response = await axios.get(this.itemsSoldStore.url, {
          params: {
          }
        });
        let label = response.data.label;
        let data = response.data.data;
        this.chartItemsSoldStore.datacollection.labels = label;
        this.chartItemsSoldStore.datacollection.datasets[0].data = data;
        this.itemsSoldStore.loaded = true;
        console.log(response);
      } catch (error) {
        console.error(error);
      }
    },
    async getChartAvgTimeSales() {
      this.avgTimeSales.loaded = false;
      try {
        const response = await axios.get(this.avgTimeSales.url, {
          params: {
          }
        });
        let id = response.data.id;
        let label = response.data.label;
        let data = response.data.data;
        this.chartAvgTimeSales.datacollection.labels = label;
        this.chartAvgTimeSales.datacollection.datasets[0].data = data;
        this.avgTimeSales.loaded = true;
        for(let i = 0; i < label.length; i++) {
          this.sales.push({
            id: id[i],
            name: label[i],
            day: data[i],
          });
        }
        console.log(response);
      } catch (error) {
        console.error(error);
      }
    },
    getMonth() {
      this.getRevenue();
      this.getItemsSold();
      this.getChartData();
      this.getChartRevenueProduct();
    }
  }
})
</script>
@endpush