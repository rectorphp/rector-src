<?php

namespace Rector\Tests\Php80\Rector\Class_\FixtureDisallowModelBasedClasses\Fixture;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class SkipWithDoctrineAnnotation
{
    /**
     * @ORM\Column(type="text")
     */
    private string $content;

    public function __construct(string $content)
    {
        $this->content = $content;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
