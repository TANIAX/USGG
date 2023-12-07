<?php

namespace App\Interfaces;

/**
 * Interface for validator classes.
 * @author Guillaume Cornez
 */
interface IValidator
{
    /**
     * Validates the data provided througth preperties.
     */
    public function validate();
}