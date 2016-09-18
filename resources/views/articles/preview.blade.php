<link rel="stylesheet" href="{{ asset("/src/css/preview.css") }}"/>
<div class="article-preview">
    <div class="title col-xs-12">{{ $article->title }}</div>
    <div class="category col-xs-12">频道 {{ $article->categories->name }}</div>
    <div class="content col-xs-12">{!! $article->content !!}</div>
</div>