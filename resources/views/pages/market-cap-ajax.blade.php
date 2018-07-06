<style>
	.coincap { border: 1px solid #000; font-size: 13px; border-radius: 2px; }
	.row{padding: 0 !important; margin:0 !important; }
	.no-wrap { display: inline; white-space: nowrap }
	.pb40 { padding-bottom: 40px !important; }
</style>


<div class="container">
	<h2 data-lang-id="about_pageSubtitle" class="pb40">Top market cap</h2>

	<div class="coincap">
	<div class="row row-no-padding thead">
		<div class="col-4 col-md-2">Name</div>
		<div class="col-4 col-md-1">Price</div>
		<div class="col-md-2 d-none d-md-block">Market Cap</div>
		<div class="col-md-2 d-none d-md-block">Available Supply</div>
		<div class="col-md-2 d-none d-md-block">Volume 24h</div>
		<div class="col-4 col-md-1">% 1h</div>
		<div class="col-md-1 d-none d-md-block">% 24h</div>
		<div class="col-md-1 d-none d-md-block">% 7d</div>
	</div>

		<?php if(isset($data['cmc'])) { ?>
		@php ($i=0)
		@foreach(@$data['cmc'] as $val)
			@if($i<10)
				<div class="row result">
					<div class="col-4 col-md-2 no-wrap name">{{ $val['name'] }} {{ $val['symbol'] }}</div>
					<div class="col-4 col-md-1 no-wrap">$ {{ \App\Helpers\Helper::formatCurrency($val['price_usd'], 2) }} @if($val['price_usd']) @endif</div>
					<div class="col-md-2 d-none d-md-block">$ {{ \App\Helpers\Helper::formatCurrency($val['market_cap_usd'], 2) }} @if($val['market_cap_usd']) @endif</div>
					<div class="col-md-2 d-none d-md-block">{{ $val['available_supply'] }}  @if($val['available_supply']) {{ $val['symbol'] }} @endif</div>
					<div class="col-md-2 d-none d-md-block">$ {{ \App\Helpers\Helper::formatCurrency($val['24h_volume_usd'],2) }} @if($val['24h_volume_usd']) @endif</div>
					<div @if ($val['percent_change_1h'] > 0) class="col-4 col-md-1 text-green"
						@else
						class="col-4 col-md-1 text-red"
							@endif>
						<i @if ($val['percent_change_1h'] > 0) class="fa fa-arrow-up text-green"
						   @else
						   class="fa fa-arrow-down text-red"
								@endif></i>
						{{ $val['percent_change_1h'] }}
					</div>
					<div @if ($val['percent_change_24h'] > 0) class="col-md-1 d-none d-md-block text-green"
						@else
						class="col-md-1 d-none d-md-block text-red"
							@endif>
						<i @if ($val['percent_change_24h'] > 0) class="fa fa-arrow-up text-green"
						   @else
						   class="fa fa-arrow-down text-red"
								@endif></i>
						{{ $val['percent_change_24h'] }}
					</div>
					<div @if ($val['percent_change_7d'] > 0) class="col-md-1 d-none d-md-block text-green"
						@else
						class="col-md-1 d-none d-md-block text-red"
							@endif>
						<i @if ($val['percent_change_7d'] > 0) class="fa fa-arrow-up text-green"
						   @else
						   class="fa fa-arrow-down text-red"
								@endif></i>
						{{ $val['percent_change_7d'] }}
					</div>
				</div>
			<hr>
				@php ($i++)
			@endif
		@endforeach
		<?php } ?>

</div>
</div>
<div class="spacer-35">&nbsp;</div>