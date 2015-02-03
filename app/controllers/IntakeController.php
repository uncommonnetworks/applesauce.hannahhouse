<?php

use Carbon\Carbon;

class IntakeController extends Controller {

    public function intakeBegin()
    {
        $id = Input::get('id');

        if( is_numeric($id) )
        {
            $resident = Resident::findOrFail($id);
        }
        else
        {
            $resident = Resident::makeNew();
        }

        $resident->status = RESIDENTSTATUS_INTAKE;


        // update memory object with form data
        $resident->setName( Input::get('first_name'), Input::get('middle_name'), Input::get('last_name'), Input::get('goes_by_name') );

        list( $day, $month, $year ) = preg_split('/\//', Input::get('date_of_birth'));
        $resident->setDateOfBirth( $day, $month, $year );

        $resident->gender = Input::get('gender');
        $resident->marital_status = Input::get('marital_status');
        $resident->sin = Input::get('sin');
        $resident->health_card_number = Input::get('health_card_number');

        $resident->save();


        // if a pending intake note was made with this name, delete it
        if( !is_numeric($id) )
        {
            foreach( Note::ofType(NOTETYPE_SHIFT)->withFlag(NOTEFLAG_PENDINGINTAKE)->today()->get() as $pendingNote )
                if( $pendingNote->title == "{$resident->first_name} {$resident->last_name}" )
                    $pendingNote->delete();
        }

        return Redirect::route('intake-bed', array( 'id' => $resident->id));
    }
/*
    public function intakeBackground()
    {
        $id = Input::get('id');
        $resident = Resident::findOrFail($id);

        if( $previousAddressId = Input::get('previousAddressId') )
            $address = PreviousAddress::find($previousAddressId);
        else
            $address = new PreviousAddress();

        foreach( array( 'street1', 'street2', 'city', 'postal', 'region' ) as $var )
            $address->$var = Input::get( $var );

        $address->start_date = Carbon::createFromDate( Input::get('start_date_Y'), Input::get('start_date_m'), 1 );
        $address->end_date = Carbon::now();

        if( $previousAddressId )
            $address->save();
        else
            $resident->replacePreviousAddress($address);

        $resident->previous_region = Input::get( 'previous_region' );
        $resident->push();

        return Redirect::route('intake-contacts', array( 'id' => $resident->id ));
    }
*/
    public function intakeBed()
    {
        $id = Input::get('id');
        $resident = Resident::findOrFail($id);

        // ignore duplicate submissions of this form
        if( !$resident->bed || !$resident->residency )
        {
            $date = Carbon::parse( Input::get('date') );
            $bedId = Input::get('bed_id');
            $bed = Bed::findOrFail($bedId);

            $residency = Residency::intake($resident, $date, $bed);
        }

        if( !$resident->locker && $locker_id = Input::get('locker_id'))
        {
            $locker = Locker::findOrFail($locker_id);
            $locker->helloResident($resident);
        }



        return Redirect::route('intake-details', array( 'id' => $resident->id ));
    }

