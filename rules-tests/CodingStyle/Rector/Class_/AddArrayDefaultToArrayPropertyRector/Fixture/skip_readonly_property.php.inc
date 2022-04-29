<?php

declare(strict_types=1);

/**
 * Allows serializing model without loosing changed values.
 */
namespace Rector\Tests\CodingStyle\Rector\Class_\AddArrayDefaultToArrayPropertyRector\Fixture;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\SerializesModels;

abstract class SkipReadonlyProperty
{
    use SerializesModels;

    /**
     * Contains model changes the new values.
     *
     * @var array<string, array>
     */
    public readonly array $changes;

    public function __construct(Model $model)
    {
        $this->changes = $model->getChanges();
    }
}

?>

