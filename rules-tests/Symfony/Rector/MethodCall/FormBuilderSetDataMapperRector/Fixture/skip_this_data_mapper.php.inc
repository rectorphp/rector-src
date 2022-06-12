<?php

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

namespace Rector\Tests\Symfony\Rector\MethodCall\FormBuilderSetDataMapperRector\Fixture;

final class SkipThisDataMapper extends AbstractType implements DataMapperInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->setDataMapper($this);
    }
}
