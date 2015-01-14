<?php

namespace Cascade\Enumerator;

interface EnumeratorValue
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getValue();
}
