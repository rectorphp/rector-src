<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Property\RemoveUnusedPrivatePropertyRector;
use Rector\Php70\Rector\Ternary\TernaryToNullCoalescingRector;
use Rector\Php80\Rector\Class_\AnnotationToAttributeRector;
use Rector\Php80\ValueObject\AnnotationToAttribute;
use Rector\Renaming\Rector\FuncCall\RenameFunctionRector;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Symfony\Symfony44\Rector\ClassMethod\ConsoleExecuteReturnIntRector;

return static function (RectorConfig $rectorConfig): void {
    /** enables @see \Rector\PostRector\Rector\NameImportingPostRector */
    $rectorConfig->importNames();

    $rectorConfig->ruleWithConfiguration(RenameClassRector::class, [
        'Some\Exception' => 'Some\Target\Exception',
        'DateTime' => 'DateTimeInterface',
        'Phalcon\Logger' => 'Phalcon\Logger\Logger',
    ]);

    $rectorConfig->rule(TernaryToNullCoalescingRector::class);

    $rectorConfig->ruleWithConfiguration(AnnotationToAttributeRector::class, [
        new AnnotationToAttribute('Doctrine\ORM\Mapping\Entity'),
    ]);

    $rectorConfig->rules([ConsoleExecuteReturnIntRector::class, RemoveUnusedPrivatePropertyRector::class]);

    $rectorConfig->ruleWithConfiguration(RenameFunctionRector::class, [
        'split' => 'explode',
    ]);
};
