<?php

/**
 * @author Andrew Fisher
 */
class Model_Calculator_Multiply implements Model_Calculator_Operator_Interface
{
    public function execute($a, $b)
    {
        return $a * $b;
    }
}