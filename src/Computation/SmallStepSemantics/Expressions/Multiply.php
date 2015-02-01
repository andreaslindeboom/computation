<?php
namespace Computation\SmallStepSemantics\Expressions;

use Computation\SmallStepSemantics\Base\Expression;
use Computation\SmallStepSemantics\Base\Reducible;
use Computation\SmallStepSemantics\ReducibleElement;

class Multiply extends Expression
{
    use Reducible;

    private $left, $right;

    public function __construct(Expression $left, Expression $right)
    {
        $this->left = $left;
        $this->right = $right;
    }

    public function reduce($environment)
    {
        if ($this->left->isReducible()) {
            $left = clone $this->left;
            list($left, $environment) = $left->reduce($environment);
            return [new Multiply($left, $this->right), $environment];
        }
        if ($this->right->isReducible()) {
            $right = clone $this->right;
            list($right, $environment) = $right->reduce($environment);
            return [new Multiply($this->left, $right), $environment];
        }
        return [new Number($this->left->getValue() * $this->right->getValue()), $environment];
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return "($this->left * $this->right)";
    }
}
