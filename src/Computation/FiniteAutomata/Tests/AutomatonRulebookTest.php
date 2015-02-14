<?php
namespace Computation\FiniteAutomata\Tests;

use Computation\FiniteAutomata\Deterministic\AutomatonRule;
use Computation\FiniteAutomata\Deterministic\AutomatonRulebook;

class AutomatonRulebookTest extends \PHPUnit_Framework_TestCase {

    /**
     * @test
     */
    public function shouldReturnNextState()
    {
        $rulebook = new AutomatonRulebook([
            new AutomatonRule(1, 'a', 2), new AutomatonRule(1, 'b', 1),
            new AutomatonRule(2, 'a', 2), new AutomatonRule(2, 'b', 3),
            new AutomatonRule(3, 'a', 3), new AutomatonRule(3, 'b', 3),
        ]);

        $this->assertEquals(2, $rulebook->nextState(1, 'a'));
        $this->assertEquals(1, $rulebook->nextState(1, 'b'));
        $this->assertEquals(3, $rulebook->nextState(2, 'b'));
    }
}
