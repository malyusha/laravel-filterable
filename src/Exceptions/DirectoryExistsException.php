<?php


namespace Malyusha\Filterable\Exceptions;


class DirectoryExistsException extends Exception
{
    protected $directory;

    public function __construct($directory, $code = 0, \Exception $previous = null)
    {
        $this->directory = $directory;

        parent::__construct('Directory ' . $this->getDirectory() . ' already exists.', $code, $previous);
    }

    public function getDirectory()
    {
        return $this->directory;
    }
}