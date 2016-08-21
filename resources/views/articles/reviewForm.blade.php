@php (
       switch($statusName) {
       case 'reject':
              $reviewTitle = '驳回文章';
              $checkboxLabel = '确定驳回';
              break;
       case 'publish':
              $reviewTitle = '发布文章';
              $checkboxLabel = '确定发布';
              break;
       case 'reject':
              $reviewTitle = '审核文章';
              $checkboxLabel = '已经审核';
              break;
       default:
              $reviewTitle = '审核文章';
              $checkboxLabel = '已经审核';
              break;
       }
 )

@if (count($statusCheck) <= 0 && $currentUser->hasRole('chef_editor'))
    <h3>{{ $reviewTitle }}:</h3>
    {!! Form::open(array('url' => 'admin/article/review/'.$article->id, 'class' => 'form', 'method'=>'put')) !!}
    {!! Form::label('comment', '建议', array('class'=>'col-lg-12 col-md-12 col-sm-12')) !!}
    {!! Form::textarea('comment', '', array('class'=>'name col-lg-12 col-md-12 col-sm-12', 'placeholder' => '建议', 'rows'=> '3')) !!}
    {!! Form::text('article_status', $statusName, array('hidden')) !!}
    {{--{!! Form::label('published', '确定驳回', array('class'=>'col-lg-2 col-md-2 col-sm-2')) !!} --}}{{--if published setup to 4 that will be reject--}}
    {{--{!! Form::checkbox('published', '', false, array('class'=>'col-lg-2 col-md-2 col-sm-2')) !!}--}}
    <label for="published" class="col-lg-2 col-md-2 col-sm-2">
        <input type="checkbox" name="published" class="col-lg-2 col-md-2 col-sm-2" /> {{ $checkboxLabel }}
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
        {{--<div id="edit_status_check_form_{{ $statusCheck[0]->id }}" style="display: none" >--}}
        <div id="edit_status_check_form_{{ $statusCheck[0]->id }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading_edit_status_check_form">
            {!! Form::open(array('url' => 'admin/article/review/'.$article->id.'/edit/'.$statusCheck[0]->id, 'class' => 'form', 'method'=>'post')) !!}
            {!! Form::label('comment', '建议', array('class'=>'col-lg-12 col-md-12 col-sm-12')) !!}
            {!! Form::textarea('comment', $statusCheck[0]->comment , array('class'=>'name col-lg-12 col-md-12 col-sm-12', 'placeholder' => '建议', 'rows'=> '3')) !!}
            {!! Form::text('article_status', $statusName, array('hidden')) !!}
            {{--{!! Form::label('published', '确定驳回', array('class'=>'col-lg-2 col-md-2 col-sm-2')) !!} --}}{{--if published setup to 4 that will be reject--}}
            {{--{!! Form::checkbox('published', null, $statusCheck[0]->checked == 4 ? true : false, array('class'=>'col-lg-2 col-md-2 col-sm-2')) !!}--}}
            <label for="published" class="col-lg-2 col-md-2 col-sm-2">
                <input type="checkbox" name="published" {{ $statusCheck[0]->checked == 4 ? 'checked' : '' }} class="col-lg-2 col-md-2 col-sm-2" /> {{ $checkboxLabel }}
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
                {{--{!! Form::label('published', '确定驳回', array('class'=>'col-lg-2 col-md-2 col-sm-2')) !!} --}}{{--if published setup to 4 that will be reject--}}
                {{--{!! Form::checkbox('published', '', $article->published == 4 ? true : false, array('class'=>'col-lg-2 col-md-2 col-sm-2')) !!}--}}
                <label for="published" class="col-lg-2 col-md-2 col-sm-2">
                    <input type="checkbox" name="published" {{ $statusCk->checked == 4 ? 'checked' : '' }} class="col-lg-2 col-md-2 col-sm-2" /> {{ $checkboxLabel }}
                </label>
                {!! Form::token() !!}
                {!! Form::submit('保存', array('class'=>'btn btn-primary')) !!}
                {!! Form::close() !!}
            </div>
        @endif
    @endforeach
@endif