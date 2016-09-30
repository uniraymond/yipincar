@extends('layouts.base')
@include('layouts.mineSideBar')
@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">填写入驻资料</h1>

                @if ($fail = Session::get('warning'))
                    <div class="col-md-12 bs-example-bg-classes" >
                        <p class="bg-danger">
                            {{ $fail }}
                        </p>
                    </div>
                @endif

                <div class="panel-body">
                    {!! Form::open(array('url' => 'admin/profile', 'class' => 'form', 'method'=>'post')) !!}
                    <div class="form-group  col-lg-12 col-md-12 col-sm-12" >
                        <div>
                            {!! Form::label('mediatype', '自媒体类型', array('class'=>'col-md-6')) !!}
                            <div class="col-md-12">
                                <label for="mediatype">
                                    <input type="radio" value="1" name="mediatype"/>个体自媒体
                                </label>
                                <label for="mediatype">
                                    <input type="radio" value="2" name="mediatype"/>组织机构自媒体
                                </label>
                            </div>
                        </div>

                        <div>
                            {!! Form::label('name', '运营者姓名', array('class'=>'col-md-6')) !!}
                            <div class="col-md-12">
                                {!! Form::text('name', '', array('class'=>'col-md-6 form-control', 'placeholder' => '姓名')) !!}
                            </div>
                        </div>

                        <div>
                            <label for="provid_id" >
                                <select name="provid_id" >
                                    <option value="sfz" >身份证</option>
                                    <option value="passport" >护照</option>
                                </select>
                                <input placeholder="证件号码" />
                            </label>
                        </div>

                        <div>
                            {!! Form::label('images', '证件照片*', array('class'=>'col-md-12')) !!}
                            @if ($errors->has('images'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('images') ? '图片不能为空' : '' }}</strong>
                                </span>
                            @endif //TODO
                            {!! Form::file('images', '', array('class'=>'col-md-12 form-control-file form-control', 'id'=>'files', 'required'=>'required')) !!}
                            <img id="image" width="100" />
                            <div class="clearfix"></div>
                        </div>
                        <div>
                            {!! Form::label('dob', '所在地', array('class'=>'col-md-6')) !!}
                            <div class="col-md-12">
                                <select>
                                    <option value="1"></option>
                                    <option value="2"></option>
                                </select>
                            </div>
                        </div>
                        <div>
                            {!! Form::label('mailbox', '联系邮箱', array('class'=>'col-md-6')) !!}
                            <div class="col-md-12">
                                {!! Form::textarea('mailbox', '', array('class'=>'col-md-6 form-control', 'height'=>"20", 'placeholder' => '联系邮箱')) !!}
                            </div>
                        </div>
                        <div>
                            {!! Form::label('contactphone', '联系手机', array('class'=>'col-md-6')) !!}
                            <div class="col-md-12">
                                {!! Form::text('phone', '', array('class'=>'col-md-6 form-control', 'placeholder' => '电话')) !!}
                            </div>
                        </div>

                        <div>
                            {!! Form::label('', '个人授权书*', array('class'=>'col-md-12')) !!}
                            @if ($errors->has('images'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('images') ? '图片不能为空' : '' }}</strong>
                                </span>
                            @endif //TODO
                            {!! Form::file('images', '', array('class'=>'col-md-12 form-control-file form-control', 'id'=>'files', 'required'=>'required')) !!}
                            <img id="image" width="100" />
                            <div class="clearfix"></div>
                        </div>



                        <div>
                            {!! Form::label('gender', '性别', array('class'=>'col-md-6')) !!}
                            <div class="col-md-12">
                                {!! Form::select('gender',['male'=>'Male','female'=>'Female']) !!}
                            </div>
                        </div>
                        <div>
                            {!! Form::label('cellphone', '手机', array('class'=>'col-md-6')) !!}
                            <div class="col-md-12">
                                {!! Form::text('cellphone', '', array('class'=>'col-md-6 form-control', 'placeholder' => '手机')) !!}
                            </div>
                        </div>

                        {!! Form::text('user_id', $user->id, array('hidden'=>'hidden')) !!}
                        {!! Form::token() !!}
                        <div class=" col-lg-12 col-md-12 col-sm-12">
                            {!! Form::submit('保存', array('class'=>'btn btn-primary')) !!}
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection