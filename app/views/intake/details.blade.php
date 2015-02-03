@extends('layouts.master')


@section('head-tags')

{{  HTML::script('js/clone-form-td.js') }}

@stop

@section('body-tag')

<body class="theme-clean main-menu-right">
@stop

@section('page')
<div class="page-header">
    <h1><span class="text-light-gray">Intake / </span>Background Details</h1>
</div> <!-- / .page-header -->




<!-- Javascript -->
<script>

    init.push(function () {

        // Setup validation
        $("#jq-validation-form").validate({

            focusInvalid: false,
            rules: {
                'postal': {
                    required: false,
                    minlength: 6,
                    pattern: "[A-Za-z][0-9][A-Za-z] ?[0-9][A-Za-z][0-9]"
                },


                'city': {
                    required: false,
                    minlength: 2
                },


                'region': {
                    required: false,
                    minlength: 3
                },

                'doctor_phone': {
                    required: false,
                    phoneUS: true
                },


                'contact_phone': {
                    required: false,
                    phoneUS: true
                }


            }
        });




        $("#resallergies").select2({tags:["<?php echo implode('","',$allergies); ?>"]});

        $("#resallergies").select2({
            initSelection : function (element, callback) {
                var data = [];
                $(element.val().split(",")).each(function () {
                    data.push({id: this, text: this});
                });
                callback(data);
            }
        });

        $("#resallergies").val(["<?php echo implode('","',$resident->allergies->lists('name','id')); ?>"]).select2();

//
//        $('#income').children('.select2').select2({
//            tags:["{{-- implode('","', IncomeSource::all()) --}}"]
//        });

    });
</script>
<!-- / Javascript -->


{{ Form::open(['action' => 'IntakeController@intakeDetails', 'class' => 'form-horizontal', 'id' => 'jq-validation-form']) }}
{{ Form::hidden('id', $resident->id) }}

    <div class="panel">
    <div class="panel-heading">
        <span class="panel-title">Previous Address</span>  for <?php echo $resident->display_name; ?>
    </div>
<div class="panel-body">

<?php

// address is either:
// a returning resident's current address,
// or unknown

if( $resident->status == RESIDENTSTATUS_FORMER )
    $address = $resident->currentAddress;

else if( $resident->status == RESIDENTSTATUS_INTAKE )
    $address = $resident->previousAddress;

if(!isset($address))
    $address = new PreviousAddress();


?>

@if($resident->status == RESIDENTSTATUS_INTAKE)
    {{ Form::hidden('previousAddressId', $address->id) }}
@endif


<div class="form-group">&nbsp;</div>
<div class="form-group">
    <?php echo Form::label('street1', 'Street Address', array( 'class' => 'control-label col-sm-3' )); ?>
    <div class="col-sm-3">
        {{ Form::text('street1', $address->street1, array('class'=>'form-control')) }}<br />
        {{ Form::text('street2', $address->street2, array('class'=>'form-control')) }}
    </div>
</div>
<!--<div class="form-group">-->
<!--    --><?php //echo Form::label('street2', '...', array( 'class' => 'control-label col-sm-3' )); ?>
<!--    <div class="col-sm-3">-->
<!---->
<!--    </div>-->
<!--</div>-->
<div class="form-group">
    {{ Form::label('city', 'City', array( 'class' => 'control-label col-sm-3' )) }}

    <div class="col-sm-3">
        {{ Form::text('city', $address->city, array('class'=>'form-control')) }}
    </div>
</div>
<div class="form-group">
    {{ Form::label('postal', 'Postal Code', array( 'class' => 'control-label col-sm-3' )) }}

    <div class="col-sm-3">
        {{ Form::text('postal', $address->postal, array('class'=>'form-control')) }}
    </div>
</div>
<div class="form-group">
    {{ Form::label('Region', 'Region', array( 'class' => 'control-label col-sm-3' )) }}

    <div class="col-sm-3">
        {{ Form::text('region', $address->region, array('class'=>'form-control')) }}
    </div>
