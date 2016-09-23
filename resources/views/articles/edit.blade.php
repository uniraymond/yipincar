@extends('layouts.base')
<link rel="stylesheet" href="{{ asset("/src/css/jquery-ui.min.css") }}" />
@include('layouts.contentSideBar')
{{--@include('articles.sidebarCategory',['categories'=>$categories, 'types'=>$types, 'tag'=>$tags, 'currentAction'=>$currentAction])--}}
@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">编辑文章</h1>

                @if ($fail = Session::get('warning'))
                    <div class="col-md-12 bs-example-bg-classes" >
                        <p class="bg-danger">
                            {{ $fail }}
                        </p>
                    </div>
                @endif

                <div class="col-lg-12 col-md-12 col-sm-12">
                    {!! Form::open(array('url' => 'admin/article/'.$article->id, 'class' => 'form', 'method'=>'put', 'enctype'=>'multipart/form-data')) !!}
                    <div class="form-group  col-lg-12 col-md-12 col-sm-12" >
                        <div class="{{ isset($errors) && $errors->has('title') ? 'has-error clearfix' : 'clearfix' }}" style="margin-bottom: 5px" >
                            <label class="col-lg-12 col-md-12 col-sm-12">标题</label>
                            <div class="col-md-12">
                                <input class="col-lg-12 col-md-12 col-sm-12 form-control" type="text" id="title" name="title" required  value="{{ $article->title }}"/>
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
                                <textarea class="col-lg-12 col-md-12 col-sm-12 form-control" type="text" id="description" name="description" placeholder="简介" >{{ $article->description }}</textarea>
                            </div>
                        </div>

                        <div>
                            <label class="col-lg-12 col-md-12 col-sm-12">选择栏目</label>
                            <div class="col-md-12">
                                <select class="col-lg-12 col-md-12 col-sm-12 form-control" name="category_id">
                                    @foreach($categories as $category)
                                        <option {{ $article->category_id == $category->id ? 'selected' : '' }} value="{{$category->id}}">
                                            {{$category->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class={{ isset($errors) && $errors->has('content') ? 'has-error clearfix' : 'clearfix' }}>
                            <label class="col-lg-12 col-md-12 col-sm-12 clearfix">内容</label>
                            <div class="clearfix"></div>
                            <div class="col-md-12">
                                <textarea class="col-lg-12 col-md-12 col-sm-12 form-control clearfix" id="content" name="content" height="50">{{ $article->content }}</textarea>
                                @if ($errors->has('content'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('content') ? '内容不能为空' : ''}}</strong>
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
                            <label class="col-lg-12 col-md-12 col-sm-12">选择标签(多个关键字之间用逗号隔开)</label>
                            <div class="col-md-12">
                                <input id="tags" name="tags" class="col-lg-12 col-md-12 col-sm-12 form-control" placeholder="选择标签" value="{!! $currentTagString !!}"  />
                                <div class="col-lg-12 col-md-12 col-sm-12 highlight">
                                    <span><small>提示现有的标签: {{ $tagString }}</small></span>
                                </div>
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
                        @if( Auth::user()->hasAnyRole(['super_admin', 'admin', 'chef_editor', 'main_editor']))
                           <div>
                               <div id="settop_error" class="alert-danger"></div>
                               <div class="clearfix"></div>
                               <label class="col-md-3 published_label" for="top">
                                   <input id="settop" class="top" type="checkbox" name="top" {{ $article->top ? 'checked' : '' }} /> 置顶
                               </label>
                           </div>
                        @endif
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
       <script src="{{ url('/src/js/jQuery.min.2.2.4.js') }}" ></script>
       <script>
           jQuery(document).ready(function(){
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
               toolbar: 'undo redo | image | removeformat',
               relative_urls: false,
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
   @endsection
