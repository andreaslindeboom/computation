<?php
namespace Computation\FiniteAutomata\Tests;

use Computation\FiniteAutomata\Deterministic\AutomatonRulebook;
use Computation\FiniteAutomata\Deterministic\Automaton;
use Computation\FiniteAutomata\Deterministic\AutomatonRule;

class AutomatonTest extends \PHPUnit_Framework_TestCase {

    private $rulebook;

    public function setUp()
    {
        $this->rulebook = new AutomatonRulebook([
            new AutomatonRule(1, 'a', 2), new AutomatonRule(1, 'b', 1),
            new AutomatonRule(2, 'a', 2), new AutomatonRule(2, 'b', 3),
            new AutomatonRule(3, 'a', 3), new AutomatonRule(3, 'b', 3),
        ]);
    }
    
    /**
     * @test
     */
    public function shouldTellIfIsAccepting()
    {
        $automaton = new Automaton(1, [1, 3], $this->rulebook);
        $this->assertTrue($automaton->isAccepting());

        $automaton = new Automaton(1, [3], $this->rulebook);
        $this->assertFalse($automaton->isAccepting());
    }

    /**
     * @test
     */
    public function shouldReadCharacter()
    {
        $automaton = new Automaton(1, [3], $this->rulebook);
        $this->assertFalse($automaton->isAccepting());

        $automaton->readCharacter('b');
        $this->assertFalse($automaton->isAccepting());

        foreach (range(1, 3) as $i) {
            $automaton->readCharacter('a');
        }
        $this->assertFalse($automaton->isAccepting());

        $automaton->readCharacter('b');
        $this->assertTrue($automaton->isAccepting());
    }

    /**
     * @test
     */
    public function shouldReadString()
    {
        $automaton = new Automaton(1, [3], $this->rulebook);
        $this->assertFalse($automaton->isAccepting());

        $automaton->readString('baab');
        $this->assertTrue($automaton->isAccepting());
    }
}
