@extends('layouts.base')
<link rel="stylesheet" href="{{ asset("/src/css/jquery-ui.min.css") }}" />
@include('layouts.contentSideBar')
{{--@include('articles.sidebarCategory',['categories'=>$categories, 'types'=>$types, 'tag'=>$tags, 'currentAction'=>$currentAction])--}}
@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12" style="margin-top: 35px">
                <h1 class="page-header">编辑文章</h1>

                @if ($fail = Session::get('warning'))
                    <div class="col-md-12 bs-example-bg-classes" >
                        <p class="bg-danger">
                            {{ $fail }}
                        </p>
                    </div>
                @endif

                @if (count($errors) > 0)
                    <span class="help-block">
                         <strong>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </strong>
                    </span>
                @endif

                <div class="col-lg-12 col-md-12 col-sm-12" style="margin-top: 55px">
                    {!! Form::open(array('url' => 'admin/article/', 'class' => 'form', 'method'=>'put', 'enctype'=>'multipart/form-data')) !!}
                    <div class="form-group  col-lg-12 col-md-12 col-sm-12" >


                        <div>
                            <label class="col-lg-1 col-md-1 col-sm-1" style="margin-bottom: 55px">选择栏目</label>
                            <div class="col-md-2" style="margin-bottom: 55px">
                                <select class="col-lg-12 col-md-12 col-sm-12 form-control" name="category_id">
                                    @if(Auth::user()->hasAnyRole([ 'auth_editor']))
                                        @foreach($categories as $category)
                                            @if($category->id == 5 || $category->id == 6 || $category->id == 7)
                                                <option value="{{$category->id}}">
                                                    {{$category->name}}
                                                </option>
                                            @endif
                                        @endforeach
                                    @else
                                        @foreach($categories as $category)
                                            <option value="{{$category->id}}">
                                                {{$category->name}}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
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

                        @if( !Auth::user()->hasAnyRole([ 'auth_editor']))
                        {!! Form::label('template_label', '首页模版', array('class'=>'col-md-1', 'style'=>'margin-top: 55px')) !!}

                        <form id="template_form" >
                            <div class="col-md-8" style="margin-top: 55px">
                                {{--<select class="col-lg-12 col-md-12 col-sm-12 form-control" name="temmplate_radio" id="temmplate_radio">--}}
                                @foreach ($templates as $temp)
                                    <input type="radio" style="margin-left: 30px" name="temmplate_radio" id="temmplate_radio"  value="{{$temp->id}}" @if(1 == $temp->id) checked="checked" @endif />{{$temp->name}}
                                    {{--<option value="{{$temp->id}}" @if($template == $temp->id) selected = 'selected' @endif>{{$temp->name}}</option>--}}
                                @endforeach
                                {{--</select>--}}
                            </div>
                        </form>
                        <div class="clearfix"></div>
                        @endif

                        <div>
                            <label class="col-lg-1 col-md-1 col-sm-1" style="margin-top: 75px">首页图片</label>

                            {{--<form style="position:relative">--}}
                                <div id="image_container1" class="col-md-3"  style=" margin-top: 55px;">
                                    <input type="file" class="col-md-12 form-control-file" id="images1" name="images1"  style="display:none"/>
                                    {{--                                {!! Form::file('images', '', array('class'=>'col-md-12 form-control-file form-control', 'id'=>'images', 'required'=>'required')) !!}--}}
                                    <label for="images1">　　
                                        　　　　　　
                                        <img src="/photos/add_image.jpg" id="image1" width="200" />
                                        　　
                                        　　</label>
                                </div>

