<?php

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Culpa\Blameable;


/** Resident is always in one of the following states */

define( 'RESIDENTSTATUS_CURRENT', 'current' );
define( 'RESIDENTSTATUS_FORMER', 'former' );
define( 'RESIDENTSTATUS_SUSPENDED', 'suspended' );
define( 'RESIDENTSTATUS_OWP', 'owp' );
define( 'RESIDENTSTATUS_INTAKE', 'intake' );
define( 'RESIDENTSTATUS_DELETED', 'deleted' );
define( 'RESIDENTSTATUS_DECEASED', 'deceased' );


// todo: config
define( 'RESIDENT_RECENTHOURS', 36 );

/** Marital Status options */

define( 'MARITALSTATUS_SINGLE', 'single' );
define( 'MARITALSTATUS_MARRIED', 'married' );
define( 'MARITALSTATUS_SEPARATED', 'separated' );
define( 'MARITALSTATUS_DIVORCED', 'divorced' );
define( 'MARITALSTATUS_WIDOWED', 'widowed' );
define( 'MARITALSTATUS_UNKNOWN', 'not disclosed' );


/** Mr and Mrs */
define( 'PERSONTITLE_MR', 'Mr.');
define( 'PERSONTITLE_MRS', 'Mrs.');
define( 'PERSONTITLE_MISS', 'Miss');
define( 'PERSONTITLE_MS', 'Ms.');
define( 'PERSONTITLE_UNKNOWN', ' ' );


/** Age restrictions */
define( 'RESIDENT_AGEMAX', 100 );
define( 'RESIDENT_AGEMIN', 18 );


/** Storage */
define( 'RESIDENT_PATH', 'resident/' );


class Resident extends Eloquent {

    use SoftDeletingTrait;
    use Culpa\CreatedBy, Culpa\UpdatedBy, Culpa\DeletedBy;

    public static $states = array(
        RESIDENTSTATUS_CURRENT => 'current resident',
        RESIDENTSTATUS_FORMER => 'former resident',
        RESIDENTSTATUS_SUSPENDED => 'suspended',
        RESIDENTSTATUS_OWP => 'out with permission',
        RESIDENTSTATUS_INTAKE => 'intake incomplete'
    );

    public static $stateBadgeClass = array(
        RESIDENTSTATUS_CURRENT => 'badge badge-success',
        RESIDENTSTATUS_FORMER => 'badge badge-info',
        RESIDENTSTATUS_SUSPENDED => 'badge badge-danger',
        RESIDENTSTATUS_OWP => 'badge badge-warning',
        RESIDENTSTATUS_INTAKE => 'badge badge-primary',
        RESIDENTSTATUS_DELETED => 'badge'
    );

    public static $stateButtonClass = array(
        RESIDENTSTATUS_CURRENT => 'btn-success',
        RESIDENTSTATUS_FORMER => 'btn-info',
        RESIDENTSTATUS_SUSPENDED => 'btn-danger',
        RESIDENTSTATUS_OWP => 'btn-warning',
        RESIDENTSTATUS_INTAKE => 'btn-primary',
        RESIDENTSTATUS_DELETED => ''
    );

    public static $stateBackgroundClass = array(
        RESIDENTSTATUS_CURRENT => 'bg-success',
        RESIDENTSTATUS_FORMER => 'bg-info',
        RESIDENTSTATUS_SUSPENDED => 'bg-danger',
        RESIDENTSTATUS_OWP => 'bg-warning',
        RESIDENTSTATUS_INTAKE => 'bg-primary',
        RESIDENTSTATUS_DELETED => ''
    );

    public static $stateIcon = array(
        RESIDENTSTATUS_CURRENT => 'fa-home',
        RESIDENTSTATUS_FORMER => 'fa-folder-open-o',
        RESIDENTSTATUS_SUSPENDED => 'fa-exclamation-triangle',
        RESIDENTSTATUS_OWP => 'fa-suitcase',
        RESIDENTSTATUS_INTAKE => 'fa-sign-in',
        RESIDENTSTATUS_DELETED => 'fa-power-off'
    );

