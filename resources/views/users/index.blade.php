@extends('layouts.base')
@include('layouts.settingSideBar')
<link rel="stylesheet" href="{{ asset("/src/css/select2.min.css") }}" />
@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">用户</h1>

                <select class="js-example-basic-single" name="role" id="select_role">
                    <option value="0">用户权限查询</option>
                    @foreach ($usergroups as $role)
                        <option value="{{ $role->id }}" > {{ $role->description }}</option>
                    @endforeach
                </select>
                <button id="search_role" value="查找" class="btn btn-default">查找</button>

                @if (Auth::user()->hasAnyRole(['super_admin', 'admin']))
                    <div class="col-lg-2 col-md-2 col-sm-2 pull-right clearfix">
                        {{ link_to('admin/user/create', '添加', ['class'=>'btn btn-default']) }}
                    </div>
                @endif

                {{--flash alert--}}
                @if ($success = Session::get('status'))
                    <div class="col-lg-12 col-md-12 col-sm-12 bs-example-bg-classes" >
                        <p class="bg-success">
                            {{ $success }}
                        </p>
                    </div>
                @endif
<div id="search_result">
                @if(count($users)>0)
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>用户名</th>
                            <th>电子邮件</th>
                            <th>权限</th>
                            <th>编辑</th>
                            <th>屏蔽</th>
                            <th>删除</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            @if($user->hasRole('super_admin') && !Auth::user()->hasRole('super_admin'))
                            @else
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td><span id="email_{{ $user->id }}">{{ $user->email }}</span></td>
                                    <td>
                                        @foreach($user->roles as $ur)
                                            {{ $ur->description }}
                                        @endforeach
                                    </td>
                                    <td><a class="btn btn-default" href="/admin/user/{{ $user->id }}/edit" id="editBtn_{{ $user->id }}">编辑</a></td>

                                    <td>
                                        @if ($user->banned)
                                            {!! Form::open(array('url' => 'admin/user/'.$user->id.'/active', 'class' => 'form', 'method'=>'put', 'onsubmit'=>'return confirm("确定屏蔽这个账号?");')) !!}
                                            {!! Form::text('id', $user->id, array('hidden'=>'hidden', 'readonly' => true)) !!}
                                            {!! Form::submit('恢复', array('class'=>'btn btn-primary')) !!}
                                        @else
                                            {!! Form::open(array('url' => 'admin/user/'.$user->id.'/banned', 'class' => 'form', 'method'=>'put', 'onsubmit'=>'return confirm("确定屏蔽这个账号?");')) !!}
                                            {!! Form::text('id', $user->id, array('hidden'=>'hidden', 'readonly' => true)) !!}
                                            {!! Form::submit('屏蔽', array('class'=>'btn btn-warning')) !!}
                                        @endif
                                        {!! Form::token() !!}
                                        {!! Form::close() !!}
                                    </td>
                                    <td>
                                        {!! Form::open(array('url' => 'admin/user/'.$user->id, 'class' => 'form', 'method'=>'delete', 'onsubmit'=>'return confirm("确定删除这个账号?");')) !!}
                                        {!! Form::text('id', $user->id, array('hidden'=>'hidden', 'readonly' => true)) !!}
                                        {!! Form::submit('删除', array('class'=>'btn btn-danger')) !!}
                                        {!! Form::token() !!}
                                        {!! Form::close() !!}
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                @endif

                    @if ( Null !== Auth::user()->hasRole('super_admin', 'admin') )
                        <div class="col-lg-12 col-md-12 col-sm-12 clearfix">
                            {!! $users->links() !!}
                        </div>
                    @endif
            </div>
                </div>
        </div>
    </div>


    <script src="{{ url('/src/js/jQuery.min.2.2.4.js') }}" ></script>
    <script src="{{ url('/src/js/select2/select2.full.min.js') }}" ></script>
    <script type="text/javascript">
        $('.js-example-basic-single').select2();
        $(document).ready(function(){
            $('#search_role').click(function(){
                var searchdata = $('#select_role').val();
                console.log($.get('/admin/user/listAutheditor/'+searchdata));
                $('#search_result').load('/admin/user/listAutheditor/'+searchdata);
            });
        });
    </script>
@endsection