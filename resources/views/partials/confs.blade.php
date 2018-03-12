@if(!empty($data['confs']))
    <h4>Configuration:</h4>
    <select name="conf">
    @foreach($data['confs'] as $name)
    <option value="{{ $name }}">{{ $name }}</option>
    @endforeach
    </select>
@else
    <input name="conf" value="" type="hidden">
@endif