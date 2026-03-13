<?php

declare(strict_types=1);

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
    public function handle(): void
    {
        foreach ($this->getFiles() as $file) {
            $targetDirectory = lang_path();
            $targetPath = $targetDirectory.'/'.$file->getBasename();
            if ($this->filesystem->missing($targetDirectory)) {
                $this->filesystem->makeDirectory($targetDirectory);
            }

            if ($this->filesystem->missing($targetPath)) {
                $this->filesystem->copy($file->getPathname(), $targetPath);
                $this->info($targetPath.' created.');

                continue;
            }

            $existingTranslations = $this->getTranslations($targetPath);
            $newTranslations = $this->getTranslations($file->getPathname());

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
