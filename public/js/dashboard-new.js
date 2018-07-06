/* common */
$(document).ready(function() {
	if($('.mask').length>0) {
		$(".mask").mask("+7 (999) 999-99-99");
	}
	$('.currency-sel select,.currency-sel2 select,.check1 input').styler();



	function ress() {
		$('.content-page__left').css({'left':$('.content-page').offset().left});
		if($(window).width()<1025) {
			$('.content-page').addClass('menu-diss');
		}
	}
	ress();
	$(window).resize(function() {
		ress();
	});
	$(window).load(function() {
		ress();
	});
	$('.currency-chose1__current').click(function() {
		$(this).parent().toggleClass('active');
	});
	$(document).click(function(e){
	    if ($(e.target).closest(".currency-chose1").length) return;
		$('.currency-chose1').removeClass('active');
	    e.stopPropagation();
	});
	$('.line-open1').click(function() {
		$(this).toggleClass('active');
		$('.content-page').toggleClass('menu-diss');
	});
	var table1=$('.table2,.table5');
	table1.find('thead tr').addClass('disabled');
	table1.each(function() {
		$(this).find('tr:not(.disabled) td').each(function() {
			$(this).prepend('<div class="title-hide">'+table1.find('tr').eq(0).find('td').eq($(this).index()).text()+'</div>');
		});
		table1.find('.disabled').removeClass('disabled');
	});
	$('.big-fake-select__current').click(function() {
		if($(this).attr('data-dt')==0) {
			$('.big-fake-select__current[data-dt=1]').attr('data-dt','0').parent().removeClass('active');
			$(this).attr('data-dt','1').parent().addClass('active');
		}
		else {
			$(this).attr('data-dt','0').parent().removeClass('active');
		}
	});
	$(document).click(function(e){
	    if ($(e.target).closest(".big-fake-select").length) return;
		$('.big-fake-select__current').attr('data-dt','0').parent().removeClass('active');
	    e.stopPropagation();
	});
	$('.small-form-address1__submit span').click(function() {
		$(this).parent().find('input').click();
	});
	$(".slide1").slider({
		range: 'min',
		min: 0,
		max: 318,
		step: 1,
		value: 150,
		slide: function( event, ui ) {
			//$('.').val(ui.value);
		}
	});
});







