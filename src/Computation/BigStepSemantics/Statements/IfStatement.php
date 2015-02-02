<?php
namespace Computation\BigStepSemantics\Statements;

use Computation\BigStepSemantics\Base\Statement;
use Computation\BigStepSemantics\Expressions\Boolean;

class IfStatement implements Statement
{
    private $condition, $consequence, $alternative;

    public function __construct($condition, $consequence, $alternative)
    {
        $this->condition = $condition;
        $this->consequence = $consequence;
        $this->alternative = $alternative;
    }

    public function evaluate($environment = [])
    {
        if ($this->condition->evaluate($environment) == new Boolean(true)) {
            return $this->consequence->evaluate($environment);
        }
        return $this->alternative->evaluate($environment);
    }
}
