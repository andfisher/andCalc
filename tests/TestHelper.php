<?php

class TestHelper
{
	public static function spyOnProperty($property, $object)
	{
		$className = get_class($object);
		$reflectionClass = new ReflectionClass($className);

		$property = $reflectionClass->getProperty($property);
	    $property->setAccessible(true);
		return $property->getValue($object);
	}
	
	
	public static function getCleanMock($className, $test)
	{
		$class = new ReflectionClass($className);
		$methods = $class->getMethods();
		$stubMethods = array();
		foreach ($methods as $method) {
			if ($method->isPublic() || ($method->isProtected() && $method->isAbstract())) {
				$stubMethods[] = $method->getName();
			}
		}
		$mocked = $test->getMock($className, $stubMethods, array(), $className . '_EntryMapperTestMock_' . uniqid(), false);
		return $mocked;
	}
}