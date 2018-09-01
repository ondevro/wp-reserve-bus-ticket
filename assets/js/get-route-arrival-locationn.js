jQuery(function($){

    $('div#route_departure_location select').on('change', function(e) {
		alert( this.value );
		e.preventDefault();

        var data = {
            action: 'process_reservation',
            nonce: myAjax.nonce
        };

        $.post( myAjax.url, data, function( response )
        {
            this.empty();

			$.each(response.data, function( key, value ) {
			  this.append($("<option></option>").attr("value", value).text(key));
			});
        });

        return false;
    });
});