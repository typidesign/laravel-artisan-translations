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

it('creates a new translation file when it does not exist', function (): void {
    $sourceFile = $this->sourcePath.'/fr.json';
    File::put($sourceFile, json_encode(['Hello' => 'Bonjour', 'Goodbye' => 'Au revoir']));

    $targetFile = $this->langPath.'/fr.json';
    File::delete($targetFile);

    $this->artisan('translations:add', ['path' => 'source-translations/fr.json'])
        ->expectsOutputToContain('created')
        ->assertExitCode(0);

    expect(File::exists($targetFile))->toBeTrue();

    $translations = json_decode(File::get($targetFile), true);

    expect($translations)->toHaveKey('Hello', 'Bonjour')
        ->toHaveKey('Goodbye', 'Au revoir');
});

it('merges translations without overwriting existing keys', function (): void {
    $targetFile = $this->langPath.'/fr.json';
    File::put($targetFile, json_encode(['Hello' => 'Salut']));

    $sourceFile = $this->sourcePath.'/fr.json';
    File::put($sourceFile, json_encode(['Hello' => 'Bonjour', 'Goodbye' => 'Au revoir']));

    $this->artisan('translations:add', ['path' => 'source-translations/fr.json'])
        ->assertExitCode(0);

    $translations = json_decode(File::get($targetFile), true);

    expect($translations)
        ->toHaveKey('Hello', 'Salut')
        ->toHaveKey('Goodbye', 'Au revoir');
});

it('overwrites existing keys when using the force option', function (): void {
    $targetFile = $this->langPath.'/fr.json';
    File::put($targetFile, json_encode(['Hello' => 'Salut']));

    $sourceFile = $this->sourcePath.'/fr.json';
    File::put($sourceFile, json_encode(['Hello' => 'Bonjour', 'Goodbye' => 'Au revoir']));

    $this->artisan('translations:add', ['path' => 'source-translations/fr.json', '--force' => true])
        ->assertExitCode(0);

    $translations = json_decode(File::get($targetFile), true);

    expect($translations)
        ->toHaveKey('Hello', 'Bonjour')
        ->toHaveKey('Goodbye', 'Au revoir');
});

it('sorts translations alphabetically after merging', function (): void {
    $targetFile = $this->langPath.'/fr.json';
    File::put($targetFile, json_encode(['Zebra' => 'Zèbre']));

    $sourceFile = $this->sourcePath.'/fr.json';
    File::put($sourceFile, json_encode(['Apple' => 'Pomme', 'Mango' => 'Mangue']));

    $this->artisan('translations:add', ['path' => 'source-translations/fr.json'])
        ->assertExitCode(0);

    $translations = json_decode(File::get($targetFile), true);
    $keys = array_keys($translations);

    expect($keys)->toBe(['Apple', 'Mango', 'Zebra']);
});

it('processes all files in a directory', function (): void {
    File::put($this->sourcePath.'/fr.json', json_encode(['Hello' => 'Bonjour']));
    File::put($this->sourcePath.'/nl.json', json_encode(['Hello' => 'Hallo']));

    $this->artisan('translations:add', ['path' => 'source-translations'])
        ->assertExitCode(0);

    expect(File::exists($this->langPath.'/fr.json'))->toBeTrue();
    expect(File::exists($this->langPath.'/nl.json'))->toBeTrue();
});

it('writes json with pretty print and unescaped unicode', function (): void {
    $targetFile = $this->langPath.'/fr.json';
    File::put($targetFile, json_encode(['Existing' => 'Existant']));

    $sourceFile = $this->sourcePath.'/fr.json';
    File::put($sourceFile, json_encode(['Café' => 'Café', 'Résumé' => 'Résumé']));

    $this->artisan('translations:add', ['path' => 'source-translations/fr.json'])
        ->assertExitCode(0);

    $content = File::get($targetFile);

    expect($content)->toContain('Café')
        ->toContain("\n");
});
