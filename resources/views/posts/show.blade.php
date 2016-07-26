@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Posts</div>

                    <div class="panel-body">
                        <div class="col-md-10"><h2>{{$jsonPosts['title']}}</h2></div>
                        <div class="col-md-12">{{$jsonPosts['body']}}</div>

                        <div class="col-md-12"><h3>Comments</h3></div>
                        @foreach ($jsonComments as $comment)
                            <div class="col-sm-1">{{$comment['id']}}</div>
                            <div class="col-md-11">{{$comment['body']}}</div>
                            <div class="col-md-12">By: {{$comment['email']}}</div>
                        @endforeach
                    </div>

                    <div class="panel-body">
                        <form action="comment/add" method="post">
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" class="form-control" id="title" placeholder="Title">
                            </div>
                            <div class="form-group">
                                <label for="comment">Comment</label>
                                <area type="text" class="form-control" id="comment" placeholder="coment">
                            </div>
                            <button type="submit" class="btn btn-default">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
