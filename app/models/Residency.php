<?php

use Carbon\Carbon;
use Culpa\Blameable;


class Residency extends Eloquent {

    use Culpa\CreatedBy, Culpa\UpdatedBy;


    protected $fillable = ['start_date'];

    protected $dates = ['start_date', 'end_date', 'created_at', 'updated_at'];

    protected $blameable = ['created', 'updated'];



    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */


    public function resident()
    {
        return $this->belongsTo('Resident');
    }

    public function bed()
    {
        return $this->hasOne('Bed');
    }

    public function notes()
    {
        return $this->morphToMany('Note', 'noted');
    }

    public function incomes()
    {
        return $this->hasMany('Income');
    }




    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */


    public function scopeCurrent()
    {
        return $this->query->whereNull('end_date');
    }

    public function scopeMovedIn($query, $fromDate, $untilDate)
    {
        return $query->join('residents', 'residents.id', '=', 'residencies.resident_id')
                ->where('residencies.start_date', '<=', $untilDate)
                ->where('residencies.start_date', '>=', $fromDate)
                ->where(function($query) use($untilDate){
                    $query->whereNull('residencies.end_date')
                        ->orWhere('residencies.end_date', '>', $untilDate);
                })
                ->orderBy('residencies.start_date', 'ASC')
                ->orderBy('residents.last_name', 'ASC')
                ->orderBy('residents.first_name', 'ASC');

    }

    public function scopeMovedOut($query, $fromDate, $untilDate)
    {
        return $query->join('residents', 'residents.id', '=', 'residencies.resident_id')
            ->where('end_date', '<=', $untilDate)
            ->where('end_date', '>=', $fromDate)
            ->orderBy('residencies.end_date', 'ASC')
            ->orderBy('residents.last_name', 'ASC')
            ->orderBy('residents.first_name', 'ASC');
    }

    public function scopeNotMoved($query, $fromDate, $untilDate)
    {
        return $query->join('residents', 'residents.id', '=', 'residencies.resident_id')
            ->join('bed_history', 'bed_history.resident_id', '=', 'residencies.resident_id')
            ->where('bed_history.nightDate', '=', $untilDate->toDateString())
            ->where('bed_history.status', '=', 'occupied')

            ->where('residencies.start_date', '<', $fromDate->toDateString())
            ->orderBy('residencies.start_date', 'ASC')
            ->orderBy('residents.last_name', 'ASC')
            ->orderBy('residents.first_name', 'ASC');
    }




    /*
    |--------------------------------------------------------------------------
    | Setters
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | Getters
    |--------------------------------------------------------------------------
    */


    /*
    |--------------------------------------------------------------------------
    | Computed Fields
    |--------------------------------------------------------------------------
    */

/*
    public function getGovernmentNoteAttribute()
    {
        foreach($this->notes as $note)
            if($note->type == NOTETYPE_GOVT)
                return $note;

        return null;
    }
*/
    public function getDetailNoteAttribute()
    {
        foreach($this->notes as $note)
            if($note->type == NOTETYPE_DETAIL)
                return $note;

        return null;
    }

    public function getShiftNoteAttribute()
    {
        foreach($this->notes as $note)
            if($note->type == NOTETYPE_SHIFT)
                return $note;

        return null;
    }





    /*
    |--------------------------------------------------------------------------
    | Construction
    |--------------------------------------------------------------------------
    */







    /*
    |--------------------------------------------------------------------------
    | Logic
    |--------------------------------------------------------------------------
    */



    /** where it all begins */
    public static function intake( Resident & $resident, Carbon $date, Bed $bed )
    {
        $moveInAddress = CurrentAddress::moveInAddress();
        $resident->replaceCurrentAddress( $moveInAddress );

        $residency = Residency::create( array( 'start_date' => $date ));
        $resident->residencies()->save($residency);
        $resident->residency()->associate($residency);
        $resident->save();

        $bed->helloResident($resident);

        $residency->save();


        return $residency;

    }


    /**
     * @param $reason - given in the form of a resident status
     *
     * locker emptied, lock available
     * bed needs cleaning
     * residency end date
     * shift note
     */
    public function end( )
    {

        $this->end_date = Carbon::now();

        if($this->resident->bed)
        $this->resident->bed->goodbyeResident();

        if($this->resident->locker)
            $this->resident->locker->goodbyeResident();


        $this->end_date = Carbon::now();
        $this->save();

        Note::makeNew(NOTETYPE_SHIFT, NOTEFLAG_OUTTAKE, null, "{$this->resident->display_name} has moved out, and is no longer a resident.", $this->resident);


    }


}





Residency::creating(function($residency){

});


Residency::updating(function($residency){

});


Residency::observe(new Culpa\BlameableObserver);
