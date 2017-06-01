<?php

namespace Typidesign\Translations\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

abstract class AbstractTranslations extends Command
{
    /**
     * The filesystem instance.
     */
    protected $files;

    /**
     * Create a new command instance.
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Get an array containing all translations form a json file.
     */
    protected function getTranslations(string $file) : array
    {
        return $this->files->exists($file) ? (array) json_decode($this->files->get($file)) : [];
    }

    /**
     * Update the json file with new object.
     */
    protected function put(string $file, array $translations)
    {
        $this->files->put($file, json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_FORCE_OBJECT));
    }

    /**
     * Get file(s) from the path argument.
     */
    protected function getFiles() : array
    {
        $fileOrDirectory = base_path($this->argument('path'));
        if (! $this->files->exists($fileOrDirectory)) {
            $this->error($this->argument('path').' is not a file or a directory.');
            exit();
        }
        if ($this->files->isFile($fileOrDirectory)) {
            $files = [$fileOrDirectory];
        } elseif ($this->files->isDirectory($fileOrDirectory)) {
            $files = $this->files->files($fileOrDirectory);
        }

        return $files;
    }
}
