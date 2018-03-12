@push('scripts')
<script>
    $(function() {
        $('#submit-form').on('click', function(e) {
            e.preventDefault();
            $('#checkout-form').submit();
            return false;
        });
    });
</script>
@endpush
@push('scripts')
@include('handlers.auto_address')
@endpush