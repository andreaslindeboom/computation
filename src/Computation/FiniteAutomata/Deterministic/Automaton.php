<?php
namespace Computation\FiniteAutomata\Deterministic;

/**
 * Class Automaton
 * @package Computation\FiniteAutomata\Deterministic
 */
class Automaton
{
    /**
     * @var integer
     */
    private $currentState;

    /**
     * @var integer[]
     */
    private $acceptingStates;

    /**
     * @var AutomatonRulebook
     */
    private $rulebook;

    /**
     * @param integer $currentState
     * @param array $acceptingStates
     * @param AutomatonRulebook $rulebook
     */
    function __construct($currentState, array $acceptingStates, AutomatonRulebook $rulebook)
    {
        $this->currentState = $currentState;
        $this->acceptingStates = $acceptingStates;
        $this->rulebook = $rulebook;
    }

    /**
     * @return boolean
     */
    public function isAccepting()
    {
        return in_array($this->currentState, $this->acceptingStates);
    }

    /**
     * @param string $character
     */
    public function readCharacter($character)
    {
        $this->currentState = $this->rulebook->nextState($this->currentState, $character);
    }

    /**
     * @param string $string
     */
    public function readString($string)
    {
        foreach (str_split($string) as $character) {
            $this->readCharacter($character);
        };
    }
}
