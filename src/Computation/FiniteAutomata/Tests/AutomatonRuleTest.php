<?php
namespace Computation\FiniteAutomata\Tests;

use Computation\FiniteAutomata\Deterministic\AutomatonRule;

class AutomatonRuleTest extends \PHPUnit_Framework_TestCase {

    /**
     * @test
     */
    public function shouldTellIfAppliesTo()
    {
        $rule = new AutomatonRule(1, 'a', 2);

        $this->assertTrue($rule->appliesTo(1, 'a'));
    }

    /**
     * @test
     */
    public function shouldGiveNextState()
    {
        $rule = new AutomatonRule(1, 'a', 2);

        $this->assertEquals(2, $rule->follow());
    }
}
