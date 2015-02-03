@extends('layouts.master')


@section('body-tag')
<body class="theme-clean main-menu-right page-search">
@stop

@section('head-tags')

@stop

@section('page')




<div class="page-header">
    <h1><i class="fa fa-building page-header-icon"></i>&nbsp;&nbsp;BEDDING</h1>
</div> <!-- / .page-header -->


<!-- Tabs -->
<div class="search-tabs">
    <ul class="nav nav-tabs">

        <li class="active">
            <a href="#bedding-tab-all" data-toggle="tab">All <span class="label label-success">{{ Bed::vacant()->count() }}  / {{ Bed::count() }}</span></a>
        </li>
        <li>
            <a href="#bedding-tab-men" data-toggle="tab">Male <span class="label label-success">{{ Bed::vacant()->men()->count() }}  / {{ Bed::men()->count() }}</span></a>
        </li>
        <li>
            <a href="#bedding-tab-women" data-toggle="tab">Female <span class="label label-success">{{ Bed::vacant()->women()->count() }} / {{ Bed::women()->count() }}</span></a>
        </li>

    </ul> <!-- / .nav -->
</div>
<!-- / Tabs -->

<!-- Panel -->
<div class="panel">




<div class="panel-body tab-content">


<!-- ALL tab -->
    <div class="tab-pane fade in active" id="bedding-tab-all">


        <div class="pane-body">


@foreach( $rooms = Bedroom::active()->ordered()->get() as $i => $bedroom )

{{-- new row every 3 rooms --}}
    @if( $i % 3 == 0 )
            <div class="row">
    @endif

            <div class="col-sm-4">


<a name="bedroom{{ $bedroom->id }}"></a>

                <div class="stat-panel">
                    <div class="stat-row">
                        <!-- Success darker background -->
                        <div class="stat-cell  darker">
                            <!-- Stat panel bg icon -->
                            <i class="fa fa-{{ $bedroom->gender }} bg-icon" style="font-size:60px;line-height:80px;height:80px;"></i>
                            <!-- Big text -->
                            <span class="text-bg">{{{ $bedroom->name }}}</span><br>
{{--                            <!-- Small text -->
                            <span class="text-sm">{{{ $bedroom->id }}}</span>
                            --}}
                        </div>
                    </div> <!-- /.stat-row -->



                    <div class="stat-row">
                        <!-- Success background, without bottom border, without padding, horizontally centered text -->
                        <div class="stat-counters bg-default no-padding text-center">
                            <!-- Small padding, without horizontal padding -->
                            <div class="stat-cell col-xs-4 padding-sm no-padding-hr">
                                <!-- Big text -->
                                <span class="text-bg"><strong>{{ $bedroom->occupied->count() }}</strong> / {{ $bedroom->beds->count() }}</span><br>
                                <!-- Extra small text -->
                                <span class="text-xs">{{ Bed::$states[ BEDSTATUS_OCCUPIED ] }}</span>
                            </div>
                            <!-- Small padding, without horizontal padding -->
                            <div class="stat-cell col-xs-4 padding-sm no-padding-hr">
                                <!-- Big text -->
                                <span class="text-bg"><strong>{{ $bedroom->notready->count() }}</strong></span><br>
                                <!-- Extra small text -->
                                <span class="text-xs">{{ Bed::$states[ BEDSTATUS_NOTREADY ] }}</span>
                            </div>
                            <!-- Small padding, without horizontal padding -->
                            <div class="stat-cell col-xs-4 padding-sm no-padding-hr">
                                <!-- Big text -->
                                <span class="text-bg"><strong>{{ $bedroom->vacant->count() }}</strong></span><br>
                                <!-- Extra small text -->
                                <span class="text-xs">{{ Bed::$states[ BEDSTATUS_VACANT ] }}</span>
                            </div>
                        </div> <!-- /.stat-counters -->
                    </div> <!-- /.stat-row -->

    @foreach( $bedroom->beds as $bed )


        <?php
            $background = $bed->resident && $bed->resident->status == RESIDENTSTATUS_OWP ?
                Resident::$stateBackgroundClass[ RESIDENTSTATUS_OWP ] :
                Bed::$stateBackgroundClass[ $bed->status ];
