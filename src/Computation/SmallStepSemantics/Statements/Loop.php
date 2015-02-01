<?php
namespace Computation\SmallStepSemantics\Statements;

use Computation\SmallStepSemantics\Base\Expression;
use Computation\SmallStepSemantics\Base\Reducible;
use Computation\SmallStepSemantics\Base\Statement;
use Computation\SmallStepSemantics\Expressions\Conditional;

class Loop extends Statement
{
    use Reducible;

    private $condition, $body;

    public function __construct(Expression $condition, Statement $body)
    {
        $this->condition = $condition;
        $this->body = $body;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return "while $this->condition do $this->body";
    }

    public function reduce($environment)
    {
        return [
            new Conditional(
                // if condition is not cloned, the reference will get reduced and the rest of the loop will work
                // with hardcoded values instead of variables.
                clone $this->condition,
                new Sequence(
                    $this->body,
                    $this
                )
            ),
            $environment
        ];
    }
}
