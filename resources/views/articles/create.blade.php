@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-11 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        New Article
                    </div>
                    @if ($fail = Session::get('warning'))
                        <div class=" col-lg-12 col-md-12 col-sm-12  bs-example-bg-classes" >
                            <p class="bg-danger">
                                {{ $fail }}
                            </p>
                        </div>
                    @endif

                    <div class="panel-body">
                        {!! Form::open( array('url' => 'admin/article', 'class'=>'form', 'method'=> 'POST', 'enctype'=>'multipart/form-data' )) !!}
                        {{--<form class="form" action="{{ url('admin/article') }}" method="post" enctype="multipart/form-data">--}}
                        <div class="form-group  col-lg-12 col-md-12 col-sm-12" >
                            <div class="{{ isset($errors) && $errors->has('title') ? 'has-error' : '' }}" >
                                <label class="col-lg-12 col-md-12 col-sm-12">Title</label>
                                <input class="col-lg-12 col-md-12 col-sm-12" type="text" id="title" name="title" required />
                                <span id="helpBlock2" class="help-block">{{ $errors->first('title')}}</span>
                            </div>

                            <div class="{{ isset($errors) && $errors->has('content') ? 'has-error' : '' }}" >
                                <label class="col-lg-12 col-md-12 col-sm-12">Content</label>
                                <div class="clearfix"></div>
                                <textarea class="col-lg-12 col-md-12 col-sm-12 form-control my-editor" id="content" name="content" required placeholder="Content"></textarea>
                                <span id="helpBlock2" class="help-block">{{ $errors->first('content')}}</span>
                            </div>

                            <div>
                                <label class="col-lg-12 col-md-12 col-sm-12">Select Category</label>
                                <select class="col-lg-12 col-md-12 col-sm-12" name="category_id">
                                    @foreach ($categories as $category)
                                        <option value="{{$category->id}}">{{$category->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="col-lg-12 col-md-12 col-sm-12">Select Type</label>
                                <select class="col-lg-12 col-md-12 col-sm-12" name="type_id">
                                    @foreach ($articletypes as $type)
                                        <option value="{{$type->id}}">{{$type->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="col-lg-12 col-md-12 col-sm-12">Select Tags</label>
                                <select class="col-lg-12 col-md-12 col-sm-12" name="tag_ids[]" multiple>
                                    @foreach ($tags as $tag)
                                        <option value="{{$tag->id}}">{{$tag->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="col-lg-1 col-md-1 col-sm-1 pull-left">Publish</label>
                                <input class="col-lg-1 col-md-1 col-sm-1 pull-left published" type="checkbox" name="published" />
                            </div>
                        </div>
                        {!! Form::token() !!}
                        <div class=" col-lg-12 col-md-12 col-sm-12">
                            <input type="submit" id="submit" value="Submit" class="btn btn-default" />
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


<script src="https://cdn.tinymce.com/4/tinymce.min.js"></script>
<script>
    var editor_config = {
        path_absolute : "{{ URL::to('/') }}/",
        selector: "textarea",
        plugins : 'image',
        menubar: false,
        toolbar: 'undo redo | link image',
        relative_urls: false,
        file_browser_callback_types: 'image media',
        file_browser_callback : function(field_name, url, type, win) {
            var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
            var y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight;

            var cmsURL = editor_config.path_absolute + 'laravel-filemanager?field_name=' + field_name;
            console.log(cmsURL);
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