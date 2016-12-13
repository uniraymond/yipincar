{{--<meta name="viewport" content="width=device-width, initial-scale=1" />--}}
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<title>{{ $article->title }}</title>
<link rel="stylesheet" href="{{ asset("/src/css/preview.css") }}"/>
{{--<div class="article-preview" id="content" contenteditable="false">--}}
<div class="article-preview" >

    <div class="title col-xs-12">{{ $article->title }}</div>
    <div class="clearfix"></div>
    <div class="subtitle">
        <div class="category col-xs-8">{{ $article->categories->name }}
            @if (isset($article->user_created_by->profiles))
                @if(isset($article->user_created_by->profiles->media_name) && $article->user_created_by->profiles->media_name != '')
                    <span class="mediaName" >{{ $article->user_created_by->profiles->media_name }}</span>
                @else
                    <span class="authname" >{{ $article->authname != '' ? $article->authname : $article->user_created_by->name }}</span>
                @endif
            @else
                <span class="authname" >{{ $article->authname != '' ? $article->authname : $article->user_created_by->name }}</span>
            @endif
            <span class="article_publish_date">{{ date('Y-m-d H:i', strtotime($article->created_at)) }}</span> </div>
        <div class="comment col-xs-2">评论{{ count($article->comments) }}</div>
    </div>
    <div class="clearfix"></div>

    @if($article->description)
        <div class="description">导读： {{ $article->description }}</div>
        <div class="clearfix"></div>
    @endif

    {{--<table>--}}
        <div class="content col-xs-12">{!! $article->content !!}</div>
    {{--</table>--}}
</div>
<div class="clearfix"></div>

<script src="{{ url('/src/js/jQuery.min.2.2.4.js') }}" ></script>
<script>
    jQuery(document).ready(function(){
        var width = $(document.body).width();


//        var tables = document.getElementsByTagName('img');
//        for(var i = 0; i<tables.length; i++){  // 逐个改变
//            tables[i].style.width = '100%';  // 宽度改为100%
//            tables[i].style.height = 'auto';
//        }

//        var width = document.body.scrollWidth;
//        jQuery('.content p img').width(width > 800 ? 800 *0.9 : width);
//        jQuery('.article-preview').width(width > 800 ? 800 *0.9 : width);

        jQuery('.content p img').width(width -30);
        jQuery('.article-preview').width(width -30);
    });
</script>