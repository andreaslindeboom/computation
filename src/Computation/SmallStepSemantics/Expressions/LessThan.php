<?php
namespace Computation\SmallStepSemantics\Expressions;

use Computation\SmallStepSemantics\Base\Expression;
use Computation\SmallStepSemantics\Base\Reducible;

class LessThan extends Expression
{
    use Reducible;

    private $left, $right;

    public function __construct(Expression $left, Expression $right)
    {
        $this->left = $left;
        $this->right = $right;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return "$this->left < $this->right";
    }

    public function reduce($environment)
    {
        if ($this->left->isReducible()) {
            $left = clone $this->left;

            list($left, $environment) = $left->reduce($environment);
            return [new LessThan($left, $this->right), $environment];
        }
        if ($this->right->isReducible()) {
            $right = clone $this->right;

            list($right, $environment) = $right->reduce($environment);
            return [new LessThan($this->left, $right), $environment];
        }
        return [new Boolean($this->left < $this->right ? true : false), $environment];
    }
}