    public static $maritalStatusOptions = array(
        MARITALSTATUS_SINGLE => MARITALSTATUS_SINGLE,
        MARITALSTATUS_MARRIED => MARITALSTATUS_MARRIED,
        MARITALSTATUS_SEPARATED => MARITALSTATUS_SEPARATED,
        MARITALSTATUS_DIVORCED => MARITALSTATUS_DIVORCED,
        MARITALSTATUS_WIDOWED => MARITALSTATUS_WIDOWED,
        MARITALSTATUS_UNKNOWN => MARITALSTATUS_UNKNOWN
    );

    public static $titleOptions = array(
        PERSONTITLE_MR => PERSONTITLE_MR,
        PERSONTITLE_MISS => PERSONTITLE_MISS,
        PERSONTITLE_MRS => PERSONTITLE_MRS,
        PERSONTITLE_MS => PERSONTITLE_MS
    );




    // these attributes ought not to be set or edited
    protected $guarded = ['id'];


    // track user that performed these actions
    protected $blameable = ['created', 'updated', 'deleted'];

    protected $dates = ['created_at', 'updated_at', 'deleted_at', 'status_updated_at'];


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */


//    public function addresses()
//    {
//        return $this->hasMany('Address');
//    }

    public function currentAddress()
    {
        return $this->hasOne('CurrentAddress');
    }

    public function previousAddress()
    {
        return $this->hasOne('PreviousAddress');
    }

    public function otherAddresses()
    {
        return $this->hasMany('OtherAddress');
    }

    public function residencies()
    {
        return $this->hasMany('Residency')
            ->orderBy('start_date', 'DESC');
    }

    public function residency()
    {
        return $this->belongsTo('Residency');
    }

    public function bed()
    {
        return $this->hasOne('Bed');
    }

    public function locker()
    {
        return $this->hasOne('Locker')
            ->whereStatus(LOCKERSTATUS_OCCUPIED);
    }

    public function allergies()
    {
        return $this->belongsToMany('Allergy')
                ->withTimestamps();
    }

    public function identifications()
    {
        return $this->hasMany('Identification');
    }

    public function picture()
    {
        return $this->morphOne('Picture', 'pictureable');
    }

    public function notes()
    {
        return $this->morphToMany('Note', 'noted');
    }

    public function strikes()
    {
        return $this->hasMany('Strike');
    }

    public function warnings()
    {
        return $this->hasMany('Warning');
    }

    public function suspensions()
    {
        return $this->hasMany('Suspension');
    }

    public function wanteds()
    {
        return $this->hasMany('Wanted');
    }

