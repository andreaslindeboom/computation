<?php
namespace Computation\SmallStepSemantics\Expressions;

use Computation\SmallStepSemantics\Base\Element;
use Computation\SmallStepSemantics\Base\Reducible;
use Computation\SmallStepSemantics\Base\Statement;

class Conditional extends Element
{
    use Reducible;

    private $condition, $consequence, $alternative;

    public function __construct(Element $condition, Statement $consequence, Statement $alternative)
    {
        $this->condition = $condition;
        $this->consequence = $consequence;
        $this->alternative = $alternative;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return "if ($this->condition) { $this->consequence } else { $this->alternative }";
    }

    public function reduce($environment)
    {
        if ($this->condition->isReducible()) {
            $this->condition = $this->condition->reduce();
            return [$this, $environment];
        }
        if ($this->condition == new Boolean(true)) {
            return [$this->consequence, $environment];
        } else {
            return [$this->alternative, $environment];
        }
    }
}
