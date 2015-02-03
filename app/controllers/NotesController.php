<?php

// don't know why i need this
require_once app_path() . '/models/Note.php';


class NotesController extends \BaseController {

    public function newPending()
    {
        foreach( Note::$typesByFlag[ NOTEFLAG_PENDINGINTAKE ] as $type )
        {
            if( $text = Note::shiftNote(NOTEFLAG_PENDINGINTAKE, Input::get(NOTEFLAG_PENDINGINTAKE.'-person')))
            {
                $note = Note::makeNew($type,NOTEFLAG_PENDINGINTAKE,Input::get(NOTEFLAG_PENDINGINTAKE .'-person'),$text );
                $note->save();
            }
        }

        return Redirect::route('dashboard');
    }


    public function newIntake()
    {
        $resident = Resident::findOrFail(Input::get('residents'));
        $resident->status = RESIDENTSTATUS_INTAKE;
        $date = Carbon\Carbon::now();
        $bed = Bed::findOrFail(Input::get('bed'));
        $notes = array();

        foreach( Note::$typesByFlag[ NOTEFLAG_INTAKE ] as $type )
        {
            if( $type == NOTETYPE_DETAIL )
            {
                if( $text = Input::get(NOTEFLAG_INTAKE . '-' . $type) )
                    $notes[] = Note::makeNew($type, NOTEFLAG_INTAKE, null, $text, $resident);
            }
            else if( $type == NOTETYPE_SHIFT )
            {
                if( $text = Note::shiftNote(NOTEFLAG_INTAKE, $resident->display_name) )
                    $notes[] = Note::makeNew($type, NOTEFLAG_INTAKE, null, $text, $resident);
            }
        }

        $residency = Residency::intake($resident, $date, $bed);

        foreach( $notes as $note )
        {
            $residency->notes()->attach($note);

            // only attach shift note to bed
            if( $note->type == NOTETYPE_SHIFT )
                $bed->notes()->attach($note);
        }


        return Redirect::route('intake-background', array( 'id' => $resident->id ));
    }


    public function newOuttake()
    {
        $resident = Resident::findOrFail(Input::get('residents'));


        $address = new CurrentAddress();

        foreach( array( 'street1', 'street2', 'city', 'postal', 'region' ) as $var )
            $address->$var = Input::get( $var );

        $address->start_date = Carbon\Carbon::now()->addDay();


        $notes = NotesController::saveNotesForResident(NOTEFLAG_OUTTAKE, $resident);

        foreach( $notes as $note )
        {
            $resident->residency->notes()->attach($note);

            if( $note->type == NOTETYPE_SHIFT && $resident->bed )
                $resident->bed->notes()->attach($note);
        }

        $resident->residency->end();

        $resident->outtake($address);

        return Redirect::route('resident',$resident->id);
    }


    public function newGeneral()
    {
        $note = Note::makeNew(NOTETYPE_SHIFT, NOTEFLAG_GENERAL, null, Input::get(NOTEFLAG_GENERAL.'-'.NOTETYPE_SHIFT));
        $note->save();

        return Redirect::route('dashboard');
    }



    public function newIncident()
    {
        $residents = Resident::whereIn('id', preg_split('/,/',Input::get('residents')))->get();
//        dd(preg_split('/,/',Input::get('resident')));
//        dd($residents);

        NotesController::saveNotesForResident(NOTEFLAG_INCIDENT, $residents);


        return Redirect::route('dashboard');
    }

    public function newObservation()
    {
        $resident = Resident::findOrFail(Input::get('residents'));

        NotesController::saveNotesForResident(NOTEFLAG_OBSERVATION, $resident);


        return Redirect::route('dashboard');
    }

    public function newCoaching()
    {
        $resident = Resident::findOrFail(Input::get('residents'));

        NotesController::saveNotesForResident(NOTEFLAG_COACHING, $resident);

        return Redirect::route('dashboard');
    }


    public function newWarning()
    {
        $resident = Resident::findOrFail(Input::get('residents'));


        $notes = NotesController::saveNotesForResident(NOTEFLAG_WARNING, $resident);

        Warning::makeNew(
            $resident, null,
//            Input::get(NOTEFLAG_WARNING.'-reason'),
            $notes[NOTETYPE_SHIFT], $notes[NOTETYPE_DETAIL]
        );


        return Redirect::route('strikes');

    }

    public function newStrike()
    {
        $resident = Resident::findOrFail(Input::get('residents'));


        $notes = NotesController::saveNotesForResident(NOTEFLAG_STRIKE, $resident);

        Strike::makeNew(
                $resident,
                Input::get(NOTEFLAG_STRIKE.'-reason'),
                $notes[NOTETYPE_SHIFT], $notes[NOTETYPE_DETAIL],
                Input::get(NOTEFLAG_STRIKE.'-duration')
        );


        return Redirect::route('strikes');
    }


    public function newOWP()
    {
        $resident = Resident::findOrFail(Input::get('residents'));

        NotesController::saveNotesForResident(NOTEFLAG_OWP, $resident);

        $resident->send_owp();

        return Redirect::route('dashboard');
    }



    public function newOwpr()
    {
        $resident = Resident::findOrFail(Input::get('residents'));

        NotesController::saveNotesForResident(NOTEFLAG_OWPR, $resident);

        $resident->return_owp();

        return Redirect::route('dashboard');
    }



    public function newSuspension()
    {
        $resident = Resident::findOrFail(Input::get('residents'));


        $notes = NotesController::saveNotesForResident(NOTEFLAG_SUSPENSION, $resident);

        $suspension = Suspension::makeNew($resident,
            Input::get(NOTEFLAG_SUSPENSION.'-type'), Input::get(NOTEFLAG_SUSPENSION.'-duration'),
            $notes[NOTETYPE_SHIFT],$notes[NOTETYPE_DETAIL]);


        $resident->suspend();


        return Redirect::route('suspensions');
    }


    public function newWanted()
    {

        $resident = Resident::findOrFail(Input::get('residents'));
        $resident->is_wanted = $resident->is_wanted ? 0 : 1;

        Note::makeNew(NOTETYPE_DETAIL, NOTEFLAG_WANTED, null, Input::get(NOTEFLAG_WANTED . '-' . NOTETYPE_DETAIL));
        NotesController::saveNotesForResident( NOTEFLAG_WANTED, $resident );


        $resident->save();

        return Redirect::route('resident', $resident->id);
    }

    protected function saveNotesForResident($flag, $resident)
    {
        $notes = [];

        foreach( Note::$typesByFlag[$flag] as $type )
        {
            if( $type == NOTETYPE_DETAIL )
            {
                if( $text = Input::get($flag . '-' . $type) )
                    $notes[$type] = Note::makeNew($type, $flag, null, $text, $resident);
            }
            else if( $type == NOTETYPE_SHIFT )
            {
                if( $text = Note::shiftNote($flag, $resident) )
                    $notes[$type] = Note::makeNew($type, $flag, null, $text, $resident);
            }
        }

        return $notes;
    }


}