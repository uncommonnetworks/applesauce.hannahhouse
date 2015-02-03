<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Carbon\Carbon;

class ExpireSuspensions extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'applesauce:expire-suspensions';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Expire suspensions with an end date in the past';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		Auth::loginUsingId(Config::get('auth.sysuser'));
		$residents = [];

		foreach( Suspension::dueToExpire()->get() as $suspension )
		{
			$suspension->expire();
			$suspension->resident->unsuspend();

			$note = Note::makeNew(
				NOTETYPE_DETAIL,
				NOTEFLAG_SUSPENSION,
				'Suspension Expired',
				"{$suspension->resident->display_name}'s suspension has ended.",
				$suspension->resident
			);

			$residents[ $suspension->resident->id ] = $suspension->resident->display_name;
		}

		if(count($residents)) {
			$note = Note::makeNew(
				NOTETYPE_SHIFT,
				NOTEFLAG_INCIDENT,
				null,
				"The following suspensions are now complete/expired: " . implode(', ', $residents) . '.',
				array_keys($residents)
			);
		}

	}

}
