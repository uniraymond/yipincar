@extends('layouts.base')
@include('layouts.settingSideBar')
@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12" style="margin-top: 35px">
                <h1 class="page-header">文章评论</h1>
                {{--flash alert--}}
                @if ($success = Session::get('status'))
                    <div class="col-lg-12 col-md-12 col-sm-12 bs-example-bg-classes" >
                        <p class="bg-success">
                            {{ $success }}
                        </p>
                    </div>
                @endif
            @if($comments)
                <table class="table">
                    <thead>
                        <tr>
                            <th>文章评论</th>
                            <th>文章</th>
                            <th>发表</th>
                            <th>删除</th>
                            <th>点赞数</th>
                        </tr>
                    </thead>
                    <tbody>
                    {!! Form::open(array('url' => 'admin/articlecomment/update/'.$articleId, 'class' => 'form', 'method'=>'post', 'onsubmit'=>'return confirm("确定发表?");')) !!}
                        @foreach($comments as $comment)
                            <tr>
                                <td>{{ $comment->comment }}</td>
                                <td><span id="article_{{ $comment->id }}">{{ $comment->article->title }}</span></td>
                                <td>
                                    <input type="checkbox" name="published[{{ $comment->id }}]" {{ $comment->published ? 'checked' : '' }}/>
                                </td>
                                <td>
                                    <button class="btn btn-primary" id="deletComment{{ $comment->id }}" value="{{ $comment->id }}" onclick="deleteComment({{ $comment->id }}); return false;" >
                                        删除
                                    </button>
                                    {{--<input type="checkbox" name="delete[{{ $comment->id }}]" />--}}
                                </td>
                                <td>
                                    {{ count($comment->Zan) }}
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td>{!! Html::link('admin/article', '返回文章列表') !!}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>{!! Form::submit('提交', array('class'=>'btn btn-default')) !!}</td>

                            {!! Form::token() !!}
                            {!! Form::close() !!}
                        </tr>
                    </tbody>
                </table>
            @endif
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 clearfix">
            <span class="totalpage pagination">评论总数：{{ ($totalComments) }}个</span>   {!! $comments->links() !!}
        </div>
    </div>
        <script src="{{ url('/src/js/jQuery.min.2.2.4.js') }}" ></script>
        <script type="text/javascript">
            function deleteComment(commentId) {
                var getUrl = "{{ url('admin/articlecomment/delete') }}" + '/' + commentId;
                var tokenval = jQuery("input[name=_token]").val();
                if (confirm("确定删除?")) {
                    jQuery.ajax({
                        method: 'post',
                        url: getUrl,
                        data: {'comment_id':commentId, '_token':tokenval},
                        dataType: 'json',
                        success: function(data) {
                            console.log(data);
//                            location,reload(ture);
                        },
                        complete: function(){
                            window.location.reload(true);
                        }
                    });
                }
                return false;
            }
        </script>
@endsection