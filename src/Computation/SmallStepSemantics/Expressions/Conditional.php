<?php
namespace Computation\SmallStepSemantics\Expressions;

use Computation\SmallStepSemantics\Base\Expression;
use Computation\SmallStepSemantics\Base\Reducible;
use Computation\SmallStepSemantics\Base\Statement;
use Computation\SmallStepSemantics\Statements\DoNothing;

class Conditional extends Expression
{
    use Reducible;

    private $condition, $consequence, $alternative;

    public function __construct(Expression $condition, Statement $consequence, Statement $alternative = null)
    {
        $this->condition = $condition;
        $this->consequence = $consequence;

        if ($alternative === null) {
            $alternative = new DoNothing();
        }

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
            list($this->condition, $environment) = $this->condition->reduce($environment);
            return [$this, $environment];
        }
        if ($this->condition == new Boolean(true)) {
            return [$this->consequence, $environment];
        } else {
            return [$this->alternative, $environment];
        }
    }
}
