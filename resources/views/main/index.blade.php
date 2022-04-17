
FORUM

@foreach($model['sections'] as $section)
<div><a href="{{ url('/s/'.$section['id'])}}">{{ $section['title'] }} </a></div>
@endforeach
<br>
<div>TOPIC</div>
@foreach($model['topics'] as $topic)
<div>{{ $topic['title'] }}</div>
@endforeach
<br>
<div>POST</div>
@foreach($model['posts'] as $post)
<div>{{ $post['text'] }}</div>
@endforeach

