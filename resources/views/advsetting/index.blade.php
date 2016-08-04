@extends('layouts.app')

@section('content')
    <div class="container" ng-app="resourceApp">
        <div class="row" ng-controller="resourceController">
            <table class="table">
                <thead>
                    <tr>
                        <th>图片</th>
                        <th>Details</th>
                        <th>公开</th>
                        <th>顺序</th>
                        <th>删除</th>
                        <th>编辑</th>
                    </tr>
                </thead>
                @if($images)
                    {!! Form::open(array('url' => 'admin/advsetting/update', 'class'=>'form')) !!}
                        <tbody>
                            @foreach($images as $image)
                                <tr>
                                    <td>{{ $image->name }}</td>
                                    <td><span id="imageDesc_{{ $image->id }}">{{ $image->description }}</span></td>
                                    <td><input type="checkbox" name="published_{{ $image->id }}" {{ $image->published ? 'checked' : '' }}/></td>
                                    <td><input type="text" name="order_{{ $image->id }}" value="{{ $image->order }}" /></td>
                                    <td><input type="checkbox" name="delete_{{ $image->id }}" ng-checked="delete_{{$image->id}}" ng-click="confirmDelete('{{ $image->id }}')"/></td>
                                    <td><a class="btn btn-default" ng-click="loadImageDescription('{{ $image->id }}', '{{ $image->description }}')" id="editBtn_{{ $image->id }}">编辑</a></td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="5"> </td>
                                <td><input class="btn btn-primary" type="submit" value="submit" /></td>
                            </tr>
                        </tbody>
                        {!! Form::token() !!}
                    {!! Form::close() !!}
                @endif
            </table>
            <div class="col-md-7 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">更改图片详情</div>
                    <div class="panel-body">
                        <div>
                            {{ $success = Session::get('status')}}
                            @if (count($errors) > 0)
                                @foreach ($errors->all() as $error)
                                    {{ $error }}
                                @endforeach
                            @endif
                        </div>
                        {{--<form ng-submit="updateImageDescription()" class="form" method="POST">--}}
                        {!! Form::open(array('url' => '/api/updateImage', 'class' => 'form', )) !!}
                            {!! Form::label('description', 'Description', array('class'=>'col-md-12')) !!}
                            <input type="text" name="id" hidden="hidden"  ng-model="imageId" />
                            <input type="text" name="description" class="description input col-md-12" placeholder="Description" ng-model="imageDescription" />
                            {!! Form::submit('确定', array('class'=>'btn btn-primary')) !!}
                            {!! Form::token() !!}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>

            <div class="col-md-12 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">上传图片</div>
                    <div class="panel-body">
                        <div>
                            {{ $success = Session::get('filestatus')}}
                            @if (count($errors) > 0)
                                @foreach ($errors->all() as $error)
                                    {{ $error }}
                                @endforeach
                            @endif
                        </div>
                        {!! Form::open(array('url' => '/api/uploadImage', 'class' => 'form', 'enctype'=>'multipart/form-data')) !!}
                            {!! Form::label('name', 'Name', array('class'=>'col-md-12')) !!}
                            {!! Form::text('name', '', array('class'=>'name input col-md-12', 'placeholder' => 'Name')) !!}
                            {!! Form::label('description', 'Description', array('class'=>'col-md-12')) !!}
                            {!! Form::textarea('description', '', array('class' => 'description input col-md-12', 'placeholder' => 'Description')) !!}
                            {!! Form::label('images', 'Upload Images', array('class'=>'col-md-12')) !!}
                            {!! Form::file('image', '', array('class'=>'col-md-12 form-control-file')) !!}
                            {!! Form::token() !!}
                            {!! Form::text('type_id', 1, array('hidden'=>'hidden')) !!}
                            {!! Form::submit('保存', array('class'=>'btn btn-primary')) !!}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection