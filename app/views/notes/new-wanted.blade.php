{{-- to be included in new note form --}}





<div class="row waitForResident"
@if(!$resident)
    style="display: none"
@endif
    >
    <div class="col-sm-10 col-sm-offset1">
<div class="panel-heading bg-danger">

@if($resident)
    {{ $resident->display_name }}
@else
    <span class="residentName">&hellip;</span>
@endif
    <span class="panel-title">is
    <span id="residentWanted">
@if($resident)
        {{ $resident->is_wanted ? 'no longer' : 'now' }}
@endif
    </span>
    a wanted person.
    </span>


</div>

    </div>
</div>