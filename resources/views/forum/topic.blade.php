id {{$forumId}}

<form method='post' action={{ url('/f/'.$forumId.'/topic/save')}}>
    @csrf

        <div class="topicNew">
            <p><label>Название топика: </label>
                <input type="text" name="title" id="title"></p>
            <p><label>Текст: </label>
                <input type="text" name="text" id="text"></p>
            <p><input type="submit" value="Отправить">
                <input type="reset" value="Очистить"></p>
        </div>
    </form>
