@push('scripts')
<script>
    $(function() {

        var options = {};

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function setUrl(id) {
            var pid = id ? id : $('.item').data('product-id');
            var state = {'product_id': pid};
            var url = '{{ route('pages.result') }}/' + pid;
            history.pushState(state, null, url);
        }

        $('.product-variant').on('click', function(e) {
            //e.preventDefault();
            $('#preloader').fadeIn('slow');
            var th = $(this);
            var groups = $('#result').find('.item-conf');
            var _variants = [];
            var variants = $('.product-variant.active');

            for(var i = 0; i < variants.length; i++) {
                var name = variants.eq(i).data('name');
                var type = variants.eq(i).data('type');
                options[type] = name;
            }

            options[th.data('type')] = th.data('name');

            var i = 0;

            for(e in options) {
                _variants[i] = options[e];
                i++;
            }

            if(groups.length > 0 && i < groups.length) {
                return;
            }

            var setting = $('.item').data();
            var error = $('#error');

            error.html('Please wait...');

            $.ajax({
                url: '{{ route('ajax.getVariant') }}',
                type: 'POST',
                data: {options: options},
                dataType: 'json',
                success: function (response) {
                    if(response.status == 'ok') {
                        error.html('');

                        var product = response.data;

                        var priceConverted = Number(setting.rate * product.price).toFixed(2);
                        priceConverted = priceConverted.toLocaleString('en-IN', { maximumSignificantDigits: 2});
                        var price = Number(product.price).toFixed(2);
                        price = price.toLocaleString('en-IN', { maximumSignificantDigits: 2});

                        $('.item-thumb img').attr('src', product.image);
                        $('.item-title').html(product.title);
                        $('#dimensions').html(product.dimensions);
                        $('#weight').html(product.weight);
                        $('#item-price-converted').html(priceConverted);
                        $('#item-price-original').html(price);

                        $('#product-variant').val(_variants.join(', '));
                        $('#product-id').val(product.id);
                        $('#product-title').val(product.title);
                        $('#product-image').val(product.image);
                        $('#product-dimensions').val(product.dimensions);
                        $('#product-price').val(product.price);
                        $('#product-weight').val(product.weight);
                        $('#product-weight-gram').val(product.weight_gram);

                        setUrl(product.id);

                        if(!product.price || isNaN(product.price) || product.price == 'null') {
                            $('.btn-primary').attr('disabled', 'disabled');
                        } else {
                            $('.btn-primary').removeAttr('disabled');
                        }
                    } else {
                        error.html(response.msg);
                    }
                },
                error: function (response) {
                    error.html('Something error. Please try again!');
                },
                complete: function (response) {
                    $('#preloader').fadeOut('slow');
                }
            });

            //return false;
        });

        $('.quantity-mobile, .quantity').bind({
            change: function() { changeQ($(this)) },
            keyup: function() { changeQ($(this)) },
        });

        function changeQ(th, click) {

            var data = th.closest('.search-body').data();

            if (click) {
                var input = th.closest('.item-cnt').find('input');
                data.quantity = input.val();

                var q = parseInt(data.quantity);

                if (isNaN(q) || q < 1) {
                    input.val(1);
                }
            } else {
                data.quantity = th.val();

                var q = parseInt(data.quantity);

                if (isNaN(q) || q < 1) {
                    th.val(1);
                }
            }
        }
    });
</script>
@endpush
@push('styles')
<style type="text/css">
    #preloader { position: fixed; left: 0; top: 0; z-index: 999; width: 100%; height: 100%; overflow: visible; background: rgba(0, 0, 0, 0.67) url('http://files.mimoymima.com/images/loading.gif') no-repeat center center; }
</style>
@endpush