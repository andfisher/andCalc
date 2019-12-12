<?php

/**
 * @author Andrew Fisher
 */
class Form_Calculator_Factory
{
    public static function build()
    {
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/forms.ini', APPLICATION_ENV);
        return new Zend_Form($config->calendar);
    }
}
