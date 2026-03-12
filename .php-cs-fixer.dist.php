<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

return (new Config())
    ->setRiskyAllowed(true)
    ->setRules([
        'psrautoload' => true,
        'PSR12' => true,
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'array_push' => true,
        'yoda_style' => false,
        'array_indentation' => true,
        'function_to_constant' => true,
        'explicit_string_variable' => true,
        'braces_position' => [
            'allow_single_line_anonymous_functions' => false,
        ],
        'cast_spaces' => ['space' => 'single'],
    ])
    // 💡 by default, Fixer looks for `*.php` files excluding `./vendor/` - here, you can groom this config
    ->setFinder(
        (new Finder())
            // 💡 root folder to check
            ->in('app', 'database', 'routes', 'tests')
            // 💡 additional files, eg bin entry file
            // ->append([__DIR__.'/bin-entry-file'])
            // 💡 folders to exclude, if any
            // ->exclude([/* ... */])
            // 💡 path patterns to exclude, if any
            // ->notPath([/* ... */])
            // 💡 extra configs
            // ->ignoreDotFiles(false) // true by default in v3, false in v4 or future mode
            // ->ignoreVCS(true) // true by default
    )
;
