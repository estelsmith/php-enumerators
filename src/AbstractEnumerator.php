<?php

namespace Cascade\Enumerator;

use Cascade\Enumerator\Exception\InvalidEnumeratorValueException;

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

    public static function getValues()
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

    public static function __callStatic($name, $arguments)
    {
        $values = static::getValues();

        if (array_key_exists($name, $values)) {
            return $values[$name];
        }

        throw new InvalidEnumeratorValueException(sprintf(
            'Enumerator value %s:%s does not exist',
            get_called_class(),
            $name
        ));
    }
}
