<?php

/**
 * @author Andrew Fisher
 */
class Model_Calculator_Add implements Model_Calculator_Operator_Interface
{
    public function execute($a, $b)
    {
        return $a + $b;
    }
}