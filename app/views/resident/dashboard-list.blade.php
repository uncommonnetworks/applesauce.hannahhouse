

@foreach( $residents as $resident )



<div class="comment">
    @if($resident->thumbUrl)
    <img src="{{ $resident->thumbUrl }}" alt="" class="comment-avatar">
    @endif
    <div class="comment-body">
        <div class="comment-by">
            <i class="fa fa-{{ Resident::$stateIcon[$resident->status] }}"></i>
            <span class="text-bg"><a href="{{ route('resident',$resident->id) }}">{{ $resident->display_name }}</a></span>
            {{ $resident->date_of_birth }}
            <span class="badge badge-default">{{ $resident->age }}</span>

        </div>
        <div class="comment-text">

        </div>
        <div class="comment-actions">

            @if($resident->is_wanted)
                <span class="badge badge-danger">wanted</span>
            @endif
            <span class="badge badge-{{ Resident::$stateBadgeClass[$resident->status] }}">{{ Resident::$states[$resident->status] }}</span>

@if($resident->status == RESIDENTSTATUS_OWP)

            <a href="{{ route('note-new', [NOTEFLAG_OWPR, $resident->id]) }}"
                class="title {{ Note::$flagClass[NOTEFLAG_OWPR] }}"><i class="fa {{ Note::$flagIcon[NOTEFLAG_OWPR] }}"></i> {{ Note::$flags[NOTEFLAG_OWPR] }}</a>
@endif

        </div>
    </div> <!-- / .comment-body -->
</div> <!-- / .comment -->




@endforeach