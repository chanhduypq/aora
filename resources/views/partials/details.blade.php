@if(!empty($data['details']['shipping_dimension'])
    || !empty($data['details']['product_dimension'])
    || !empty($data['details']['shipping_weight'])
    || !empty($data['details']['product_weight'])
)
    <h4>Details:</h4>
    <table>
    @foreach($data['details'] as $name => $value)
        @if(!empty($value))
            <tr>
                <th>{{ $name }}:&nbsp;</th>
                <td>
                    {{ $value }}
                    <input type="hidden" name="{{ $name }}" value="{{ $value }}">
                </td>
            </tr>
        @else
            <input type="hidden" name="{{ $name }}" value="{{ $value }}">
        @endif
    @endforeach
    </table>
@else
    @foreach($data['details'] as $name => $value)
        <input type="hidden" name="{{ $name }}" value="{{ $value }}">
    @endforeach
@endif