?>
                    <div class="stat-row">

                        <div class="stat-counters bordered  no-padding text-center">
                            <!-- Small padding, without horizontal padding -->
                            <div class="stat-cell col-xs-1 padding-sm no-padding-hr  {{ $background }}">
                                <!-- Big text -->
                                <button class="btn btn-small " data-toggle="modal" data-target="#bedhistory-{{ $bed->id }}">{{ $bed->name }}</button>

        @if( $bed->status == BEDSTATUS_VACANT )
                                <a href="{{ route('intake-begin') }}?bed={{ $bed->id }}" title="Begin Intake" class="btn btn-success btn-xs"><i class="fa fa-sign-in"></i></a>

        @endif
                                <br /><span class="text-bg">

                                        @if( $bed->resident )
                                            <strong><a href="{{ route('resident',$bed->resident->id) }}">{{ $bed->resident->display_name }}</a></strong>
                                        @endif
                                </span><br />
                                <!-- Extra small text -->
                                <span class="text-xs">{{ $bed->status }}</span>
                            </div>
                        </div> <!-- /.stat-counters -->

                        @if( $bed->divider )

                        <hr />
                        @endif

                    </div> <!-- /.stat-row -->





    @endforeach
                </div> <!-- /.stat-panel -->
                <!-- /11. $EXAMPLE_ACCOUNT_OVERVIEW -->


            </div> <!-- /. col-4 -->


    @if( $i % 3 == 2 || $i == count($rooms)-1)
        </div>
    @endif

