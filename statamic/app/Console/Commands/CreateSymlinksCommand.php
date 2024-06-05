<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CreateSymlinksCommand extends Command
{
    protected $signature = 'create-symlinks';

    public function handle()
    {
        $addons = File::directories(base_path('addons'));

        foreach ($addons as $addon) {
            $package = basename($addon);

            $source = base_path("addons/{$package}/dist");

            if (! is_dir($source)) {
                continue;
            }

            $public_vendor = public_path("vendor/{$package}");

            if (is_link($public_vendor)) {
                unlink($public_vendor);
            }

            if (is_dir($public_vendor)) {
                File::deleteDirectory($public_vendor);
            }

            $this->laravel->make('files')->relativeLink($source, $public_vendor);
        }

        $this->info('Symlinks created');
    }
}