    public function bedHistory()
    {
        return $this->hasMany( 'BedHistory' )
            ->orderBy( 'nightDate', 'DESC' );
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeCurrent($query)
    {
        return $query->whereStatus(RESIDENTSTATUS_CURRENT);
    }

    public function scopeWithStatus($query, $status)
    {
        return $query->whereStatus($status);
    }

    public function scopeLiving($query)
    {
        return $query->where('status', '<>', RESIDENTSTATUS_DECEASED);
    }

    public function scopeDeceased($query)
    {
        return $query->whereStatus(RESIDENTSTATUS_DECEASED);
    }

    public function scopeFormer($query)
    {
        return $query->whereStatus(RESIDENTSTATUS_FORMER);
    }

    public function scopeOwp($query)
    {
        return $query->whereStatus(RESIDENTSTATUS_OWP);
    }

    public function scopeWanted($query)
    {
        return $query->whereIsWanted(1);
    }

    public function scopeMale($query)
    {
        return $query->whereGender('M');
    }

    public function scopeFemale($query)
    {
        return $query->whereGender('F');
    }


    public function scopeRecent($query)
    {
        return $query->where('status_updated_at', '>', Carbon::createFromTimestamp(strtotime(RESIDENT_RECENTHOURS .' hours ago'))->toDateString());
    }
/*

    public function scopeMovedIn($query, $fromDate, $untilDate)
    {
        /*
        return $query->whereIn('id',
            DB::table('residentStatusHistory')->where('status', RESIDENTSTATUS_CURRENT)
                ->where('endDate', '>', $untilDate)
                ->where('startDate' '<', $fromDate)
                ->lists('resident');
        );
        *
        return $query->whereHas('residency', function ($qres) use($fromDate, $untilDate){
            Log::info("query residency {$fromDate} - {$untilDate}");
            $qres->where('start_date', '<', $untilDate)
                ->where('start_date', '>', $fromDate);
        });
    }


    public function scopeMovedOut($query, $fromDate, $untilDate)
    {

    }
*/

    public function scopeNoLocker($query)
    {
        return $query->has('locker','<',1);
    }

    public function scopeInYear($query, $year)
    {
        $firstOfYear = new Carbon("first day of {$year}");
        $lastOfYear = new Carbon("last day of {$year}");

        return $query->join('residencies', 'resident_id', '=', 'residents.id')
            ->where(function($query) use($firstOfYear, $lastOfYear) {
                $query->where('residencies.start_date', '>=', $firstOfYear)
                    ->where('residencies.start_date', '<=', $lastOfYear);
                })
            ->orWhere(function($query) use($firstOfYear, $lastOfYear){
                $query->where('residencies.end_date', '>=', $firstOfYear)
                    ->where('residencies.end_date', '<=', $lastOfYear);
                })
            ->orderBy('residents.last_name')
            ->orderBy('residents.first_name');
    }

    /*
    |--------------------------------------------------------------------------
    | Computed Fields
    |--------------------------------------------------------------------------
    */

    public function getFullNameAttribute()
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function getGivenNamesAttribute()
    {
        $name = $this->first_name;

        if($this->middle_name)
            $name .= " {$this->middle_name}";

        return $name;
    }

    public function getAgeAttribute()
    {
//        if( !$this->date_of_birth ) return null;

        return Carbon::parse($this->date_of_birth)->age;
    }

    public function getDobAttribute()
    {
        if(!$this->date_of_birth) return null;

        return Carbon::parse($this->date_of_birth);
    }

    public function getDateOfBirthDmyAttribute()
    {
        return date( 'd/m/Y', strtotime( $this->date_of_birth ) );
    }

    public function getIdSinAttribute()
    {
        foreach( $this->identifications as $identification )
        {
            if( $identification->type == IDENTIFICATION_SIN )
                return $identification;
        }
        return false;
    }

    public function getIdHealthAttribute()
    {
        foreach( $this->identifications as $identification )
            if( $identification->type == IDENTIFICATION_HEALTH )
                return $identification;

        return false;
    }
//    public function getNameAttribute()
//    {
//        return "{$this->goes_by_name} {$this->last_name}";
//    }
    public function getPictureUrlAttribute()
    {
        return "/resident-image/{$this->id}";
    }

    public function getThumbUrlAttribute()
    {
        return "/resident-image/{$this->id}/thumb";
    }

    public function getPathAttribute()
    {
        $path = RESIDENT_PATH . substr($this->last_name, 0, 2) . "/{$this->last_name}, {$this->first_name}";

        if( !is_dir(storage_path() . "/{$path}") )
            mkdir( storage_path() . "/{$path}", 0775, true );

        return $path;
    }

    public function getUrlAttribute()
    {
        return URL::route('resident', $this->id );
    }

    public function getMostRecentResidencyAttribute()
    {
        foreach($this->residencies as $residency)
        {
            if(isset($residency->end_date))
                return $residency;
        }

        return false;
    }

    public function getIntakesThisMonthAttribute()
    {
        $count = 0;
        $thisMonth = new Carbon('first day of this month');

        foreach( $this->residencies as $residency )
        {
            if( $residency->start_date->gte($thisMonth) )
                $count++;
            else
                break;
        }

        return $count;
    }

    public function getNightsThisMonthAttribute()
    {
        $firstOfMonth = new Carbon('first day of this month');

        return BedHistory::where('resident_id','=',$this->id)
            ->where('nightDate', '<=', $firstOfMonth);
    }

    public function getDietaryConcernsAttribute()
    {
        if(count($this->allergies))
        {
            return 'Allergies: ' . $this->allergies->implode('name', ', ');
        }

        return null;
    }


    /*
    |--------------------------------------------------------------------------
    | Setters
    |--------------------------------------------------------------------------
    */

    public function setName( $first_name, $middle_name, $last_name, $goes_by_name )
    {
        // only set new value if parameter is not null
        foreach( array( 'first_name', 'middle_name', 'last_name', 'goes_by_name') as $var )
        {
            if( !is_null($$var) )
                $this->$var = $$var;
        }

        $this->attributes['display_name'] =
            $this->first_name . ' ' .
            ($this->goes_by_name ? "\"{$this->goes_by_name}\" " : '') .
            $this->last_name;
    }


    public function setTitleAttribute($newTitle)
    {
        $this->attributes['title'] = $newTitle;

        // infer gender from title
        $this->gender = ($newTitle == PERSONTITLE_MR ? 'M' : 'F');
    }


    // properly set the internal attribute without worrying about format
    public function setDateOfBirth( $day, $month, $year )
    {
        $this->date_of_birth = date( 'Y-m-d', strtotime("{$year}-{$month}-{$day}"));
    }

    // new allergies will usually be set as a comma-delimited list, each of which may or may not already exist
    // or, list must be a list of allergy ids
    public function setAllergiesAttribute( $list )
    {
        if( !is_array( $list ) )
        {
            $string = $list;
            $list = array();

            foreach( explode( ',', $string ) as $allergy )
            {
                $newAllergy = Allergy::firstOrCreate( array('name' => ucfirst(trim($allergy))) );
                $list[] = $newAllergy->id;
            }
        }

        $this->allergies()->sync( $list );
    }





    public function setStatusAttribute( $newStatus )
    {
        $this->status_updated_at = Carbon::now();
        $this->attributes['status'] = $newStatus;
    }


    /*
    |--------------------------------------------------------------------------
    | Getters
    |--------------------------------------------------------------------------
    */

    public function __toString()
    {
        return "{$this->display_name} &nbsp; <em>({$this->date_of_birth})</em>";
    }


    /** logic to provide form field validation boundaries */
    public static function getDobMinYear()
    {
        return date( 'Y', strtotime(RESIDENT_AGEMAX . ' years ago') );
    }

    public static function getDobMaxYear()
    {
        return date( 'Y', strtotime(RESIDENT_AGEMIN . ' years ago') );
    }


    public function getPictureImg( $attributes='' )
    {
        $url = $this->pictureUrl;

        if( $url )
            return "<img src=\"{$url}\" alt=\"{$this->name}\" {$attributes} />";
    }

    public function getThumbImg( $attributes='' )
    {
        $url = $this->thumbUrl;

        if( $url )
            return "<img src=\"{$url}\" alt=\"{$this->name}\" {$attributes} />";
    }


    public function stats($year)
    {
        $history = BedHistory::residentYear($this, $year);
        $data = [
            'numberOfStays' => Residency::residentYear($this, $year)->count(),
            'totalBedNights' => $history->count(),
            'byMonth' => array_fill_keys( range(0,12), 0 )
        ];


        foreach( $history as $night )
        {
            $data['byMonth'][ date('m', strtotime($night->nightDate)) ]++;
        }

        return $data;

    }


    /*
    |--------------------------------------------------------------------------
    | Construction
    |--------------------------------------------------------------------------
    */

    public static function makeNew($name=null)
    {
        $resident = new Resident;

        // default values
        $resident->status = RESIDENTSTATUS_INTAKE;
        $resident->marital_status = MARITALSTATUS_UNKNOWN;

//        $resident->currentAddress = new CurrentAddress;
//        $resident->previousAddress = new PreviousAddress;

        if(!is_null($name))
        {
            $nameParts = preg_split( '/[^a-zA-Z-]+/', $name, 2 );

            $resident->first_name = $nameParts[0];

            if(isset($nameParts[1]))
                $resident->last_name = $nameParts[1];
        }

        return $resident;
    }




    /*
    |--------------------------------------------------------------------------
    | Logic
    |--------------------------------------------------------------------------
    */


    /** complete an intake */
    public function intake()
    {
        // intake on wanted person clears wanted status
        if($this->is_wanted)
            $this->is_wanted = 0;

        $this->status = RESIDENTSTATUS_CURRENT;
        $this->save();


        return $this;
    }

    public function outtake(CurrentAddress $address=null)
    {
        $this->expire_strikes();

        if($address)
            $this->replaceCurrentAddress($address);

        $this->status = RESIDENTSTATUS_FORMER;
        $this->residency_id = null;
        $this->save();

        return $this;
    }



    /** a resident has been noted OWP */
    public function send_owp()
    {
        $this->status = RESIDENTSTATUS_OWP;
        $this->save();

        return $this;
    }

    /** a resident has returned from OWP */
    public function return_owp()
    {
        $this->status = RESIDENTSTATUS_CURRENT;
        $this->save();

        return $this;
    }

    /** a resident is gone OWP too long */
    public function expire_owp()
    {
        $this->status = RESIDENTSTATUS_FORMER;
        $this->save();

        // TODO: out-take automatic
        // $this->residency->end(RESIDENTSTATUS_OWP)

        return $this;
    }

    /** a resident has been suspended
     *
     * end residency
     */
    public function suspend()
    {
        $this->status = RESIDENTSTATUS_SUSPENDED;
        $this->save();

        $this->residency->end(RESIDENTSTATUS_SUSPENDED);

        return $this;
    }


    public function unsuspend()
    {
//        $residency = Residency::intake($this, Carbon::now(), $bed);
        $this->status = RESIDENTSTATUS_FORMER;
        $this->save();

    }

    protected  function expire_strikes()
    {
        foreach($this->strikes() as $strike)
            $strike->expire();
    }


    public function replaceCurrentAddress( $address )
    {
        $this->moveCurrentToPreviousAddress();
        $this->currentAddress()->save($address);
    }

    public function replacePreviousAddress( $address )
    {
        $this->movePreviousToOtherAddress();
        $this->previousAddress()->save($address);
    }

    protected function moveCurrentToPreviousAddress()
    {
        if( $this->currentAddress )
        {
            $this->movePreviousToOtherAddress();

            $previousAddress = PreviousAddress::makeFromCurrentAddress( $this->currentAddress );
            $previousAddress->end_date = Carbon::now();
            $this->previousAddress()->save($previousAddress);

            $this->currentAddress->delete();
        }
    }

    protected function movePreviousToOtherAddress()
    {
        if( $this->previousAddress )
        {
            $otherAddress = OtherAddress::makeFromAddress($this->previousAddress);

            if(!$otherAddress->end_date)
                $otherAddress->end_date = Carbon::now();

            $this->otherAddresses()->save($otherAddress);

            $this->previousAddress->delete();
        }
    }



    /** logic for search by name */
    public static function search($query, $scope=false)
    {
//Log::info("Search: $query");
        // split query by words
        $query = explode(" ", $query);

        //search for first or last name as there is no space
        if (!empty($query[0]) && empty($query[1]))
        {
            $q = $query[0];

            $r = Resident::where(function($query) use ($q) {
                $query->where('first_name', 'LIKE', "$q%")
                    ->orWhere('last_name', 'LIKE', "$q%")
                    ->orWhere('goes_by_name', 'LIKE', "$q%");
            });
        }

        //search for first AND last name as there is a space
        elseif (!empty($query[0]) && !empty($query[1]))
        {
            $qf = $query[0];
            $ql = $query[1];

            $r = Resident::where(function($query) use ($qf, $ql) {
                $query->where('first_name', 'LIKE', "$qf%")
                    ->where('last_name', 'LIKE', "$ql%");
            });
        }

        else // (empty($query))
            $r = Resident::take(20);

        if($scope)
        {
            if( is_array($scope) )
                foreach( $scope as $anotherScope )
                    $r = $r->$anotherScope();
            else
                $r = $r->$scope();
        }

        $r = $r->orderBy('last_name', 'asc')
            ->orderBy('first_name', 'asc');




        return $r->get();
    }


}




Resident::creating(function($resident){
});



// fires after the resident is saved
/*
Resident::created(function($resident){


    $previous = new PreviousAddress;
    $current = CurrentAddress::moveInAddress();
    $previous->save();
    $current->save();

    $resident->previousAddress()->save( $previous );
    $resident->currentAddress()->save( $current );

//    $resident->previousAddress()->associate($previous)->save();
//    $resident->currentAddress()->associate($current);
//    $resident->save();

});
*/
Resident::updating(function($resident){
});

/*
Resident::updating( function($resident)
{
    $resident->previous_address->save();
    $resident->previous_address_id = $resident->previous_address->id;

    $resident->current_address->save();
    $resident->current_address_id = $resident->current_address->id;
});
*/

Resident::observe(new Culpa\BlameableObserver);
