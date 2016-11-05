@extends('layouts.base')
@include('layouts.userSideBar')
<link rel="stylesheet" href="{{ asset("/src/css/select2.min.css") }}" />
@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12" style="margin-top: 15px">
                <h1 class="page-header">入驻编辑列表</h1>

                {{--<select class="js-example-basic-single" name="role" id="select_role">--}}
                    {{--<option value="0">用户权限查询</option>--}}
                    {{--@foreach ($usergroups as $role)--}}
                        {{--@if ($role->name != 'super_admin')--}}
                            {{--<option value="{{ $role->id }}" > {{ $role->description }}</option>--}}
                        {{--@endif--}}
                    {{--@endforeach--}}
                {{--</select>--}}
                {{--<button id="search_role" value="查找" class="btn btn-default">查找</button>--}}

                @if (Auth::user()->hasAnyRole(['super_admin', 'admin']))
                    {{--<div class="col-lg-2 col-md-2 col-sm-2 pull-right clearfix">--}}
                        {{--{{ link_to('admin/user/create', '添加', ['class'=>'btn btn-default']) }}--}}
                    {{--</div>--}}
                @endif

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
                                <th>电话</th>
                                <th>权限</th>
                                <th>审核</th>
                                <th>屏蔽</th>
                                <th>删除</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $user)
                                {{--@if(Auth::user()->hasAnyRole(['super_admin', 'admin', 'chef_editor', 'main_editor', 'adv_editor'])||$user->id == 1 || $user->id == 2)--}}
{{--                                @if(!Auth::user()->hasAnyRole('super_admin', 'admin')||$user->id == 1 || $user->id == 2)--}}
                                {{--@if(Auth::user()->hasAnyRole('super_admin')||$user->id == 1 || $user->id == 2)--}}
                                {{--@else--}}
                                    @if($user->id == 1 || $user->id == 2)
                                    @elseif(Auth::user()->hasAnyRole(['super_admin', 'admin', 'chef_editor', 'main_editor', 'adv_editor']))
                                    <tr>
                                        <td>
                                            <a class="" href="{{ url('admin/authprofile/'.$user->user_id.'/view') }}" id="editBtn_{{ $user->id }}">
                                                {{ $user->name }}
                                            </a>
                                        </td>
                                        <td><span id="phone_{{ $user->id }}">{{ $user->phone }}</span></td>
                                        <td>入驻编辑
                                            {{--@foreach($user->roles as $ur)--}}
                                                {{--{{ $ur->description }}--}}
                                            {{--@endforeach--}}
                                        </td>
                                        <td>
                                            @if($user->status_id < 3)
                                                <button type="button" value="" class="btn btn_default" id="btn_{{ $user->user_id }}" onclick="passValidate({{ $user->user_id }})" >审核</button>
                                            @else
                                                <button type="button" value="" class="btn btn_default" id="btn_{{ $user->user_id }}" onclick="stopValidate({{ $user->user_id }})" >通过</button>
                                            @endif
                                        <td>
                                            @if ($user->banned)
                                                {!! Form::open(array('url' => 'admin/user/'.$user->user_id.'/authactive', 'class' => 'form', 'method'=>'put', 'onsubmit'=>'return confirm("确定屏蔽这个账号?");')) !!}
                                                {!! Form::text('id', $user->user_id, array('hidden'=>'hidden', 'readonly' => true)) !!}
                                                {!! Form::submit('恢复', array('class'=>'btn btn-primary')) !!}
                                            @else
                                                {!! Form::open(array('url' => 'admin/user/'.$user->user_id.'/authbanned', 'class' => 'form', 'method'=>'put', 'onsubmit'=>'return confirm("确定屏蔽这个账号?");')) !!}
                                                {!! Form::text('id', $user->user_id, array('hidden'=>'hidden', 'readonly' => true)) !!}
                                                {!! Form::submit('屏蔽', array('class'=>'btn btn-warning')) !!}
                                            @endif
                                            {!! Form::token() !!}
                                            {!! Form::close() !!}
                                        </td>
                                        <td>
                                            {!! Form::open(array('url' => 'admin/user/'.$user->user_id, 'class' => 'form', 'method'=>'delete', 'onsubmit'=>'return confirm("确定删除这个账号?");')) !!}
                                            {!! Form::text('id', $user->user_id, array('hidden'=>'hidden', 'readonly' => true)) !!}
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
                    @if ( Auth::user()->hasAnyRole(['super_admin', 'admin', 'chef_editor', 'main_editor', 'adv_editor']) )
{{--                    @if ( Auth::user()->hasRole('super_admin', 'admin') )--}}
                        <div class="col-lg-12 col-md-12 col-sm-12 clearfix">
                            <span class="totalpage pagination">共有入驻编辑：{{ ($totalUsers) }}</span>   {!! $users->links() !!}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>


    <script src="{{ url('/src/js/jQuery.min.2.2.4.js') }}" ></script>
    <script src="{{ url('/src/js/jquery-ui.min.js') }}" ></script>

    <script src="{{ url('/src/js/select2/select2.full.min.js') }}" ></script>
    <script type="text/javascript">
        jQuery(function(){
        });

        function passValidate(user_id) {
            var getUrl = "{{ url('admin/varifyStatus') }}" + '/' + user_id;
                jQuery.get(getUrl,function(data){
                    console.log('changed');
                    jQuery('#btn_'+user_id).html('通过');
                    location.reload();
                });
        }
        function stopValidate(user_id) {
            var reason = prompt("拒绝申请的原因：", "");
            if(reason != null) {
                var getUrl = "{{ url('admin/devarifyStatus') }}" + '/' + user_id+'?reason='+reason;
                    jQuery.get(getUrl,function(data){
                    console.log(reason);
                    jQuery('#btn_'+user_id).html('审核');
                    location.reload();
                })
            }
        }
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