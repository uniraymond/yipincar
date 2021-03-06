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
                    @foreach ($errors->all() as $error)
                        <div class="col-md-12 bs-example-bg-classes" >
                            <p class="bg-danger">
                                {{ $error }}
                            </p>
                        </div>
                    @endforeach
                @endif

                <div class="col-lg-12 col-md-12 col-sm-12" style="margin-top: 55px">
                    {!! Form::open(array('url' => 'admin/article/'.$article->id, 'class' => 'form', 'method'=>'put', 'enctype'=>'multipart/form-data')) !!}
                    <div class="form-group  col-lg-12 col-md-12 col-sm-12" >

                        <div>
                            <label class="col-lg-1 col-md-1 col-sm-1" style="margin-bottom: 55px">选择栏目</label>
                            <div class="col-md-2">
                                <select class="col-lg-12 col-md-12 col-sm-12 form-control" name="category_id">
                                    @if(Auth::user()->hasAnyRole([ 'auth_editor']))
                                        @foreach($categories as $category)
                                            @if($category->id == 5 || $category->id == 6 || $category->id == 7)
                                                <option value="{{$category->id}}" {{ $article->category_id == $category->id ? 'selected' : '' }}>
                                                    {{$category->name}}
                                                </option>
                                            @endif
                                        @endforeach
                                    @else
                                        @foreach($categories as $category)
                                            <option value="{{$category->id}}" {{ $article->category_id == $category->id ? 'selected' : '' }}>
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
                                <label class="col-lg-1 col-md-1 col-sm-1">作者</label>
                                <div class="col-md-2">
                                    <input class="col-lg-12 col-md-12 col-sm-12 form-control" type="text" id="authname" name="authname" value="{{ $article->authname }}"  />
                                </div>
                            </div>
                        @endif

                        <div>
                            <label class="col-lg-1 col-md-1 col-sm-1">简介</label>
                            <div class="col-md-11">
                                <textarea class="col-lg-12 col-md-12 col-sm-12 form-control" type="text" id="description" name="description" placeholder="限140字" maxlength="140">{{ $article->description }}</textarea>
                            </div>
                        </div>

                        @if( !Auth::user()->hasAnyRole([ 'auth_editor']))
                            {!! Form::label('template_label', '首页模版', array('class'=>'col-md-1', 'style'=>'margin-top: 55px')) !!}

                            <form id="template_form" >
                                <div class="col-md-8" style="margin-top: 55px">
                                    {{--<select class="col-lg-12 col-md-12 col-sm-12 form-control" name="temmplate_radio" id="temmplate_radio">--}}
                                    @foreach ($templates as $temp)
                                        <input type="radio" style="margin-left: 30px" name="temmplate_radio" id="temmplate_radio"  value="{{$temp->id}}" @if($article->template_id == $temp->id) checked="checked" @endif />{{$temp->name}}
                                        {{--<option value="{{$temp->id}}" @if($template == $temp->id) selected = 'selected' @endif>{{$temp->name}}</option>--}}
                                    @endforeach
                                    {{--</select>--}}
                                </div>
                            </form>
                            <div class="clearfix"></div>
                        @endif

                        <div>
                            <label class="col-lg-3 col-md-1 col-sm-1" style="margin-top: 75px">首页图片(点击图片重新设置)</label>
                            <div class="clearfix"></div>
                            @php
                            $articleLinks = $article->resources;
                            $links = array();
                            if (count($articleLinks) > 0) {
                            foreach($articleLinks as $artLink) {

                                array_push($links, $artLink);
                            }
                            }
                            usort($links, 'cmp');

                            function  cmp ( $linka ,  $linkb )
                            {
                            return $linka->order  >  $linkb->order;
                            }
                            @endphp
                            {{--<form style="position:relative">--}}
                            <div id="image_container1" class="col-md-3">
                                <input type="file" class="col-md-12 form-control-file" id="images1" name="images1" style="display:none"/>
                                {{--                                {!! Form::file('images', '', array('class'=>'col-md-12 form-control-file form-control', 'id'=>'images', 'required'=>'required')) !!}--}}
                                <label for="images1">　　                                    　　　　　
                                    <img  @if(count($links)) src="{{$links[0]->link}}" @else src="/photos/add_image.jpg" @endif alt="{{ $article->title }}" id="image1" width="200" />
                                    　　
                                    　　</label>
                            </div>


                            <div id="image_container2" class="col-md-3"  @if($article->template_id == 3 && count($links) >= 3) style="display:block"  @else  style="display:none" @endif>
                                <input type="file" class="col-md-12 form-control-file" id="images2" name="images2" style="display:none"/>
                                {{--                                {!! Form::file('images', '', array('class'=>'col-md-12 form-control-file form-control', 'id'=>'images', 'required'=>'required')) !!}--}}
                                <label for="images2">　　
                                    　　　　　　
                                    <img id="image2" width="200"   @if($article->template_id == 3 && count($links) >= 3) src="{{$links[1]->link}}" @else src="/photos/add_image.jpg" @endif/>
                                    　　
                                    　　</label>
                            </div>

                            <div id="image_container3" class="col-md-3" @if($article->template_id == 3 && count($links) >= 3) style="display:block" @else style="display:none" @endif>
                                <input type="file" class="col-md-12 form-control-file" id="images3" name="images3" style="display:none"/>
                                {{--                                {!! Form::file('images', '', array('class'=>'col-md-12 form-control-file form-control', 'id'=>'images', 'required'=>'required')) !!}--}}
                                <label for="images3">　　
                                    　　　　　　
                                    <img id="image3" @if($article->template_id == 3 && count($links) >= 3) src="{{$links[2]->link}}" @else src="/photos/add_image.jpg" @endif width="200"/>
                                    　　
                                    　　</label>
                            </div>
                            {{--@endif--}}


                            {{--</form>--}}

                        </div>
                        <div class="clearfix"/>

                        @if( !Auth::user()->hasAnyRole([ 'auth_editor']))
                            <div>
                                <label class="col-md-3 published_label" style="margin-top: 60px">
                                    <input class="published" type="checkbox" name="watermark" id="watermark" @if($article->watermark) checked="checked" @endif/> 添加水印
                                </label>
                            </div>
                        @endif

                        {{--<div>--}}
                            {{--<label class="col-lg-1 col-md-1 col-sm-1" style="margin-top: 55px">首页图片</label>--}}
                            {{--<div class="col-md-4" style="margin-top: 55px; margin-bottom: 55px">--}}
{{--                                {!! Form::file('images', '', array('class'=>'col-md-12 form-control-file form-control', 'id'=>'files images', 'required'=>'required')) !!}--}}
                                {{--<input type="file" id="images" name="images" class="col-md-12 form-control-file form-control" />--}}
                                {{--@php--}}
                                    {{--$articleLinks = $article->resources;--}}
                                    {{--$articleLink = '';--}}
                                    {{--if (count($articleLinks) > 0) {--}}
                                        {{--foreach($articleLinks as $artLink) {--}}
                                            {{--$articleLink = $artLink->link;--}}
                                            {{--break;--}}
                                        {{--}--}}
                                    {{--}--}}
                                {{--@endphp--}}
                                {{--<img id="image" width="100" src="{{ $articleLink  }}" alt="{{ $article->title }}" />--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        <div class="{{ isset($errors) && $errors->has('title') ? 'has-error clearfix' : 'clearfix' }}" style="margin-bottom: 0px" >
                            {{--<label class="col-lg-12 col-md-12 col-sm-12">标题</label>--}}
                            <div class="col-md-12">
                                <input class="col-lg-12 col-md-12 col-sm-12 form-control" type="text" id="title" name="title" required  value="{{ $article->title }}" maxlength="35"   placeholder="标题, 限35字"/>
                                @if ($errors->has('title'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>



                        <div class={{ isset($errors) && $errors->has('content') ? 'has-error clearfix' : 'clearfix' }} style="margin-bottom: 55px">
                            {{--<label class="col-lg-12 col-md-12 col-sm-12 clearfix">内容</label>--}}
                            <div class="clearfix"></div>
                            <div class="col-md-12">
                                <textarea class="col-lg-12 col-md-12 col-sm-12 form-control clearfix" id="content" name="content" height="50">{{ $article->content }}</textarea>
                                @if ($errors->has('content'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('content')}}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{--<div>--}}
                            {{--<label class="col-lg-12 col-md-12 col-sm-12">选择类型</label>--}}
                            {{--<div class="col-md-12">--}}
                                {{--<select class="col-lg-12 col-md-12 col-sm-12 form-control" name="type_id">--}}
                                    {{--@foreach ($articletypes as $type)--}}
                                        {{--<option {{ $article->type_id == $type->id ? 'selected' : '' }} value="{{$type->id}}">{{$type->name}}</option>--}}
                                    {{--@endforeach--}}
                                {{--</select>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        <div>
                            <label class="col-lg-1 col-md-1 col-sm-1">选择标签</label>
                            <div class="col-md-11">
                                <input id="tags" name="tags" class="col-lg-12 col-md-12 col-sm-12 form-control" placeholder="(必填) 多个关键字之间用逗号隔开" value="{!! $currentTagString !!}"  />
                                {{--<div class="col-lg-12 col-md-12 col-sm-12 highlight"  style="margin-top: 5px">--}}
                                    {{--<span><small>提示现有的标签: {{ $tagString }}</small></span>--}}
                                {{--</div>--}}
                            </div>
                        </div>

                        @if( Auth::user()->hasAnyRole(['editor', 'auth_editor']) && $article->published <= 2 )
                            <div>
                                <label class="col-md-3 published_label" for="published">
                                    <input class="published" type="checkbox" name="published" {{ $article->published == 2 ? 'checked' : '' }} /> 提交审查
                                </label>
                            </div>
                        @endif


                        <div class="clearfix"></div>
                        {{--@if( Auth::user()->hasAnyRole(['super_admin', 'admin', 'chef_editor', 'main_editor', 'adv_editor']) && $article->published == 4)--}}
                           {{--<div>--}}
                               {{--<div id="settop_error" class="alert-danger"></div>--}}
                               {{--<div class="clearfix"></div>--}}
                               {{--<label class="col-md-3 published_label" for="top">--}}
                                   {{--<input id="settop" class="top" type="checkbox" name="top" {{ $article->top ? 'checked' : '' }} /> 置顶--}}
                               {{--</label>--}}
                           {{--</div>--}}
                        {{--@endif--}}
                       </div>
                       {!! Form::token() !!}
                       <div class=" col-lg-12 col-md-12 col-sm-12" style="margin-top: 55px; margin-bottom: 55px">
                           <input type="submit" id="submit" value="保存" class="btn btn-default" />
                       </div>
                       </form>
                   </div>

               </div>
           </div>
       </div>
       <script src="{{ url('/src/js/jQuery.min.2.2.4.js') }}" ></script>
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
//           document.getElementById("images").onchange = function () {
//               var reader = new FileReader();
//
//               reader.onload = function (e) {
//                   // get loaded data and render thumbnail.
//                   document.getElementById("image").src = e.target.result;
//               };
//
//               // read the image file as a data URL.
//               reader.readAsDataURL(this.files[0]);
//           };

           jQuery(document).ready(function(){
               jQuery('#title').blur(function(){
                   var title =  jQuery('#title').val();
                   console.log(title.length);
                   if (title.length > 35) {
                       jQuery('#title').append('<span><strong>文章标题太长</strong></span>');
                       alert('文章标题超过35个字');
                       return false;
                   }
               });
               jQuery('#settop').click(function(){
                   jQuery.ajax({
                       url: '/admin/advsetting/checktop',
                       type: "GET",
                       success: function(data){
                           console.log(data.status);
                           if (data.status == 'faild') {
                               jQuery('#settop_error').html('文章或广告已达置顶上限.').delay(3000).fadeOut('slow');;
                               jQuery('#settop').prop("disabled", true).prop('checked', false).val(0);
                           }
                       }
                   });
               });
           });
       </script>
       <script src="/src/js/vendor/tinymce/js/tinymce/tinymce.min.js"></script>
       <script>
           var editor_config = {
               height: "350",
               path_absolute : "{{ URL::to('/') }}/",
               selector: "textarea#content",
               plugins : 'link image imagetools preview',
               menubar: false,
               toolbar: 'undo redo | image | removeformat | bold italic underline strikethrough | alignleft aligncenter alignright | link',
               relative_urls: false,
               object_resizing: true,
               automatic_uploads: true,
               
               removeformat: [
                   {selector: 'b,strong,em,i,font,u,strike', remove : 'all', split : true, expand : false, block_expand: true, deep : true},
                   {selector: 'span', attributes : ['style', 'class'], remove : 'empty', split : true, expand : false, deep : true},
                   {selector: '*', attributes : ['style', 'class'], split : false, expand : false, deep : true}
               ],

               paste_auto_cleanup_on_paste : true,
               paste_remove_styles: true,
               paste_remove_styles_if_webkit: true,
               paste_strip_class_attributes: true,
//               invalid_styles: 'width',
//               extended_valid_elements : "img[class|src|border=0|alt|title|hspace|vspace|align|onmouseover|onmouseout|name]",
               file_browser_callback_types: 'image media',
               file_browser_callback : function(field_name, url, type, win) {
                   var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
                   var y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight;

                   var cmsURL = editor_config.path_absolute + 'laravel-filemanager?field_name=' + field_name;
//                   var cmsURL = editor_config.path_absolute + 'laravel-filemanager?field_name=' + field_name;
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
                       close_previous : "no",
                       removeformat: [
                           {selector: 'b,strong,em,i,font,u,strike', remove : 'all', split : true, expand : false, block_expand: true, deep : true},
                           {selector: 'span', attributes : ['style', 'class'], remove : 'empty', split : true, expand : false, deep : true},
                           {selector: '*', attributes : ['style', 'class'], split : false, expand : false, deep : true}
                       ]
                   });
               }
           };

           tinymce.init(editor_config);

           var changeFlag=false;
           //标识文本框值是否改变，为true，标识已变
           $(document).ready(function(){
               //文本框值改变即触发
               $("input[type='text']").change(function(){
                   changeFlag=true;
               });
               //文本域改变即触发
               $("textarea").change(function(){
                   changeFlag=true;
               });
           });
       </script>
       <script>
           //autocomplete
           jQuery( function() {
               {{--        var availableTags = [ {!! $tagString !!} ];--}}
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


    <script src="{{ url('/src/js/jQuery.min.2.2.4.js') }}" ></script>
    <script type="text/javascript">

        $(document).ready(function() {
            $('input[name=temmplate_radio]').change(function(){
//            alert('image1 value: ' + document.getElementById("image1").src);
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

    </script>
   @endsection
