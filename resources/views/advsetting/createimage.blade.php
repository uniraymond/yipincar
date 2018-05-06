@extends('layouts.base')

@include('layouts.contentSideBar')
{{--@include('advsetting.sidebarType',['types'=>$types])--}}
@section('content')
  <div id="page-wrapper">
    <div class="row">
      <div class="col-lg-12" style="margin-top: 35px">
        <h1 class="page-header">新建广告</h1>

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
              <span class="help-block">
                <strong>
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </strong>
            </span>
          @endif
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12" style="margin-top: 55px">
          {!! Form::open(array('url' => 'admin/advsetting/uploadimage', 'class' => 'form', 'enctype'=>'multipart/form-data', 'method'=>'put')) !!}

            {{--{!! Form::label('template_id', '首页模版', array('class'=>'col-md-1')) !!}--}}
            {{--<div class="col-md-2" style="margin-bottom: 55px">--}}
                {{--<select class="col-lg-12 col-md-12 col-sm-12 form-control" name="template_id">--}}
                    {{--@foreach ($templates as $template)--}}
                        {{--<option value="{{$template->id}}" >{{$template->name}}</option>--}}
                    {{--@endforeach--}}
                {{--</select>--}}
            {{--</div>--}}
          <div>
            <label class="col-md-3 published_label">
              <input class="published" type="checkbox" name="identification" id="identification"  checked="checked"/> 添加"推广"标识
            </label>
          </div>
          <div class="clearfix"></div>

          {!! Form::label('template_label', '首页模版', array('class'=>'col-md-1', 'style'=>'margin-top: 55px')) !!}

          {{--<form id="template_form" >--}}
            <div class="col-md-8" style="margin-top: 55px">
              {{--<select class="col-lg-12 col-md-12 col-sm-12 form-control" name="temmplate_radio" id="temmplate_radio">--}}
              @foreach ($templates as $temp)
                <input type="radio" style="margin-left: 30px" name="temmplate_radio" id="temmplate_radio"  value="{{$temp->id}}" @if(1 == $temp->id) checked="checked" @endif />{{$temp->name}}
                {{--<option value="{{$temp->id}}" @if($template == $temp->id) selected = 'selected' @endif>{{$temp->name}}</option>--}}
              @endforeach
              {{--</select>--}}
            </div>
          {{--</form>--}}
            <div class="clearfix"></div>

          <div>
            <label class="col-lg-1 col-md-1 col-sm-1" style="margin-top: 75px">添加图片*</label>

            {{--<form style="position:relative">--}}
            <div id="image_container1" class="col-md-3"  style=" margin-top: 55px;">
              <input type="file" class="col-md-12 form-control-file" id="images1" name="images1"  style="display:none"/>
              {{--                                {!! Form::file('images', '', array('class'=>'col-md-12 form-control-file form-control', 'id'=>'images', 'required'=>'required')) !!}--}}
              <label for="images1">　　
                　　　　　　
                <img src="/photos/add_image.jpg" id="image1" width="200" />
                　　
                　　</label>
            </div>

            <div id="image_container2" class="col-md-3" style=" margin-top: 55px; display:none">
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


            {{--</form>--}}

          </div>
          <div class="clearfix"/>

          {!! Form::label('category_id', '栏目', array('class'=>'col-md-1')) !!}
          <div class="col-md-2" style="margin-bottom: 55px">
            <select class="col-lg-12 col-md-12 col-sm-12 form-control" name="category_id">
              <option value="3">动态</option>
              @foreach ($categories as $category)
                <option value="{{$category->id}}">{{$category->name}}</option>
              @endforeach
            </select>
          </div>
          <div class="clearfix"></div>

          {!! Form::label('type_id', '选择类型', array('class'=>'col-md-1')) !!}
          <div class="col-md-2" style="margin-bottom: 55px">
            <select class="col-lg-12 col-md-12 col-sm-12 form-control" name="type_id">
              @foreach ($types as $type)
                <option value="{{$type->id}}" >{{$type->name}}</option>
              @endforeach
            </select>
          </div>

          <div class="clearfix"></div>

          {!! Form::label('position_id', '选择位置', array('class'=>'col-md-1')) !!}
          <div class="col-md-2" style="margin-bottom: 55px">
            <select class="col-lg-12 col-md-12 col-sm-12 form-control" name="position_id">
              @foreach ($positions as $position)
                <option value="{{$position->id}}">{{$position->name}}</option>
              @endforeach
          </select>
            </div>
          <div class="clearfix"></div>

          {!! Form::label('order', '显示顺序', array('class'=>'col-md-1')) !!}
          <div class="col-md-2" style="margin-bottom: 55px">
            <select class="col-lg-12 col-md-12 col-sm-12 form-control" name="order">
              @for($i=1; $i<=30; $i++)
                <option value="{{ $i }}">{{ $i }}</option>i
              @endfor
            </select>
          </div>
          <div class="clearfix"></div>

          {{--@if ($errors->has('title'))--}}
            {{--<span class="help-block">--}}
                {{--<strong>--}}
                  {{--@foreach ($errors->all() as $error)--}}
                    {{--<li>{{ $error }}</li>--}}
                  {{--@endforeach--}}
                {{--</strong>--}}
            {{--</span>--}}
          {{--@endif--}}
          {!! Form::label('title', '标题*', array('class'=>'col-md-1')) !!}
          <div class="col-md-11" style="margin-bottom: 55px">
            {!! Form::text('title', '', array('class' => 'input col-md-12 form-control', 'placeholder' => '必填,限35个字')) !!}
          </div>
          <div class="clearfix"></div>

          {!! Form::label('description', '广告描述', array('class'=>'col-md-1')) !!}
          <div class="col-md-11" style="margin-bottom: 55px">
            <textarea name="description" class="description input col-md-12 form-control" placeholder="详细信息" rows="3" ></textarea>
          </div>
          <div class="clearfix"></div>

            {{--@if ($errors->has('links'))--}}
                {{--<span class="help-block">--}}
                {{--<strong>{{ $errors->first('links') ? '链接不能为空' : '' }}</strong>--}}
            {{--</span>--}}
            {{--@endif--}}
          {!! Form::label('links', '链接*', array('class'=>'col-md-1')) !!}
          <div class="col-md-11" style="margin-bottom: 55px">
            {!! Form::text('links', '', array('class' => 'input col-md-12 form-control', 'placeholder' => '必填')) !!}
          </div>
          <div class="clearfix"></div>

          {{--{!! Form::label('published_at', '开始显示日期', array('class'=>'col-md-12')) !!}--}}
          {!! Form::date('published_at', '', array('class'=>'col-md-12', 'hidden', 'placehold'=>'开始日期')) !!}
          <div class="clearfix"></div>


          @if( Auth::user()->hasAnyRole(['adv_editor']) )
            <div>
              <label class="col-md-3 published_label" for="status">
                <input class="status" type="checkbox" name="status"  /> 提交审查
              </label>
            </div>
          @endif

          <div class="clearfix"></div>

          {{--<div>--}}
            {{--<div id="settop_error" class="alert-danger"></div>--}}
            {{--<div class="clearfix"></div>--}}
            {{--<label class="col-md-3 published_label" for="top">--}}
              {{--<input id="settop" class="top" type="checkbox" name="top"  /> 置顶--}}
            {{--</label>--}}
          {{--</div>--}}

          <div class="clearfix"></div>

          {{--{!! Form::label('images', '上传图片*', array('class'=>'col-md-2')) !!}--}}
          {{--@if ($errors->has('images'))--}}
            {{--<span class="help-block">--}}
                {{--<strong>{{ $errors->first('images') ? '图片不能为空' : '' }}</strong>--}}
            {{--</span>--}}
          {{--@endif--}}
          {{--<div class="col-md-4" style="margin-bottom: 55px">--}}
              {{--<input type="file" class="col-md-12 form-control-file form-control" id="images" name="images">--}}
{{--            {!! Form::file('images', '', array('class'=>'col-md-12 form-control-file form-control', 'id'=>'files', 'required'=>'required')) !!}--}}
            {{--<img id="image" width="100" />--}}
          {{--</div>--}}
          {{--<div class="clearfix"></div>--}}

          {!! Form::token() !!}
          <br>
          <div class=" col-lg-12 col-md-12 col-sm-12">
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

<script>
  document.getElementById("images").onchange = function () {
    var reader = new FileReader();

    reader.onload = function (e) {
      // get loaded data and render thumbnail.
      document.getElementById("image").src = e.target.result;
    };

    // read the image file as a data URL.
    reader.readAsDataURL(this.files[0]);
  };
</script>

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
@endsection