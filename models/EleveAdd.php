<?php

namespace PHPMaker2023\gestion_ECOLE;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class EleveAdd extends Eleve
{
    use MessagesTrait;

    // Page ID
    public $PageID = "add";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "EleveAdd";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $CurrentPageName = "EleveAdd";

    // Page headings
    public $Heading = "";
    public $Subheading = "";
    public $PageHeader;
    public $PageFooter;

    // Page layout
    public $UseLayout = true;

    // Page terminated
    private $terminated = false;

    // Page heading
    public function pageHeading()
    {
        global $Language;
        if ($this->Heading != "") {
            return $this->Heading;
        }
        if (method_exists($this, "tableCaption")) {
            return $this->tableCaption();
        }
        return "";
    }

    // Page subheading
    public function pageSubheading()
    {
        global $Language;
        if ($this->Subheading != "") {
            return $this->Subheading;
        }
        if ($this->TableName) {
            return $Language->phrase($this->PageID);
        }
        return "";
    }

    // Page name
    public function pageName()
    {
        return CurrentPageName();
    }

    // Page URL
    public function pageUrl($withArgs = true)
    {
        $route = GetRoute();
        $args = RemoveXss($route->getArguments());
        if (!$withArgs) {
            foreach ($args as $key => &$val) {
                $val = "";
            }
            unset($val);
        }
        return rtrim(UrlFor($route->getName(), $args), "/") . "?";
    }

    // Show Page Header
    public function showPageHeader()
    {
        $header = $this->PageHeader;
        $this->pageDataRendering($header);
        if ($header != "") { // Header exists, display
            echo '<p id="ew-page-header">' . $header . '</p>';
        }
    }

    // Show Page Footer
    public function showPageFooter()
    {
        $footer = $this->PageFooter;
        $this->pageDataRendered($footer);
        if ($footer != "") { // Footer exists, display
            echo '<p id="ew-page-footer">' . $footer . '</p>';
        }
    }

    // Set field visibility
    public function setVisibility()
    {
        $this->IdEleve->Visible = false;
        $this->MatricEleve->setVisibility();
        $this->NomEleve->setVisibility();
        $this->PostnomElve->setVisibility();
        $this->PrenomEleve->setVisibility();
        $this->SexeEleve->setVisibility();
        $this->DatenaissanceEleve->setVisibility();
        $this->LieunaissanceEleve->setVisibility();
        $this->NomdupereElve->setVisibility();
        $this->NomdelamereEleve->setVisibility();
    }

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $DashboardReport, $DebugTimer;
        $this->TableVar = 'eleve';
        $this->TableName = 'eleve';

        // Table CSS class
        $this->TableClass = "table table-striped table-bordered table-hover table-sm ew-desktop-table ew-add-table";

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("language");

        // Table object (eleve)
        if (!isset($GLOBALS["eleve"]) || get_class($GLOBALS["eleve"]) == PROJECT_NAMESPACE . "eleve") {
            $GLOBALS["eleve"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'eleve');
        }

        // Start timer
        $DebugTimer = Container("timer");

        // Debug message
        LoadDebugMessage();

