@push('scripts')
<script>
    var settings = {
        "async": true,
        "crossDomain": true,
        "url": '',
        "method": "GET",
        "processData": false,
        "contentType": false,
    };

    var url = "https://developers.onemap.sg/commonapi/search";
    $(function() {
        $('.postal-code').on('keyup', function(e) {
            var th = $(this);
            var pcode = th.val();

            if(!pcode) {
                return;
            }

            settings.url = url + "?searchVal="+pcode+"&returnGeom=N&getAddrDetails=Y";

            $.ajax(settings).done(function (response) {
                if(response.found > 0) {
                    var address = response.results[0].ADDRESS;
                    th.closest('.address-block').find('.address').val(address);
                    $('.dl-js-address').val(address);
                }
            });
        });
    });
</script>
@endpush
