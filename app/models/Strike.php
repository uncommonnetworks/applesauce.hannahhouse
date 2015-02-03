<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Carbon\Carbon;
use Culpa\Blameable;


define('STRIKESTATUS_CURRENT', 'current');
define('STRIKESTATUS_COMPLETE', 'complete');

// TODO multiple duration options
define('STRIKE_30DAY', 30);


class Strike extends Eloquent{

    use SoftDeletingTrait;
    use Culpa\CreatedBy, Culpa\UpdatedBy, Culpa\DeletedBy;


    public static $types = array(
        STRIKESTATUS_CURRENT => STRIKESTATUS_CURRENT,
        STRIKESTATUS_COMPLETE => STRIKESTATUS_COMPLETE
    );

    public static $durations = array(
        STRIKE_30DAY => '30 Days'
    );

    public static $default_duration = STRIKE_30DAY;

    protected $fillable = ['reason', 'duration'];

    protected $dates = ['created_at', 'updated_at', 'end_date', 'deleted_at'];

    protected $blameable = ['created', 'updated', 'deleted'];


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */


    public function resident()
    {
        return $this->belongsTo('Resident');
    }

    public function shiftNote()
    {
        return $this->belongsTo('Note', 'shift_note_id');
    }

    public function detailNote()
    {
        return $this->belongsTo('Note', 'detail_note_id');
    }


    public function notes()
    {
        return $this->morphToMany('Note', 'noted');
    }



    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */


    public function scopeCurrent($query)
    {
        return $query->whereStatus( STRIKESTATUS_CURRENT );
    }

    public function scopeComplete($query)
    {
        return $query->whereStatus( STRIKESTATUS_COMPLETE );
    }

    public function scopeForResident($query, $residentId )
    {
        return $query->whereResidentId( $residentId );
    }

    public function scopeDueToExpire($query)
    {
        return $query->whereStatus( STRIKESTATUS_CURRENT )
            ->where( 'end_date', '<', DB::raw('NOW()') );
    }


    /*
    |--------------------------------------------------------------------------
    | Computed Fields
    |--------------------------------------------------------------------------
    */



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
    | Construction
    |--------------------------------------------------------------------------
    */

    public static function makeNew( Resident & $resident, $reason, Note & $shiftNote, Note & $detailNote, $duration=STRIKE_30DAY )
    {
        $strike = new Strike(['reason' => $reason, 'duration' => $duration]);

        $strike->status = STRIKESTATUS_CURRENT;

        if($strike->duration)
            $strike->end_date = Carbon::now()->addDays($strike->duration);

        $strike->resident()->associate($resident);
        $strike->shiftNote()->associate($shiftNote);
        $strike->detailNote()->associate($detailNote);
        $strike->save();

        return $strike;
    }


    /*
    |--------------------------------------------------------------------------
    | Logic
    |--------------------------------------------------------------------------
    */


    public function expire()
    {
//        $this->end_date = Carbon::now();
        $this->status = STRIKESTATUS_COMPLETE;
        $this->save();

        return $this;
    }
}



Strike::observe(new Culpa\BlameableObserver());