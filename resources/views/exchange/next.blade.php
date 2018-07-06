@extends('_layouts.main')

@section('content')
    @include('_layouts.topnav')
    @include('_partials.flashalert')
    <?php
    define('REFRESH_SECONDS', 30);
    ?>
    <section id="members" class="ptb40 bg--secondary">
        <div class="container">
            <img class="center" src="/img/coin/64/{{ $coin->coin_coin }}.png"
                 onerror="this.src='/img/coin/64/noimage.png'" height="64" class="img-responsive mx-auto d-block"><br>

            <h1 class="center">{{ $coin->coin_title }} TRADING TOOL (BETA)</h1><br>

            <div class="app-odrs">
                <main class="exchanger-app text-center" id="exchanger_err" style="display: none">
                    <h3>Something is wrong.<br/> Unable to connect with Metamask or Smart Contract</h3>
                </main>
                <main class="exchanger-app" id="exchanger_app">
                    <div class="app-inner">

                        <section class="orders-container">
                            <div class="orders-inner">
                                <div class="clearfix">
                                    <h5 class="float-left">
                                        <span class="text-uppercase" id="walletAddress"></span>
                                    </h5>
                                </div>
                                <hr>
                                <div class="clearfix">
                                    <h5 class="float-left">
                                        <span class="text-uppercase" id="tokenBalance"></span></span>
                                    </h5>
                                    <h5 class="float-right">
                                        <span class="text-uppercase" id="etherBalance"></span>
                                    </h5>
                                </div>
                                <hr>
                                <div class="clearfix">
                                    <h5 class="float-left">
                                        <span class="text-uppercase"><span style="width: 160px">Allowance : </span><span
                                                    class="tokenAllowance"></span></span><br/>
                                        <span class="text-uppercase"><span
                                                    style="width: 160px">Tokens Open : </span><span
                                                    class="totalUserMakeTokens"></span></span><br/>
                                        <span class="text-uppercase"><span
                                                    style="width: 160px">Tokens Sold : </span><span
                                                    class="totalUserTakeTokens"></span></span>
                                    </h5>
                                    <h5 class="float-right">
                                        <input type="number" style="width: auto;" id="txtIncreaseAllowance">
                                        <a href="javascript:void(null)" id="lnkIncreaseAllowance"
                                           class="btn btn-primary waves-effect"><i class="fa fa-plus"></i> Increase
                                            Allowance</a>
                                    </h5>
                                </div>
                                <hr>
                                <div class="btn-orders">
                                    <a href="javascript:void(null)" id="lnkMakeOrder"
                                       class="btn btn-primary waves-effect">Make Order</a>
                                    <a href="javascript:void(null)" target="_blank"
                                       class="btn btn-default waves-effect lnkTransactions">See All Transaction</a>
                                    <a href="javascript:void(null)" id="lnkResetAllowance"
                                       class="btn btn-danger waves-effect">Reset Allowance - <span
                                                class="tokenAllowance"></span></a>
                                </div>
                                <hr>
                                <div class="text-left">
                                    <h2 class="m-b-15">Important Instructions</h2>
                                    <ul class="list-unstyled">
                                        <li>Please note that it might take a while for a transaction to process. You can
                                            <a href="javascript:void(null)" target="_blank" class="lnkTransactions">click
                                                here</a> to see all of your transactions.
                                        </li>
                                        <li>When an order is placed, it requires a certain amount of time for it to
                                            appear on the Blockchain.
                                        </li>
                                        <li>If you are selling your tokens ▒~@~S the amount will only be deducted once
                                            someone accepts your order.
                                        </li>
                                        <li>An order can only be removed by the one who set the order, it may take some
                                            time for it to reflect on the Blockchain once it is removed.
                                        </li>
                                        <li>Once an order is taken, Ethereum is sent directly to the seller and will
                                            take some time to reflect on the order status and your account balance.
                                        </li>
                                        <li>All order information and data is stored and secure on a Blockchain network.
                                            Transactions are signed using metamask ▒~@~S and we do not hold your private
                                            wallet key.
                                        </li>
                                        <li><b>Please ensure you keep enough allowance set. Your allowance should always
                                                be equal to tokens open.</b></li>
                                    </ul>
                                </div>
                            </div>
                        </section>

                        <hr>

                        <div class="clearfix m-b-10">
                            <h2 class="float-left">Open Orders</h2>
                            <h5 class="float-right">Total Orders: <span id="tokenMakeOrdersCount"></span></h5>
                        </div>

                        <section class="card orders-table-container">
                            <div class="orders-table-inner">
                                <div class="table-container table-responsive">
                                    <table class="table table-hover table-responsive-lg">
                                        <thead>
                                        <tr>
                                            <th class=""><span>Token name</span></th>
                                            <!-- <th class=""><span>Token symbol</span></th> -->
                                            <th class="" style="width: 300px; max-width: 300px">
                                                <span>Seller Account</span></th>
                                            <th class="text-right" style="width: 125px; max-width: 125px"><span>Total Tokens</span>
                                            </th>
                                            <!-- <th class="text-right" style="width: 125px; max-width: 125px"><span>Total Amount</span></th> -->
                                            <th class="text-right" style="width: 135px; max-width: 135px"><span>Available Tokens</span>
                                            </th>
                                            <!-- <th class="text-right" style="width: 125px; max-width: 125px"><span>Sold Tokens</span></th> -->
                                            <th class="text-right" style="width: 150px; max-width: 150px"><span>Price Per Token</span>
                                            </th>
                                            <!-- <th class=""><span>Buyer Account</span></th> -->
                                            <th class="text-left" style="width: 130px; max-width: 130px"></th>
                                        </tr>
                                        </thead>
                                        <tbody id="tbodyMakeOrders">

                                        </tbody>
                                    </table>
                                    <div style="display: none">
                                        <table>
                                            <tr id="trMakeOrder">
                                                <td>
                                                    <small class="tokenName"></small>
                                                </td>
                                                <!-- <td><small class="tokenSymbol"></small></td> -->
                                                <td style="width: 300px; max-width: 300px">
                                                    <small class="sellerAddress text-uppercase"></small>
                                                </td>
                                                <td class="text-right" style="width: 125px; max-width: 125px">
                                                    <small class="numTokens"></small>
                                                </td>
                                                <!-- <td class="text-right" style="width: 125px; max-width: 125px"><small class="numTokensSold"></small></td> -->
                                                <td class="text-right" style="width: 125px; max-width: 125px">
                                                    <small class="numTokensAval"></small>
                                                </td>
                                                <!-- <td class="text-right" style="width: 125px; max-width: 125px"><small class="totalPrice"></small></td> -->
                                                <td class="text-right" style="width: 150px; max-width: 150px">
                                                    <small class="pricePerToken"></small>
                                                </td>
                                                <!-- <td><small class="buyerAddress text-uppercase"></small></td> -->
                                                <td class="actions text-left" style="width: 170px; max-width: 170px">
                                                    <a href="javascript:void(null)"
                                                       class="btn btn-sm btn-info btn-take-order btnTakeOrder">Take
                                                        Order</a>
                                                    <a href="javascript:void(null)"
                                                       class="btn btn-sm btn-danger btn-delete btnDelete">Delete</a>
                                                    <a href="javascript:void(null)"
                                                       class="btn btn-sm btn-danger btn-delete btnDeleteOwner"><i
                                                                class="fa fa-trash"></i></a>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="table-footer-container">
                                    <div class="table-length">
                                        <label for="makeOrderCount">Rows per page:</label>
                                        <select name="length" id="makeOrderCount" class="niceSelect">
                                            <option value="10">10</option>
                                            <option value="20">20</option>
                                            <option value="50">50</option>
                                            <option value="-1">All</option>
                                        </select>
                                    </div>
                                    <div class="table-pagination" id="makeOrderPagination">
                                        <ul class="pagination pagination-circle pg-info mb-0">
                                            <li class="page-item" id="makeOrderPrev">
                                                <a class="page-link waves-effect waves-effect" aria-label="Previous">
                                                    <span aria-hidden="true">«</span>
                                                    <span class="sr-only">Previous</span>
                                                </a>
                                            </li>
                                            <li class="page-item" id="makeOrderNext">
                                                <a class="page-link waves-effect waves-effect" aria-label="Next">
                                                    <span aria-hidden="true">»</span>
                                                    <span class="sr-only">Next</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <hr class="m-t-30">

                        <div class="clearfix m-b-10">
                            <h2 class="float-left">Completed Orders</h2>
                            <h5 class="float-right">Total Orders: <span id="tokenTakeOrdersCount"></span></h5>
                        </div>
                        <section class="card orders-table-container">
                            <div class="orders-table-inner">
                                <div class="table-container table-responsive">
                                    <table class="table table-hover table-responsive-lg">
                                        <thead>
                                        <tr>
                                            <th class=""><span>Token name</span></th>
                                            <th class=""><span>Seller Account</span></th>
                                            <th class="text-right"><span>Total Tokens</span></th>
                                            <th class="text-right"><span>Total Amount</span></th>
                                            <th class="text-right"><span>Price Per Token</span></th>
                                            <!-- <th class=""><span>Buyer Account</span></th> -->
                                        </tr>
                                        </thead>
                                        <tbody id="tbodyTakeOrders">

                                        </tbody>
                                    </table>
                                    <div style="display: none">
                                        <table>
                                            <tr id="trTakeOrder">
                                                <td>
                                                    <small class="tokenName"></small>
                                                </td>
                                                <!-- <td><small class="tokenSymbol"></small></td> -->
                                                <td>
                                                    <small class="sellerAddress text-uppercase"></small>
                                                </td>
                                                <td class="text-right">
                                                    <small class="numTokens"></small>
                                                </td>
                                                <td class="text-right">
                                                    <small class="totalPrice"></small>
                                                </td>
                                                <td class="text-right">
                                                    <small class="pricePerToken"></small>
                                                </td>
                                                <!-- <td><small class="buyerAddress text-uppercase"></small></td> -->
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="table-footer-container">
                                    <div class="table-length">
                                        <label for="takeOrderCount">Rows per page:</label>
                                        <select name="length" id="takeOrderCount" class="niceSelect">
                                            <option value="10">10</option>
                                            <option value="20">20</option>
                                            <option value="50">50</option>
                                            <option value="-1">All</option>
                                        </select>
                                    </div>
                                    <div class="table-pagination" id="takeOrderPagination">
                                        <ul class="pagination pagination-circle pg-info mb-0">
                                            <li class="page-item" id="takeOrderPrev">
                                                <a class="page-link waves-effect waves-effect" aria-label="Previous">
                                                    <span aria-hidden="true">«</span>
                                                    <span class="sr-only">Previous</span>
                                                </a>
                                            </li>
                                            <li class="page-item" id="takeOrderNext">
                                                <a class="page-link waves-effect waves-effect" aria-label="Next">
                                                    <span aria-hidden="true">»</span>
                                                    <span class="sr-only">Next</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </section>

                    </div>
                </main>

                <div class="form-make-order">
                    <div aria-hidden="true" aria-labelledby="myModalLabel" class="modal fade" id="modalMakeOrder"
                         role="dialog" tabindex="-1">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-primary white-text">
                                    <h4 class="title">
                                        <i class="fas fa-cart-plus"></i> Make Order
                                    </h4>
                                    <button aria-label="Close" class="close waves-effect waves-light text-white"
                                            data-dismiss="modal" type="button">
                                                                <span aria-hidden="true">
                                                                        <span class="fas fa-times-circle"></span>
                                                                </span>
                                    </button>
                                </div>
                                <div class="modal-body mb-0">
                                    <form id="frmMakeOrder" name="frmMakeOrder" action="#"
                                          style="background: none; border: none;">
                                        <div class="md-form form-sm">
                                            <input type="text" class="form-control disabled text-uppercase"
                                                   id="makeWalletAddress" placeholder="-" tabindex="-1" readonly/>
                                            <label for="makeWalletAddress">Wallet Address</label>
                                        </div>
                                        <div class="md-form form-sm">
                                            <input type="text" class="form-control disabled text-uppercase"
                                                   id="makeTokenAddress" placeholder="-" tabindex="-1" readonly/>
                                            <label for="makeTokenAddress">Token Address</label>
                                        </div>
                                        <div class="md-form form-sm">
                                            <input type="text" class="form-control disabled" id="makeTokenName"
                                                   placeholder="-" tabindex="-1" readonly/>
                                            <label for="makeTokenName">Token Name</label>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="md-form form-sm">
                                                    <input type="text" class="form-control disabled"
                                                           id="makeTokenSymbol" placeholder="-" tabindex="-1" readonly/>
                                                    <label for="makeTokenSymbol">Token Symbol</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="md-form form-sm">
                                                    <input type="text" class="form-control disabled"
                                                           id="makeTokenDecimal" placeholder="-" tabindex="-1"
                                                           readonly/>
                                                    <label for="makeTokenDecimals">Token Decimals</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="md-form form-sm">
                                                    <input type="text" class="form-control disabled"
                                                           id="makeEtherBalance" placeholder="-" tabindex="-1"
                                                           readonly/>
                                                    <label for="makeEtherBalance">ETH Balance</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="md-form form-sm">
                                                    <input type="text" class="form-control disabled"
                                                           id="makeTokenBalance" value="-" tabindex="-1" readonly/>
                                                    <label for="makeTokenBalance">Token Balance</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="md-form form-sm">
                                                    <input type="number" class="form-control" id="makeTokenAmount"
                                                           min="1" step="any"/>
                                                    <label for="makeTokenAmount">Tokens to Sell</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="md-form form-sm">
                                                    <input type="number" class="form-control" id="makeEtherAmount"
                                                           step="any"/>
                                                    <label for="makeEtherAmount">How many Ether</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="md-form form-sm">
                                                    <input type="number" class="form-control disabled"
                                                           id="makeTokenRateEther" value="-" tabindex="-1" readonly/>
                                                    <label for="makeTokenRateEther">Token Price in Ether</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 hidden">
                                                <div class="md-form form-sm">
                                                    <input type="number" class="form-control disabled"
                                                           id="makeTokenRateUsd" value="-" tabindex="-1" readonly/>
                                                    <label for="makeTokenRateUsd">Token Price in USD</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="md-form-btn text-right">
                                            <button class="btn btn-primary">
                                                Submit Order
                                                <span class="fas fa-paper-plane"></span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div aria-hidden="true" aria-labelledby="myModalLabel" class="modal fade" id="modalTakeOrder"
                         role="dialog" tabindex="-1">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-primary white-text">
                                    <h4 class="title">
                                        <i class="fas fa-cart-plus"></i> Take Order
                                    </h4>
                                    <button aria-label="Close" class="close waves-effect waves-light text-white"
                                            data-dismiss="modal" type="button">
                                                                <span aria-hidden="true">
                                                                        <span class="fas fa-times-circle"></span>
                                                                </span>
                                    </button>
                                </div>
                                <div class="modal-body mb-0">
                                    <form id="frmTakeOrder" name="frmTakeOrder" action="#"
                                          style="background: none; border: none;">
                                        <h5 class="m-b-20">Wallet Information</h5>
                                        <div class="md-form form-sm">
                                            <input type="text" class="form-control disabled text-uppercase"
                                                   id="takeWalletAddress" placeholder="-" tabindex="-1" readonly/>
                                            <label for="takeWalletAddress">Wallet Address</label>
                                        </div>
                                        <div class="md-form form-sm">
                                            <input type="text" class="form-control disabled text-uppercase"
                                                   id="takeOrderSellerAddress" placeholder="-" tabindex="-1" readonly/>
                                            <label for="takeOrderSellerAddress">Seller Address</label>
                                        </div>
                                        <h5 class="m-b-20">Token Information</h5>
                                        <div class="md-form form-sm">
                                            <input type="text" class="form-control disabled text-uppercase"
                                                   id="takeTokenAddress" placeholder="-" tabindex="-1" readonly/>
                                            <label for="takeTokenAddress">Token Address</label>
                                        </div>
                                        <div class="md-form form-sm">
                                            <input type="text" class="form-control disabled" id="takeTokenName"
                                                   placeholder="-" tabindex="-1" readonly/>
                                            <label for="takeTokenName">Token Name</label>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="md-form form-sm">
                                                    <input type="text" class="form-control disabled"
                                                           id="takeTokenSymbol" placeholder="-" tabindex="-1" readonly/>
                                                    <label for="takeTokenSymbol">Token Symbol</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="md-form form-sm">
                                                    <input type="text" class="form-control disabled"
                                                           id="takeTokenDecimal" placeholder="-" tabindex="-1"
                                                           readonly/>
                                                    <label for="takeTokenDecimals">Token Decimals</label>
                                                </div>
                                            </div>
                                        </div>
                                        <h5 class="m-b-20">Your Balance</h5>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="md-form form-sm">
                                                    <input type="text" class="form-control disabled"
                                                           id="takeEtherBalance" placeholder="-" tabindex="-1"
                                                           readonly/>
                                                    <label for="takeEtherBalance">ETH Balance</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="md-form form-sm">
                                                    <input type="text" class="form-control disabled"
                                                           id="takeTokenBalance" value="-" tabindex="-1" readonly/>
                                                    <label for="takeTokenBalance">Token Balance</label>
                                                </div>
                                            </div>
                                        </div>
                                        <h5 class="m-b-20">Order Details</h5>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="md-form form-sm">
                                                    <input type="number" class="form-control" id="takeOrderToken"
                                                           min="1" step="any" value="-" tabindex="-1" readonly/>
                                                    <label for="takeOrderToken">Available Tokens</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="md-form form-sm">
                                                    <input type="number" class="form-control" id="takeOrderPrice"
                                                           step="any" value="-" tabindex="-1" readonly/>
                                                    <label for="takeOrderPrice">Price in Ethers</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="md-form form-sm">
                                                    <input type="number" class="form-control" id="takeTokenAmount"
                                                           min="1" step="any"/>
                                                    <label for="takeTokenAmount">Tokens to Buy</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="md-form form-sm">
                                                    <input type="number" class="form-control" id="takeEtherAmount"
                                                           value="-" tabindex="-1" readonly/>
                                                    <label for="takeEtherAmount">How many Ether</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="md-form form-sm">
                                                    <input type="number" class="form-control disabled"
                                                           id="takeTokenRateEther" value="-" tabindex="-1" readonly/>
                                                    <label for="takeTokenRateEther">Token Price in Ether</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 hidden">
                                                <div class="md-form form-sm">
                                                    <input type="number" class="form-control disabled"
                                                           id="takeTokenRateUsd" value="-" tabindex="-1" readonly/>
                                                    <label for="takeTokenRateUsd">Token Price in USD</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="md-form-btn text-right">
                                            <button class="btn btn-primary">
                                                Buy <span class="takeTokenAmount"></span> Tokens
                                                <span class="fas fa-paper-plane"></span>
                                            </button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <script src="https://code.jquery.com/jquery-2.2.4.js"
            integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cleave.js/1.0.1/cleave.min.js"></script>
    <!-- Additional JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/async/2.6.0/async.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.5.0/js/mdb.min.js"></script>

    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>

    <!-- Vendors Scripts -->
    <script src="{{ asset('truffle/dist/assets/js/vendors.min.js') }}" type="text/javascript"></script>

    <!-- Blockchain Scripts -->
    <script src="{{ asset('truffle/dist/eth/eth.token.order.min.js') }}?rand=<?php echo rand(); ?>"
            type="text/javascript"></script>

    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,700,700i|Roboto:300,300i,400,400i,700,700i"/>

    <!-- Font Awesome -->
    <script defer src="https://use.fontawesome.com/releases/v5.0.4/js/all.js"></script>

    <!-- App Stylesheet -->
    <link rel="stylesheet" href="{{ asset('truffle/dist/assets/css/app.css') }}"/>

    <script>
        $(document).ready(function () {
            Util.base_url = '/truffle/build/contracts';
            ExchangerApp.boot('<?php echo $coin->coin_exchanger ?>', '<?php echo $coin->coin_address ?>', '<?php echo $coin->coin_network ?>');
            $(document).trigger('exchangerReady');
        });

        $(document).on('contractsError', function () {
            $('#exchanger_app').hide();
            $('#exchanger_err').show();
        });

        $(document).on('contractsReady', function () {
            $('#exchanger_app').show();
            $('#exchanger_err').hide();
        });
    </script>


@endsection