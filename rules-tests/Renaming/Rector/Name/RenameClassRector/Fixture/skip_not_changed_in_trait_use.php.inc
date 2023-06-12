<?php

namespace MyNamespace;

abstract class SkipNotChangedInTraitUse
{
    use LockDelete {
        LockDelete::extractObjectsId insteadof LockRemove;
    }
}