@endforeach

        </div>

    </div>
    <!-- / ALL tab -->



    <!-- MALE tab -->

    <div class="tab-pane fade in" id="bedding-tab-men">


        <div class="pane-body">




            @foreach( $rooms = Bedroom::active()->male()->ordered()->get() as $i => $bedroom )
            {{-- new row every 3 rooms --}}
            @if( $i % 3 == 0 )
            <div class="row">
                @endif

                <div class="col-sm-4">


                    <a name="bedroom{{ $bedroom->id }}"></a>

                    <div class="stat-panel">
                        <div class="stat-row">
                            <!-- Success darker background -->
                            <div class="stat-cell  darker">
                                <!-- Stat panel bg icon -->
                                <i class="fa fa-{{ $bedroom->gender }} bg-icon" style="font-size:60px;line-height:80px;height:80px;"></i>
                                <!-- Big text -->
                                <span class="text-bg">{{{ $bedroom->name }}}</span><br>
                                {{--                            <!-- Small text -->
                                <span class="text-sm">{{{ $bedroom->id }}}</span>
                                --}}
                            </div>
                        </div> <!-- /.stat-row -->



                        <div class="stat-row">
                            <!-- Success background, without bottom border, without padding, horizontally centered text -->
                            <div class="stat-counters bg-default no-padding text-center">
                                <!-- Small padding, without horizontal padding -->
                                <div class="stat-cell col-xs-4 padding-sm no-padding-hr">
                                    <!-- Big text -->
                                    <span class="text-bg"><strong>{{ $bedroom->occupied->count() }}</strong> / {{ $bedroom->beds->count() }}</span><br>
                                    <!-- Extra small text -->
                                    <span class="text-xs">{{ Bed::$states[ BEDSTATUS_OCCUPIED ] }}</span>
                                </div>
                                <!-- Small padding, without horizontal padding -->
                                <div class="stat-cell col-xs-4 padding-sm no-padding-hr">
                                    <!-- Big text -->
                                    <span class="text-bg"><strong>{{ $bedroom->notready->count() }}</strong></span><br>
                                    <!-- Extra small text -->
                                    <span class="text-xs">{{ Bed::$states[ BEDSTATUS_NOTREADY ] }}</span>
                                </div>
                                <!-- Small padding, without horizontal padding -->
                                <div class="stat-cell col-xs-4 padding-sm no-padding-hr">
                                    <!-- Big text -->
                                    <span class="text-bg"><strong>{{ $bedroom->vacant->count() }}</strong></span><br>
                                    <!-- Extra small text -->
                                    <span class="text-xs">{{ Bed::$states[ BEDSTATUS_VACANT ] }}</span>
                                </div>
                            </div> <!-- /.stat-counters -->
                        </div> <!-- /.stat-row -->

                        @foreach( $bedroom->beds as $bed )


                        <?php
                        $background = $bed->resident && $bed->resident->status == RESIDENTSTATUS_OWP ?
                            Resident::$stateBackgroundClass[ RESIDENTSTATUS_OWP ] :
                            Bed::$stateBackgroundClass[ $bed->status ];
                        ?>
                        <div class="stat-row">

                            <div class="stat-counters bordered  no-padding text-center">
                                <!-- Small padding, without horizontal padding -->
                                <div class="stat-cell col-xs-1 padding-sm no-padding-hr  {{ $background }}">
                                    <!-- Big text -->
                                    <button class="btn btn-small " data-toggle="modal" data-target="#bedhistory-{{ $bed->id }}">{{ $bed->name }}</button>

                                    @if( $bed->status == BEDSTATUS_VACANT )
                                    <a href="{{ route('intake-begin') }}?bed={{ $bed->id }}" title="Begin Intake" class="btn btn-success btn-xs"><i class="fa fa-sign-in"></i></a>

                                    @endif
                                    <br /><span class="text-bg">

                                        @if( $bed->resident )
                                            <strong><a href="{{ route('resident',$bed->resident->id) }}">{{ $bed->resident->display_name }}</a></strong>
                                        @endif
                                </span><br />
                                    <!-- Extra small text -->
                                    <span class="text-xs">{{ $bed->status }}</span>
                                </div>
                            </div> <!-- /.stat-counters -->

                            @if( $bed->divider )

                            <hr />
                            @endif

                        </div> <!-- /.stat-row -->





                        @endforeach
                    </div> <!-- /.stat-panel -->
                    <!-- /11. $EXAMPLE_ACCOUNT_OVERVIEW -->


                </div> <!-- /. col-4 -->


                @if( $i % 3 == 2 || $i == count($rooms)-1)
            </div>
            @endif

            @endforeach
        </div>

    </div>



    <!-- FEMALE tab -->

    <div class="tab-pane fade in" id="bedding-tab-women">


        <div class="pane-body">



            @foreach( $rooms = Bedroom::female()->ordered()->get() as $i => $bedroom )

            {{-- new row every 3 rooms --}}
            @if( $i % 3 == 0 )
            <div class="row">
                @endif

                <div class="col-sm-4">


                    <a name="bedroom{{ $bedroom->id }}"></a>

                    <div class="stat-panel">
                        <div class="stat-row">
                            <!-- Success darker background -->
                            <div class="stat-cell  darker">
                                <!-- Stat panel bg icon -->
                                <i class="fa fa-{{ $bedroom->gender }} bg-icon" style="font-size:60px;line-height:80px;height:80px;"></i>
                                <!-- Big text -->
                                <span class="text-bg">{{{ $bedroom->name }}}</span><br>
                                {{--                            <!-- Small text -->
                                <span class="text-sm">{{{ $bedroom->id }}}</span>
                                --}}
                            </div>
                        </div> <!-- /.stat-row -->



                        <div class="stat-row">
                            <!-- Success background, without bottom border, without padding, horizontally centered text -->
                            <div class="stat-counters bg-default no-padding text-center">
                                <!-- Small padding, without horizontal padding -->
                                <div class="stat-cell col-xs-4 padding-sm no-padding-hr">
                                    <!-- Big text -->
                                    <span class="text-bg"><strong>{{ $bedroom->occupied->count() }}</strong> / {{ $bedroom->beds->count() }}</span><br>
                                    <!-- Extra small text -->
                                    <span class="text-xs">{{ Bed::$states[ BEDSTATUS_OCCUPIED ] }}</span>
                                </div>
                                <!-- Small padding, without horizontal padding -->
                                <div class="stat-cell col-xs-4 padding-sm no-padding-hr">
                                    <!-- Big text -->
                                    <span class="text-bg"><strong>{{ $bedroom->notready->count() }}</strong></span><br>
                                    <!-- Extra small text -->
                                    <span class="text-xs">{{ Bed::$states[ BEDSTATUS_NOTREADY ] }}</span>
                                </div>
                                <!-- Small padding, without horizontal padding -->
                                <div class="stat-cell col-xs-4 padding-sm no-padding-hr">
                                    <!-- Big text -->
                                    <span class="text-bg"><strong>{{ $bedroom->vacant->count() }}</strong></span><br>
                                    <!-- Extra small text -->
                                    <span class="text-xs">{{ Bed::$states[ BEDSTATUS_VACANT ] }}</span>
                                </div>
                            </div> <!-- /.stat-counters -->
                        </div> <!-- /.stat-row -->

                        @foreach( $bedroom->beds as $bed )


                        <?php
                        $background = $bed->resident && $bed->resident->status == RESIDENTSTATUS_OWP ?
                            Resident::$stateBackgroundClass[ RESIDENTSTATUS_OWP ] :
                            Bed::$stateBackgroundClass[ $bed->status ];
                        ?>
                        <div class="stat-row">

                            <div class="stat-counters bordered  no-padding text-center">
                                <!-- Small padding, without horizontal padding -->
                                <div class="stat-cell col-xs-1 padding-sm no-padding-hr  {{ $background }}">
                                    <!-- Big text -->
                                    <button class="btn btn-small " data-toggle="modal" data-target="#bedhistory-{{ $bed->id }}">{{ $bed->name }}</button>

                                    @if( $bed->status == BEDSTATUS_VACANT )
                                    <a href="{{ route('intake-begin') }}?bed={{ $bed->id }}" title="Begin Intake" class="btn btn-success btn-xs"><i class="fa fa-sign-in"></i></a>

                                    @endif
                                    <br /><span class="text-bg">

                                        @if( $bed->resident )
                                            <strong><a href="{{ route('resident',$bed->resident->id) }}">{{ $bed->resident->display_name }}</a></strong>
                                        @endif
                                </span><br />
                                    <!-- Extra small text -->
                                    <span class="text-xs">{{ $bed->status }}</span>
                                </div>
                            </div> <!-- /.stat-counters -->

                            @if( $bed->divider )

                            <hr />
                            @endif

                        </div> <!-- /.stat-row -->





                        @endforeach
                    </div> <!-- /.stat-panel -->
                    <!-- /11. $EXAMPLE_ACCOUNT_OVERVIEW -->


                </div> <!-- /. col-4 -->


                @if( $i % 3 == 2 || $i == count($rooms)-1)
            </div>
            @endif

            @endforeach

        </div>

    </div>




