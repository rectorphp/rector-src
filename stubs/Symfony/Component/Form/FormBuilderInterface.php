<?php

namespace Symfony\Component\Form;

if (interface_exists('Symfony\Component\Form\FormBuilderInterface')) {
    return;
}

class FormBuilderInterface
{
    public function add(string|self $child, ?string $type = null, array $options = []): static;
}
