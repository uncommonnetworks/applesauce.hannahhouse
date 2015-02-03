@extends('layouts.master')


@section('head-tags')
    {{ HTML::script('/assets/javascripts/jquery.stickytabs.js') }}
@stop

@section('body-tag')
<body class="theme-clean main-menu-right page-search">
@stop

@section('page')




<div class="page-header">
    <h1><i class="fa fa-search page-header-icon"></i>&nbsp;&nbsp;Search results</h1>
</div> <!-- / .page-header -->

@if (isset($q))
<div class="search-text">
    <strong><?php echo count($residents); ?></strong> result<?php if(count($residents) != 1) echo 's'; ?>
    found for: <span class="text-primary">{{{ $q }}}</span>
</div> <!-- / .search-text -->
@endif

<!-- Tabs -->
<div class="search-tabs">
    <ul class="nav nav-tabs">

        <li class="active">
            <a href="#search-tabs-residents" data-toggle="tab">Residents <span class="label label-success">{{ count($residents) }}</span></a>
        </li>

@foreach( Resident::$states as $statusSlug => $statusName )
    @if($statusSlug != RESIDENTSTATUS_INTAKE )
        <li>
            <a href="#search-tab-{{ $statusSlug }}" data-toggle="tab">
                <span class="{{ Resident::$stateBadgeClass[$statusSlug] }}">{{ $statusName }}</span>
                <span class="label">{{ isset($residentsByStatus[$statusSlug]) ? count($residentsByStatus[$statusSlug]) : '0' }}</span>
            </a>
        </li>
    @endif
@endforeach

@if(count($residentsWanted))
        <li>
            <a href="#search-tab-wanted" data-toggle="tab">Wanted <span class="label label-danger">{{ count($residentsWanted) }}</span></a>
        </li>
@endif

    </ul> <!-- / .nav -->
</div>
<!-- / Tabs -->

<!-- Panel -->
<div class="panel search-panel">

<!-- Search form -->
<form action="search" class="search-form bg-primary" method="post">
    <div class="input-group input-group-lg">
        <span class="input-group-addon no-background"><i class="fa fa-search"></i></span>

@if( isset($q) )
        <input type="text" name="q" class="form-control" value="{{{ $q }}}" />
@else
        <input type="text" name="q" class="form-control" placeholder="Type your search here..." />
@endif
					<span class="input-group-btn">
						<button class="btn" type="submit">Search</button>
					</span>
    </div> <!-- / .input-group -->
</form>
<!-- / Search form -->

<!-- Search results -->
<div class="panel-body tab-content">



<!-- Residents search -->
<div class="search-users tab-pane fade in active" id="search-tabs-residents">
    <table class="table table-hover">
        <thead>
        <tr>

            <th>Name</th>
            <th>DOB</th>
            <th>Status</th>
            <th>&nbsp;</th>
        </tr>
        </thead>
        <tbody>


@foreach ($residents as $resident)

        <tr>
            <td>
                <a href="{{ $resident->url }}">
                    <?php if( $resident->picture ): ?>
                        <img src="{{ $resident->thumbUrl }}" alt="" class="avatar" />
                    <?php else: ?>
                        <img src="assets/images/pixel-admin/avatar.png" alt="" class="avatar" />
                    <?php endif; ?>
                </a>&nbsp;&nbsp;
                <a href="{{ $resident->url }}">{{ $resident->display_name }}</a>
            </td>
            <td>
                {{ $resident->dob }}
                <span class="label">{{ $resident->age }}</span>
            </td>
            <td>
                <span class="{{ Resident::$stateBadgeClass[ $resident->status ] }}">{{ Resident::$states[ $resident->status ] }}</span>
                @if($resident->is_wanted)
                    <span class="badge badge-danger">wanted</span>
                @endif
            </td>
            <td>
@if( $resident->status == RESIDENTSTATUS_FORMER || $resident->status == RESIDENTSTATUS_INTAKE )
                <a href="{{ route('intake-begin',$resident->id) }}" class="btn btn-sm btn-info">Intake <i class="fa fa-sign-in"></i></a>
@else

@endif
            </td>
        </tr>
