@extends('layouts.base')
{{--@include('users.side',['usergroups'=>$usergroups])--}}
@include('layouts.settingSideBar')
@foreach($user->userrole as $ur)
    @php $userIds[] = $ur->role_id; @endphp
@endforeach
@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12" style="margin-top: 35px">
                <h1 class="page-header">用户详情</h1>

                @if ($success = Session::get('status'))
                    <div class="col-lg-12 col-md-12 col-sm-12 bs-example-bg-classes" >
                        <p class="bg-success">
                            {{ $success }}
                        </p>
                    </div>
                @endif

                <div class="panel panel-default">
                    <div class="panel-heading heading_block_title col-lg-12 col-md-12">
                        <h3 class=""panel-title">{{ $user->name }}</h3>
                    </div>
                    {{--<div id="heading_block" class="panel-heading col-lg-3 col-md-4">--}}
                        {{--<div>--}}
                            {{--<small><span>作者: </span>{{ $article->created_by ? $article->user_created_by->name : '无名' }}</small>--}}
                        {{--</div>--}}
                        {{--<div>--}}
                            {{--<small><span>完成日期: </span>{{ date('Y m d, H:s', strtotime($article->created_date)) }}</small>--}}
                        {{--</div>--}}
                        {{--<div>--}}
                            {{--<small><span>文章状态: </span>{{ count($article->article_status)>0 ? $article->article_status->title : '草稿' }}</small>--}}
                        {{--</div>--}}
                    {{--</div>--}}

                    <div class="panel-body">
                        <div class="form-group col-lg-12 col-md-12 col-sm-12" >
                            <div>电子邮件:</div>
                            <div> <p>{{ $user->email }}</p> </div>
                            <div class="clearfix"></div>
                            <div>作者一共有{{count( $articles )}}篇文章</div>
                        </div>
                    </div>
                    @if ( Null !== Auth::user() && (Auth::user()->hasAnyRole(['super_admin', 'admin'] || Auth::user()->id == $user->id)) )
                        <div class="col-lg-2 col-md-2 col-sm-2 edit_article pull-right clearfix">
                            {{ link_to('admin/user/'.$user->id.'/edit', '编辑', ['class'=>'btn btn-primary']) }}
                        </div>
                    @endif
                    <div class="col-lg-2 col-md-2 col-sm-2 edit_article pull-right clearfix">
                        <a class="btn btn-default btn-close" href="{{ url('/admin/user/'.$user->id) }}">取消</a>
                    </div>
                </div>

                @if (count($articles) > 0 )
                    <div class="panel-body">
                        <div><h3>{{ $user->name }} 的文章</h3></div>
                        <div class="form-group  col-lg-12 col-md-12 col-sm-12" id="auth_article_list">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>文章</th>
                                        <th>栏目</th>
                                        <th>类型</th>
                                        <th>状态</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($articles as $article)
                                        <tr>
                                            <td>{{ link_to('admin/article/'.$article->id, $article->title) }}</td>
                                            <td>{{ $article->categories->name }}</td>
                                            <td>{{ $article->article_types->name }}</td>
                                            <td>{{ count($article->article_status)>0 ? $article->article_status->title : '草稿' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection