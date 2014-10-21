<p>{{ $step->text or '' }}</p>
{{ Form::open( ['role' => 'form' , 'action' => [ 'quiz.next' , $id , $token ] ] ) }}
<input style="display:none" name="_step" value="{{ $step->step }}">
    {{ $content or '' }}
{{ Form::close() }}