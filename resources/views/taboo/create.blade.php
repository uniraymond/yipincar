@extends('layouts.base')
<link rel="stylesheet" href="{{ asset("/src/css/jquery-ui.min.css") }}" />
@include('layouts.settingSideBar')
@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">编辑敏感词</h1>

                @if ($fail = Session::get('warning'))
                    <div class="col-md-12 bs-example-bg-classes" >
                        <p class="bg-danger">
                            {{ $fail }}
                        </p>
                    </div>
                @endif

                <div class="col-lg-12 col-md-12 col-sm-12">
                    {!! Form::open(array('url' => 'admin/taboo/store', 'class' => 'form', 'method'=>'put')) !!}
                    <div class="form-group  col-lg-12 col-md-12 col-sm-12" >
                        <div class="{{ isset($errors) && $errors->has('cotent') ? 'has-error clearfix' : 'clearfix' }}" style="margin-bottom: 5px" >
                            <label class="col-lg-12 col-md-12 col-sm-12">敏感词</label>
                            <div class="col-md-12">
                                <input class="col-lg-12 col-md-12 col-sm-12 form-control" type="text" id="content" name="content" required />
                                @if ($errors->has('title'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('content') ? '敏感词不能为空' : ''}}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div>
                            <label class="col-lg-12 col-md-12 col-sm-12">类别</label>
                            <div class="col-md-12">
                                <select>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category }}" >{{ $category }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>
                    {!! Form::token() !!}
                    <div class=" col-lg-12 col-md-12 col-sm-12">
                        <input type="submit" id="submit" value="保存" class="btn btn-default" />
                    </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection

<script>
    //autocomplete
    jQuery( function() {
        var availableTaboos = {!! json_encode($categories) !!}
                function split( val ) {
            return val.split( /,\s*/ );
        }
        function extractLast( term ) {
            return split( term ).pop();
        }

        jQuery('#tags').on( "keydown", function( event ) { console.log('click');
            if ( event.keyCode === jQuery.ui.keyCode.TAB &&
                    jQuery( this ).autocomplete( "instance" ).menu.active ) {
                event.preventDefault();
            }
        }).autocomplete({
            minLength: 0,
            source: function( request, response ) {
                // delegate back to autocomplete, but extract the last term
                response( jQuery.ui.autocomplete.filter(
                        availableTags, extractLast( request.term ) ) );
            },
            focus: function() {
                // prevent value inserted on focus
                return false;
            },
            select: function( event, ui ) {
                var terms = split( this.value );
                // remove the current input
                terms.pop();
                // add the selected item
                terms.push( ui.item.value );
                // add placeholder to get the comma-and-space at the end
                terms.push( "" );
                this.value = terms.join( ", " );
                return false;
            }
        });
    } );
</script>