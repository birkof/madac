<?php

namespace AppBundle\Tests\Base;

class UnitTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Given the class instance, calls the method with the specified name and arguments
     *
     * @param $object - Object with the desired unaccessible method
     * @param $methodName - The name of the unaccessible method
     * @param $methodArgs - All required method parameters
     * @return mixed - The invoked method's return result.
     */
    protected function callMethod($object, $methodName, $methodArgs = array())
    {
        $class = new \ReflectionClass($object);
        $method = $class->getMethod($methodName);
        $method->setAccessible(true);
        return $method->invokeArgs($object, $methodArgs);
    }
}
