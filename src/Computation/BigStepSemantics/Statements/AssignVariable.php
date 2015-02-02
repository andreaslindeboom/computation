<?php
namespace Computation\BigStepSemantics\Statements;

use Computation\BigStepSemantics\Base\Statement;
use Computation\BigStepSemantics\Base\Expression;

class AssignVariable implements Statement
{
    private $label, $value;

    function __construct($label, Expression $value)
    {
        $this->label = $label;
        $this->value = $value;
    }

    public function evaluate($environment = [])
    {
        $environment[$this->label] = $this->value->evaluate($environment);

        return $environment;
    }
}
