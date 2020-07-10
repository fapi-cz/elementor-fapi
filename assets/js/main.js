( function( $ ) {
	/**
 	 * @param $scope The Widget wrapper element as a jQuery element
	 * @param $ The jQuery alias
	 */ 
	var WidgetHelloWorldHandler = function( $scope, $ ) {
		console.log( $scope );
	};
	
	// Make sure you run this code under Elementor.
	$( window ).on( 'elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/hello-world.default', WidgetHelloWorldHandler );
	} );

	$( '.konfigurator-checkbox').on( 'click', function(){
		$(this).toggleClass( 'checked' );
		var itemId = $(this).data( 'id' );
		$( '.elementor-repeater-item-'+itemId ).toggleClass( 'active' );

		var total = 0;
		var totalEur = 0;

		$('.konfigurator-list-item.active').each(function() {
			var priceData = $(this).find( '.konfigurator-price-value' ); 
			var price = $( priceData ).data( 'price' );
			var priceEur = $( priceData ).data( 'price-eur' );
			total += price;
			totalEur += priceEur;
		});

		var currency = $( '.konfigurator-selector span.active-currency' ).data( 'currency' );

		if( currency == 'czk' ){
			var formatedTotal = String(total).replace(/(?<!\..*)(\d)(?=(?:\d{3})+(?:\.|$))/g, '$1 ');
		} else {
			var formatedTotal = String(totalEur).replace(/(?<!\..*)(\d)(?=(?:\d{3})+(?:\.|$))/g, '$1 ');
		}

		
		$( '.konfigurator-total-price-value' ).text( formatedTotal );
		$( '.konfigurator-total-price-value' ).attr( 'data-total', total );
		$( '.konfigurator-total-price-value' ).attr( 'data-total-eur', totalEur );

	});

	
	jQuery('.konfigurator-button').on( 'click', function(e){
		e.preventDefault();
		jQuery( '#popup-konfigurator' ).toretpopup( 'show' ); 
		
		
	});

	jQuery('.popup-konfigurator-submit').on( 'click', function(e){
		
		e.preventDefault();
            var name        = jQuery( '.konfigurator-first-name' ).val();
            var surname     = jQuery( '.konfigurator-last-name' ).val();
            var email       = jQuery( '.konfigurator-email' ).val();
			var phone       = jQuery( '.konfigurator-phone' ).val();
			var place       = jQuery( '.konfigurator-place' ).val();
			var note        = jQuery( '.konfigurator-note' ).val();
			var robots      = jQuery( '.robots' ).val();
			
			var total       = jQuery( '.konfigurator-total-price-value' ).data( 'total' );
			var totalEur    = jQuery( '.konfigurator-total-price-value' ).data( 'total-eur' );
			var formType    = jQuery( '.konfigurator-total-price-value' ).data( 'form-type' );

        if( robots ) { 
			
            return;
        }

        if( !name || 0 === name.length ) { 
            alert( 'Vyplňte prosím jméno' ); 
            return;
        }
        if( !surname || 0 === surname.length ) { 
            alert( 'Vyplňte prosím příjmení' ); 
            return;
        }
        if( !email || 0 === email.length ) { 
            alert( 'Vyplňte prosím email' ); 
            return;
		}
		if( !phone || 0 === phone.length ) { 
            alert( 'Vyplňte prosím telefon' ); 
            return;
		}
		
		var items = '';

		$('.konfigurator-list-item.active').each(function() {
			var itemText = $(this).find( 'h2' ); 
			items += itemText.text() + '/';			
		});

		$( '.popup-konfigurator-overlay' ).css( 'display', 'flex;' ); 

		var data = {
			'action'  	: 'konfigurator_mail',
			'name'      : name,
			'surname'   : surname,
			'email'     : email,
			'phone' 	: phone,
			'place'   	: place,
			'note' 		: note,
			'total'		: total,
			'totaleur'	: totalEur,
			'formtype'	: formType,
			'items'		: items
		};

		jQuery.post(wcp_localize.ajaxurl, data, function( response ) {
			
			var result = jQuery.parseJSON( response );
			
			if( result.status != 'success' ){
				
				jQuery( '.konfigurator-error' ).text( result.message );
				jQuery( '.konfigurator-error' ).css( 'display', 'block' );
				
			}else{
				jQuery( '.popup-konfigurator footer').css( 'display', 'none' );
				jQuery( '.popup-konfigurator-body' ).html( '<h3 style="width:100%;text-align:center;">Děkujeme za poptávku, budeme vás kontaktovat.</h3>' );
			
			}
		
		});

	});

	                 
    jQuery('#popup-close-button').on('click',function( e ){
        jQuery('#popup-konfigurator').toretpopup('hide');
        jQuery( '.popup-konfigurator-overlay' ).css( 'display', 'none' );
        jQuery( '.popup-konfigurator-response' ).css( 'display', 'none' );
        jQuery( '.popup-konfigurator-email-error' ).css( 'display', 'none' );
        jQuery( '.konfigurator-first-name' ).val('');
        jQuery( '.konfigurator-last-name' ).val('');
        jQuery( '.konfigurator-email' ).val('');
            
    });
    jQuery('.popup-response-close').on('click',function( e ){
        e.preventDefault();
        jQuery('#popup-konfigurator').toretpopup('hide');
        jQuery( '.popup-konfigurator-overlay' ).css( 'display', 'none' );
        jQuery( '.popup-konfigurator-response' ).css( 'display', 'none' );
        jQuery( '.popup-konfigurator-email-error' ).css( 'display', 'none' );
        jQuery( '.konfigurator-first-name' ).val('');
        jQuery( '.konfigurator-last-name' ).val('');
        jQuery( '.konfigurator-email' ).val('');
            
	});
	
	jQuery('.konfigurator-selector span').on('click',function( e ){
		$( '.konfigurator-selector span' ).removeClass( 'active-currency' );
		$( this ).addClass( 'active-currency' );
		var currency = $( this ).data( 'currency' );
		console.log( currency );

		var total = 0;
		var totalEur = 0;
		$('.konfigurator-list-item.active').each(function() {
			var priceData = $(this).find( '.konfigurator-price-value' ); 
			var price = $( priceData ).data( 'price' );
			var priceEur = $( priceData ).data( 'price-eur' );
			total += price;
			totalEur += priceEur;
		});

		/*if( currency == 'czk' ){
			var formatedTotal = String(total).replace(/(?<!\..*)(\d)(?=(?:\d{3})+(?:\.|$))/g, '$1 ');
		} else {
			var formatedTotal = String(totalEur).replace(/(?<!\..*)(\d)(?=(?:\d{3})+(?:\.|$))/g, '$1 ');
		}

		
		$( '.konfigurator-total-price-value' ).text( formatedTotal );
		*/
		$( '.konfigurator-total-price-value' ).attr( 'data-total', total );
		$( '.konfigurator-total-price-value' ).attr( 'data-total-eur', totalEur );


		if( currency == 'czk' ){
			
			$('.konfigurator-list-item').each(function() {
				var priceData = $(this).find( '.konfigurator-price-value' ); 
				var currencyData = $(this).find( '.konfigurator-price-currency' );
				$(currencyData).text( 'Kč' );
				var price = $( priceData ).data( 'price' );
				var formatedPrice = String(price).replace(/(?<!\..*)(\d)(?=(?:\d{3})+(?:\.|$))/g, '$1 ');
				$( priceData ).text( formatedPrice );		
			});
			var total = $( '.konfigurator-total-price-value' ).data( 'total' );
			var formatedTotal = String(total).replace(/(?<!\..*)(\d)(?=(?:\d{3})+(?:\.|$))/g, '$1 ');
			$( '.konfigurator-total-price-value' ).text( formatedTotal );
			$( '.konfigurator-total-price-currency' ).text( 'Kč' );
		} else {
			
			$('.konfigurator-list-item').each(function() {
				var priceData = $(this).find( '.konfigurator-price-value' ); 
				var currencyData = $(this).find( '.konfigurator-price-currency' );
				$(currencyData).text( 'EUR' );
				var priceEur = $( priceData ).data( 'price-eur' );
				var formatedPrice = String(priceEur).replace(/(?<!\..*)(\d)(?=(?:\d{3})+(?:\.|$))/g, '$1 ');
				$( priceData ).text( formatedPrice );
			});
			var totalEur = $( '.konfigurator-total-price-value' ).data( 'total-eur' );
			var formatedTotal = String(totalEur).replace(/(?<!\..*)(\d)(?=(?:\d{3})+(?:\.|$))/g, '$1 ');
			$( '.konfigurator-total-price-value' ).text( formatedTotal );
			$( '.konfigurator-total-price-currency' ).text( 'EUR' );
		}
	});



} )( jQuery );
