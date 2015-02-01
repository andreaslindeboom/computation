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
            list($this->first, $environment) = $this->first->reduce($environment);
            return [$this, $environment];
        }
        if ($this->second != new DoNothing()) {
            list($this->second, $environment) = $this->second->reduce($environment);
            return [$this, $environment];
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
