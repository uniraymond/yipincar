<?php
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Resource Upload</div>
                    <div class="panel-body">
                        <form action="resource/upload" method="post">
                            <label>Choose file</label>
                            <input type="file" id="file" class="file" />
                            <input type="submit" id="submit" value="Upload" />
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
@section