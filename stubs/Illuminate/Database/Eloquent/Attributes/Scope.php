<?php

namespace Illuminate\Database\Eloquent\Attributes;

use Attribute;

if (class_exists('Illuminate\Database\Eloquent\Attributes\Scope')) {
    return;
}

#[Attribute(Attribute::TARGET_METHOD)]
class Scope
{
}
