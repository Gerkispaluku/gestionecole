<?php

namespace PHPMaker2023\gestion_ECOLE;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class AgentEdit extends Agent
{
    use MessagesTrait;

    // Page ID
    public $PageID = "edit";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "AgentEdit";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $CurrentPageName = "AgentEdit";

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
        $this->IdAgent->setVisibility();
        $this->MatricAgent->setVisibility();
        $this->NomAgent->setVisibility();
        $this->PostnomAgent->setVisibility();
        $this->PrenomAgent->setVisibility();
        $this->SexeAgent->setVisibility();
        $this->EtatCivilAgent->setVisibility();
        $this->TelephoneAgent->setVisibility();
        $this->Motsecret->setVisibility();
    }

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $DashboardReport, $DebugTimer;
        $this->TableVar = 'agent';
        $this->TableName = 'agent';

        // Table CSS class
        $this->TableClass = "table table-striped table-bordered table-hover table-sm ew-desktop-table ew-edit-table";

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("language");

        // Table object (agent)
        if (!isset($GLOBALS["agent"]) || get_class($GLOBALS["agent"]) == PROJECT_NAMESPACE . "agent") {
            $GLOBALS["agent"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'agent');
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
                    $result["view"] = $pageName == "AgentView"; // If View page, no primary button
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
            $key .= @$ar['IdAgent'];
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
            $this->IdAgent->Visible = false;
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

    // Properties
    public $FormClassName = "ew-form ew-edit-form overlay-wrapper";
    public $IsModal = false;
    public $IsMobileOrModal = false;
    public $DbMasterFilter;
    public $DbDetailFilter;
    public $HashValue; // Hash Value
    public $DisplayRecords = 1;
    public $StartRecord;
    public $StopRecord;
    public $TotalRecords = 0;
    public $RecordRange = 10;
    public $RecordCount;

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
        $this->setupLookupOptions($this->MatricAgent);
        $this->setupLookupOptions($this->SexeAgent);
        $this->setupLookupOptions($this->EtatCivilAgent);

        // Check modal
        if ($this->IsModal) {
            $SkipHeaderFooter = true;
        }
        $this->IsMobileOrModal = IsMobile() || $this->IsModal;
        $loaded = false;
        $postBack = false;

        // Set up current action and primary key
        if (IsApi()) {
            // Load key values
            $loaded = true;
            if (($keyValue = Get("IdAgent") ?? Key(0) ?? Route(2)) !== null) {
                $this->IdAgent->setQueryStringValue($keyValue);
                $this->IdAgent->setOldValue($this->IdAgent->QueryStringValue);
            } elseif (Post("IdAgent") !== null) {
                $this->IdAgent->setFormValue(Post("IdAgent"));
                $this->IdAgent->setOldValue($this->IdAgent->FormValue);
            } else {
                $loaded = false; // Unable to load key
            }

            // Load record
            if ($loaded) {
                $loaded = $this->loadRow();
            }
            if (!$loaded) {
                $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record message
                $this->terminate();
                return;
            }
            $this->CurrentAction = "update"; // Update record directly
            $this->OldKey = $this->getKey(true); // Get from CurrentValue
            $postBack = true;
        } else {
            if (Post("action", "") !== "") {
                $this->CurrentAction = Post("action"); // Get action code
                if (!$this->isShow()) { // Not reload record, handle as postback
                    $postBack = true;
                }

                // Get key from Form
                $this->setKey(Post($this->OldKeyName), $this->isShow());
            } else {
                $this->CurrentAction = "show"; // Default action is display

                // Load key from QueryString
                $loadByQuery = false;
                if (($keyValue = Get("IdAgent") ?? Route("IdAgent")) !== null) {
                    $this->IdAgent->setQueryStringValue($keyValue);
                    $loadByQuery = true;
                } else {
                    $this->IdAgent->CurrentValue = null;
                }
            }

            // Load recordset
            if ($this->isShow()) {
                    // Load current record
                    $loaded = $this->loadRow();
                $this->OldKey = $loaded ? $this->getKey(true) : ""; // Get from CurrentValue
            }
        }

        // Process form if post back
        if ($postBack) {
            $this->loadFormValues(); // Get form values
        }

        // Validate form if post back
        if ($postBack) {
            if (!$this->validateForm()) {
                $this->EventCancelled = true; // Event cancelled
                $this->restoreFormValues();
                if (IsApi()) {
                    $this->terminate();
                    return;
                } else {
                    $this->CurrentAction = ""; // Form error, reset action
                }
            }
        }

        // Perform current action
        switch ($this->CurrentAction) {
            case "show": // Get a record to display
                    if (!$loaded) { // Load record based on key
                        if ($this->getFailureMessage() == "") {
                            $this->setFailureMessage($Language->phrase("NoRecord")); // No record found
                        }
                        $this->terminate("AgentList"); // No matching record, return to list
                        return;
                    }
                break;
            case "update": // Update
                $returnUrl = $this->getReturnUrl();
                if (GetPageName($returnUrl) == "AgentList") {
                    $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                }
                $this->SendEmail = true; // Send email on update success
                if ($this->editRow()) {
                    // Handle UseAjaxActions with return page
                    if ($this->IsModal && $this->UseAjaxActions) {
                        $this->IsModal = false;
                        if (GetPageName($returnUrl) != "AgentList") {
                            Container("flash")->addMessage("Return-Url", $returnUrl); // Save return URL
                            $returnUrl = "AgentList"; // Return list page content
                        }
                    }
                    if ($this->getSuccessMessage() == "") {
                        $this->setSuccessMessage($Language->phrase("UpdateSuccess")); // Update success
                    }
                    if (IsJsonResponse()) {
                        $this->terminate(true);
                        return;
                    } else {
                        $this->terminate($returnUrl); // Return to caller
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
                } elseif ($this->getFailureMessage() == $Language->phrase("NoRecord")) {
                    $this->terminate($returnUrl); // Return to caller
                    return;
                } else {
                    $this->EventCancelled = true; // Event cancelled
                    $this->restoreFormValues(); // Restore form values if update failed
                }
        }

        // Set up Breadcrumb
        $this->setupBreadcrumb();

        // Render the record
        $this->RowType = ROWTYPE_EDIT; // Render as Edit
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

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;
        $validate = !Config("SERVER_VALIDATE");

        // Check field name 'IdAgent' first before field var 'x_IdAgent'
        $val = $CurrentForm->hasValue("IdAgent") ? $CurrentForm->getValue("IdAgent") : $CurrentForm->getValue("x_IdAgent");
        if (!$this->IdAgent->IsDetailKey) {
            $this->IdAgent->setFormValue($val);
        }

        // Check field name 'MatricAgent' first before field var 'x_MatricAgent'
        $val = $CurrentForm->hasValue("MatricAgent") ? $CurrentForm->getValue("MatricAgent") : $CurrentForm->getValue("x_MatricAgent");
        if (!$this->MatricAgent->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->MatricAgent->Visible = false; // Disable update for API request
            } else {
                $this->MatricAgent->setFormValue($val);
            }
        }

        // Check field name 'NomAgent' first before field var 'x_NomAgent'
        $val = $CurrentForm->hasValue("NomAgent") ? $CurrentForm->getValue("NomAgent") : $CurrentForm->getValue("x_NomAgent");
        if (!$this->NomAgent->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->NomAgent->Visible = false; // Disable update for API request
            } else {
                $this->NomAgent->setFormValue($val);
            }
        }

        // Check field name 'PostnomAgent' first before field var 'x_PostnomAgent'
        $val = $CurrentForm->hasValue("PostnomAgent") ? $CurrentForm->getValue("PostnomAgent") : $CurrentForm->getValue("x_PostnomAgent");
        if (!$this->PostnomAgent->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->PostnomAgent->Visible = false; // Disable update for API request
            } else {
                $this->PostnomAgent->setFormValue($val);
            }
        }

        // Check field name 'PrenomAgent' first before field var 'x_PrenomAgent'
        $val = $CurrentForm->hasValue("PrenomAgent") ? $CurrentForm->getValue("PrenomAgent") : $CurrentForm->getValue("x_PrenomAgent");
        if (!$this->PrenomAgent->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->PrenomAgent->Visible = false; // Disable update for API request
            } else {
                $this->PrenomAgent->setFormValue($val);
            }
        }

        // Check field name 'SexeAgent' first before field var 'x_SexeAgent'
        $val = $CurrentForm->hasValue("SexeAgent") ? $CurrentForm->getValue("SexeAgent") : $CurrentForm->getValue("x_SexeAgent");
        if (!$this->SexeAgent->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->SexeAgent->Visible = false; // Disable update for API request
            } else {
                $this->SexeAgent->setFormValue($val);
            }
        }

        // Check field name 'EtatCivilAgent' first before field var 'x_EtatCivilAgent'
        $val = $CurrentForm->hasValue("EtatCivilAgent") ? $CurrentForm->getValue("EtatCivilAgent") : $CurrentForm->getValue("x_EtatCivilAgent");
        if (!$this->EtatCivilAgent->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->EtatCivilAgent->Visible = false; // Disable update for API request
            } else {
                $this->EtatCivilAgent->setFormValue($val);
            }
        }

        // Check field name 'TelephoneAgent' first before field var 'x_TelephoneAgent'
        $val = $CurrentForm->hasValue("TelephoneAgent") ? $CurrentForm->getValue("TelephoneAgent") : $CurrentForm->getValue("x_TelephoneAgent");
        if (!$this->TelephoneAgent->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->TelephoneAgent->Visible = false; // Disable update for API request
            } else {
                $this->TelephoneAgent->setFormValue($val);
            }
        }

        // Check field name 'Motsecret' first before field var 'x_Motsecret'
        $val = $CurrentForm->hasValue("Motsecret") ? $CurrentForm->getValue("Motsecret") : $CurrentForm->getValue("x_Motsecret");
        if (!$this->Motsecret->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Motsecret->Visible = false; // Disable update for API request
            } else {
                $this->Motsecret->setFormValue($val, true, $validate);
            }
        }
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->IdAgent->CurrentValue = $this->IdAgent->FormValue;
        $this->MatricAgent->CurrentValue = $this->MatricAgent->FormValue;
        $this->NomAgent->CurrentValue = $this->NomAgent->FormValue;
        $this->PostnomAgent->CurrentValue = $this->PostnomAgent->FormValue;
        $this->PrenomAgent->CurrentValue = $this->PrenomAgent->FormValue;
        $this->SexeAgent->CurrentValue = $this->SexeAgent->FormValue;
        $this->EtatCivilAgent->CurrentValue = $this->EtatCivilAgent->FormValue;
        $this->TelephoneAgent->CurrentValue = $this->TelephoneAgent->FormValue;
        $this->Motsecret->CurrentValue = $this->Motsecret->FormValue;
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
        $this->IdAgent->setDbValue($row['IdAgent']);
        $this->MatricAgent->setDbValue($row['MatricAgent']);
        $this->NomAgent->setDbValue($row['NomAgent']);
        $this->PostnomAgent->setDbValue($row['PostnomAgent']);
        $this->PrenomAgent->setDbValue($row['PrenomAgent']);
        $this->SexeAgent->setDbValue($row['SexeAgent']);
        $this->EtatCivilAgent->setDbValue($row['EtatCivilAgent']);
        $this->TelephoneAgent->setDbValue($row['TelephoneAgent']);
        $this->Motsecret->setDbValue($row['Motsecret']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['IdAgent'] = $this->IdAgent->DefaultValue;
        $row['MatricAgent'] = $this->MatricAgent->DefaultValue;
        $row['NomAgent'] = $this->NomAgent->DefaultValue;
        $row['PostnomAgent'] = $this->PostnomAgent->DefaultValue;
        $row['PrenomAgent'] = $this->PrenomAgent->DefaultValue;
        $row['SexeAgent'] = $this->SexeAgent->DefaultValue;
        $row['EtatCivilAgent'] = $this->EtatCivilAgent->DefaultValue;
        $row['TelephoneAgent'] = $this->TelephoneAgent->DefaultValue;
        $row['Motsecret'] = $this->Motsecret->DefaultValue;
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

        // IdAgent
        $this->IdAgent->RowCssClass = "row";

        // MatricAgent
        $this->MatricAgent->RowCssClass = "row";

        // NomAgent
        $this->NomAgent->RowCssClass = "row";

        // PostnomAgent
        $this->PostnomAgent->RowCssClass = "row";

        // PrenomAgent
        $this->PrenomAgent->RowCssClass = "row";

        // SexeAgent
        $this->SexeAgent->RowCssClass = "row";

        // EtatCivilAgent
        $this->EtatCivilAgent->RowCssClass = "row";

        // TelephoneAgent
        $this->TelephoneAgent->RowCssClass = "row";

        // Motsecret
        $this->Motsecret->RowCssClass = "row";

        // View row
        if ($this->RowType == ROWTYPE_VIEW) {
            // IdAgent
            $this->IdAgent->ViewValue = $this->IdAgent->CurrentValue;

            // MatricAgent
            $this->MatricAgent->ViewValue = $this->MatricAgent->CurrentValue;
            $curVal = strval($this->MatricAgent->CurrentValue);
            if ($curVal != "") {
                $this->MatricAgent->ViewValue = $this->MatricAgent->lookupCacheOption($curVal);
                if ($this->MatricAgent->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter("`IdAgent`", "=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->MatricAgent->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->MatricAgent->Lookup->renderViewRow($rswrk[0]);
                        $this->MatricAgent->ViewValue = $this->MatricAgent->displayValue($arwrk);
                    } else {
                        $this->MatricAgent->ViewValue = $this->MatricAgent->CurrentValue;
                    }
                }
            } else {
                $this->MatricAgent->ViewValue = null;
            }

            // NomAgent
            $this->NomAgent->ViewValue = $this->NomAgent->CurrentValue;

            // PostnomAgent
            $this->PostnomAgent->ViewValue = $this->PostnomAgent->CurrentValue;

            // PrenomAgent
            $this->PrenomAgent->ViewValue = $this->PrenomAgent->CurrentValue;

            // SexeAgent
            if (strval($this->SexeAgent->CurrentValue) != "") {
                $this->SexeAgent->ViewValue = $this->SexeAgent->optionCaption($this->SexeAgent->CurrentValue);
            } else {
                $this->SexeAgent->ViewValue = null;
            }

            // EtatCivilAgent
            if (strval($this->EtatCivilAgent->CurrentValue) != "") {
                $this->EtatCivilAgent->ViewValue = $this->EtatCivilAgent->optionCaption($this->EtatCivilAgent->CurrentValue);
            } else {
                $this->EtatCivilAgent->ViewValue = null;
            }

            // TelephoneAgent
            $this->TelephoneAgent->ViewValue = $this->TelephoneAgent->CurrentValue;

            // Motsecret
            $this->Motsecret->ViewValue = $Language->phrase("PasswordMask");

            // IdAgent
            $this->IdAgent->HrefValue = "";

            // MatricAgent
            $this->MatricAgent->HrefValue = "";

            // NomAgent
            $this->NomAgent->HrefValue = "";

            // PostnomAgent
            $this->PostnomAgent->HrefValue = "";

            // PrenomAgent
            $this->PrenomAgent->HrefValue = "";

            // SexeAgent
            $this->SexeAgent->HrefValue = "";

            // EtatCivilAgent
            $this->EtatCivilAgent->HrefValue = "";

            // TelephoneAgent
            $this->TelephoneAgent->HrefValue = "";

            // Motsecret
            $this->Motsecret->HrefValue = "";
        } elseif ($this->RowType == ROWTYPE_EDIT) {
            // IdAgent
            $this->IdAgent->setupEditAttributes();
            $this->IdAgent->EditValue = $this->IdAgent->CurrentValue;

            // MatricAgent
            $this->MatricAgent->setupEditAttributes();
            if (!$this->MatricAgent->Raw) {
                $this->MatricAgent->CurrentValue = HtmlDecode($this->MatricAgent->CurrentValue);
            }
            $this->MatricAgent->EditValue = HtmlEncode($this->MatricAgent->CurrentValue);
            $curVal = strval($this->MatricAgent->CurrentValue);
            if ($curVal != "") {
                $this->MatricAgent->EditValue = $this->MatricAgent->lookupCacheOption($curVal);
                if ($this->MatricAgent->EditValue === null) { // Lookup from database
                    $filterWrk = SearchFilter("`IdAgent`", "=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->MatricAgent->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->MatricAgent->Lookup->renderViewRow($rswrk[0]);
                        $this->MatricAgent->EditValue = $this->MatricAgent->displayValue($arwrk);
                    } else {
                        $this->MatricAgent->EditValue = HtmlEncode($this->MatricAgent->CurrentValue);
                    }
                }
            } else {
                $this->MatricAgent->EditValue = null;
            }
            $this->MatricAgent->PlaceHolder = RemoveHtml($this->MatricAgent->caption());

            // NomAgent
            $this->NomAgent->setupEditAttributes();
            if (!$this->NomAgent->Raw) {
                $this->NomAgent->CurrentValue = HtmlDecode($this->NomAgent->CurrentValue);
            }
            $this->NomAgent->EditValue = HtmlEncode($this->NomAgent->CurrentValue);
            $this->NomAgent->PlaceHolder = RemoveHtml($this->NomAgent->caption());

            // PostnomAgent
            $this->PostnomAgent->setupEditAttributes();
            if (!$this->PostnomAgent->Raw) {
                $this->PostnomAgent->CurrentValue = HtmlDecode($this->PostnomAgent->CurrentValue);
            }
            $this->PostnomAgent->EditValue = HtmlEncode($this->PostnomAgent->CurrentValue);
            $this->PostnomAgent->PlaceHolder = RemoveHtml($this->PostnomAgent->caption());

            // PrenomAgent
            $this->PrenomAgent->setupEditAttributes();
            if (!$this->PrenomAgent->Raw) {
                $this->PrenomAgent->CurrentValue = HtmlDecode($this->PrenomAgent->CurrentValue);
            }
            $this->PrenomAgent->EditValue = HtmlEncode($this->PrenomAgent->CurrentValue);
            $this->PrenomAgent->PlaceHolder = RemoveHtml($this->PrenomAgent->caption());

            // SexeAgent
            $this->SexeAgent->EditValue = $this->SexeAgent->options(false);
            $this->SexeAgent->PlaceHolder = RemoveHtml($this->SexeAgent->caption());

            // EtatCivilAgent
            $this->EtatCivilAgent->setupEditAttributes();
            $this->EtatCivilAgent->EditValue = $this->EtatCivilAgent->options(true);
            $this->EtatCivilAgent->PlaceHolder = RemoveHtml($this->EtatCivilAgent->caption());

            // TelephoneAgent
            $this->TelephoneAgent->setupEditAttributes();
            if (!$this->TelephoneAgent->Raw) {
                $this->TelephoneAgent->CurrentValue = HtmlDecode($this->TelephoneAgent->CurrentValue);
            }
            $this->TelephoneAgent->EditValue = HtmlEncode($this->TelephoneAgent->CurrentValue);
            $this->TelephoneAgent->PlaceHolder = RemoveHtml($this->TelephoneAgent->caption());

            // Motsecret
            $this->Motsecret->setupEditAttributes();
            $this->Motsecret->EditValue = $Language->phrase("PasswordMask"); // Show as masked password
            $this->Motsecret->PlaceHolder = RemoveHtml($this->Motsecret->caption());
            if (strval($this->Motsecret->EditValue) != "" && is_numeric($this->Motsecret->EditValue)) {
                $this->Motsecret->EditValue = $this->Motsecret->EditValue;
            }

            // Edit refer script

            // IdAgent
            $this->IdAgent->HrefValue = "";

            // MatricAgent
            $this->MatricAgent->HrefValue = "";

            // NomAgent
            $this->NomAgent->HrefValue = "";

            // PostnomAgent
            $this->PostnomAgent->HrefValue = "";

            // PrenomAgent
            $this->PrenomAgent->HrefValue = "";

            // SexeAgent
            $this->SexeAgent->HrefValue = "";

            // EtatCivilAgent
            $this->EtatCivilAgent->HrefValue = "";

            // TelephoneAgent
            $this->TelephoneAgent->HrefValue = "";

            // Motsecret
            $this->Motsecret->HrefValue = "";
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
        if ($this->IdAgent->Required) {
            if (!$this->IdAgent->IsDetailKey && EmptyValue($this->IdAgent->FormValue)) {
                $this->IdAgent->addErrorMessage(str_replace("%s", $this->IdAgent->caption(), $this->IdAgent->RequiredErrorMessage));
            }
        }
        if ($this->MatricAgent->Required) {
            if (!$this->MatricAgent->IsDetailKey && EmptyValue($this->MatricAgent->FormValue)) {
                $this->MatricAgent->addErrorMessage(str_replace("%s", $this->MatricAgent->caption(), $this->MatricAgent->RequiredErrorMessage));
            }
        }
        if ($this->NomAgent->Required) {
            if (!$this->NomAgent->IsDetailKey && EmptyValue($this->NomAgent->FormValue)) {
                $this->NomAgent->addErrorMessage(str_replace("%s", $this->NomAgent->caption(), $this->NomAgent->RequiredErrorMessage));
            }
        }
        if ($this->PostnomAgent->Required) {
            if (!$this->PostnomAgent->IsDetailKey && EmptyValue($this->PostnomAgent->FormValue)) {
                $this->PostnomAgent->addErrorMessage(str_replace("%s", $this->PostnomAgent->caption(), $this->PostnomAgent->RequiredErrorMessage));
            }
        }
        if ($this->PrenomAgent->Required) {
            if (!$this->PrenomAgent->IsDetailKey && EmptyValue($this->PrenomAgent->FormValue)) {
                $this->PrenomAgent->addErrorMessage(str_replace("%s", $this->PrenomAgent->caption(), $this->PrenomAgent->RequiredErrorMessage));
            }
        }
        if ($this->SexeAgent->Required) {
            if ($this->SexeAgent->FormValue == "") {
                $this->SexeAgent->addErrorMessage(str_replace("%s", $this->SexeAgent->caption(), $this->SexeAgent->RequiredErrorMessage));
            }
        }
        if ($this->EtatCivilAgent->Required) {
            if (!$this->EtatCivilAgent->IsDetailKey && EmptyValue($this->EtatCivilAgent->FormValue)) {
                $this->EtatCivilAgent->addErrorMessage(str_replace("%s", $this->EtatCivilAgent->caption(), $this->EtatCivilAgent->RequiredErrorMessage));
            }
        }
        if ($this->TelephoneAgent->Required) {
            if (!$this->TelephoneAgent->IsDetailKey && EmptyValue($this->TelephoneAgent->FormValue)) {
                $this->TelephoneAgent->addErrorMessage(str_replace("%s", $this->TelephoneAgent->caption(), $this->TelephoneAgent->RequiredErrorMessage));
            }
        }
        if ($this->Motsecret->Required) {
            if (!$this->Motsecret->IsDetailKey && EmptyValue($this->Motsecret->FormValue)) {
                $this->Motsecret->addErrorMessage(str_replace("%s", $this->Motsecret->caption(), $this->Motsecret->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->Motsecret->FormValue)) {
            $this->Motsecret->addErrorMessage($this->Motsecret->getErrorMessage(false));
        }
        if (!$this->Motsecret->Raw && Config("REMOVE_XSS") && CheckPassword($this->Motsecret->FormValue)) {
            $this->Motsecret->addErrorMessage($Language->phrase("InvalidPasswordChars"));
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

    // Update record based on key values
    protected function editRow()
    {
        global $Security, $Language;
        $oldKeyFilter = $this->getRecordFilter();
        $filter = $this->applyUserIDFilters($oldKeyFilter);
        $conn = $this->getConnection();

        // Load old row
        $this->CurrentFilter = $filter;
        $sql = $this->getCurrentSql();
        $rsold = $conn->fetchAssociative($sql);
        if (!$rsold) {
            $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record message
            return false; // Update Failed
        } else {
            // Save old values
            $this->loadDbValues($rsold);
        }

        // Set new row
        $rsnew = [];

        // MatricAgent
        $this->MatricAgent->setDbValueDef($rsnew, $this->MatricAgent->CurrentValue, $this->MatricAgent->ReadOnly);

        // NomAgent
        $this->NomAgent->setDbValueDef($rsnew, $this->NomAgent->CurrentValue, $this->NomAgent->ReadOnly);

        // PostnomAgent
        $this->PostnomAgent->setDbValueDef($rsnew, $this->PostnomAgent->CurrentValue, $this->PostnomAgent->ReadOnly);

        // PrenomAgent
        $this->PrenomAgent->setDbValueDef($rsnew, $this->PrenomAgent->CurrentValue, $this->PrenomAgent->ReadOnly);

        // SexeAgent
        $this->SexeAgent->setDbValueDef($rsnew, $this->SexeAgent->CurrentValue, $this->SexeAgent->ReadOnly);

        // EtatCivilAgent
        $this->EtatCivilAgent->setDbValueDef($rsnew, $this->EtatCivilAgent->CurrentValue, $this->EtatCivilAgent->ReadOnly);

        // TelephoneAgent
        $this->TelephoneAgent->setDbValueDef($rsnew, $this->TelephoneAgent->CurrentValue, $this->TelephoneAgent->ReadOnly);

        // Motsecret
        if (!IsMaskedPassword($this->Motsecret->CurrentValue)) {
            $this->Motsecret->setDbValueDef($rsnew, $this->Motsecret->CurrentValue, $this->Motsecret->ReadOnly);
        }

        // Update current values
        $this->setCurrentValues($rsnew);

        // Call Row Updating event
        $updateRow = $this->rowUpdating($rsold, $rsnew);
        if ($updateRow) {
            if (count($rsnew) > 0) {
                $this->CurrentFilter = $filter; // Set up current filter
                $editRow = $this->update($rsnew, "", $rsold);
                if (!$editRow && !EmptyValue($this->DbErrorMessage)) { // Show database error
                    $this->setFailureMessage($this->DbErrorMessage);
                }
            } else {
                $editRow = true; // No field to update
            }
            if ($editRow) {
            }
        } else {
            if ($this->getSuccessMessage() != "" || $this->getFailureMessage() != "") {
                // Use the message, do nothing
            } elseif ($this->CancelMessage != "") {
                $this->setFailureMessage($this->CancelMessage);
                $this->CancelMessage = "";
            } else {
                $this->setFailureMessage($Language->phrase("UpdateCancelled"));
            }
            $editRow = false;
        }

        // Call Row_Updated event
        if ($editRow) {
            $this->rowUpdated($rsold, $rsnew);
        }

        // Write JSON response
        if (IsJsonResponse() && $editRow) {
            $row = $this->getRecordsFromRecordset([$rsnew], true);
            $table = $this->TableVar;
            WriteJson(["success" => true, "action" => Config("API_EDIT_ACTION"), $table => $row]);
        }
        return $editRow;
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("index");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("AgentList"), "", $this->TableVar, true);
        $pageId = "edit";
        $Breadcrumb->add("edit", $pageId, $url);
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
                case "x_IdAgent":
                    break;
                case "x_MatricAgent":
                    break;
                case "x_SexeAgent":
                    break;
                case "x_EtatCivilAgent":
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

    // Set up starting record parameters
    public function setupStartRecord()
    {
        if ($this->DisplayRecords == 0) {
            return;
        }
        $pageNo = Get(Config("TABLE_PAGE_NUMBER"));
        $startRec = Get(Config("TABLE_START_REC"));
        $infiniteScroll = false;
        $recordNo = $pageNo ?? $startRec; // Record number = page number or start record
        if ($recordNo !== null && is_numeric($recordNo)) {
            $this->StartRecord = $recordNo;
        } else {
            $this->StartRecord = $this->getStartRecordNumber();
        }

        // Check if correct start record counter
        if (!is_numeric($this->StartRecord) || intval($this->StartRecord) <= 0) { // Avoid invalid start record counter
            $this->StartRecord = 1; // Reset start record counter
        } elseif ($this->StartRecord > $this->TotalRecords) { // Avoid starting record > total records
            $this->StartRecord = (int)(($this->TotalRecords - 1) / $this->DisplayRecords) * $this->DisplayRecords + 1; // Point to last page first record
        } elseif (($this->StartRecord - 1) % $this->DisplayRecords != 0) {
            $this->StartRecord = (int)(($this->StartRecord - 1) / $this->DisplayRecords) * $this->DisplayRecords + 1; // Point to page boundary
        }
        if (!$infiniteScroll) {
            $this->setStartRecordNumber($this->StartRecord);
        }
    }

    // Get page count
    public function pageCount() {
        return ceil($this->TotalRecords / $this->DisplayRecords);
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
