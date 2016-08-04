<div class="panel panel-default">
    <div class="panel-heading">更改图片详情</div>
    <div class="panel-body">
        {!! Form::open(array('url' => 'admin/advsetting/update', 'class' => 'form')) !!}
        {!! Form::label('name', 'Name', array('class'=>'col-md-12')) !!}
        {!! Form::text('Name', $image->name , array('class'=>'name input col-md-12', 'placeholder' => 'Name')) !!}
        {!! Form::label('description', 'Description', array('class'=>'col-md-12')) !!}
        {!! Form::text('Description', $image->description, array('class' => 'description input col-md-12', 'placeholder' => 'Description')) !!}
        {!! Form::submit('确定', array('class'=>'btn btn-primary')) !!}
        {!! Form::token() !!}
        {!! Form::close() !!}
    </div>
</div>