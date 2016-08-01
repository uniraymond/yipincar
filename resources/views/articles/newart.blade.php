@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-7 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">New Article</div>
                    <div class="panel-body">
                        <form class="form" action="\api\article" method="post" enctype="multipart/form-data">
                            <div class="form-group col-md-6" >
                                <label class="col-md-12">Title</label>
                                <input class="col-md-12" type="text" id="title" name="title" />
                                <label class="col-md-12">Content</label>
                                <textarea class="col-md-12" id="content" name="content"></textarea>
                                <label class="col-md-12">Select Type</label>
                                <select class="col-md-12" name="arttype">
                                    @foreach ($articletypes as $type)
                                        <option value="{{$type->id}}">{{$type->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12" class="form-group">
                                <label>Resource</label>
                                <input type="file" id="file" name="file" />
                                <input type="text" id="filedescription" name="filedescription" placeholder="File Description" />

                                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            </div>

                            <input type="submit" id="submit" value="Upload" />
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection


<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
<script>
    var editor_config = {
        path_absolute : "{{ URL::to('/') }}/",
        selector: "textarea",
        plugins : 'link image imagetools preview',
        menubar: false,
        toolbar: [
        'undo redo | bold italic | link image' | 'alignleft aligncenter alignright'],
//        relative_urls: false,
        file_browser_callback_types: 'image media',
        file_browser_callback : function(field_name, url, type, win) {
            var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
            var y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight;

            var cmsURL = editor_config.path_absolute + 'laravel-filemanager?field_name=' + field_name;
            if (type == 'image') {
                cmsURL = cmsURL + "&type=Images";
            } else {
                cmsURL = cmsURL + "&type=Files";
            }

            tinyMCE.activeEditor.windowManager.open({
                file : cmsURL,
                title : 'Filemanager',
                width : x * 0.8,
                height : y * 0.8,
                resizable : "yes",
                close_previous : "no"
            });
        },
    };

    tinymce.init(editor_config);
</script>