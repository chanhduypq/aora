@if(!empty($data['sizes']))
    <h4>Sizes:</h4>
    <select name="size">
    @foreach($data['sizes'] as $name)
    <option value="{{ $name }}">{{ $name }}</option>
    @endforeach
    </select>
@else
    <input name="size" value="" type="hidden">
@endif