    public function intakeDetails()
    {
        $id = Input::get('id');
        $resident = Resident::findOrFail($id);


        // addresses
        if( $previousAddressId = Input::get('previousAddressId') )
            $address = PreviousAddress::find($previousAddressId);
        else
            $address = new PreviousAddress();

        foreach( array( 'street1', 'street2', 'city', 'postal', 'region' ) as $var )
            $address->$var = Input::get( $var );

        $address->start_date = Carbon::createFromDate( Input::get('start_date_Y'), Input::get('start_date_m'), 1 );
        $address->end_date = Carbon::now();

        if( $previousAddressId )
            $address->save();
        else
            $resident->replacePreviousAddress($address);

        $resident->previous_region = Input::get( 'previous_region' );
        $resident->push();


        // contacts
        foreach( ['contact_name', 'contact_phone', 'contact_street1', 'contact_street2',
                     'contact_city', 'contact_relationship', 'doctor_name', 'doctor_phone'] as $var )
        {
            if( $value = Input::get($var))
                $resident->$var = $value;
        }

        $resident->save();

        // allergies
        $resident->allergies = Input::get('resallergies');
        $resident->save();

        // income
        $incomes = [];
        if($amount = Input::get('income_amount'))
            if( $type = Input::get('income_type') == 'Other' )
                Income::saveToResidency( $resident->residency, $type, IncomeSource::getSource(Input::get('income_source')), $amount);
            else
                Income::saveToResidency( $resident->residency, $type, IncomeSource::getSource($type), $amount);

        for($i = 2; $i <= 5; $i++)
            if($amount = Input::get("ID{$i}_income_amount"))
                if( $type = Input::get("ID{$i}_income_type") == 'Other' )
                    Income::saveToResidency( $resident->residency, $type, IncomeSource::getSource(Input::get("ID{$i}_income_source")), $amount);
                else
                    Income::saveToResidency( $resident->residency, $type, IncomeSource::getSource($type), $amount);

        return Redirect::route('intake-finish', ['id' => $resident->id]);
    }
/*
    public function intakeContacts()
    {
        $id = Input::get('id');
        $resident = Resident::findOrFail($id);

        foreach( array( 'contact_name', 'contact_phone', 'contact_street1', 'contact_street2',
                        'contact_city', 'contact_relationship', 'doctor_name', 'doctor_phone' ) as $var )
        {
            $resident->$var = Input::get($var);
        }

        $resident->save();


        // allergies may have been added or removed
        $resident->allergies = Input::get('resallergies');

        return Redirect::route('intake-money', array( 'id' => $resident->id ));
    }
*/
    /*

    public function intakeMoney()
    {
        $id = Input::get('id');
        $resident = Resident::findOrFail($id);


        foreach( array( 'exp_rent', 'exp_taxes', 'exp_room_and_board', 'exp_fire_insurance', 'exp_mortgage',
                        'exp_fuel', 'assets_of_value', 'sources_of_income' ) as $var )
        {
            $resident->residency->$var = intval(trim(Input::get($var)));
        }

        $resident->residency->save();

        return Redirect::route('intake-notes', array( 'id' => $resident->id ));
    }
    */
/*
    public function intakeNotes()
    {
        $id = Input::get('id');
        $resident = Resident::findOrFail($id);

        // insert or update government note
        /*
        if( $resident->residency->governmentNote )
        {
            $resident->residency->governmentNote->text = Input::get('govt_note');
            $resident->residency->governmentNote->save();

        }
        else
        {
            $note = Note::makeNew(NOTETYPE_DETAIL, NOTEFLAG_INTAKE, $resident->display_name, Input::get('govt_note'), $resident);
            $resident->residency->notes()->attach($note);
        }
*
        // insert or update detail note
        if( $resident->residency->detailNote )
        {
            $resident->residency->detailNote->text = Input::get('detail_note');
            $resident->residency->detailNote->save();

        }
        else
        {
            $note = Note::makeNew(NOTETYPE_DETAIL, NOTEFLAG_INTAKE, null, Input::get('detail_note'), $resident);
            $resident->residency->notes()->attach($note);
        }

        // insert or update shift note
        if( $resident->residency->shiftNote )
        {
            $resident->residency->shiftNote->text = Input::get('shift_note');
            $resident->residency->shiftNote->save();

        }
        else
        {
            $note = Note::makeNew(NOTETYPE_SHIFT, NOTEFLAG_INTAKE, null, Input::get('shift_note'), $resident);
            $resident->residency->notes()->attach($note);
        }


        return Redirect::route('intake-finish', array( 'id' => $resident->id ));
    }
*/

    public function intakeFinish()
    {
        $id = Input::get('id');
        $resident = Resident::findOrFail($id);

        // insert or update detail note
        if( $resident->residency->detailNote )
        {
            $resident->residency->detailNote->text = Input::get('detail_note');
            $resident->residency->detailNote->save();

        }
        else
        {
            $note = Note::makeNew(NOTETYPE_DETAIL, NOTEFLAG_INTAKE, null, Input::get('detail_note'), $resident);
            $resident->residency->notes()->attach($note);
        }

        // insert or update shift note
        if( $resident->residency->shiftNote )
        {
            $resident->residency->shiftNote->text = Input::get('shift_note');
            $resident->residency->shiftNote->save();

        }
        else
        {
            $note = Note::makeNew(NOTETYPE_SHIFT, NOTEFLAG_INTAKE, null,
                "{$resident->display_name} has completed intake and is now a resident.", $resident);
            $resident->residency->notes()->attach($note);
        }


        $resident->intake();

        return Redirect::route('resident', array('id' => $id));
    }

}


?>