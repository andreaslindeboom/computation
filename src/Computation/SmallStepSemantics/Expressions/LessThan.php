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
            list($this->left, $environment) = $this->left->reduce($environment);
            return [$this, $environment];
        }
        if ($this->right->isReducible()) {
            list($this->right, $environment) = $this->right->reduce($environment);
            return [$this, $environment];
        }
        return [new Boolean($this->left < $this->right ? true : false), $environment];
    }
}
