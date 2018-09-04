<?php
$url='http://api.jisuapi.com/car/brand?appkey=197a3b832c400da4';
$html = file_get_contents($url);
echo $html;
?>
{{--<script src="{{ url('/src/js/jQuery.min.2.2.4.js') }}" ></script>--}}
{{--<script language="JavaScript">--}}
    {{--$(document).ready(function () {--}}
        {{--$.get("http://api.jisuapi.com/car/brand?appkey=197a3b832c400da4", function(res) {--}}
            {{--console.log(res);--}}
        {{--});--}}
    {{--})--}}
    {{--</script>--}}