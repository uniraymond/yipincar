@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>图片</th>
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
                                    <th scope="row"></th>
                                    <td>{{ $image->name }}</td>
                                    <td><input type="checkbox" name="published_{{ $image->id }}" value="{{ $image->published }}" /></td>
                                    <td><input type="text" name="order_{{ $image->id }}" value="{{ $image->order }}" /></td>
                                    <td><input type="checkbox" name="delete" /></td>
                                    <td><a class="btn btn-default">编辑</a></td>
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
                        {!! Form::open(array('url' => 'admin/advsetting/update', 'class' => 'form')) !!}
                            {!! Form::label('name', 'Name', array('class'=>'col-md-12')) !!}
                            {!! Form::text('Name', '', array('class'=>'name input col-md-12', 'placeholder' => 'Name')) !!}
                            {!! Form::label('description', 'Description', array('class'=>'col-md-12')) !!}
                            {!! Form::text('Description', '', array('class' => 'description input col-md-12', 'placeholder' => 'Description')) !!}
                            {!! Form::submit('确定', array('class'=>'btn btn-primary')) !!}
                        {!! Form::token() !!}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>

            <div class="col-md-7 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">上传图片</div>
                    <div class="panel-body">
                        {!! Form::open(array('url' => 'admin/advsetting/upload', 'class' => 'form')) !!}
                            {!! Form::label('name', 'Name', array('class'=>'col-md-12')) !!}
                            {!! Form::text('name', '', array('class'=>'name input col-md-12', 'placeholder' => 'Name')) !!}
                            {!! Form::label('description', 'Description', array('class'=>'col-md-12')) !!}
                            {!! Form::text('description', '', array('class' => 'description input col-md-12', 'placeholder' => 'Description')) !!}
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