</div>


</div>
<!-- / Panel -->


{{--
<script type="text/javascript">
    init.push(function() {
        $('.bedhistoryline').sparkline('html', {
            type: 'tristate',
            tooltipValueLookups: {
                'offset': {
                    0: '{{ RESIDENTSTATUS_FORMER }}',
                    -1: '{{ RESIDENTSTATUS_SUSPENDED }}',
                    1: '{{ RESIDENTSTATUS_CURRENT }}'
                }
            }
//            disableHiddenCheck: true
        });
    });

</script>
--}}


<?php /*
@foreach( Bed::vacant()->get() as $bed )

{{--
    <script type="text/javascript">
        init.push(function() {


            $('#bedhistory-{{ $bed->id }}').on('shown.bs.modal', function(){
                $.sparkline_display_visible();
            });
        });
    </script>
--}}


<div id="bedhistory-{{ $bed->id}}" class="modal modal-alert modal-info fade bedhistorydiv" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <i class="fa fa-book"></i>
            </div>
            <div class="modal-title">Bed History: {{ $bed->name }}</div>
            <div class="modal-body">

<?php


$resident = '';
$status = '';
$fromDate = '';
$toDate = '';
$last = '';

foreach( $bed->bed_history as $history )
{
    $last = $history;

    if( $history->status == $status && $history->resident == $resident )
        continue;
    else
    {
        if( $toDate )
        {
            $fromDate = $history->nightDate;

            $toDateC = Carbon\Carbon::parse($toDate)->subDay();
            $fromDateC = Carbon\Carbon::parse($fromDate);

            if( $toDateC->eq($fromDateC) )
                $row = "{$toDate}: ";
            else
                $row = "{$fromDate} &mdash; " . $toDateC->toDateString() . ': ';

            if( $status == BEDSTATUS_OCCUPIED )
                $row .= $resident;
            else
                $row .= Bed::$states[ $status ];

            echo "$row<br />";
        }

        $resident = $history->resident;
        $status = $history->status;
        $toDate = $history->nightDate;
    }

 }

$row = "{$last->nightDate} &mdash; {$toDate}: ";

if( $status == BEDSTATUS_OCCUPIED )
    $row .= $resident;
else
    $row .= Bed::$states[ $status ];

echo "$row<br />";
?>



            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" data-dismiss="modal">OK</button>
            </div>
        </div> <!-- / .modal-content -->
    </div> <!-- / .modal-dialog -->
</div>
@endforeach
*/?>

@foreach( Bed::all() as $bed )
<div id="bedhistory-{{ $bed->id}}" class="modal modal-info  fade" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <i class="fa fa-book"></i>
                Bed History: {{ $bed->name }}
            </div>

            <div class="modal-body">
                <table class="table table-striped">
                    {{--<thead>--}}
                    {{--<tr>--}}
                        {{--<th>Date</th>--}}
                        {{--<th>Action</th>--}}

                        {{--<th class="pull-right">Staff</th>--}}
                    {{--</tr>--}}

                    {{--</thead>--}}

                    @foreach( $bed->bed_status_history as $row )
                        <tr>
                            <td>{{ $row->start_date->toDateString() }}</td>
                            <td>
                                @if($row->resident)
                                    <a href={{ route('resident', $row->resident->id) }}">{{ $row->resident }}</a>
                                    moved in
                                @elseif( $row->status == BEDSTATUS_NOTREADY )
                                    moved out
                                @elseif( $row->status == BEDSTATUS_VACANT )
                                    stripped
                                @endif
                            </td>
                            <td>

                            </td>
                            <td>
                                <img src="{{ $row->whodunit->initialUrl }}" alt="" class="comment-avatar">
                            </td>
                        </tr>
                    @endforeach
                </table>




            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" data-dismiss="modal">OK</button>
            </div>
        </div> <!-- / .modal-content -->
    </div> <!-- / .modal-dialog -->
</div>
@endforeach








@stop
