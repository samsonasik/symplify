# The Easiest Way to Use Any Coding Standard

[![Downloads total](https://img.shields.io/packagist/dt/symplify/easy-coding-standard.svg?style=flat-square)](https://packagist.org/packages/symplify/easy-coding-standard/stats)

![ECS-Run](docs/run-and-fix.gif)

## Features

- Use [PHP_CodeSniffer || PHP-CS-Fixer](https://www.tomasvotruba.com/blog/2017/05/03/combine-power-of-php-code-sniffer-and-php-cs-fixer-in-3-lines/) - anything you like
- **2nd run under few seconds** with un-changed file cache
- [Skipping files](#ignore-what-you-cant-fix) for specific checkers
- [Prepared sets](#use-prepared-checker-sets) - PSR12, Symfony, Common, Array, Symplify and more...
- Use [Prefixed version](https://github.com/symplify/easy-coding-standard-prefixed) to prevent conflicts on install

Are you already using another tool?

- [How to Migrate From PHP_CodeSniffer to EasyCodingStandard in 7 Steps](https://www.tomasvotruba.com/blog/2018/06/04/how-to-migrate-from-php-code-sniffer-to-easy-coding-standard/#comment-4086561141)
- [How to Migrate From PHP CS Fixer to EasyCodingStandard in 6 Steps](https://www.tomasvotruba.com/blog/2018/06/07/how-to-migrate-from-php-cs-fixer-to-easy-coding-standard/)

## Install

```bash
composer require symplify/easy-coding-standard --dev
```

### Prefixed Version

Head over to the ["Easy Coding Standard Prefixed" repository](https://github.com/symplify/easy-coding-standard-prefixed) for more information.

## Usage

### 1. Create Configuration and Setup Checkers

- Create an `ecs.php` in your root directory
- Add [Sniffs](https://github.com/squizlabs/PHP_CodeSniffer)
- ...or [Fixers](https://github.com/FriendsOfPHP/PHP-CS-Fixer) you'd love to use

```php
<?php

// ecs.php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use PhpCsFixer\Fixer\ArrayNotation\ArraySyntaxFixer;
use Symplify\EasyCodingStandard\Configuration\Option;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ContainerConfigurator $containerConfigurator): void {
    // A. standalone rule
    $services = $containerConfigurator->services();
    $services->set(ArraySyntaxFixer::class)
        ->call('configure', [[
            'syntax' => 'short',
        ]]);

    // B. full sets
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::SETS, [
        SetList::CLEAN_CODE,
        SetList::PSR_12,
    ]);
};
```

### 2. Run in CLI

```bash
# dry
vendor/bin/ecs check src

# fix
vendor/bin/ecs check src --fix
```

## Features

How to load own config?

```bash
vendor/bin/ecs check src --config another-config.php
```

### Extended Configuration

Configuration can be extended with many options. Here is list of them with example values and little description what are they for:

```php
<?php

// ecs.php

declare(strict_types=1);

use PhpCsFixer\Fixer\ArrayNotation\ArraySyntaxFixer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\EasyCodingStandard\Configuration\Option;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();

    // alternative to CLI arguments, easier to maintain and extend
    $parameters->set(Option::PATHS, [
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);

    // exlude paths with really nasty code
    $parameters->set(Option::EXCLUDE_PATHS, [
        __DIR__ . '/packakges/*/src/Legacy',
    ]);

    // run single rule only on specific path
    $parameters->set(Option::ONLY, [
        ArraySyntaxFixer::class => [
            __DIR__ . '/src/NewCode'
        ]
    ]);

    $parameters->set(Option::SKIP, [
        ArraySyntaxFixer::class => [
            # path to file (you can copy this from error report)
            __DIR__ . '/packages/EasyCodingStandard/packages/SniffRunner/src/File/File.php',

            # or multiple files by path to match against "fnmatch()"
            __DIR__ . '/packages/*/src/Command'
        ],

        // skip rule compeltely
        ArraySyntaxFixer::class => null,

        // just single one part of the rule?
        ArraySyntaxFixer::class . '.SomeSingleOption' => null,

        // ignore specific error message
        'Cognitive complexity for method "addAction" is 13 but has to be less than or equal to 8.' => null,
    ]);

    // scan other file extendsions; [default: [php]]
    $parameters->set(Option::FILE_EXTENSIONS, ['php', 'phpt']);

    // configure cache paths & namespace - useful for Gitlab CI caching, where getcwd() produces always different path
    // fdefault: sys_get_temp_dir() . '/_changed_files_detector_tests']
    $parameters->set(Option::CACHE_DIRECTORY, '.ecs_cache');

    // [default: \Nette\Utils\Strings::webalize(getcwd())']
    $parameters->set(Option::CACHE_NAMESPACE, 'my_project_namespace');

    // indent and tabs/spaces
    // [default: spaces]
    $parameters->set(Option::INDENTATION, 'tab');

    // [default: PHP_EOL]; other options: "\n"
    $parameters->set(Option::LINE_ENDING, "\r\n");
};

```

### FAQ

#### How can I see all loaded checkers?

```bash
vendor/bin/ecs show
vendor/bin/ecs show --config ...
```

#### How do I clear cache?

```bash
vendor/bin/ecs check src --clear-cache
```

## Your IDE Integration

### PHPStorm

EasyCodingStandard can be used as an External Tool

![PHPStorm Configuration](docs/phpstorm-config.png)

Go to `Preferences` > `Tools` > `External Tools` and click `+` to add a new tool.

- Name: `ecs` (Can be any value)
- Description: `easyCodingStandard` (Can be any value)
- Program: `$ProjectFileDir$/vendor/bin/ecs` (Path to `ecs` executable; On Windows path separators must be a `\`)
- Parameters: `check $FilePathRelativeToProjectRoot$` (append `--fix` to auto-fix)
- Working directory: `$ProjectFileDir$`

Press `Cmd/Ctrl` + `Shift` + `A` (Find Action), search for `ecs`, and then hit Enter. It will run `ecs` for the current file.

To run `ecs` on a directory, right click on a folder in the project browser go to external tools and select `ecs`.

You can also create a keyboard shortcut in [Preferences > Keymap](https://www.jetbrains.com/help/webstorm/configuring-keyboard-and-mouse-shortcuts.html) to run `ecs`.

### Visual Studio Code

[EasyCodingStandard for Visual Studio Code](https://marketplace.visualstudio.com/items?itemName=azdanov.vscode-easy-coding-standard) extension adds support for running EasyCodingStandard inside the editor.

## Tool Integration
| Tool | Extension | Description |
| ---- | --------- | ----------- |
| [GrumPHP](https://github.com/phpro/grumphp) | [ECS Task](https://github.com/phpro/grumphp/blob/master/doc/tasks/ecs.md) | Provides a new task for GrumPHP which runs ECS |

## Contributing

Send [issue](https://github.com/symplify/symplify/issues) or [pull-request](https://github.com/symplify/symplify/pulls) to main repository.
