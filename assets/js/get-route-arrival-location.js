jQuery(function($){
    var departure_element = $('div#route_departure_location select');
    var arrival_element = $('div#route_arrival_location select');
    var passengers_element = $('select[name=route_passengers]');
    var price_element = $('span#show_price');
    var current_ticket_price;

    departure_element.on('change', function(e) {
        e.preventDefault();
        $.ajax({
            url: myAjax.url,
            type : 'POST',
            data: {
                    'action': "process_route_departure_location",
                    'deparute_location': this.value,
                    'nonce': myAjax.nonce
                  },
            success: function(response) {
                var locations = JSON.parse(response);

                arrival_element.empty();
                arrival_element.append($('<option>', {
                    text : 'Sosire in'
                    }));

                $.each(locations.locations, function( i, item ) {
                  arrival_element.append($('<option>', { 
                    value: item.meta_value,
                    text : item.meta_value
                    }));
                });
            }
        });
    });

    arrival_element.on('change', function(e) {
        e.preventDefault();
        $.ajax({
            url: myAjax.url,
            type : 'POST',
            data: {
                    'action': "process_route_arrival_location",
                    'route_departure_location': $('div#route_departure_location select :selected').val(),
                    'route_arrival_location': this.value,
                    'nonce': myAjax.nonce
                  },
            success: function(response) {
                var json_data = JSON.parse(response);
                current_ticket_price = json_data.price;

                price_element.text(current_ticket_price);
            }
        });
    });

    passengers_element.on('change', function(e) {
        e.preventDefault();
        if(current_ticket_price.length > 0) {
            var maths = parseInt(current_ticket_price) * parseInt(this.value);

            if(!isNaN(maths)) {
                price_element.text(maths);
            }
        }
    });
});