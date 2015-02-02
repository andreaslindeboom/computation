<?php
namespace Computation\BigStepSemantics\Statements;

use Computation\BigStepSemantics\Base\Expression;
use Computation\BigStepSemantics\Base\Statement;
use Computation\BigStepSemantics\Expressions\Boolean;

class WhileLoop implements Statement
{
    private $condition, $body;

    public function __construct(Expression $condition, Statement $body)
    {
        $this->condition = $condition;
        $this->body = $body;
    }

    public function evaluate($environment = [])
    {
        if ($this->condition->evaluate($environment) == new Boolean(true)) {
            return $this->evaluate($this->body->evaluate($environment));
        }
        if ($this->condition->evaluate($environment) == new Boolean(false)) {
            return $environment;
        }
    }
}
