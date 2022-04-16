
FORUM

@foreach($model['sections'] as $section)
<div>{{ $section['title'] }}</div>
@endforeach

@foreach($model['posts'] as $post)
<div>{{ $post['text'] }}</div>
@endforeach

@foreach($model['topics'] as $topic)
<div>{{ $topic['title'] }}</div>
@endforeach
