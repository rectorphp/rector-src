<?php

namespace MyNamespace;

trait LockDelete {
    public function extractObjectsId() {}
}

abstract class SkipNotChangedInTraitUse
{
    use LockDelete {
        LockDelete::extractObjectsId insteadof LockRemove;
    }
}