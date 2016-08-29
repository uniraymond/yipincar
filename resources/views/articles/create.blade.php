@extends('layouts.app')
@section('style')
    @parent
    <link rel="stylesheet" href="/src/css/jquery-ui.min.css">
    <link rel="stylesheet" href="/src/css/jquery-ui.structure.min.css">
    <link rel="stylesheet" href="/src/css/jquery-ui.theme.min.css">
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-11 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        新文章
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
                                <label class="col-lg-12 col-md-12 col-sm-12">标题</label>
                                <div class="col-md-12">
                                    <input class="col-lg-12 col-md-12 col-sm-12" type="text" id="title" name="title" required />
                                    @if ($errors->has('title'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('title') ? '标题不能为空' : ''}}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div>
                                <label class="col-lg-12 col-md-12 col-sm-12">简介</label>
                                <div class="col-md-12">
                                    <textarea class="col-lg-12 col-md-12 col-sm-12" type="text" id="description" name="description" placeholder="简介" height="100"></textarea>
                                </div>
                            </div>

                            <div class="{{ isset($errors) && $errors->has('content') ? 'has-error' : '' }}" >
                                <label class="col-lg-12 col-md-12 col-sm-12">内容</label>
                                <div class="clearfix"></div>
                                <div class="col-md-12">
                                    <textarea class="col-lg-12 col-md-12 col-sm-12 form-control my-editor" id="content" name="content" placeholder="详细内容" height="50" ></textarea>
                                    @if ($errors->has('content'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('content') ? '内容不能为空' : ''}}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div>
                                <label class="col-lg-12 col-md-12 col-sm-12">选择类别</label>
                                <div class="col-md-12">
                                    <select class="col-lg-12 col-md-12 col-sm-12" name="category_id">
                                        @foreach ($categories as $category)
                                            <option value="{{$category->id}}">{{$category->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="col-lg-12 col-md-12 col-sm-12">选择类型</label>
                                <div class="col-md-12">
                                    <select class="col-lg-12 col-md-12 col-sm-12" name="type_id">
                                        @foreach ($articletypes as $type)
                                            <option value="{{$type->id}}">{{$type->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="col-lg-12 col-md-12 col-sm-12">选择标签(按空格键会有提示)</label>
                                <div class="col-md-12">
                                    <input id="tags" name="tags" class="col-lg-12 col-md-12 col-sm-12 form-control" placeholder="选择标签" />
                                    <div class="col-lg-12 col-md-12 col-sm-12 highlight">
                                        <span><small>提示现有的标签: {!! $tagString !!}</small></span>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <div class="col-md-12">
                                <label class="col-lg-2 col-md-2 col-sm-2 pull-left published_label">
                                    <input class="col-lg-1 col-md-1 col-sm-1 pull-left published" type="checkbox" name="published" />  提交
                                </label>
                                </div>
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

            @include('articles.sidebarCategory',['categories'=>$categories, 'types'=>$types, 'tag'=>$tags, 'currentAction'=>$currentAction])
        </div>
    </div>
@endsection

@section('script')
@parent
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

<script src="/src/js/jquery-ui.min.js" ></script>
<script>
    //autocomplete
    jQuery( function() {
        var availableTags = [ {!! $tagString !!} ];
        function split( val ) {
            return val.split( /,\s*/ );
        }
        function extractLast( term ) {
            return split( term ).pop();
        }

        jQuery('#tags').on( "keydown", function( event ) {
                if ( event.keyCode === jQuery.ui.keyCode.TAB &&
                        jQuery( this ).autocomplete( "instance" ).menu.active ) {
                    event.preventDefault();
                }
            })
            .autocomplete({
                minLength: 0,
                source: function( request, response ) {
                    // delegate back to autocomplete, but extract the last term
                    response( jQuery.ui.autocomplete.filter(
                            availableTags, extractLast( request.term ) ) );
                },
                focus: function() {
                    // prevent value inserted on focus
                    return false;
                },
                select: function( event, ui ) {
                    var terms = split( this.value );
                    // remove the current input
                    terms.pop();
                    // add the selected item
                    terms.push( ui.item.value );
                    // add placeholder to get the comma-and-space at the end
                    terms.push( "" );
                    this.value = terms.join( ", " );
                    return false;
                }
            });
        } );
</script>
@endsection