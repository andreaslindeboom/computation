<?php
namespace Computation\FiniteAutomata\Deterministic;

/**
 * Class AutomatonRule
 * @package Computation\FiniteAutomata\Deterministic
 */
class AutomatonRule
{
    /**
     * @var integer
     */
    private $appliesToState;
    /**
     * @var string
     */
    private $matchesCharacter;
    /**
     * @var integer
     */
    private $followingState;

    /**
     * @param integer $appliesToState
     * @param string $matchesCharacter
     * @param integer $followingState
     */
    function __construct($appliesToState, $matchesCharacter, $followingState)
    {
        $this->appliesToState = $appliesToState;
        $this->matchesCharacter = $matchesCharacter;
        $this->followingState = $followingState;
    }

    /**
     * @param integer $state
     * @param string $character
     * @return boolean
     */
    public function appliesTo($state, $character)
    {
        return $state === $this->appliesToState && $character === $this->matchesCharacter;
    }

    /**
     * @return integer
     */
    public function follow()
    {
        return $this->followingState;
    }
}
