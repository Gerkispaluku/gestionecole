<?php

namespace PHPMaker2023\gestion_ECOLE;

/**
 * Advanced filter class
 */
class AdvancedFilter
{
    public $ID;
    public $Name;
    public $FunctionName;
    public $Enabled = true;

    public function __construct($filterid, $filtername, $filterfunc)
    {
        $this->ID = $filterid;
        $this->Name = $filtername;
        $this->FunctionName = $filterfunc;
    }
}
