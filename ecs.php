<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\FunctionNotation\MethodArgumentSpaceFixer;
use PhpCsFixer\Fixer\Import\NoUnusedImportsFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocLineSpanFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ECSConfig $ecsConfig): void {
    $ecsConfig->paths([
        __DIR__ . '/config',
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);

    $ecsConfig->rules([
        NoUnusedImportsFixer::class,
    ]);

    $ecsConfig->sets([
        SetList::PSR_12,
        SetList::COMMON,
        SetList::CLEAN_CODE,
        SetList::STRICT,
    ]);

    $ecsConfig->skip([
        PhpdocLineSpanFixer::class,
        MethodArgumentSpaceFixer::class
    ]);

    $ecsConfig->parallel();
};