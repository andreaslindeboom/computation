<?php
namespace Computation\BigStepSemantics\Statements;

use Computation\BigStepSemantics\Base\Statement;

class DoNothing implements Statement
{
    public function evaluate($environment = [])
    {
        return $environment;
    }
}
