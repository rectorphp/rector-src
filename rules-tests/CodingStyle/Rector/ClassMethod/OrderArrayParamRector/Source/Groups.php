<?php

declare(strict_types=1);

namespace Rector\Tests\CodingStyle\Rector\ClassMethod\OrderArrayParamRector\Source;

/**
 * from: https://github.com/symfony/symfony/blob/6.3/src/Symfony/Component/Serializer/Annotation/Groups.php
 */
#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::TARGET_PROPERTY)]
class Groups
{
    /**
     * @var string[]
     */
    private readonly array $groups;

    /**
     * @param string|string[] $groups
     */
    public function __construct(string|array $groups)
    {
        $this->groups = (array) $groups;

        if (!$this->groups) {
            throw new \InvalidArgumentException(sprintf('Parameter of annotation "%s" cannot be empty.', static::class));
        }

        foreach ($this->groups as $group) {
            if (!\is_string($group) || '' === $group) {
                throw new \InvalidArgumentException(sprintf('Parameter of annotation "%s" must be a string or an array of non-empty strings.', static::class));
            }
        }
    }

    /**
     * @return string[]
     */
    public function getGroups(): array
    {
        return $this->groups;
    }
}
