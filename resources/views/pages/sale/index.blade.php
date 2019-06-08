@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="card">
        <div class="card-header">Sale</div>

        <div class="card-body">
          <div class="form-group row">
            <div class="col-md-3">
              <button @click="create()" class="btn btn-primary">Create</button>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-12">
              <div class="accordion" id="accordionExample" v-if="isLoading">
                <div class="card" v-for="(sale, index) in sales">
                  <div class="card-header" :id="'heading'+index">
                    <h2 class="mb-0">
                      <button class="btn btn-link" type="button" data-toggle="collapse" :data-target="'#collapse'+index" aria-expanded="true" aria-controls="'collapse'+index">
                        @{{ sale.customer_name }} - @{{ sale.created_at | formatDate }}
                      </button>
                    </h2>
                  </div>
                  <div :id="'collapse'+index" class="collapse" :aria-labelledby="'heading'+index" data-parent="#accordionExample">
                    <div class="card-body">
                      @{{ sale.books.title }} - [@{{ sale.books.categories.name }}]<br>
                      @{{ sale.cost }}
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@component('components.modal_sale_create', ['title' => 'Create Sale'])
@endcomponent

@endsection

@push('scripts')
<script type="text/javascript">
$(document).ready(function() {
  var tables = $('#sales-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: 'category/get-datatables',
    order: [[ 1, 'asc' ]],
    columns: [
      { data: 'id', name: 'id', searchable: false },
      { data: 'name', name: 'name' },
      { data: 'books_count', name: 'books_count', searchable: false },
      /* ACTION */ {
        render: function (data, type, row) {
          return "<button id='modal-edit' class='btn btn-sm btn-primary' data-id='"+row.id+"' data-name='"+row.name+"'>Edit</button>&nbsp;<button onclick='checkDelete("+row.id+")' class='btn btn-sm btn-danger'>Delete</button>";
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
  app.name = ($(this).data('name'));
  $("#modal-category-edit").modal('show');
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
    id: '',
    customer_name: '',
    books: '',
    listBooks: [],
    qty: '',
    price: '',
    amount: '',
    cost: '',
    sales: [],
    isLoading: false,
  },
  created() {
    this.getData();
    this.getBook();
  },
  methods: {
    async create(submit) {
      if(submit) {
        try {
          const response = await axios.post('sale', {
            customer_name: this.customer_name,
            books_id: this.books.id,
            amount: this.amount,
            cost: this.cost,
          });
          this.initForm();
          $('#modal-sale-create').modal('hide');
          $('#sales-table').DataTable().ajax.reload();
          Toast.fire({
            type: 'success',
            title: 'Created'
          });
          console.log(response);
        } catch(error) {
          console.error(error);
        }
      }
      else {
        $('#modal-sale-create').modal('show');
      }
    },
    async delete(id) {
      try {
        const response = await axios.delete('category/'+id);
        $('#sales-table').DataTable().ajax.reload();
        Toast.fire({
          type: 'success',
          title: 'Deleted'
        });
        console.log(response);
      } catch(error) {
        console.error(error);
      }
    },
    async getData() {
      try {
        const response = await axios.get('sale/get-data');
        this.sales = response.data.data;
        this.isLoading = true;
        console.log(response);
      } catch (error) {
        console.error(error);
      }
    },
    async getBook() {
      try {
        const response = await axios.get('book/get-data');
        this.listBooks = response.data.data;
        console.log(response);
      } catch (error) {
        console.error(error);
      }
    },
    async getDetail() {
      try {
        const response = await axios.get('book/'+this.books.id);
        this.qty = response.data.qty;
        this.price = response.data.price;
        console.log(response);
      } catch (error) {
        console.error(error);
      }
    },
    initForm() {
      this.customer_name = '';
      this.books = '',
      this.qty = '';
      this.price = '';
      this.amount = '';
      this.cost = '';
    },
    customLabel({title, categories}) {
      return `${title} - [${categories.name}]`;
    }
  },
  computed: {
    checkQty() {
      if(this.qty < this.amount) {
        this.amount = '';
        return alert('Quantity is not enough');
      }
    }
  },
  watch: {
    amount: function(val) {
      this.cost = val * this.price;
      this.checkQty;
    }
  }
})
</script>
@endpush