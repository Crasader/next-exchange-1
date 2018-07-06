<div id="load" style="position: relative;">
    <table class="table next-table">
        <thead>
        <tr>
            <td>#</td>
            <td>Date</td>
            <td>TxID</td>
            <td>Type</td>
            <td>Buy/Sell</td>
            <td>Amount</td>
            <td>Confirmed</td>
        </tr>
        </thead>
        <tbody>
        @foreach($transactions as $transaction)
            <tr>
                <td>{{ $transaction->transaction_id }}</td>
                <td>{{ $transaction->created_at }}</td>
                <td>

                    @if($transaction->main_coin == 'BTC')
                        <span><a href="https://blockchain.info/tx/{{ $transaction->transaction_rxid }}"
                                 target="_blank">{{ mb_strimwidth($transaction->transaction_rxid, 0, 32, '...') }}</a></span>
                    @elseif($transaction->main_coin = 'ETH')
                        <span><a href="https://etherscan.io/tx/{{ $transaction->transaction_rxid }}"
                                 target="_blank">{{ mb_strimwidth($transaction->transaction_rxid, 0, 32, '...') }}</a></span>
                    @elseif($transaction->main_coin == 'XRP')
                        <span><a href="https://bithomp.com/explorer/{{ $transaction->transaction_rxid }}"
                                 target="_blank">{{ mb_strimwidth($transaction->transaction_rxid, 0, 32, '...') }}</a></span>
                    @elseif($transaction->main_coin == 'DASH')
                        <span><a href="https://explorer.dash.org/tx/{{ $transaction->transaction_rxid }}"
                                 target="_blank">{{ mb_strimwidth($transaction->transaction_rxid, 0, 32, '...') }}</a></span>
                    @elseif($transaction->main_coin == 'LTC')
                        <span><a href="https://bchain.info/LTC/tx/{{ $transaction->transaction_rxid }}"
                                 target="_blank">{{ mb_strimwidth($transaction->transaction_rxid, 0, 32, '...') }}</a></span>
                    @elseif($transaction->main_coin == 'XMR')
                        <span><a href="https://moneroexplorer.com/tx/{{ $transaction->transaction_rxid }}"
                                 target="_blank">{{ mb_strimwidth($transaction->transaction_rxid, 0, 32, '...') }}</a></span>
                    @elseif($transaction->main_coin == 'LTC')
                        <span><a href="https://bchain.info/LTC/tx/{{ $transaction->transaction_rxid }}"
                                 target="_blank">{{ mb_strimwidth($transaction->transaction_rxid, 0, 32, '...') }}</a></span>
                    @elseif($transaction->main_coin == 'ADA')
                        <span><a href="https://cardanoexplorer.com/tx/{{ $transaction->transaction_rxid }}"
                                 target="_blank">{{ mb_strimwidth($transaction->transaction_rxid, 0, 32, '...') }}</a></span>
                    @elseif($transaction->main_coin == 'XEM')
                        <span><a href="http://chain.nem.ninja/#/transfer/{{ $transaction->transaction_rxid }}"
                                 target="_blank">{{ mb_strimwidth($transaction->transaction_rxid, 0, 32, '...') }}</a></span>
                    @elseif($transaction->main_coin == 'NEXT')
                        <span><a href="https://etherscan.io/tx/{{ $transaction->transaction_rxid }}"
                                 target="_blank">{{ mb_strimwidth($transaction->transaction_rxid, 0, 32, '...') }}</a></span>
                    @elseif($transaction->main_coin == 'ETN')
                        <span><a href="https://blockexplorer.electroneum.com/tx/{{ $transaction->transaction_rxid }}"
                                 target="_blank">{{ mb_strimwidth($transaction->transaction_rxid, 0, 32, '...') }}</a></span>
                    @elseif($transaction->main_coin == 'NEXT')
                        <span><a href="https://etherscan.io/tx/{{ $transaction->transaction_rxid }}"
                                 target="_blank">{{ mb_strimwidth($transaction->transaction_rxid, 0, 32, '...') }}</a></span>
                    @elseif($transaction->main_coin == 'XVG')
                        <span><a href="https://verge-blockchain.info/tx/{{ $transaction->transaction_rxid }}"
                                 target="_blank">{{ mb_strimwidth($transaction->transaction_rxid, 0, 32, '...') }}</a></span>
                    @elseif($transaction->main_coin == 'SKY')
                        <span><a href="https://explorer.skycoin.net/app/transaction/{{ $transaction->transaction_rxid }}"
                                 target="_blank">{{ mb_strimwidth($transaction->transaction_rxid, 0, 32, '...') }}</a></span>
                    @elseif($transaction->main_coin == 'ECA')
                        <span><a href="https://www.electraexplorer.com/tx/{{ $transaction->transaction_rxid }}"
                                 target="_blank">{{ mb_strimwidth($transaction->transaction_rxid, 0, 32, '...') }}</a></span>
                    @elseif($transaction->main_coin == 'LYL')
                        <span><a href="http://explorer.nemchina.com/#/s_tx?hash={{ $transaction->transaction_rxid }}"
                                 target="_blank">{{ mb_strimwidth($transaction->transaction_rxid, 0, 32, '...') }}</a></span>
                    @else
                        <span><a href="#"
                                 target="_blank">{{ mb_strimwidth($transaction->transaction_rxid, 0, 32, '...') }}</a></span>
                    @endif
                </td>
                <td>
                <span>
                    @if($transaction->transaction_type == 1)
                        Deposit
                    @elseif($transaction->transaction_type == 2)
                        Withdraw
                    @elseif($transaction->transaction_type == 3)
                        Order Execution
                    @endif
                </span>
                </td>
                <td>
                <span>
                    @if($transaction->transaction_buysell == 1)
                        Buy
                    @elseif($transaction->transaction_buysell == 2)
                        Sell
                    @endif
                </span>

                </td>
                <td>
                    @if($transaction->transaction_buysell == 1)
                        <img src="/img/coin/16/{{ $transaction->maincoin_name }}.png"
                             onerror="this.src='/img/coin/16/noimage.png'"
                             title="{{ $transaction->maincoin_name }}">
                        {{ floatval($transaction->transaction_maincoin_amount) }}
                    @elseif($transaction->transaction_buysell == 2)
                        <img src="/img/coin/16/{{ $transaction->market_name }}.png"
                             onerror="this.src='/img/coin/16/noimage.png'"
                             title="{{ $transaction->market_name }}">
                        {{ floatval($transaction->transaction_amount) }}
                    @else
                        <img src="/img/coin/16/{{ $transaction->maincoin_name }}.png"
                             onerror="this.src='/img/coin/16/noimage.png'"
                             title="{{ $transaction->maincoin_name }}">
                        {{ floatval($transaction->transaction_amount) }}
                    @endif
                </td>
                <td>
                    @if($transaction->transaction_status == 1)
                        <i class="fa fa-check-circle text-success" title="Confirmed"> Confirmed</i>
                    @elseif($transaction->transaction_status == 0)
                        <i class="fa fa-exclamation-circle text-warning" title="Unconfirmed"> Unconfirmed</i>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

{{ $transactions->links() }}
