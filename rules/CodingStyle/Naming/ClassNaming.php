<?php

declare(strict_types=1);

namespace Rector\CodingStyle\Naming;

use Nette\Utils\Strings;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\ClassLike;

final class ClassNaming
{
    public function getShortName(string | Name | Identifier | ClassLike $name): string
    {
        if ($name instanceof ClassLike) {
            if (! $name->name instanceof Identifier) {
                return '';
            }

            return $this->getShortName($name->name);
        }

        if ($name instanceof Name || $name instanceof Identifier) {
            $name = $name->toString();
        }

        $name = trim($name, '\\');

        $shortName = Strings::after($name, '\\', -1);
        if (is_string($shortName)) {
            return $shortName;
        }

        return $name;
    }
}
