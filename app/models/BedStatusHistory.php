<?php

class BedStatusHistory extends Eloquent {

    protected $table = 'bed_status_history';

    protected $dates = ['start_date'];
    protected $fillable = ['start_date','bed_id','status','resident_id','updated_by'];
    public $timestamps = [];


    public function bed()
    {
        return $this->belongsTo('Bed', 'bed_id');
    }

    public function whodunit()
    {
        return $this->belongsTo('User', 'updated_by', 'id');
    }

    public function resident()
    {
        return $this->belongsTo('Resident');
    }


    public static function record( Bed $bed )
    {

        $record = BedStatusHistory::create([
            'start_date' => Carbon\Carbon::now(),
            'bed_id' => $bed->id,
            'status' => $bed->status,
            'resident_id' => $bed->status == BEDSTATUS_OCCUPIED ? $bed->resident->id : null,
            'updated_by' => Auth::user()->id
        ]);

        $record->save();
    }
}