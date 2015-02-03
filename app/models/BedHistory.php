<?php

class BedHistory extends Eloquent {
    protected $table = 'bed_history';


    public function resident()
    {
        return $this->belongsTo('Resident');
    }

    public function bed()
    {
        return $this->belongsTo('Bed');
    }


    public function scopeResidentSince($query, Resident $resident, Carbon\Carbon $sinceDate)
    {
        return $query->where( 'resident_id', '=', $resident->id )
            ->where( 'nightDate', '>=', $sinceDate )
            ->orderBy( 'nightDate', 'ASC' );
    }


    public function scopeResidentYear($query, Resident $resident, $year)
    {
        $firstOfYear = new \Carbon\Carbon("first day of {$year}");
        $lastOfYear = new \Carbon\Carbon("last day of {$year}");

        return $query->where( 'resident_id', '=', $resident->id )
            ->where( 'nightDate', '>=', $firstOfYear )
            ->where( 'nightDate', '<=', $lastOfYear )
            ->orderBy( 'nightDate', 'ASC' );
    }
}