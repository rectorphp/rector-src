<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php70\Rector\Ternary\TernaryToNullCoalescingRector;
use Rector\Php80\Rector\Class_\AnnotationToAttributeRector;
use Rector\Php80\ValueObject\AnnotationToAttribute;
use Rector\Renaming\Rector\Name\RenameClassRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->importNames();
    $rectorConfig->ruleWithConfiguration(RenameClassRector::class, [
        'Some\Exception' => 'Some\Target\Exception',
    ]);
    $rectorConfig->rule(TernaryToNullCoalescingRector::class);
    $rectorConfig->ruleWithConfiguration(AnnotationToAttributeRector::class, [
        new AnnotationToAttribute('Doctrine\ORM\Mapping\Entity'),
    ]);
};
