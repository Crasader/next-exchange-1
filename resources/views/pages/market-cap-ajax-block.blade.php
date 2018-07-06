@if(isset($data['cmc']))
    @php ($i=0)
    @foreach(@$data['cmc'] as $val)
        @if($i<10)
            <div class="carousel1__item">
                <div class="block-currency1">
                    <!-- <img src="img/coin/64/{{ $val['symbol'] }}.png" alt="" class="block-currency1__bottom-image"> -->
                    <div class="block-currency1__top">


                       <img src="img/coin/32/{{ $val['symbol'] }}.png" alt="{{ $val['symbol'] }}" class="block-currency1__left">
                        <div class="block-currency1__over">
                            <div class="block-currency1__vertical">
                                <span>{{ $val['name'] }}</span>
                                <p>{{ $val['symbol'] }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="block-currency1__text">
                        <span>$ {{ \App\Helpers\Helper::formatCurrency($val['price_usd'], 2) }}</span>
                        @if($val['percent_change_24h'] >= 0)
                        <p>{{ $val['percent_change_24h'] }}%</p>
                        @else
                        <p class="mod1">{{ $val['percent_change_24h'] }}%</p>
                        @endif
                    </div>
                </div>
            </div>
            @php ($i++)
        @endif
    @endforeach
@endif