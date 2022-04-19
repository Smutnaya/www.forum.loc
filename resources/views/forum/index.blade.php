@foreach($model['topics'] as $topic)
<div><a href="{{ url('/t/'.$topic['id'])}}">{{ $topic['title'] }} </a></div>
@endforeach

<p><u><a href="{{ url('/f/'.$forumId.'/topic')}}">New topic</a></u></p>


