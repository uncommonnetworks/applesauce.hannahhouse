<?php

use Culpa\Blameable;
use Illuminate\Database\Eloquent\SoftDeletingTrait;


class IncomeSource extends Eloquent
{
    use Culpa\UpdatedBy;
    use SoftDeletingTrait;

    public static $sourceByType = array(
        INCOMETYPE_ODSP => INCOMETYPE_ODSP,
        INCOMETYPE_OW => INCOMETYPE_OW
    );




    public $dates = ['created_at', 'updated_at', 'deleted_at'];
    public $blameable = ['created','updated','deleted'];
    protected $fillable = ['source'];


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function income()
    {
        return $this->hasMany('Income');
    }





    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */



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




    public function __toString()
    {
        return $this->source;
    }



    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */

    public static function getSource($source)
    {
        $id = IncomeSource::whereSource($source)->pluck('id');

        if( $id )
            return IncomeSource::find($id);

        $incomeSource = new IncomeSource(['source' => $source]);
        $incomeSource->save();
        return $incomeSource;
    }

    /*
    |--------------------------------------------------------------------------
    | Logic
    |--------------------------------------------------------------------------
    */

}





IncomeSource::observe(new Culpa\BlameableObserver());