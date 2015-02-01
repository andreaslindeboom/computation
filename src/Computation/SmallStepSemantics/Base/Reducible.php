<?php
namespace Computation\SmallStepSemantics\Base;

trait Reducible
{
    public function isReducible()
    {
        return true;
    }

    abstract public function reduce($environment);
}
