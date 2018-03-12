@if(!empty($data['styles']))
    <h4>Styles:</h4>
    <select name="style">
    @foreach($data['styles'] as $name)
    <option value="{{ $name }}">{{ $name }}</option>
    @endforeach
    </select>
@else
    <input name="style" value="" type="hidden">
@endif