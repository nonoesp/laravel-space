<?php

namespace Nonoesp\Folio\Commands;

use Illuminate\Console\Command;
use InvalidArgumentException;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'folio:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup a fresh Folio install.';

    /**
     * Execute the console command.
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     */
    public function handle()
    {
        $this->ensureDirectoriesExist();
        $this->symlinkUploadsFolder();
        $this->publishImageAssets();
        
        $this->info('Folio was installed successfully.');
    }

    /**
     * Copy Folio image resources.
     */
    protected function publishImageAssets() {

        // https://github.com/laravel/ui/blob/2.x/src/AuthCommand.php#L93-L104

        $destination = public_path('img/folio');
        $imageResourcesPath = __DIR__.'/../../resources/images/';
        $imagePaths =  \File::glob($imageResourcesPath.'*');
        foreach ($imagePaths as $index=>$origin) {
            $filename = str_replace($imageResourcesPath, '', $origin);
            $target = $destination.'/'.$filename;

            if (\File::exists($target)) {
                continue;
            }

            copy($origin, $target);
        }

    }

    /**
     * Create the directories for the files.
     *
     * @return void
     */
    protected function ensureDirectoriesExist()
    {
        if (! is_dir($directory = storage_path('app/public/uploads'))) {
            mkdir($directory, 0755, true);
        }

        if (! is_dir($directory = public_path('img'))) {
            mkdir($directory, 0755, true);
        }
        
        if (! is_dir($directory = public_path('img/folio'))) {
            mkdir($directory, 0755, true);
        }
    }

    /**
     * Symlink uploads folder.
     */
    protected function symlinkUploadsFolder()
    {
        $publicFolder = public_path(config('folio.uploader.public-folder'));
        if (! is_dir($publicFolder)) {
            $uploadsFolder = storage_path('app/public/'.config('folio.uploader.uploads-folder'));
            symlink($uploadsFolder, $publicFolder);
        } else {
            $this->comment('Symlink already exists.');
        }
    }

}