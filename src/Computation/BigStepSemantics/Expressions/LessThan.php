<?php
namespace Computation\BigStepSemantics\Expressions;

use Computation\BigStepSemantics\Base\Expression;

class LessThan implements Expression
{
    private $left, $right;

    public function __construct(Expression $left, Expression $right)
    {
        $this->left = $left;
        $this->right = $right;
    }

    public function evaluate($environment = [])
    {
        return new Boolean($this->left->evaluate($environment) < $this->right->evaluate($environment));
    }
}
