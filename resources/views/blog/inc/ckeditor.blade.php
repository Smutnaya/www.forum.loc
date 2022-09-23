<textarea name="text" type="text" id="text"  style="font-family: Arial, sans-serif !important; margin-top: 0 !important; margin-bottom: 0 !important; padding: 0 !important;" onkeypress="if(event.keyCode == 13) return false;"></textarea>

<script>
    CKEDITOR.addCss(".cke_editable{cursor:text; font-size: 13px; font-family: Arial, sans-serif;} p{margin:1;} .cke_top, .cke_contents, .cke_bottom{max-height: 100px !important;}")
    CKEDITOR.replace('text', {
        customConfig: '/ckeditor/custom/ckeditor_config_comment.js',
        removePlugins: ['elementspath', 'autogrow', 'uploadimage'],
        filebrowserUploadUrl: "{{ route('upload', ['_token' => csrf_token()]) }}",
        filebrowserUploadMethod: 'form',
        allowedContent: true
    });

    $('#reset').click(function(e) {
        CKEDITOR.instances.text.setData("");
    });
</script>
