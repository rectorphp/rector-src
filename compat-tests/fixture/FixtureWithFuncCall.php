<?php

namespace Fixture;

final class FixtureWithFuncCall
{
    public function run(): int
    {
        return strlen('hello');
    }
}
