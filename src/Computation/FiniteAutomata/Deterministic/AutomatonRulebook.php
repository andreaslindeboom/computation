<?php
namespace Computation\FiniteAutomata\Deterministic;

/**
 * Class AutomatonRulebook
 * @package Computation\FiniteAutomata\Deterministic
 */
class AutomatonRulebook
{
    /**
     * @var AutomatonRule[]
     */
    private $rules;

    /**
     * @param AutomatonRule[] $rules
     */
    function __construct(array $rules)
    {
        $this->rules = $rules;
    }

    /**
     * @param integer $state
     * @param string $character
     * @return integer
     */
    public function nextState($state, $character)
    {
        return $this->ruleFor($state, $character)->follow();
    }

    /**
     * @param integer $state
     * @param string $character
     * @return AutomatonRule
     */
    private function ruleFor($state, $character)
    {
        return array_reduce($this->rules, function($result, $rule) use ($state, $character) {
            return is_null($result) && $rule->appliesTo($state, $character) ? $rule : $result;
        });
    }
}
