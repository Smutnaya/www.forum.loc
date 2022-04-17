@foreach($model['forums'] as $forum)
<div><a href="{{ url('/f/'.$forum['id'])}}">{{ $forum['title'] }} </a></div>
<div>{{ $forum['description'] }}</div>
@endforeach
