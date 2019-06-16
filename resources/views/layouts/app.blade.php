<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/vue-multiselect@2.1.0/dist/vue-multiselect.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
</head>
<body>
    <div>
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul id="nav" class="navbar-nav mr-auto">
                        @auth
                            @if(Auth::user()->role == 1)
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('delivery-order.create') }}">{{ __('Create Delivery Order') }}</a>
                                </li>
                            @elseif(Auth::user()->role == 2)
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('invoice.create') }}">{{ __('Create Invoice') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('invoice.index') }}">{{ __('List Invoice') }}</a>
                                </li>
                            @endif
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('delivery-order.index') }}">{{ __('List Delivery Order') }} <span class="badge badge-danger badge-pill" v-show="display">@{{ statusNew }}</span></a>
                            </li>
                        @endauth
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('user.changePasswordForm') }}">
                                        {{ __('Change Password') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <div id="app">
            <main class="py-4">
                @yield('content')
            </main>
        </div>
    </div>
    <!-- Scripts -->
    <!--<script src="{{ asset('js/app.js') }}" defer></script>-->
    <!-- jQuery -->
    <script
        src="https://code.jquery.com/jquery-3.4.1.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
        crossorigin="anonymous">
    </script>
    <!-- Popper -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <!-- Bootstrap -->
    <script
        src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous">
    </script>
    <!-- Vue -->
    <script src="{{ asset('js/vue.min.js') }}"></script>
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <!-- SweetAlert -->
    @include('sweetalert::alert')
    <script type="text/javascript">
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 1500
        });
    </script>
    <!-- Moment -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script type="text/javascript">
        Vue.filter('formatDate', function(value) {
            if(value) {
                return moment(String(value)).format('DD MMMM YYYY, HH:mm:ss');
            }
        });
        Vue.mixin({
            methods: {
                humanDate(value) {
                    return moment(String(value)).format('DD MMM YYYY');
                },
                formatStatus(value) {
                    if(value == '0') {
                        return '<span class="badge badge-pill badge-primary">New</span>';
                    }
                    else if(value == '1') {
                        return '<span class="badge badge-pill badge-warning">On Progress</span>';
                    }
                    else if(value == '2') {
                        return '<span class="badge badge-pill badge-success">Complete</span>';
                    }
                },
            }
        });
    </script>
    <!-- Vue-Multiselect -->
    <script src="https://unpkg.com/vue-multiselect@2.1.0"></script>
    <!-- Vue-ChartJS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>
    <script src="https://unpkg.com/vue-chartjs/dist/vue-chartjs.min.js"></script>
    <!-- Vue Currency Filter Dependency -->
    <script src="https://unpkg.com/vue-currency-filter@3.2.3/dist/vue-currency-filter.iife.js"></script>
    <script type="text/javascript">
        if(VueCurrencyFilter) {
            Vue.use(VueCurrencyFilter, {
                symbol: "",
                thousandsSeparator: ".",
                fractionCount: 0,
                fractionSeparator: ".",
                symbolPosition: "front",
                symbolSpacing: false
            })
        }
    </script>

    <!-- Global Components -->
    <script type="text/javascript">
        Vue.component('multiselect', window.VueMultiselect.default);
    </script>

    @auth
      <script type="text/javascript">
      var nav = new Vue({
        el: '#nav',
        data: {
          statusNew: 0,
          display: false,
        },
        created() {
          this.getStatus();
        },
        methods: {
          async getStatus() {
            this.display = false;
            try {
              const response = await axios.get('/status/get-data', {
                params: {
                  sales_id: {{ Auth::user()->id }},
                }
              });
              this.statusNew = response.data;
              if(this.statusNew != 0) {
                  this.display = true;
              }
              console.log(response);
            } catch (error) {
              console.error(error);
            }
          },
        }
      });
      </script>
    @endauth

    @stack('scripts')
</body>
</html>
