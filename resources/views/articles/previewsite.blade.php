{{--<meta name="viewport" content="width=device-width, initial-scale=1" />--}}
<link rel="stylesheet" href="{{ asset("/src/css/previewsite.css") }}"/>
<div class="article-preview">
    <div class="title col-xs-12">{{ $article->title }}</div>
    <div class="clearfix"></div>
    <div class="subtitle">
        <div class="category col-xs-8">{{ $article->categories->name }}
            <span class="authname" >{{ $article->authname ? $article->authname : $article->user_created_by->name }}</span>
            <span class="article_publish_date">{{ date('Y-m-d H:i', strtotime($article->created_at)) }}</span> </div>
        <div class="comment col-xs-2">评论{{ count($article->comments) }}</div>
    </div>
    <div class="clearfix"></div>
    <div class="description">导读： {{ $article->description }}</div>
    <div class="clearfix"></div>
    <div class="content col-xs-12">{!! $article->content !!}</div>
</div>

<script src="{{ url('/src/js/jQuery.min.2.2.4.js') }}" ></script>
<script>
    jQuery(document).ready(function(){
//        var width = $(document.body).width();
        var width = document.body.scrollWidth;
//        jQuery('.content p img').width(width > 800 ? 800 *0.9 : width);
//        jQuery('.article-preview').width(width > 800 ? 800 *0.9 : width);
        jQuery('.content p img').width(width -30);
        jQuery('.article-preview').width(width -30);

    });
</script>