        // Open connection
        $GLOBALS["Conn"] ??= $this->getConnection();
    }

    // Get content from stream
    public function getContents(): string
    {
        global $Response;
        return is_object($Response) ? $Response->getBody() : ob_get_clean();
    }

    // Is lookup
    public function isLookup()
    {
        return SameText(Route(0), Config("API_LOOKUP_ACTION"));
    }

    // Is AutoFill
    public function isAutoFill()
    {
        return $this->isLookup() && SameText(Post("ajax"), "autofill");
    }

    // Is AutoSuggest
    public function isAutoSuggest()
    {
        return $this->isLookup() && SameText(Post("ajax"), "autosuggest");
    }

    // Is modal lookup
    public function isModalLookup()
    {
        return $this->isLookup() && SameText(Post("ajax"), "modal");
    }

    // Is terminated
    public function isTerminated()
    {
        return $this->terminated;
    }

    /**
     * Terminate page
     *
     * @param string $url URL for direction
     * @return void
     */
    public function terminate($url = "")
    {
        if ($this->terminated) {
            return;
        }
        global $TempImages, $DashboardReport, $Response;

        // Page is terminated
        $this->terminated = true;

        // Page Unload event
        if (method_exists($this, "pageUnload")) {
            $this->pageUnload();
        }

        // Global Page Unloaded event (in userfn*.php)
        Page_Unloaded();
        if (!IsApi() && method_exists($this, "pageRedirecting")) {
            $this->pageRedirecting($url);
        }

        // Close connection
        CloseConnections();

        // Return for API
        if (IsApi()) {
            $res = $url === true;
            if (!$res) { // Show response for API
                $ar = array_merge($this->getMessages(), $url ? ["url" => GetUrl($url)] : []);
                WriteJson($ar);
            }
            $this->clearMessages(); // Clear messages for API request
            return;
        } else { // Check if response is JSON
            if (StartsString("application/json", $Response->getHeaderLine("Content-type")) && $Response->getBody()->getSize()) { // With JSON response
                $this->clearMessages();
                return;
            }
        }

        // Go to URL if specified
        if ($url != "") {
            if (!Config("DEBUG") && ob_get_length()) {
                ob_end_clean();
            }

            // Handle modal response (Assume return to modal for simplicity)
            if ($this->IsModal) { // Show as modal
                $result = ["url" => GetUrl($url), "modal" => "1"];
                $pageName = GetPageName($url);
                if ($pageName != $this->getListUrl()) { // Not List page => View page
                    $result["caption"] = $this->getModalCaption($pageName);
                    $result["view"] = $pageName == "EleveView"; // If View page, no primary button
                } else { // List page
                    // $result["list"] = $this->PageID == "search"; // Refresh List page if current page is Search page
                    $result["error"] = $this->getFailureMessage(); // List page should not be shown as modal => error
                    $this->clearFailureMessage();
                }
                WriteJson($result);
            } else {
                SaveDebugMessage();
                Redirect(GetUrl($url));
            }
        }
        return; // Return to controller
    }

    // Get records from recordset
    protected function getRecordsFromRecordset($rs, $current = false)
    {
        $rows = [];
        if (is_object($rs)) { // Recordset
            while ($rs && !$rs->EOF) {
                $this->loadRowValues($rs); // Set up DbValue/CurrentValue
                $row = $this->getRecordFromArray($rs->fields);
                if ($current) {
                    return $row;
                } else {
                    $rows[] = $row;
                }
                $rs->moveNext();
            }
        } elseif (is_array($rs)) {
            foreach ($rs as $ar) {
                $row = $this->getRecordFromArray($ar);
                if ($current) {
                    return $row;
                } else {
                    $rows[] = $row;
                }
            }
        }
        return $rows;
    }

    // Get record from array
    protected function getRecordFromArray($ar)
    {
        $row = [];
        if (is_array($ar)) {
            foreach ($ar as $fldname => $val) {
                if (array_key_exists($fldname, $this->Fields) && ($this->Fields[$fldname]->Visible || $this->Fields[$fldname]->IsPrimaryKey)) { // Primary key or Visible
                    $fld = &$this->Fields[$fldname];
                    if ($fld->HtmlTag == "FILE") { // Upload field
                        if (EmptyValue($val)) {
                            $row[$fldname] = null;
                        } else {
                            if ($fld->DataType == DATATYPE_BLOB) {
                                $url = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                    "/" . $fld->TableVar . "/" . $fld->Param . "/" . rawurlencode($this->getRecordKeyValue($ar))));
                                $row[$fldname] = ["type" => ContentType($val), "url" => $url, "name" => $fld->Param . ContentExtension($val)];
                            } elseif (!$fld->UploadMultiple || !ContainsString($val, Config("MULTIPLE_UPLOAD_SEPARATOR"))) { // Single file
                                $url = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                    "/" . $fld->TableVar . "/" . Encrypt($fld->physicalUploadPath() . $val)));
                                $row[$fldname] = ["type" => MimeContentType($val), "url" => $url, "name" => $val];
                            } else { // Multiple files
                                $files = explode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $val);
                                $ar = [];
                                foreach ($files as $file) {
                                    $url = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                        "/" . $fld->TableVar . "/" . Encrypt($fld->physicalUploadPath() . $file)));
                                    if (!EmptyValue($file)) {
                                        $ar[] = ["type" => MimeContentType($file), "url" => $url, "name" => $file];
                                    }
                                }
                                $row[$fldname] = $ar;
                            }
                        }
                    } else {
                        $row[$fldname] = $val;
                    }
                }
            }
        }
        return $row;
    }

    // Get record key value from array
    protected function getRecordKeyValue($ar)
    {
        $key = "";
        if (is_array($ar)) {
            $key .= @$ar['IdEleve'];
        }
        return $key;
    }

    /**
     * Hide fields for add/edit
     *
     * @return void
     */
    protected function hideFieldsForAddEdit()
    {
        if ($this->isAdd() || $this->isCopy() || $this->isGridAdd()) {
            $this->IdEleve->Visible = false;
        }
    }

    // Lookup data
    public function lookup($ar = null)
    {
        global $Language, $Security;

        // Get lookup object
        $fieldName = $ar["field"] ?? Post("field");
        $lookup = $this->Fields[$fieldName]->Lookup;
        $name = $ar["name"] ?? Post("name");
        $isQuery = ContainsString($name, "query_builder_rule");
        if ($isQuery) {
            $lookup->FilterFields = []; // Skip parent fields if any
        }

        // Get lookup parameters
        $lookupType = $ar["ajax"] ?? Post("ajax", "unknown");
        $pageSize = -1;
        $offset = -1;
        $searchValue = "";
        if (SameText($lookupType, "modal") || SameText($lookupType, "filter")) {
            $searchValue = $ar["q"] ?? Param("q") ?? $ar["sv"] ?? Post("sv", "");
            $pageSize = $ar["n"] ?? Param("n") ?? $ar["recperpage"] ?? Post("recperpage", 10);
        } elseif (SameText($lookupType, "autosuggest")) {
            $searchValue = $ar["q"] ?? Param("q", "");
            $pageSize = $ar["n"] ?? Param("n", -1);
            $pageSize = is_numeric($pageSize) ? (int)$pageSize : -1;
            if ($pageSize <= 0) {
                $pageSize = Config("AUTO_SUGGEST_MAX_ENTRIES");
            }
        }
        $start = $ar["start"] ?? Param("start", -1);
        $start = is_numeric($start) ? (int)$start : -1;
        $page = $ar["page"] ?? Param("page", -1);
        $page = is_numeric($page) ? (int)$page : -1;
        $offset = $start >= 0 ? $start : ($page > 0 && $pageSize > 0 ? ($page - 1) * $pageSize : 0);
        $userSelect = Decrypt($ar["s"] ?? Post("s", ""));
        $userFilter = Decrypt($ar["f"] ?? Post("f", ""));
        $userOrderBy = Decrypt($ar["o"] ?? Post("o", ""));
        $keys = $ar["keys"] ?? Post("keys");
        $lookup->LookupType = $lookupType; // Lookup type
        $lookup->FilterValues = []; // Clear filter values first
        if ($keys !== null) { // Selected records from modal
            if (is_array($keys)) {
                $keys = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $keys);
            }
            $lookup->FilterFields = []; // Skip parent fields if any
            $lookup->FilterValues[] = $keys; // Lookup values
            $pageSize = -1; // Show all records
        } else { // Lookup values
            $lookup->FilterValues[] = $ar["v0"] ?? $ar["lookupValue"] ?? Post("v0", Post("lookupValue", ""));
        }
        $cnt = is_array($lookup->FilterFields) ? count($lookup->FilterFields) : 0;
        for ($i = 1; $i <= $cnt; $i++) {
            $lookup->FilterValues[] = $ar["v" . $i] ?? Post("v" . $i, "");
        }
        $lookup->SearchValue = $searchValue;
        $lookup->PageSize = $pageSize;
        $lookup->Offset = $offset;
        if ($userSelect != "") {
            $lookup->UserSelect = $userSelect;
        }
        if ($userFilter != "") {
            $lookup->UserFilter = $userFilter;
        }
        if ($userOrderBy != "") {
            $lookup->UserOrderBy = $userOrderBy;
        }
        return $lookup->toJson($this, !is_array($ar)); // Use settings from current page
    }
    public $FormClassName = "ew-form ew-add-form";
    public $IsModal = false;
    public $IsMobileOrModal = false;
    public $DbMasterFilter = "";
    public $DbDetailFilter = "";
    public $StartRecord;
    public $Priv = 0;
    public $CopyRecord;

    /**
     * Page run
     *
     * @return void
     */
    public function run()
    {
        global $ExportType, $UserProfile, $Language, $Security, $CurrentForm, $SkipHeaderFooter;

        // Is modal
        $this->IsModal = ConvertToBool(Param("modal"));
        $this->UseLayout = $this->UseLayout && !$this->IsModal;

        // Use layout
        $this->UseLayout = $this->UseLayout && ConvertToBool(Param(Config("PAGE_LAYOUT"), true));

        // View
        $this->View = Get(Config("VIEW"));

        // Create form object
        $CurrentForm = new HttpForm();
        $this->CurrentAction = Param("action"); // Set up current action
        $this->setVisibility();

        // Set lookup cache
        if (!in_array($this->PageID, Config("LOOKUP_CACHE_PAGE_IDS"))) {
            $this->setUseLookupCache(false);
        }

        // Global Page Loading event (in userfn*.php)
        Page_Loading();

        // Page Load event
        if (method_exists($this, "pageLoad")) {
            $this->pageLoad();
        }

        // Hide fields for add/edit
        if (!$this->UseAjaxActions) {
            $this->hideFieldsForAddEdit();
        }
        // Use inline delete
        if ($this->UseAjaxActions) {
            $this->InlineDelete = true;
        }

        // Set up lookup cache
        $this->setupLookupOptions($this->SexeEleve);

        // Load default values for add
        $this->loadDefaultValues();

        // Check modal
        if ($this->IsModal) {
            $SkipHeaderFooter = true;
        }
        $this->IsMobileOrModal = IsMobile() || $this->IsModal;
        $postBack = false;

        // Set up current action
        if (IsApi()) {
            $this->CurrentAction = "insert"; // Add record directly
            $postBack = true;
        } elseif (Post("action", "") !== "") {
            $this->CurrentAction = Post("action"); // Get form action
            $this->setKey(Post($this->OldKeyName));
            $postBack = true;
        } else {
            // Load key values from QueryString
            if (($keyValue = Get("IdEleve") ?? Route("IdEleve")) !== null) {
                $this->IdEleve->setQueryStringValue($keyValue);
            }
            $this->OldKey = $this->getKey(true); // Get from CurrentValue
            $this->CopyRecord = !EmptyValue($this->OldKey);
            if ($this->CopyRecord) {
                $this->CurrentAction = "copy"; // Copy record
                $this->setKey($this->OldKey); // Set up record key
            } else {
                $this->CurrentAction = "show"; // Display blank record
            }
        }

        // Load old record or default values
        $rsold = $this->loadOldRecord();

        // Load form values
        if ($postBack) {
            $this->loadFormValues(); // Load form values
        }

        // Validate form if post back
        if ($postBack) {
            if (!$this->validateForm()) {
                $this->EventCancelled = true; // Event cancelled
                $this->restoreFormValues(); // Restore form values
                if (IsApi()) {
                    $this->terminate();
                    return;
                } else {
                    $this->CurrentAction = "show"; // Form error, reset action
                }
            }
        }

        // Perform current action
        switch ($this->CurrentAction) {
            case "copy": // Copy an existing record
                if (!$rsold) { // Record not loaded
                    if ($this->getFailureMessage() == "") {
                        $this->setFailureMessage($Language->phrase("NoRecord")); // No record found
                    }
                    $this->terminate("EleveList"); // No matching record, return to list
                    return;
                }
                break;
            case "insert": // Add new record
                $this->SendEmail = true; // Send email on add success
                if ($this->addRow($rsold)) { // Add successful
                    if ($this->getSuccessMessage() == "" && Post("addopt") != "1") { // Skip success message for addopt (done in JavaScript)
                        $this->setSuccessMessage($Language->phrase("AddSuccess")); // Set up success message
                    }
                    $returnUrl = $this->getReturnUrl();
                    if (GetPageName($returnUrl) == "EleveList") {
                        $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                    } elseif (GetPageName($returnUrl) == "EleveView") {
                        $returnUrl = $this->getViewUrl(); // View page, return to View page with keyurl directly
                    }

                    // Handle UseAjaxActions
                    if ($this->IsModal && $this->UseAjaxActions) {
                        $this->IsModal = false;
                        if (GetPageName($returnUrl) != "EleveList") {
                            Container("flash")->addMessage("Return-Url", $returnUrl); // Save return URL
                            $returnUrl = "EleveList"; // Return list page content
                        }
                    }
                    if (IsJsonResponse()) { // Return to caller
                        $this->terminate(true);
                        return;
                    } else {
                        $this->terminate($returnUrl);
                        return;
                    }
                } elseif (IsApi()) { // API request, return
                    $this->terminate();
                    return;
                } elseif ($this->IsModal && $this->UseAjaxActions) { // Return JSON error message
                    WriteJson([ "success" => false, "validation" => $this->getValidationErrors(), "error" => $this->getFailureMessage() ]);
                    $this->clearFailureMessage();
                    $this->terminate();
                    return;
                } else {
                    $this->EventCancelled = true; // Event cancelled
                    $this->restoreFormValues(); // Add failed, restore form values
                }
        }

        // Set up Breadcrumb
        $this->setupBreadcrumb();

        // Render row based on row type
        $this->RowType = ROWTYPE_ADD; // Render add type

        // Render row
        $this->resetAttributes();
        $this->renderRow();

        // Set LoginStatus / Page_Rendering / Page_Render
        if (!IsApi() && !$this->isTerminated()) {
            // Setup login status
            SetupLoginStatus();

            // Pass login status to client side
            SetClientVar("login", LoginStatus());

            // Global Page Rendering event (in userfn*.php)
            Page_Rendering();

            // Page Render event
            if (method_exists($this, "pageRender")) {
                $this->pageRender();
            }

            // Render search option
            if (method_exists($this, "renderSearchOptions")) {
                $this->renderSearchOptions();
            }
        }
    }

    // Get upload files
    protected function getUploadFiles()
    {
        global $CurrentForm, $Language;
    }

    // Load default values
    protected function loadDefaultValues()
    {
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;
        $validate = !Config("SERVER_VALIDATE");

        // Check field name 'MatricEleve' first before field var 'x_MatricEleve'
        $val = $CurrentForm->hasValue("MatricEleve") ? $CurrentForm->getValue("MatricEleve") : $CurrentForm->getValue("x_MatricEleve");
        if (!$this->MatricEleve->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->MatricEleve->Visible = false; // Disable update for API request
            } else {
                $this->MatricEleve->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'NomEleve' first before field var 'x_NomEleve'
        $val = $CurrentForm->hasValue("NomEleve") ? $CurrentForm->getValue("NomEleve") : $CurrentForm->getValue("x_NomEleve");
        if (!$this->NomEleve->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->NomEleve->Visible = false; // Disable update for API request
            } else {
                $this->NomEleve->setFormValue($val);
            }
        }

        // Check field name 'PostnomElve' first before field var 'x_PostnomElve'
        $val = $CurrentForm->hasValue("PostnomElve") ? $CurrentForm->getValue("PostnomElve") : $CurrentForm->getValue("x_PostnomElve");
        if (!$this->PostnomElve->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->PostnomElve->Visible = false; // Disable update for API request
            } else {
                $this->PostnomElve->setFormValue($val);
            }
        }

        // Check field name 'PrenomEleve' first before field var 'x_PrenomEleve'
        $val = $CurrentForm->hasValue("PrenomEleve") ? $CurrentForm->getValue("PrenomEleve") : $CurrentForm->getValue("x_PrenomEleve");
        if (!$this->PrenomEleve->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->PrenomEleve->Visible = false; // Disable update for API request
            } else {
                $this->PrenomEleve->setFormValue($val);
            }
        }

        // Check field name 'SexeEleve' first before field var 'x_SexeEleve'
        $val = $CurrentForm->hasValue("SexeEleve") ? $CurrentForm->getValue("SexeEleve") : $CurrentForm->getValue("x_SexeEleve");
        if (!$this->SexeEleve->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->SexeEleve->Visible = false; // Disable update for API request
            } else {
                $this->SexeEleve->setFormValue($val);
            }
        }

        // Check field name 'DatenaissanceEleve' first before field var 'x_DatenaissanceEleve'
        $val = $CurrentForm->hasValue("DatenaissanceEleve") ? $CurrentForm->getValue("DatenaissanceEleve") : $CurrentForm->getValue("x_DatenaissanceEleve");
        if (!$this->DatenaissanceEleve->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->DatenaissanceEleve->Visible = false; // Disable update for API request
            } else {
                $this->DatenaissanceEleve->setFormValue($val, true, $validate);
            }
            $this->DatenaissanceEleve->CurrentValue = UnFormatDateTime($this->DatenaissanceEleve->CurrentValue, $this->DatenaissanceEleve->formatPattern());
        }

        // Check field name 'LieunaissanceEleve' first before field var 'x_LieunaissanceEleve'
        $val = $CurrentForm->hasValue("LieunaissanceEleve") ? $CurrentForm->getValue("LieunaissanceEleve") : $CurrentForm->getValue("x_LieunaissanceEleve");
        if (!$this->LieunaissanceEleve->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->LieunaissanceEleve->Visible = false; // Disable update for API request
            } else {
                $this->LieunaissanceEleve->setFormValue($val);
            }
        }

        // Check field name 'NomdupereElve' first before field var 'x_NomdupereElve'
        $val = $CurrentForm->hasValue("NomdupereElve") ? $CurrentForm->getValue("NomdupereElve") : $CurrentForm->getValue("x_NomdupereElve");
        if (!$this->NomdupereElve->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->NomdupereElve->Visible = false; // Disable update for API request
            } else {
                $this->NomdupereElve->setFormValue($val);
            }
        }

        // Check field name 'NomdelamereEleve' first before field var 'x_NomdelamereEleve'
        $val = $CurrentForm->hasValue("NomdelamereEleve") ? $CurrentForm->getValue("NomdelamereEleve") : $CurrentForm->getValue("x_NomdelamereEleve");
        if (!$this->NomdelamereEleve->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->NomdelamereEleve->Visible = false; // Disable update for API request
            } else {
                $this->NomdelamereEleve->setFormValue($val);
            }
        }

        // Check field name 'IdEleve' first before field var 'x_IdEleve'
        $val = $CurrentForm->hasValue("IdEleve") ? $CurrentForm->getValue("IdEleve") : $CurrentForm->getValue("x_IdEleve");
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->MatricEleve->CurrentValue = $this->MatricEleve->FormValue;
        $this->NomEleve->CurrentValue = $this->NomEleve->FormValue;
        $this->PostnomElve->CurrentValue = $this->PostnomElve->FormValue;
        $this->PrenomEleve->CurrentValue = $this->PrenomEleve->FormValue;
        $this->SexeEleve->CurrentValue = $this->SexeEleve->FormValue;
        $this->DatenaissanceEleve->CurrentValue = $this->DatenaissanceEleve->FormValue;
        $this->DatenaissanceEleve->CurrentValue = UnFormatDateTime($this->DatenaissanceEleve->CurrentValue, $this->DatenaissanceEleve->formatPattern());
        $this->LieunaissanceEleve->CurrentValue = $this->LieunaissanceEleve->FormValue;
        $this->NomdupereElve->CurrentValue = $this->NomdupereElve->FormValue;
        $this->NomdelamereEleve->CurrentValue = $this->NomdelamereEleve->FormValue;
    }

    /**
     * Load row based on key values
     *
     * @return void
     */
    public function loadRow()
    {
        global $Security, $Language;
        $filter = $this->getRecordFilter();

        // Call Row Selecting event
        $this->rowSelecting($filter);

        // Load SQL based on filter
        $this->CurrentFilter = $filter;
        $sql = $this->getCurrentSql();
        $conn = $this->getConnection();
        $res = false;
        $row = $conn->fetchAssociative($sql);
        if ($row) {
            $res = true;
            $this->loadRowValues($row); // Load row values
        }
        return $res;
    }

    /**
     * Load row values from recordset or record
     *
     * @param Recordset|array $rs Record
     * @return void
     */
    public function loadRowValues($rs = null)
    {
        if (is_array($rs)) {
            $row = $rs;
        } elseif ($rs && property_exists($rs, "fields")) { // Recordset
            $row = $rs->fields;
        } else {
            $row = $this->newRow();
        }
        if (!$row) {
            return;
        }

        // Call Row Selected event
        $this->rowSelected($row);
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

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['IdEleve'] = $this->IdEleve->DefaultValue;
        $row['MatricEleve'] = $this->MatricEleve->DefaultValue;
        $row['NomEleve'] = $this->NomEleve->DefaultValue;
        $row['PostnomElve'] = $this->PostnomElve->DefaultValue;
        $row['PrenomEleve'] = $this->PrenomEleve->DefaultValue;
        $row['SexeEleve'] = $this->SexeEleve->DefaultValue;
        $row['DatenaissanceEleve'] = $this->DatenaissanceEleve->DefaultValue;
        $row['LieunaissanceEleve'] = $this->LieunaissanceEleve->DefaultValue;
        $row['NomdupereElve'] = $this->NomdupereElve->DefaultValue;
        $row['NomdelamereEleve'] = $this->NomdelamereEleve->DefaultValue;
        return $row;
    }

    // Load old record
    protected function loadOldRecord()
    {
        // Load old record
        if ($this->OldKey != "") {
            $this->setKey($this->OldKey);
            $this->CurrentFilter = $this->getRecordFilter();
            $sql = $this->getCurrentSql();
            $conn = $this->getConnection();
            $rs = LoadRecordset($sql, $conn);
            if ($rs && ($row = $rs->fields)) {
                $this->loadRowValues($row); // Load row values
                return $row;
            }
        }
        $this->loadRowValues(); // Load default row values
        return null;
    }

    // Render row values based on field settings
    public function renderRow()
    {
        global $Security, $Language, $CurrentLanguage;

        // Initialize URLs

        // Call Row_Rendering event
        $this->rowRendering();

        // Common render codes for all row types

        // IdEleve
        $this->IdEleve->RowCssClass = "row";

        // MatricEleve
        $this->MatricEleve->RowCssClass = "row";

        // NomEleve
        $this->NomEleve->RowCssClass = "row";

        // PostnomElve
        $this->PostnomElve->RowCssClass = "row";

        // PrenomEleve
        $this->PrenomEleve->RowCssClass = "row";

        // SexeEleve
        $this->SexeEleve->RowCssClass = "row";

        // DatenaissanceEleve
        $this->DatenaissanceEleve->RowCssClass = "row";

        // LieunaissanceEleve
        $this->LieunaissanceEleve->RowCssClass = "row";

        // NomdupereElve
        $this->NomdupereElve->RowCssClass = "row";

        // NomdelamereEleve
        $this->NomdelamereEleve->RowCssClass = "row";

        // View row
        if ($this->RowType == ROWTYPE_VIEW) {
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

            // MatricEleve
            $this->MatricEleve->HrefValue = "";

            // NomEleve
            $this->NomEleve->HrefValue = "";

            // PostnomElve
            $this->PostnomElve->HrefValue = "";

            // PrenomEleve
            $this->PrenomEleve->HrefValue = "";

            // SexeEleve
            $this->SexeEleve->HrefValue = "";

            // DatenaissanceEleve
            $this->DatenaissanceEleve->HrefValue = "";

            // LieunaissanceEleve
            $this->LieunaissanceEleve->HrefValue = "";

            // NomdupereElve
            $this->NomdupereElve->HrefValue = "";

            // NomdelamereEleve
            $this->NomdelamereEleve->HrefValue = "";
        } elseif ($this->RowType == ROWTYPE_ADD) {
            // MatricEleve
            $this->MatricEleve->setupEditAttributes();
            $this->MatricEleve->EditValue = HtmlEncode($this->MatricEleve->CurrentValue);
            $this->MatricEleve->PlaceHolder = RemoveHtml($this->MatricEleve->caption());
            if (strval($this->MatricEleve->EditValue) != "" && is_numeric($this->MatricEleve->EditValue)) {
                $this->MatricEleve->EditValue = $this->MatricEleve->EditValue;
            }

            // NomEleve
            $this->NomEleve->setupEditAttributes();
            if (!$this->NomEleve->Raw) {
                $this->NomEleve->CurrentValue = HtmlDecode($this->NomEleve->CurrentValue);
            }
            $this->NomEleve->EditValue = HtmlEncode($this->NomEleve->CurrentValue);
            $this->NomEleve->PlaceHolder = RemoveHtml($this->NomEleve->caption());

            // PostnomElve
            $this->PostnomElve->setupEditAttributes();
            if (!$this->PostnomElve->Raw) {
                $this->PostnomElve->CurrentValue = HtmlDecode($this->PostnomElve->CurrentValue);
            }
            $this->PostnomElve->EditValue = HtmlEncode($this->PostnomElve->CurrentValue);
            $this->PostnomElve->PlaceHolder = RemoveHtml($this->PostnomElve->caption());

            // PrenomEleve
            $this->PrenomEleve->setupEditAttributes();
            if (!$this->PrenomEleve->Raw) {
                $this->PrenomEleve->CurrentValue = HtmlDecode($this->PrenomEleve->CurrentValue);
            }
            $this->PrenomEleve->EditValue = HtmlEncode($this->PrenomEleve->CurrentValue);
            $this->PrenomEleve->PlaceHolder = RemoveHtml($this->PrenomEleve->caption());

            // SexeEleve
            $this->SexeEleve->EditValue = $this->SexeEleve->options(false);
            $this->SexeEleve->PlaceHolder = RemoveHtml($this->SexeEleve->caption());

            // DatenaissanceEleve
            $this->DatenaissanceEleve->setupEditAttributes();
            $this->DatenaissanceEleve->EditValue = HtmlEncode(FormatDateTime($this->DatenaissanceEleve->CurrentValue, $this->DatenaissanceEleve->formatPattern()));
            $this->DatenaissanceEleve->PlaceHolder = RemoveHtml($this->DatenaissanceEleve->caption());

            // LieunaissanceEleve
            $this->LieunaissanceEleve->setupEditAttributes();
            if (!$this->LieunaissanceEleve->Raw) {
                $this->LieunaissanceEleve->CurrentValue = HtmlDecode($this->LieunaissanceEleve->CurrentValue);
            }
            $this->LieunaissanceEleve->EditValue = HtmlEncode($this->LieunaissanceEleve->CurrentValue);
            $this->LieunaissanceEleve->PlaceHolder = RemoveHtml($this->LieunaissanceEleve->caption());

            // NomdupereElve
            $this->NomdupereElve->setupEditAttributes();
            if (!$this->NomdupereElve->Raw) {
                $this->NomdupereElve->CurrentValue = HtmlDecode($this->NomdupereElve->CurrentValue);
            }
            $this->NomdupereElve->EditValue = HtmlEncode($this->NomdupereElve->CurrentValue);
            $this->NomdupereElve->PlaceHolder = RemoveHtml($this->NomdupereElve->caption());

            // NomdelamereEleve
            $this->NomdelamereEleve->setupEditAttributes();
            if (!$this->NomdelamereEleve->Raw) {
                $this->NomdelamereEleve->CurrentValue = HtmlDecode($this->NomdelamereEleve->CurrentValue);
            }
            $this->NomdelamereEleve->EditValue = HtmlEncode($this->NomdelamereEleve->CurrentValue);
            $this->NomdelamereEleve->PlaceHolder = RemoveHtml($this->NomdelamereEleve->caption());

            // Add refer script

            // MatricEleve
            $this->MatricEleve->HrefValue = "";

            // NomEleve
            $this->NomEleve->HrefValue = "";

            // PostnomElve
            $this->PostnomElve->HrefValue = "";

            // PrenomEleve
            $this->PrenomEleve->HrefValue = "";

            // SexeEleve
            $this->SexeEleve->HrefValue = "";

            // DatenaissanceEleve
            $this->DatenaissanceEleve->HrefValue = "";

            // LieunaissanceEleve
            $this->LieunaissanceEleve->HrefValue = "";

            // NomdupereElve
            $this->NomdupereElve->HrefValue = "";

            // NomdelamereEleve
            $this->NomdelamereEleve->HrefValue = "";
        }
        if ($this->RowType == ROWTYPE_ADD || $this->RowType == ROWTYPE_EDIT || $this->RowType == ROWTYPE_SEARCH) { // Add/Edit/Search row
            $this->setupFieldTitles();
        }

        // Call Row Rendered event
        if ($this->RowType != ROWTYPE_AGGREGATEINIT) {
            $this->rowRendered();
        }
    }

    // Validate form
    protected function validateForm()
    {
        global $Language, $Security;

        // Check if validation required
        if (!Config("SERVER_VALIDATE")) {
            return true;
        }
        $validateForm = true;
        if ($this->MatricEleve->Required) {
            if (!$this->MatricEleve->IsDetailKey && EmptyValue($this->MatricEleve->FormValue)) {
                $this->MatricEleve->addErrorMessage(str_replace("%s", $this->MatricEleve->caption(), $this->MatricEleve->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->MatricEleve->FormValue)) {
            $this->MatricEleve->addErrorMessage($this->MatricEleve->getErrorMessage(false));
        }
        if ($this->NomEleve->Required) {
            if (!$this->NomEleve->IsDetailKey && EmptyValue($this->NomEleve->FormValue)) {
                $this->NomEleve->addErrorMessage(str_replace("%s", $this->NomEleve->caption(), $this->NomEleve->RequiredErrorMessage));
            }
        }
        if ($this->PostnomElve->Required) {
            if (!$this->PostnomElve->IsDetailKey && EmptyValue($this->PostnomElve->FormValue)) {
                $this->PostnomElve->addErrorMessage(str_replace("%s", $this->PostnomElve->caption(), $this->PostnomElve->RequiredErrorMessage));
            }
        }
        if ($this->PrenomEleve->Required) {
            if (!$this->PrenomEleve->IsDetailKey && EmptyValue($this->PrenomEleve->FormValue)) {
                $this->PrenomEleve->addErrorMessage(str_replace("%s", $this->PrenomEleve->caption(), $this->PrenomEleve->RequiredErrorMessage));
            }
        }
        if ($this->SexeEleve->Required) {
            if ($this->SexeEleve->FormValue == "") {
                $this->SexeEleve->addErrorMessage(str_replace("%s", $this->SexeEleve->caption(), $this->SexeEleve->RequiredErrorMessage));
            }
        }
        if ($this->DatenaissanceEleve->Required) {
            if (!$this->DatenaissanceEleve->IsDetailKey && EmptyValue($this->DatenaissanceEleve->FormValue)) {
                $this->DatenaissanceEleve->addErrorMessage(str_replace("%s", $this->DatenaissanceEleve->caption(), $this->DatenaissanceEleve->RequiredErrorMessage));
            }
        }
        if (!CheckDate($this->DatenaissanceEleve->FormValue, $this->DatenaissanceEleve->formatPattern())) {
            $this->DatenaissanceEleve->addErrorMessage($this->DatenaissanceEleve->getErrorMessage(false));
        }
        if ($this->LieunaissanceEleve->Required) {
            if (!$this->LieunaissanceEleve->IsDetailKey && EmptyValue($this->LieunaissanceEleve->FormValue)) {
                $this->LieunaissanceEleve->addErrorMessage(str_replace("%s", $this->LieunaissanceEleve->caption(), $this->LieunaissanceEleve->RequiredErrorMessage));
            }
        }
        if ($this->NomdupereElve->Required) {
            if (!$this->NomdupereElve->IsDetailKey && EmptyValue($this->NomdupereElve->FormValue)) {
                $this->NomdupereElve->addErrorMessage(str_replace("%s", $this->NomdupereElve->caption(), $this->NomdupereElve->RequiredErrorMessage));
            }
        }
        if ($this->NomdelamereEleve->Required) {
            if (!$this->NomdelamereEleve->IsDetailKey && EmptyValue($this->NomdelamereEleve->FormValue)) {
                $this->NomdelamereEleve->addErrorMessage(str_replace("%s", $this->NomdelamereEleve->caption(), $this->NomdelamereEleve->RequiredErrorMessage));
            }
        }

        // Return validate result
        $validateForm = $validateForm && !$this->hasInvalidFields();

        // Call Form_CustomValidate event
        $formCustomError = "";
        $validateForm = $validateForm && $this->formCustomValidate($formCustomError);
        if ($formCustomError != "") {
            $this->setFailureMessage($formCustomError);
        }
        return $validateForm;
    }

    // Add record
    protected function addRow($rsold = null)
    {
        global $Language, $Security;

        // Set new row
        $rsnew = [];

        // MatricEleve
        $this->MatricEleve->setDbValueDef($rsnew, $this->MatricEleve->CurrentValue, false);

        // NomEleve
        $this->NomEleve->setDbValueDef($rsnew, $this->NomEleve->CurrentValue, false);

        // PostnomElve
        $this->PostnomElve->setDbValueDef($rsnew, $this->PostnomElve->CurrentValue, false);

        // PrenomEleve
        $this->PrenomEleve->setDbValueDef($rsnew, $this->PrenomEleve->CurrentValue, false);

        // SexeEleve
        $this->SexeEleve->setDbValueDef($rsnew, $this->SexeEleve->CurrentValue, false);

        // DatenaissanceEleve
        $this->DatenaissanceEleve->setDbValueDef($rsnew, UnFormatDateTime($this->DatenaissanceEleve->CurrentValue, $this->DatenaissanceEleve->formatPattern()), false);

        // LieunaissanceEleve
        $this->LieunaissanceEleve->setDbValueDef($rsnew, $this->LieunaissanceEleve->CurrentValue, false);

        // NomdupereElve
        $this->NomdupereElve->setDbValueDef($rsnew, $this->NomdupereElve->CurrentValue, false);

        // NomdelamereEleve
        $this->NomdelamereEleve->setDbValueDef($rsnew, $this->NomdelamereEleve->CurrentValue, false);

        // Update current values
        $this->setCurrentValues($rsnew);
        if ($this->MatricEleve->CurrentValue != "") { // Check field with unique index
            $filter = "(`MatricEleve` = " . AdjustSql($this->MatricEleve->CurrentValue, $this->Dbid) . ")";
            $rsChk = $this->loadRs($filter)->fetch();
            if ($rsChk !== false) {
                $idxErrMsg = str_replace("%f", $this->MatricEleve->caption(), $Language->phrase("DupIndex"));
                $idxErrMsg = str_replace("%v", $this->MatricEleve->CurrentValue, $idxErrMsg);
                $this->setFailureMessage($idxErrMsg);
                return false;
            }
        }
        $conn = $this->getConnection();

        // Load db values from old row
        $this->loadDbValues($rsold);

        // Call Row Inserting event
        $insertRow = $this->rowInserting($rsold, $rsnew);
        if ($insertRow) {
            $addRow = $this->insert($rsnew);
            if ($addRow) {
            } elseif (!EmptyValue($this->DbErrorMessage)) { // Show database error
                $this->setFailureMessage($this->DbErrorMessage);
            }
        } else {
            if ($this->getSuccessMessage() != "" || $this->getFailureMessage() != "") {
                // Use the message, do nothing
            } elseif ($this->CancelMessage != "") {
                $this->setFailureMessage($this->CancelMessage);
                $this->CancelMessage = "";
            } else {
                $this->setFailureMessage($Language->phrase("InsertCancelled"));
            }
            $addRow = false;
        }
        if ($addRow) {
            // Call Row Inserted event
            $this->rowInserted($rsold, $rsnew);
        }

        // Write JSON response
        if (IsJsonResponse() && $addRow) {
            $row = $this->getRecordsFromRecordset([$rsnew], true);
            $table = $this->TableVar;
            WriteJson(["success" => true, "action" => Config("API_ADD_ACTION"), $table => $row]);
        }
        return $addRow;
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("index");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("EleveList"), "", $this->TableVar, true);
        $pageId = ($this->isCopy()) ? "Copy" : "Add";
        $Breadcrumb->add("add", $pageId, $url);
    }

    // Setup lookup options
    public function setupLookupOptions($fld)
    {
        if ($fld->Lookup !== null && $fld->Lookup->Options === null) {
            // Get default connection and filter
            $conn = $this->getConnection();
            $lookupFilter = "";

            // No need to check any more
            $fld->Lookup->Options = [];

            // Set up lookup SQL and connection
            switch ($fld->FieldVar) {
                case "x_IdEleve":
                    break;
                case "x_SexeEleve":
                    break;
                default:
                    $lookupFilter = "";
                    break;
            }

            // Always call to Lookup->getSql so that user can setup Lookup->Options in Lookup_Selecting server event
            $sql = $fld->Lookup->getSql(false, "", $lookupFilter, $this);

            // Set up lookup cache
            if (!$fld->hasLookupOptions() && $fld->UseLookupCache && $sql != "" && count($fld->Lookup->Options) == 0 && count($fld->Lookup->FilterFields) == 0) {
                $totalCnt = $this->getRecordCount($sql, $conn);
                if ($totalCnt > $fld->LookupCacheCount) { // Total count > cache count, do not cache
                    return;
                }
                $rows = $conn->executeQuery($sql)->fetchAll();
                $ar = [];
                foreach ($rows as $row) {
                    $row = $fld->Lookup->renderViewRow($row, Container($fld->Lookup->LinkTable));
                    $key = $row["lf"];
                    if (IsFloatType($fld->Type)) { // Handle float field
                        $key = (float)$key;
                    }
                    $ar[strval($key)] = $row;
                }
                $fld->Lookup->Options = $ar;
            }
        }
    }

    // Page Load event
    public function pageLoad()
    {
        //Log("Page Load");
    }

    // Page Unload event
    public function pageUnload()
    {
        //Log("Page Unload");
    }

    // Page Redirecting event
    public function pageRedirecting(&$url)
    {
        // Example:
        //$url = "your URL";
    }

    // Message Showing event
    // $type = ''|'success'|'failure'|'warning'
    public function messageShowing(&$msg, $type)
    {
        if ($type == 'success') {
            //$msg = "your success message";
        } elseif ($type == 'failure') {
            //$msg = "your failure message";
        } elseif ($type == 'warning') {
            //$msg = "your warning message";
        } else {
            //$msg = "your message";
        }
    }

    // Page Render event
    public function pageRender()
    {
        //Log("Page Render");
    }

    // Page Data Rendering event
    public function pageDataRendering(&$header)
    {
        // Example:
        //$header = "your header";
    }

    // Page Data Rendered event
    public function pageDataRendered(&$footer)
    {
        // Example:
        //$footer = "your footer";
    }

    // Page Breaking event
    public function pageBreaking(&$break, &$content)
    {
        // Example:
        //$break = false; // Skip page break, or
        //$content = "<div style=\"break-after:page;\"></div>"; // Modify page break content
    }

    // Form Custom Validate event
    public function formCustomValidate(&$customError)
    {
        // Return error message in $customError
        return true;
    }
}
