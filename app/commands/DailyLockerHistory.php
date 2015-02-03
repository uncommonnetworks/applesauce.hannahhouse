<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Carbon\Carbon;

class DailyLockerHistory extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'applesauce:daily-locker-history';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Record todays locker assignments.';


	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		DB::statement(
			'INSERT IGNORE INTO locker_history
				SELECT null, lockers.id, lockers.resident_id, lockers.status, NOW(), lockers.updated_by_id
				FROM lockers'
		);

		Log::info('Locker History saved for ' . Carbon::now());
	}
}
