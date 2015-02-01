<?php
namespace Computation\SmallStepSemantics\Statements;

use Computation\SmallStepSemantics\Base\Expression;
use Computation\SmallStepSemantics\Base\Reducible;
use Computation\SmallStepSemantics\Base\Statement;
use Computation\SmallStepSemantics\Expressions\Boolean;

class Conditional extends Statement
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
            $condition = clone $this->condition;
            list($condition, $environment) = $condition->reduce($environment);
            return [new Conditional($condition, $this->consequence, $this->alternative), $environment];
        }
        if ($this->condition == new Boolean(true)) {
            return [$this->consequence, $environment];
        } else {
            return [$this->alternative, $environment];
        }
    }
}
