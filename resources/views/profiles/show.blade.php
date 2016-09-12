@extends('layouts.base')

@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">个人资料</h1>

                @if ($fail = Session::get('warning'))
                    <div class="col-md-12 bs-example-bg-classes" >
                        <p class="bg-danger">
                            {{ $fail }}
                        </p>
                    </div>
                @endif

                <div class="panel-body">
                    <div class="form-group  col-lg-12 col-md-12 col-sm-12" >
                        <div>
                            <div class="col-md-6">
                                姓名：
                            </div>
                            <div class="col-md-6">
                                {{ $profile->name }}
                            </div>
                        </div>
                        <div>
                            <div class="col-md-6">
                                性别：
                            </div>
                            <div class="col-md-6">
                                {{ $profile->gender }}
                            </div>
                        </div>

                        <div>
                            <div class="col-md-6">
                                生日：
                            </div>
                            <div class="col-md-6">
                                {{ $profile->dob }}
                            </div>
                        </div>

                        <div>
                            <div class="col-md-6">
                               电话 ：
                            </div>
                            <div class="col-md-6">
                                {{ $profile->phone }}
                            </div>
                        </div>

                        <div>
                            <div class="col-md-6">
                                手机：
                            </div>
                            <div class="col-md-6">
                                {{ $profile->cellphone }}
                            </div>
                        </div>

                        <div>
                            <div class="col-md-6">
                                自我介绍：
                            </div>
                            <div class="col-md-6">
                                {{ $profile->address }}
                            </div>
                        </div>
                </div>
                </div>
            </div>
        </div>
    </div>
@endsection