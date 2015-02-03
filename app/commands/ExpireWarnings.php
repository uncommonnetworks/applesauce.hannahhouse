<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ExpireWarnings extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'applesauce:expire-warnings';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Expire warnings with end dates in the past.';


	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		foreach( Warning::dueToExpire()->get() as $warning )
			$warning->expire();
	}
}
