{{--<meta name="viewport" content="width=device-width, initial-scale=1" />--}}
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<title>{{ $article->title }}</title>
<link rel="stylesheet" href="{{ asset("/src/css/bootstrap.css") }}">
<link rel="stylesheet" href="{{ asset("/src/css/preview.css") }}"/>

{{--<style media="screen">--}}
    {{--* {--}}
        {{--padding: 0;--}}
        {{--margin: 0;--}}
        {{---webkit-user-select: none;--}}
        {{---webkit-tap-highlight-color:rgba(0, 0, 0, 0);--}}
    {{--}--}}

    {{--html, body {--}}
        {{--height: 100%;--}}
        {{---webkit-overflow-scrolling: touch;--}}
    {{--}--}}

    {{--.example-title {--}}
        {{--height: 40px;--}}
        {{--line-height: 40px;--}}
        {{--text-align: center;--}}
        {{--font-size: 20px;--}}
        {{--background-color: #dddddd;--}}
    {{--}--}}

    {{--.example-list {--}}
        {{--padding: 4px 4px 0;--}}
        {{--list-style: none;--}}
    {{--}--}}

    {{--.example-listitem {--}}
        {{--height: 50px;--}}
        {{--line-height: 50px;--}}
        {{--border: solid 1px #999;--}}
        {{--border-radius: 2px;--}}
        {{--margin-bottom: 4px;--}}
        {{--text-align: center;--}}
    {{--}--}}

    {{--.example-listitem:last-child {--}}
        {{--margin-bottom: 0;--}}
    {{--}--}}

    {{--.example-wrapper {--}}
        {{--height: 300px;--}}
        {{--overflow: scroll;--}}
    {{--}--}}

    {{--.mint-loadmore-top span {--}}
        {{--display: inline-block;--}}
        {{--transition: .2s linear;--}}
    {{--}--}}

    {{--.rotate {--}}
        {{--transform: rotate(180deg);--}}
    {{--}--}}
{{--</style>--}}


{{--<script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>--}}

{{--<link>http://www.topautochina.com/preview/{{ $article->id }}</link>--}}
<!--此行是XML声明。定义了XML的版本和所使用的编码-->
{{--<rss--}}
{{--xmlns:content="http://purl.org/rss/1.0/modules/content/"--}}
{{--xmlns:atom="http://www.w3.org/2005/Atom" version="2.0">--}}
{{--<channel>--}}
{{--<title>{{ $article->title }}</title>--}}
{{--<link>http://www.topautochina.com/preview/{{ $article->id }}</link>--}}
{{--<description><![CDATA[{{ $article->description }}]]>}</description>--}}
{{--<item>--}}
{{--<title>{{ $article->title }}</title>--}}
{{--<link>http://www.topautochina.com/preview/{{ $article->id }}</link>--}}
{{--<description><![CDATA[{{ $article->description }}]]></description>--}}
{{--<pubDate>{{ date('Y-m-d H:i', strtotime($article->created_at)) }}</pubDate>--}}
{{--</item>--}}
{{--</channel>--}}
{{--</rss>--}}
{{--@section('content')--}}
<div class="article-preview" id="prviewbody" contenteditable="false">
    <div style="margin: 8px" >
        <div id="uid" style="visibility: hidden">{{$uid}}</div>
        <div class="title col-xs-12">{{ $article->title }}</div>
        <div class="clearfix"></div>
        <div class="subtitle">
            <div class="category col-xs-10">{{ $article->categories->name }}
                @if(isset($article->user_created_by->profiles) && isset($article->user_created_by->profiles->media_name) && $article->user_created_by->profiles->media_name != '')
                    <span class="mediaName" >{{ $article->user_created_by->profiles->media_name }}</span>
                @else
                    <span class="authname" >{{ $article->authname != '' ? $article->authname : $article->user_created_by->name }}</span>
                @endif
                <span class="article_publish_date">{{ date('Y-m-d H:i', strtotime($article->created_at)) }}</span> </div>
            <div class="comment col-xs-2">评论{{ count($article->comments) }}</div>
        </div>
        {{--<div class="clearfix"></div>--}}

        @if($article->description)
            <div class="description  col-xs-12"> 导读： {{ $article->description }}</div>
            {{--<div class="clearfix"></div>--}}
        @endif

        {{--<table>--}}
        <div class="content col-xs-12">{!! $article->content !!}</div>
        <div class="clearfix"></div>
        {{--</table>--}}

    </div>

    @if(count($recommends))
        <div class="recommend_top">推荐阅读</div>
        {{--<div class="media recommend_list">--}}
            {{--<div class="media-body" onclick="recommendClicked({{$article->id}})">--}}
                {{--<h4 class="media-heading">{{$article->title}}</h4>--}}
                {{--{{$article->description}}--}}
                {{--回复:{{count($article->comments)}}--}}
            {{--</div>--}}

                {{--<div class="media-right">--}}
                    {{--@php--}}
                    {{--$articleLinks = $article->resources;--}}
                    {{--$articleLink = '';--}}
                    {{--if (count($articleLinks) > 0) {--}}
                    {{--foreach($articleLinks as $artLink) {--}}
                    {{--$articleLink = $artLink->link;--}}
                    {{--break;--}}
                    {{--}--}}
                    {{--}--}}
                    {{--@endphp--}}
                    {{--<a href="#">--}}
                        {{--<img class="media-object" width="100" alt="图标" src="{{$articleLink}}">--}}
                        {{--                                {!! gettype($diary->user->avatar) !!}--}}
                    {{--</a>--}}
                {{--</div>--}}
        {{--</div>--}}
    {{--<div class="article_divider"></div>--}}

    <div class="media recommend_list">
        @foreach($recommends as $recommend)
{{--            {{$recommend->resources}}--}}
            @php
                $uri = 'http://'.$_SERVER['HTTP_HOST']."/v1/preview/".$recommend->id."/".$excludes.'/'.$readerid.'/'.$uid;
            @endphp
            {{--{{$uri}}--}}
        <a href= "{{$uri}}">
            <div class="media-body  recommend_container" >
                <h4 class="recommend_title">{{$recommend->title}}</h4>
                <h6 class="recommend_subtitle">
                    <div class="recommend_subInfo media-list">
                        @if(isset($recommend->mediaName) && $recommend->mediaName != '')
                            {{ $recommend->mediaName }}
                        @else
                            {{ $recommend->userName != '' ? $recommend->userName : $recommend->userName }}
                        @endif
                        {{ date('Y-m-d H:i', strtotime($recommend->created_at)) }}
                    </div>

                    <div class="pull-right recommend_readed" >阅读:{{$recommend->readed}}</div>
                </h6>
            </div>

            <div class="media-right">
                @if($recommend->resources)
                    <img class="media-object recommend_icon" alt="图标" src="{{$recommend->resources}}">
                @else
                    <img class="media-object recommend_icon" alt="图标" src="/photos/app_logo.png">
                @endif
            </div>
        </a>
            <div class="article_divider"></div>



        @endforeach
    </div>
    @endif

    @if(count($comments))
        <div class="recommend_top">热门评论</div>
        <div class="media recommend_list">
            @foreach($comments as $comment)

                    <div class="media-body  recommend_container" onclick="replyComment({{$comment->id}})">
                        <div class="media-left">
                            @if($comment->icon)
                                <img class="media-object img-circle" width="50" height="50" alt="图标" src="{{$comment->icon}}">
                            @else
                                <img class="media-object img-circle" width="50" height="50" alt="图标" src="/photos/male_holder.png">
                            @endif
                            @if($readerid && $readerid == $comment->created_by)
                                <h5 class="comment_delete" onclick="event.cancelBubble=true;deleteComment({{$comment->id}})">删除</h5>
                                {{--<a class="comment_delete" href="/api/delcomment?commentid={{$comment->id}}">删除</a>--}}
                            @endif
                        </div>

                        <div class="media-body comment_content">
                            <h4 class="comment_heading">{{$comment->name}}</h4>
                            <h4 class="recommend_subtitle">
                                {{substr(Carbon\Carbon::today(), 0, 10)==substr($article->created_at, 0, 10) ?
                                "今天 ".substr($comment->created_at, 10, strlen($comment->created_at)) : $comment->created_at}}
                            </h4>
                            <div class="clearfix"></div>
                            {{$comment->comment}}
                        </div>

                        <div class="media-right" id="approve_comment" onclick="event.cancelBubble=true;aprroveComment({{$comment->id}})">
                            {{--<a href="#">--}}
                            <img class="media-object" width="25" alt="图标" src="/photos/approve_button.png" >
                            <div class="comment_approve" id="comment_approves_count" >{{$comment->zans ? $comment->zans : "点赞"}}</div>
                            {{--                                {!! gettype($diary->user->avatar) !!}--}}
                            {{--</a>--}}
                        </div>

                        <div class="article_divider"></div>
                    </div>
                <div class="clearfix"></div>
            @endforeach
        </div>
    @endif


    {{--<loadmore :top-method="loadTop" :bottom-method="loadBottom" :bottom-all-loaded="allLoaded" ref="loadmore">--}}
        {{--<ul>--}}
            {{--<li v-for="item in list">{{ $article->title }}</li>--}}
        {{--</ul>--}}
    {{--</loadmore>--}}

    {{--<loadmore :top-method="loadTop2" @top-status-change="handleTopChange" ref="bottom">--}}
    {{--<ul class="example-list">--}}
        {{--<li v-for="item in list2" class="example-listitem">{{ $article->title }}</li>--}}
    {{--</ul>--}}
    {{--<div slot="top" class="mint-loadmore-top">--}}
        {{--<span v-show="topStatus !== 'loading'" :class="{ 'rotate': topStatus === 'drop' }">↓</span>--}}
        {{--<span v-show="topStatus === 'loading'">Loading...</span>--}}
    {{--</div>--}}
    {{--</loadmore>--}}

    {{--<loadmore :top-method="loadTop" :bottom-method="loadBottom" :bottom-all-loaded="allLoaded" ref="loadmore">--}}
        {{--<div class="card facebook-card" v-for="item in mes">--}}
            {{--<div class="card-header no-border">--}}
                {{--<div class="facebook-avatar">--}}
                    {{--<img :src="item.userHeadImg" alt=""width="34" height="34"/>--}}
                {{--</div>--}}
                {{--<div class="facebook-name">{{$article->title}}</div>--}}
                {{--<div class="facebook-date">{{$article->description}}</div>--}}
            {{--</div>--}}
            {{--<div class="card-content">--}}
                {{--<img :src="item.headImage" alt="" width="100%"/>--}}
            {{--</div>--}}
            {{--<div class="card-footer no-border">--}}
                {{--<a href="#" class="link">{{$article->id}}赞</a>--}}
                {{--<a href="#" class="link">评论</a>--}}
                {{--<a href="#" class="link">分享</a>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</loadmore>--}}

</div>

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

        jQuery('.content p img').width(width -60 > 800 ? 800 : width -60);

//        jQuery('.article-preview').width(width -30);

    });


{{--<script type="text/javascript">--}}

    {{--new Vue({--}}
        {{--el: '.vuetitle',--}}
        {{--data: {--}}
            {{--message: 'Hello Laravel!'--}}
        {{--}--}}
    {{--})--}}

    {{--// ES6 mudule--}}
    {{--import Loadmore from 'vue-loadmore';--}}

    {{--// CommonJS--}}
{{--//    const Loadmore = require('vue-loadmore').default;//    Vue.component('loadmore', Loadmore);--}}
    {{--new Vue({--}}
        {{--el: '#example1',--}}
        {{--components: {--}}
            {{--'loadmore': Loadmore--}}
        {{--},--}}

        {{--data() {--}}
        {{--return {--}}
            {{--list: [],--}}
            {{--allLoaded: false--}}
        {{--};--}}
    {{--},--}}

    {{--methods: {--}}
        {{--loadTop(id) {--}}
            {{--setTimeout(() => {--}}
                {{--if (this.list[0] === 1) {--}}
                {{--for (let i = 0; i >= -10; i--) {--}}
                    {{--this.list.unshift(i);--}}
                {{--}--}}
            {{--}--}}
            {{--this.$refs.top.onTopLoaded(id);--}}
        {{--}, 1500);--}}
        {{--},--}}

        {{--loadBottom(id) {--}}
            {{--setTimeout(() => {--}}
                {{--let lastValue = this.list[this.list.length - 1];--}}
            {{--if (lastValue < 40) {--}}
                {{--for (let i = 1; i <= 10; i++) {--}}
                    {{--this.list.push(lastValue + i);--}}
                {{--}--}}
            {{--} else {--}}
                {{--this.allLoaded = true;--}}
            {{--}--}}
            {{--this.$broadcast('onBottomLoaded', id);--}}
        {{--}, 1500);--}}
        {{--}--}}
    {{--},--}}

    {{--created() {--}}
        {{--for (let i = 1; i <= 20; i++) {--}}
            {{--this.list.push(i);--}}
        {{--}--}}
    {{--}--}}
    {{--});--}}

    {{--new Vue({--}}
        {{--el: '#example2',--}}
        {{--components: {--}}
            {{--'loadmore': Loadmore--}}
        {{--},--}}

        {{--data() {--}}
        {{--return {--}}
            {{--list2: [],--}}
            {{--topStatus: ''--}}
        {{--};--}}
    {{--},--}}

    {{--methods:{--}}
        {{--getList:function(page){--}}
            {{--this.$http.get("https://apis.baidu.com/qunartravel/travellist/travellist",{--}}
                {{--params:{--}}
                    {{--page:this.page,--}}
                {{--},--}}
                {{--headers:{--}}
                    {{--'apikey':'06ad8ab76e20c1fb0a04cfd9ce4f5e0c'--}}
                {{--}--}}
            {{--}).then(function(res){--}}
                {{--//this.mes=this.mes.concat(res.body.data.books);  数据追加--}}
                {{--this.mes=res.body.data.books;--}}
                {{--console.log(this.mes);--}}
            {{--},function(err){--}}
                {{--console.log(err);--}}
                {{--this.success=false;--}}
            {{--})--}}
        {{--},--}}
        {{--loadTop(id){--}}
            {{--console.log(this.page);--}}
            {{--//默认是第三页，下拉刷新的时候获取第一页--}}
            {{--this.page=1;--}}
            {{--this.getList(this.page);--}}
            {{--this.$refs.loadmore.onTopLoaded(id);--}}
            {{--console.log("id="+id);--}}
        {{--},--}}
        {{--loadMore(){--}}
            {{--console.log("loadMore");--}}

        {{--},--}}
        {{--loadBottom(id) {--}}
            {{--console.log("下方在执行id="+id)--}}
            {{--//this.page++;--}}
            {{--//this.getList(this.page);--}}
            {{--//  this.$refs.loadmore.onBottomLoaded(id);--}}
        {{--}--}}

    {{--}--}}

    {{--created() {--}}
        {{--for (let i = 1; i <= 20; i++) {--}}
            {{--this.list2.push(i);--}}
        {{--}--}}
    {{--}--}}
    {{--});--}}

    {{--//    Vue.component('ZZScroll', require('/src/js/components/ZZScroll.vue'))--}}
</script>

<script src="{{ url('/src/js/jQuery.min.2.2.4.js') }}" ></script>

<script>
//    function recommendClicked($recommendid, $excludes, $readerid){
//        $assign = '/v1/preview/' + $recommendid +'/' + $excludes + '/' + $readerid;
//        alert('url:' + $readerid);
////        window.location.assign($assign)
//    }



    {{--$('#approve_comment').click(function(){--}}
    {{--//            alert('raido value: ' + $(this).val());--}}
        {{--$.post('{{ action('InfoController@releaseComment') }}',--}}

                {{--{ '_token': token ,'oldpassword': oldpassword,--}}

                    {{--'newpassword': newpassword, 'newpassword2':newpassword2 },--}}

                {{--function(data){--}}

                    {{--alert("Data Loaded: " + data);--}}

                {{--});--}}
    {{--});--}}

    function replyComment($commentid) {
        $assign = '/api/comment?commentid=' + $commentid;
//        alert($assign);
        window.location.assign($assign)

    }

    function aprroveComment($commentid) {
        $uid = $('#uid').val();
        $assign='/api/approvecomment?commentid=' + $commentid + '&uid=' + $uid;
//        alert('谢谢支持!' + $assign);
//        window.location.assign($assign)
        $.ajax({

            type: 'GET',

            url: $assign,

            data: { commentid : $commentid,
                    uid : $uid},

            dataType: "json",

//            headers: {
//
//                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
//
//            },

            success: function(data){

//                console.log(data);
                if (data.approved == '+1') {
                    alert('谢谢支持!');
                } else {
                    alert('取消点赞!');
                }
                $('#comment_approves_count').text(data.count == 0 ? '点赞' : data.count)

            },

            error: function(xhr, type){

//                alert('Ajax error!')

            }

        });
    }

    function deleteComment($commentid) {
        if (confirm('确定要删除评论?')) {
            alert('谢谢支持!');
            $assign = "/api/delcomment?commentid=" + $commentid;
            $.ajax({

                type: 'GET',

                url: $assign,

                data: { commentid : $commentid},

                dataType: "json",

//            headers: {
//
//                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
//
//            },

                success: function(data){

//                console.log(data);

                    window.location.reload();
                },

                error: function(xhr, type){
//                    alert('Ajax error!')
                }

            });
        } else  {
//            alert('取消删除!');
        }

    }
</script>
{{--@endsection--}}