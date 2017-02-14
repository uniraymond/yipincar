<rss version="2.0">
    <chanel>
        {{--<meta name="viewport" content="width=device-width, initial-scale=1" />--}}

        <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
        <title>{{ $article->title }}</title>
        <link rel="stylesheet" href="{{ asset("/src/css/preview.css") }}"/>


        {{--<link>http://www.topautochina.com/preview/{{ $article->id }}</link>--}}
        {{--<!--此行是XML声明。定义了XML的版本和所使用的编码-->--}}
        {{--<rss--}}
        {{--xmlns:content="http://purl.org/rss/1.0/modules/content/"--}}
        {{--xmlns:atom="http://www.w3.org/2005/Atom" version="2.0">--}}
        {{--<channel>--}}
        {{--<title>{{ $article->title }}</title>--}}
        {{--<link>http://www.topautochina.com/preview/{{ $article->id }}</link>--}}
        {{--<description><![CDATA[{{ $article->description }}]]></description>--}}
        {{--<item>--}}
        {{--<title>{{ $article->title }}</title>--}}
        {{--<link>http://www.topautochina.com/preview/{{ $article->id }}</link>--}}
        {{--<description><![CDATA[{{ $article->description }}]]></description>--}}
        {{--<pubDate>{{ date('Y-m-d H:i', strtotime($article->created_at)) }}</pubDate>--}}
        {{--</item>--}}
        {{--</channel>--}}
        {{--</rss>--}}
        {{--<div class="article-preview" id="content" contenteditable="false">--}}


        <div style="margin: 17px" >
            <item>
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
                        <span class="article_publish_date"><pubDate>{{ date('Y-m-d H:i', strtotime($article->created_at)) }}</pubDate></span> </div>
                    <div class="comment col-xs-2">评论{{ count($article->comments) }}</div>
                </div>
                <div class="clearfix"></div>

                @if($article->description)
                    <div class="description"><description>导读： {{ $article->description }}</description></div>
                    <div class="clearfix"></div>
                @endif

                {{--<table>--}}
                @if($article->category_id == 16)
                    @php
                    $articleLinks = $article->resources;
                    $articleLink1 = '';
                    $articleLink = '';
                    if (count($articleLinks) > 0) {
                    foreach($articleLinks as $artLink) {
                    if($artLink->description == 'image') {
                    $articleLink1 = "http://localhost:8000".$artLink->link;
                    break;
                    }
                    }
                    foreach($articleLinks as $artLink) {
                    if($artLink->description == 'video') {
                    $articleLink = "http://localhost:8000".$artLink->link;
                    break;
                    }
                    }
                    }
                    @endphp
                    <video id="example_video_1" class="video-js vjs-default-skin vjs-big-play-centered"
                           controls preload="auto" width="100%" height="auto"
                           poster="{{$articleLink1}}"
                           data-setup='{"example_option":true}'>
                        <source src="{{$articleLink}}"/>
                        <p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
                    </video>
                @else
                    <div class="content col-xs-12">{!! $article->content !!}</div>
                    <div class="clearfix"></div>
                @endif
                {{--</table>--}}
            </item>

        </div>
    </chanel>

</rss>
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