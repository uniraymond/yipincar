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
                    {!! Form::open(array('url' => 'authprofile/store', 'class' => 'form', 'method' => 'put', 'enctype' => 'multipart/form-data')) !!}
                    <div class="form-group  col-lg-12 col-md-12 col-sm-12" >
                        <div class="clearfix">
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

                        <div class="clearfix">
                            {!! Form::label('name', '运营者姓名', array('class'=>'col-md-6')) !!}
                            <div class="col-md-12">
                                {!! Form::text('name', '', array('class'=>'col-md-6 form-control', 'placeholder' => '姓名')) !!}
                            </div>
                        </div>

                        <div class="clearfix">
                            <label for="prove_type" >运营者证件
                                <select name="prove_type" >
                                    <option value="sfz" >身份证</option>
                                    <option value="passport" >护照</option>
                                </select>
                                <input name="prove_number" placeholder="证件号码" />
                            </label>
                        </div>

                        <div class="clearfix">
                            {!! Form::label('proveimage', '证件照片', array('class'=>'col-md-12')) !!}
                            @if ($errors->has('proveimage'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('proveimage') ? '图片不能为空' : '' }}</strong>
                                </span>
                            @endif
                            {!! Form::file('proveimage', '', array('class'=>'col-md-12 form-control-file form-control', 'id'=>'proveimage', 'required'=>'required')) !!}
                            <img id="proveimage_image" width="100" />
                            <div class="clearfix"></div>
                        </div>

                        <div class="clearfix">
                            {!! Form::label('city_id', '所在地', array('class'=>'col-md-6')) !!}
                            <div class="col-md-12">
                                <select name="city_id">
                                    <option value="0">中国</option>
                                    <option value="1">北京</option>
                                    <option value="2">上海</option>
                                    <option value="3">广州</option>
                                    <option value="4">深圳</option>
                                </select>
                            </div>
                        </div>

                        <div class="clearfix">
                            {!! Form::label('mailbox', '联系邮箱', array('class'=>'col-md-6')) !!}
                            <div class="col-md-12">
                                {!! Form::email('mailbox', '', array('class'=>'col-md-6 form-control', 'height'=>"20", 'placeholder' => '联系邮箱')) !!}
                            </div>
                        </div>

                        <div class="clearfix">
                            {!! Form::label('cellphone', '联系手机', array('class'=>'col-md-6')) !!}
                            <div class="col-md-12">
                                {!! Form::text('cellphone', '', array('class'=>'col-md-6 form-control', 'placeholder' => '电话')) !!}
                            </div>
                        </div>

                        <div class="clearfix">
                            {!! Form::label('auth_resource', '个人授权书', array('class'=>'col-md-12')) !!}
                            @if ($errors->has('auth_resource'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('auth_resource') ? '图片不能为空' : '' }}</strong>
                                </span>
                            @endif
                            {!! Form::file('auth_resource', '', array('class'=>'col-md-12 form-control-file form-control', 'id'=>'auth_resource', 'required'=>'required')) !!}
                            <img id="auth_resource_image" width="100" />
                            <div class="clearfix"></div>
                        </div>

                        <div class="clearfix">
                            {!! Form::label('ass_resource', '组织机构代码证', array('class'=>'col-md-12')) !!}
                            @if ($errors->has('ass_resource'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('ass_resource') ? '图片不能为空' : '' }}</strong>
                                </span>
                            @endif
                            {!! Form::file('ass_resource', '', array('class'=>'col-md-12 form-control-file form-control', 'id'=>'ass_resource', 'required'=>'required')) !!}
                            <img id="ass_resource_image" width="100" />
                            <div class="clearfix"></div>
                        </div>

                        <div class="clearfix">
                            {!! Form::label('contract_auth', '合同授权书', array('class'=>'col-md-12')) !!}
                            @if ($errors->has('contract_auth'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('contract_auth') ? '图片不能为空' : '' }}</strong>
                                </span>
                            @endif
                            {!! Form::file('contract_auth', '', array('class'=>'col-md-12 form-control-file form-control', 'id'=>'contract_auth', 'required'=>'required')) !!}
                            <img id="contract_auth_image" width="100" />
                            <div class="clearfix"></div>
                        </div>

                        <div class="clearfix">
                            {!! Form::label('targetArea', '专注领域', array('class'=>'col-md-6')) !!}
                            <div class="col-md-12">
                                <select>
                                    <option value="1"></option>
                                    <option value="2"></option>
                                </select>
                            </div>
                        </div>

                        <div class="clearfix">
                            {!! Form::label('self_url', '个人网站（可选）', array('class'=>'col-md-6')) !!}
                            <div class="col-md-12">
                                {!! Form::text('self_url', '', array('class'=>'col-md-6 form-control', 'placeholder' => '个人网站')) !!}
                            </div>
                        </div>

                        <div class="clearfix">
                            {!! Form::label('icon_uri', '辅助材料', array('class'=>'col-md-6 clearfix')) !!}
                            <div class="col-md-12">
                                {!! Form::text('icon_uri', '', array('class'=>'col-md-6 form-control', 'placeholder' => '辅助材料')) !!}
                            </div>
                            <div class="clearfix">博客，专栏等地址链接</div>
                        </div>

                        <div class="clearfix">
                            {!! Form::label('weixin_public_id', '微信公众号', array('class'=>'col-md-6')) !!}
                            <div class="col-md-12">
                                {!! Form::text('weixin_public_id', '', array('class'=>'col-md-6 form-control', 'placeholder' => '微信公众号')) !!}
                            </div>
                        </div>

                        <div class="clearfix">
                            {!! Form::label('media_name', '自媒体名称', array('class'=>'col-md-6')) !!}
                            <div class="col-md-12">
                                {!! Form::text('', '自媒体名称', array('class'=>'col-md-6 form-control', 'placeholder' => '自媒体名称')) !!}
                            </div>
                        </div>

                        <div class="clearfix">
                            {!! Form::label('media_icon', '自媒体头像', array('class'=>'col-md-12')) !!}
                            @if ($errors->has('media_icon'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('media_icon') ? '自媒体头像' : '' }}</strong>
                                </span>
                            @endif
                            {!! Form::file('media_icon', '', array('class'=>'col-md-12 form-control-file form-control', 'id'=>'media_icon', 'required'=>'required')) !!}
                            <img id="media_icon_image" width="100" />
                            <div class="media_icon"></div>
                        </div>

                        <div class="clearfix">
                            {!! Form::label('自媒体简介', '', array('class'=>'col-md-12 clearfix')) !!}
                            <div class="col-md-12">
                                {!! Form::text('about_self', '', array('class'=>'col-md-12 form-control', 'placeholder' => '自媒体简介')) !!}
                            </div>
                            <div class="clearfix">2到12字，要求一句话介绍您的自媒体，无特殊符号，请勿添加联系方式</div>
                        </div>

                        <div class="form-group{{ $errors->has('confirmterm') ? ' has-error' : '' }} clearfix">
                            <label for="confirmterm" class="col-md-4 control-label"></label>

                            <div class="col-md-6">
                                <label for="confirmterm">
                                    <input id="confirmterm" type="checkbox" name="confirmterm" />
                                    同意<a href="{{ url('termandconditions') }}" ><<一品汽车自媒体平台使用协议>></a>
                                </label>

                                @if ($errors->has('confirmterm'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('confirmterm') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

{{--                        {!! Form::text('user_id', $user->id, array('hidden'=>'hidden')) !!}--}}
                        {!! Form::token() !!}
                        <div class=" col-lg-12 col-md-12 col-sm-12 clearfix">
                            {!! Form::submit('保存', array('class'=>'btn btn-primary')) !!}
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById("proveimage").onchange = function () {
            var reader = new FileReader();

            reader.onload = function (e) {
                // get loaded data and render thumbnail.
                document.getElementById("proveimage_image").src = e.target.result;
            };

            // read the image file as a data URL.
            reader.readAsDataURL(this.files[0]);
        };

        document.getElementById("auth_resource").onchange = function () {
            var reader = new FileReader();

            reader.onload = function (e) {
                // get loaded data and render thumbnail.
                document.getElementById("auth_resource_image").src = e.target.result;
            };

            // read the image file as a data URL.
            reader.readAsDataURL(this.files[0]);
        };

        document.getElementById("ass_resource").onchange = function () {
            var reader = new FileReader();

            reader.onload = function (e) {
                // get loaded data and render thumbnail.
                document.getElementById("ass_resource_image").src = e.target.result;
            };

            // read the image file as a data URL.
            reader.readAsDataURL(this.files[0]);
        };

        document.getElementById("contract_auth").onchange = function () {
            var reader = new FileReader();

            reader.onload = function (e) {
                // get loaded data and render thumbnail.
                document.getElementById("contract_auth_image").src = e.target.result;
            };

            // read the image file as a data URL.
            reader.readAsDataURL(this.files[0]);
        };

        document.getElementById("media_icon").onchange = function () {
            var reader = new FileReader();

            reader.onload = function (e) {
                // get loaded data and render thumbnail.
                document.getElementById("media_icon_image").src = e.target.result;
            };

            // read the image file as a data URL.
            reader.readAsDataURL(this.files[0]);
        };
    </script>
@endsection