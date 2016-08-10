@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-7 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Edit Article</div>
                    <div class="panel-body">
                        {!! Form::open(array('url' => 'admin/article/'.$article->id, 'class' => 'form', 'method'=>'put')) !!}
{{--                        <form class="form" action="{{ url('admin/article/'.$article->id) }}" method="put" enctype="multipart/form-data">--}}
                            <div class="form-group col-md-6" >
                                <label class="col-md-12">Content</label>
                                <textarea class="col-md-12" id="content" name="content">{{ $article->content }}</textarea>


                                <label class="col-md-12">Title</label>
                                <input class="col-md-12" type="text" id="title" name="title" value="{{ $article->title }}" />


                                <label class="col-md-12">Select Category</label>
                                <select class="col-md-12" name="category_id">
                                    @foreach ($categories as $category)
                                        <option {{ $article->category_id == $category->id ? 'selected' : '' }} value="{{$category->id}}">{{$category->name}}</option>
                                    @endforeach
                                </select>
                                <label class="col-md-12">Select Tags</label>
                                <select class="col-md-12" name="tag_ids[]" multiple>
                                    @foreach ($tags as $tag)
                                        <option {{ in_array($tag->id, $currentTags) ? 'selected' : '' }} value="{{$tag->id}}">{{$tag->name}}</option>
                                    @endforeach
                                </select>
                                <label class="col-md-4">Publish</label>
                                <input class="col-md-4" type="checkbox" name="published" />
                            </div>
                            {!! Form::token() !!}
                            <input type="submit" id="submit" value="Submit" />
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
        plugins : 'image',
        menubar: false,
        toolbar: [
            'undo redo | image' | 'alignleft aligncenter alignright'],
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