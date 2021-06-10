<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Exception;

use Exception;
use Rector\TypeDeclaration\Contract\TypeInferer\PriorityAwareTypeInfererInterface;

final class ConflictingPriorityException extends Exception
{
    public function __construct(
        PriorityAwareTypeInfererInterface $firstPriorityAwareTypeInferer,
        PriorityAwareTypeInfererInterface $secondPriorityAwareTypeInferer
    ) {
        $message = sprintf(
            'There are 2 type inferers with %d priority:%s- %s%s- %s.%sChange value in "getPriority()" method in one of them to different value',
            $firstPriorityAwareTypeInferer->getPriority(),
            PHP_EOL,
            $firstPriorityAwareTypeInferer::class,
            PHP_EOL,
            $secondPriorityAwareTypeInferer::class,
            PHP_EOL
        );

        parent::__construct($message);
    }
}
