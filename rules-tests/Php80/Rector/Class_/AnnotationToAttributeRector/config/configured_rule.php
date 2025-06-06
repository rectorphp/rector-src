<?php

declare(strict_types=1);

use Behat\Step\Then;
use Behat\Step\When;
use Rector\Config\RectorConfig;
use Rector\Php80\Rector\Class_\AnnotationToAttributeRector;
use Rector\Php80\ValueObject\AnnotationToAttribute;
use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\Annotation\OpenApi\Annotation\NestedPastAnnotation;
use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\Annotation\OpenApi\PastAnnotation;
use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\Attribute\NewName1;
use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\Attribute\NewName2;
use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\Attribute\OpenApi\Attribute\NestedFutureAttribute;
use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\Attribute\OpenApi\FutureAttribute;
use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\Attribute\SameName;
use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\GenericAnnotation;
use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\GenericSingleImplicitAnnotation;
use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\NotAnAttribute;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(AnnotationToAttributeRector::class, [
        new AnnotationToAttribute('Doctrine\ORM\Mapping\Embeddable'),

        // not an attribute yet
        new AnnotationToAttribute(NotAnAttribute::class),

        new AnnotationToAttribute(PastAnnotation::class, FutureAttribute::class),
        new AnnotationToAttribute(NestedPastAnnotation::class, NestedFutureAttribute::class),

        // use always this annotation to test inner part of annotation - arguments, arrays, calls...
        new AnnotationToAttribute(GenericAnnotation::class),
        new AnnotationToAttribute(GenericSingleImplicitAnnotation::class),

        new AnnotationToAttribute('Symfony\Component\Routing\Annotation\Route'),

        // doctrine
        new AnnotationToAttribute('Doctrine\ORM\Mapping\Entity', null, ['repositoryClass']),
        new AnnotationToAttribute('Doctrine\ORM\Mapping\DiscriminatorMap'),
        new AnnotationToAttribute('Doctrine\ORM\Mapping\Column'),

        // validation
        new AnnotationToAttribute('Symfony\Component\Validator\Constraints\Choice'),
        new AnnotationToAttribute('Symfony\Component\Validator\Constraints\Length'),
        new AnnotationToAttribute('Symfony\Component\Validator\Constraints\File'),

        // JMS + Symfony
        new AnnotationToAttribute('Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter'),

        // test for alias used
        new AnnotationToAttribute(
            'Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\UseAlias\TestSmth'
        ),
        new AnnotationToAttribute(
            'Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\UseAlias\TestOther'
        ),
        new AnnotationToAttribute('Sensio\Bundle\FrameworkExtraBundle\Configuration\Security'),

        new AnnotationToAttribute('Symfony\Component\Serializer\Attribute\Groups'),

        // special case with following comment becoming a inner value
        new AnnotationToAttribute('When', When::class, useValueAsAttributeArgument: true),
        new AnnotationToAttribute('Then', Then::class, useValueAsAttributeArgument: true),

        // simple tag to attribute
        new AnnotationToAttribute('OldName1', NewName1::class),
        new AnnotationToAttribute('OldName2', NewName2::class),
        new AnnotationToAttribute('SameName', SameName::class),

        new AnnotationToAttribute('OpenApi\Annotations\Property', 'OpenApi\Attributes\Property'),
    ]);
};
