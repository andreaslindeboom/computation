<?php
namespace Computation\SmallStepSemantics\Base;

trait NotReducible
{
    public function isReducible()
    {
        return false;
    }
}
