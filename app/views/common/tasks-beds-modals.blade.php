@foreach( Bed::notready()->get() as $bed )
<div id="bedtask-{{ $bed->id}}" class="modal modal-alert modal-info fade" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <i class="fa fa-paint-brush"></i>
            </div>
            <div class="modal-title">Bed {{ $bed->name }} needs stripping</div>
            <div class="modal-body">

                {{ Form::open(array('route' => array('bed-clean'))) }}
                {{ Form::hidden('bedId', $bed->id) }}
                {{ Form::submit('I stripped it!', array('class' => 'btn btn-success', 'data-dismiss' => 'modal')) }}

                {{ Form::close() }}

            </div>
            <div class="modal-footer">
            </div>
        </div> <!-- / .modal-content -->
    </div> <!-- / .modal-dialog -->
</div>
@endforeach