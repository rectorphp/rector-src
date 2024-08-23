<?php

class DependentTestClass
{
    public function callFunction()
    {
        $dependencyTestClass = new DependencyTestClass();
        return $dependencyTestClass->calledFunction(1);
    }
}
