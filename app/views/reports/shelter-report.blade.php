@extends('layouts.master')


@section('body-tag')
<body class="theme-clean main-menu-right page-search">
@stop

@section('page')
<div id="content-wrapper">

<div class="page-header bg-info">
    <h1><i class="fa fa-list-ol page-header-icon"></i>&nbsp;&nbsp;Shelter Usage by Year</h1>
</div> <!-- / .page-header -->


<!-- Panel -->
<div class="panel search-panel">


{{ Form::open(['action' => 'ReportsController@postShelterReport', 'class' => 'search-form']) }}
{{--{{ Form::hidden('year', date('Y')) }}--}}

<div class="input-group input-group-lg">




<span class="input-group-btn">

    @for( $year = Config::get('world.firstYear'); $year <= intval(date('Y')); $year++ )
    <button type="submit" class="btn btn-primary btn-flat btn-xs" name="year" value="{{ $year }}">{{ $year }}</button> &nbsp;
    @endfor
</span>
</div> <!-- / .input-group -->

{{ Form::close() }}

</div>



</div>
<!-- / Panel -->

</div>


@stop