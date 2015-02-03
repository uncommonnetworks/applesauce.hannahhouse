
@foreach( Locker::dirty()->get() as $locker )



<div id="lockertask-{{ $locker->id}}" class="modal modal-alert  fade" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <i class="fa fa-paint-brush"></i>
            </div>
            <div class="modal-title">Locker {{ $locker->name }} needs cleaning</div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                {{ Form::open(array('route' => array('locker-clean'))) }}
                {{ Form::hidden('lockerId', $locker->id) }}
                {{ Form::submit('I cleaned it!', array('class' => 'btn btn-success', 'data-dismiss' => 'modal')) }}

                {{ Form::close() }}
            </div>
        </div> <!-- / .modal-content -->
    </div> <!-- / .modal-dialog -->
</div>

@endforeach