<?php
$tasksIntakePendings = Note::withFlag(NOTEFLAG_PENDINGINTAKE)->today()->get();
$tasksIntakePartials = Resident::withStatus(RESIDENTSTATUS_INTAKE)->get();
?>


@if( count($tasksIntakePendings) )
<li class="mm-dropdown">
    <a href="#"><i class="menu-icon fa fa-clock-o"></i><span class="mm-text">Potential Intakes</span></a>
    <ul>
        @foreach( $tasksIntakePendings as $pending )
        <li>
            <a tabindex="-1" href="{{ route('search') }}/{{ $pending->title }}"><span class="mm-text">{{ $pending->title }}</span></a>
        </li>
        @endforeach
    </ul>
</li>
@endif


@if( count($tasksIntakePartials) )

<li class="mm-dropdown">
    <a href="#"><i class="menu-icon fa fa-sign-in"></i><span class="mm-text">Pending Intakes</span></a>
    <ul>
        @foreach( $tasksIntakePartials as $resident )
        <li>
            <a tabindex="-1" href="{{ route('intake-begin', ['id' => $resident->id]) }}"><span class="mm-text">{{ $resident->display_name }}</span></a>
        </li>
        @endforeach
    </ul>
</li>

@endif
