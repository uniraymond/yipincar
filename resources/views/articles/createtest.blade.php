@extends('layouts.base')
<link rel="stylesheet" href="{{ asset("/src/css/jquery-ui.min.css") }}" />
@include('layouts.contentSideBar')
{{--@include('articles.sidebarCategory',['categories'=>$categories, 'types'=>$types, 'tag'=>$tags, 'currentAction'=>$currentAction])--}}
@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12" style="margin-top: 35px">
                <h1 class="page-header">编辑视频</h1>

                @if ($fail = Session::get('warning'))
                    <div class="col-md-12 bs-example-bg-classes" >
                        <p class="bg-danger">
                            {{ $fail }}
                        </p>
                    </div>
                @endif

                <div class="col-lg-12 col-md-12 col-sm-12" style="margin-top: 55px">
                    {!! Form::open(array('url' => 'admin/article/', 'class' => 'form', 'method'=>'put', 'enctype'=>'multipart/form-data')) !!}
                    <div class="form-group  col-lg-12 col-md-12 col-sm-12" >


                        <div>
                            <label class="col-lg-1 col-md-1 col-sm-1" style="margin-bottom: 55px">选择栏目</label>
                            <div class="col-md-2" style="margin-bottom: 55px">
                                <select class="col-lg-12 col-md-12 col-sm-12 form-control" name="category_id" id="category_id">
                                    {{--@if(Auth::user()->hasAnyRole([ 'auth_editor']))--}}
                                        {{--@foreach($categories as $category)--}}
                                            {{--@if($category->id == 5 || $category->id == 6 || $category->id == 7)--}}
                                                {{--<option value="{{$category->id}}">--}}
                                                    {{--{{$category->name}}--}}
                                                {{--</option>--}}
                                            {{--@endif--}}
                                        {{--@endforeach--}}
                                    {{--@else--}}
                                        @foreach($categories as $category)
                                            <option value="{{$category->id}}" {{ $categoryid == $category->id ? 'selected' : '' }}>
                                                {{$category->name}}
                                            </option>
                                        @endforeach
                                    {{--@endif--}}
                                </select>
                            </div>
                        </div>
                        <div class="clearfix"></div>

                        {!! Form::label('template_id', '首页模版', array('class'=>'col-md-1')) !!}
                        <div class="col-md-2" style="margin-bottom: 55px">
                            <select class="col-lg-12 col-md-12 col-sm-12 form-control" name="template_id">
                                @foreach ($templates as $template)
                                    <option value="{{$template->id}}" >{{$template->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="clearfix"></div>

                        @if( !Auth::user()->hasAnyRole([ 'auth_editor']))
                            <div class="{{ isset($errors) && $errors->has('authname') ? 'has-error clearfix' : 'clearfix' }}" style="margin-bottom: 55px" >
                                <label class="col-lg1-1 col-md-1 col-sm-1">作者</label>
                                <div class="col-md-2">
                                    <input class="col-lg-3 col-md-3 col-sm-3 form-control" type="text" id="authname" name="authname"  />
                                </div>
                            </div>
                        @endif

                        <div>
                            <label class="col-lg-1 col-md-1 col-sm-1">简介</label>
                            <div class="col-md-11">
                                <textarea class="col-lg-12 col-md-12 col-sm-12 form-control" type="text" id="description" name="description" placeholder="限140字" maxlength="140"></textarea>
                            </div>

                            @if ($errors->has('description'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                            @endif
                        </div><br>

                        <div>
                            <label class="col-lg-1 col-md-1 col-sm-1" style="margin-top: 55px">首页图片</label>
                            <div class="col-md-4"  style="margin-top: 55px; margin-bottom: 55px">
                                <input type="file" class="col-md-12 form-control-file" id="images" name="images" />
                                {{--                                {!! Form::file('images', '', array('class'=>'col-md-12 form-control-file form-control', 'id'=>'images', 'required'=>'required')) !!}--}}
                                <img id="image" width="300" />
                            </div>
                        </div>


                        <div class="{{ isset($errors) && $errors->has('title') ? 'has-error clearfix' : 'clearfix' }}" >
                            @if($categoryid==16)
                                <label class="col-lg-1 col-md-1 col-sm-1">标题</label>
                            @endif

                            @if($categoryid==16)  <div class="col-md-11">   @else  <div class="col-md-12">  @endif
                                @if ($errors->has('title'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                                @endif
                                <input class="col-lg-12 col-md-12 col-sm-12 form-control" type="text" id="title" name="title" required maxlength="30"  placeholder="标题, 限30字"/>
                                @if ($errors->has('title'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        @if($categoryid==16)

                            <div>
                                <label class="col-lg-1 col-md-1 col-sm-1" style="margin-top: 55px">视频文件</label>
                                <div class="col-md-4"  style="margin-top: 55px; margin-bottom: 55px">
                                    <input type="file" class="col-md-12 form-control-file" id="video" name="video" />
                                    {{--                                {!! Form::file('images', '', array('class'=>'col-md-12 form-control-file form-control', 'id'=>'images', 'required'=>'required')) !!}--}}
                                    {{--<img id="image" width="300" />--}}
                                </div>
                            </div>
                            <div class="clearfix"></div>

                            <div style="margin-bottom: 55px">
                                <label class="col-lg-1 col-md-1 col-sm-1">视频链接</label>
                                <div class="col-md-11">
                                    <input class="col-lg-12 col-md-12 col-sm-12 form-control" type="text" id="videolink" name="videolink" placeholder="如果已经添加视频文件, 则无需链接"/>
                                </div>

                                @if ($errors->has('description'))
                                    <span class="help-block">
                                            <strong>{{ $errors->first('description') }}</strong>
                                        </span>
                                @endif
                            </div><br>
                        @else
                            <div class="{{ isset($errors) && $errors->has('content') ? 'has-error clearfix' : 'clearfix' }}" style="margin-bottom: 55px">
                                {{--<label class="col-lg-12 col-md-12 col-sm-12 clearfix">内容</label>--}}
                                <div class="clearfix"></div>
                                <div class="col-md-12">
                                    <textarea class="col-lg-12 col-md-12 col-sm-12 form-control clearfix" id="content" name="content" height="50"></textarea>
                                    @if ($errors->has('content'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('content') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endif

                        {{--<div>--}}
                        {{--<label class="col-lg-12 col-md-12 col-sm-12">选择类型</label>--}}
                        {{--<div class="col-md-12">--}}
                        {{--<select class="col-lg-12 col-md-12 col-sm-12 form-control" name="type_id">--}}
                        {{--@foreach ($articletypes as $type)--}}
                        {{--<option value="{{$type->id}}">{{$type->name}}</option>--}}
                        {{--@endforeach--}}
                        {{--</select>--}}
                        {{--</div>--}}
                        {{--</div>--}}

                        <div>
                            <label class="col-lg-1 col-md-1 col-sm-1">选择标签</label>
                            <div class="col-md-11">
                                <input id="tags" name="tags" class="col-lg-12 col-md-12 col-sm-12 form-control" placeholder="(必填) 多个关键字之间用逗号隔开"  />
                                {{--<div class="col-lg-12 col-md-12 col-sm-12 highlight"  style="margin-top: 5px">--}}
                                {{--<span><small>提示现有的标签: {!! $tagString !!}</small></span>--}}
                                {{--</div>--}}
                            </div>
                        </div>


                    </div>
                    {!! Form::token() !!}
                    <div class=" col-lg-1 col-md-1 col-sm-1" style="margin-top: 55px">
                        <input type="submit" id="submit" value="保存" class="btn btn-default" />
                    </div>
                    <div>
                        <label class="col-md-3 published_label" style="margin-top: 60px">
                            <input class="published" type="checkbox" name="published"  /> 提交
                        </label>
                    </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.getElementById("images").onchange = function () {
//            var reader = new FileReader();
//
//            reader.onload = function (e) {
//                // get loaded data and render thumbnail.
//                document.getElementById("image").src = e.target.result;
//            };
//
//            // read the image file as a data URL.
//            reader.readAsDataURL(this.files[0]);
            var filepath = $("input[name='images']").val();
            var extStart = filepath.lastIndexOf(".");
            var ext = filepath.substring(extStart, filepath.length).toUpperCase();
            if (ext != ".BMP" && ext != ".PNG" && ext != ".GIF" && ext != ".JPG" && ext != ".JPEG") {
                alert("图片限于bmp,png,gif,jpeg,jpg格式");
                $("#fileType").text("")
                $("#fileSize").text("");
                $('input[type="file"]').val('');
                //                    $("img[id='image']").text("");
                return false;
            } else {
                $("#fileType").text(ext)
            }

            var reader = new FileReader();

            reader.onload = function (e) {
                // get loaded data and render thumbnail.
                document.getElementById("image").src = e.target.result;
            };

            // read the image file as a data URL.
            reader.readAsDataURL(this.files[0]);
        };

        document.getElementById("video").onchange = function () {
            var filepath = $("input[name='video']").val();
            var extStart = filepath.lastIndexOf(".");
            var ext = filepath.substring(extStart, filepath.length).toUpperCase();
            if (ext != ".MP4" && ext != ".MOV" && ext != ".OGV" && ext != ".MKV" && ext != ".OGG" && ext != ".M4V") {
                alert("视频限于mp4,mov,ogv,ogg,mkv,m4v格式");
                $("#fileType").text("")
                $("#fileSize").text("");
                $('input[type="file"]').val('');
                //                    $("img[id='image']").text("");
                return false;
            } else {
                $("#fileType").text(ext)
            }
//            alert("brower:" + navigator.appName.indexOf("Microsoft Internet Explorer"));
            var file_size = 0;
            if (navigator.appName.indexOf("Microsoft Internet Explorer") != -1) {
//                alert("ie brower");
                var img = new Image();
                img.src = filepath;
                while (true) {
                    if (img.fileSize > 0) {
                        if (img.fileSize > 20 * 1024 * 1024) {
                            alert("图片不大于20MB。");
                            $('input[type="file"]').val('');
                            return false;
                        } else {
                            var num03 = img.fileSize / 1024;
                            num04 = num03.toFixed(2)
                            $("#fileSize").text(num04 + "KB");
                        }
                        break;
                    }
                }
            } else {
//                alert("non ie brower");
                file_size = this.files[0].size;
                var size = file_size / 1024;
                if (size > 20 * 1024) {
                    alert("上传的视频大小不能超过20M, 如果超过20M请在下面填写视频文件链接!");
                    $('input[type="file"]').val('');
                    return false;
                } else {
                    var num01 = file_size / 1024;
                    num02 = num01.toFixed(2);
                    $("#fileSize").text(num02 + " KB");
                }
            }
            var reader = new FileReader();

            reader.onload = function (e) {
                // get loaded data and render thumbnail.
                document.getElementById("video").src = e.target.result;
            };

            // read the image file as a data URL.
            reader.readAsDataURL(this.files[1]);
        };
    </script>


    {{--video test script--}}
    <script src="{{ url('/src/js/jQuery.min.2.2.4.js') }}" ></script>
    <script type="text/javascript">
        jQuery('#category_id').change(function(){
            console.log($("#category_id").val());
            var categoryID = "/admin/createtest?categoryid="+$("#category_id").val();
            jQuery(window.location).attr('href', categoryID);
        });
    </script>
@endsection


<script src="{{ url('/src/js/vendor/tinymce/js/tinymce/tinymce.min.js') }}"></script>
<script>
    //    var sampleApp = angular.module('myapp', [], function($interpolateProvider) {
    //        $interpolateProvider.startSymbol('<%');
    //        $interpolateProvider.endSymbol('%>');
    //    });
</script>
<script src="{{ url('/src/js/jQuery.min.2.2.4.js') }}" ></script>
<script>
    var editor_config = {
        language:'zh_CN',
        height: "350",

        path_absolute : "{{ URL::to('/') }}/",
        selector: "textarea#content",
        plugins : 'link image imagetools preview',
        menubar: false,
        toolbar: 'undo redo | image | removeformat | bold italic underline strikethrough | alignleft aligncenter alignright',
        relative_urls: false,
        automatic_uploads: false,
        removeformat: [
            {selector: 'b,strong,em,i,font,u,strike', remove : 'all', split : true, expand : false, block_expand: true, deep : true},
            {selector: 'span', attributes : ['style', 'class'], remove : 'empty', split : true, expand : false, deep : true},
            {selector: '*', attributes : ['style', 'class'], split : false, expand : false, deep : true}
        ],
        file_browser_callback_types: 'image media',
        file_browser_callback : function(field_name, url, type, win) {
            var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
            var y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight;

            var cmsURL = editor_config.path_absolute + 'laravel-filemanager?field_name=' + field_name;
            {{--            var cmsURL = editor_config.path_absolute + '{{ Auth::user()->phone }}?field_name=' + field_name;--}}
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
        }
    };

    tinymce.init(editor_config);

    var changeFlag=false;
    //标识文本框值是否改变，为true，标识已变
    jQuery(document).ready(function(){
//        jQuery("#images").change(function() {
//            console.log('changes');
//            var reader = new FileReader();
//            reader.onload = function (e) {
//                console.log('onload');
//                // get loaded data and render thumbnail.
//                jQuery("#image").src = e.target.result;
//            };
//
//            // read the image file as a data URL.
//            reader.readAsDataURL(this.files[0]);
//        });

        jQuery('#title').blur(function(){
            var title =  jQuery('#title').val();
            console.log(title.length);
            if (title.length > 30) {
                jQuery('#title').append('<span><strong>文章标题太长</strong></span>');
                alert('文章标题超过30个字');
                return false;
            }
        });

        //文本框值改变即触发
        jQuery("input[type='text']").change(function(){
            changeFlag=true;
        });
        //文本域改变即触发
        jQuery("textarea").change(function(){
            changeFlag=true;
        });
    });

    //离开页面时保存文档
    //        jQuery('#submit').submit(
    //        window.onbeforeunload = function() {
    //            if(changeFlag ==true){
    //                return confirm("页面值已经修改，是否要保存？");
    //            }
    //        })
</script>

<script>
    //autocomplete
    jQuery( function() {
        var availableTags = {!! json_encode($tagArray) !!}
        function split( val ) {
            return val.split( /,\s*/ );
        }
        function extractLast( term ) {
            return split( term ).pop();
        }

        jQuery('#tags').on( "keydown", function( event ) { console.log('click');
            if ( event.keyCode === jQuery.ui.keyCode.TAB &&
                    jQuery( this ).autocomplete( "instance" ).menu.active ) {
                event.preventDefault();
            }
        }).autocomplete({
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