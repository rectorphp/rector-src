<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveTypedPropertyDeadInstanceOfRector\Fixture;

/**
 * @property Document|Course|Book $target
 */
class SearchResultClickedResource
{
    /**
     * @return 'document'|'course'|'book'|'unknown'
     */
    private function getType(): string
    {
        if ($this->target instanceof Document) {
            return 'document';
        }

        if ($this->target instanceof Course) {
            return 'course';
        }

        if ($this->target instanceof Book) {
            return 'book';
        }

        return 'unknown';
    }
}
