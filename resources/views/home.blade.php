@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    You are logged in!
                </div>

                <div>
                    @foreach ($jsonPost as $allArticles)
                        <div>
                            <ul>
                                <li>{{$allArticles['id']}}</li>
                                <li>{{$allArticles['title']}}</li>
                                <li>{{$allArticles['body']}}</li>
                            </ul>

                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
