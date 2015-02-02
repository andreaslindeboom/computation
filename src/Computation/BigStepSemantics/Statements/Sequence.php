<?php
namespace Computation\BigStepSemantics\Statements;

use Computation\BigStepSemantics\Base\Statement;

class Sequence implements Statement
{
    private $first, $second;

    public function __construct(Statement $first, Statement $second)
    {
        $this->first = $first;
        $this->second = $second;
    }

    public function evaluate($environment = [])
    {
        return $this->second->evaluate($this->first->evaluate($environment));
    }
}
