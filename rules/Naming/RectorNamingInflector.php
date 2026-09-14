<?php

declare(strict_types=1);

namespace Rector\Naming;

use Doctrine\Inflector\Inflector;
use Nette\Utils\Strings;

final readonly class RectorNamingInflector
{
    /**
     * @see https://regex101.com/r/VqVvke/3
     */
    private const string DATA_INFO_SUFFIX_REGEX = '#^(?<prefix>.+)(?<suffix>Data|Info)$#';

    /**
     * Mass nouns ending in lowercase "data"/"info", eg "metadata", must stay untouched
     */
    private const string MASS_NOUN_SUFFIX_REGEX = '#(?:data|info)$#';

    public function __construct(
        private Inflector $inflector
    ) {
    }

    public function singularize(string $name): string
    {
        $matches = Strings::match($name, self::DATA_INFO_SUFFIX_REGEX);
        if ($matches !== null) {
            $singularized = $this->inflector->singularize((string) $matches['prefix']);
            $uninflectable = $matches['suffix'];

            return $singularized . $uninflectable;
        }

        if (Strings::match($name, self::MASS_NOUN_SUFFIX_REGEX) !== null) {
            return $name;
        }

        return $this->inflector->singularize($name);
    }
}
