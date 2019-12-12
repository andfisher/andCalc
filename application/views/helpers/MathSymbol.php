<?php

class Zend_View_Helper_MathSymbol extends Zend_View_Helper_Abstract
{
	public function mathSymbol($in)
	{
		switch ($in) {
			case 'ADDITION':
				return '+';
			case 'SUBTRACT':
				return '-';
			case 'MULTIPLY':
				return '&times;';
			case 'DIVIDE':
				return '&divide;';
        }
		return '';
	}
}