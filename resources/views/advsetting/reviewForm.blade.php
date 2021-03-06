@php
   $displayForm = false;
   switch($statusName) {
   case 'publish':
        if(Auth::user()->hasRole('chef_editor')) {
            $displayForm = true;
        }
          $reviewTitle = '最终审核';
          $checkboxLabel = '终审';
          $radioLabel1 = '发布';
          $radioLabel2 = '驳回';
          $currentStatusId = 4;
          break;
   case 'review':
          break;
   case 'review_apply':
        if(Auth::user()->hasAnyRole(['adv_editor']) && $advsetting->created_by == Auth::user()->id && ($advsetting->status == 1 || $advsetting->status == 0)) {
            $displayForm = true;
        }
          $reviewTitle = '申请审核';
          $checkboxLabel = '申请审核';
          $radioLabel1 = '申请审核';
          $radioLabel2 = '草稿';
          $currentStatusId = 2;
          break;
   case 'draft': break;
   }
@endphp

@if (count($statusCheck) <= 0 && $displayForm)
    {{--@if (count($statusCheck) <= 0 && $currentUser->hasRole('adv_editor'))--}}
        <h3>{{ $reviewTitle }}:</h3>
        {!! Form::open(array('url' => 'admin/advsetting/review/'.$advsetting->id, 'class' => 'form', 'method'=>'put')) !!}
        {!! Form::label('comment', '建议', array('class'=>'col-lg-12 col-md-12 col-sm-12')) !!}
        {!! Form::textarea('comment', '', array('class'=>'name col-lg-12 col-md-12 col-sm-12', 'placeholder' => '建议', 'rows'=> '3')) !!}
        {!! Form::text('article_status', $statusName, array('hidden')) !!}
        <div class="form-check">
            <label for="status" class="col-lg-2 col-md-2 col-sm-2 form-check-label">
                <input type="radio" name="status" class="col-lg-2 col-md-2 col-sm-2 form-check-input" value="2" /> {{ $radioLabel1 }}
            </label>
            <label for="status" class="col-lg-2 col-md-2 col-sm-2 form-check-label">
                <input type="radio" name="status" class="col-lg-2 col-md-2 col-sm-2 form-check-input" value="1" /> {{ $radioLabel2 }}
            </label>
        </div>
        {!! Form::submit('确定', array('class'=>'btn btn-primary col-lg-offset-8 col-md-offset-8 col-sm-offset-8')) !!}
        {!! Form::token() !!}
        {!! Form::close() !!}
    {{--@endif--}}
@elseif (count($statusCheck) == 1)
        <div class="{{ $statusCheck[0]->checked == $advsetting->status ? 'bs-callout bs-callout-primary' : '' }}" >
            <div class="col-lg-2 col-md-2 col-sm-2">{{ $statusCheck[0]->article_status->title }}</div>
            <div class="col-lg-9 col-md-9 col-sm-9">{{ $statusCheck[0]->comment }}</div>
            @if($statusCheck[0]->created_by == $currentUser->id && $displayForm)
                <div class="col-lg-1 col-md-1 col-sm-1">
                    <a class="collapsed" data-toggle="collapse" href="#edit_status_check_form_{{ $statusCheck[0]->id }}" aria-expanded="false" aria-controls="edit_status_check_form_{{ $statusCheck[0]->id }}">
                        审核
                    </a>
                </div>
                <div id="edit_status_check_form_{{ $statusCheck[0]->id }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading_edit_status_check_form">
                    {!! Form::open(array('url' => 'admin/advsetting/review/'.$advsetting->id.'/edit/'.$statusCheck[0]->id, 'class' => 'form', 'method'=>'post')) !!}
                    {!! Form::label('comment', '建议', array('class'=>'col-lg-12 col-md-12 col-sm-12')) !!}
                    <div class="col-md-12">
                        {!! Form::textarea('comment', $statusCheck[0]->comment , array('class'=>'name col-lg-12 col-md-12 col-sm-12', 'placeholder' => '建议', 'rows'=> '3')) !!}
                    </div>
                    {!! Form::text('article_status', $statusName, array('hidden')) !!}
                    <div class="form-check">
                            <label for="status" class="col-lg-2 col-md-2 col-sm-2 form-check-label">
                                <input type="radio" name="status" {{ $statusCheck[0]->checked == $currentStatusId  ? 'checked' : '' }} class="col-lg-2 col-md-2 col-sm-2 form-check-input" value="2" /> {{ $radioLabel1 }}
                            </label>
                            <label for="status" class="col-lg-2 col-md-2 col-sm-2 form-check-label">
                                <input type="radio" name="status" {{ $statusCheck[0]->checked == 1 ? 'checked' : '' }} class="col-lg-2 col-md-2 col-sm-2 form-check-input" value="1" /> {{ $radioLabel2 }}
                            </label>
                    </div>
                    <div class="clearfix"></div>
                    {!! Form::submit('确定', array('class'=>'btn btn-primary col-lg-offset-8 col-md-offset-8 col-sm-offset-8')) !!}
                    {!! Form::token() !!}
                    {!! Form::close() !!}
                </div>
            @endif
        </div>
    @else
        @foreach($statusCheck as $statusCk)
            <div class="{{ $statusCk->checked == $currentStatusId ? 'bs-callout bs-callout-primary' : '' }}" >
                <div class="col-lg-2 col-md-2 col-sm-2">{{ $statusCk->article_status->title }}</div>
                <div class="col-lg-9 col-md-9 col-sm-9">{{ $statusCk->comment }}</div>
                @if($statusCk->created_by == $currentUser->id && $displayForm)
                    <div class="col-lg-1 col-md-1 col-sm-1">
                        <a class="collapsed" data-toggle="collapse" href="#edit_status_check_form_{{ $statusCk->id }}" aria-expanded="false" aria-controls="edit_status_check_form_{{ $statusCk->id }}">
                            审核
                        </a>
                    </div>
                    <div id="edit_status_check_form_{{ $statusCk->id }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading_edit_status_check_form">
                        {!! Form::open(array('url' => 'admin/advsetting/review/'.$advsetting->id.'/edit/'.$statusCk->id, 'class' => 'form', 'method'=>'post')) !!}
                        {!! Form::label('comment', '建议', array('class'=>'col-lg-12 col-md-12 col-sm-12')) !!}
                        {!! Form::textarea('comment', $statusCk->comment , array('class'=>'name col-lg-12 col-md-12 col-sm-12', 'placeholder' => '建议', 'rows'=> '3')) !!}
                        {!! Form::text('article_status', $statusName, array('hidden')) !!}
                        <div class="form-check">
                                <label for="status" class="col-lg-2 col-md-2 col-sm-2 form-check-label">
                                    <input type="radio" name="status" class="col-lg-2 col-md-2 col-sm-2 form-check-input" {{ $statusCk->checked == $currentStatusId ? 'checked' : '' }} value="2" /> {{ $radioLabel1 }}
                                </label>
                                <label for="status" class="col-lg-2 col-md-2 col-sm-2 form-check-label">
                                    <input type="radio" name="status" class="col-lg-2 col-md-2 col-sm-2 form-check-input" {{ ($statusCk->checked == 1 || $statusCk->checked == 0) ? 'checked' : '' }} value="1" /> {{ $radioLabel2 }}
                                </label>
                        </div>
                        {!! Form::submit('确定', array('class'=>'btn btn-primary col-lg-offset-8 col-md-offset-8 col-sm-offset-8')) !!}
                        {!! Form::token() !!}
                        {!! Form::close() !!}
                    </div>
                @endif
            </div>
        @endforeach
    {{--@endif--}}
@endif