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
            list($this->value, $environment) = $this->value->reduce($environment);
            return [$this, $environment];
        }

        $environment[$this->variableName] = $this->value;

        return [new DoNothing(), $environment];
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return "set: $this->variableName ($this->value)";
    }
}
