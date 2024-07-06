<?php

namespace Step2Dev\LazyCart\Commands;

use Illuminate\Console\Command;

class LazyCartCommand extends Command
{
    public $signature = 'lazy-cart';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
