<?php

declare(strict_types=1);

use Symplify\CodingStandard\Fixer\LineLength\LineLengthFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;

return ECSConfig::configure()
    ->withCache(directory: __DIR__ . '/.build/ecs/', namespace: getcwd())
    ->withPaths([__DIR__ . '/src', __DIR__ . '/test'])
    ->withRootFiles()
    ->withRules([LineLengthFixer::class])
    ->withPreparedSets(psr12: true, common: true)

;
