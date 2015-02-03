@extends('layouts.master')

@section('title')
  Strikes
@stop


@section('body-tag')
<body class="theme-clean main-menu-right page-search">
@stop

@section('page')




<div class="page-header">
    <h1><i class="fa fa-building page-header-icon"></i>&nbsp;&nbsp;STRIKES</h1>
</div> <!-- / .page-header -->




<!-- Panel -->
<div class="panel">

<script>
    init.push(function () {
        $('table button').popover();
    });
</script>


<div class="panel-body">

        <div class="pane-body">


        <div class="row">


            <div class="col-md-12">
                <div class="panel panel-dark panel-danger">
                    <div class="panel-heading">
                        <span class="panel-title"><i class="panel-title-icon fa {{ Note::$flagIcon[NOTEFLAG_STRIKE] }}"></i>Strikes</span>
                        <div class="panel-heading-controls">
                            <a href="{{ route('note-new', NOTEFLAG_STRIKE) }}"><button type="button" class="btn btn-sm btn-success">+ ADD</button></a>
                        </div> <!-- / .panel-heading-controls -->
                    </div> <!-- / .panel-heading -->
@include('strikes.list', ['strikes' => Strike::current()->get(), 'showResident' => true ])

                </div> <!-- / .panel -->
            </div>
            <!-- /12. $NEW_USERS_TABLE -->

            <!-- 13. $RECENT_TASKS =============================================================================

                        Recent tasks
            -->

<?php /*
            <div class="col-md-5">
                <div class="panel widget-tasks panel-dark-gray">
                    <div class="panel-heading">
                        <span class="panel-title"><i class="panel-title-icon fa fa-tasks"></i>Recent tasks</span>
                        <div class="panel-heading-controls">
                            <button class="btn btn-xs btn-primary btn-outline dark" ><i class="fa fa-eraser text-success"></i> bleh</button>
                        </div>
                    </div> <!-- / .panel-heading -->
                    <?php /*
                <!-- Without vertical padding -->
                <div class="panel-body no-padding-vr ui-sortable">
                </div> <!-- / .panel-body -->
                </div> <!-- / .panel -->
            </div>
            <!-- /13. $RECENT_TASKS -->
*/ ?>
        </div>


        @if(Auth::user()->can('warnings.view'))
        <div class="row">


            <div class="col-md-12">
                <div class="panel panel-dark panel-light-green widget-comments" id="warnings-list">
                    <div class="panel-heading">
                        <span class="panel-title"><i class="panel-title-icon fa fa-smile-o"></i>Warnings</span>
                        <div class="panel-heading-controls">
                            <a href="{{ route('note-new', NOTEFLAG_WARNING) }}"><button type="button" class="btn btn-sm btn-success">+ ADD</button></a>
                        </div> <!-- / .panel-heading-controls -->
                    </div> <!-- / .panel-heading -->


                    @include('notes.list', array('notes' => Note::ofType(NOTETYPE_DETAIL)->withFlag(NOTEFLAG_WARNING)->get()))


                </div> <!-- / .panel -->
            </div>



        </div>
        @endif


        </div>




</div>


</div>
<!-- / Panel -->




@stop