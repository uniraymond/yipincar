@extends('layouts.app')
@section('content')
    @php ( $currentUser = Auth::user() )
    <div class="container">
        <div class="row">
            <div class="col-md-11 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h1>{{ $article->title }}</h1>
                        <div>
                            <small><span>作者: </span>{{ $article->created_by ? $article->user_created_by->name : '无名' }}</small>
                        </div>
                        <div>
                            <small><span>完成日期: </span>{{ date('Y m d, H:s', strtotime($article->created_date)) }}</small>
                        </div>
                    </div>

                    <div class="panel-body">
                        <div class="form-group col-lg-12 col-md-12 col-sm-12" >
                            <div>简述:</div>
                            <div> <p>{{ $article->description }}</p> </div>
                            <div class="clearfix"></div>
                            <div>详细内容: </div>
                            <div> {!! $article->content !!} </div>
                            <div class="list-group">
                                <div class="list-group-item list-group-item-action"> 类别: {{ $article->categories->name }} </div>
                                <div class="list-group-item list-group-item-action"> 类型: {{ $article->article_types->name }} </div>
                                <div class="list-group-item list-group-item-action"> 标签:
                                    @if (count($article->tags)>0)
                                        @foreach($article->tags as $article_tag)
                                            {{ $article_tag->name . ' ' }}
                                        @endforeach
                                    @else
                                        {{ '暂时还没有选择标签' }}
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 pull-right clearfix">
                            {{ link_to('admin/article/'.$article->id.'/edit', '编辑', ['class'=>'btn btn-primary']) }}
                        </div>
                    </div>
                </div>

                @if ($fail = Session::get('warning'))
                    <div class=" col-lg-12 col-md-12 col-sm-12  bs-example-bg-classes" >
                        <p class="bg-danger">
                            {{ $fail }}
                        </p>
                    </div>
                @endif

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3>文章审阅</h3>
                    </div>

                    <div class="panel-body">
                        <div class="form-group  col-lg-12 col-md-12 col-sm-12" id="accordion">
                            @if (count($allStatusChecks) > 0 )
                            @foreach ($allStatusChecks as $statusName => $statusCheck)
                                <div class="col-lg-12 col-md-12 col-sm-12 article_reviews" id="heading_edit_status_check_form">
                                @if ($statusName == 'reject')
                                    {{--editors can do something here only--}}
                                        @include('articles.reviewForm', [
                                                                            'statusCheck'=>$statusCheck,
                                                                            'currentUser'=>$currentUser,
                                                                            'statusName'=>$statusName,
                                                                            'article'=>$article
                                                                        ])
                                @elseif ($statusName == 'publish')
                                    {{--editors can do something here only--}}
                                    @if (count($statusCheck) <= 0 && $currentUser->hasRole('chef_editor'))
                                        <h3>发布文章:</h3>
                                        {!! Form::open(array('url' => 'admin/article/review/'.$article->id, 'class' => 'form', 'method'=>'put')) !!}
                                        {!! Form::label('comment', '建议', array('class'=>'col-lg-12 col-md-12 col-sm-12')) !!}
                                        {!! Form::textarea('comment', '', array('class'=>'name col-lg-12 col-md-12 col-sm-12', 'placeholder' => '建议', 'rows'=> '3')) !!}
                                        {!! Form::text('article_status', $statusName, array('hidden')) !!}
                                            <label for="published" class="col-lg-2 col-md-2 col-sm-2">
                                                <input type="checkbox" name="published" class="col-lg-2 col-md-2 col-sm-2" /> 确定发布
                                            </label>
                                            {!! Form::token() !!}
                                        {!! Form::submit('保存', array('class'=>'btn btn-primary')) !!}
                                        {!! Form::close() !!}
                                    @elseif (count($statusCheck) == 1)
                                        <div class="col-lg-2 col-md-2 col-sm-2">{{ $statusCheck[0]->article_status->title }}</div>
                                        <div class="col-lg-9 col-md-9 col-sm-9">{{ $statusCheck[0]->comment }}</div>
                                        @if($statusCheck[0]->created_by == $currentUser->id && $currentUser->hasRole('chef_editor'))
                                            <div class="col-lg-1 col-md-1 col-sm-1">
                                                <a class="collapsed" data-toggle="collapse" href="#edit_status_check_form_{{ $statusCheck[0]->id }}" aria-expanded="false" aria-controls="edit_status_check_form_{{ $statusCheck[0]->id }}">
                                                    Edit
                                                </a>
                                            </div>
                                            <div id="edit_status_check_form_{{ $statusCheck[0]->id }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading_edit_status_check_form">
                                                {!! Form::open(array('url' => 'admin/article/review/'.$article->id.'/edit/'.$statusCheck[0]->id, 'class' => 'form', 'method'=>'post')) !!}
                                                {!! Form::label('comment', '建议', array('class'=>'col-lg-12 col-md-12 col-sm-12')) !!}
                                                {!! Form::textarea('comment', $statusCheck[0]->comment , array('class'=>'name col-lg-12 col-md-12 col-sm-12', 'placeholder' => '建议', 'rows'=> '3')) !!}
                                                {!! Form::text('article_status', $statusName, array('hidden')) !!}
                                                <label for="published" class="col-lg-2 col-md-2 col-sm-2">
                                                    <input type="checkbox" name="published" {{ $statusCheck[0]->checked == 3 ? 'checked' : '' }} class="col-lg-2 col-md-2 col-sm-2" /> 确定发布
                                                </label>
                                                {!! Form::token() !!}
                                                {!! Form::submit('保存', array('class'=>'btn btn-primary')) !!}
                                                {!! Form::close() !!}
                                            </div>
                                        @endif
                                    @else
                                        @foreach($statusCheck as $statusCk)
                                            <div class="col-lg-2 col-md-2 col-sm-2">{{ $statusCk->article_status->title }}</div>
                                            <div class="col-lg-9 col-md-9 col-sm-9">{{ $statusCk->comment }}</div>
                                            @if($statusCk->created_by == $currentUser->id && $currentUser->hasRole('chef_editor'))
                                                <div class="col-lg-1 col-md-1 col-sm-1">
                                                    <a class="collapsed" data-toggle="collapse" href="#edit_status_check_form_{{ $statusCk->id }}" aria-expanded="false" aria-controls="edit_status_check_form_{{ $statusCk->id }}">
                                                        Edit
                                                    </a>
                                                </div>
                                                <div id="edit_status_check_form_{{ $statusCk->id }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading_edit_status_check_form">
                                                    {!! Form::open(array('url' => 'admin/article/review/'.$article->id.'/edit/'.$statusCk->id, 'class' => 'form', 'method'=>'post')) !!}
                                                    {!! Form::label('comment', '建议', array('class'=>'col-lg-12 col-md-12 col-sm-12')) !!}
                                                    {!! Form::textarea('comment', $statusCk->comment , array('class'=>'name col-lg-12 col-md-12 col-sm-12', 'placeholder' => '建议', 'rows'=> '3')) !!}
                                                    {!! Form::text('article_status', $statusName, array('hidden')) !!}
                                                    <label for="published" class="col-lg-2 col-md-2 col-sm-2">
                                                        <input type="checkbox" name="published" {{ $statusCk->checked == 3 ? 'checked' : '' }} class="col-lg-2 col-md-2 col-sm-2" /> 确定发布
                                                    </label>
                                                    {!! Form::token() !!}
                                                    {!! Form::submit('保存', array('class'=>'btn btn-primary')) !!}
                                                    {!! Form::close() !!}
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                @elseif($statusName == 'review')
                                    {{--main editors can do something here only--}}
                                    @if (count($statusCheck) <= 0 && $currentUser->hasRole('main_editor'))
                                        <h3>审核文章:</h3>
                                        {!! Form::open(array('url' => 'admin/article/review/'.$article->id, 'class' => 'form', 'method'=>'put')) !!}
                                        {!! Form::label('comment', '建议', array('class'=>'col-lg-12 col-md-12 col-sm-12')) !!}
                                        {!! Form::textarea('comment', '', array('class'=>'name col-lg-12 col-md-12 col-sm-12', 'placeholder' => '建议', 'rows'=> '3')) !!}
                                        {!! Form::text('article_status', $statusName, array('hidden')) !!}
                                            <label for="published" class="col-lg-2 col-md-2 col-sm-2">
                                                <input type="checkbox" name="published" class="col-lg-2 col-md-2 col-sm-2" /> 已经审核
                                            </label>
                                            {!! Form::token() !!}
                                        {!! Form::submit('保存', array('class'=>'btn btn-primary')) !!}
                                        {!! Form::close() !!}
                                    @elseif (count($statusCheck) == 1)
                                        <div class="col-lg-2 col-md-2 col-sm-2">{{ $statusCheck[0]->article_status->title }}</div>
                                        <div class="col-lg-9 col-md-9 col-sm-9">{{ $statusCheck[0]->comment }}</div>
                                        @if($statusCheck[0]->created_by == $currentUser->id && $currentUser->hasRole('main_editor'))
                                                <div class="col-lg-1 col-md-1 col-sm-1">
                                                    <a class="collapsed" data-toggle="collapse" href="#edit_status_check_form_{{ $statusCheck[0]->id }}" aria-expanded="false" aria-controls="edit_status_check_form_{{ $statusCheck[0]->id }}">
                                                        Edit
                                                    </a>
                                                </div>
                                                <div id="edit_status_check_form_{{ $statusCheck[0]->id }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading_edit_status_check_form">
                                                {!! Form::open(array('url' => 'admin/article/review/'.$article->id.'/edit/'.$statusCheck[0]->id, 'class' => 'form', 'method'=>'post')) !!}
                                                {!! Form::label('comment', '建议', array('class'=>'col-lg-12 col-md-12 col-sm-12')) !!}
                                                {!! Form::textarea('comment', $statusCheck[0]->comment , array('class'=>'name col-lg-12 col-md-12 col-sm-12', 'placeholder' => '建议', 'rows'=> '3')) !!}
                                                {!! Form::text('article_status', $statusName, array('hidden')) !!}
                                                <label for="published" class="col-lg-2 col-md-2 col-sm-2">
                                                    <input type="checkbox" name="published" {{ $statusCheck[0]->checked == 2 ? 'checked' : '' }} class="col-lg-2 col-md-2 col-sm-2" /> 已经审核
                                                </label>
                                                {!! Form::token() !!}
                                                {!! Form::submit('保存', array('class'=>'btn btn-primary')) !!}
                                                {!! Form::close() !!}
                                            </div>
                                        @endif
                                    @else
                                        @foreach($statusCheck as $statusCk)
                                            <div class="col-lg-2 col-md-2 col-sm-2">{{ $statusCk->article_status->title }}</div>
                                            <div class="col-lg-9 col-md-9 col-sm-9">{{ $statusCk->comment }}</div>
                                            @if($statusCk->created_by == $currentUser->id && $currentUser->hasRole('chef_editor'))
                                                <div class="col-lg-1 col-md-1 col-sm-1">
                                                    <a class="collapsed" data-toggle="collapse" href="#edit_status_check_form_{{ $statusCk->id }}" aria-expanded="false" aria-controls="edit_status_check_form_{{ $statusCk->id }}">
                                                        Edit
                                                    </a>
                                                </div>
                                                <div id="edit_status_check_form_{{ $statusCk->id }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading_edit_status_check_form">
                                                    {!! Form::open(array('url' => 'admin/article/review/'.$article->id.'/edit/'.$statusCk->id, 'class' => 'form', 'method'=>'post')) !!}
                                                    {!! Form::label('comment', '建议', array('class'=>'col-lg-12 col-md-12 col-sm-12')) !!}
                                                    {!! Form::textarea('comment', $statusCk->comment , array('class'=>'name col-lg-12 col-md-12 col-sm-12', 'placeholder' => '建议', 'rows'=> '3')) !!}
                                                    {!! Form::text('article_status', $statusName, array('hidden')) !!}
                                                    <label for="published" class="col-lg-2 col-md-2 col-sm-2">
                                                        <input type="checkbox" name="published" {{ $statusCk->checked == 2 ? 'checked' : '' }} class="col-lg-2 col-md-2 col-sm-2" /> 已经审核
                                                    </label>
                                                    {!! Form::token() !!}
                                                    {!! Form::submit('保存', array('class'=>'btn btn-primary')) !!}
                                                    {!! Form::close() !!}
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                @elseif($statusName == 'draft')
                                    {{--editors can do something here only--}}
                                    @if (count($statusCheck) <= 0 && $currentUser->hasRole('editor', 'auth_editor') && $article->created_by == $currentUser->id)
                                        <h3>发表草稿:</h3>
                                        {!! Form::open(array('url' => 'admin/article/review/'.$article->id, 'class' => 'form', 'method'=>'put')) !!}
                                        {!! Form::label('comment', '建议', array('class'=>'col-lg-12 col-md-12 col-sm-12')) !!}
                                        {!! Form::textarea('comment', '', array('class'=>'name col-lg-12 col-md-12 col-sm-12', 'placeholder' => '建议', 'rows'=> '3')) !!}
                                        {!! Form::text('article_status', $statusName, array('hidden')) !!}
                                            <label for="published" class="col-lg-2 col-md-2 col-sm-2">
                                                <input type="checkbox" name="published" class="col-lg-2 col-md-2 col-sm-2" /> 申请审核
                                            </label>
                                            {!! Form::token() !!}
                                        {!! Form::submit('保存', array('class'=>'btn btn-primary')) !!}
                                        {!! Form::close() !!}
                                    @elseif (count($statusCheck) == 1)
                                        <div class="col-lg-2 col-md-2 col-sm-2">{{ $statusCheck[0]->article_status->title }}</div>
                                        <div class="col-lg-9 col-md-9 col-sm-9">{{ $statusCheck[0]->comment }}</div>
                                        @if($statusCheck[0]->created_by == $currentUser->id && $currentUser->hasRole('editor', 'auth_editor'))
                                            <div class="col-lg-1 col-md-1 col-sm-1">
                                                <a class="collapsed" data-toggle="collapse" href="#edit_status_check_form_{{ $statusCheck[0]->id }}" aria-expanded="false" aria-controls="edit_status_check_form_{{ $statusCheck[0]->id }}">
                                                    Edit
                                                </a>
                                            </div>
                                            <div id="edit_status_check_form_{{ $statusCheck[0]->id }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading_edit_status_check_form">
                                                {!! Form::open(array('url' => 'admin/article/review/'.$article->id.'/edit/'.$statusCheck[0]->id, 'class' => 'form', 'method'=>'post')) !!}
                                                {!! Form::label('comment', '建议', array('class'=>'col-lg-12 col-md-12 col-sm-12')) !!}
                                                {!! Form::textarea('comment', $statusCheck[0]->comment , array('class'=>'name col-lg-12 col-md-12 col-sm-12', 'placeholder' => '建议', 'rows'=> '3')) !!}
                                                {!! Form::text('article_status', $statusName, array('hidden')) !!}
                                                <label for="published" class="col-lg-2 col-md-2 col-sm-2">
                                                    <input type="checkbox" name="published" {{ $statusCheck[0]->checked == 1 ? 'checked' : '' }} class="col-lg-2 col-md-2 col-sm-2" /> 申请审核
                                                </label>
                                                {!! Form::token() !!}
                                                {!! Form::submit('保存', array('class'=>'btn btn-primary')) !!}
                                                {!! Form::close() !!}
                                            </div>
                                        @endif
                                    @else
                                        @foreach($statusCheck as $statusCk)
                                            <div class="col-lg-2 col-md-2 col-sm-2">{{ $statusCk->article_status->title }}</div>
                                            <div class="col-lg-9 col-md-9 col-sm-9">{{ $statusCk->comment }}</div>
                                            @if($statusCk->created_by == $currentUser->id && $currentUser->hasRole('editor', 'auth_editor'))
                                                <div class="col-lg-1 col-md-1 col-sm-1">
                                                    <a class="collapsed" data-toggle="collapse" href="#edit_status_check_form_{{ $statusCk->id }}" aria-expanded="false" aria-controls="edit_status_check_form_{{ $statusCk->id }}">
                                                        Edit
                                                    </a>
                                                </div>
                                                <div id="edit_status_check_form_{{ $statusCk->id }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading_edit_status_check_form">
                                                    {!! Form::open(array('url' => 'admin/article/review/'.$article->id.'/edit/'.$statusCk->id, 'class' => 'form', 'method'=>'post')) !!}
                                                    {!! Form::label('comment', '建议', array('class'=>'col-lg-12 col-md-12 col-sm-12')) !!}
                                                    {!! Form::textarea('comment', $statusCk->comment , array('class'=>'name col-lg-12 col-md-12 col-sm-12', 'placeholder' => '建议', 'rows'=> '3')) !!}
                                                    {!! Form::text('article_status', $statusName, array('hidden')) !!}
                                                    <label for="published" class="col-lg-2 col-md-2 col-sm-2">
                                                        <input type="checkbox" name="published" {{ $statusCk->checked == 1 ? 'checked' : '' }} class="col-lg-2 col-md-2 col-sm-2" /> 申请审核
                                                    </label>
                                                    {!! Form::token() !!}
                                                    {!! Form::submit('保存', array('class'=>'btn btn-primary')) !!}
                                                    {!! Form::close() !!}
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                @endif
                                </div>
                                <div class="clearfix col-lg-12 col-md-12 col-sm-12"></div>
                            @endforeach
                                @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
