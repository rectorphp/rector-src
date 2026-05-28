<?php

declare(strict_types=1);

<<<<<<< HEAD
use Rector\CodeQuality\Rector\CallLike\AddNameToNullArgumentRector;
use Rector\CodeQuality\Rector\FuncCall\SortCallLikeNamedArgsRector;
use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\MethodCall\RemoveNullArgOnNullDefaultParamRector;

return RectorConfig::configure()
    ->withRules([
        AddNameToNullArgumentRector::class,
        SortCallLikeNamedArgsRector::class,
        RemoveNullArgOnNullDefaultParamRector::class,
    ]);
=======
use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets([SetList::NAMED_ARGS]);
};
>>>>>>> 3fcc6cfe4a (split of RemoveNullNamedArgOnNullDefaultParamRector to handle only named args)
