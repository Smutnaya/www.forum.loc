@if (!is_null($model['user']))
    <span
        @if (!is_null($post['DATA']->like_name)) data-bs-toggle="popover" data-bs-trigger="hover" data-bs-placement="top" data-bs-content="
                                <?php
                                echo htmlspecialchars_decode($post['DATA']->like_name);
                                ?>" @endif>
        @if (is_null($post['like']) || $post['like']->action == 'dislike')
            <a href="{{ url('/p/' . $post['id'] . '/like') }}" style="text-decoration: none !important;">
                <i class="fa-regular fa-thumbs-up" style="color:#0e583d"></i>
            </a>
        @elseif ($post['like']->action == 'like')
            <a href="{{ url('/p/' . $post['id'] . '/likem') }}" style="text-decoration: none !important;">
                <i class="fa-solid fa-thumbs-up" style="color:#0e583d"></i>
            </a>
        @endif
        <span class="ms-1 me-3" style="color:0e583d">{{ $post['DATA']->like }}</span>
    </span>
    <span
        @if (!is_null($post['DATA']->dislike_name)) data-bs-toggle="popover" data-bs-trigger="hover" data-bs-placement="top" data-bs-content="
                                <?php
                                echo htmlspecialchars_decode($post['DATA']->dislike_name);
                                ?>" @endif>
        @if (is_null($post['like']) || $post['like']->action == 'like')
            <a href="{{ url('/p/' . $post['id'] . '/dislike') }}" style="text-decoration: none !important;">
                <i class="fa-regular fa-thumbs-down" style="color: #660000"></i>
            </a>
        @elseif ($post['like']->action == 'dislike')
            <a href="{{ url('/p/' . $post['id'] . '/dislikem') }}" style="text-decoration: none !important;">
                <i class="fa-solid fa-thumbs-down" style="color: #660000"></i>
            </a>
        @endif
        <span class="ms-1" style="color: #660000">{{ $post['DATA']->dislike }}</span>
    </span>
@else
    <span
        @if (!is_null($post['DATA']->like_name)) data-bs-toggle="popover" data-bs-trigger="hover" data-bs-placement="top" data-bs-content="
                                <?php
                                echo htmlspecialchars_decode($post['DATA']->like_name);
                                ?>" @endif>
        <i class="fa-regular fa-thumbs-up" style="color:#0e583d"></i>
        <span class="ms-1 me-3" style="color:0e583d">{{ $post['DATA']->like }}</span>
    </span>
    <span
        @if (!is_null($post['DATA']->dislike_name)) data-bs-toggle="popover" data-bs-trigger="hover" data-bs-placement="top" data-bs-content="
                                <?php
                                echo htmlspecialchars_decode($post['DATA']->dislike_name);
                                ?>" @endif>
        <i class="fa-regular fa-thumbs-down" style="color: #660000"></i>
        <span class="ms-1" style="color: #660000">{{ $post['DATA']->dislike }}</span>
    </span>
@endif
<script>
     $(document).ready(function(){
     $('[data-bs-toggle="popover"]').popover();
  });
</script>

{{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    var tooltipTriggerList = [].slice.call(
        document.querySelectorAll('[data-bs-toggle="tooltip"]')
    );
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
    var popoverList = popoverTriggerList.map(function(popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl)
    })
</script> --}}
{{-- <script>
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
    var popoverList = popoverTriggerList.map(function(popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl)
    })
    var popover = new bootstrap.Popover(document.querySelector('.example-popover'), {
        container: 'body'
    })
</script> --}}
