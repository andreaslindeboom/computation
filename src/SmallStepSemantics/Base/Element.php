<?php
namespace Computation\SmallStepSemantics\Base;

/**
 * Class Element
 * @package SmallStepSemantics
 */
abstract class Element
{
    /**
     * @return boolean
     */
    abstract public function isReducible();

    /**
     * @return string
     */
    abstract public function __toString();

}
