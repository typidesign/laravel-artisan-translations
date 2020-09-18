<?php

namespace Typidesign\Translations\Console\Commands;

class AddTranslations extends AbstractTranslations
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'translations:add
        {path : The file or directory containing the json encoded translations you want to merge.}
        {--force : Overwrite existing translations.}';

    /**
     * The console command description.
     */
    protected $description = 'Add translations strings found in a file or directory.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        foreach ($this->getFiles() as $file) {
            $targetDirectory = resource_path('lang');
            $targetPath = $targetDirectory.'/'.$file->getBasename();
            if ($this->files->missing($targetDirectory)) {
                $this->files->makeDirectory($targetDirectory);
            }
            if ($this->files->missing($targetPath)) {
                $this->files->copy($file->getPathname(), $targetPath);
                $this->info($targetPath.' created.');
                continue;
            }

            $existingTranslations = $this->getTranslations($targetPath);
            $newTranslations = $this->getTranslations($file);

            if ($this->option('force')) {
                $translations = array_merge($existingTranslations, $newTranslations);
            } else {
                $translations = $existingTranslations + $newTranslations;
            }
            ksort($translations, SORT_STRING | SORT_FLAG_CASE);

            $this->put($targetPath, $translations);

            $this->info(count($translations) - count($existingTranslations).' translations added in '.$targetPath.'.');
        }
    }
}
