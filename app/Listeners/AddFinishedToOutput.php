<?php

namespace App\Listeners;

use \Illuminate\Console\Events\CommandFinished;

class AddFinishedToOutput
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(CommandFinished $event): void
    {
        $message = 'Thanks for using Dian Elearning version 1.0.1';
        $event->output->writeln('');
        $event->output->writeln($message);
    }
}
