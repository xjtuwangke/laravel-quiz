@foreach( $step->options as $value => $text  )
<div class="radio">
  <label>
    <input type="radio" name="options[]" value="{{{ $value }}}">
    {{{ $text }}}
  </label>
</div>
@endforeach
<button type="submit" class="btn btn-default">下一步</button>