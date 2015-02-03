<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class Daily extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'applesauce:daily';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Run daily tasks on database';


	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$this->call('applesauce:daily-bed-history');
		$this->call('applesauce:daily-locker-history');
//		$this->call('applesauce:expire-pending-intakes');
		$this->call('applesauce:expire-suspensions');
		$this->call('applesauce:expire-strikes');
		$this->call('applesauce:expire-warnings');
	}
}
