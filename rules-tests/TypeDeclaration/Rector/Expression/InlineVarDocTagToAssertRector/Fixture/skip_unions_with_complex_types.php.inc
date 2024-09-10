<?php

/** @var array<string>|false $foo */
$foo = getArrayOfString();

/** @var string[]|null $foo */
$foo = getArrayOfString();

/** @var string|Collection<Foo> $foo */
$foo = getCollection();

/** @var array<Foo>|Collection<Foo> $foo */
$foo = getCollectionOrArray();

