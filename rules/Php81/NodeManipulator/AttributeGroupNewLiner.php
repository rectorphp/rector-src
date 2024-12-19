<?php

declare(strict_types=1);

namespace Rector\Php81\NodeManipulator;

use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Property;
use Rector\ValueObject\Application\File;

final class AttributeGroupNewLiner
{
    public function newLine(File $file, Property|Param|Class_ $node): void
    {
        $oldTokens = $file->getOldTokens();
        $startTokenPos = $node->getStartTokenPos();

        if (! isset($oldTokens[$startTokenPos])) {
            return;
        }

        if ($oldTokens[$startTokenPos]->text !== '#[') {
            return;
        }

        $iteration = 1;
        $lastKey = array_key_last($node->attrGroups);

        if ($lastKey === null) {
            return;
        }

        $lastAttributeTokenPos = $node->attrGroups[$lastKey]->getEndTokenPos();

        while (isset($oldTokens[$startTokenPos + $iteration])) {
            if ($startTokenPos + $iteration === $lastAttributeTokenPos
                && $oldTokens[$startTokenPos + $iteration]->text === ']' &&
                trim($oldTokens[$startTokenPos + $iteration + 1]->text) === ''
            ) {
                $space = ltrim($oldTokens[$startTokenPos + $iteration + 1]->text ?? '', "\r\n");
                $oldTokens[$startTokenPos + $iteration]->text = "]\n" . $space;
                break;
            }

            ++$iteration;
        }
    }
}
