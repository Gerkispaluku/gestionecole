<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "eleveinfo.php" ?>
<?php include_once "agentinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$eleve_list = NULL; // Initialize page object first

class celeve_list extends celeve {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{84EC96E8-6D54-4BAB-B15E-1EA379B0779B}";

	// Table name
	var $TableName = 'eleve';

	// Page object name
	var $PageObjName = 'eleve_list';

	// Grid form hidden field names
	var $FormName = 'felevelist';
	var $FormActionName = 'k_action';
	var $FormKeyName = 'k_key';
	var $FormOldKeyName = 'k_oldkey';
	var $FormBlankRowName = 'k_blankrow';
	var $FormKeyCountName = 'key_count';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Custom export
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Methods to clear message
	function ClearMessage() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
	}

	function ClearFailureMessage() {
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
	}

	function ClearSuccessMessage() {
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
	}

	function ClearWarningMessage() {
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	function ClearMessages() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $TokenTimeout = 0;
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME], $this->TokenTimeout);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		global $UserTable, $UserTableConn;
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (eleve)
		if (!isset($GLOBALS["eleve"]) || get_class($GLOBALS["eleve"]) == "celeve") {
			$GLOBALS["eleve"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["eleve"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "eleveadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "elevedelete.php";
		$this->MultiUpdateUrl = "eleveupdate.php";

		// Table object (agent)
		if (!isset($GLOBALS['agent'])) $GLOBALS['agent'] = new cagent();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'eleve', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// User table object (agent)
		if (!isset($UserTable)) {
			$UserTable = new cagent();
			$UserTableConn = Conn($UserTable->DBID);
		}

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "div";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";

		// Filter options
		$this->FilterOptions = new cListOptions();
		$this->FilterOptions->Tag = "div";
		$this->FilterOptions->TagClassName = "ewFilterOption felevelistsrch";

		// List actions
		$this->ListActions = new cListActions();
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loaded();
		if (!$Security->CanList()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			$this->Page_Terminate(ew_GetUrl("index.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();
		$this->IdEleve->SetVisibility();
		$this->IdEleve->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->MatricEleve->SetVisibility();
		$this->NomEleve->SetVisibility();
		$this->PostnomElve->SetVisibility();
		$this->PrenomEleve->SetVisibility();
		$this->SexeEleve->SetVisibility();
		$this->DatenaissanceEleve->SetVisibility();
		$this->LieunaissanceEleve->SetVisibility();
		$this->NomdupereElve->SetVisibility();
		$this->NomdelamereEleve->SetVisibility();
		$this->Image_eleve->SetVisibility();
		$this->Description_eleve->SetVisibility();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
		}

		// Create Token
		$this->CreateToken();

		// Setup other options
		$this->SetupOtherOptions();

		// Set up custom action (compatible with old version)
		foreach ($this->CustomActions as $name => $action)
			$this->ListActions->Add($name, $action);

		// Show checkbox column if multiple action
		foreach ($this->ListActions->Items as $listaction) {
			if ($listaction->Select == EW_ACTION_MULTIPLE && $listaction->Allow) {
				$this->ListOptions->Items["checkbox"]->Visible = TRUE;
				break;
			}
		}
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT, $eleve;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($eleve);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
		$this->Page_Redirecting($url);

		 // Close connection
		ew_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $SearchOptions; // Search options
	var $OtherOptions = array(); // Other options
	var $FilterOptions; // Filter options
	var $ListActions; // List actions
	var $SelectedCount = 0;
	var $SelectedIndex = 0;
	var $DisplayRecs = 10;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $DefaultSearchWhere = ""; // Default search WHERE clause
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $StartRowCnt = 1;
	var $RowCnt = 0;
	var $Attrs = array(); // Row attributes and cell attributes
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $MultiColumnClass;
	var $MultiColumnEditClass = "col-sm-12";
	var $MultiColumnCnt = 12;
	var $MultiColumnEditCnt = 12;
	var $GridCnt = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;	
	var $MultiSelectKey;
	var $Command;
	var $RestoreSearch = FALSE;
	var $DetailPages;
	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";

		// Get command
		$this->Command = strtolower(@$_GET["cmd"]);
		if ($this->IsPageRequest()) { // Validate request

			// Process list action first
			if ($this->ProcessListAction()) // Ajax request
				$this->Page_Terminate();

			// Handle reset command
			$this->ResetCmd();

			// Set up Breadcrumb
			if ($this->Export == "")
				$this->SetupBreadcrumb();

			// Hide list options
			if ($this->Export <> "") {
				$this->ListOptions->HideAllOptions(array("sequence"));
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			} elseif ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			}

			// Hide options
			if ($this->Export <> "" || $this->CurrentAction <> "") {
				$this->ExportOptions->HideAllOptions();
				$this->FilterOptions->HideAllOptions();
			}

			// Hide other options
			if ($this->Export <> "") {
				foreach ($this->OtherOptions as &$option)
					$option->HideAllOptions();
			}

			// Get default search criteria
			ew_AddFilter($this->DefaultSearchWhere, $this->BasicSearchWhere(TRUE));

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Process filter list
			$this->ProcessFilterList();

			// Restore search parms from Session if not searching / reset / export
			if (($this->Export <> "" || $this->Command <> "search" && $this->Command <> "reset" && $this->Command <> "resetall") && $this->CheckSearchParms())
				$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$this->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetUpSortOrder();

			// Get basic search criteria
			if ($gsSearchError == "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 10; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Load search default if no existing search criteria
		if (!$this->CheckSearchParms()) {

			// Load basic search from default
			$this->BasicSearch->LoadDefault();
			if ($this->BasicSearch->Keyword != "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Build search criteria
		ew_AddFilter($this->SearchWhere, $sSrchAdvanced);
		ew_AddFilter($this->SearchWhere, $sSrchBasic);

		// Call Recordset_Searching event
		$this->Recordset_Searching($this->SearchWhere);

		// Save search criteria
		if ($this->Command == "search" && !$this->RestoreSearch) {
			$this->setSearchWhere($this->SearchWhere); // Save to Session
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} else {
			$this->SearchWhere = $this->getSearchWhere();
		}

		// Build filter
		$sFilter = "";
		if (!$Security->CanList())
			$sFilter = "(0=1)"; // Filter all records
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Set up filter in session
		$this->setSessionWhere($sFilter);
		$this->CurrentFilter = "";

		// Load record count first
		if (!$this->IsAddOrEdit()) {
			$bSelectLimit = $this->UseSelectLimit;
			if ($bSelectLimit) {
				$this->TotalRecs = $this->SelectRecordCount();
			} else {
				if ($this->Recordset = $this->LoadRecordset())
					$this->TotalRecs = $this->Recordset->RecordCount();
			}
		}

		// Search options
		$this->SetupSearchOptions();
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $this->KeyFilter();
				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // Next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 1) {
			$this->IdEleve->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->IdEleve->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {
		global $UserProfile;

		// Load server side filters
		if (EW_SEARCH_FILTER_OPTION == "Server") {
			$sSavedFilterList = $UserProfile->GetSearchFilters(CurrentUserName(), "felevelistsrch");
		} else {
			$sSavedFilterList = "";
		}

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->IdEleve->AdvancedSearch->ToJSON(), ","); // Field IdEleve
		$sFilterList = ew_Concat($sFilterList, $this->MatricEleve->AdvancedSearch->ToJSON(), ","); // Field MatricEleve
		$sFilterList = ew_Concat($sFilterList, $this->NomEleve->AdvancedSearch->ToJSON(), ","); // Field NomEleve
		$sFilterList = ew_Concat($sFilterList, $this->PostnomElve->AdvancedSearch->ToJSON(), ","); // Field PostnomElve
		$sFilterList = ew_Concat($sFilterList, $this->PrenomEleve->AdvancedSearch->ToJSON(), ","); // Field PrenomEleve
		$sFilterList = ew_Concat($sFilterList, $this->SexeEleve->AdvancedSearch->ToJSON(), ","); // Field SexeEleve
		$sFilterList = ew_Concat($sFilterList, $this->DatenaissanceEleve->AdvancedSearch->ToJSON(), ","); // Field DatenaissanceEleve
		$sFilterList = ew_Concat($sFilterList, $this->LieunaissanceEleve->AdvancedSearch->ToJSON(), ","); // Field LieunaissanceEleve
		$sFilterList = ew_Concat($sFilterList, $this->NomdupereElve->AdvancedSearch->ToJSON(), ","); // Field NomdupereElve
		$sFilterList = ew_Concat($sFilterList, $this->NomdelamereEleve->AdvancedSearch->ToJSON(), ","); // Field NomdelamereEleve
		$sFilterList = ew_Concat($sFilterList, $this->Image_eleve->AdvancedSearch->ToJSON(), ","); // Field Image_eleve
		$sFilterList = ew_Concat($sFilterList, $this->Description_eleve->AdvancedSearch->ToJSON(), ","); // Field Description_eleve
		if ($this->BasicSearch->Keyword <> "") {
			$sWrk = "\"" . EW_TABLE_BASIC_SEARCH . "\":\"" . ew_JsEncode2($this->BasicSearch->Keyword) . "\",\"" . EW_TABLE_BASIC_SEARCH_TYPE . "\":\"" . ew_JsEncode2($this->BasicSearch->Type) . "\"";
			$sFilterList = ew_Concat($sFilterList, $sWrk, ",");
		}
		$sFilterList = preg_replace('/,$/', "", $sFilterList);

		// Return filter list in json
		if ($sFilterList <> "")
			$sFilterList = "\"data\":{" . $sFilterList . "}";
		if ($sSavedFilterList <> "") {
			if ($sFilterList <> "")
				$sFilterList .= ",";
			$sFilterList .= "\"filters\":" . $sSavedFilterList;
		}
		return ($sFilterList <> "") ? "{" . $sFilterList . "}" : "null";
	}

	// Process filter list
	function ProcessFilterList() {
		global $UserProfile;
		if (@$_POST["ajax"] == "savefilters") { // Save filter request (Ajax)
			$filters = ew_StripSlashes(@$_POST["filters"]);
			$UserProfile->SetSearchFilters(CurrentUserName(), "felevelistsrch", $filters);

			// Clean output buffer
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			echo ew_ArrayToJson(array(array("success" => TRUE))); // Success
			$this->Page_Terminate();
			exit();
		} elseif (@$_POST["cmd"] == "resetfilter") {
			$this->RestoreFilterList();
		}
	}

	// Restore list of filters
	function RestoreFilterList() {

		// Return if not reset filter
		if (@$_POST["cmd"] <> "resetfilter")
			return FALSE;
		$filter = json_decode(ew_StripSlashes(@$_POST["filter"]), TRUE);
		$this->Command = "search";

		// Field IdEleve
		$this->IdEleve->AdvancedSearch->SearchValue = @$filter["x_IdEleve"];
		$this->IdEleve->AdvancedSearch->SearchOperator = @$filter["z_IdEleve"];
		$this->IdEleve->AdvancedSearch->SearchCondition = @$filter["v_IdEleve"];
		$this->IdEleve->AdvancedSearch->SearchValue2 = @$filter["y_IdEleve"];
		$this->IdEleve->AdvancedSearch->SearchOperator2 = @$filter["w_IdEleve"];
		$this->IdEleve->AdvancedSearch->Save();

		// Field MatricEleve
		$this->MatricEleve->AdvancedSearch->SearchValue = @$filter["x_MatricEleve"];
		$this->MatricEleve->AdvancedSearch->SearchOperator = @$filter["z_MatricEleve"];
		$this->MatricEleve->AdvancedSearch->SearchCondition = @$filter["v_MatricEleve"];
		$this->MatricEleve->AdvancedSearch->SearchValue2 = @$filter["y_MatricEleve"];
		$this->MatricEleve->AdvancedSearch->SearchOperator2 = @$filter["w_MatricEleve"];
		$this->MatricEleve->AdvancedSearch->Save();

		// Field NomEleve
		$this->NomEleve->AdvancedSearch->SearchValue = @$filter["x_NomEleve"];
		$this->NomEleve->AdvancedSearch->SearchOperator = @$filter["z_NomEleve"];
		$this->NomEleve->AdvancedSearch->SearchCondition = @$filter["v_NomEleve"];
		$this->NomEleve->AdvancedSearch->SearchValue2 = @$filter["y_NomEleve"];
		$this->NomEleve->AdvancedSearch->SearchOperator2 = @$filter["w_NomEleve"];
		$this->NomEleve->AdvancedSearch->Save();

		// Field PostnomElve
		$this->PostnomElve->AdvancedSearch->SearchValue = @$filter["x_PostnomElve"];
		$this->PostnomElve->AdvancedSearch->SearchOperator = @$filter["z_PostnomElve"];
		$this->PostnomElve->AdvancedSearch->SearchCondition = @$filter["v_PostnomElve"];
		$this->PostnomElve->AdvancedSearch->SearchValue2 = @$filter["y_PostnomElve"];
		$this->PostnomElve->AdvancedSearch->SearchOperator2 = @$filter["w_PostnomElve"];
		$this->PostnomElve->AdvancedSearch->Save();

		// Field PrenomEleve
		$this->PrenomEleve->AdvancedSearch->SearchValue = @$filter["x_PrenomEleve"];
		$this->PrenomEleve->AdvancedSearch->SearchOperator = @$filter["z_PrenomEleve"];
		$this->PrenomEleve->AdvancedSearch->SearchCondition = @$filter["v_PrenomEleve"];
		$this->PrenomEleve->AdvancedSearch->SearchValue2 = @$filter["y_PrenomEleve"];
		$this->PrenomEleve->AdvancedSearch->SearchOperator2 = @$filter["w_PrenomEleve"];
		$this->PrenomEleve->AdvancedSearch->Save();

		// Field SexeEleve
		$this->SexeEleve->AdvancedSearch->SearchValue = @$filter["x_SexeEleve"];
		$this->SexeEleve->AdvancedSearch->SearchOperator = @$filter["z_SexeEleve"];
		$this->SexeEleve->AdvancedSearch->SearchCondition = @$filter["v_SexeEleve"];
		$this->SexeEleve->AdvancedSearch->SearchValue2 = @$filter["y_SexeEleve"];
		$this->SexeEleve->AdvancedSearch->SearchOperator2 = @$filter["w_SexeEleve"];
		$this->SexeEleve->AdvancedSearch->Save();

		// Field DatenaissanceEleve
		$this->DatenaissanceEleve->AdvancedSearch->SearchValue = @$filter["x_DatenaissanceEleve"];
		$this->DatenaissanceEleve->AdvancedSearch->SearchOperator = @$filter["z_DatenaissanceEleve"];
		$this->DatenaissanceEleve->AdvancedSearch->SearchCondition = @$filter["v_DatenaissanceEleve"];
		$this->DatenaissanceEleve->AdvancedSearch->SearchValue2 = @$filter["y_DatenaissanceEleve"];
		$this->DatenaissanceEleve->AdvancedSearch->SearchOperator2 = @$filter["w_DatenaissanceEleve"];
		$this->DatenaissanceEleve->AdvancedSearch->Save();

		// Field LieunaissanceEleve
		$this->LieunaissanceEleve->AdvancedSearch->SearchValue = @$filter["x_LieunaissanceEleve"];
		$this->LieunaissanceEleve->AdvancedSearch->SearchOperator = @$filter["z_LieunaissanceEleve"];
		$this->LieunaissanceEleve->AdvancedSearch->SearchCondition = @$filter["v_LieunaissanceEleve"];
		$this->LieunaissanceEleve->AdvancedSearch->SearchValue2 = @$filter["y_LieunaissanceEleve"];
		$this->LieunaissanceEleve->AdvancedSearch->SearchOperator2 = @$filter["w_LieunaissanceEleve"];
		$this->LieunaissanceEleve->AdvancedSearch->Save();

		// Field NomdupereElve
		$this->NomdupereElve->AdvancedSearch->SearchValue = @$filter["x_NomdupereElve"];
		$this->NomdupereElve->AdvancedSearch->SearchOperator = @$filter["z_NomdupereElve"];
		$this->NomdupereElve->AdvancedSearch->SearchCondition = @$filter["v_NomdupereElve"];
		$this->NomdupereElve->AdvancedSearch->SearchValue2 = @$filter["y_NomdupereElve"];
		$this->NomdupereElve->AdvancedSearch->SearchOperator2 = @$filter["w_NomdupereElve"];
		$this->NomdupereElve->AdvancedSearch->Save();

		// Field NomdelamereEleve
		$this->NomdelamereEleve->AdvancedSearch->SearchValue = @$filter["x_NomdelamereEleve"];
		$this->NomdelamereEleve->AdvancedSearch->SearchOperator = @$filter["z_NomdelamereEleve"];
		$this->NomdelamereEleve->AdvancedSearch->SearchCondition = @$filter["v_NomdelamereEleve"];
		$this->NomdelamereEleve->AdvancedSearch->SearchValue2 = @$filter["y_NomdelamereEleve"];
		$this->NomdelamereEleve->AdvancedSearch->SearchOperator2 = @$filter["w_NomdelamereEleve"];
		$this->NomdelamereEleve->AdvancedSearch->Save();

		// Field Image_eleve
		$this->Image_eleve->AdvancedSearch->SearchValue = @$filter["x_Image_eleve"];
		$this->Image_eleve->AdvancedSearch->SearchOperator = @$filter["z_Image_eleve"];
		$this->Image_eleve->AdvancedSearch->SearchCondition = @$filter["v_Image_eleve"];
		$this->Image_eleve->AdvancedSearch->SearchValue2 = @$filter["y_Image_eleve"];
		$this->Image_eleve->AdvancedSearch->SearchOperator2 = @$filter["w_Image_eleve"];
		$this->Image_eleve->AdvancedSearch->Save();

		// Field Description_eleve
		$this->Description_eleve->AdvancedSearch->SearchValue = @$filter["x_Description_eleve"];
		$this->Description_eleve->AdvancedSearch->SearchOperator = @$filter["z_Description_eleve"];
		$this->Description_eleve->AdvancedSearch->SearchCondition = @$filter["v_Description_eleve"];
		$this->Description_eleve->AdvancedSearch->SearchValue2 = @$filter["y_Description_eleve"];
		$this->Description_eleve->AdvancedSearch->SearchOperator2 = @$filter["w_Description_eleve"];
		$this->Description_eleve->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->NomEleve, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->PostnomElve, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->PrenomEleve, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->SexeEleve, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->LieunaissanceEleve, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->NomdupereElve, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->NomdelamereEleve, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Image_eleve, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Description_eleve, $arKeywords, $type);
		return $sWhere;
	}

	// Build basic search SQL
	function BuildBasicSearchSQL(&$Where, &$Fld, $arKeywords, $type) {
		global $EW_BASIC_SEARCH_IGNORE_PATTERN;
		$sDefCond = ($type == "OR") ? "OR" : "AND";
		$arSQL = array(); // Array for SQL parts
		$arCond = array(); // Array for search conditions
		$cnt = count($arKeywords);
		$j = 0; // Number of SQL parts
		for ($i = 0; $i < $cnt; $i++) {
			$Keyword = $arKeywords[$i];
			$Keyword = trim($Keyword);
			if ($EW_BASIC_SEARCH_IGNORE_PATTERN <> "") {
				$Keyword = preg_replace($EW_BASIC_SEARCH_IGNORE_PATTERN, "\\", $Keyword);
				$ar = explode("\\", $Keyword);
			} else {
				$ar = array($Keyword);
			}
			foreach ($ar as $Keyword) {
				if ($Keyword <> "") {
					$sWrk = "";
					if ($Keyword == "OR" && $type == "") {
						if ($j > 0)
							$arCond[$j-1] = "OR";
					} elseif ($Keyword == EW_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NULL";
					} elseif ($Keyword == EW_NOT_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NOT NULL";
					} elseif ($Fld->FldIsVirtual) {
						$sWrk = $Fld->FldVirtualExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					} elseif ($Fld->FldDataType != EW_DATATYPE_NUMBER || is_numeric($Keyword)) {
						$sWrk = $Fld->FldBasicSearchExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					}
					if ($sWrk <> "") {
						$arSQL[$j] = $sWrk;
						$arCond[$j] = $sDefCond;
						$j += 1;
					}
				}
			}
		}
		$cnt = count($arSQL);
		$bQuoted = FALSE;
		$sSql = "";
		if ($cnt > 0) {
			for ($i = 0; $i < $cnt-1; $i++) {
				if ($arCond[$i] == "OR") {
					if (!$bQuoted) $sSql .= "(";
					$bQuoted = TRUE;
				}
				$sSql .= $arSQL[$i];
				if ($bQuoted && $arCond[$i] <> "OR") {
					$sSql .= ")";
					$bQuoted = FALSE;
				}
				$sSql .= " " . $arCond[$i] . " ";
			}
			$sSql .= $arSQL[$cnt-1];
			if ($bQuoted)
				$sSql .= ")";
		}
		if ($sSql <> "") {
			if ($Where <> "") $Where .= " OR ";
			$Where .=  "(" . $sSql . ")";
		}
	}

	// Return basic search WHERE clause based on search keyword and type
	function BasicSearchWhere($Default = FALSE) {
		global $Security;
		$sSearchStr = "";
		if (!$Security->CanSearch()) return "";
		$sSearchKeyword = ($Default) ? $this->BasicSearch->KeywordDefault : $this->BasicSearch->Keyword;
		$sSearchType = ($Default) ? $this->BasicSearch->TypeDefault : $this->BasicSearch->Type;
		if ($sSearchKeyword <> "") {
			$sSearch = trim($sSearchKeyword);
			if ($sSearchType <> "=") {
				$ar = array();

				// Match quoted keywords (i.e.: "...")
				if (preg_match_all('/"([^"]*)"/i', $sSearch, $matches, PREG_SET_ORDER)) {
					foreach ($matches as $match) {
						$p = strpos($sSearch, $match[0]);
						$str = substr($sSearch, 0, $p);
						$sSearch = substr($sSearch, $p + strlen($match[0]));
						if (strlen(trim($str)) > 0)
							$ar = array_merge($ar, explode(" ", trim($str)));
						$ar[] = $match[1]; // Save quoted keyword
					}
				}

				// Match individual keywords
				if (strlen(trim($sSearch)) > 0)
					$ar = array_merge($ar, explode(" ", trim($sSearch)));

				// Search keyword in any fields
				if (($sSearchType == "OR" || $sSearchType == "AND") && $this->BasicSearch->BasicSearchAnyFields) {
					foreach ($ar as $sKeyword) {
						if ($sKeyword <> "") {
							if ($sSearchStr <> "") $sSearchStr .= " " . $sSearchType . " ";
							$sSearchStr .= "(" . $this->BasicSearchSQL(array($sKeyword), $sSearchType) . ")";
						}
					}
				} else {
					$sSearchStr = $this->BasicSearchSQL($ar, $sSearchType);
				}
			} else {
				$sSearchStr = $this->BasicSearchSQL(array($sSearch), $sSearchType);
			}
			if (!$Default) $this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->BasicSearch->setKeyword($sSearchKeyword);
			$this->BasicSearch->setType($sSearchType);
		}
		return $sSearchStr;
	}

	// Check if search parm exists
	function CheckSearchParms() {

		// Check basic search
		if ($this->BasicSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->IdEleve); // IdEleve
			$this->UpdateSort($this->MatricEleve); // MatricEleve
			$this->UpdateSort($this->NomEleve); // NomEleve
			$this->UpdateSort($this->PostnomElve); // PostnomElve
			$this->UpdateSort($this->PrenomEleve); // PrenomEleve
			$this->UpdateSort($this->SexeEleve); // SexeEleve
			$this->UpdateSort($this->DatenaissanceEleve); // DatenaissanceEleve
			$this->UpdateSort($this->LieunaissanceEleve); // LieunaissanceEleve
			$this->UpdateSort($this->NomdupereElve); // NomdupereElve
			$this->UpdateSort($this->NomdelamereEleve); // NomdelamereEleve
			$this->UpdateSort($this->Image_eleve); // Image_eleve
			$this->UpdateSort($this->Description_eleve); // Description_eleve
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->getSqlOrderBy() <> "") {
				$sOrderBy = $this->getSqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
			}
		}
	}

	// Reset command
	// - cmd=reset (Reset search parameters)
	// - cmd=resetall (Reset search and master/detail parameters)
	// - cmd=resetsort (Reset sort parameters)
	function ResetCmd() {

		// Check if reset command
		if (substr($this->Command,0,5) == "reset") {

			// Reset search criteria
			if ($this->Command == "reset" || $this->Command == "resetall")
				$this->ResetSearchParms();

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->IdEleve->setSort("");
				$this->MatricEleve->setSort("");
				$this->NomEleve->setSort("");
				$this->PostnomElve->setSort("");
				$this->PrenomEleve->setSort("");
				$this->SexeEleve->setSort("");
				$this->DatenaissanceEleve->setSort("");
				$this->LieunaissanceEleve->setSort("");
				$this->NomdupereElve->setSort("");
				$this->NomdelamereEleve->setSort("");
				$this->Image_eleve->setSort("");
				$this->Description_eleve->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = TRUE;
		$item->Visible = FALSE;

		// "view"
		$item = &$this->ListOptions->Add("view");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanView();
		$item->OnLeft = TRUE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanEdit();
		$item->OnLeft = TRUE;

		// "copy"
		$item = &$this->ListOptions->Add("copy");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanAdd();
		$item->OnLeft = TRUE;

		// List actions
		$item = &$this->ListOptions->Add("listactions");
		$item->CssStyle = "white-space: nowrap;";
		$item->OnLeft = TRUE;
		$item->Visible = FALSE;
		$item->ShowInButtonGroup = FALSE;
		$item->ShowInDropDown = FALSE;

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = ($Security->CanDelete() || $Security->CanEdit());
		$item->OnLeft = TRUE;
		$item->Header = "<input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\">";
		$item->MoveTo(0);
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseImageAndText = TRUE;
		$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = FALSE;
		if ($this->ListOptions->UseButtonGroup && ew_IsMobile())
			$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->ButtonClass = "btn-sm"; // Class for button group

		// Call ListOptions_Load event
		$this->ListOptions_Load();
		$this->SetupListOptionsExt();
		$item = &$this->ListOptions->GetItem($this->ListOptions->GroupOptionName);
		$item->Visible = $this->ListOptions->GroupOptionVisible();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		$viewcaption = ew_HtmlTitle($Language->Phrase("ViewLink"));
		if ($Security->CanView()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . $viewcaption . "\" data-caption=\"" . $viewcaption . "\" href=\"" . ew_HtmlEncode($this->ViewUrl) . "\">" . $Language->Phrase("ViewLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		$editcaption = ew_HtmlTitle($Language->Phrase("EditLink"));
		if ($Security->CanEdit()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("EditLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		$copycaption = ew_HtmlTitle($Language->Phrase("CopyLink"));
		if ($Security->CanAdd()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewCopy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("CopyLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// Set up list action buttons
		$oListOpt = &$this->ListOptions->GetItem("listactions");
		if ($oListOpt && $this->Export == "" && $this->CurrentAction == "") {
			$body = "";
			$links = array();
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_SINGLE && $listaction->Allow) {
					$action = $listaction->Action;
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode(str_replace(" ewIcon", "", $listaction->Icon)) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\"></span> " : "";
					$links[] = "<li><a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . $listaction->Caption . "</a></li>";
					if (count($links) == 1) // Single button
						$body = "<a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $Language->Phrase("ListActionButton") . "</a>";
				}
			}
			if (count($links) > 1) { // More than one buttons, use dropdown
				$body = "<button class=\"dropdown-toggle btn btn-default btn-sm ewActions\" title=\"" . ew_HtmlTitle($Language->Phrase("ListActionButton")) . "\" data-toggle=\"dropdown\">" . $Language->Phrase("ListActionButton") . "<b class=\"caret\"></b></button>";
				$content = "";
				foreach ($links as $link)
					$content .= "<li>" . $link . "</li>";
				$body .= "<ul class=\"dropdown-menu" . ($oListOpt->OnLeft ? "" : " dropdown-menu-right") . "\">". $content . "</ul>";
				$body = "<div class=\"btn-group\">" . $body . "</div>";
			}
			if (count($links) > 0) {
				$oListOpt->Body = $body;
				$oListOpt->Visible = TRUE;
			}
		}

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->IdEleve->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event);'>";
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["addedit"];

		// Add
		$item = &$option->Add("add");
		$addcaption = ew_HtmlTitle($Language->Phrase("AddLink"));
		$item->Body = "<a class=\"ewAddEdit ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("AddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());
		$option = $options["action"];

		// Add multi delete
		$item = &$option->Add("multidelete");
		$item->Body = "<a class=\"ewAction ewMultiDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" href=\"\" onclick=\"ew_SubmitAction(event,{f:document.felevelist,url:'" . $this->MultiDeleteUrl . "',msg:ewLanguage.Phrase('DeleteConfirmMsg')});return false;\">" . $Language->Phrase("DeleteSelectedLink") . "</a>";
		$item->Visible = ($Security->CanDelete());

		// Add multi update
		$item = &$option->Add("multiupdate");
		$item->Body = "<a class=\"ewAction ewMultiUpdate\" title=\"" . ew_HtmlTitle($Language->Phrase("UpdateSelectedLink")) . "\" data-table=\"eleve\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("UpdateSelectedLink")) . "\" href=\"\" onclick=\"ew_SubmitAction(event,{f:document.felevelist,url:'" . $this->MultiUpdateUrl . "'});return false;\">" . $Language->Phrase("UpdateSelectedLink") . "</a>";
		$item->Visible = ($Security->CanEdit());

		// Set up options default
		foreach ($options as &$option) {
			$option->UseImageAndText = TRUE;
			$option->UseDropDownButton = TRUE;
			$option->UseButtonGroup = TRUE;
			$option->ButtonClass = "btn-sm"; // Class for button group
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["addedit"]->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");

		// Filter button
		$item = &$this->FilterOptions->Add("savecurrentfilter");
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"felevelistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"felevelistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
		$item->Visible = TRUE;
		$this->FilterOptions->UseDropDownButton = TRUE;
		$this->FilterOptions->UseButtonGroup = !$this->FilterOptions->UseDropDownButton;
		$this->FilterOptions->DropDownButtonPhrase = $Language->Phrase("Filters");

		// Add group option item
		$item = &$this->FilterOptions->Add($this->FilterOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
			$option = &$options["action"];

			// Set up list action buttons
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_MULTIPLE) {
					$item = &$option->Add("custom_" . $listaction->Action);
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode($listaction->Icon) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\"></span> " : $caption;
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.felevelist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
					$item->Visible = $listaction->Allow;
				}
			}

			// Hide grid edit and other options
			if ($this->TotalRecs <= 0) {
				$option = &$options["addedit"];
				$item = &$option->GetItem("gridedit");
				if ($item) $item->Visible = FALSE;
				$option = &$options["action"];
				$option->HideAllOptions();
			}
	}

	// Process list action
	function ProcessListAction() {
		global $Language, $Security;
		$userlist = "";
		$user = "";
		$sFilter = $this->GetKeyFilter();
		$UserAction = @$_POST["useraction"];
		if ($sFilter <> "" && $UserAction <> "") {

			// Check permission first
			$ActionCaption = $UserAction;
			if (array_key_exists($UserAction, $this->ListActions->Items)) {
				$ActionCaption = $this->ListActions->Items[$UserAction]->Caption;
				if (!$this->ListActions->Items[$UserAction]->Allow) {
					$errmsg = str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionNotAllowed"));
					if (@$_POST["ajax"] == $UserAction) // Ajax
						echo "<p class=\"text-danger\">" . $errmsg . "</p>";
					else
						$this->setFailureMessage($errmsg);
					return FALSE;
				}
			}
			$this->CurrentFilter = $sFilter;
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$rs = $conn->Execute($sSql);
			$conn->raiseErrorFn = '';
			$this->CurrentAction = $UserAction;

			// Call row action event
			if ($rs && !$rs->EOF) {
				$conn->BeginTrans();
				$this->SelectedCount = $rs->RecordCount();
				$this->SelectedIndex = 0;
				while (!$rs->EOF) {
					$this->SelectedIndex++;
					$row = $rs->fields;
					$Processed = $this->Row_CustomAction($UserAction, $row);
					if (!$Processed) break;
					$rs->MoveNext();
				}
				if ($Processed) {
					$conn->CommitTrans(); // Commit the changes
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionCompleted"))); // Set up success message
				} else {
					$conn->RollbackTrans(); // Rollback changes

					// Set up error message
					if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

						// Use the message, do nothing
					} elseif ($this->CancelMessage <> "") {
						$this->setFailureMessage($this->CancelMessage);
						$this->CancelMessage = "";
					} else {
						$this->setFailureMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionFailed")));
					}
				}
			}
			if ($rs)
				$rs->Close();
			$this->CurrentAction = ""; // Clear action
			if (@$_POST["ajax"] == $UserAction) { // Ajax
				if ($this->getSuccessMessage() <> "") {
					echo "<p class=\"text-success\">" . $this->getSuccessMessage() . "</p>";
					$this->ClearSuccessMessage(); // Clear message
				}
				if ($this->getFailureMessage() <> "") {
					echo "<p class=\"text-danger\">" . $this->getFailureMessage() . "</p>";
					$this->ClearFailureMessage(); // Clear message
				}
				return TRUE;
			}
		}
		return FALSE; // Not ajax request
	}

	// Set up search options
	function SetupSearchOptions() {
		global $Language;
		$this->SearchOptions = new cListOptions();
		$this->SearchOptions->Tag = "div";
		$this->SearchOptions->TagClassName = "ewSearchOption";

		// Search button
		$item = &$this->SearchOptions->Add("searchtoggle");
		$SearchToggleClass = ($this->SearchWhere <> "") ? " active" : " active";
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"felevelistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

		// Search highlight button
		$item = &$this->SearchOptions->Add("searchhighlight");
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewHighlight active\" title=\"" . $Language->Phrase("Highlight") . "\" data-caption=\"" . $Language->Phrase("Highlight") . "\" data-toggle=\"button\" data-form=\"felevelistsrch\" data-name=\"" . $this->HighlightName() . "\">" . $Language->Phrase("HighlightBtn") . "</button>";
		$item->Visible = ($this->SearchWhere <> "" && $this->TotalRecs > 0);

		// Button group for search
		$this->SearchOptions->UseDropDownButton = FALSE;
		$this->SearchOptions->UseImageAndText = TRUE;
		$this->SearchOptions->UseButtonGroup = TRUE;
		$this->SearchOptions->DropDownButtonPhrase = $Language->Phrase("ButtonSearch");

		// Add group option item
		$item = &$this->SearchOptions->Add($this->SearchOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide search options
		if ($this->Export <> "" || $this->CurrentAction <> "")
			$this->SearchOptions->HideAllOptions();
		global $Security;
		if (!$Security->CanSearch()) {
			$this->SearchOptions->HideAllOptions();
			$this->FilterOptions->HideAllOptions();
		}
	}

	function SetupListOptionsExt() {
		global $Security, $Language;

		// Hide detail items for dropdown if necessary
		$this->ListOptions->HideDetailItemsForDropDown();
	}

	function RenderListOptionsExt() {
		global $Security, $Language;
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearch->Keyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		if ($this->BasicSearch->Keyword <> "") $this->Command = "search";
		$this->BasicSearch->Type = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {

		// Load List page SQL
		$sSql = $this->SelectSQL();
		$conn = &$this->Connection();

		// Load recordset
		$dbtype = ew_GetConnectionType($this->DBID);
		if ($this->UseSelectLimit) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			if ($dbtype == "MSSQL") {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderBy())));
			} else {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
			}
			$conn->raiseErrorFn = '';
		} else {
			$rs = ew_LoadRecordset($sSql, $conn);
		}

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
	}

	// Load row based on key values
	function LoadRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql, $conn);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->IdEleve->setDbValue($rs->fields('IdEleve'));
		$this->MatricEleve->setDbValue($rs->fields('MatricEleve'));
		$this->NomEleve->setDbValue($rs->fields('NomEleve'));
		$this->PostnomElve->setDbValue($rs->fields('PostnomElve'));
		$this->PrenomEleve->setDbValue($rs->fields('PrenomEleve'));
		$this->SexeEleve->setDbValue($rs->fields('SexeEleve'));
		$this->DatenaissanceEleve->setDbValue($rs->fields('DatenaissanceEleve'));
		$this->LieunaissanceEleve->setDbValue($rs->fields('LieunaissanceEleve'));
		$this->NomdupereElve->setDbValue($rs->fields('NomdupereElve'));
		$this->NomdelamereEleve->setDbValue($rs->fields('NomdelamereEleve'));
		$this->Image_eleve->Upload->DbValue = $rs->fields('Image_eleve');
		$this->Image_eleve->CurrentValue = $this->Image_eleve->Upload->DbValue;
		$this->Description_eleve->setDbValue($rs->fields('Description_eleve'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
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
		$this->Image_eleve->Upload->DbValue = $row['Image_eleve'];
		$this->Description_eleve->DbValue = $row['Description_eleve'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("IdEleve")) <> "")
			$this->IdEleve->CurrentValue = $this->getKey("IdEleve"); // IdEleve
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$this->OldRecordset = ew_LoadRecordset($sSql, $conn);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
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
		// Image_eleve
		// Description_eleve

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// IdEleve
		$this->IdEleve->ViewValue = $this->IdEleve->CurrentValue;
		$this->IdEleve->ViewCustomAttributes = "";

		// MatricEleve
		$this->MatricEleve->ViewValue = $this->MatricEleve->CurrentValue;
		$this->MatricEleve->ViewCustomAttributes = "";

		// NomEleve
		$this->NomEleve->ViewValue = $this->NomEleve->CurrentValue;
		$this->NomEleve->ViewCustomAttributes = "";

		// PostnomElve
		$this->PostnomElve->ViewValue = $this->PostnomElve->CurrentValue;
		$this->PostnomElve->ViewCustomAttributes = "";

		// PrenomEleve
		$this->PrenomEleve->ViewValue = $this->PrenomEleve->CurrentValue;
		$this->PrenomEleve->ViewCustomAttributes = "";

		// SexeEleve
		if (strval($this->SexeEleve->CurrentValue) <> "") {
			$this->SexeEleve->ViewValue = $this->SexeEleve->OptionCaption($this->SexeEleve->CurrentValue);
		} else {
			$this->SexeEleve->ViewValue = NULL;
		}
		$this->SexeEleve->ViewCustomAttributes = "";

		// DatenaissanceEleve
		$this->DatenaissanceEleve->ViewValue = $this->DatenaissanceEleve->CurrentValue;
		$this->DatenaissanceEleve->ViewValue = ew_FormatDateTime($this->DatenaissanceEleve->ViewValue, 0);
		$this->DatenaissanceEleve->ViewCustomAttributes = "";

		// LieunaissanceEleve
		$this->LieunaissanceEleve->ViewValue = $this->LieunaissanceEleve->CurrentValue;
		$this->LieunaissanceEleve->ViewCustomAttributes = "";

		// NomdupereElve
		$this->NomdupereElve->ViewValue = $this->NomdupereElve->CurrentValue;
		$this->NomdupereElve->ViewCustomAttributes = "";

		// NomdelamereEleve
		$this->NomdelamereEleve->ViewValue = $this->NomdelamereEleve->CurrentValue;
		$this->NomdelamereEleve->ViewCustomAttributes = "";

		// Image_eleve
		if (!ew_Empty($this->Image_eleve->Upload->DbValue)) {
			$this->Image_eleve->ImageWidth = 200;
			$this->Image_eleve->ImageHeight = 0;
			$this->Image_eleve->ImageAlt = $this->Image_eleve->FldAlt();
			$this->Image_eleve->ViewValue = $this->Image_eleve->Upload->DbValue;
		} else {
			$this->Image_eleve->ViewValue = "";
		}
		$this->Image_eleve->ViewCustomAttributes = "";

		// Description_eleve
		$this->Description_eleve->ViewValue = $this->Description_eleve->CurrentValue;
		$this->Description_eleve->ViewCustomAttributes = "";

			// IdEleve
			$this->IdEleve->LinkCustomAttributes = "";
			$this->IdEleve->HrefValue = "";
			$this->IdEleve->TooltipValue = "";

			// MatricEleve
			$this->MatricEleve->LinkCustomAttributes = "";
			$this->MatricEleve->HrefValue = "";
			$this->MatricEleve->TooltipValue = "";

			// NomEleve
			$this->NomEleve->LinkCustomAttributes = "";
			$this->NomEleve->HrefValue = "";
			$this->NomEleve->TooltipValue = "";
			if ($this->Export == "")
				$this->NomEleve->ViewValue = ew_Highlight($this->HighlightName(), $this->NomEleve->ViewValue, $this->BasicSearch->getKeyword(), $this->BasicSearch->getType(), "", "");

			// PostnomElve
			$this->PostnomElve->LinkCustomAttributes = "";
			$this->PostnomElve->HrefValue = "";
			$this->PostnomElve->TooltipValue = "";
			if ($this->Export == "")
				$this->PostnomElve->ViewValue = ew_Highlight($this->HighlightName(), $this->PostnomElve->ViewValue, $this->BasicSearch->getKeyword(), $this->BasicSearch->getType(), "", "");

			// PrenomEleve
			$this->PrenomEleve->LinkCustomAttributes = "";
			$this->PrenomEleve->HrefValue = "";
			$this->PrenomEleve->TooltipValue = "";
			if ($this->Export == "")
				$this->PrenomEleve->ViewValue = ew_Highlight($this->HighlightName(), $this->PrenomEleve->ViewValue, $this->BasicSearch->getKeyword(), $this->BasicSearch->getType(), "", "");

			// SexeEleve
			$this->SexeEleve->LinkCustomAttributes = "";
			$this->SexeEleve->HrefValue = "";
			$this->SexeEleve->TooltipValue = "";

			// DatenaissanceEleve
			$this->DatenaissanceEleve->LinkCustomAttributes = "";
			$this->DatenaissanceEleve->HrefValue = "";
			$this->DatenaissanceEleve->TooltipValue = "";

			// LieunaissanceEleve
			$this->LieunaissanceEleve->LinkCustomAttributes = "";
			$this->LieunaissanceEleve->HrefValue = "";
			$this->LieunaissanceEleve->TooltipValue = "";
			if ($this->Export == "")
				$this->LieunaissanceEleve->ViewValue = ew_Highlight($this->HighlightName(), $this->LieunaissanceEleve->ViewValue, $this->BasicSearch->getKeyword(), $this->BasicSearch->getType(), "", "");

			// NomdupereElve
			$this->NomdupereElve->LinkCustomAttributes = "";
			$this->NomdupereElve->HrefValue = "";
			$this->NomdupereElve->TooltipValue = "";
			if ($this->Export == "")
				$this->NomdupereElve->ViewValue = ew_Highlight($this->HighlightName(), $this->NomdupereElve->ViewValue, $this->BasicSearch->getKeyword(), $this->BasicSearch->getType(), "", "");

			// NomdelamereEleve
			$this->NomdelamereEleve->LinkCustomAttributes = "";
			$this->NomdelamereEleve->HrefValue = "";
			$this->NomdelamereEleve->TooltipValue = "";
			if ($this->Export == "")
				$this->NomdelamereEleve->ViewValue = ew_Highlight($this->HighlightName(), $this->NomdelamereEleve->ViewValue, $this->BasicSearch->getKeyword(), $this->BasicSearch->getType(), "", "");

			// Image_eleve
			$this->Image_eleve->LinkCustomAttributes = "";
			if (!ew_Empty($this->Image_eleve->Upload->DbValue)) {
				$this->Image_eleve->HrefValue = ew_GetFileUploadUrl($this->Image_eleve, $this->Image_eleve->Upload->DbValue); // Add prefix/suffix
				$this->Image_eleve->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->Image_eleve->HrefValue = ew_ConvertFullUrl($this->Image_eleve->HrefValue);
			} else {
				$this->Image_eleve->HrefValue = "";
			}
			$this->Image_eleve->HrefValue2 = $this->Image_eleve->UploadPath . $this->Image_eleve->Upload->DbValue;
			$this->Image_eleve->TooltipValue = "";
			if ($this->Image_eleve->UseColorbox) {
				if (ew_Empty($this->Image_eleve->TooltipValue))
					$this->Image_eleve->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
				$this->Image_eleve->LinkAttrs["data-rel"] = "eleve_x" . $this->RowCnt . "_Image_eleve";
				ew_AppendClass($this->Image_eleve->LinkAttrs["class"], "ewLightbox");
			}

			// Description_eleve
			$this->Description_eleve->LinkCustomAttributes = "";
			$this->Description_eleve->HrefValue = "";
			$this->Description_eleve->TooltipValue = "";
			if ($this->Export == "")
				$this->Description_eleve->ViewValue = ew_Highlight($this->HighlightName(), $this->Description_eleve->ViewValue, $this->BasicSearch->getKeyword(), $this->BasicSearch->getType(), "", "");
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", $this->TableVar, $url, "", $this->TableVar, TRUE);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Setup AutoSuggest filters of a field
	function SetupAutoSuggestFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
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
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example: 
		//$this->ListOptions->Items["new"]->Body = "xxx";

	}

	// Row Custom Action event
	function Row_CustomAction($action, $row) {

		// Return FALSE to abort
		return TRUE;
	}

	// Page Exporting event
	// $this->ExportDoc = export document object
	function Page_Exporting() {

		//$this->ExportDoc->Text = "my header"; // Export header
		//return FALSE; // Return FALSE to skip default export and use Row_Export event

		return TRUE; // Return TRUE to use default export and skip Row_Export event
	}

	// Row Export event
	// $this->ExportDoc = export document object
	function Row_Export($rs) {

		//$this->ExportDoc->Text .= "my content"; // Build HTML with field value: $rs["MyField"] or $this->MyField->ViewValue
	}

	// Page Exported event
	// $this->ExportDoc = export document object
	function Page_Exported() {

		//$this->ExportDoc->Text .= "my footer"; // Export footer
		//echo $this->ExportDoc->Text;

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($eleve_list)) $eleve_list = new celeve_list();

// Page init
$eleve_list->Page_Init();

// Page main
$eleve_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$eleve_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = felevelist = new ew_Form("felevelist", "list");
felevelist.FormKeyCountName = '<?php echo $eleve_list->FormKeyCountName ?>';

// Form_CustomValidate event
felevelist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
felevelist.ValidateRequired = true;
<?php } else { ?>
felevelist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
felevelist.Lists["x_SexeEleve"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
felevelist.Lists["x_SexeEleve"].Options = <?php echo json_encode($eleve->SexeEleve->Options()) ?>;

// Form object for search
var CurrentSearchForm = felevelistsrch = new ew_Form("felevelistsrch");
</script>
<style type="text/css">
.ewTablePreviewRow { /* main table preview row color */
	background-color: #FFFFFF; /* preview row color */
}
.ewTablePreviewRow .ewGrid {
	display: table;
}
.ewTablePreviewRow .ewGrid .ewTable {
	width: auto;
}
</style>
<div id="ewPreview" class="hide"><ul class="nav nav-tabs"></ul><div class="tab-content"><div class="tab-pane fade"></div></div></div>
<script type="text/javascript" src="phpjs/ewpreview.min.js"></script>
<script type="text/javascript">
var EW_PREVIEW_PLACEMENT = EW_CSS_FLIP ? "left" : "right";
var EW_PREVIEW_SINGLE_ROW = false;
var EW_PREVIEW_OVERLAY = false;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php if ($eleve_list->TotalRecs > 0 && $eleve_list->ExportOptions->Visible()) { ?>
<?php $eleve_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($eleve_list->SearchOptions->Visible()) { ?>
<?php $eleve_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($eleve_list->FilterOptions->Visible()) { ?>
<?php $eleve_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php
	$bSelectLimit = $eleve_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($eleve_list->TotalRecs <= 0)
			$eleve_list->TotalRecs = $eleve->SelectRecordCount();
	} else {
		if (!$eleve_list->Recordset && ($eleve_list->Recordset = $eleve_list->LoadRecordset()))
			$eleve_list->TotalRecs = $eleve_list->Recordset->RecordCount();
	}
	$eleve_list->StartRec = 1;
	if ($eleve_list->DisplayRecs <= 0 || ($eleve->Export <> "" && $eleve->ExportAll)) // Display all records
		$eleve_list->DisplayRecs = $eleve_list->TotalRecs;
	if (!($eleve->Export <> "" && $eleve->ExportAll))
		$eleve_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$eleve_list->Recordset = $eleve_list->LoadRecordset($eleve_list->StartRec-1, $eleve_list->DisplayRecs);

	// Set no record found message
	if ($eleve->CurrentAction == "" && $eleve_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$eleve_list->setWarningMessage(ew_DeniedMsg());
		if ($eleve_list->SearchWhere == "0=101")
			$eleve_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$eleve_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$eleve_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($eleve->Export == "" && $eleve->CurrentAction == "") { ?>
<form name="felevelistsrch" id="felevelistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($eleve_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="felevelistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="eleve">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($eleve_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($eleve_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $eleve_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($eleve_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($eleve_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($eleve_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($eleve_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
		</ul>
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $eleve_list->ShowPageHeader(); ?>
<?php
$eleve_list->ShowMessage();
?>
<?php if ($eleve_list->TotalRecs > 0 || $eleve->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid eleve">
<div class="panel-heading ewGridUpperPanel">
<?php if ($eleve->CurrentAction <> "gridadd" && $eleve->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($eleve_list->Pager)) $eleve_list->Pager = new cPrevNextPager($eleve_list->StartRec, $eleve_list->DisplayRecs, $eleve_list->TotalRecs) ?>
<?php if ($eleve_list->Pager->RecordCount > 0 && $eleve_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($eleve_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $eleve_list->PageUrl() ?>start=<?php echo $eleve_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($eleve_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $eleve_list->PageUrl() ?>start=<?php echo $eleve_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $eleve_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($eleve_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $eleve_list->PageUrl() ?>start=<?php echo $eleve_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($eleve_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $eleve_list->PageUrl() ?>start=<?php echo $eleve_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $eleve_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $eleve_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $eleve_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $eleve_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($eleve_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<form name="felevelist" id="felevelist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($eleve_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $eleve_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="eleve">
<div id="gmp_eleve" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($eleve_list->TotalRecs > 0 || $eleve->CurrentAction == "gridedit") { ?>
<table id="tbl_elevelist" class="table ewTable">
<?php echo $eleve->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$eleve_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$eleve_list->RenderListOptions();

// Render list options (header, left)
$eleve_list->ListOptions->Render("header", "left");
?>
<?php if ($eleve->IdEleve->Visible) { // IdEleve ?>
	<?php if ($eleve->SortUrl($eleve->IdEleve) == "") { ?>
		<th data-name="IdEleve"><div id="elh_eleve_IdEleve" class="eleve_IdEleve"><div class="ewTableHeaderCaption"><?php echo $eleve->IdEleve->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="IdEleve"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $eleve->SortUrl($eleve->IdEleve) ?>',1);"><div id="elh_eleve_IdEleve" class="eleve_IdEleve">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $eleve->IdEleve->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($eleve->IdEleve->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($eleve->IdEleve->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($eleve->MatricEleve->Visible) { // MatricEleve ?>
	<?php if ($eleve->SortUrl($eleve->MatricEleve) == "") { ?>
		<th data-name="MatricEleve"><div id="elh_eleve_MatricEleve" class="eleve_MatricEleve"><div class="ewTableHeaderCaption"><?php echo $eleve->MatricEleve->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="MatricEleve"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $eleve->SortUrl($eleve->MatricEleve) ?>',1);"><div id="elh_eleve_MatricEleve" class="eleve_MatricEleve">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $eleve->MatricEleve->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($eleve->MatricEleve->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($eleve->MatricEleve->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($eleve->NomEleve->Visible) { // NomEleve ?>
	<?php if ($eleve->SortUrl($eleve->NomEleve) == "") { ?>
		<th data-name="NomEleve"><div id="elh_eleve_NomEleve" class="eleve_NomEleve"><div class="ewTableHeaderCaption"><?php echo $eleve->NomEleve->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="NomEleve"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $eleve->SortUrl($eleve->NomEleve) ?>',1);"><div id="elh_eleve_NomEleve" class="eleve_NomEleve">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $eleve->NomEleve->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($eleve->NomEleve->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($eleve->NomEleve->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($eleve->PostnomElve->Visible) { // PostnomElve ?>
	<?php if ($eleve->SortUrl($eleve->PostnomElve) == "") { ?>
		<th data-name="PostnomElve"><div id="elh_eleve_PostnomElve" class="eleve_PostnomElve"><div class="ewTableHeaderCaption"><?php echo $eleve->PostnomElve->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="PostnomElve"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $eleve->SortUrl($eleve->PostnomElve) ?>',1);"><div id="elh_eleve_PostnomElve" class="eleve_PostnomElve">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $eleve->PostnomElve->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($eleve->PostnomElve->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($eleve->PostnomElve->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($eleve->PrenomEleve->Visible) { // PrenomEleve ?>
	<?php if ($eleve->SortUrl($eleve->PrenomEleve) == "") { ?>
		<th data-name="PrenomEleve"><div id="elh_eleve_PrenomEleve" class="eleve_PrenomEleve"><div class="ewTableHeaderCaption"><?php echo $eleve->PrenomEleve->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="PrenomEleve"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $eleve->SortUrl($eleve->PrenomEleve) ?>',1);"><div id="elh_eleve_PrenomEleve" class="eleve_PrenomEleve">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $eleve->PrenomEleve->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($eleve->PrenomEleve->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($eleve->PrenomEleve->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($eleve->SexeEleve->Visible) { // SexeEleve ?>
	<?php if ($eleve->SortUrl($eleve->SexeEleve) == "") { ?>
		<th data-name="SexeEleve"><div id="elh_eleve_SexeEleve" class="eleve_SexeEleve"><div class="ewTableHeaderCaption"><?php echo $eleve->SexeEleve->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="SexeEleve"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $eleve->SortUrl($eleve->SexeEleve) ?>',1);"><div id="elh_eleve_SexeEleve" class="eleve_SexeEleve">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $eleve->SexeEleve->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($eleve->SexeEleve->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($eleve->SexeEleve->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($eleve->DatenaissanceEleve->Visible) { // DatenaissanceEleve ?>
	<?php if ($eleve->SortUrl($eleve->DatenaissanceEleve) == "") { ?>
		<th data-name="DatenaissanceEleve"><div id="elh_eleve_DatenaissanceEleve" class="eleve_DatenaissanceEleve"><div class="ewTableHeaderCaption"><?php echo $eleve->DatenaissanceEleve->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="DatenaissanceEleve"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $eleve->SortUrl($eleve->DatenaissanceEleve) ?>',1);"><div id="elh_eleve_DatenaissanceEleve" class="eleve_DatenaissanceEleve">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $eleve->DatenaissanceEleve->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($eleve->DatenaissanceEleve->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($eleve->DatenaissanceEleve->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($eleve->LieunaissanceEleve->Visible) { // LieunaissanceEleve ?>
	<?php if ($eleve->SortUrl($eleve->LieunaissanceEleve) == "") { ?>
		<th data-name="LieunaissanceEleve"><div id="elh_eleve_LieunaissanceEleve" class="eleve_LieunaissanceEleve"><div class="ewTableHeaderCaption"><?php echo $eleve->LieunaissanceEleve->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="LieunaissanceEleve"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $eleve->SortUrl($eleve->LieunaissanceEleve) ?>',1);"><div id="elh_eleve_LieunaissanceEleve" class="eleve_LieunaissanceEleve">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $eleve->LieunaissanceEleve->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($eleve->LieunaissanceEleve->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($eleve->LieunaissanceEleve->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($eleve->NomdupereElve->Visible) { // NomdupereElve ?>
	<?php if ($eleve->SortUrl($eleve->NomdupereElve) == "") { ?>
		<th data-name="NomdupereElve"><div id="elh_eleve_NomdupereElve" class="eleve_NomdupereElve"><div class="ewTableHeaderCaption"><?php echo $eleve->NomdupereElve->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="NomdupereElve"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $eleve->SortUrl($eleve->NomdupereElve) ?>',1);"><div id="elh_eleve_NomdupereElve" class="eleve_NomdupereElve">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $eleve->NomdupereElve->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($eleve->NomdupereElve->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($eleve->NomdupereElve->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($eleve->NomdelamereEleve->Visible) { // NomdelamereEleve ?>
	<?php if ($eleve->SortUrl($eleve->NomdelamereEleve) == "") { ?>
		<th data-name="NomdelamereEleve"><div id="elh_eleve_NomdelamereEleve" class="eleve_NomdelamereEleve"><div class="ewTableHeaderCaption"><?php echo $eleve->NomdelamereEleve->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="NomdelamereEleve"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $eleve->SortUrl($eleve->NomdelamereEleve) ?>',1);"><div id="elh_eleve_NomdelamereEleve" class="eleve_NomdelamereEleve">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $eleve->NomdelamereEleve->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($eleve->NomdelamereEleve->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($eleve->NomdelamereEleve->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($eleve->Image_eleve->Visible) { // Image_eleve ?>
	<?php if ($eleve->SortUrl($eleve->Image_eleve) == "") { ?>
		<th data-name="Image_eleve"><div id="elh_eleve_Image_eleve" class="eleve_Image_eleve"><div class="ewTableHeaderCaption"><?php echo $eleve->Image_eleve->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Image_eleve"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $eleve->SortUrl($eleve->Image_eleve) ?>',1);"><div id="elh_eleve_Image_eleve" class="eleve_Image_eleve">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $eleve->Image_eleve->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($eleve->Image_eleve->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($eleve->Image_eleve->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($eleve->Description_eleve->Visible) { // Description_eleve ?>
	<?php if ($eleve->SortUrl($eleve->Description_eleve) == "") { ?>
		<th data-name="Description_eleve"><div id="elh_eleve_Description_eleve" class="eleve_Description_eleve"><div class="ewTableHeaderCaption"><?php echo $eleve->Description_eleve->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Description_eleve"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $eleve->SortUrl($eleve->Description_eleve) ?>',1);"><div id="elh_eleve_Description_eleve" class="eleve_Description_eleve">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $eleve->Description_eleve->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($eleve->Description_eleve->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($eleve->Description_eleve->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$eleve_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($eleve->ExportAll && $eleve->Export <> "") {
	$eleve_list->StopRec = $eleve_list->TotalRecs;
} else {

	// Set the last record to display
	if ($eleve_list->TotalRecs > $eleve_list->StartRec + $eleve_list->DisplayRecs - 1)
		$eleve_list->StopRec = $eleve_list->StartRec + $eleve_list->DisplayRecs - 1;
	else
		$eleve_list->StopRec = $eleve_list->TotalRecs;
}
$eleve_list->RecCnt = $eleve_list->StartRec - 1;
if ($eleve_list->Recordset && !$eleve_list->Recordset->EOF) {
	$eleve_list->Recordset->MoveFirst();
	$bSelectLimit = $eleve_list->UseSelectLimit;
	if (!$bSelectLimit && $eleve_list->StartRec > 1)
		$eleve_list->Recordset->Move($eleve_list->StartRec - 1);
} elseif (!$eleve->AllowAddDeleteRow && $eleve_list->StopRec == 0) {
	$eleve_list->StopRec = $eleve->GridAddRowCount;
}

// Initialize aggregate
$eleve->RowType = EW_ROWTYPE_AGGREGATEINIT;
$eleve->ResetAttrs();
$eleve_list->RenderRow();
while ($eleve_list->RecCnt < $eleve_list->StopRec) {
	$eleve_list->RecCnt++;
	if (intval($eleve_list->RecCnt) >= intval($eleve_list->StartRec)) {
		$eleve_list->RowCnt++;

		// Set up key count
		$eleve_list->KeyCount = $eleve_list->RowIndex;

		// Init row class and style
		$eleve->ResetAttrs();
		$eleve->CssClass = "";
		if ($eleve->CurrentAction == "gridadd") {
		} else {
			$eleve_list->LoadRowValues($eleve_list->Recordset); // Load row values
		}
		$eleve->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$eleve->RowAttrs = array_merge($eleve->RowAttrs, array('data-rowindex'=>$eleve_list->RowCnt, 'id'=>'r' . $eleve_list->RowCnt . '_eleve', 'data-rowtype'=>$eleve->RowType));

		// Render row
		$eleve_list->RenderRow();

		// Render list options
		$eleve_list->RenderListOptions();
?>
	<tr<?php echo $eleve->RowAttributes() ?>>
<?php

// Render list options (body, left)
$eleve_list->ListOptions->Render("body", "left", $eleve_list->RowCnt);
?>
	<?php if ($eleve->IdEleve->Visible) { // IdEleve ?>
		<td data-name="IdEleve"<?php echo $eleve->IdEleve->CellAttributes() ?>>
<span id="el<?php echo $eleve_list->RowCnt ?>_eleve_IdEleve" class="eleve_IdEleve">
<span<?php echo $eleve->IdEleve->ViewAttributes() ?>>
<?php echo $eleve->IdEleve->ListViewValue() ?></span>
</span>
<a id="<?php echo $eleve_list->PageObjName . "_row_" . $eleve_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($eleve->MatricEleve->Visible) { // MatricEleve ?>
		<td data-name="MatricEleve"<?php echo $eleve->MatricEleve->CellAttributes() ?>>
<span id="el<?php echo $eleve_list->RowCnt ?>_eleve_MatricEleve" class="eleve_MatricEleve">
<span<?php echo $eleve->MatricEleve->ViewAttributes() ?>>
<?php echo $eleve->MatricEleve->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($eleve->NomEleve->Visible) { // NomEleve ?>
		<td data-name="NomEleve"<?php echo $eleve->NomEleve->CellAttributes() ?>>
<span id="el<?php echo $eleve_list->RowCnt ?>_eleve_NomEleve" class="eleve_NomEleve">
<span<?php echo $eleve->NomEleve->ViewAttributes() ?>>
<?php echo $eleve->NomEleve->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($eleve->PostnomElve->Visible) { // PostnomElve ?>
		<td data-name="PostnomElve"<?php echo $eleve->PostnomElve->CellAttributes() ?>>
<span id="el<?php echo $eleve_list->RowCnt ?>_eleve_PostnomElve" class="eleve_PostnomElve">
<span<?php echo $eleve->PostnomElve->ViewAttributes() ?>>
<?php echo $eleve->PostnomElve->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($eleve->PrenomEleve->Visible) { // PrenomEleve ?>
		<td data-name="PrenomEleve"<?php echo $eleve->PrenomEleve->CellAttributes() ?>>
<span id="el<?php echo $eleve_list->RowCnt ?>_eleve_PrenomEleve" class="eleve_PrenomEleve">
<span<?php echo $eleve->PrenomEleve->ViewAttributes() ?>>
<?php echo $eleve->PrenomEleve->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($eleve->SexeEleve->Visible) { // SexeEleve ?>
		<td data-name="SexeEleve"<?php echo $eleve->SexeEleve->CellAttributes() ?>>
<span id="el<?php echo $eleve_list->RowCnt ?>_eleve_SexeEleve" class="eleve_SexeEleve">
<span<?php echo $eleve->SexeEleve->ViewAttributes() ?>>
<?php echo $eleve->SexeEleve->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($eleve->DatenaissanceEleve->Visible) { // DatenaissanceEleve ?>
		<td data-name="DatenaissanceEleve"<?php echo $eleve->DatenaissanceEleve->CellAttributes() ?>>
<span id="el<?php echo $eleve_list->RowCnt ?>_eleve_DatenaissanceEleve" class="eleve_DatenaissanceEleve">
<span<?php echo $eleve->DatenaissanceEleve->ViewAttributes() ?>>
<?php echo $eleve->DatenaissanceEleve->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($eleve->LieunaissanceEleve->Visible) { // LieunaissanceEleve ?>
		<td data-name="LieunaissanceEleve"<?php echo $eleve->LieunaissanceEleve->CellAttributes() ?>>
<span id="el<?php echo $eleve_list->RowCnt ?>_eleve_LieunaissanceEleve" class="eleve_LieunaissanceEleve">
<span<?php echo $eleve->LieunaissanceEleve->ViewAttributes() ?>>
<?php echo $eleve->LieunaissanceEleve->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($eleve->NomdupereElve->Visible) { // NomdupereElve ?>
		<td data-name="NomdupereElve"<?php echo $eleve->NomdupereElve->CellAttributes() ?>>
<span id="el<?php echo $eleve_list->RowCnt ?>_eleve_NomdupereElve" class="eleve_NomdupereElve">
<span<?php echo $eleve->NomdupereElve->ViewAttributes() ?>>
<?php echo $eleve->NomdupereElve->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($eleve->NomdelamereEleve->Visible) { // NomdelamereEleve ?>
		<td data-name="NomdelamereEleve"<?php echo $eleve->NomdelamereEleve->CellAttributes() ?>>
<span id="el<?php echo $eleve_list->RowCnt ?>_eleve_NomdelamereEleve" class="eleve_NomdelamereEleve">
<span<?php echo $eleve->NomdelamereEleve->ViewAttributes() ?>>
<?php echo $eleve->NomdelamereEleve->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($eleve->Image_eleve->Visible) { // Image_eleve ?>
		<td data-name="Image_eleve"<?php echo $eleve->Image_eleve->CellAttributes() ?>>
<span id="el<?php echo $eleve_list->RowCnt ?>_eleve_Image_eleve" class="eleve_Image_eleve">
<span>
<?php echo ew_GetFileViewTag($eleve->Image_eleve, $eleve->Image_eleve->ListViewValue()) ?>
</span>
</span>
</td>
	<?php } ?>
	<?php if ($eleve->Description_eleve->Visible) { // Description_eleve ?>
		<td data-name="Description_eleve"<?php echo $eleve->Description_eleve->CellAttributes() ?>>
<span id="el<?php echo $eleve_list->RowCnt ?>_eleve_Description_eleve" class="eleve_Description_eleve">
<span<?php echo $eleve->Description_eleve->ViewAttributes() ?>>
<?php echo $eleve->Description_eleve->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$eleve_list->ListOptions->Render("body", "right", $eleve_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($eleve->CurrentAction <> "gridadd")
		$eleve_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($eleve->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($eleve_list->Recordset)
	$eleve_list->Recordset->Close();
?>
<div class="panel-footer ewGridLowerPanel">
<?php if ($eleve->CurrentAction <> "gridadd" && $eleve->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($eleve_list->Pager)) $eleve_list->Pager = new cPrevNextPager($eleve_list->StartRec, $eleve_list->DisplayRecs, $eleve_list->TotalRecs) ?>
<?php if ($eleve_list->Pager->RecordCount > 0 && $eleve_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($eleve_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $eleve_list->PageUrl() ?>start=<?php echo $eleve_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($eleve_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $eleve_list->PageUrl() ?>start=<?php echo $eleve_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $eleve_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($eleve_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $eleve_list->PageUrl() ?>start=<?php echo $eleve_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($eleve_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $eleve_list->PageUrl() ?>start=<?php echo $eleve_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $eleve_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $eleve_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $eleve_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $eleve_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($eleve_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($eleve_list->TotalRecs == 0 && $eleve->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($eleve_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
felevelistsrch.FilterList = <?php echo $eleve_list->GetFilterList() ?>;
felevelistsrch.Init();
felevelist.Init();
</script>
<?php
$eleve_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$eleve_list->Page_Terminate();
?>
