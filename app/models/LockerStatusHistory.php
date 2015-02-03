<?php

class LockerStatusHistory extends Eloquent {

    protected $table = 'locker_status_history';

    protected $dates = ['start_date'];
    protected $fillable = ['start_date','locker_id','status','resident_id','updated_by'];
    public $timestamps = [];


    public function locker()
    {
        return $this->belongsTo('Locer', 'locker_id');
    }

    public function whodunit()
    {
        return $this->belongsTo('User', 'updated_by', 'id');
    }

    public function resident()
    {
        return $this->belongsTo('Resident');
    }


    public static function record( Locker $locker )
    {
        $record = LockerStatusHistory::create([
            'start_date' => Carbon\Carbon::now(),
            'locker_id' => $locker->id,
            'status' => $locker->status,
            'resident_id' => $locker->status == LOCKERSTATUS_OCCUPIED ? $locker->resident->id : null,
            'updated_by' => Auth::user()->id
        ]);

        $record->save();
    }
}