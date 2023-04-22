<?php

namespace PHPMaker2023\gestion_ECOLE;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Table class for eleve
 */
class Eleve extends DbTable
{
    protected $SqlFrom = "";
    protected $SqlSelect = null;
    protected $SqlSelectList = null;
    protected $SqlWhere = "";
    protected $SqlGroupBy = "";
    protected $SqlHaving = "";
    protected $SqlOrderBy = "";
    public $DbErrorMessage = "";
    public $UseSessionForListSql = true;

    // Column CSS classes
    public $LeftColumnClass = "col-sm-2 col-form-label ew-label";
    public $RightColumnClass = "col-sm-10";
    public $OffsetColumnClass = "col-sm-10 offset-sm-2";
    public $TableLeftColumnClass = "w-col-2";

    // Ajax / Modal
    public $UseAjaxActions = false;
    public $ModalSearch = true;
    public $ModalView = false;
    public $ModalAdd = false;
    public $ModalEdit = false;
    public $ModalUpdate = true;
    public $InlineDelete = false;
    public $ModalGridAdd = false;
    public $ModalGridEdit = false;
    public $ModalMultiEdit = false;

    // Fields
    public $IdEleve;
    public $MatricEleve;
    public $NomEleve;
    public $PostnomElve;
    public $PrenomEleve;
    public $SexeEleve;
    public $DatenaissanceEleve;
    public $LieunaissanceEleve;
    public $NomdupereElve;
    public $NomdelamereEleve;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $CurrentLanguage, $CurrentLocale;

        // Language object
        $Language = Container("language");
        $this->TableVar = "eleve";
        $this->TableName = 'eleve';
        $this->TableType = "TABLE";
        $this->ImportUseTransaction = $this->supportsTransaction() && Config("IMPORT_USE_TRANSACTION");
        $this->UseTransaction = $this->supportsTransaction() && Config("USE_TRANSACTION");

