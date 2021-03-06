<?php
namespace Computation\SmallStepSemantics\Statements;

use Computation\SmallStepSemantics\Base\Reducible;
use Computation\SmallStepSemantics\Base\Statement;

class AssignVariable extends Statement
{
    use Reducible;

    private $variableName;
    private $value;

    public function __construct($variableName, $value)
    {
        $this->variableName = $variableName;
        $this->value = $value;
    }

    public function reduce($environment)
    {
        // To be determined: what do we want, reduce on read or reduce on write? for now, going with reduce on write.
        // If we want reduce on read we can comment this out.
        if ($this->value->isReducible()) {
            $value = clone $this->value;
            list($value, $environment) = $value->reduce($environment);
            return [new AssignVariable($this->variableName, $value), $environment];
        }

        $environment[$this->variableName] = $this->value;

        return [new DoNothing(), $environment];
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return "$this->variableName = $this->value";
    }
}
