# andCalc
Zend Framework MVC Calculator

Supports the following mathematical operators:
Addition (+)
Subtraction (-)
Division (-)
Multiplication (x)

========================================================

This Calculator uses the Zend Framework which you can download here:
https://packages.zendframework.com/releases/ZendFramework-1.12.20/ZendFramework-1.12.20.zip

Extract the zip's library/Zend directory into the matching library/Zend directory.

========================================================

As standard with most Zend apps, your apache setup will need the rewrite module enabled and your vhost will need the following declarations:



	RewriteEngine On
	RewriteCond %{REQUEST_FILENAME} !-f
	RewriteCond %{REQUEST_FILENAME} !-d
	RewriteRule ^.*$ /index.php [L]
	
========================================================

To run the calculator, run the root (/) or alternatively the MVC implied /calculator route.