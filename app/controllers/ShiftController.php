<?php

use Carbon\Carbon;

class ShiftController extends Controller {


    public function edit()
    {
        $shift = Shift::findOrFail(1);

/*
        foreach( ['shift73', 'shift31130', 'shift612', 'shift11308']
            as $field )
        {
            if($value = Input::get($field))
                $shift->$field = $value;


        }
*/
        $field = Input::get('name');
        $value = Input::get('value');
        $shift->$field = $value;
        $shift->save();
        return Response::json('success', 200);
    }
}
?>