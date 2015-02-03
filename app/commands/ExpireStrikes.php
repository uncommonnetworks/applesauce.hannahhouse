<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ExpireStrikes extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'applesauce:expire-strikes';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Expire strikes with end dates in the past';


	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		Strike::dueToExpire()->get()->each( function( $strike ){
			$strike->expire();
		});
	}


}
