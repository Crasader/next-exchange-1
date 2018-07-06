@extends('_layouts.main')

@section('content')



    <div id="main-container">
  <div class="banner banner-mini bg-blue">
        <span data-lang-id="dashboard_pageTitle">Exchange</span>
    </div>


                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                    @endif

                           @include('_partials.dashboard-sidenav')


                            <div class="page-wrapper" style="padding-left:240px; padding-top: 40px;">

                                <!-- ============================================================== -->
                                <!-- Container fluid  -->
                                <!-- ============================================================== -->
                                <div class="container-fluid">
                                    <!-- ============================================================== -->
                                    <!-- Bread crumb and right sidebar toggle -->
                                    <!-- ============================================================== -->
                                    <div class="row page-titles">
                                        <div class="col-md-5 align-self-center">
                                            <h3 class="text-themecolor">Exchange</h3>
                                        </div>
                                        <div class="col-md-7 align-self-center">
                                            <ol class="breadcrumb">
                                                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                                                <li class="breadcrumb-item">Dashboard</li>
                                                <li class="breadcrumb-item active">Exchange</li>
                                            </ol>
                                        </div>

                                    </div>
                                    <!-- ============================================================== -->
                                    <!-- End Bread crumb and right sidebar toggle -->
                                    <!-- ============================================================== -->

                                    <!-- ============================================================== -->
                                    <!-- Start Page Content -->
                                    <!-- ============================================================== -->
                                    <div class="row">


                                            <div class="col-md-6">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h4 class="card-title">NEXT</h4>
                                                        <div id="morris-line-chart"></div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-md-3">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h4 class="card-title">BUY NEXT</h4>
                                                        <div id="morris-line-chart"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        <div class="col-md-3">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h4 class="card-title">SELL NEXT</h4>
                                                    <div id="morris-line-chart"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h4 class="card-title">MY ORDERBOOK</h4>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h4 class="card-title">ORDERBOOK</h4>

                                                </div>
                                            </div>
                                        </div>


                            </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h4 class="card-title">MARKET-DEPTH</h4>

                                                </div>
                                            </div>
                                        </div>
                                    </div>



        </div>


@endsection
