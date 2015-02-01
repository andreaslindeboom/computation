<?php
namespace Computation\SmallStepSemantics\Statements;

use Computation\SmallStepSemantics\Base\NotReducible;
use Computation\SmallStepSemantics\Base\Statement;

class DoNothing extends Statement
{
    use NotReducible;

    /**
     * @return string
     */
    public function __toString()
    {
        return "do nothing";
    }
}
