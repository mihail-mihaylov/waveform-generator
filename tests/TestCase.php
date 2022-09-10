<?php

namespace Tests;

use ReflectionClass;
use ReflectionMethod;

class TestCase extends \PHPUnit\Framework\TestCase
{
    public function getPrivateMethod(string $className, string $methodName): ReflectionMethod
    {
        $reflector = new ReflectionClass($className);
        $method = $reflector->getMethod($methodName);
        $method->setAccessible(true);

        return $method;
    }

    public function invokePrivateMethod(string $methodName, $object, array $args = []): mixed
    {
        $className = get_class($object);
        $method = $this->getPrivateMethod($className, $methodName);

        if (!empty($args)) {
            return $method->invokeArgs($object, $args);
        }

        return $method->invoke($object);
    }
}
