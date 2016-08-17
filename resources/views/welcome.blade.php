@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Yi Pin Cars</div>

                <div class="panel-body">
                    <a href="{{ 'article/create' }}">New Article</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
