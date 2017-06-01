<?php

namespace Typidesign\Translations\Console\Commands;

class RemoveTranslations extends AbstractTranslations
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'translations:remove
        {path : The file or directory containing the json encoded translations you want to remove.}';

    /**
     * The console command description.
     */
    protected $description = 'Remove translations strings found in a file or directory.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        foreach ($this->getFiles() as $file) {
            $mainFile = resource_path('lang/'.basename($file));

            $existingTranslations = $this->getTranslations($mainFile);
            $newTranslations = $this->getTranslations($file);

            $translations = array_diff($existingTranslations, $newTranslations);

            $this->put($mainFile, $translations);

            $this->info(count($existingTranslations) - count($translations).' translations removed in '.$mainFile.'.');
        }
    }
}
