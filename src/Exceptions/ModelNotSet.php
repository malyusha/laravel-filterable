<?php

namespace Malyusha\Filterable\Exceptions;

class ModelNotSet extends Exception
{
    public function __construct(string $filter, int $code = 0, \Throwable $previous = null)
    {
        parent::__construct("Model property not set in {$filter} filter", $code, $previous);
    }
}