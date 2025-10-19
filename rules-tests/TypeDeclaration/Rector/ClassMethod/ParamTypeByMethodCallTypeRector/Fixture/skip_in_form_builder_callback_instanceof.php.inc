<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ParamTypeByMethodCallTypeRector\Fixture;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class SkipInFormBuilderCallbackInstanceof extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('someType', DocumentType::class, [
            'class' => SomeType::class,
            'required' => false,
            'label' => 'Some Type',
            'attr' => [
                'data-help' => 'some data help',
            ],
            'constraints' => [
                new Assert\Callback(function ($someType, ExecutionContextInterface $context): void {
                    if (! $someType instanceof SomeType) {
                        return;
                    }

                    $this->use($someType);

                    // some logic here
                })
            ]
        ]);
    }

    private function use(SomeType $someType): void
    {
    }
}
