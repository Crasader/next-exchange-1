<style>
	#tokencap .thead { 
		padding: 5px; color: #fff;
		font-weight:700;
		background: #202D33;
	}
	#tokencap .result { 
		padding: 5px; color: #202D33;
	}
</style>
<section id="tokencap">
@include('pages.market-cap-ajax')
</section>
<script type="text/javascript">
    function refreshTable() {
        $.ajax({
            url: "top_market_cap",
            type: 'GET',
            success: function (data) {
                $('#tokencap').html(data);
            }
        });
    }
    
    $(document).ready(function () {
        setInterval(refreshTable, 60000);
    });
</script>
