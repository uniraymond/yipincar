<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<title>奥迪A3</title>
<link rel="stylesheet" href="{{ asset("/src/css/bootstrap.css") }}">
<link rel="stylesheet" href="{{ asset("/src/css/preview.css") }}"/>

<div class="article-preview" id="prviewbody" contenteditable="false">
    <div style="margin: 12px" >
    <img src="/audi_a3.jpg">
        <div class="clearfix"></div>

        <div class="title col-xs-12">奥迪 A3<div class="pull-right" style="color: blue">4.7分</div></div>

        <div class="clearfix"></div>
        <div class="col-xs-12">紧凑型车 1.4L/2.0L</div>
        <div class="clearfix"></div>

        <div class="col-xs-2">14.29-29.67万</div><div style="text-decoration:line-through"> 19.05-25.62万</div>

        {{--<div class="category col-xs-10">{{ $article->categories->name }}--}}
                {{--@if (isset($article->user_created_by->profiles))--}}
                    {{--@if(isset($article->user_created_by->profiles->media_name) && $article->user_created_by->profiles->media_name != '')--}}
                        {{--<span class="mediaName" >{{ $article->user_created_by->profiles->media_name }}</span>--}}
                    {{--@else--}}
                        {{--<span class="authname" >{{ $article->authname != '' ? $article->authname : $article->user_created_by->name }}</span>--}}
                    {{--@endif--}}
                {{--@else--}}
                    {{--<span class="authname" >{{ $article->authname != '' ? $article->authname : $article->user_created_by->name }}</span>--}}
                {{--@endif--}}
                {{--<span class="article_publish_date">{{ date('Y-m-d H:i', strtotime($article->created_at)) }}</span> </div>--}}
            {{--<div class="comment col-xs-2">评论{{ count($article->comments) }}</div>--}}
        {{--</div>--}}
        {{--<div class="clearfix"></div>--}}

        {{--@if($article->description)--}}
            {{--<div class="description  col-xs-12"> 导读： {{ $article->description }}</div>--}}
            {{--<div class="clearfix"></div>--}}
        {{--@endif--}}

        {{--<table>--}}
        {{--<div id="content" class="content col-xs-12">{!! $article->content !!}</div>--}}
        {{--<div class="clearfix"></div>--}}
        {{--</table>--}}

    </div>
</div>

<script src="{{ url('/src/js/jQuery.min.2.2.4.js') }}" ></script>
<script>
    $(window).load(function(){
        var tables = document.getElementsByTagName('img');
        for(var i = 0; i<tables.length; i++){  // 逐个改变
            tables[i].style.width = '100%';  // 宽度改为100%
            tables[i].style.height = 'auto';
        }
    });
</script>