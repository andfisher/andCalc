<?php

require_once dirname(__FILE__) . '/../../../application/models/Calculator/Divide.php';

class Model_Calculator_DivideTest extends PHPUnit_Framework_TestCase {

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {}

    public function testDivisionOperator()
    {
        $division = new Model_Calculator_Divide();

        try {
            $division->execute(10, 0);
        } catch (Model_Calculator_Divide_Exception $e) {
            $this->pass('Division by zero generates a catchable Model_Calculator_Divide_Exception');
        } catch (Exception $e) {
            $this->fail('Division by zero DOES NOT generate a Model_Calculator_Divide_Exception');
        }
    }

    public function testDivisionResults()
    {
        $division = new Model_Calculator_Divide();

        $this->assertEquals($division->execute(144, 12), 12);
        $this->assertEquals($division->execute(64, 8), 8);
        $this->assertEquals($division->execute(100, 10), 10);
    }

}

