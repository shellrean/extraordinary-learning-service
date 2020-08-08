<?php

namespace App\Listeners;

use Illuminate\Console\Events\CommandStarting;

class AddLogoToOutput
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(CommandStarting $event): void
    {
        $event->output->write("
 _____  _               ______ _                       _             
|  __ \(_)             |  ____| |                     (_)            
| |  | |_  __ _ _ __   | |__  | | ___  __ _ _ __ _ __  _ _ __   __ _ 
| |  | | |/ _` | '_ \  |  __| | |/ _ \/ _` | '__| '_ \| | '_ \ / _` |
| |__| | | (_| | | | | | |____| |  __/ (_| | |  | | | | | | | | (_| |
|_____/|_|\__,_|_| |_| |______|_|\___|\__,_|_|  |_| |_|_|_| |_|\__, |
                                                                __/ |
                                                               |___/ 
");
    }
}
