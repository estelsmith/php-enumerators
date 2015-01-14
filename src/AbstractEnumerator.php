<?php

namespace Cascade\Enumerator;

abstract class AbstractEnumerator implements Enumerator, EnumeratorValue
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $value;

    /**
     * @param string $name
     * @param string $value
     */
    public function __construct($name, $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getValues()
    {
        static $values = [];

        if ($values) {
            return $values;
        }

        $enumeratorClass = new \ReflectionClass(get_called_class());
        $constants = $enumeratorClass->getConstants();

        if ($constants) {
            foreach ($constants as $constantName => $constantValue) {
                $values[$constantName] = $enumeratorClass->newInstance($constantName, $constantValue);
            }
        }

        return $values;
    }

    public static function has(EnumeratorValue $value)
    {
        return array_key_exists($value->getName(), static::getValues());
    }
}