@endforeach

        </tbody>
    </table>
</div>


@foreach( Resident::$states as $statusSlug => $statusName )
        @if($statusSlug != RESIDENTSTATUS_INTAKE )

@if(isset($residentsByStatus[$statusSlug]))
        <!-- Residents search -->
        <div class="search-users tab-pane fade in" id="search-tab-{{ $statusSlug }}">
            <table class="table table-hover">
                <thead>
                <tr>

                    <th>Name</th>
                    <th>DOB</th>
                    <th>Status</th>
                    <th>&nbsp;</th>
                </tr>
                </thead>
                <tbody>


                @foreach ($residentsByStatus[$statusSlug] as $resident)

                    <tr>
                        <td>
                            <a href="{{ $resident->url }}">
                                <img src="{{ $resident->thumbUrl }}" alt="" class="avatar" />
                            </a>&nbsp;&nbsp;
                            <a href="{{ $resident->url }}">{{ $resident->display_name }}</a>
                        </td>
                        <td>
                            {{ $resident->dob }}
                            <span class="label">{{ $resident->age }}</span>
                        </td>
                        <td>
                            @if($resident->is_wanted)
                                <span class="badge badge-danger">wanted</span>
                            @endif
                            <span class="{{ Resident::$stateBadgeClass[ $resident->status ] }}">{{ Resident::$states[ $resident->status ] }}</span>
                        </td>
                        <td>
                            @if( $resident->status == RESIDENTSTATUS_FORMER || $resident->status == RESIDENTSTATUS_INTAKE)
                                <a href="{{ route('intake-begin',$resident->id) }}" class="btn btn-sm btn-info">Intake <i class="fa fa-sign-in"></i></a>
                            @else

                            @endif
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
        </div>
@endif
        @endif
@endforeach



    <div class="search-users tab-pane fade in" id="search-tab-wanted">
        <table class="table table-hover">
            <thead>
            <tr>

                <th>Name</th>
                <th>DOB</th>
                <th>Status</th>
                <th>&nbsp;</th>
            </tr>
            </thead>
            <tbody>


            @foreach ($residentsWanted as $resident)

                <tr>
                    <td>
                        <a href="{{ $resident->url }}">

                            <img src="{{ $resident->thumbUrl }}" alt="" class="avatar" />



                        </a>&nbsp;&nbsp;
                        <a href="{{ $resident->url }}">{{ $resident->display_name }}</a>
                    </td>
                    <td>
                        {{ $resident->dob }}
                        <span class="label">{{ $resident->age }}</span>
                    </td>
                    <td>
                        <span class="badge badge-danger">wanted</span>
                        <span class="{{ Resident::$stateBadgeClass[ $resident->status ] }}">{{ Resident::$states[ $resident->status ] }}</span>
                    </td>
                    <td>
                        @if( $resident->status == RESIDENTSTATUS_FORMER || $resident->status == RESIDENTSTATUS_INTAKE)
                            <a href="{{ route('intake-begin',$resident->id) }}" class="btn btn-sm btn-info">Intake <i class="fa fa-sign-in"></i></a>
                        @else

                        @endif
                    </td>
                </tr>
            @endforeach

            </tbody>
        </table>
    </div>



</div>






<!-- / Search results -->

<!-- Panel Footer --
<div class="panel-footer">
    <ul class="pagination">
        <li class="disabled"><a href="#">«</a></li>
        <li class="active"><a href="#">1 <span class="sr-only">(current)</span></a></li>
        <li><a href="#">2</a></li>
        <li><a href="#">3</a></li>
        <li><a href="#">4</a></li>
        <li><a href="#">5</a></li>
        <li><a href="#">»</a></li>
    </ul>
</div> <!-- / .panel-footer -->


</div>
<!-- / Panel -->


<div class="well-lg">

    <a class="btn btn-lg btn-info" href="{{ route('intake-begin') }}{{ $q ? "?name={$q}" : '' }}">New Resident Intake <i class="fa fa-sign-in"></i></a>
</div>


@stop


@section('footer-scripts')
<script type="text/javascript">

        $('.nav-tabs').stickyTabs();
</script>
@stop