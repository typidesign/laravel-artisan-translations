<?php

use Illuminate\Support\Facades\File;

beforeEach(function (): void {
    $this->langPath = lang_path();

    File::ensureDirectoryExists($this->langPath);

    $this->sourcePath = base_path('source-translations');

    File::ensureDirectoryExists($this->sourcePath);
});

afterEach(function (): void {
    File::deleteDirectory($this->sourcePath);
    File::cleanDirectory($this->langPath);
});

it('removes translations that exist in the source file', function (): void {
    $targetFile = $this->langPath.'/fr.json';
    File::put($targetFile, json_encode(['Hello' => 'Bonjour', 'Goodbye' => 'Au revoir', 'Thanks' => 'Merci']));

    $sourceFile = $this->sourcePath.'/fr.json';
    File::put($sourceFile, json_encode(['Hello' => 'Bonjour', 'Thanks' => 'Merci']));

    $this->artisan('translations:remove', ['path' => 'source-translations/fr.json'])
        ->expectsOutputToContain('2 translations removed')
        ->assertExitCode(0);

    $translations = json_decode(File::get($targetFile), true);

    expect($translations)
        ->toHaveKey('Goodbye', 'Au revoir')
        ->not->toHaveKey('Hello')
        ->not->toHaveKey('Thanks');
});

it('does not remove translations with different values', function (): void {
    $targetFile = $this->langPath.'/fr.json';
    File::put($targetFile, json_encode(['Hello' => 'Salut', 'Goodbye' => 'Au revoir']));

    $sourceFile = $this->sourcePath.'/fr.json';
    File::put($sourceFile, json_encode(['Hello' => 'Bonjour']));

    $this->artisan('translations:remove', ['path' => 'source-translations/fr.json'])
        ->expectsOutputToContain('0 translations removed')
        ->assertExitCode(0);

    $translations = json_decode(File::get($targetFile), true);

    expect($translations)
        ->toHaveKey('Hello', 'Salut')
        ->toHaveKey('Goodbye', 'Au revoir');
});

it('processes all files in a directory', function (): void {
    File::put($this->langPath.'/fr.json', json_encode(['Hello' => 'Bonjour', 'Goodbye' => 'Au revoir']));
    File::put($this->langPath.'/nl.json', json_encode(['Hello' => 'Hallo', 'Goodbye' => 'Tot ziens']));

    File::put($this->sourcePath.'/fr.json', json_encode(['Hello' => 'Bonjour']));
    File::put($this->sourcePath.'/nl.json', json_encode(['Hello' => 'Hallo']));

    $this->artisan('translations:remove', ['path' => 'source-translations'])
        ->assertExitCode(0);

    $frTranslations = json_decode(File::get($this->langPath.'/fr.json'), true);
    $nlTranslations = json_decode(File::get($this->langPath.'/nl.json'), true);

    expect($frTranslations)->not->toHaveKey('Hello')->toHaveKey('Goodbye');
    expect($nlTranslations)->not->toHaveKey('Hello')->toHaveKey('Goodbye');
});

it('handles removing from an empty target file gracefully', function (): void {
    $targetFile = $this->langPath.'/fr.json';
    File::put($targetFile, json_encode([]));

    $sourceFile = $this->sourcePath.'/fr.json';
    File::put($sourceFile, json_encode(['Hello' => 'Bonjour']));

    $this->artisan('translations:remove', ['path' => 'source-translations/fr.json'])
        ->expectsOutputToContain('0 translations removed')
        ->assertExitCode(0);
});
