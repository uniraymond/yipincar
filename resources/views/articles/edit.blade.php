@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-11 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        编辑文章
                    </div>
                    @if ($fail = Session::get('warning'))
                        <div class=" col-lg-12 col-md-12 col-sm-12  bs-example-bg-classes" >
                            <p class="bg-danger">
                                {{ $fail }}
                            </p>
                        </div>
                    @endif

                    <div class="panel-body">
                        {!! Form::open(array('url' => 'admin/article/'.$article->id, 'class' => 'form', 'method'=>'put', 'enctype'=>'multipart/form-data')) !!}
                            <div class="form-group  col-lg-12 col-md-12 col-sm-12" >
                                <div class="{{ isset($errors) && $errors->has('title') ? 'has-error clearfix' : 'clearfix' }}" style="margin-bottom: 5px" >
                                    <label class="col-lg-12 col-md-12 col-sm-12">标题</label>
                                    <input class="col-lg-12 col-md-12 col-sm-12" type="text" id="title" name="title" required value="{{ $article->title }}"/>
                                    <span id="helpBlock2" class="help-block">{{ $errors->first('title')}}</span>
                                </div>

                                <div>
                                    <label class="col-lg-12 col-md-12 col-sm-12">简介</label>
                                    <textarea class="col-lg-12 col-md-12 col-sm-12" type="text" id="description" name="description" placeholder="简介" >{{ $article->description }}</textarea>
                                </div>

                                <div class={{ isset($errors) && $errors->has('content') ? 'has-error clearfix' : 'clearfix' }}>
                                    <label class="col-lg-12 col-md-12 col-sm-12 clearfix">内容</label>
                                    <div class="clearfix"></div>
                                    <textarea class="col-lg-12 col-md-12 col-sm-12 clearfix" id="content" name="content" height="50">{{ $article->content }}</textarea>

                                    <span id="helpBlock2" class="help-block">{{ $errors->first('content')}}</span>
                                </div>

                                <div>
                                    <label class="col-lg-12 col-md-12 col-sm-12">选择类别</label>
                                    <select class="col-lg-12 col-md-12 col-sm-12" name="category_id">
                                        @foreach ($categories as $category)
                                            <option {{ $article->category_id == $category->id ? 'selected' : '' }} value="{{$category->id}}">{{$category->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="col-lg-12 col-md-12 col-sm-12">选择关键字</label>
                                    <select class="col-lg-12 col-md-12 col-sm-12" name="tag_ids[]" multiple>
                                        @foreach ($tags as $tag)
                                            <option @if (isset($currentTags)) {{ in_array($tag->id, $currentTags) ? 'selected' : '' }} @endif value="{{$tag->id}}">{{$tag->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="col-lg-2 col-md-2 col-sm-2 pull-left">发布</label>
                                    <input class="col-lg-1 col-md-1 col-sm-1 pull-left published" type="checkbox" name="published"  />
                                </div>
                            </div>
                            {!! Form::token() !!}
                            <div class=" col-lg-12 col-md-12 col-sm-12">
                                <input type="submit" id="submit" value="保存" class="btn btn-default" />
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection


<script src="/src/js/vendor/tinymce/js/tinymce/tinymce.min.js"></script>
<script>
    var editor_config = {
        height: "350",
        path_absolute : "{{ URL::to('/') }}/",
        selector: "textarea#content",
        plugins : 'link image imagetools preview',
        menubar: false,
        toolbar: 'undo redo | image',
        relative_urls: false,
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