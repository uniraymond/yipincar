@extends('layouts.base')
@include('layouts.contentSideBar')
{{--@include('articles.sidebarCategory',['categories'=>$categories, 'types'=>$types, 'tag'=>$tags, 'currentAction'=>$currentAction])--}}
@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12" style="margin-top: 35px">
                <h1 class="page-header">历史记录</h1>

                {{--flash alert--}}
                @if ($success = Session::get('status'))
                    <div class="col-lg-12 col-md-12 col-sm-12 bs-example-bg-classes" >
                        <p class="bg-success">
                            {{ $success }}
                        </p>
                    </div>
                @endif

                @if(count($histories)>0)
                    <table class="table table-striped">
                        <thead>
                        <tr>
                           <th>动作</th>
                           <th>原文章</th>
                           <th>新纪录</th>
                           <th>更改者</th>
                           <th>更改时间</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($histories as $history)
                            @php
                                $json = '{'.$history->target.'}';
                                $article = json_decode($json, true);
                            var_dump($article);
                                echo $article["article_title"];
                            @endphp
                            <tr>
                                <td>{{ $history->action }}</td>
                                <td>{{ $history->origin }}</td>
                                <td></td>
                                <td>{{ $history->created_by->user->name }}</td>
                                <td>{{ $history->created_at }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="col-lg-12 col-md-12 col-sm-12 clearfix">
                        <h4>暂时还没有历史记录.</h4>
                    </div>
                @endif
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 clearfix">
            {!! $histories->links() !!}
        </div>
    </div>
@endsection