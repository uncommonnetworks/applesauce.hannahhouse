<?php



class LockerController extends Controller {

    public static function cleanLocker()
    {
        $locker = Locker::findOrFail(Input::get('lockerId'));

        $locker->clean();

        return Redirect::back();
//        return Redirect::to(route('lockers') . "#lockerrom{$locker->room_id}");
    }


    public static function assignLocker()
    {
        $locker = Locker::findOrFail(Input::get('lockerId'));
        $resident = Resident::findOrFail(Input::get('residentId'));

        $locker->helloResident($resident);

        return Redirect::to(route('lockers') . "#lockerroom{$locker->room_id}");
    }

    public static function changeLocker()
    {
        $locker = Locker::findOrFail(Input::get('locker_id'));
        $resident = Resident::findOrFail(Input::get('resident_id'));

        $lock=null;

        if($resident->locker)
        {
            $lock = $resident->locker->lock;
            $resident->locker->goodbyeResident($resident);
        }

        $locker->helloResident($resident, $lock);

        return Redirect::to(route('resident', $resident->id));
    }

    public static function changeLock()
    {
        $lock = Lock::findOrFail(Input::get('lock_id'));
        $resident = Resident::findOrFail(Input::get('resident_id'));

        if($resident->locker->lock)
            $resident->locker->lock->goodByeLocker($resident->locker);

        $lock->helloLocker($resident->locker);

        return Redirect::to(route('resident', $resident->id));
    }

}