{{--                            @if($template==3)--}}
                                <div id="image_container2" class="col-md-3"  style=" margin-top: 55px; display:none">
                                    <input type="file" class="col-md-12 form-control-file" id="images2" name="images2" style="display:none"/>
                                    {{--                                {!! Form::file('images', '', array('class'=>'col-md-12 form-control-file form-control', 'id'=>'images', 'required'=>'required')) !!}--}}
                                    <label for="images2">　　
                                        　　　　　　
                                        <img src="/photos/add_image.jpg" id="image2" width="200"/>
                                        　　
                                        　　</label>
                                </div>

                                <div id="image_container3" class="col-md-3"  style=" margin-top: 55px; display:none">
                                    <input type="file" class="col-md-12 form-control-file" id="images3" name="images3" style="display:none"/>
                                    {{--                                {!! Form::file('images', '', array('class'=>'col-md-12 form-control-file form-control', 'id'=>'images', 'required'=>'required')) !!}--}}
                                    <label for="images3">　　
                                        　　　　　　
                                        <img src="/photos/add_image.jpg" id="image3" width="200" />
                                        　　
                                        　　</label>
                                </div>
                            {{--@endif--}}


                            {{--</form>--}}

                        </div>
                        <div class="clearfix"/>

                        @if( !Auth::user()->hasAnyRole([ 'auth_editor']))
                            <div>
                                <label class="col-md-3 published_label" style="margin-top: 60px">
                                    <input class="published" type="checkbox" name="watermark" id="watermark" /> 添加水印
                                </label>
                            </div>
                        @endif

                        <div class="{{ isset($errors) && $errors->has('title') ? 'has-error clearfix' : 'clearfix' }}" style="margin-bottom: 0px" >
                            {{--<label class="col-lg-1 col-md-1 col-sm-1">标题</label>--}}
                            <div class="col-md-12">
                                @if ($errors->has('title'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                                @endif
                                <input class="col-lg-12 col-md-12 col-sm-12 form-control" type="text" id="title" name="title" required maxlength="35"  placeholder="标题, 限35字"/>
                                @if ($errors->has('title'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
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

    <script src="{{ url('/src/js/vendor/tinymce/js/tinymce/tinymce.min.js') }}"></script>
    <script>
        document.getElementById("images1").onchange = function () {
            var reader = new FileReader();

            reader.onload = function (e) {
                // get loaded data and render thumbnail.
                document.getElementById("image1").src = e.target.result;
            };

            // read the image file as a data URL.
            reader.readAsDataURL(this.files[0]);
        };

        document.getElementById("images2").onchange = function () {
            var reader = new FileReader();

            reader.onload = function (e) {
                // get loaded data and render thumbnail.
                document.getElementById("image2").src = e.target.result;
            };

            // read the image file as a data URL.
            reader.readAsDataURL(this.files[0]);
        };

        document.getElementById("images3").onchange = function () {
            var reader = new FileReader();

            reader.onload = function (e) {
                // get loaded data and render thumbnail.
                document.getElementById("image3").src = e.target.result;
            };

            // read the image file as a data URL.
            reader.readAsDataURL(this.files[0]);
        };
    </script>
{{--@endsection--}}

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
        plugins : ['link image imagetools preview', 'paste'],
        menubar: false,
        toolbar: 'undo redo | image | removeformat | bold italic underline strikethrough | alignleft aligncenter alignright | link',
        relative_urls: false,
        automatic_uploads: false,
        removeformat: [
            {selector: 'b,strong,em,i,font,u,strike', remove : 'all', split : true, expand : false, block_expand: true, deep : true},
            {selector: 'span', attributes : ['style', 'class'], remove : 'empty', split : true, expand : false, deep : true},
            {selector: '*', attributes : ['style', 'class'], split : false, expand : false, deep : true}
        ],

        paste_auto_cleanup_on_paste : true,
        paste_remove_styles: true,
        paste_remove_styles_if_webkit: true,
        paste_strip_class_attributes: true,

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
            if (title.length > 35) {
                jQuery('#title').append('<span><strong>文章标题太长</strong></span>');
                alert('文章标题超过35个字');
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

<script src="{{ url('/src/js/jQuery.min.2.2.4.js') }}" ></script>
    <script type="text/javascript">
        var val = document.getElementByIdx("temmplate_radio").value;
        alert('checked value: ' + val);

        $('#temmplate_radio').each(function(){
//            $('#temmplate_radio').ajaxForm(options).submit()
            var val;
            if($(this).attr("checked")){
                val=$(this).attr("value")
            }
            alert('checked value: ' + val);

        });
//    jQuery('#temmplate_radio').change(function(){
    $(document).ready(function() {
        var options = {
            beforeSubmit: setTemplate,
            success:      showResponse,
//            dataType: json,
        }
        $('#temmplate_radio').each(function(){
//            $('#temmplate_radio').ajaxForm(options).submit()
            var val;
            if($(this).attr("checked")){
                val=$(this).attr("value")
            }
            alert('checked value: ' + val);

        });

        function setTemplate() {
            alert('value changed' + $('#temmplate_radio').val());
            if ($('#temmplate_radio').val()==3) {
                $('#image2').css('display', 'block');
                $('#image3').css('display', 'block');
            } else {
                $('#image2').css('display', 'none');
                $('#image3').css('display', 'none');
            }
        }

        function showResponse() {
            alert('value changed' + $('#temmplate_radio').val());
            if ($('#temmplate_radio').val()==3) {
                $('#image2').css('display', 'block');
                $('#image3').css('display', 'block');
            } else {
                $('#image2').css('display', 'none');
                $('#image3').css('display', 'none');
            }
        }
    }

        {{--$(".temmplate_radio").empty();--}}

{{--// 实际的应用中，这里的option一般都是用循环生成多个了--}}

{{--//            var option = $("<option>").val(1).text("pxx");--}}
{{--//--}}
{{--//            $(".temmplate_radio").append(option);--}}
        {{--console.log($("#temmplate_radio").val());--}}
        {{--var temmplate_radiourl = "/admin/article/create?template="+$("#temmplate_radio").val()+"&authname="+$("#authname").val();--}}
        {{--jQuery(window.location).attr('href', temmplate_radiourl);--}}
//    });
</script>
<script src="{{ url('/src/js/jQuery.min.2.2.4.js') }}" ></script>
<script type="text/javascript">

    $(document).ready(function() {
        $('input[name=temmplate_radio]').change(function(){
//            alert('raido value: ' + $(this).val());
            if ($(this).val()==3) {
                $('#image_container2').css('display', 'block');
                $('#image_container3').css('display', 'block');
            } else {
                $('#image_container2').css('display', 'none');
                $('#image_container3').css('display', 'none');
            }
            $('#template_form').submit();
        });
    });

//    $("[name=template_id]").each(function(i,v){
//        var dep2 = $(this).val();
//        alert(dep2);
////        if(data.dep == dep2) $(this).prop("checked",true);
//    });

//    jQuery('#template_id').each(function(){
//        alert('$("#template_id").val()');
////        $(".template_id").empty();
////        console.log($("#template_id").val());
////        var temmplate_radiourl = "/admin/article/create?template="+$("#template_id").val();
////        jQuery(window.location).attr('href', temmplate_radiourl);
//    });
</script>

@stop