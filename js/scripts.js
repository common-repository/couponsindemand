//docReady( function() {




jQuery.noConflict()(function ($) { 

	
	var baseurl = 'http://www.couponsindemand.com/cfc/couponScan.cfc?method=insBarcodeScan';
	
	function redeemCoupon() {
		
		var surl = baseurl + '&callback=?';
		var thisPin = $( this ).closest("div.CID-pin");
		var thisButton = $( this ).closest("div.get-coupon-button");
		
		$.getJSON(surl, {couponid : $(this).attr("couponid")}, function(data) {
			$( thisPin ).closest("div.CID-pin").css({"background-color":"#DEDEDE","border":"2px solid black"});
			$( thisButton ).closest('.get-coupon-button').html('<a class="thankyou">Thank you</a>');
		});
		return false;
	}
	$(document).ready(function() {
		$(".redeemBtn").click(redeemCoupon);
	});
	
	

	//$(function(){
	  $(window).load(function(){

		  var container = document.querySelector('#cid-container');
		  var iso = window.iso = new Isotope( container, {									 
			itemSelector: '.element',
			layoutMode: 'masonry',
		
			masonry: {
				columnWidth: 2
			},
			cellsByRow: {
				columnWidth: 110,
				rowHeight: 110
			},
			masonryHorizontal: {
				rowHeight: 55
			},
			cellsByColumn: {
				columnWidth: 110,
				rowHeight: 110
			},
			getSortData: {
			  number: '.number parseInt',
			  symbol: '.symbol',
			  name: '.name',
			  category: '[data-category]',
			  weight: '.weight'
			}
		  });

		  var options = document.querySelector('#options');
		
		  eventie.bind( options, 'click', function( event ) {
			if ( !matchesSelector( event.target, 'button' ) ) {
			  return;
			}
		
			var key = event.target.parentNode.getAttribute('data-isotope-key');
			var value = event.target.getAttribute('data-isotope-value');
		
			if ( key === 'filter' && value === 'number-greater-than-50' ) {
			  value = function( elem ) {
				var numberText = getText( elem.querySelector('.number') );
				return parseInt( numberText, 10 ) > 40;
			  };
			}
			//console.log( key, value );
			iso.options[ key ] = value;
			iso.arrange();
		  });
		  
	});
});
function getText( elem ) {
  	return elem.textContent || elem.innerText;
}