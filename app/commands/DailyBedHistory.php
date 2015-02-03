<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Carbon\Carbon;

class DailyBedHistory extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'applesauce:daily-bed-history';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Record todays bed assignments.';



	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		DB::statement(
			'INSERT IGNORE INTO bed_history
				SELECT null, beds.id, beds.resident_id, beds.status, NOW(), beds.updated_by_id
				FROM beds'
		);

		Log::info('Bed History saved for ' . Carbon::now());

	}

}
