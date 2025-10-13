<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamStringTypeFromSprintfUseRector\Fixture;

class SkipPossibleInt
{
    public function error_handler_errortype($errno): string
    {
        return match ($errno) {
            \E_USER_ERROR, \E_ERROR, \E_COMPILE_ERROR, \E_RECOVERABLE_ERROR => 'Fatal error',
            \E_PARSE => 'Parse error',
            \E_USER_WARNING, \E_WARNING, \E_COMPILE_WARNING => 'Warning',
            \E_USER_NOTICE, \E_NOTICE => 'Notice',
            \E_USER_DEPRECATED, \E_DEPRECATED => 'Deprecated',
            default => sprintf('Unknown (%s)', $errno),
        };
    }
}