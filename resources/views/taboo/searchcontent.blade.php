@if(isset($taboo))
    <table class="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>敏感词</th>
            <th>类别</th>
            <th>编辑</th>
            <th>删除</th>
        </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $taboo->id }}</td>
                <td>{{ $taboo->content }}</td>
                <td>{{ $taboo->category }}</td>
                <td><a class="btn btn-default" href="/admin/taboo/{{ $taboo->id }}/edit" id="editBtn_{{ $taboo->id }}">编辑</a></td>
                <td>
                    {!! Form::open(array('url' => 'admin/taboo/'.$taboo->id, 'class' => 'form', 'method'=>'delete', 'onsubmit'=>'return confirm("确定删除?");')) !!}
                    {!! Form::text('id', $taboo->id, array('hidden'=>'hidden', 'readonly' => true)) !!}
                    {!! Form::submit('删除', array('class'=>'btn btn-danger')) !!}
                    {!! Form::token() !!}
                    {!! Form::close() !!}
                </td>
            </tr>
        </tbody>
    </table>
@else
    <div class="col-lg-12 col-md-12 col-sm-12 clearfix">
        <h4>还没有标签.</h4>
    </div>
@endif