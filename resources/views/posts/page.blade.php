@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Posts</div>

                    <div class="panel-body">
                        <div class="col-md-2">Post ID</div>
                        <div class="col-md-10">Post Title</div>
                        @foreach ($currentPosts as $allArticles)
                            <div class="col-md-1">{{$allArticles['id']}}</div>
                            <div class="col-md-11"><a href='post/{{$allArticles["id"]}}'>{{$allArticles['title']}}</a></div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
