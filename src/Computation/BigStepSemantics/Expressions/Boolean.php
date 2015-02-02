<?php
namespace Computation\BigStepSemantics\Expressions;

use Computation\BigStepSemantics\Base\Expression;

class Boolean implements Expression
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function evaluate($environment = [])
    {
        return $this;
    }

    public function getValue()
    {
        return $this->value;
    }
}
