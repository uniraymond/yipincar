@extends('layouts.base')
@include('advsetting.sidebarType',['types'=>$types])
@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">广告设置</h1>

                {{--new blog link--}}
                <div class="col-lg-2 col-md-2 col-sm-2 pull-right clearfix">
                    {{ link_to('admin/advsetting/createimage', '新建', ['class'=>'btn btn-default']) }}
                </div>

                {{--flash alert--}}
                @if ($success = Session::get('status'))
                    <div class="col-lg-12 col-md-12 col-sm-12 bs-example-bg-classes" >
                        <p class="bg-success">
                            {{ $success }}
                        </p>
                    </div>
                @endif

                @if(count($images)>0)
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
                        {!! Form::open(array('url' => 'admin/advsetting/update', 'class'=>'form')) !!}
                            <tbody>
                                @foreach($images as $image)
                                    <tr>
                                        <td>{{ $image->name }}</td>
                                        <td><span id="imageDesc_{{ $image->id }}">{{ $image->description }}</span></td>
                                        <td><input type="text" name="order[{{ $image->id }}]" value="{{ $image->order }}" /></td>
                                        <td><input type="checkbox" name="delete[{{ $image->id }}]" ng-click="confirmDelete('{{ $image->id }}')"/></td>
                                        <td>
                                            <a href="{{ url('admin/advsetting/editimage/'.$image->id) }}" class="btn btn-default">编辑</a>
                                         </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="4"> </td>
                                    <td><input class="btn btn-primary" type="submit" value="提交" /></td>
                                </tr>
                            </tbody>
                            {!! Form::token() !!}
                        {!! Form::close() !!}
                    </table>
                @endif
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 clearfix">
                {!! $images->links() !!}
            </div>
        </div>
    </div>
@endsection
