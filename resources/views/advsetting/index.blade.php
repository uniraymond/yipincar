{{--@extends('layouts.base')--}}
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <h1>广告设置</h1>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2 pull-right clearfix">
                <a href="{{ url('admin/advsetting/createimage') }}" class="dropdown-toggle btn btn-secondary collapsed" data-toggle="#add_adv_images" role="button" aria-expanded="false" id="add_new_adv_bt">
                    新建
                </a>
                {{--<a href="javascript:;" data-toggle="collapse" data-target="#add_adv_images" class="@if($currentAction != 'category') collapsed @endif" aria-expanded="@if($currentAction == 'category') true @else false @endif">--}}
                    {{--新建 --}}
                {{--</a>--}}
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>标题</th>
                        <th>描述</th>
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
                                    <td><input type="text" name="order[{{ $image->id }}]" value="{{ $image->order }}" /></td>
                                    <td><input type="checkbox" name="delete[{{ $image->id }}]" ng-click="confirmDelete('{{ $image->id }}')"/></td>
                                    <td>
                                        <a href="{{ url('admin/advsetting/editimage/'.$image->id) }} ">编辑</a>
                                     </td>
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
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 clearfix">
            {!! $images->links() !!}
        </div>
        @include('advsetting.sidebarType',['types'=>$types])
    </div>
@endsection
