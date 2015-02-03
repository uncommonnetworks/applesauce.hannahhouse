@extends('layouts.master')



@section('body-tag')
<body class="theme-clean main-menu-right">
@stop

@section('page')




<div class="page-header">

    <div class="row">
        <!-- Page header, center on small screens -->
        <h1 class="col-xs-12 col-sm-3 text-left-sm"><i class="fa fa-dashboard page-header-icon"></i>&nbsp;&nbsp;Dashboard</h1>

        <h1 class="col-xs-12 col-sm-3 text-left-sm">
            <a href="/search#search-tab-current">
            <i class="fa fa-list page-header-icon"></i>&nbsp;&nbsp;Current Residents
            </a>
        </h1>
        <div class="col-xs-12 col-sm-6">
            <div class="row">
                <hr class="visible-xs no-grid-gutter-h">
                <!-- "Create project" button, width=auto on desktops -->
                <div class="pull-right col-xs-12 col-sm-auto">

                    <div class="btn-group">

                        <button type="button" class="btn btn-primary btn-labeled dropdown-toggle" data-toggle="dropdown">
                            <span class="btn-label icon fa fa-plus"></span> Note &nbsp;
                            <i class="fa fa-caret-down"></i>
                        </button>
                        <ul class="dropdown-menu">
                            @foreach( Note::$flags as $flag => $flagName )
                            <li><a href="{{ route('note-new', $flag) }}"><i class="fa fa-plus-square"></i> {{ $flagName }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Margin -->
                <div class="visible-xs clearfix form-group-margin"></div>

                <!-- Search field --
                <form action="" class="pull-right col-xs-12 col-sm-6">
                    <div class="input-group no-margin">
                        <span class="input-group-addon" style="border:none;background: #fff;background: rgba(0,0,0,.05);"><i class="fa fa-search"></i></span>
                        <input type="text" placeholder="Search..." class="form-control no-padding-hr" style="border:none;background: #fff;background: rgba(0,0,0,.05);">
                    </div>
                </form>
                -->
            </div>
        </div>
    </div>
</div> <!-- / .page-header -->


<div class="row">


    <div class="col-md-4">
        <div class="row">

            <div class="col-sm-4 col-md-12">
                <div class="row"><div class="col-sm-6">
                <div class="stat-panel ">
                    <!-- Danger background, vertically centered text -->
                    <div class="stat-cell bg-info valign-middle">
                        <!-- Stat panel bg icon -->
                        <i class="fa fa-users bg-icon"></i>
                        <!-- Extra large text -->
                        <span class="text-xlg">
                            <strong>{{ Resident::current()->male()->count() }}</strong>&nbsp;<span class="text-lg text-slim">men</span>
                            <strong>{{ Resident::current()->female()->count() }}</strong>&nbsp;<span class="text-lg text-slim">women</span>
                        </span>

                        <br>
                        <!-- Big text -->
                        <span class="text-bg">Current Residents</span><br>

                    </div> <!-- /.stat-cell -->
                </div> <!-- /.stat-panel -->
                </div>
                <div class="col-sm-6">
                <div class="stat-panel ">
                    <!-- Danger background, vertically centered text -->
                    <div class="stat-cell bg-info valign-middle">
                        <!-- Stat panel bg icon -->
                        <i class="fa fa-building bg-icon"></i>
                        <!-- Extra large text -->
                        <span class="text-xlg">
                            <strong>{{ Bed::vacant()->men()->count() }}</strong>&nbsp;<span class="text-lg text-slim">men</span>
                            <strong>{{ Bed::vacant()->women()->count() }}</strong>&nbsp;<span class="text-lg text-slim">women</span>
                        </span>

                        <br>
                        <!-- Big text -->
                        <span class="text-bg">Available Beds</span><br>

                    </div> <!-- /.stat-cell -->
                </div> <!-- /.stat-panel -->
                </div></div>

            </div>

            <div class="col-sm-4 col-md-12">


                <div class="stat-panel" >
                    <div class="stat-row">
                        <!-- Purple background, small padding -->
                        <div class="stat-cell bg-pa-purple padding-sm">
                            <!-- Extra small text -->
                            <div class="pull-right text-xs">
                                <a href="#" id="shiftDate">{{ $shifts->date }}</a>
                            </div>
                            <div class="text-xs" style="margin-bottom: 5px;">RSM STAFF FOR
                            <strong data-type="date" data-name="updated_at" id="shift-updated-date">{{ $shifts->updated_at->toFormattedDateString() }}</strong>
                            </div>
                        </div>
                    </div> <!-- /.stat-row -->
                    <div class="stat-row"  id="shifts-div" style="overflow:visible !important;">
                        <!-- Bordered, without top border, horizontally centered text -->
                        <div class="stat-counters bordered no-border-t text-center">

                            <div class="stat-cell col-xs-6 padding-sm no-padding-hr">

                                <span class=""><strong id="shift73" data-name="shift73" data-type="select2" data-value="{{$shifts->shift73}}">{{ User::find($shifts->shift73) }}</strong></span><br>

                                <span class="text-xs text-muted">7:00a - 3:30p</span>
                            </div>

                            <div class="stat-cell col-xs-6 padding-sm no-padding-hr">

                                <span class=""><strong id="shift31130" data-value="{{$shifts->shift31130}}">{{ User::find($shifts->shift31130) }}</strong></span><br>

                                <span class="text-xs text-muted">3:00p - 11:30p</span>
                            </div>
                        </div>

                        <div class="stat-counters bordered no-border-t text-center">

                            <div class="stat-cell col-xs-6 padding-sm no-padding-hr">

                                <span class=""><strong id="shift612" data-value="{{$shifts->shift612}}">{{ User::find($shifts->shift612) }}</strong></span><br>

                                <span class="text-xs text-muted">6:00p - 12:00a</span>
                            </div>

                            <div class="stat-cell col-xs-6 padding-sm no-padding-hr">

                                <span class=""><strong id="shift11308" data-value="{{$shifts->shift11308}}">{{ User::find($shifts->shift11308) }}</strong></span><br>

                                <span class="text-xs text-muted">11:30p - 8:00a</span>
                            </div>
                        </div> <!-- /.stat-counters -->
                    </div> <!-- /.stat-row -->
                </div> <!-- /.stat-panel -->
            </div>

        </div>
    </div>


    <!-- 11. $RECENT_ACTIVITY ==========================================================================

                Recent activity
    -->
    <!-- Javascript -->
    <script>
        init.push(function () {
            $('#dashboard-recent .panel-body > div').slimScroll({ height: 292, alwaysVisible: true, color: '#888',allowPageScroll: true });
        })
    </script>
    <!-- / Javascript -->

    <div class="col-md-8">


        <div class="panel panel-warning" id="dashboard-recent">
            <div class="panel-heading">
                <span class="panel-title"><i class="panel-title-icon fa fa-fire text-danger"></i>Current Residents</span>
                <ul class="nav nav-tabs nav-tabs-xs">
                    <li class="active">
                        <a href="#dashboard-recent-comments" data-toggle="tab">Recently Arrived</a>
                    </li>
                    <li>
                        <a href="#dashboard-owp-comments" data-toggle="tab">OWP</a>
                    </li>
                    <li>
                        <a href="#dashboard-recent-threads" data-toggle="tab">Recently Departed</a>
                    </li>
                    <li class="dropdown">
                        <a href="#dashboard-residents" data-toggle="dropdown">
                            Select a Resident
                            <i class="fa fa-caret-down"></i>
                        </a>
                        <ul class="dropdown-menu">

                            @foreach(Resident::current()->get() as $resident)
                            <li>
                                <a href="#dashboard-resident-{{ $resident->id }}" data-toggle="tab">{{ $resident->display_name }}</a>
                            </li>
                            @endforeach
                        </ul>
                    </li>
                </ul>
            </div> <!-- / .panel-heading -->
            <div class="tab-content">

                <!-- Comments widget -->

                <!-- Without padding -->
                <div class="widget-comments panel-body tab-pane no-padding fade active in" id="dashboard-recent-comments">
                    <!-- Panel padding, without vertical padding -->
                    <div class="panel-padding no-padding-vr">

                        @include('resident.dashboard-list', array('residents' => Resident::current()->recent()->get()) )

                    </div>
                </div> <!-- / .widget-comments -->



                <!-- Without padding -->
                <div class="widget-comments panel-body tab-pane no-padding fade in" id="dashboard-owp-comments">
                    <!-- Panel padding, without vertical padding -->
                    <div class="panel-padding no-padding-vr">


                        @include('resident.dashboard-list', array('residents' => Resident::owp()->get()) )

                    </div>
                </div> <!-- / .widget-comments -->

                <!-- Threads widget -->

                <!-- Without padding -->
                <div class="widget-comments panel-body tab-pane no-padding fade" id="dashboard-recent-threads">
                    <div class="panel-padding no-padding-vr">

                        @include('resident.dashboard-list', array('residents' => Resident::former()->recent()->get()) )


                    </div>
                </div> <!-- / .panel-body -->


                @foreach( Resident::current()->get() as $resident )
                <div class="widget-resident panel-body tab-pane no-padding fade" id="dashboard-resident-{{ $resident->id }}">
                    <div class="panel-padding no-padding-vr">
                        <div class="row">

                        <div class="col-lg-4">
                        <a href="{{ route('resident', $resident->id) }}">{{ $resident->getThumbImg() }}</a>
                        <h2><a href="{{ route('resident', $resident->id) }}">{{ $resident->display_name }}</a>
                        </h2>
                            @if($resident->is_wanted)
                                <span class="badge badge-danger">wanted</span>
                            @endif
                            <em>{{ $resident->date_of_birth }}</em>
                            <span class="badge-default badge">{{ $resident->age }}</span>
                        </div>

                        <div class="col-lg-4">
                            @if( $resident->sin )
                            <div class="list-group">
                                <strong>SIN</strong> <button type="button" class="btn btn-outline" data-toggle="modal" data-target="#viewsin">{{ $resident->sin }}</button>

                                <div id="viewsin" class="modal fade" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                <h4 class="modal-title">{{ $resident->display_name }}: SIN CARD</h4>
                                            </div>
                                            <div class="modal-body">
                                                @if($resident->idSin)
                                                    {{ $resident->idSin->getPictureImg(IDENTIFICATION_BIG) }}
                                                @endif
                                            </div>
                                        </div> <!-- / .modal-content -->
                                    </div> <!-- / .modal-dialog -->
                                </div>
                            </div>
                            @endif

                            @if( $resident->health_card_number )
                            <div class="list-group">
                                <strong>Health</strong> <button type="button" class="btn btn-outline" data-toggle="modal" data-target="#viewhealth">{{ $resident->health_card_number }}</button>

                                <div id="viewhealth" class="modal fade" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                <h4 class="modal-title">{{ $resident->display_name }}: HEALTH CARD</h4>
                                            </div>
                                            <div class="modal-body">
                                                @if($resident->idHealth)
                                                {{ $resident->idHealth->getPictureImg(IDENTIFICATION_BIG) }}
                                                @endif
                                            </div>
                                        </div> <!-- / .modal-content -->
                                    </div> <!-- / .modal-dialog -->
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="col-lg-4">

                            @if( $resident->contact_name )




                                <div class="well well-sm" id="show-contact-div">
                                    <h6 class="badge">emergency contact</h6>
                                    <h4>
                                        {{ $resident->contact_name }}
                                        @if( isset($resident->relationship) )
                                        ({{ $resident->relationship }})
                                        @endif
                                    </h4>
                                    <p>
                                        @if($resident->contact_street1)
                                        {{ $resident->contact_street1 }}<br />
                                        @endif
                                        @if($resident->contact_street2)
                                        {{ $resident->contact_street2 }}<br />
                                        @endif
                                        @if($resident->contact_city)
                                        {{{ $resident->contact_city }}}<br />
                                        @endif
                                    </p>
                                    @if($resident->contact_phone)
                                    <span class="label">{{ $resident->contact_phone }}</span>
                                    @endif
                                </div>

                                <div id="edit-contact-div" style="display: none">
                                    <h4>
                                        <a id="edit-contact_name" class="editable-click" href="#" data-name="contact_name">{{ $resident->contact_name }}</a>
                                    </h4>
                                    <p>
                                        <a id="edit-contact_street1" class="editable-click" href="#" data-name="contact_street1">{{ $resident->contact_street1 }}</a><br />
                                        <a id="edit-contact_street2" class="editable-click" href="#" data-name="contact_street2">{{ $resident->contact_street2 }}</a><br />
                                        <a id="edit-contact_city" class="editable-click" href="#" data-name="contact_city">{{ $resident->contact_city }}</a><br />
                                        <a id="edit-contact_phone" class="editable-click" href="#" data-name="contact_phone">{{ $resident->contact_phone }}</a>
                                    </p>

                                </div>




                            @endif


                        </div>
                        </div>
                        <div class="widget-profile-text stat-cell darker  panel-danger" style="padding: 0;">
                            @if(Auth::user()->can('notes.detail-view'))
                            {{{ $onenote = $resident->notes()->first() }}}
                            <span class="pull-right"><img class="comment-avatar" src="{{ $onenote->author->initialUrl }}" /></span>
                            @endif


                        </div>
                    </div>
                </div>
                @endforeach


            </div>
        </div> <!-- / .widget-threads -->
    </div>
    <!-- /11. $RECENT_ACTIVITY -->


</div>
<hr class="no-grid-gutter-h grid-gutter-margin-b no-margin-t">

<div class="row">

<!-- 10. $SUPPORT_TICKETS ==========================================================================

			Support tickets
-->
<!-- Javascript
<script>
    init.push(function () {
        $('#dashboard-support-tickets .panel-body > div').slimScroll({ height: 300, alwaysVisible: true, color: '#888',allowPageScroll: true });
    })
</script>
<!-- / Javascript -->

<div class="col-md-12">





    <div class="panel panel-default widget-comments" id="dashboard-support-tickets">
        <div class="panel-heading">
            <span class="panel-title"><i class="panel-title-icon fa fa-bullhorn"></i></span>

            <div class="panel-heading-controls">

                <!-- Tabs -->
                <ul class="nav nav-tabs bs-tabdrop">
                    <li class="active"><a href="#bs-tabdrop-all" data-toggle="tab">All Notes</a></li>
                    @foreach( Note::$flags as $flag => $flagName )
                    <li><a href="#bs-tabdrop-{{ $flag }}" data-toggle="tab">{{ $flagName }}</a></li>
                    @endforeach
                </ul>

            </div>
        </div> <!-- / .panel-heading -->



        <div class="tab-content tab-content-bordered">
            <div class="tab-pane active" id="bs-tabdrop-all">
                @include('notes.list', ['notes' => Note::ofType(NOTETYPE_SHIFT)->get()])
            </div>
            @foreach( Note::$flags as $flag => $flagName )
            <div class="tab-pane" id="bs-tabdrop-{{ $flag }}">
                @include('notes.list', ['notes' => Note::ofType(NOTETYPE_SHIFT)->withFlag($flag)->get()])
            </div>
            @endforeach
        </div>




    </div> <!-- / .panel -->
</div>
<!-- /10. $SUPPORT_TICKETS -->


</div>

@stop


@section('footer-scripts')



<script type="text/javascript">

    init.push(function () {


        $('ul.bs-tabdrop').tabdrop();


        var staff = [];
        @foreach( User::all() as $staff )
        staff.push({id: "{{ $staff->id }}", text: "{{ $staff->name }}"});
        @endforeach

        $('#shift-updated-date').editable({
            ajaxOptions: { 'type': 'POST' },
            url: '/shifts/edit/1',
            pk: "1",
            placement: 'right',
            container: 'body',
            type: 'date',
            datepicker: { autoclose: true, clearBtn: false },
            success: function(response, newValue) {
                if(response.success)
                    $()
                else
                    return response.msg;
            }
        });

        $('#shifts-div').editable({
            ajaxOptions: { 'type': 'POST' },
            selector: 'strong',
            url: '/shifts/edit/1',
            pk: "1",
            placement: 'right',
            container: 'body',
//            mode: 'inline',
            type: 'select2',
            source: staff,
            success: function(response, newValue) {
                if(response.success)
                    $()
                else
                    return response.msg;
            }
        });
    });

setInterval( function(){
$("#notes-dashboard").load("/notes/dashboard")
}, 20000 );








</script>

@stop
