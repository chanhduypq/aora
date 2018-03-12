@push('scripts')
<script>
    $(function() {

        totalPrice(false);

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('.submit-form').on('click', function(e) {
            e.preventDefault();
            $(this).closest('form').submit();
            return false;
        });

        $('.cart-body').on('change', 'input[name=shipping_method]', function() {
            totalPrice(false);
        });

        $('.cart-remove').on('click', function(e) {
            e.preventDefault();

            var th = $(this);
            var data = th.closest('.cart-row').data();

            $.ajax({
                url: '{{ route('cart.delete') }}',
                type: 'POST',
                data: data,
                dataType: 'json',
                success: function (response) {
                    th.closest('.cart-row').remove();
                },
                error: function (response) {
                },
                complete: function (response) {
                    totalPrice(false);
                }
            });
        });

        $('.quantity-mobile, .quantity').bind({
            change: function() { changeQ($(this)) },
            keyup: function() { changeQ($(this)) },
        });

        function changeQ(th, click) {

            var data = th.closest('.cart-row').data();
            var mobile = false;

            if(click) {
                var input = th.closest('.item-cnt').find('input');
                data.quantity = input.val();
                mobile = input.hasClass('quantity-mobile');

                var q = parseInt(data.quantity);

                if(isNaN(q) || q < 1) {
                    input.val(1);
                    return;
                }
            } else {
                data.quantity = th.val();
                mobile = th.hasClass('quantity-mobile');

                var q = parseInt(data.quantity);

                if(isNaN(q) || q < 1) {
                    th.val(1);
                    return;
                }
            }

            $.ajax({
                url: '{{ route('cart.changeQ') }}',
                type: 'POST',
                data: data,
                dataType: 'json',
                success: function (response) {
                },
                error: function (response) {
                },
                complete: function (response) {
                    totalPrice(mobile);
                }
            });
        }

        function totalPrice(mobile) {
            if($('.cart-table').length == 0) {
                $('.search-title').text('Your Cart is empty');
                return;
            }
            var price = 0;
            var q = $('.quantity'+(mobile ? '-mobile' : ''));
            var weights = 0;
            var setting = $('#cart-form').data();
            var shipInput = $('input[name=shipping_method]:checked');
            var shipping = parseFloat(shipInput.data('price'));
            var addShipping = parseFloat(shipInput.data('add-price'));
            var nameShipping = shipInput.data('name');

            setting.rate = parseFloat(setting.rate);
            setting.discount = parseInt(setting.discount);

            for(var i = 0; i < q.length; i++) {
                var d = q.eq(i);
                var data = d.closest('.cart-row').data();

                price += parseFloat(Number(parseInt(d.val()) * parseFloat(data.price) * setting.rate).toFixed(2));
                if(nameShipping == 'standard') {
                    weights += parseFloat(data.weight_max * d.val());
                } else {
                    weights += parseFloat(data.weight * d.val());
                }
            }

            $('.order_subtotal').html(price);
            if(price == 0) {
                $('.cart-table').hide();
                $('#cart-form').hide();
                $('.search-title').text('Your Cart is empty');
            }

            var _totalPrice = price;
            var totalShip = 0;
            var discountPrice = 0;

            if(shipping && !isNaN(shipping)) {
                var addShip = 0;

                addShip = Math.ceil(weights / 100) * addShipping;

                totalShip = (shipping + addShip);
            }

            discountPrice = (_totalPrice * (setting.discount / 100));
            _totalPrice -= discountPrice;
            //_totalPrice += parseFloat(Number(totalShip).toFixed(2));
            totalShip = Number(totalShip).toFixed(2);

            updateShippingPrice();

            $('#discount-total').val(discountPrice);
            $('#shipping-total').val(totalShip);

            $('.discount-total').html(Number(discountPrice).toFixed(2));
            $('.order_total').html(Number(_totalPrice).toFixed(2));
        }

        $('#get-dhl').on('click', function(e) {
            e.preventDefault();

            var th = $(this);
            var pcode = th.prev().val();

            $.ajax({
                url: '{{ route('ajax.getDhl') }}',
                type: 'POST',
                data: {postal_code: pcode},
                dataType: 'json',
                success: function (response) {
                    if(response.status == 'ok') {
                        $('.cart-shipping').html(response.data.dhl);
                    } else {
                        $('.cart-shipping').html('Invalid postal code!');
                    }
                },
                error: function (response) {
                    $('.cart-shipping').html('Invalid postal code!');
                },
                complete: function (response) {
                    totalPrice(false);
                }
            });

            return false;
        });

        updateShippingPrice();

        function updateShippingPrice() {           

            var weights = 0;
            var q = $('.quantity');
            
            for(var i = 0; i < q.length; i++) {
                var d = q.eq(i);
                var data = d.closest('.cart-row').data();

                weights += parseFloat(data.weight * d.val());
            }
            
            $('input[name=shipping_method]').each(function() {
                var base = parseFloat($(this).data('price'));
                var variable = parseFloat($(this).data('add-price'));            

                var shipping = base + (Math.ceil(weights/100) * variable);

                $(this).parent().find('.shipping_rate').html(Number(shipping).toFixed(2));
            });
        }
    });
</script>
@endpush