</div>

<div class="form-group">
    {{ Form::label('start_date_m', 'From', array('class' => 'col-sm-3 control-label')) }}
    <div class="col-sm-3">
        {{ Form::selectMonth( 'start_date_m', date( 'm', strtotime( $address->start_date )) ) }}
        {{ Form::select( 'start_date_y', PreviousAddress::startDateYears(), date( 'Y', strtotime( $address->start_date)) ) }}
        <?php //echo Form::text('start_date', $resident->previousAddress->startDate, array( 'class'=>'form-control col-sm-2')); ?>
        <p class="help-block">

            Until <?php echo date( 'M Y' ); ?>
        </p>

    </div>
</div>
    {{--

<!--<div class="panel-heading">-->
<!--    <span class="panel-title">Regional History</span>-->
<!--</div>-->

<!--<div class="form-group">&nbsp;</div>-->
<div class="form-group">
    {{ Form::label('previous_region', 'Previously Living In', array( 'class' => 'control-label col-sm-3' )) }}
    <div class="col-sm-5">
        {{ Form::textarea( 'previous_region', $resident->previous_region, array( 'class' => 'form-control', 'placeholder' => 'Niagara May/2013 through Dec/2013' )) }}
    </div>
</div>
--}}

</div>





    <div class="panel-heading bordered no-border-hr">
        <span class="panel-title">Emergency Contacts</span> for <?php echo $resident->display_name; ?>
    </div>
    <div class="panel-body">
        <!--<div class="note note-info">&nbsp;</div>-->


        <div class="form-group">
            <?php echo Form::label('contact_name', 'Emergency Contact Name', array('class' => 'col-sm-3 control-label')); ?>

            <div class="col-sm-3">
                <?php echo Form::text('contact_name', $resident->contact_name,
                    array('id' => 'contact_name', 'class' => 'form-control')); ?>

            </div>
        </div>
        <div class="form-group">
            <?php echo Form::label('contact_relationship', 'Relationship to ' . $resident->first_name, array('class' => 'col-sm-3 control-label')); ?>

            <div class="col-sm-3">
                <?php echo Form::text('contact_relationship', $resident->contact_relationship,
                    array('id' => 'contact_relationship', 'class' => 'form-control')); ?>

            </div>
        </div>

        <div class="form-group">
            <?php echo Form::label('contact_street1', 'Street Address', array('class' => 'col-sm-3 control-label')); ?>

            <div class="col-sm-3">
                <?php echo Form::text('contact_street1', $resident->contact_street1,
                    array( 'id' => 'contact_street1', 'class' => 'form-control' ) ); ?><br />
                <?php echo Form::text('contact_street2', $resident->contact_street2,
                    array( 'id' => 'contact_street2', 'class' => 'form-control' ) ); ?>
            </div>

        </div>

        <div class="form-group">
            <?php echo Form::label('contact_city', 'City', array( 'class' => 'control-label col-sm-3')); ?>
            <div class="col-sm-3">
                <?php echo Form::text('contact_city', $resident->contact_city,
                    array('id' => 'contact_city',  'class' => 'form-control')); ?>
            </div>
        </div>

        <div class="form-group">
            <?php echo Form::label('contact_phone', 'Phone #', array( 'class' => 'col-sm-3 control-label' )); ?>
            <div class="col-sm-3">
                <?php echo Form::text('contact_phone', $resident->contact_phone,
                    array( 'id' => 'contact_phone', 'class' => 'form-control' )); ?>

            </div>
        </div>

        <hr class="panel-wide">

        <div class="form-group">
            <?php echo Form::label('doctor_name', 'Doctor Name', array( 'class' => 'col-sm-3 control-label' )); ?>

            <div class="col-sm-3">
                <?php echo Form::text('doctor_name', $resident->doctor_name,
                    array('id' => 'doctor_name', 'class' => 'form-control')); ?>

            </div>
        </div>

        <div class="form-group">
            <?php echo Form::label('doctor_phone', 'Doctor Phone #', array( 'class' => 'col-sm-3 control-label' )); ?>

            <div class="col-sm-3">
                <?php echo Form::text('doctor_phone', $resident->doctor_phone,
                    array( 'id' => 'doctor_phone', 'class' => 'form-control' )); ?>

            </div>
        </div>

        <hr />
        <div class="form-group">
            <?php echo Form::label('resallergies', 'Known Allergies?', array( 'class' => 'col-sm-3 control-label' )); ?>
            <?php //dd($resident->allergies->lists('name')); ?>
            <div class="col-sm-3">
                <?php echo Form::text('resallergies', '' ,
                    array( 'id' => 'resallergies', 'class' => 'form-control')); ?>

            </div>
        </div>



    </div>


    <div class="panel-heading bordered no-border-hr">
        <span class="panel-title">Sources of Income</span> for {{ $resident->display_name }}
    </div>
    <div class="panel-body">

        <div id="entry1" class="clonedInput row">

            <fieldset>
                <div class="form-group">

                    <div class="col-sm-1">
                        <button type="button" id="btnAdd" name="btnAdd" class="btn btn-info btn-xs"><i class="fa fa-plus-square"></i></button>
