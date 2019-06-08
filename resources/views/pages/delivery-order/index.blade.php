@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">View Delivery Order</div>

        <div class="card-body">
          <div class="form-group row">
            <div class="col-md-12">
              <table class="table table-bordered" id="categories-table">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th>Name</th>
                    <th>Book(s) QTY</th>
                    <th>Action</th>
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