        // Update Table
        $this->UpdateTable = "eleve";
        $this->Dbid = 'DB';
        $this->ExportAll = true;
        $this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)

        // PDF
        $this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
        $this->ExportPageSize = "a4"; // Page size (PDF only)

        // PhpSpreadsheet
        $this->ExportExcelPageOrientation = null; // Page orientation (PhpSpreadsheet only)
        $this->ExportExcelPageSize = null; // Page size (PhpSpreadsheet only)

        // PHPWord
        $this->ExportWordPageOrientation = ""; // Page orientation (PHPWord only)
        $this->ExportWordPageSize = ""; // Page orientation (PHPWord only)
        $this->ExportWordColumnWidth = null; // Cell width (PHPWord only)
        $this->DetailAdd = true; // Allow detail add
        $this->DetailEdit = false; // Allow detail edit
        $this->DetailView = false; // Allow detail view
        $this->ShowMultipleDetails = false; // Show multiple details
        $this->GridAddRowCount = 5;
        $this->AllowAddDeleteRow = true; // Allow add/delete row
        $this->UseAjaxActions = $this->UseAjaxActions || Config("USE_AJAX_ACTIONS");
        $this->UserIDAllowSecurity = Config("DEFAULT_USER_ID_ALLOW_SECURITY"); // Default User ID allowed permissions
        $this->BasicSearch = new BasicSearch($this);

        // IdEleve
        $this->IdEleve = new DbField(
            $this, // Table
            'x_IdEleve', // Variable name
            'IdEleve', // Name
            '`IdEleve`', // Expression
            '`IdEleve`', // Basic search expression
            3, // Type
            11, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`IdEleve`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'SELECT' // Edit Tag
        );
        $this->IdEleve->InputTextType = "text";
        $this->IdEleve->IsAutoIncrement = true; // Autoincrement field
        $this->IdEleve->IsPrimaryKey = true; // Primary key field
        $this->IdEleve->setSelectMultiple(false); // Select one
        $this->IdEleve->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->IdEleve->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        switch ($CurrentLanguage) {
            case "en-US":
                $this->IdEleve->Lookup = new Lookup('IdEleve', 'eleve', false, 'IdEleve', ["IdEleve","","",""], '', '', [], [], [], [], [], [], '', '', "`IdEleve`");
                break;
            default:
                $this->IdEleve->Lookup = new Lookup('IdEleve', 'eleve', false, 'IdEleve', ["IdEleve","","",""], '', '', [], [], [], [], [], [], '', '', "`IdEleve`");
                break;
        }
        $this->IdEleve->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->IdEleve->SearchOperators = ["=", "<>", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['IdEleve'] = &$this->IdEleve;

        // MatricEleve
        $this->MatricEleve = new DbField(
            $this, // Table
            'x_MatricEleve', // Variable name
            'MatricEleve', // Name
            '`MatricEleve`', // Expression
            '`MatricEleve`', // Basic search expression
            3, // Type
            11, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`MatricEleve`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->MatricEleve->InputTextType = "text";
        $this->MatricEleve->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->MatricEleve->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['MatricEleve'] = &$this->MatricEleve;

        // NomEleve
        $this->NomEleve = new DbField(
            $this, // Table
            'x_NomEleve', // Variable name
            'NomEleve', // Name
            '`NomEleve`', // Expression
            '`NomEleve`', // Basic search expression
            200, // Type
            25, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`NomEleve`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->NomEleve->InputTextType = "text";
        $this->NomEleve->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['NomEleve'] = &$this->NomEleve;

        // PostnomElve
        $this->PostnomElve = new DbField(
            $this, // Table
            'x_PostnomElve', // Variable name
            'PostnomElve', // Name
            '`PostnomElve`', // Expression
            '`PostnomElve`', // Basic search expression
            200, // Type
            25, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`PostnomElve`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->PostnomElve->InputTextType = "text";
        $this->PostnomElve->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['PostnomElve'] = &$this->PostnomElve;

        // PrenomEleve
        $this->PrenomEleve = new DbField(
            $this, // Table
            'x_PrenomEleve', // Variable name
            'PrenomEleve', // Name
            '`PrenomEleve`', // Expression
            '`PrenomEleve`', // Basic search expression
            200, // Type
            25, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`PrenomEleve`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->PrenomEleve->InputTextType = "text";
        $this->PrenomEleve->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['PrenomEleve'] = &$this->PrenomEleve;

        // SexeEleve
        $this->SexeEleve = new DbField(
            $this, // Table
            'x_SexeEleve', // Variable name
            'SexeEleve', // Name
            '`SexeEleve`', // Expression
            '`SexeEleve`', // Basic search expression
            200, // Type
            1, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`SexeEleve`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'RADIO' // Edit Tag
        );
        $this->SexeEleve->InputTextType = "text";
        switch ($CurrentLanguage) {
            case "en-US":
                $this->SexeEleve->Lookup = new Lookup('SexeEleve', 'eleve', false, '', ["","","",""], '', '', [], [], [], [], [], [], '', '', "");
                break;
            default:
                $this->SexeEleve->Lookup = new Lookup('SexeEleve', 'eleve', false, '', ["","","",""], '', '', [], [], [], [], [], [], '', '', "");
                break;
        }
        $this->SexeEleve->OptionCount = 2;
        $this->SexeEleve->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['SexeEleve'] = &$this->SexeEleve;

        // DatenaissanceEleve
        $this->DatenaissanceEleve = new DbField(
            $this, // Table
            'x_DatenaissanceEleve', // Variable name
            'DatenaissanceEleve', // Name
            '`DatenaissanceEleve`', // Expression
            CastDateFieldForLike("`DatenaissanceEleve`", 0, "DB"), // Basic search expression
            133, // Type
            10, // Size
            0, // Date/Time format
            false, // Is upload field
            '`DatenaissanceEleve`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->DatenaissanceEleve->InputTextType = "text";
        $this->DatenaissanceEleve->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->DatenaissanceEleve->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['DatenaissanceEleve'] = &$this->DatenaissanceEleve;

        // LieunaissanceEleve
        $this->LieunaissanceEleve = new DbField(
            $this, // Table
            'x_LieunaissanceEleve', // Variable name
            'LieunaissanceEleve', // Name
            '`LieunaissanceEleve`', // Expression
            '`LieunaissanceEleve`', // Basic search expression
            200, // Type
            25, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`LieunaissanceEleve`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->LieunaissanceEleve->InputTextType = "text";
        $this->LieunaissanceEleve->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['LieunaissanceEleve'] = &$this->LieunaissanceEleve;

        // NomdupereElve
        $this->NomdupereElve = new DbField(
            $this, // Table
            'x_NomdupereElve', // Variable name
            'NomdupereElve', // Name
            '`NomdupereElve`', // Expression
            '`NomdupereElve`', // Basic search expression
            200, // Type
            25, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`NomdupereElve`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->NomdupereElve->InputTextType = "text";
        $this->NomdupereElve->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['NomdupereElve'] = &$this->NomdupereElve;

        // NomdelamereEleve
        $this->NomdelamereEleve = new DbField(
            $this, // Table
            'x_NomdelamereEleve', // Variable name
            'NomdelamereEleve', // Name
            '`NomdelamereEleve`', // Expression
            '`NomdelamereEleve`', // Basic search expression
            200, // Type
            25, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`NomdelamereEleve`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->NomdelamereEleve->InputTextType = "text";
        $this->NomdelamereEleve->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['NomdelamereEleve'] = &$this->NomdelamereEleve;

        // Add Doctrine Cache
        $this->Cache = new ArrayCache();
        $this->CacheProfile = new \Doctrine\DBAL\Cache\QueryCacheProfile(0, $this->TableVar);

        // Call Table Load event
        $this->tableLoad();
    }

    // Field Visibility
    public function getFieldVisibility($fldParm)
    {
        global $Security;
        return $this->$fldParm->Visible; // Returns original value
    }

    // Set left column class (must be predefined col-*-* classes of Bootstrap grid system)
    public function setLeftColumnClass($class)
    {
        if (preg_match('/^col\-(\w+)\-(\d+)$/', $class, $match)) {
            $this->LeftColumnClass = $class . " col-form-label ew-label";
            $this->RightColumnClass = "col-" . $match[1] . "-" . strval(12 - (int)$match[2]);
            $this->OffsetColumnClass = $this->RightColumnClass . " " . str_replace("col-", "offset-", $class);
            $this->TableLeftColumnClass = preg_replace('/^col-\w+-(\d+)$/', "w-col-$1", $class); // Change to w-col-*
        }
    }

    // Single column sort
    public function updateSort(&$fld)
    {
        if ($this->CurrentOrder == $fld->Name) {
            $sortField = $fld->Expression;
            $lastSort = $fld->getSort();
            if (in_array($this->CurrentOrderType, ["ASC", "DESC", "NO"])) {
                $curSort = $this->CurrentOrderType;
            } else {
                $curSort = $lastSort;
            }
            $orderBy = in_array($curSort, ["ASC", "DESC"]) ? $sortField . " " . $curSort : "";
            $this->setSessionOrderBy($orderBy); // Save to Session
        }
    }

    // Update field sort
    public function updateFieldSort()
    {
        $orderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
        $flds = GetSortFields($orderBy);
        foreach ($this->Fields as $field) {
            $fldSort = "";
            foreach ($flds as $fld) {
                if ($fld[0] == $field->Expression || $fld[0] == $field->VirtualExpression) {
                    $fldSort = $fld[1];
                }
            }
            $field->setSort($fldSort);
        }
    }

    // Render X Axis for chart
    public function renderChartXAxis($chartVar, $chartRow)
    {
        return $chartRow;
    }

    // Table level SQL
    public function getSqlFrom() // From
    {
        return ($this->SqlFrom != "") ? $this->SqlFrom : "eleve";
    }

    public function sqlFrom() // For backward compatibility
    {
        return $this->getSqlFrom();
    }

    public function setSqlFrom($v)
    {
        $this->SqlFrom = $v;
    }

    public function getSqlSelect() // Select
    {
        return $this->SqlSelect ?? $this->getQueryBuilder()->select("*");
    }

    public function sqlSelect() // For backward compatibility
    {
        return $this->getSqlSelect();
    }

    public function setSqlSelect($v)
    {
        $this->SqlSelect = $v;
    }

    public function getSqlWhere() // Where
    {
        $where = ($this->SqlWhere != "") ? $this->SqlWhere : "";
        $this->DefaultFilter = "";
        AddFilter($where, $this->DefaultFilter);
        return $where;
    }

    public function sqlWhere() // For backward compatibility
    {
        return $this->getSqlWhere();
    }

    public function setSqlWhere($v)
    {
        $this->SqlWhere = $v;
    }

    public function getSqlGroupBy() // Group By
    {
        return ($this->SqlGroupBy != "") ? $this->SqlGroupBy : "";
    }

    public function sqlGroupBy() // For backward compatibility
    {
        return $this->getSqlGroupBy();
    }

    public function setSqlGroupBy($v)
    {
        $this->SqlGroupBy = $v;
    }

    public function getSqlHaving() // Having
    {
        return ($this->SqlHaving != "") ? $this->SqlHaving : "";
    }

    public function sqlHaving() // For backward compatibility
    {
        return $this->getSqlHaving();
    }

    public function setSqlHaving($v)
    {
        $this->SqlHaving = $v;
    }

    public function getSqlOrderBy() // Order By
    {
        return ($this->SqlOrderBy != "") ? $this->SqlOrderBy : "";
    }

    public function sqlOrderBy() // For backward compatibility
    {
        return $this->getSqlOrderBy();
    }

    public function setSqlOrderBy($v)
    {
        $this->SqlOrderBy = $v;
    }

    // Apply User ID filters
    public function applyUserIDFilters($filter, $id = "")
    {
        return $filter;
    }

    // Check if User ID security allows view all
    public function userIDAllow($id = "")
    {
        $allow = $this->UserIDAllowSecurity;
        switch ($id) {
            case "add":
            case "copy":
            case "gridadd":
            case "register":
            case "addopt":
                return (($allow & 1) == 1);
            case "edit":
            case "gridedit":
            case "update":
            case "changepassword":
            case "resetpassword":
                return (($allow & 4) == 4);
            case "delete":
                return (($allow & 2) == 2);
            case "view":
                return (($allow & 32) == 32);
            case "search":
                return (($allow & 64) == 64);
            case "lookup":
                return (($allow & 256) == 256);
            default:
                return (($allow & 8) == 8);
        }
    }

    /**
     * Get record count
     *
     * @param string|QueryBuilder $sql SQL or QueryBuilder
     * @param mixed $c Connection
     * @return int
     */
    public function getRecordCount($sql, $c = null)
    {
        $cnt = -1;
        $rs = null;
        if ($sql instanceof QueryBuilder) { // Query builder
            $sqlwrk = clone $sql;
            $sqlwrk = $sqlwrk->resetQueryPart("orderBy")->getSQL();
        } else {
            $sqlwrk = $sql;
        }
        $pattern = '/^SELECT\s([\s\S]+)\sFROM\s/i';
        // Skip Custom View / SubQuery / SELECT DISTINCT / ORDER BY
        if (
            ($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') &&
            preg_match($pattern, $sqlwrk) && !preg_match('/\(\s*(SELECT[^)]+)\)/i', $sqlwrk) &&
            !preg_match('/^\s*select\s+distinct\s+/i', $sqlwrk) && !preg_match('/\s+order\s+by\s+/i', $sqlwrk)
        ) {
            $sqlwrk = "SELECT COUNT(*) FROM " . preg_replace($pattern, "", $sqlwrk);
        } else {
            $sqlwrk = "SELECT COUNT(*) FROM (" . $sqlwrk . ") COUNT_TABLE";
        }
        $conn = $c ?? $this->getConnection();
        $cnt = $conn->fetchOne($sqlwrk);
        if ($cnt !== false) {
            return (int)$cnt;
        }

        // Unable to get count by SELECT COUNT(*), execute the SQL to get record count directly
        return ExecuteRecordCount($sql, $conn);
    }

    // Get SQL
    public function getSql($where, $orderBy = "")
    {
        return $this->getSqlAsQueryBuilder($where, $orderBy)->getSQL();
    }

    // Get QueryBuilder
    public function getSqlAsQueryBuilder($where, $orderBy = "")
    {
        return $this->buildSelectSql(
            $this->getSqlSelect(),
            $this->getSqlFrom(),
            $this->getSqlWhere(),
            $this->getSqlGroupBy(),
            $this->getSqlHaving(),
            $this->getSqlOrderBy(),
            $where,
            $orderBy
        );
    }

    // Table SQL
    public function getCurrentSql()
    {
        $filter = $this->CurrentFilter;
        $filter = $this->applyUserIDFilters($filter);
        $sort = $this->getSessionOrderBy();
        return $this->getSql($filter, $sort);
    }

    /**
     * Table SQL with List page filter
     *
     * @return QueryBuilder
     */
    public function getListSql()
    {
        $filter = $this->UseSessionForListSql ? $this->getSessionWhere() : "";
        AddFilter($filter, $this->CurrentFilter);
        $filter = $this->applyUserIDFilters($filter);
        $this->recordsetSelecting($filter);
        $select = $this->getSqlSelect();
        $from = $this->getSqlFrom();
        $sort = $this->UseSessionForListSql ? $this->getSessionOrderBy() : "";
        $this->Sort = $sort;
        return $this->buildSelectSql(
            $select,
            $from,
            $this->getSqlWhere(),
            $this->getSqlGroupBy(),
            $this->getSqlHaving(),
            $this->getSqlOrderBy(),
            $filter,
            $sort
        );
    }

    // Get ORDER BY clause
    public function getOrderBy()
    {
        $orderBy = $this->getSqlOrderBy();
        $sort = $this->getSessionOrderBy();
        if ($orderBy != "" && $sort != "") {
            $orderBy .= ", " . $sort;
        } elseif ($sort != "") {
            $orderBy = $sort;
        }
        return $orderBy;
    }

    // Get record count based on filter (for detail record count in master table pages)
    public function loadRecordCount($filter)
    {
        $origFilter = $this->CurrentFilter;
        $this->CurrentFilter = $filter;
        $this->recordsetSelecting($this->CurrentFilter);
        $select = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlSelect() : $this->getQueryBuilder()->select("*");
        $groupBy = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlGroupBy() : "";
        $having = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlHaving() : "";
        $sql = $this->buildSelectSql($select, $this->getSqlFrom(), $this->getSqlWhere(), $groupBy, $having, "", $this->CurrentFilter, "");
        $cnt = $this->getRecordCount($sql);
        $this->CurrentFilter = $origFilter;
        return $cnt;
    }

    // Get record count (for current List page)
    public function listRecordCount()
    {
        $filter = $this->getSessionWhere();
        AddFilter($filter, $this->CurrentFilter);
        $filter = $this->applyUserIDFilters($filter);
        $this->recordsetSelecting($filter);
        $select = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlSelect() : $this->getQueryBuilder()->select("*");
        $groupBy = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlGroupBy() : "";
        $having = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlHaving() : "";
        $sql = $this->buildSelectSql($select, $this->getSqlFrom(), $this->getSqlWhere(), $groupBy, $having, "", $filter, "");
        $cnt = $this->getRecordCount($sql);
        return $cnt;
    }

    /**
     * INSERT statement
     *
     * @param mixed $rs
     * @return QueryBuilder
     */
    public function insertSql(&$rs)
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->insert($this->UpdateTable);
        foreach ($rs as $name => $value) {
            if (!isset($this->Fields[$name]) || $this->Fields[$name]->IsCustom) {
                continue;
            }
            $type = GetParameterType($this->Fields[$name], $value, $this->Dbid);
            $queryBuilder->setValue($this->Fields[$name]->Expression, $queryBuilder->createPositionalParameter($value, $type));
        }
        return $queryBuilder;
    }

    // Insert
    public function insert(&$rs)
    {
        $conn = $this->getConnection();
        try {
            $success = $this->insertSql($rs)->execute();
            $this->DbErrorMessage = "";
        } catch (\Exception $e) {
            $success = false;
            $this->DbErrorMessage = $e->getMessage();
        }
        if ($success) {
            // Get insert id if necessary
            $this->IdEleve->setDbValue($conn->lastInsertId());
            $rs['IdEleve'] = $this->IdEleve->DbValue;
        }
        return $success;
    }

    /**
     * UPDATE statement
     *
     * @param array $rs Data to be updated
     * @param string|array $where WHERE clause
     * @param string $curfilter Filter
     * @return QueryBuilder
     */
    public function updateSql(&$rs, $where = "", $curfilter = true)
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->update($this->UpdateTable);
        foreach ($rs as $name => $value) {
            if (!isset($this->Fields[$name]) || $this->Fields[$name]->IsCustom || $this->Fields[$name]->IsAutoIncrement) {
                continue;
            }
            $type = GetParameterType($this->Fields[$name], $value, $this->Dbid);
            $queryBuilder->set($this->Fields[$name]->Expression, $queryBuilder->createPositionalParameter($value, $type));
        }
        $filter = ($curfilter) ? $this->CurrentFilter : "";
        if (is_array($where)) {
            $where = $this->arrayToFilter($where);
        }
        AddFilter($filter, $where);
        if ($filter != "") {
            $queryBuilder->where($filter);
        }
        return $queryBuilder;
    }

    // Update
    public function update(&$rs, $where = "", $rsold = null, $curfilter = true)
    {
        // If no field is updated, execute may return 0. Treat as success
        try {
            $success = $this->updateSql($rs, $where, $curfilter)->execute();
            $success = ($success > 0) ? $success : true;
            $this->DbErrorMessage = "";
        } catch (\Exception $e) {
            $success = false;
            $this->DbErrorMessage = $e->getMessage();
        }

        // Return auto increment field
        if ($success) {
            if (!isset($rs['IdEleve']) && !EmptyValue($this->IdEleve->CurrentValue)) {
                $rs['IdEleve'] = $this->IdEleve->CurrentValue;
            }
        }
        return $success;
    }

    /**
     * DELETE statement
     *
     * @param array $rs Key values
     * @param string|array $where WHERE clause
     * @param string $curfilter Filter
     * @return QueryBuilder
     */
    public function deleteSql(&$rs, $where = "", $curfilter = true)
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->delete($this->UpdateTable);
        if (is_array($where)) {
            $where = $this->arrayToFilter($where);
        }
        if ($rs) {
            if (array_key_exists('IdEleve', $rs)) {
                AddFilter($where, QuotedName('IdEleve', $this->Dbid) . '=' . QuotedValue($rs['IdEleve'], $this->IdEleve->DataType, $this->Dbid));
            }
        }
        $filter = ($curfilter) ? $this->CurrentFilter : "";
        AddFilter($filter, $where);
        return $queryBuilder->where($filter != "" ? $filter : "0=1");
    }

    // Delete
    public function delete(&$rs, $where = "", $curfilter = false)
    {
        $success = true;
        if ($success) {
            try {
                $success = $this->deleteSql($rs, $where, $curfilter)->execute();
                $this->DbErrorMessage = "";
            } catch (\Exception $e) {
                $success = false;
                $this->DbErrorMessage = $e->getMessage();
            }
        }
        return $success;
    }

    // Load DbValue from recordset or array
    protected function loadDbValues($row)
    {
        if (!is_array($row)) {
            return;
        }
        $this->IdEleve->DbValue = $row['IdEleve'];
        $this->MatricEleve->DbValue = $row['MatricEleve'];
        $this->NomEleve->DbValue = $row['NomEleve'];
        $this->PostnomElve->DbValue = $row['PostnomElve'];
        $this->PrenomEleve->DbValue = $row['PrenomEleve'];
        $this->SexeEleve->DbValue = $row['SexeEleve'];
        $this->DatenaissanceEleve->DbValue = $row['DatenaissanceEleve'];
        $this->LieunaissanceEleve->DbValue = $row['LieunaissanceEleve'];
        $this->NomdupereElve->DbValue = $row['NomdupereElve'];
        $this->NomdelamereEleve->DbValue = $row['NomdelamereEleve'];
    }

    // Delete uploaded files
    public function deleteUploadedFiles($row)
    {
        $this->loadDbValues($row);
    }

    // Record filter WHERE clause
    protected function sqlKeyFilter()
    {
        return "`IdEleve` = @IdEleve@";
    }

    // Get Key
    public function getKey($current = false)
    {
        $keys = [];
        $val = $current ? $this->IdEleve->CurrentValue : $this->IdEleve->OldValue;
        if (EmptyValue($val)) {
            return "";
        } else {
            $keys[] = $val;
        }
        return implode(Config("COMPOSITE_KEY_SEPARATOR"), $keys);
    }

    // Set Key
    public function setKey($key, $current = false)
    {
        $this->OldKey = strval($key);
        $keys = explode(Config("COMPOSITE_KEY_SEPARATOR"), $this->OldKey);
        if (count($keys) == 1) {
            if ($current) {
                $this->IdEleve->CurrentValue = $keys[0];
            } else {
                $this->IdEleve->OldValue = $keys[0];
            }
        }
    }

    // Get record filter
    public function getRecordFilter($row = null, $current = false)
    {
        $keyFilter = $this->sqlKeyFilter();
        if (is_array($row)) {
            $val = array_key_exists('IdEleve', $row) ? $row['IdEleve'] : null;
        } else {
            $val = !EmptyValue($this->IdEleve->OldValue) && !$current ? $this->IdEleve->OldValue : $this->IdEleve->CurrentValue;
        }
        if (!is_numeric($val)) {
            return "0=1"; // Invalid key
        }
        if ($val === null) {
            return "0=1"; // Invalid key
        } else {
            $keyFilter = str_replace("@IdEleve@", AdjustSql($val, $this->Dbid), $keyFilter); // Replace key value
        }
        return $keyFilter;
    }

    // Return page URL
    public function getReturnUrl()
    {
        $referUrl = ReferUrl();
        $referPageName = ReferPageName();
        $name = PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_RETURN_URL");
        // Get referer URL automatically
        if ($referUrl != "" && $referPageName != CurrentPageName() && $referPageName != "login") { // Referer not same page or login page
            $_SESSION[$name] = $referUrl; // Save to Session
        }
        return $_SESSION[$name] ?? GetUrl("EleveList");
    }

    // Set return page URL
    public function setReturnUrl($v)
    {
        $_SESSION[PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_RETURN_URL")] = $v;
    }

    // Get modal caption
    public function getModalCaption($pageName)
    {
        global $Language;
        if ($pageName == "EleveView") {
            return $Language->phrase("View");
        } elseif ($pageName == "EleveEdit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "EleveAdd") {
            return $Language->phrase("Add");
        }
        return "";
    }

    // API page name
    public function getApiPageName($action)
    {
        switch (strtolower($action)) {
            case Config("API_VIEW_ACTION"):
                return "EleveView";
            case Config("API_ADD_ACTION"):
                return "EleveAdd";
            case Config("API_EDIT_ACTION"):
                return "EleveEdit";
            case Config("API_DELETE_ACTION"):
                return "EleveDelete";
            case Config("API_LIST_ACTION"):
                return "EleveList";
            default:
                return "";
        }
    }

    // Current URL
    public function getCurrentUrl($parm = "")
    {
        $url = CurrentPageUrl(false);
        if ($parm != "") {
            $url = $this->keyUrl($url, $parm);
        } else {
            $url = $this->keyUrl($url, Config("TABLE_SHOW_DETAIL") . "=");
        }
        return $this->addMasterUrl($url);
    }

    // List URL
    public function getListUrl()
    {
        return "EleveList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("EleveView", $parm);
        } else {
            $url = $this->keyUrl("EleveView", Config("TABLE_SHOW_DETAIL") . "=");
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "EleveAdd?" . $parm;
        } else {
            $url = "EleveAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("EleveEdit", $parm);
        return $this->addMasterUrl($url);
    }

    // Inline edit URL
    public function getInlineEditUrl()
    {
        $url = $this->keyUrl("EleveList", "action=edit");
        return $this->addMasterUrl($url);
    }

    // Copy URL
    public function getCopyUrl($parm = "")
    {
        $url = $this->keyUrl("EleveAdd", $parm);
        return $this->addMasterUrl($url);
    }

    // Inline copy URL
    public function getInlineCopyUrl()
    {
        $url = $this->keyUrl("EleveList", "action=copy");
        return $this->addMasterUrl($url);
    }

    // Delete URL
    public function getDeleteUrl()
    {
        if ($this->UseAjaxActions && ConvertToBool(Param("infinitescroll")) && CurrentPageID() == "list") {
            return $this->keyUrl(GetApiUrl(Config("API_DELETE_ACTION") . "/" . $this->TableVar));
        } else {
            return $this->keyUrl("EleveDelete");
        }
    }

    // Add master url
    public function addMasterUrl($url)
    {
        return $url;
    }

    public function keyToJson($htmlEncode = false)
    {
        $json = "";
        $json .= "\"IdEleve\":" . JsonEncode($this->IdEleve->CurrentValue, "number");
        $json = "{" . $json . "}";
        if ($htmlEncode) {
            $json = HtmlEncode($json);
        }
        return $json;
    }

    // Add key value to URL
    public function keyUrl($url, $parm = "")
    {
        if ($this->IdEleve->CurrentValue !== null) {
            $url .= "/" . $this->encodeKeyValue($this->IdEleve->CurrentValue);
        } else {
            return "javascript:ew.alert(ew.language.phrase('InvalidRecord'));";
        }
        if ($parm != "") {
            $url .= "?" . $parm;
        }
        return $url;
    }

    // Render sort
    public function renderFieldHeader($fld)
    {
        global $Security, $Language, $Page;
        $sortUrl = "";
        $attrs = "";
        if ($fld->Sortable) {
            $sortUrl = $this->sortUrl($fld);
            $attrs = ' role="button" data-ew-action="sort" data-ajax="' . ($this->UseAjaxActions ? "true" : "false") . '" data-sort-url="' . $sortUrl . '" data-sort-type="1"';
            if ($this->ContextClass) { // Add context
                $attrs .= ' data-context="' . HtmlEncode($this->ContextClass) . '"';
            }
        }
        $html = '<div class="ew-table-header-caption"' . $attrs . '>' . $fld->caption() . '</div>';
        if ($sortUrl) {
            $html .= '<div class="ew-table-header-sort">' . $fld->getSortIcon() . '</div>';
        }
        if ($fld->UseFilter && $Security->canSearch()) {
            $html .= '<div class="ew-filter-dropdown-btn" data-ew-action="filter" data-table="' . $fld->TableVar . '" data-field="' . $fld->FieldVar .
                '"><div class="ew-table-header-filter" role="button" aria-haspopup="true">' . $Language->phrase("Filter") . '</div></div>';
        }
        $html = '<div class="ew-table-header-btn">' . $html . '</div>';
        if ($this->UseCustomTemplate) {
            $scriptId = str_replace("{id}", $fld->TableVar . "_" . $fld->Param, "tpc_{id}");
            $html = '<template id="' . $scriptId . '">' . $html . '</template>';
        }
        return $html;
    }

    // Sort URL
    public function sortUrl($fld)
    {
        global $DashboardReport;
        if (
            $this->CurrentAction || $this->isExport() ||
            in_array($fld->Type, [128, 204, 205])
        ) { // Unsortable data type
                return "";
        } elseif ($fld->Sortable) {
            $urlParm = "order=" . urlencode($fld->Name) . "&amp;ordertype=" . $fld->getNextSort();
            if ($DashboardReport) {
                $urlParm .= "&amp;dashboard=true";
            }
            return $this->addMasterUrl($this->CurrentPageName . "?" . $urlParm);
        } else {
            return "";
        }
    }

    // Get record keys from Post/Get/Session
    public function getRecordKeys()
    {
        $arKeys = [];
        $arKey = [];
        if (Param("key_m") !== null) {
            $arKeys = Param("key_m");
            $cnt = count($arKeys);
        } else {
            if (($keyValue = Param("IdEleve") ?? Route("IdEleve")) !== null) {
                $arKeys[] = $keyValue;
            } elseif (IsApi() && (($keyValue = Key(0) ?? Route(2)) !== null)) {
                $arKeys[] = $keyValue;
            } else {
                $arKeys = null; // Do not setup
            }

            //return $arKeys; // Do not return yet, so the values will also be checked by the following code
        }
        // Check keys
        $ar = [];
        if (is_array($arKeys)) {
            foreach ($arKeys as $key) {
                if (!is_numeric($key)) {
                    continue;
                }
                $ar[] = $key;
            }
        }
        return $ar;
    }

    // Get filter from records
    public function getFilterFromRecords($rows)
    {
        $keyFilter = "";
        foreach ($rows as $row) {
            if ($keyFilter != "") {
                $keyFilter .= " OR ";
            }
            $keyFilter .= "(" . $this->getRecordFilter($row) . ")";
        }
        return $keyFilter;
    }

    // Get filter from record keys
    public function getFilterFromRecordKeys($setCurrent = true)
    {
        $arKeys = $this->getRecordKeys();
        $keyFilter = "";
        foreach ($arKeys as $key) {
            if ($keyFilter != "") {
                $keyFilter .= " OR ";
            }
            if ($setCurrent) {
                $this->IdEleve->CurrentValue = $key;
            } else {
                $this->IdEleve->OldValue = $key;
            }
            $keyFilter .= "(" . $this->getRecordFilter() . ")";
        }
        return $keyFilter;
    }

    // Load recordset based on filter / sort
    public function loadRs($filter, $sort = "")
    {
        $sql = $this->getSql($filter, $sort); // Set up filter (WHERE Clause) / sort (ORDER BY Clause)
        $conn = $this->getConnection();
        return $conn->executeQuery($sql);
    }

    // Load row values from record
    public function loadListRowValues(&$rs)
    {
        if (is_array($rs)) {
            $row = $rs;
        } elseif ($rs && property_exists($rs, "fields")) { // Recordset
            $row = $rs->fields;
        } else {
            return;
        }
        $this->IdEleve->setDbValue($row['IdEleve']);
        $this->MatricEleve->setDbValue($row['MatricEleve']);
        $this->NomEleve->setDbValue($row['NomEleve']);
        $this->PostnomElve->setDbValue($row['PostnomElve']);
        $this->PrenomEleve->setDbValue($row['PrenomEleve']);
        $this->SexeEleve->setDbValue($row['SexeEleve']);
        $this->DatenaissanceEleve->setDbValue($row['DatenaissanceEleve']);
        $this->LieunaissanceEleve->setDbValue($row['LieunaissanceEleve']);
        $this->NomdupereElve->setDbValue($row['NomdupereElve']);
        $this->NomdelamereEleve->setDbValue($row['NomdelamereEleve']);
    }

    // Render list content
    public function renderListContent($filter)
    {
        global $Response;
        $listPage = "EleveList";
        $listClass = PROJECT_NAMESPACE . $listPage;
        $page = new $listClass();
        $page->loadRecordsetFromFilter($filter);
        $view = Container("view");
        $template = $listPage . ".php"; // View
        $GLOBALS["Title"] ??= $page->Title; // Title
        try {
            $Response = $view->render($Response, $template, $GLOBALS);
        } finally {
            $page->terminate(); // Terminate page and clean up
        }
    }

    // Render list row values
    public function renderListRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // Common render codes

        // IdEleve

        // MatricEleve

        // NomEleve

        // PostnomElve

        // PrenomEleve

        // SexeEleve

        // DatenaissanceEleve

        // LieunaissanceEleve

        // NomdupereElve

        // NomdelamereEleve

        // IdEleve
        $this->IdEleve->ViewValue = $this->IdEleve->CurrentValue;

        // MatricEleve
        $this->MatricEleve->ViewValue = $this->MatricEleve->CurrentValue;

        // NomEleve
        $this->NomEleve->ViewValue = $this->NomEleve->CurrentValue;

        // PostnomElve
        $this->PostnomElve->ViewValue = $this->PostnomElve->CurrentValue;

        // PrenomEleve
        $this->PrenomEleve->ViewValue = $this->PrenomEleve->CurrentValue;

        // SexeEleve
        if (strval($this->SexeEleve->CurrentValue) != "") {
            $this->SexeEleve->ViewValue = $this->SexeEleve->optionCaption($this->SexeEleve->CurrentValue);
        } else {
            $this->SexeEleve->ViewValue = null;
        }

        // DatenaissanceEleve
        $this->DatenaissanceEleve->ViewValue = $this->DatenaissanceEleve->CurrentValue;
        $this->DatenaissanceEleve->ViewValue = FormatDateTime($this->DatenaissanceEleve->ViewValue, $this->DatenaissanceEleve->formatPattern());

        // LieunaissanceEleve
        $this->LieunaissanceEleve->ViewValue = $this->LieunaissanceEleve->CurrentValue;

        // NomdupereElve
        $this->NomdupereElve->ViewValue = $this->NomdupereElve->CurrentValue;

        // NomdelamereEleve
        $this->NomdelamereEleve->ViewValue = $this->NomdelamereEleve->CurrentValue;

        // IdEleve
        $this->IdEleve->HrefValue = "";
        $this->IdEleve->TooltipValue = "";

        // MatricEleve
        $this->MatricEleve->HrefValue = "";
        $this->MatricEleve->TooltipValue = "";

        // NomEleve
        $this->NomEleve->HrefValue = "";
        $this->NomEleve->TooltipValue = "";

        // PostnomElve
        $this->PostnomElve->HrefValue = "";
        $this->PostnomElve->TooltipValue = "";

        // PrenomEleve
        $this->PrenomEleve->HrefValue = "";
        $this->PrenomEleve->TooltipValue = "";

        // SexeEleve
        $this->SexeEleve->HrefValue = "";
        $this->SexeEleve->TooltipValue = "";

        // DatenaissanceEleve
        $this->DatenaissanceEleve->HrefValue = "";
        $this->DatenaissanceEleve->TooltipValue = "";

        // LieunaissanceEleve
        $this->LieunaissanceEleve->HrefValue = "";
        $this->LieunaissanceEleve->TooltipValue = "";

        // NomdupereElve
        $this->NomdupereElve->HrefValue = "";
        $this->NomdupereElve->TooltipValue = "";

        // NomdelamereEleve
        $this->NomdelamereEleve->HrefValue = "";
        $this->NomdelamereEleve->TooltipValue = "";

        // Call Row Rendered event
        $this->rowRendered();

        // Save data for Custom Template
        $this->Rows[] = $this->customTemplateFieldValues();
    }

    // Render edit row values
    public function renderEditRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // IdEleve
        $this->IdEleve->setupEditAttributes();
        $this->IdEleve->EditValue = $this->IdEleve->CurrentValue;

        // MatricEleve
        $this->MatricEleve->setupEditAttributes();
        $this->MatricEleve->EditValue = $this->MatricEleve->CurrentValue;
        $this->MatricEleve->PlaceHolder = RemoveHtml($this->MatricEleve->caption());
        if (strval($this->MatricEleve->EditValue) != "" && is_numeric($this->MatricEleve->EditValue)) {
            $this->MatricEleve->EditValue = $this->MatricEleve->EditValue;
        }

        // NomEleve
        $this->NomEleve->setupEditAttributes();
        if (!$this->NomEleve->Raw) {
            $this->NomEleve->CurrentValue = HtmlDecode($this->NomEleve->CurrentValue);
        }
        $this->NomEleve->EditValue = $this->NomEleve->CurrentValue;
        $this->NomEleve->PlaceHolder = RemoveHtml($this->NomEleve->caption());

        // PostnomElve
        $this->PostnomElve->setupEditAttributes();
        if (!$this->PostnomElve->Raw) {
            $this->PostnomElve->CurrentValue = HtmlDecode($this->PostnomElve->CurrentValue);
        }
        $this->PostnomElve->EditValue = $this->PostnomElve->CurrentValue;
        $this->PostnomElve->PlaceHolder = RemoveHtml($this->PostnomElve->caption());

        // PrenomEleve
        $this->PrenomEleve->setupEditAttributes();
        if (!$this->PrenomEleve->Raw) {
            $this->PrenomEleve->CurrentValue = HtmlDecode($this->PrenomEleve->CurrentValue);
        }
        $this->PrenomEleve->EditValue = $this->PrenomEleve->CurrentValue;
        $this->PrenomEleve->PlaceHolder = RemoveHtml($this->PrenomEleve->caption());

        // SexeEleve
        $this->SexeEleve->EditValue = $this->SexeEleve->options(false);
        $this->SexeEleve->PlaceHolder = RemoveHtml($this->SexeEleve->caption());

        // DatenaissanceEleve
        $this->DatenaissanceEleve->setupEditAttributes();
        $this->DatenaissanceEleve->EditValue = FormatDateTime($this->DatenaissanceEleve->CurrentValue, $this->DatenaissanceEleve->formatPattern());
        $this->DatenaissanceEleve->PlaceHolder = RemoveHtml($this->DatenaissanceEleve->caption());

        // LieunaissanceEleve
        $this->LieunaissanceEleve->setupEditAttributes();
        if (!$this->LieunaissanceEleve->Raw) {
            $this->LieunaissanceEleve->CurrentValue = HtmlDecode($this->LieunaissanceEleve->CurrentValue);
        }
        $this->LieunaissanceEleve->EditValue = $this->LieunaissanceEleve->CurrentValue;
        $this->LieunaissanceEleve->PlaceHolder = RemoveHtml($this->LieunaissanceEleve->caption());

        // NomdupereElve
        $this->NomdupereElve->setupEditAttributes();
        if (!$this->NomdupereElve->Raw) {
            $this->NomdupereElve->CurrentValue = HtmlDecode($this->NomdupereElve->CurrentValue);
        }
        $this->NomdupereElve->EditValue = $this->NomdupereElve->CurrentValue;
        $this->NomdupereElve->PlaceHolder = RemoveHtml($this->NomdupereElve->caption());

        // NomdelamereEleve
        $this->NomdelamereEleve->setupEditAttributes();
        if (!$this->NomdelamereEleve->Raw) {
            $this->NomdelamereEleve->CurrentValue = HtmlDecode($this->NomdelamereEleve->CurrentValue);
        }
        $this->NomdelamereEleve->EditValue = $this->NomdelamereEleve->CurrentValue;
        $this->NomdelamereEleve->PlaceHolder = RemoveHtml($this->NomdelamereEleve->caption());

        // Call Row Rendered event
        $this->rowRendered();
    }

    // Aggregate list row values
    public function aggregateListRowValues()
    {
    }

    // Aggregate list row (for rendering)
    public function aggregateListRow()
    {
        // Call Row Rendered event
        $this->rowRendered();
    }

    // Export data in HTML/CSV/Word/Excel/Email/PDF format
    public function exportDocument($doc, $recordset, $startRec = 1, $stopRec = 1, $exportPageType = "")
    {
        if (!$recordset || !$doc) {
            return;
        }
        if (!$doc->ExportCustom) {
            // Write header
            $doc->exportTableHeader();
            if ($doc->Horizontal) { // Horizontal format, write header
                $doc->beginExportRow();
                if ($exportPageType == "view") {
                    $doc->exportCaption($this->IdEleve);
                    $doc->exportCaption($this->MatricEleve);
                    $doc->exportCaption($this->NomEleve);
                    $doc->exportCaption($this->PostnomElve);
                    $doc->exportCaption($this->PrenomEleve);
                    $doc->exportCaption($this->SexeEleve);
                    $doc->exportCaption($this->DatenaissanceEleve);
                    $doc->exportCaption($this->LieunaissanceEleve);
                    $doc->exportCaption($this->NomdupereElve);
                    $doc->exportCaption($this->NomdelamereEleve);
                } else {
                    $doc->exportCaption($this->IdEleve);
                    $doc->exportCaption($this->MatricEleve);
                    $doc->exportCaption($this->NomEleve);
                    $doc->exportCaption($this->PostnomElve);
                    $doc->exportCaption($this->PrenomEleve);
                    $doc->exportCaption($this->SexeEleve);
                    $doc->exportCaption($this->DatenaissanceEleve);
                    $doc->exportCaption($this->LieunaissanceEleve);
                    $doc->exportCaption($this->NomdupereElve);
                    $doc->exportCaption($this->NomdelamereEleve);
                }
                $doc->endExportRow();
            }
        }

        // Move to first record
        $recCnt = $startRec - 1;
        $stopRec = ($stopRec > 0) ? $stopRec : PHP_INT_MAX;
        while (!$recordset->EOF && $recCnt < $stopRec) {
            $row = $recordset->fields;
            $recCnt++;
            if ($recCnt >= $startRec) {
                $rowCnt = $recCnt - $startRec + 1;

                // Page break
                if ($this->ExportPageBreakCount > 0) {
                    if ($rowCnt > 1 && ($rowCnt - 1) % $this->ExportPageBreakCount == 0) {
                        $doc->exportPageBreak();
                    }
                }
                $this->loadListRowValues($row);

                // Render row
                $this->RowType = ROWTYPE_VIEW; // Render view
                $this->resetAttributes();
                $this->renderListRow();
                if (!$doc->ExportCustom) {
                    $doc->beginExportRow($rowCnt); // Allow CSS styles if enabled
                    if ($exportPageType == "view") {
                        $doc->exportField($this->IdEleve);
                        $doc->exportField($this->MatricEleve);
                        $doc->exportField($this->NomEleve);
                        $doc->exportField($this->PostnomElve);
                        $doc->exportField($this->PrenomEleve);
                        $doc->exportField($this->SexeEleve);
                        $doc->exportField($this->DatenaissanceEleve);
                        $doc->exportField($this->LieunaissanceEleve);
                        $doc->exportField($this->NomdupereElve);
                        $doc->exportField($this->NomdelamereEleve);
                    } else {
                        $doc->exportField($this->IdEleve);
                        $doc->exportField($this->MatricEleve);
                        $doc->exportField($this->NomEleve);
                        $doc->exportField($this->PostnomElve);
                        $doc->exportField($this->PrenomEleve);
                        $doc->exportField($this->SexeEleve);
                        $doc->exportField($this->DatenaissanceEleve);
                        $doc->exportField($this->LieunaissanceEleve);
                        $doc->exportField($this->NomdupereElve);
                        $doc->exportField($this->NomdelamereEleve);
                    }
                    $doc->endExportRow($rowCnt);
                }
            }

            // Call Row Export server event
            if ($doc->ExportCustom) {
                $this->rowExport($doc, $row);
            }
            $recordset->moveNext();
        }
        if (!$doc->ExportCustom) {
            $doc->exportTableFooter();
        }
    }

    // Get file data
    public function getFileData($fldparm, $key, $resize, $width = 0, $height = 0, $plugins = [])
    {
        global $DownloadFileName;

        // No binary fields
        return false;
    }

    // Table level events

    // Table Load event
    public function tableLoad()
    {
        // Enter your code here
    }

    // Recordset Selecting event
    public function recordsetSelecting(&$filter)
    {
        // Enter your code here
    }

    // Recordset Selected event
    public function recordsetSelected(&$rs)
    {
        //Log("Recordset Selected");
    }

    // Recordset Search Validated event
    public function recordsetSearchValidated()
    {
        // Example:
        //$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value
    }

    // Recordset Searching event
    public function recordsetSearching(&$filter)
    {
        // Enter your code here
    }

    // Row_Selecting event
    public function rowSelecting(&$filter)
    {
        // Enter your code here
    }

    // Row Selected event
    public function rowSelected(&$rs)
    {
        //Log("Row Selected");
    }

    // Row Inserting event
    public function rowInserting($rsold, &$rsnew)
    {
        // Enter your code here
        // To cancel, set return value to false
        return true;
    }

    // Row Inserted event
    public function rowInserted($rsold, &$rsnew)
    {
        //Log("Row Inserted");
    }

    // Row Updating event
    public function rowUpdating($rsold, &$rsnew)
    {
        // Enter your code here
        // To cancel, set return value to false
        return true;
    }

    // Row Updated event
    public function rowUpdated($rsold, &$rsnew)
    {
        //Log("Row Updated");
    }

    // Row Update Conflict event
    public function rowUpdateConflict($rsold, &$rsnew)
    {
        // Enter your code here
        // To ignore conflict, set return value to false
        return true;
    }

    // Grid Inserting event
    public function gridInserting()
    {
        // Enter your code here
        // To reject grid insert, set return value to false
        return true;
    }

    // Grid Inserted event
    public function gridInserted($rsnew)
    {
        //Log("Grid Inserted");
    }

    // Grid Updating event
    public function gridUpdating($rsold)
    {
        // Enter your code here
        // To reject grid update, set return value to false
        return true;
    }

    // Grid Updated event
    public function gridUpdated($rsold, $rsnew)
    {
        //Log("Grid Updated");
    }

    // Row Deleting event
    public function rowDeleting(&$rs)
    {
        // Enter your code here
        // To cancel, set return value to False
        return true;
    }

    // Row Deleted event
    public function rowDeleted(&$rs)
    {
        //Log("Row Deleted");
    }

    // Email Sending event
    public function emailSending($email, &$args)
    {
        //var_dump($email, $args); exit();
        return true;
    }

    // Lookup Selecting event
    public function lookupSelecting($fld, &$filter)
    {
        //var_dump($fld->Name, $fld->Lookup, $filter); // Uncomment to view the filter
        // Enter your code here
    }

    // Row Rendering event
    public function rowRendering()
    {
        // Enter your code here
    }

    // Row Rendered event
    public function rowRendered()
    {
        // To view properties of field class, use:
        //var_dump($this-><FieldName>);
    }

    // User ID Filtering event
    public function userIdFiltering(&$filter)
    {
        // Enter your code here
    }
}
