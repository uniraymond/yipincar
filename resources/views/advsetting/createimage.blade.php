@extends('layouts.base')

@include('layouts.contentSideBar')
{{--@include('advsetting.sidebarType',['types'=>$types])--}}
@section('content')
  <div id="page-wrapper">
    <div class="row">
      <div class="col-lg-12">
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
            @foreach ($errors->all() as $error)
              <div class="col-md-12 bs-example-bg-classes" >
                <p class="bg-danger">
                    需要上传图片
                    {{--{{ $error }}--}}
                </p>
              </div>
            @endforeach
          @endif
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12">
          {!! Form::open(array('url' => 'admin/advsetting/uploadimage', 'class' => 'form', 'enctype'=>'multipart/form-data', 'method'=>'put')) !!}

          {!! Form::label('type_id', '选择类型', array('class'=>'col-md-12')) !!}
          <select class="col-lg-12 col-md-12 col-sm-12 form-control" name="type_id">
            @foreach ($types as $type)
              <option value="{{$type->id}}" @if ($type->name == '视频') disabled @endif >{{$type->name}}</option>
            @endforeach
          </select>
          <div class="clearfix"></div>

          {!! Form::label('position_id', '选择位置', array('class'=>'col-md-12')) !!}
          <select class="col-lg-12 col-md-12 col-sm-12 form-control" name="position_id">
            @foreach ($positions as $position)
              <option value="{{$position->id}}">{{$position->name}}</option>
            @endforeach
          </select>
          <div class="clearfix"></div>

          {!! Form::label('title', '内容', array('class'=>'col-md-12')) !!}
          {!! Form::text('title', '', array('class' => 'input col-md-12 form-control', 'placeholder' => '内容')) !!}
          <div class="clearfix"></div>

          {!! Form::label('description', '广告描述', array('class'=>'col-md-12')) !!}
          <textarea name="description" class="description input col-md-12 form-control" placeholder="详细信息" rows="3" ></textarea>
          <div class="clearfix"></div>

          {!! Form::label('order', '显示顺序', array('class'=>'col-md-12')) !!}
          <select class="col-lg-12 col-md-12 col-sm-12 form-control" name="order">
            @for($i=1; $i<=6; $i++)
              <option value="{{ $i }}">{{ $i }}</option>i
            @endfor
          </select>
          <div class="clearfix"></div>

          {!! Form::label('links', '链接', array('class'=>'col-md-12')) !!}
          {!! Form::text('links', '', array('class' => 'input col-md-12 form-control', 'placeholder' => '链接')) !!}
          <div class="clearfix"></div>

          {!! Form::label('published_at', '开始显示日期', array('class'=>'col-md-12')) !!}
          {!! Form::date('published_at', '', array('class'=>'col-md-12', 'placehold'=>'开始日期')) !!}
          <div class="clearfix"></div>

          {!! Form::label('category_id', '栏目', array('class'=>'col-md-12')) !!}
          <select class="col-lg-12 col-md-12 col-sm-12 form-control" name="category_id">
            @foreach ($categories as $category)
              <option value="{{$category->id}}">{{$category->name}}</option>
            @endforeach
          </select>
          <div class="clearfix"></div>

          {!! Form::label('images', '上传图片*', array('class'=>'col-md-12')) !!}
          @if ($errors->has('images'))
            <span class="help-block">
                <strong>{{ $errors->first('images') ? '图片不能为空' : '' }}</strong>
            </span>
          @endif
          {!! Form::file('images', '', array('class'=>'col-md-12 form-control-file form-control', 'id'=>'files', 'required'=>'required')) !!}
          <img id="image" width="100" />
          <div class="clearfix"></div>

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
@endsection