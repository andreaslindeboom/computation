<?php
namespace Computation\BigStepSemantics\Expressions;

use Computation\BigStepSemantics\Base\Expression;

class Add implements Expression
{
    private $left, $right;

    public function __construct(Expression $left, Expression $right)
    {
        $this->left = $left;
        $this->right = $right;
    }

    public function evaluate($environment = [])
    {
        return new Number(
            $this->left->evaluate($environment)->getValue() + $this->right->evaluate($environment)->getValue()
        );
    }
}
