<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "vueenregistrementagentinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$vueenregistrementagent_list = NULL; // Initialize page object first

class cvueenregistrementagent_list extends cvueenregistrementagent {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{991D980B-FFB7-4965-AC76-A44F1786037D}";

	// Table name
	var $TableName = 'vueenregistrementagent';

	// Page object name
	var $PageObjName = 'vueenregistrementagent_list';

	// Grid form hidden field names
	var $FormName = 'fvueenregistrementagentlist';
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
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (vueenregistrementagent)
		if (!isset($GLOBALS["vueenregistrementagent"]) || get_class($GLOBALS["vueenregistrementagent"]) == "cvueenregistrementagent") {
			$GLOBALS["vueenregistrementagent"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["vueenregistrementagent"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "vueenregistrementagentadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "vueenregistrementagentdelete.php";
		$this->MultiUpdateUrl = "vueenregistrementagentupdate.php";

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'vueenregistrementagent', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fvueenregistrementagentlistsrch";

		// List actions
		$this->ListActions = new cListActions();
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();
		$this->CodeAgent->SetVisibility();
		$this->NomAgent->SetVisibility();
		$this->PostnomAgent->SetVisibility();
		$this->PrenomAgent->SetVisibility();
		$this->SexeAgent->SetVisibility();
		$this->EtatCivilAgent->SetVisibility();
		$this->TelephoneAgent->SetVisibility();
		$this->PhotoAgent->SetVisibility();

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
		global $EW_EXPORT, $vueenregistrementagent;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($vueenregistrementagent);
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
	var $DisplayRecs = 20;
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
			$this->DisplayRecs = 20; // Load default
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
		if (count($arrKeyFlds) >= 0) {
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {
		global $UserProfile;

		// Load server side filters
		if (EW_SEARCH_FILTER_OPTION == "Server") {
			$sSavedFilterList = $UserProfile->GetSearchFilters(CurrentUserName(), "fvueenregistrementagentlistsrch");
		} else {
			$sSavedFilterList = "";
		}

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->CodeAgent->AdvancedSearch->ToJSON(), ","); // Field CodeAgent
		$sFilterList = ew_Concat($sFilterList, $this->NomAgent->AdvancedSearch->ToJSON(), ","); // Field NomAgent
		$sFilterList = ew_Concat($sFilterList, $this->PostnomAgent->AdvancedSearch->ToJSON(), ","); // Field PostnomAgent
		$sFilterList = ew_Concat($sFilterList, $this->PrenomAgent->AdvancedSearch->ToJSON(), ","); // Field PrenomAgent
		$sFilterList = ew_Concat($sFilterList, $this->SexeAgent->AdvancedSearch->ToJSON(), ","); // Field SexeAgent
		$sFilterList = ew_Concat($sFilterList, $this->EtatCivilAgent->AdvancedSearch->ToJSON(), ","); // Field EtatCivilAgent
		$sFilterList = ew_Concat($sFilterList, $this->TelephoneAgent->AdvancedSearch->ToJSON(), ","); // Field TelephoneAgent
		$sFilterList = ew_Concat($sFilterList, $this->PhotoAgent->AdvancedSearch->ToJSON(), ","); // Field PhotoAgent
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "fvueenregistrementagentlistsrch", $filters);

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

		// Field CodeAgent
		$this->CodeAgent->AdvancedSearch->SearchValue = @$filter["x_CodeAgent"];
		$this->CodeAgent->AdvancedSearch->SearchOperator = @$filter["z_CodeAgent"];
		$this->CodeAgent->AdvancedSearch->SearchCondition = @$filter["v_CodeAgent"];
		$this->CodeAgent->AdvancedSearch->SearchValue2 = @$filter["y_CodeAgent"];
		$this->CodeAgent->AdvancedSearch->SearchOperator2 = @$filter["w_CodeAgent"];
		$this->CodeAgent->AdvancedSearch->Save();

		// Field NomAgent
		$this->NomAgent->AdvancedSearch->SearchValue = @$filter["x_NomAgent"];
		$this->NomAgent->AdvancedSearch->SearchOperator = @$filter["z_NomAgent"];
		$this->NomAgent->AdvancedSearch->SearchCondition = @$filter["v_NomAgent"];
		$this->NomAgent->AdvancedSearch->SearchValue2 = @$filter["y_NomAgent"];
		$this->NomAgent->AdvancedSearch->SearchOperator2 = @$filter["w_NomAgent"];
		$this->NomAgent->AdvancedSearch->Save();

		// Field PostnomAgent
		$this->PostnomAgent->AdvancedSearch->SearchValue = @$filter["x_PostnomAgent"];
		$this->PostnomAgent->AdvancedSearch->SearchOperator = @$filter["z_PostnomAgent"];
		$this->PostnomAgent->AdvancedSearch->SearchCondition = @$filter["v_PostnomAgent"];
		$this->PostnomAgent->AdvancedSearch->SearchValue2 = @$filter["y_PostnomAgent"];
		$this->PostnomAgent->AdvancedSearch->SearchOperator2 = @$filter["w_PostnomAgent"];
		$this->PostnomAgent->AdvancedSearch->Save();

		// Field PrenomAgent
		$this->PrenomAgent->AdvancedSearch->SearchValue = @$filter["x_PrenomAgent"];
		$this->PrenomAgent->AdvancedSearch->SearchOperator = @$filter["z_PrenomAgent"];
		$this->PrenomAgent->AdvancedSearch->SearchCondition = @$filter["v_PrenomAgent"];
		$this->PrenomAgent->AdvancedSearch->SearchValue2 = @$filter["y_PrenomAgent"];
		$this->PrenomAgent->AdvancedSearch->SearchOperator2 = @$filter["w_PrenomAgent"];
		$this->PrenomAgent->AdvancedSearch->Save();

		// Field SexeAgent
		$this->SexeAgent->AdvancedSearch->SearchValue = @$filter["x_SexeAgent"];
		$this->SexeAgent->AdvancedSearch->SearchOperator = @$filter["z_SexeAgent"];
		$this->SexeAgent->AdvancedSearch->SearchCondition = @$filter["v_SexeAgent"];
		$this->SexeAgent->AdvancedSearch->SearchValue2 = @$filter["y_SexeAgent"];
		$this->SexeAgent->AdvancedSearch->SearchOperator2 = @$filter["w_SexeAgent"];
		$this->SexeAgent->AdvancedSearch->Save();

		// Field EtatCivilAgent
		$this->EtatCivilAgent->AdvancedSearch->SearchValue = @$filter["x_EtatCivilAgent"];
		$this->EtatCivilAgent->AdvancedSearch->SearchOperator = @$filter["z_EtatCivilAgent"];
		$this->EtatCivilAgent->AdvancedSearch->SearchCondition = @$filter["v_EtatCivilAgent"];
		$this->EtatCivilAgent->AdvancedSearch->SearchValue2 = @$filter["y_EtatCivilAgent"];
		$this->EtatCivilAgent->AdvancedSearch->SearchOperator2 = @$filter["w_EtatCivilAgent"];
		$this->EtatCivilAgent->AdvancedSearch->Save();

		// Field TelephoneAgent
		$this->TelephoneAgent->AdvancedSearch->SearchValue = @$filter["x_TelephoneAgent"];
		$this->TelephoneAgent->AdvancedSearch->SearchOperator = @$filter["z_TelephoneAgent"];
		$this->TelephoneAgent->AdvancedSearch->SearchCondition = @$filter["v_TelephoneAgent"];
		$this->TelephoneAgent->AdvancedSearch->SearchValue2 = @$filter["y_TelephoneAgent"];
		$this->TelephoneAgent->AdvancedSearch->SearchOperator2 = @$filter["w_TelephoneAgent"];
		$this->TelephoneAgent->AdvancedSearch->Save();

		// Field PhotoAgent
		$this->PhotoAgent->AdvancedSearch->SearchValue = @$filter["x_PhotoAgent"];
		$this->PhotoAgent->AdvancedSearch->SearchOperator = @$filter["z_PhotoAgent"];
		$this->PhotoAgent->AdvancedSearch->SearchCondition = @$filter["v_PhotoAgent"];
		$this->PhotoAgent->AdvancedSearch->SearchValue2 = @$filter["y_PhotoAgent"];
		$this->PhotoAgent->AdvancedSearch->SearchOperator2 = @$filter["w_PhotoAgent"];
		$this->PhotoAgent->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->CodeAgent, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->NomAgent, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->PostnomAgent, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->PrenomAgent, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->SexeAgent, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->EtatCivilAgent, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->TelephoneAgent, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->PhotoAgent, $arKeywords, $type);
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
			$this->UpdateSort($this->CodeAgent); // CodeAgent
			$this->UpdateSort($this->NomAgent); // NomAgent
			$this->UpdateSort($this->PostnomAgent); // PostnomAgent
			$this->UpdateSort($this->PrenomAgent); // PrenomAgent
			$this->UpdateSort($this->SexeAgent); // SexeAgent
			$this->UpdateSort($this->EtatCivilAgent); // EtatCivilAgent
			$this->UpdateSort($this->TelephoneAgent); // TelephoneAgent
			$this->UpdateSort($this->PhotoAgent); // PhotoAgent
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
				$this->CodeAgent->setSort("");
				$this->NomAgent->setSort("");
				$this->PostnomAgent->setSort("");
				$this->PrenomAgent->setSort("");
				$this->SexeAgent->setSort("");
				$this->EtatCivilAgent->setSort("");
				$this->TelephoneAgent->setSort("");
				$this->PhotoAgent->setSort("");
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
		$item->OnLeft = FALSE;
		$item->Visible = FALSE;

		// List actions
		$item = &$this->ListOptions->Add("listactions");
		$item->CssStyle = "white-space: nowrap;";
		$item->OnLeft = FALSE;
		$item->Visible = FALSE;
		$item->ShowInButtonGroup = FALSE;
		$item->ShowInDropDown = FALSE;

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = FALSE;
		$item->OnLeft = FALSE;
		$item->Header = "<input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\">";
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseImageAndText = TRUE;
		$this->ListOptions->UseDropDownButton = FALSE;
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
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["action"];

		// Set up options default
		foreach ($options as &$option) {
			$option->UseImageAndText = TRUE;
			$option->UseDropDownButton = FALSE;
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fvueenregistrementagentlistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fvueenregistrementagentlistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fvueenregistrementagentlist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fvueenregistrementagentlistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

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
	}

	function SetupListOptionsExt() {
		global $Security, $Language;
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
		$this->CodeAgent->setDbValue($rs->fields('CodeAgent'));
		$this->NomAgent->setDbValue($rs->fields('NomAgent'));
		$this->PostnomAgent->setDbValue($rs->fields('PostnomAgent'));
		$this->PrenomAgent->setDbValue($rs->fields('PrenomAgent'));
		$this->SexeAgent->setDbValue($rs->fields('SexeAgent'));
		$this->EtatCivilAgent->setDbValue($rs->fields('EtatCivilAgent'));
		$this->TelephoneAgent->setDbValue($rs->fields('TelephoneAgent'));
		$this->PhotoAgent->setDbValue($rs->fields('PhotoAgent'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->CodeAgent->DbValue = $row['CodeAgent'];
		$this->NomAgent->DbValue = $row['NomAgent'];
		$this->PostnomAgent->DbValue = $row['PostnomAgent'];
		$this->PrenomAgent->DbValue = $row['PrenomAgent'];
		$this->SexeAgent->DbValue = $row['SexeAgent'];
		$this->EtatCivilAgent->DbValue = $row['EtatCivilAgent'];
		$this->TelephoneAgent->DbValue = $row['TelephoneAgent'];
		$this->PhotoAgent->DbValue = $row['PhotoAgent'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;

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
		// CodeAgent
		// NomAgent
		// PostnomAgent
		// PrenomAgent
		// SexeAgent
		// EtatCivilAgent
		// TelephoneAgent
		// PhotoAgent

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// CodeAgent
		$this->CodeAgent->ViewValue = $this->CodeAgent->CurrentValue;
		if (strval($this->CodeAgent->CurrentValue) <> "") {
			$sFilterWrk = "`IdAgent`" . ew_SearchString("=", $this->CodeAgent->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `IdAgent`, `CodeAgent` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `agent`";
		$sWhereWrk = "";
		$this->CodeAgent->LookupFilters = array("dx1" => '`CodeAgent`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->CodeAgent, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->CodeAgent->ViewValue = $this->CodeAgent->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->CodeAgent->ViewValue = $this->CodeAgent->CurrentValue;
			}
		} else {
			$this->CodeAgent->ViewValue = NULL;
		}
		$this->CodeAgent->ViewCustomAttributes = "";

		// NomAgent
		$this->NomAgent->ViewValue = $this->NomAgent->CurrentValue;
		if (strval($this->NomAgent->CurrentValue) <> "") {
			$sFilterWrk = "`CodeAgent`" . ew_SearchString("=", $this->NomAgent->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `CodeAgent`, `NomAgent` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `vueenregistrementagent`";
		$sWhereWrk = "";
		$this->NomAgent->LookupFilters = array("dx1" => '`NomAgent`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->NomAgent, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->NomAgent->ViewValue = $this->NomAgent->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->NomAgent->ViewValue = $this->NomAgent->CurrentValue;
			}
		} else {
			$this->NomAgent->ViewValue = NULL;
		}
		$this->NomAgent->ViewCustomAttributes = "";

		// PostnomAgent
		$this->PostnomAgent->ViewValue = $this->PostnomAgent->CurrentValue;
		if (strval($this->PostnomAgent->CurrentValue) <> "") {
			$sFilterWrk = "`CodeAgent`" . ew_SearchString("=", $this->PostnomAgent->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `CodeAgent`, `PostnomAgent` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `vueenregistrementagent`";
		$sWhereWrk = "";
		$this->PostnomAgent->LookupFilters = array("dx1" => '`PostnomAgent`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->PostnomAgent, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->PostnomAgent->ViewValue = $this->PostnomAgent->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->PostnomAgent->ViewValue = $this->PostnomAgent->CurrentValue;
			}
		} else {
			$this->PostnomAgent->ViewValue = NULL;
		}
		$this->PostnomAgent->ViewCustomAttributes = "";

		// PrenomAgent
		$this->PrenomAgent->ViewValue = $this->PrenomAgent->CurrentValue;
		$this->PrenomAgent->ViewCustomAttributes = "";

		// SexeAgent
		$this->SexeAgent->ViewValue = $this->SexeAgent->CurrentValue;
		$this->SexeAgent->ViewCustomAttributes = "";

		// EtatCivilAgent
		$this->EtatCivilAgent->ViewValue = $this->EtatCivilAgent->CurrentValue;
		$this->EtatCivilAgent->ViewCustomAttributes = "";

		// TelephoneAgent
		$this->TelephoneAgent->ViewValue = $this->TelephoneAgent->CurrentValue;
		$this->TelephoneAgent->ViewCustomAttributes = "";

		// PhotoAgent
		$this->PhotoAgent->ViewValue = $this->PhotoAgent->CurrentValue;
		$this->PhotoAgent->ImageAlt = $this->PhotoAgent->FldAlt();
		$this->PhotoAgent->ViewCustomAttributes = "";

			// CodeAgent
			$this->CodeAgent->LinkCustomAttributes = "";
			$this->CodeAgent->HrefValue = "";
			$this->CodeAgent->TooltipValue = "";

			// NomAgent
			$this->NomAgent->LinkCustomAttributes = "";
			$this->NomAgent->HrefValue = "";
			$this->NomAgent->TooltipValue = "";

			// PostnomAgent
			$this->PostnomAgent->LinkCustomAttributes = "";
			$this->PostnomAgent->HrefValue = "";
			$this->PostnomAgent->TooltipValue = "";

			// PrenomAgent
			$this->PrenomAgent->LinkCustomAttributes = "";
			$this->PrenomAgent->HrefValue = "";
			$this->PrenomAgent->TooltipValue = "";

			// SexeAgent
			$this->SexeAgent->LinkCustomAttributes = "";
			$this->SexeAgent->HrefValue = "";
			$this->SexeAgent->TooltipValue = "";

			// EtatCivilAgent
			$this->EtatCivilAgent->LinkCustomAttributes = "";
			$this->EtatCivilAgent->HrefValue = "";
			$this->EtatCivilAgent->TooltipValue = "";

			// TelephoneAgent
			$this->TelephoneAgent->LinkCustomAttributes = "";
			$this->TelephoneAgent->HrefValue = "";
			$this->TelephoneAgent->TooltipValue = "";

			// PhotoAgent
			$this->PhotoAgent->LinkCustomAttributes = "";
			$this->PhotoAgent->HrefValue = "";
			$this->PhotoAgent->TooltipValue = "";
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
if (!isset($vueenregistrementagent_list)) $vueenregistrementagent_list = new cvueenregistrementagent_list();

// Page init
$vueenregistrementagent_list->Page_Init();

// Page main
$vueenregistrementagent_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$vueenregistrementagent_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fvueenregistrementagentlist = new ew_Form("fvueenregistrementagentlist", "list");
fvueenregistrementagentlist.FormKeyCountName = '<?php echo $vueenregistrementagent_list->FormKeyCountName ?>';

// Form_CustomValidate event
fvueenregistrementagentlist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fvueenregistrementagentlist.ValidateRequired = true;
<?php } else { ?>
fvueenregistrementagentlist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fvueenregistrementagentlist.Lists["x_CodeAgent"] = {"LinkField":"x_IdAgent","Ajax":true,"AutoFill":false,"DisplayFields":["x_CodeAgent","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"agent"};
fvueenregistrementagentlist.Lists["x_NomAgent"] = {"LinkField":"x_CodeAgent","Ajax":true,"AutoFill":false,"DisplayFields":["x_NomAgent","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"vueenregistrementagent"};
fvueenregistrementagentlist.Lists["x_PostnomAgent"] = {"LinkField":"x_CodeAgent","Ajax":true,"AutoFill":false,"DisplayFields":["x_PostnomAgent","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"vueenregistrementagent"};

// Form object for search
var CurrentSearchForm = fvueenregistrementagentlistsrch = new ew_Form("fvueenregistrementagentlistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php if ($vueenregistrementagent_list->TotalRecs > 0 && $vueenregistrementagent_list->ExportOptions->Visible()) { ?>
<?php $vueenregistrementagent_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($vueenregistrementagent_list->SearchOptions->Visible()) { ?>
<?php $vueenregistrementagent_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($vueenregistrementagent_list->FilterOptions->Visible()) { ?>
<?php $vueenregistrementagent_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php
	$bSelectLimit = $vueenregistrementagent_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($vueenregistrementagent_list->TotalRecs <= 0)
			$vueenregistrementagent_list->TotalRecs = $vueenregistrementagent->SelectRecordCount();
	} else {
		if (!$vueenregistrementagent_list->Recordset && ($vueenregistrementagent_list->Recordset = $vueenregistrementagent_list->LoadRecordset()))
			$vueenregistrementagent_list->TotalRecs = $vueenregistrementagent_list->Recordset->RecordCount();
	}
	$vueenregistrementagent_list->StartRec = 1;
	if ($vueenregistrementagent_list->DisplayRecs <= 0 || ($vueenregistrementagent->Export <> "" && $vueenregistrementagent->ExportAll)) // Display all records
		$vueenregistrementagent_list->DisplayRecs = $vueenregistrementagent_list->TotalRecs;
	if (!($vueenregistrementagent->Export <> "" && $vueenregistrementagent->ExportAll))
		$vueenregistrementagent_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$vueenregistrementagent_list->Recordset = $vueenregistrementagent_list->LoadRecordset($vueenregistrementagent_list->StartRec-1, $vueenregistrementagent_list->DisplayRecs);

	// Set no record found message
	if ($vueenregistrementagent->CurrentAction == "" && $vueenregistrementagent_list->TotalRecs == 0) {
		if ($vueenregistrementagent_list->SearchWhere == "0=101")
			$vueenregistrementagent_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$vueenregistrementagent_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$vueenregistrementagent_list->RenderOtherOptions();
?>
<?php if ($vueenregistrementagent->Export == "" && $vueenregistrementagent->CurrentAction == "") { ?>
<form name="fvueenregistrementagentlistsrch" id="fvueenregistrementagentlistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($vueenregistrementagent_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fvueenregistrementagentlistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="vueenregistrementagent">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($vueenregistrementagent_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($vueenregistrementagent_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $vueenregistrementagent_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($vueenregistrementagent_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($vueenregistrementagent_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($vueenregistrementagent_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($vueenregistrementagent_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
		</ul>
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php $vueenregistrementagent_list->ShowPageHeader(); ?>
<?php
$vueenregistrementagent_list->ShowMessage();
?>
<?php if ($vueenregistrementagent_list->TotalRecs > 0 || $vueenregistrementagent->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid vueenregistrementagent">
<form name="fvueenregistrementagentlist" id="fvueenregistrementagentlist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($vueenregistrementagent_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $vueenregistrementagent_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="vueenregistrementagent">
<div id="gmp_vueenregistrementagent" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($vueenregistrementagent_list->TotalRecs > 0 || $vueenregistrementagent->CurrentAction == "gridedit") { ?>
<table id="tbl_vueenregistrementagentlist" class="table ewTable">
<?php echo $vueenregistrementagent->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$vueenregistrementagent_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$vueenregistrementagent_list->RenderListOptions();

// Render list options (header, left)
$vueenregistrementagent_list->ListOptions->Render("header", "left");
?>
<?php if ($vueenregistrementagent->CodeAgent->Visible) { // CodeAgent ?>
	<?php if ($vueenregistrementagent->SortUrl($vueenregistrementagent->CodeAgent) == "") { ?>
		<th data-name="CodeAgent"><div id="elh_vueenregistrementagent_CodeAgent" class="vueenregistrementagent_CodeAgent"><div class="ewTableHeaderCaption"><?php echo $vueenregistrementagent->CodeAgent->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="CodeAgent"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vueenregistrementagent->SortUrl($vueenregistrementagent->CodeAgent) ?>',1);"><div id="elh_vueenregistrementagent_CodeAgent" class="vueenregistrementagent_CodeAgent">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vueenregistrementagent->CodeAgent->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($vueenregistrementagent->CodeAgent->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vueenregistrementagent->CodeAgent->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($vueenregistrementagent->NomAgent->Visible) { // NomAgent ?>
	<?php if ($vueenregistrementagent->SortUrl($vueenregistrementagent->NomAgent) == "") { ?>
		<th data-name="NomAgent"><div id="elh_vueenregistrementagent_NomAgent" class="vueenregistrementagent_NomAgent"><div class="ewTableHeaderCaption"><?php echo $vueenregistrementagent->NomAgent->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="NomAgent"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vueenregistrementagent->SortUrl($vueenregistrementagent->NomAgent) ?>',1);"><div id="elh_vueenregistrementagent_NomAgent" class="vueenregistrementagent_NomAgent">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vueenregistrementagent->NomAgent->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($vueenregistrementagent->NomAgent->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vueenregistrementagent->NomAgent->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($vueenregistrementagent->PostnomAgent->Visible) { // PostnomAgent ?>
	<?php if ($vueenregistrementagent->SortUrl($vueenregistrementagent->PostnomAgent) == "") { ?>
		<th data-name="PostnomAgent"><div id="elh_vueenregistrementagent_PostnomAgent" class="vueenregistrementagent_PostnomAgent"><div class="ewTableHeaderCaption"><?php echo $vueenregistrementagent->PostnomAgent->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="PostnomAgent"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vueenregistrementagent->SortUrl($vueenregistrementagent->PostnomAgent) ?>',1);"><div id="elh_vueenregistrementagent_PostnomAgent" class="vueenregistrementagent_PostnomAgent">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vueenregistrementagent->PostnomAgent->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($vueenregistrementagent->PostnomAgent->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vueenregistrementagent->PostnomAgent->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($vueenregistrementagent->PrenomAgent->Visible) { // PrenomAgent ?>
	<?php if ($vueenregistrementagent->SortUrl($vueenregistrementagent->PrenomAgent) == "") { ?>
		<th data-name="PrenomAgent"><div id="elh_vueenregistrementagent_PrenomAgent" class="vueenregistrementagent_PrenomAgent"><div class="ewTableHeaderCaption"><?php echo $vueenregistrementagent->PrenomAgent->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="PrenomAgent"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vueenregistrementagent->SortUrl($vueenregistrementagent->PrenomAgent) ?>',1);"><div id="elh_vueenregistrementagent_PrenomAgent" class="vueenregistrementagent_PrenomAgent">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vueenregistrementagent->PrenomAgent->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($vueenregistrementagent->PrenomAgent->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vueenregistrementagent->PrenomAgent->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($vueenregistrementagent->SexeAgent->Visible) { // SexeAgent ?>
	<?php if ($vueenregistrementagent->SortUrl($vueenregistrementagent->SexeAgent) == "") { ?>
		<th data-name="SexeAgent"><div id="elh_vueenregistrementagent_SexeAgent" class="vueenregistrementagent_SexeAgent"><div class="ewTableHeaderCaption"><?php echo $vueenregistrementagent->SexeAgent->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="SexeAgent"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vueenregistrementagent->SortUrl($vueenregistrementagent->SexeAgent) ?>',1);"><div id="elh_vueenregistrementagent_SexeAgent" class="vueenregistrementagent_SexeAgent">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vueenregistrementagent->SexeAgent->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($vueenregistrementagent->SexeAgent->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vueenregistrementagent->SexeAgent->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($vueenregistrementagent->EtatCivilAgent->Visible) { // EtatCivilAgent ?>
	<?php if ($vueenregistrementagent->SortUrl($vueenregistrementagent->EtatCivilAgent) == "") { ?>
		<th data-name="EtatCivilAgent"><div id="elh_vueenregistrementagent_EtatCivilAgent" class="vueenregistrementagent_EtatCivilAgent"><div class="ewTableHeaderCaption"><?php echo $vueenregistrementagent->EtatCivilAgent->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="EtatCivilAgent"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vueenregistrementagent->SortUrl($vueenregistrementagent->EtatCivilAgent) ?>',1);"><div id="elh_vueenregistrementagent_EtatCivilAgent" class="vueenregistrementagent_EtatCivilAgent">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vueenregistrementagent->EtatCivilAgent->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($vueenregistrementagent->EtatCivilAgent->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vueenregistrementagent->EtatCivilAgent->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($vueenregistrementagent->TelephoneAgent->Visible) { // TelephoneAgent ?>
	<?php if ($vueenregistrementagent->SortUrl($vueenregistrementagent->TelephoneAgent) == "") { ?>
		<th data-name="TelephoneAgent"><div id="elh_vueenregistrementagent_TelephoneAgent" class="vueenregistrementagent_TelephoneAgent"><div class="ewTableHeaderCaption"><?php echo $vueenregistrementagent->TelephoneAgent->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="TelephoneAgent"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vueenregistrementagent->SortUrl($vueenregistrementagent->TelephoneAgent) ?>',1);"><div id="elh_vueenregistrementagent_TelephoneAgent" class="vueenregistrementagent_TelephoneAgent">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vueenregistrementagent->TelephoneAgent->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($vueenregistrementagent->TelephoneAgent->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vueenregistrementagent->TelephoneAgent->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($vueenregistrementagent->PhotoAgent->Visible) { // PhotoAgent ?>
	<?php if ($vueenregistrementagent->SortUrl($vueenregistrementagent->PhotoAgent) == "") { ?>
		<th data-name="PhotoAgent"><div id="elh_vueenregistrementagent_PhotoAgent" class="vueenregistrementagent_PhotoAgent"><div class="ewTableHeaderCaption"><?php echo $vueenregistrementagent->PhotoAgent->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="PhotoAgent"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vueenregistrementagent->SortUrl($vueenregistrementagent->PhotoAgent) ?>',1);"><div id="elh_vueenregistrementagent_PhotoAgent" class="vueenregistrementagent_PhotoAgent">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vueenregistrementagent->PhotoAgent->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($vueenregistrementagent->PhotoAgent->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vueenregistrementagent->PhotoAgent->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$vueenregistrementagent_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($vueenregistrementagent->ExportAll && $vueenregistrementagent->Export <> "") {
	$vueenregistrementagent_list->StopRec = $vueenregistrementagent_list->TotalRecs;
} else {

	// Set the last record to display
	if ($vueenregistrementagent_list->TotalRecs > $vueenregistrementagent_list->StartRec + $vueenregistrementagent_list->DisplayRecs - 1)
		$vueenregistrementagent_list->StopRec = $vueenregistrementagent_list->StartRec + $vueenregistrementagent_list->DisplayRecs - 1;
	else
		$vueenregistrementagent_list->StopRec = $vueenregistrementagent_list->TotalRecs;
}
$vueenregistrementagent_list->RecCnt = $vueenregistrementagent_list->StartRec - 1;
if ($vueenregistrementagent_list->Recordset && !$vueenregistrementagent_list->Recordset->EOF) {
	$vueenregistrementagent_list->Recordset->MoveFirst();
	$bSelectLimit = $vueenregistrementagent_list->UseSelectLimit;
	if (!$bSelectLimit && $vueenregistrementagent_list->StartRec > 1)
		$vueenregistrementagent_list->Recordset->Move($vueenregistrementagent_list->StartRec - 1);
} elseif (!$vueenregistrementagent->AllowAddDeleteRow && $vueenregistrementagent_list->StopRec == 0) {
	$vueenregistrementagent_list->StopRec = $vueenregistrementagent->GridAddRowCount;
}

// Initialize aggregate
$vueenregistrementagent->RowType = EW_ROWTYPE_AGGREGATEINIT;
$vueenregistrementagent->ResetAttrs();
$vueenregistrementagent_list->RenderRow();
while ($vueenregistrementagent_list->RecCnt < $vueenregistrementagent_list->StopRec) {
	$vueenregistrementagent_list->RecCnt++;
	if (intval($vueenregistrementagent_list->RecCnt) >= intval($vueenregistrementagent_list->StartRec)) {
		$vueenregistrementagent_list->RowCnt++;

		// Set up key count
		$vueenregistrementagent_list->KeyCount = $vueenregistrementagent_list->RowIndex;

		// Init row class and style
		$vueenregistrementagent->ResetAttrs();
		$vueenregistrementagent->CssClass = "";
		if ($vueenregistrementagent->CurrentAction == "gridadd") {
		} else {
			$vueenregistrementagent_list->LoadRowValues($vueenregistrementagent_list->Recordset); // Load row values
		}
		$vueenregistrementagent->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$vueenregistrementagent->RowAttrs = array_merge($vueenregistrementagent->RowAttrs, array('data-rowindex'=>$vueenregistrementagent_list->RowCnt, 'id'=>'r' . $vueenregistrementagent_list->RowCnt . '_vueenregistrementagent', 'data-rowtype'=>$vueenregistrementagent->RowType));

		// Render row
		$vueenregistrementagent_list->RenderRow();

		// Render list options
		$vueenregistrementagent_list->RenderListOptions();
?>
	<tr<?php echo $vueenregistrementagent->RowAttributes() ?>>
<?php

// Render list options (body, left)
$vueenregistrementagent_list->ListOptions->Render("body", "left", $vueenregistrementagent_list->RowCnt);
?>
	<?php if ($vueenregistrementagent->CodeAgent->Visible) { // CodeAgent ?>
		<td data-name="CodeAgent"<?php echo $vueenregistrementagent->CodeAgent->CellAttributes() ?>>
<span id="el<?php echo $vueenregistrementagent_list->RowCnt ?>_vueenregistrementagent_CodeAgent" class="vueenregistrementagent_CodeAgent">
<span<?php echo $vueenregistrementagent->CodeAgent->ViewAttributes() ?>>
<?php echo $vueenregistrementagent->CodeAgent->ListViewValue() ?></span>
</span>
<a id="<?php echo $vueenregistrementagent_list->PageObjName . "_row_" . $vueenregistrementagent_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vueenregistrementagent->NomAgent->Visible) { // NomAgent ?>
		<td data-name="NomAgent"<?php echo $vueenregistrementagent->NomAgent->CellAttributes() ?>>
<span id="el<?php echo $vueenregistrementagent_list->RowCnt ?>_vueenregistrementagent_NomAgent" class="vueenregistrementagent_NomAgent">
<span<?php echo $vueenregistrementagent->NomAgent->ViewAttributes() ?>>
<?php echo $vueenregistrementagent->NomAgent->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($vueenregistrementagent->PostnomAgent->Visible) { // PostnomAgent ?>
		<td data-name="PostnomAgent"<?php echo $vueenregistrementagent->PostnomAgent->CellAttributes() ?>>
<span id="el<?php echo $vueenregistrementagent_list->RowCnt ?>_vueenregistrementagent_PostnomAgent" class="vueenregistrementagent_PostnomAgent">
<span<?php echo $vueenregistrementagent->PostnomAgent->ViewAttributes() ?>>
<?php echo $vueenregistrementagent->PostnomAgent->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($vueenregistrementagent->PrenomAgent->Visible) { // PrenomAgent ?>
		<td data-name="PrenomAgent"<?php echo $vueenregistrementagent->PrenomAgent->CellAttributes() ?>>
<span id="el<?php echo $vueenregistrementagent_list->RowCnt ?>_vueenregistrementagent_PrenomAgent" class="vueenregistrementagent_PrenomAgent">
<span<?php echo $vueenregistrementagent->PrenomAgent->ViewAttributes() ?>>
<?php echo $vueenregistrementagent->PrenomAgent->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($vueenregistrementagent->SexeAgent->Visible) { // SexeAgent ?>
		<td data-name="SexeAgent"<?php echo $vueenregistrementagent->SexeAgent->CellAttributes() ?>>
<span id="el<?php echo $vueenregistrementagent_list->RowCnt ?>_vueenregistrementagent_SexeAgent" class="vueenregistrementagent_SexeAgent">
<span<?php echo $vueenregistrementagent->SexeAgent->ViewAttributes() ?>>
<?php echo $vueenregistrementagent->SexeAgent->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($vueenregistrementagent->EtatCivilAgent->Visible) { // EtatCivilAgent ?>
		<td data-name="EtatCivilAgent"<?php echo $vueenregistrementagent->EtatCivilAgent->CellAttributes() ?>>
<span id="el<?php echo $vueenregistrementagent_list->RowCnt ?>_vueenregistrementagent_EtatCivilAgent" class="vueenregistrementagent_EtatCivilAgent">
<span<?php echo $vueenregistrementagent->EtatCivilAgent->ViewAttributes() ?>>
<?php echo $vueenregistrementagent->EtatCivilAgent->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($vueenregistrementagent->TelephoneAgent->Visible) { // TelephoneAgent ?>
		<td data-name="TelephoneAgent"<?php echo $vueenregistrementagent->TelephoneAgent->CellAttributes() ?>>
<span id="el<?php echo $vueenregistrementagent_list->RowCnt ?>_vueenregistrementagent_TelephoneAgent" class="vueenregistrementagent_TelephoneAgent">
<span<?php echo $vueenregistrementagent->TelephoneAgent->ViewAttributes() ?>>
<?php echo $vueenregistrementagent->TelephoneAgent->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($vueenregistrementagent->PhotoAgent->Visible) { // PhotoAgent ?>
		<td data-name="PhotoAgent"<?php echo $vueenregistrementagent->PhotoAgent->CellAttributes() ?>>
<span id="el<?php echo $vueenregistrementagent_list->RowCnt ?>_vueenregistrementagent_PhotoAgent" class="vueenregistrementagent_PhotoAgent">
<span>
<?php echo ew_GetImgViewTag($vueenregistrementagent->PhotoAgent, $vueenregistrementagent->PhotoAgent->ListViewValue()) ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$vueenregistrementagent_list->ListOptions->Render("body", "right", $vueenregistrementagent_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($vueenregistrementagent->CurrentAction <> "gridadd")
		$vueenregistrementagent_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($vueenregistrementagent->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($vueenregistrementagent_list->Recordset)
	$vueenregistrementagent_list->Recordset->Close();
?>
<div class="panel-footer ewGridLowerPanel">
<?php if ($vueenregistrementagent->CurrentAction <> "gridadd" && $vueenregistrementagent->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($vueenregistrementagent_list->Pager)) $vueenregistrementagent_list->Pager = new cPrevNextPager($vueenregistrementagent_list->StartRec, $vueenregistrementagent_list->DisplayRecs, $vueenregistrementagent_list->TotalRecs) ?>
<?php if ($vueenregistrementagent_list->Pager->RecordCount > 0 && $vueenregistrementagent_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($vueenregistrementagent_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $vueenregistrementagent_list->PageUrl() ?>start=<?php echo $vueenregistrementagent_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($vueenregistrementagent_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $vueenregistrementagent_list->PageUrl() ?>start=<?php echo $vueenregistrementagent_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $vueenregistrementagent_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($vueenregistrementagent_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $vueenregistrementagent_list->PageUrl() ?>start=<?php echo $vueenregistrementagent_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($vueenregistrementagent_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $vueenregistrementagent_list->PageUrl() ?>start=<?php echo $vueenregistrementagent_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $vueenregistrementagent_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $vueenregistrementagent_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $vueenregistrementagent_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $vueenregistrementagent_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($vueenregistrementagent_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($vueenregistrementagent_list->TotalRecs == 0 && $vueenregistrementagent->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($vueenregistrementagent_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
fvueenregistrementagentlistsrch.FilterList = <?php echo $vueenregistrementagent_list->GetFilterList() ?>;
fvueenregistrementagentlistsrch.Init();
fvueenregistrementagentlist.Init();
</script>
<?php
$vueenregistrementagent_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$vueenregistrementagent_list->Page_Terminate();
?>
