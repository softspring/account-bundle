<?php

$finder = PhpCsFixer\Finder::create()
    ->in(array_values(array_filter([
        is_dir(__DIR__.'/src') ? __DIR__.'/src' : null,
        is_dir(__DIR__.'/tests') ? __DIR__.'/tests' : null,
    ])));

$config = new PhpCsFixer\Config();
return $config->setRules([
        '@Symfony' => true,
        'full_opening_tag' => false,
        'phpdoc_separation' => false,
        'global_namespace_import' => ['import_classes' => true],
    ])
    ->setFinder($finder);
