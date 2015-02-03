@extends('layouts.master')

@section('title')
  Suspensions
@stop


@section('body-tag')
<body class="theme-clean main-menu-right page-search">
@stop

@section('page')




<div class="page-header">
    <h1><i class="fa fa-building page-header-icon"></i>&nbsp;&nbsp;SUSPENSIONS</h1>
</div> <!-- / .page-header -->


<!-- Tabs -->
<div class="search-tabs">
    <ul class="nav nav-tabs">

        <li class="active">
            <a href="#suspension-tab-all" data-toggle="tab">All <span class="label label-success">{{ Suspension::current()->count() }}</span></a>
        </li>

@foreach( Suspension::$types as $type => $typeName )
        <li>
            <a href="#suspension-tab-{{ $type }}" data-toggle="tab">{{ $typeName }} <span class="label label-success">{{ Suspension::current()->type($type)->count() }}</span></a>
        </li>
@endforeach

    </ul> <!-- / .nav -->
</div>
<!-- / Tabs -->

<!-- Panel -->
<div class="panel">

<script>
    init.push(function () {
        $('table button').popover();
    });
</script>


<div class="panel-body tab-content">


<!-- ALL tab -->
    <div class="tab-pane fade in active" id="suspension-tab-all">


        <div class="pane-body">


        <div class="row">


{{--
            <div class="panel panel-dark panel-light-green">
                <div class="panel-heading">
                    <span class="panel-title"><i class="panel-title-icon fa fa-smile-o"></i>Suspensions</span>
                    <div class="panel-heading-controls">
<a href="{{ route('note-new', NOTEFLAG_SUSPENSION) }}"><button type="button" class="btn btn-sm btn-success">+ ADD</button></a>
                    </div> <!-- / .panel-heading-controls -->
                </div> <!-- / .panel-heading -->

@include('suspensions.list', ['suspensions' => Suspension::current()->get(), 'showResident' => true])

            </div> <!-- / .panel -->
--}}
        </div>



            @foreach( Suspension::$types as $type => $typeName )

                    <div class="row">



                            <div class="panel panel-dark panel-light-green">
                                <div class="panel-heading">
                                    <span class="panel-title"><i class="panel-title-icon fa fa-smile-o"></i>{{ $typeName }}</span>
                                    <div class="panel-heading-controls">
                                        <a href="{{ route('note-new', NOTEFLAG_SUSPENSION) }}"><button type="button" class="btn btn-sm btn-success">+ ADD</button></a>
                                    </div> <!-- / .panel-heading-controls -->
                                </div> <!-- / .panel-heading -->

                                @include('suspensions.list', ['suspensions' => Suspension::current()->type($type)->get(), 'showResident' => true])

                            </div> <!-- / .panel -->



                    </div>


            @endforeach





        </div>

    </div>
    <!-- / ALL tab -->




@foreach( Suspension::$types as $type => $typeName )
    <div class="tab-pane fade in" id="suspension-tab-{{ $type }}">
        <div class="pane-body">

            <div class="row">



                    <div class="panel panel-dark panel-light-green">
                        <div class="panel-heading">
                            <span class="panel-title"><i class="panel-title-icon fa fa-smile-o"></i>{{ $typeName }}</span>
                            <div class="panel-heading-controls">
                                <a href="{{ route('note-new', NOTEFLAG_SUSPENSION) }}"><button type="button" class="btn btn-sm btn-success">+ ADD</button></a>
                            </div> <!-- / .panel-heading-controls -->
                        </div> <!-- / .panel-heading -->

                            @include('suspensions.list', ['suspensions' => Suspension::current()->type($type)->get(), 'showResident' => true])

                    </div> <!-- / .panel -->


            </div>

        </div>
    </div>
@endforeach






</div>


</div>
<!-- / Panel -->




@stop
