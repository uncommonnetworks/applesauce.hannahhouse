@if( Locker::dirty()->count() > 0 )


<li class="mm-dropdown">
    <a href="#" ><i class="menu-icon fa fa-lock"></i><span class="mm-text">Dirty Lockers</span></a>
    <ul>


@foreach( Locker::dirty()->get() as $locker )
        <li><a href="#" data-toggle="modal" data-target="#lockertask-{{ $locker->id }}">{{ $locker }} </a></li>
@endforeach
    </ul>
</li>


@endif