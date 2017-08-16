<?php


namespace Malyusha\Filterable\Exceptions;


class DirectoryCreateException extends Exception
{
    protected $directory;

    public function __construct($directory, $code = 0, \Exception $previous = null)
    {
        $this->directory = $directory;

        parent::__construct('Failed to crate directory: ' . $this->getDirectory(), $code, $previous);
    }

    public function getDirectory()
    {
        return $this->directory;
    }
}