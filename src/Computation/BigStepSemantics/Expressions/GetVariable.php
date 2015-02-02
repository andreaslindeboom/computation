<?php
namespace Computation\BigStepSemantics\Expressions;

use Computation\BigStepSemantics\Base\Expression;

class GetVariable implements Expression
{
    private $label;

    public function __construct($label)
    {
        $this->label = $label;
    }

    public function evaluate($environment = [])
    {
        return $environment[$this->label]->evaluate($environment);
    }
}
