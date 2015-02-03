@if( Bed::notready()->count() > 0 )



<li class="mm-dropdown">
    <a href="#""><i class="menu-icon fa fa-paint-brush"></i><span class="mm-text">Dirty Beds</span></a>
    <ul>

@foreach( Bed::notready()->get() as $bed )
        <li><a href="#"  data-toggle="modal" data-target="#bedtask-{{ $bed->id }}">{{ $bed }} </a></li>
@endforeach
    </ul>
</li>







@endif