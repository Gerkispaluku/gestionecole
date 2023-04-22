<?php

namespace PHPMaker2023\gestion_ECOLE;

use Pinq\ITraversable, Pinq\Traversable;

/**
 * Report field class
 */
class ReportField extends DbField
{
    public $SumValue; // Sum
    public $AvgValue; // Average
    public $MinValue; // Minimum
    public $MaxValue; // Maximum
    public $CntValue; // Count
    public $SumViewValue; // Sum
    public $AvgViewValue; // Average
    public $MinViewValue; // Minimum
    public $MaxViewValue; // Maximum
    public $CntViewValue; // Count
    public $DrillDownTable = ""; // Drill down table name
    public $DrillDownUrl = ""; // Drill down URL
    public $CurrentFilter = ""; // Current filter in use
    public $GroupingFieldId = 0; // Grouping field id
    public $ShowGroupHeaderAsRow = false; // Show grouping level as row
    public $ShowCompactSummaryFooter = true; // Show compact summary footer
    public $GroupByType; // Group By Type
    public $GroupInterval; // Group Interval
    public $GroupSql; // Group SQL
    public $GroupValue; // Group Value
    public $GroupViewValue; // Group View Value
    public $DateFilter; // Date Filter ("year"|"quarter"|"month"|"day"|"")
    public $Delimiter = ""; // Field delimiter (e.g. comma) for delimiter separated value
    public $DistinctValues = [];
    public $Records = [];
    public $LevelBreak = false;

    // Database value (override PHPMaker)
    public function setDbValue($v)
    {
        if ($this->Type == 131 || $this->Type == 139) { // Convert adNumeric/adVarNumeric field
            $v = floatval($v);
        }
        parent::setDbValue($v); // Call parent method
    }

    // Group value
    public function groupValue()
    {
        return $this->GroupValue;
    }

    // Set group value
    public function setGroupValue($v)
    {
        $this->setDbValue($v);
        $this->GroupValue = $this->DbValue;
    }

    // Get select options HTML (override)
    public function selectOptionListHtml($name = "", $multiple = null)
    {
        $html = parent::selectOptionListHtml($name, $multiple);
        return str_replace(">" . Config("INIT_VALUE") . "</option>", "></option>", $html); // Do not show the INIT_VALUE as value
    }

    // Get distinct values
    public function getDistinctValues($records, $sort = "ASC")
    {
        $name = $this->getGroupName();
        if (SameText($sort, "DESC")) {
            $this->DistinctValues = Traversable::from($records)
                ->select(fn($record) => $record[$name])
                ->orderByDescending(fn($name) => $name)
                ->unique()
                ->asArray();
        } else {
            $this->DistinctValues = Traversable::from($records)
                ->select(fn($record) => $record[$name])
                ->orderByAscending(fn($name) => $name)
                ->unique()
                ->asArray();
        }
    }

    // Get distinct records
    public function getDistinctRecords($records, $val)
    {
        $name = $this->getGroupName();
        $this->Records = Traversable::from($records)
            ->where(fn($record) => $record[$name] == $val)
            ->asArray();
    }

    // Get Sum
    public function getSum($records)
    {
        $name = $this->getGroupName();
        $sum = 0;
        if (count($records) > 0) {
            $sum = Traversable::from($records)->sum(fn($record) => $record[$name]);
        }
        $this->SumValue = $sum;
    }

    // Get Avg
    public function getAvg($records)
    {
        $name = $this->getGroupName();
        $avg = 0;
        if (count($records) > 0) {
            $avg = Traversable::from($records)->average(fn($record) => $record[$name]);
        }
        $this->AvgValue = $avg;
    }

    // Get Min
    public function getMin($records)
    {
        $name = $this->getGroupName();
        $min = null;
        if (count($records) > 0) {
            $min = Traversable::from($records)->minimum(fn($record) => $record[$name]);
        }
        $this->MinValue = $min;
    }

    // Get Max
    public function getMax($records)
    {
        $name = $this->getGroupName();
        $max = null;
        if (count($records) > 0) {
            $notNull = Traversable::from($records)->where(fn($record) => !is_null($record[$name]));
            if (!$notNull->isEmpty()) {
                $max = $notNull->maximum(fn($record) => $record[$name]);
            }
        }
        $this->MaxValue = $max;
    }

    // Get Count
    public function getCnt($records)
    {
        $name = $this->getGroupName();
        $cnt = 0;
        if (count($records) > 0) {
            $cnt = Traversable::from($records)->where(fn($record) => $record[$name])->count();
        }
        $this->CntValue = $cnt;
        $this->Count = $cnt;
    }

    // Get group name
    public function getGroupName()
    {
        return $this->GroupSql != "" ? "EW_GROUP_VALUE_" . $this->GroupingFieldId : $this->Name;
    }

    /**
     * Format advanced filters
     *
     * @param array $ar
     */
    public function formatAdvancedFilters($ar)
    {
        if (is_array($ar) && is_array($this->AdvancedFilters)) {
            foreach ($ar as &$arwrk) {
                $lf = $arwrk["lf"] ?? "";
                $df = $arwrk["df"] ?? "";
                if (StartsString("@@", $lf) && SameString($lf, $df)) {
                    $key = substr($lf, 2);
                    if (array_key_exists($key, $this->AdvancedFilters)) {
                        $arwrk["df"] = $this->AdvancedFilters[$key]->Name;
                    }
                }
            }
        }
        return $ar;
    }
}
