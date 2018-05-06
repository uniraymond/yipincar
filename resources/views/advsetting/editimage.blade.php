@extends('layouts.base')

@include('layouts.contentSideBar')
{{--@include('advsetting.sidebarType',['types'=>$types])--}}
@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12" style="margin-top: 35px">
                <h1 class="page-header">编辑广告</h1>

                <div>
                    @if ($fail = Session::get('warning'))
                        <div class="col-md-12 bs-example-bg-classes" >
                            <p class="bg-danger">
                                {{ $fail }}
                            </p>
                        </div>
                    @endif
                    {{ $success = Session::get('status')}}
                    @if (count($errors) > 0)
                        @foreach ($errors->all() as $error)
                            <div class="col-md-12 bs-example-bg-classes" >
                                <p class="bg-danger">
                                    {{ $error }}
                                </p>
                            </div>
                        @endforeach
                    @endif
                </div>



                <div class="col-lg-12 col-md-12 col-sm-12">

                    {!! Form::open(array('url' => 'admin/advsetting/updateimage', 'class' => 'form', 'enctype'=>'multipart/form-data')) !!}

                    {!! Form::label('template_label', '首页模版', array('class'=>'col-md-1', 'style'=>'margin-top: 55px')) !!}
                    <div class="col-md-8" style="margin-top: 55px">
                        @foreach ($templates as $temp)
                            <input type="radio" style="margin-left: 30px" name="temmplate_radio" id="temmplate_radio"  value="{{$temp->id}}" @if($advSettings->template_id == $temp->id) checked="checked" @endif />{{$temp->name}}
                            {{--<option value="{{$temp->id}}" @if($template == $temp->id) selected = 'selected' @endif>{{$temp->name}}</option>--}}
                        @endforeach
                        {{--</select>--}}
                    </div>
                    {{--</form>--}}
                    <div class="clearfix"></div>

                    <label class="col-lg-3 col-md-1 col-sm-1" style="margin-top: 55px">设置图片(点击图片重新设置)</label>
                    <div class="clearfix"></div>
                    @php
                    $advSettingsLinks = $advSettings->resources;
                    $links = array();
                    if (count($advSettingsLinks) > 0) {
                    foreach($advSettingsLinks as $artLink) {
                    array_push($links, $artLink);
                    }
                    }
                    usort($links, 'cmp');

                    function  cmp ( $linka ,  $linkb )
                    {
                    return  $linka->order  >  $linkb->order ;
                    }
                    @endphp
                    {{--<form style="position:relative">--}}
                    <div id="image_container1" class="col-md-3">
                        <input type="file" class="col-md-12 form-control-file" id="images1" name="images1" style="display:none"/>
                        {{--                                {!! Form::file('images', '', array('class'=>'col-md-12 form-control-file form-control', 'id'=>'images', 'required'=>'required')) !!}--}}
                        <label for="images1">　　
                            　　　　　　
                            <img  src="{{ $links[0]->link  }}" alt="{{ $advSettings->title }}" id="image1" width="200" />
                            　　
                            　　</label>
                    </div>


                    <div id="image_container2" class="col-md-3"  @if($advSettings->template_id == 3 && count($links) >= 3) style="display:block" @else  style="display:none" @endif>
                        <input type="file" class="col-md-12 form-control-file" id="images2" name="images2" style="display:none"/>
                        {{--                                {!! Form::file('images', '', array('class'=>'col-md-12 form-control-file form-control', 'id'=>'images', 'required'=>'required')) !!}--}}
                        <label for="images2">　　
                            　　　　　　
                            <img id="image2" width="200"   @if($advSettings->template_id == 3 && count($links) >= 3) src="{{$links[1]->link}}" @else src="/photos/add_image.jpg" @endif/>
                            　　
                            　　</label>
                    </div>

                    <div id="image_container3" class="col-md-3" @if($advSettings->template_id == 3 && count($links) >= 3) style="display:block" @else style="display:none" @endif>
                        <input type="file" class="col-md-12 form-control-file" id="images3" name="images3" style="display:none"/>
                        {{--                                {!! Form::file('images', '', array('class'=>'col-md-12 form-control-file form-control', 'id'=>'images', 'required'=>'required')) !!}--}}
                        <label for="images3">　　
                            　　　　　　
                            <img id="image3" @if($advSettings->template_id == 3 && count($links) >= 3) src="{{$links[2]->link}}" @else src="/photos/add_image.jpg" @endif width="200"/>
                            　　
                            　　</label>
                    </div>
                    {{--<div style="margin-bottom: 55px; margin-top: 55px">--}}
                        {{--<img src="{{ url($advSettings->resources->link) }}" alt="{{ $advSettings->description }}" width="300px"/>--}}
                    {{--</div>--}}
                    {{--<br>--}}

                    <div class="clearfix"></div>
                    {!! Form::label('template_id', '首页模版', array('class'=>'col-md-1')) !!}
                    <div class="col-md-2" style="margin-bottom: 55px">
                        <select class="col-lg-12 col-md-12 col-sm-12 form-control" name="template_id">
                            @foreach ($templates as $template)
                                <option value="{{$template->id}}" {{ isset($advSettings->template_id) && ($template->id == $advSettings->template_id) ? 'selected' : '' }} >{{$template->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="clearfix"></div>

                    {!! Form::date('published_at', date('Y-m-d', strtotime($advSettings->published_at)), array('class'=>'col-md-12','hidden', 'placehold'=>'开始日期')) !!}
                    <div class="clearfix"></div>
                    {!! Form::label('category_id', '栏目', array('class'=>'col-md-1')) !!}
                    <div class="col-md-2" style="margin-bottom: 55px">
                        <select class="col-lg-12 col-md-12 col-sm-12 form-control" name="category_id">
                            <option value="3">动态</option>
                            @foreach ($categories as $category)
                                <option value="{{$category->id}}" {{ $category->id == $advSettings->category_id ? 'selected' : '' }}>{{$category->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="clearfix"></div>

                    {!! Form::label('type_id', '选择类型', array('class'=>'col-md-1')) !!}
                    <div class="col-md-2" style="margin-bottom: 55px">
                        <select class="col-lg-12 col-md-12 col-sm-12 form-control" name="type_id">
                            @foreach ($types as $type)
                                <option value="{{$type->id}}" {{ $type->id == $advSettings->type_id ? 'selected' : '' }}>{{$type->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="clearfix"></div>
                    {!! Form::label('position_id', '选择位置', array('class'=>'col-md-1')) !!}
                    <div class="col-md-2" style="margin-bottom: 55px">
                        <select class="col-lg-12 col-md-12 col-sm-12 form-control" name="position_id">
                            @foreach ($positions as $position)
                                <option value="{{$position->id}}" {{ $position->id == $advSettings->type_id ? 'selected' : '' }}>{{$position->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="clearfix"></div>

                    {!! Form::label('order', '显示顺序', array('class'=>'col-md-1')) !!}
                    <div class="col-md-2" style="margin-bottom: 55px">
                        <select class="col-lg-12 col-md-12 col-sm-12 form-control" name="order">
                            @for($i=1; $i<=30; $i++)
                                <option value="{{ $i }}" {{ $i == $advSettings->order ? 'selected' : '' }} >{{ $i }}</option>i
                            @endfor
                        </select>
                    </div>

                    <div class="clearfix"></div>
                    {!! Form::label('title', '标题', array('class'=>'col-md-1')) !!}
                    <div class="col-md-11" style="margin-bottom: 55px">
                        {!! Form::text('title', $advSettings->title, array('class' => 'input col-md-12 form-control', 'placeholder' => '标题')) !!}
                    </div>
                    <div class="clearfix"></div>
                    {!! Form::label('description', '广告描述', array('class'=>'col-md-1')) !!}
                    <div class="col-md-11" style="margin-bottom: 55px">
                        <textarea name="description" class="description input col-md-12 form-control" placeholder="详细信息" rows="3" >{{ $advSettings->description }}</textarea>
                    </div>
                    <div class="clearfix"></div>


                    @if ($errors->has('links'))
                        <span class="help-block">
                            <strong>{{ $errors->first('links') ? '链接不能为空' : '' }}</strong>
                        </span>
                    @endif
                    <div class="clearfix"></div>
                    {!! Form::label('links', '链接*', array('class'=>'col-md-1')) !!}
                    <div class="col-md-11" style="margin-bottom: 55px">
                        {!! Form::text('links', $advSettings->links, array('class' => 'input col-md-12 form-control', 'placeholder' => '链接')) !!}
                    </div>
                    {{--<div class="clearfix"></div>--}}
{{--                    {!! Form::label('published_at', '开始显示日期', array('class'=>'col-md-12')) !!}--}}


                    @if( Auth::user()->hasAnyRole(['adv_editor']) )
                        <div>
                            <label class="col-md-3 published_label" for="status">
                                <input class="status" type="checkbox" name="status" {{ $advSettings->status > 1 ? 'checked' : '' }} /> 提交审查
                            </label>
                        </div>
                    @endif

                    <div class="clearfix"></div>

                    <div>
                        @if (Auth::user()->hasAnyRole(['super_admin', 'admin', 'chef_editor', 'adv_editor']) && $advSettings->status == 4)
                            <div id="settop_error" class="alert-danger"></div>
                            <div class="clearfix"></div>
                            <label class="col-md-3 published_label" for="top">
                                <input id="settop" class="top" type="checkbox" name="top" {{ $advSettings->top ? 'checked' : '' }} /> 置顶
                            </label>
                        @endif
                    </div>

                    {!! Form::token() !!}
                    {!! Form::text('id', $advSettings->id, array('hidden', 'readonly')) !!}
                    <div class="clearfix"></div>
                    <br>
                    <br>
                    <div class=" col-lg-12 col-md-12 col-sm-12" style="margin-bottom: 55px">
                        {!! Form::submit('保存', array('class'=>'btn btn-primary')) !!}
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    <script src="{{ url('/src/js/jQuery.min.2.2.4.js') }}"></script>
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
    </script>

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

    </script>
@endsection