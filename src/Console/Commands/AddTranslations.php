<?php

namespace Typidesign\Translations\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class AddTranslations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translations:add
        {path : The file or directory containing the json encoded translations you want to merge.}
        {--force : Overwrite existing translations.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add translations strings found in a file or directory.';

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $fileOrDirectory = base_path($this->argument('path'));
        if (!$this->files->exists($fileOrDirectory)) {
            return $this->error($this->argument('path').' is not a file or a directory.');
        }
        if ($this->files->isFile($fileOrDirectory)) {
            $files = [$fileOrDirectory];
        } else if ($this->files->isDirectory($fileOrDirectory)) {
            $files = $this->files->files($fileOrDirectory);
        }
        foreach ($files as $file) {
            $mainFile = resource_path('lang/'.basename($file));

            $existingTranslations = $this->getTranslations($mainFile);
            $newTranslations = $this->getTranslations($file);

            if ($this->option('force')) {
                $translations = array_merge($existingTranslations, $newTranslations);
            } else {
                $translations = $existingTranslations + $newTranslations;
            }
            ksort($translations);

            $this->files->put($mainFile, json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

            $this->info(count($translations) - count($existingTranslations).' translations added in '.$mainFile.'.');
        }
    }

    /**
     * Get an array containing all translations form a json file
     *
     * @param string $file
     *
     * @return array
     */
    private function getTranslations($file)
    {
        return $this->files->exists($file) ? (array) json_decode($this->files->get($file)) : [];
    }
}
