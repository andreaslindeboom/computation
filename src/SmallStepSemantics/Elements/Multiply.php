<?php
namespace Computation\SmallStepSemantics\Elements;

use Computation\SmallStepSemantics\Base\Element;
use Computation\SmallStepSemantics\Base\Reducible;
use Computation\SmallStepSemantics\ReducibleElement;

class Multiply extends Element
{
    use Reducible;

    private $left, $right;

    public function __construct(Element $left, Element $right)
    {
        $this->left = $left;
        $this->right = $right;
    }

    public function reduce($environment)
    {
        if ($this->left->isReducible()) {
            $this->left = $this->left->reduce($environment);
            return $this;
        }
        if ($this->right->isReducible()) {
            $this->right = $this->right->reduce($environment);
            return $this;
        }
        return new Number($this->left->getValue() * $this->right->getValue());
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return "($this->left * $this->right)";
    }
}
