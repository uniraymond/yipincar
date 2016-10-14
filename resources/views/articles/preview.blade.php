<link rel="stylesheet" href="{{ asset("/src/css/preview.css") }}"/>
<div class="article-preview">
    <div class="title col-xs-12">{{ $article->title }}</div>
    <div class="clearfix"></div>
    <div class="subtitle">
        <div class="category col-xs-10">{{ $article->categories->name }}
            <span class="authname" >{{ $article->authname ? $article->authname : $article->user_created_by->name }}</span>
            <span class="article_publish_date">{{ $article->created_at }}</span> </div>
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
        var width = $(document.body).width();
        jQuery('.content p img').width(width-20);
    });
</script>