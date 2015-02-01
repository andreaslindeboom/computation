<?php
namespace Computation\SmallStepSemantics\Statements;

use Computation\SmallStepSemantics\Base\Reducible;
use Computation\SmallStepSemantics\Base\Statement;

class Sequence extends Statement
{
    use Reducible;

    private $first, $second;

    public function __construct(Statement $first, Statement $second)
    {
        $this->first = $first;
        $this->second = $second;
    }

    public function reduce($environment)
    {
        if ($this->first != new DoNothing()) {
            $first = clone $this->first;
            list($first, $environment) = $first->reduce($environment);
            return [new Sequence($first, $this->second), $environment];
        }
        if ($this->second != new DoNothing()) {
            $second = clone $this->second;
            list($second, $environment) = $second->reduce($environment);
            return [new Sequence($this->first, $second), $environment];
        }
        return [new DoNothing(), $environment];
    }

    /**
     * @return string
     */
    public function __toString()
    {
        if ($this->first != new DoNothing()) {
            return "$this->first";
        }
        if ($this->second != new DoNothing()) {
            return "$this->second";
        }
        return "sequence ended";
    }
}
