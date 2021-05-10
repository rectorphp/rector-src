<?php

declare(strict_types=1);

namespace Rector\Naming;

use Doctrine\Inflector\Inflector;
use Nette\Utils\Strings;

final class RectorNamingInflector
{
    /**
     * @var string
     * @see https://regex101.com/r/VqVvke/3
     */
    private const DATA_INFO_SUFFIX_REGEX = '#^(?<prefix>.+)(?<suffix>Data|Info)$#';

    public function __construct(
        private Inflector $inflector
    ) {
    }

    public function singularize(string $name): string
    {
        $matches = Strings::match($name, self::DATA_INFO_SUFFIX_REGEX);
        if ($matches === null) {
            return $this->inflector->singularize($name);
        }

        $singularized = $this->inflector->singularize($matches['prefix']);
        $uninflectable = $matches['suffix'];

        return $singularized . $uninflectable;
    }
}
