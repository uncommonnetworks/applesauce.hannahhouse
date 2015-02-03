<?php

/*
|--------------------------------------------------------------------------
| Register The Artisan Commands
|--------------------------------------------------------------------------
|
| Each available Artisan command must be registered with the console so
| that it is available to be called. We'll register every command so
| the console gets access to each of the command object instances.
|
*/

Artisan::add(new Daily);
Artisan::add(new DailyBedHistory);
Artisan::add(new DailyLockerHistory);
Artisan::add(new ExpireWarnings);
Artisan::add(new ExpireStrikes);
Artisan::add(new ExpireSuspensions);