<!--                        <button type="button" id="btnDel" name="btnDel" class="btn btn-danger btn-xs"><i class="fa fa-minus-square"></i></button>-->
                    </div>

                    <div class="col-sm-2">
                        {{ Form::select('income_type', Income::$types, Income::$defaultType, ['class' => 'form-control inc-type', 'id' => 'income_type']) }}
                    </div>
                    <div class="col-sm-3">
                        {{ Form::text('income_source', '', ['class' => 'form-control inc-source', 'id' => 'income_source']) }}
                    </div>
                    <div class="col-sm-2 input-group">
                        <span class="input-group-addon">$</span>
                        {{ Form::text('income_amount', '', ['class' => 'form-control inc-amount', 'id' => 'income_amount']) }}
                        <span class="input-group-addon">/month</span>

                    </div>
                </div>
            </fieldset>
        </div>
    </div>

    <hr />

    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <?php echo Form::button('Save and Continue Intake <i class="fa fa-sign-in"></i>', array( 'class' => 'btn btn-lg btn-info', 'type' => 'submit' )); ?>
            <!--        <button type="submit" class="btn btn-primary">Save and Continue Intake <i class="fa fa-sign-in"></i></button>-->
        </div>
    </div>

</div>

{{ Form::close() }}




@stop


@section('context-menu')
<li class="mm-dropdown open active">
    <a href="#"><i class="menu-icon fa fa-sign-in"></i><span class="mm-text">INTAKE</span><span class="label label-info">in-progress</span></a>
    <ul>
        <li>
            <a tabindex="-1" href="{{ route('intake-begin', $resident->id) }}"><span class="mm-text">Name &amp; ID</span></a>
        </li>
        <li>
            <a tabindex="-1" href="{{ route('intake-bed', $resident->id) }}"><span class="mm-text">Bed &amp; Locker</span></a>
        </li>
        <li class="active">
            <a tabindex="-1" href="#"><span class="mm-text">Address, Medical, Income</span></a>
        </li>
{{--        <li>
            <a tabindex="-1" href="{{ route('intake-contacts', $resident->id) }}"><span class="mm-text">Emergency Contacts</span></a>
        </li>

@if( $resident->residency->detailNote )
        <li>
            <a tabindex="-1" href="{{ route('intake-notes', $resident->id) }}"><span class="mm-text">Notes</span></a>
        </li>
--}}
        <li>
            <a tabindex="-1" href="{{ route('intake-finish', $resident->id) }}"><span class="mm-text">Pictures &amp; Notes</span></a>
        </li>

        <li>
            <a tabindex="-1" href="#">
                <i class="menu-icon fa fa-trash"></i> &nbsp;
                <span class="mm-text">Cancel Intake</span>
            </a>
        </li>
    </ul>
</li>
@stop