<?php

declare(strict_types=1);

namespace Typidesign\Translations\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use SplFileInfo;

abstract class AbstractTranslations extends Command
{
    /**
     * Create a new command instance.
     */
    public function __construct(protected Filesystem $filesystem)
    {
        parent::__construct();
    }

    /**
     * Get an array containing all translations form a json file.
     *
     * @return array<string, string>
     */
    protected function getTranslations(string $file): array
    {
        if (! $this->filesystem->exists($file)) {
            return [];
        }

        /** @var array<string, string> $translations */
        $translations = (array) json_decode((string) $this->filesystem->get($file));

        return $translations;
    }

    /**
     * Update the json file with new object.
     *
     * @param  array<string, string>  $translations
     */
    protected function put(string $file, array $translations): void
    {
        $this->filesystem->put($file, (string) json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_FORCE_OBJECT));
    }

    /**
     * Get file(s) from the path argument.
     *
     * @return array<int, SplFileInfo>
     */
    protected function getFiles(): array
    {
        /** @var string $pathArgument */
        $pathArgument = $this->argument('path');
        $path = base_path($pathArgument);

        if ($this->filesystem->missing($path)) {
            $this->error($pathArgument.' is not a file or a directory.');
            exit();
        }

        if ($this->filesystem->isFile($path)) {
            return [new SplFileInfo($path)];
        }

        if ($this->filesystem->isDirectory($path)) {
            /** @var array<int, SplFileInfo> */
            return $this->filesystem->files($path);
        }

        return [];
    }
}
