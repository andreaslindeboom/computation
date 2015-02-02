<?php
namespace Computation\BigStepSemantics\Base;

interface Statement
{
    public function evaluate($environment);
}
