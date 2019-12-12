<?php
/**
 * @copyright Copyright (c) 2012, CACI Limited.
 * @author Andrew Fisher
 */

class Plugin_Blacklist extends Zend_Controller_Plugin_Abstract
{
    
    /**
     * @function preDispatch()
     * @desc Before dispatch of an action, run a Blacklist check on any GET or POSTed data
     * @access public
     * @param Zend_Controller_Request_Abstract $request
     * @return void
     */
    
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        try {
        
            if ($request->isPost() || $request->isGet()) {

                $params = $_REQUEST;

                foreach ($params AS $key => $value) {
                    $this->iterateParams($value);
                }
            }
            
         } catch (Plugin_Blacklist_Exception $e) {

            // Repoint the request to the default error handler
            $request->setModuleName('default');
            $request->setControllerName('error');
            $request->setActionName('error');

            // Set up the error handler
            $error = new Zend_Controller_Plugin_ErrorHandler();
            $error->type = Plugin_Blacklist_Exception::EXCEPTION_BLACKLISTED_VALUE;
            $error->request = clone($request);
            $error->exception = $e;
            $request->setParam('error_handler', $error);
        }
    }
    
    
    /**
     * @function iterateParams()
     * @desc Recursively itterate through the submitted user input
     * @access protected
     * @param type $param
     * @throws Exception
     */
    
    protected function iterateParams($param)
    {
        if (is_string($param)) {
            
            if (! $this->blacklistCheck($param))
                $this->fail();
            
        } elseif (is_array($param)) {
            
            foreach($param AS $p) {
                $this->iterateParams($p);
            }

        } else {
            throw new Exception(Plugin_Blacklist_Exception::EXCEPTION_NOT_STRING_OR_ARRAY);
        }
    }
    
    
    /**
     * @function blacklistCheck()
     * @desc Run a string through a series of blacklisted parameters to find a match
     * @access public
     * @param string $string
     * @return boolean
     */
    
    public function blacklistCheck($string)
    {
        // Let's remove space type chars before we start matching, in case any
        // dodgy h4x0r tries to use them to fool our blacklist.
        $string = preg_replace('/\s/', '', $string);
        
        $pass = $this->_blacklistCheck($string);
        
        if ($pass) {
            
            // One more check, replace script comment blocks and check again.
            
            $string = preg_replace('/\/\*.*\*\//', '', $string);
            
            $pass = $this->_blacklistCheck($string);

        }
        
        return $pass;
    }
    
    
    /**
     * @function _blacklistCheck()
     * @desc Check the use input against blacklisted values
     * @access protected
     * @param string $string
     * @return boolean
     */
    
    protected function _blacklistCheck($string)
    {
        $string = $this->decode(strtolower($string));
        
        // Regular Expressions to check against.
        $regex_array = array(
            '<body',
            '<embed',
            '<meta',
            '<iframe',
            '<script',
            '<style',
            '<xss',
            'src=[\\\'\"\`]?javascript\:',
            'href=[\\\'\"\`]?javascript\:',
            'src=[\\\'\"\`]?vbscript\:',
            'href=[\\\'\"\`]?vbscript\:',
            'rel=[\\\'\"\`]?stylesheet',
            '\\\[\\\'\"\`]?;.*',
            '.script.*alert',
            'expression\(.*\)',
            '[a-z]*\=[\'\"\`]?[a-z\-]*\:expression',
            'expression\(.*\)',
        );
        
        // Add any further blacklist checks. 
        // This one denies any XSS vulnerabilities in the above array.
        return ! preg_match('/' . implode('|', $regex_array) . '/', $string);

    }
    
    
    /**
     * @function decode()
     * @desc Continually urldecode() a string until it can't be decoded any further
     * @access public
     * @param string $string
     * @return string
     */
    
    public function decode($string)
    {
        while ($string !== urldecode($string)) {
           $string = urldecode($string);
        }
        
        // Replace leading 0s on HTML entities, for example &#000106; is a valid
        // alternative to &#106;, but any amount of leading 0s could also be valid.
        // We want to keep this consistant!
        $string = preg_replace('/\&\#0*([1-9][0-9]*)\;?/', "&#$1;", $string);
        
        // Super entityDecode() method should replace numbered HTML entities.
        while ($string !== $this->entityDecode($string)) {
            $string = $this->entityDecode($string);
        }
        
        return $string;
    }
    
    
    /**
     * @function fail()
     * @desc Handle a Blacklisted request parameter
     * @access public
     * @throws Plugin_Blacklist_Exception
     */
    
    public function fail()
    {
        // @todo: Log
        // Hook into a logging system to keep track of the blacklist violations
        // This could be done through the ErrorController.
        
        // Revoke privileges
        if (Zend_Auth::getInstance()->hasIdentity())
            Zend_Auth::getInstance()->clearIdentity();
        
        // regenerate session id
        Zend_Session::regenerateId();

        // alert user (optional, could also redirect somewhere)
        // This could be done through the ErrorController.
        throw new Plugin_Blacklist_Exception(Plugin_Blacklist_Exception::EXCEPTION_BLACKLISTED_VALUE);
    }
    
    
    /**
     * @function entityDecode()
     * @desc Flexible method for decoding an entity.
     * @access public
     * @param string $string
     * @param int $quote_style
     * @param string $charset
     * @return string
     */
    
    public function entityDecode($string, $quote_style = ENT_COMPAT, $charset = 'UTF-8')
    {
        // Don't want to use the default php html_entity_decode.
        // We have a more flexible method that we have control over.

        // Trying to use HEX HTML entities? Eff you, h4x0r!
        $string = preg_replace_callback(
            '/\&\#x([0-9A-Fa-f]{2})\;?/i',
            create_function(
                '$matches',
                'return "&#" . hexdec($matches[1]) . ";";')
            , $string);
        
        // Trying to use HEX characters? Double eff you, h4x0r!
        $string = preg_replace_callback('/(\\\\00[0-9A-Fa-f]{2})/', 
            create_function(
                '$matches',
                'return "&#".hexdec($matches[1]).";";'
            ), $string);
        
        $string = preg_replace('/&#([0-9]+);/e', '$this->chrUtf8("\\1")', $string);
        
        return $string; 
    }

    
    /**
     * @function chrUtf8()
     * @desc Return the UTF8 character of a numeric value
     * @access protected
     * @param int $num
     * @return string
     */
    
    protected function chrUtf8($num)
    {
        if ($num < 128)
            return chr($num);
        if ($num < 2048)
            return chr(($num >> 6) + 192) . chr(($num & 63) + 128);
        if ($num < 65536)
            return chr(($num >> 12) + 224) . chr((($num >> 6) & 63) + 128) . chr(($num & 63) + 128);
        if ($num < 2097152)
            return chr(($num >> 18) + 240) . chr((($num >> 12) & 63) + 128) . chr((($num >> 6) & 63) + 128) . chr(($num & 63) + 128);
        return '';
    }

}

// End of /application/plugins/Blacklist.php