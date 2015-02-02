<?php
namespace Computation\BigStepSemantics\Base;

interface Expression
{
    public function evaluate($environment);
}
