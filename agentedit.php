<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "agentinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$agent_edit = NULL; // Initialize page object first

class cagent_edit extends cagent {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{84EC96E8-6D54-4BAB-B15E-1EA379B0779B}";

	// Table name
	var $TableName = 'agent';

	// Page object name
	var $PageObjName = 'agent_edit';

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

		// Table object (agent)
		if (!isset($GLOBALS["agent"]) || get_class($GLOBALS["agent"]) == "cagent") {
			$GLOBALS["agent"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["agent"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'agent', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// User table object (agent)
		if (!isset($UserTable)) {
			$UserTable = new cagent();
			$UserTableConn = Conn($UserTable->DBID);
		}
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
		if (!$Security->CanEdit()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("agentlist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
			if (strval($Security->CurrentUserID()) == "") {
				$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
				$this->Page_Terminate(ew_GetUrl("agentlist.php"));
			}
		}

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->MatricAgent->SetVisibility();
		$this->NomAgent->SetVisibility();
		$this->PostnomAgent->SetVisibility();
		$this->PrenomAgent->SetVisibility();
		$this->SexeAgent->SetVisibility();
		$this->EtatCivilAgent->SetVisibility();
		$this->TelephoneAgent->SetVisibility();
		$this->userlevel_id->SetVisibility();
		$this->Motdepasse->SetVisibility();

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
		global $EW_EXPORT, $agent;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($agent);
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

			// Handle modal response
			if ($this->IsModal) {
				$row = array();
				$row["url"] = $url;
				echo ew_ArrayToJson(array($row));
			} else {
				header("Location: " . $url);
			}
		}
		exit();
	}
	var $FormClassName = "form-horizontal ewForm ewEditForm";
	var $IsModal = FALSE;
	var $DbMasterFilter;
	var $DbDetailFilter;
	var $DisplayRecs = 1;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $RecCnt;
	var $RecKey = array();
	var $Recordset;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;
		global $gbSkipHeaderFooter;

		// Check modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;

		// Load current record
		$bLoadCurrentRecord = FALSE;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;

		// Load key from QueryString
		if (@$_GET["IdAgent"] <> "") {
			$this->IdAgent->setQueryStringValue($_GET["IdAgent"]);
			$this->RecKey["IdAgent"] = $this->IdAgent->QueryStringValue;
		} else {
			$bLoadCurrentRecord = TRUE;
		}

		// Load recordset
		$this->StartRec = 1; // Initialize start position
		if ($this->Recordset = $this->LoadRecordset()) // Load records
			$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
		if ($this->TotalRecs <= 0) { // No record found
			if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
			$this->Page_Terminate("agentlist.php"); // Return to list page
		} elseif ($bLoadCurrentRecord) { // Load current record position
			$this->SetUpStartRec(); // Set up start record position

			// Point to current record
			if (intval($this->StartRec) <= intval($this->TotalRecs)) {
				$bMatchRecord = TRUE;
				$this->Recordset->Move($this->StartRec-1);
			}
		} else { // Match key values
			while (!$this->Recordset->EOF) {
				if (strval($this->IdAgent->CurrentValue) == strval($this->Recordset->fields('IdAgent'))) {
					$this->setStartRecordNumber($this->StartRec); // Save record position
					$bMatchRecord = TRUE;
					break;
				} else {
					$this->StartRec++;
					$this->Recordset->MoveNext();
				}
			}
		}

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$bMatchRecord) {
					if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
						$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
					$this->Page_Terminate("agentlist.php"); // Return to list page
				} else {
					$this->LoadRowValues($this->Recordset); // Load row values
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->GetAddUrl();
				if (ew_GetPageName($sReturnUrl) == "agentlist.php")
					$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} elseif ($this->getFailureMessage() == $Language->Phrase("NoRecord")) {
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
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

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->MatricAgent->FldIsDetailKey) {
			$this->MatricAgent->setFormValue($objForm->GetValue("x_MatricAgent"));
		}
		if (!$this->NomAgent->FldIsDetailKey) {
			$this->NomAgent->setFormValue($objForm->GetValue("x_NomAgent"));
		}
		if (!$this->PostnomAgent->FldIsDetailKey) {
			$this->PostnomAgent->setFormValue($objForm->GetValue("x_PostnomAgent"));
		}
		if (!$this->PrenomAgent->FldIsDetailKey) {
			$this->PrenomAgent->setFormValue($objForm->GetValue("x_PrenomAgent"));
		}
		if (!$this->SexeAgent->FldIsDetailKey) {
			$this->SexeAgent->setFormValue($objForm->GetValue("x_SexeAgent"));
		}
		if (!$this->EtatCivilAgent->FldIsDetailKey) {
			$this->EtatCivilAgent->setFormValue($objForm->GetValue("x_EtatCivilAgent"));
		}
		if (!$this->TelephoneAgent->FldIsDetailKey) {
			$this->TelephoneAgent->setFormValue($objForm->GetValue("x_TelephoneAgent"));
		}
		if (!$this->userlevel_id->FldIsDetailKey) {
			$this->userlevel_id->setFormValue($objForm->GetValue("x_userlevel_id"));
		}
		if (!$this->Motdepasse->FldIsDetailKey) {
			$this->Motdepasse->setFormValue($objForm->GetValue("x_Motdepasse"));
		}
		if (!$this->IdAgent->FldIsDetailKey)
			$this->IdAgent->setFormValue($objForm->GetValue("x_IdAgent"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->IdAgent->CurrentValue = $this->IdAgent->FormValue;
		$this->MatricAgent->CurrentValue = $this->MatricAgent->FormValue;
		$this->NomAgent->CurrentValue = $this->NomAgent->FormValue;
		$this->PostnomAgent->CurrentValue = $this->PostnomAgent->FormValue;
		$this->PrenomAgent->CurrentValue = $this->PrenomAgent->FormValue;
		$this->SexeAgent->CurrentValue = $this->SexeAgent->FormValue;
		$this->EtatCivilAgent->CurrentValue = $this->EtatCivilAgent->FormValue;
		$this->TelephoneAgent->CurrentValue = $this->TelephoneAgent->FormValue;
		$this->userlevel_id->CurrentValue = $this->userlevel_id->FormValue;
		$this->Motdepasse->CurrentValue = $this->Motdepasse->FormValue;
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
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderByList())));
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

		// Check if valid user id
		if ($res) {
			$res = $this->ShowOptionLink('edit');
			if (!$res) {
				$sUserIdMsg = ew_DeniedMsg();
				$this->setFailureMessage($sUserIdMsg);
			}
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->IdAgent->setDbValue($rs->fields('IdAgent'));
		$this->MatricAgent->setDbValue($rs->fields('MatricAgent'));
		$this->NomAgent->setDbValue($rs->fields('NomAgent'));
		$this->PostnomAgent->setDbValue($rs->fields('PostnomAgent'));
		$this->PrenomAgent->setDbValue($rs->fields('PrenomAgent'));
		$this->SexeAgent->setDbValue($rs->fields('SexeAgent'));
		$this->EtatCivilAgent->setDbValue($rs->fields('EtatCivilAgent'));
		$this->TelephoneAgent->setDbValue($rs->fields('TelephoneAgent'));
		$this->IdFonction->setDbValue($rs->fields('IdFonction'));
		if (array_key_exists('EV__IdFonction', $rs->fields)) {
			$this->IdFonction->VirtualValue = $rs->fields('EV__IdFonction'); // Set up virtual field value
		} else {
			$this->IdFonction->VirtualValue = ""; // Clear value
		}
		$this->userlevel_id->setDbValue($rs->fields('userlevel_id'));
		$this->Motdepasse->setDbValue($rs->fields('Motdepasse'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->IdAgent->DbValue = $row['IdAgent'];
		$this->MatricAgent->DbValue = $row['MatricAgent'];
		$this->NomAgent->DbValue = $row['NomAgent'];
		$this->PostnomAgent->DbValue = $row['PostnomAgent'];
		$this->PrenomAgent->DbValue = $row['PrenomAgent'];
		$this->SexeAgent->DbValue = $row['SexeAgent'];
		$this->EtatCivilAgent->DbValue = $row['EtatCivilAgent'];
		$this->TelephoneAgent->DbValue = $row['TelephoneAgent'];
		$this->IdFonction->DbValue = $row['IdFonction'];
		$this->userlevel_id->DbValue = $row['userlevel_id'];
		$this->Motdepasse->DbValue = $row['Motdepasse'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// IdAgent
		// MatricAgent
		// NomAgent
		// PostnomAgent
		// PrenomAgent
		// SexeAgent
		// EtatCivilAgent
		// TelephoneAgent
		// IdFonction
		// userlevel_id
		// Motdepasse

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// IdAgent
		$this->IdAgent->ViewValue = $this->IdAgent->CurrentValue;
		$this->IdAgent->ViewCustomAttributes = "";

		// MatricAgent
		$this->MatricAgent->ViewValue = $this->MatricAgent->CurrentValue;
		$this->MatricAgent->ViewCustomAttributes = "";

		// NomAgent
		$this->NomAgent->ViewValue = $this->NomAgent->CurrentValue;
		$this->NomAgent->ViewCustomAttributes = "";

		// PostnomAgent
		$this->PostnomAgent->ViewValue = $this->PostnomAgent->CurrentValue;
		$this->PostnomAgent->ViewCustomAttributes = "";

		// PrenomAgent
		$this->PrenomAgent->ViewValue = $this->PrenomAgent->CurrentValue;
		$this->PrenomAgent->ViewCustomAttributes = "";

		// SexeAgent
		if (strval($this->SexeAgent->CurrentValue) <> "") {
			$this->SexeAgent->ViewValue = $this->SexeAgent->OptionCaption($this->SexeAgent->CurrentValue);
		} else {
			$this->SexeAgent->ViewValue = NULL;
		}
		$this->SexeAgent->ViewCustomAttributes = "";

		// EtatCivilAgent
		if (strval($this->EtatCivilAgent->CurrentValue) <> "") {
			$this->EtatCivilAgent->ViewValue = $this->EtatCivilAgent->OptionCaption($this->EtatCivilAgent->CurrentValue);
		} else {
			$this->EtatCivilAgent->ViewValue = NULL;
		}
		$this->EtatCivilAgent->ViewCustomAttributes = "";

		// TelephoneAgent
		$this->TelephoneAgent->ViewValue = $this->TelephoneAgent->CurrentValue;
		$this->TelephoneAgent->ViewCustomAttributes = "";

		// IdFonction
		if ($this->IdFonction->VirtualValue <> "") {
			$this->IdFonction->ViewValue = $this->IdFonction->VirtualValue;
		} else {
			$this->IdFonction->ViewValue = $this->IdFonction->CurrentValue;
		if (strval($this->IdFonction->CurrentValue) <> "") {
			$sFilterWrk = "`IdFonction`" . ew_SearchString("=", $this->IdFonction->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `IdFonction`, `NomFoction` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `fonction`";
		$sWhereWrk = "";
		$this->IdFonction->LookupFilters = array("dx1" => '`NomFoction`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->IdFonction, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->IdFonction->ViewValue = $this->IdFonction->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->IdFonction->ViewValue = $this->IdFonction->CurrentValue;
			}
		} else {
			$this->IdFonction->ViewValue = NULL;
		}
		}
		$this->IdFonction->ViewCustomAttributes = "";

		// userlevel_id
		if ($Security->CanAdmin()) { // System admin
		if (strval($this->userlevel_id->CurrentValue) <> "") {
			$sFilterWrk = "`userlevelid`" . ew_SearchString("=", $this->userlevel_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `userlevelid`, `userlevelname` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `userlevels`";
		$sWhereWrk = "";
		$this->userlevel_id->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->userlevel_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->userlevel_id->ViewValue = $this->userlevel_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->userlevel_id->ViewValue = $this->userlevel_id->CurrentValue;
			}
		} else {
			$this->userlevel_id->ViewValue = NULL;
		}
		} else {
			$this->userlevel_id->ViewValue = $Language->Phrase("PasswordMask");
		}
		$this->userlevel_id->ViewCustomAttributes = "";

		// Motdepasse
		$this->Motdepasse->ViewValue = $Language->Phrase("PasswordMask");
		$this->Motdepasse->ViewCustomAttributes = "";

			// MatricAgent
			$this->MatricAgent->LinkCustomAttributes = "";
			$this->MatricAgent->HrefValue = "";
			$this->MatricAgent->TooltipValue = "";

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

			// userlevel_id
			$this->userlevel_id->LinkCustomAttributes = "";
			$this->userlevel_id->HrefValue = "";
			$this->userlevel_id->TooltipValue = "";

			// Motdepasse
			$this->Motdepasse->LinkCustomAttributes = "";
			$this->Motdepasse->HrefValue = "";
			$this->Motdepasse->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// MatricAgent
			$this->MatricAgent->EditAttrs["class"] = "form-control";
			$this->MatricAgent->EditCustomAttributes = "";
			$this->MatricAgent->EditValue = ew_HtmlEncode($this->MatricAgent->CurrentValue);
			$this->MatricAgent->PlaceHolder = ew_RemoveHtml($this->MatricAgent->FldCaption());

			// NomAgent
			$this->NomAgent->EditAttrs["class"] = "form-control";
			$this->NomAgent->EditCustomAttributes = "";
			$this->NomAgent->EditValue = ew_HtmlEncode($this->NomAgent->CurrentValue);
			$this->NomAgent->PlaceHolder = ew_RemoveHtml($this->NomAgent->FldCaption());

			// PostnomAgent
			$this->PostnomAgent->EditAttrs["class"] = "form-control";
			$this->PostnomAgent->EditCustomAttributes = "";
			$this->PostnomAgent->EditValue = ew_HtmlEncode($this->PostnomAgent->CurrentValue);
			$this->PostnomAgent->PlaceHolder = ew_RemoveHtml($this->PostnomAgent->FldCaption());

			// PrenomAgent
			$this->PrenomAgent->EditAttrs["class"] = "form-control";
			$this->PrenomAgent->EditCustomAttributes = "";
			$this->PrenomAgent->EditValue = ew_HtmlEncode($this->PrenomAgent->CurrentValue);
			$this->PrenomAgent->PlaceHolder = ew_RemoveHtml($this->PrenomAgent->FldCaption());

			// SexeAgent
			$this->SexeAgent->EditCustomAttributes = "";
			$this->SexeAgent->EditValue = $this->SexeAgent->Options(FALSE);

			// EtatCivilAgent
			$this->EtatCivilAgent->EditAttrs["class"] = "form-control";
			$this->EtatCivilAgent->EditCustomAttributes = "";
			$this->EtatCivilAgent->EditValue = $this->EtatCivilAgent->Options(TRUE);

			// TelephoneAgent
			$this->TelephoneAgent->EditAttrs["class"] = "form-control";
			$this->TelephoneAgent->EditCustomAttributes = "";
			$this->TelephoneAgent->EditValue = ew_HtmlEncode($this->TelephoneAgent->CurrentValue);
			$this->TelephoneAgent->PlaceHolder = ew_RemoveHtml($this->TelephoneAgent->FldCaption());

			// userlevel_id
			$this->userlevel_id->EditAttrs["class"] = "form-control";
			$this->userlevel_id->EditCustomAttributes = "";
			if (!$Security->CanAdmin()) { // System admin
				$this->userlevel_id->EditValue = $Language->Phrase("PasswordMask");
			} else {
			if (trim(strval($this->userlevel_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`userlevelid`" . ew_SearchString("=", $this->userlevel_id->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `userlevelid`, `userlevelname` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `userlevels`";
			$sWhereWrk = "";
			$this->userlevel_id->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->userlevel_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->userlevel_id->EditValue = $arwrk;
			}

			// Motdepasse
			$this->Motdepasse->EditAttrs["class"] = "form-control ewPasswordStrength";
			$this->Motdepasse->EditCustomAttributes = "";
			$this->Motdepasse->EditValue = ew_HtmlEncode($this->Motdepasse->CurrentValue);
			$this->Motdepasse->PlaceHolder = ew_RemoveHtml($this->Motdepasse->FldCaption());

			// Edit refer script
			// MatricAgent

			$this->MatricAgent->LinkCustomAttributes = "";
			$this->MatricAgent->HrefValue = "";

			// NomAgent
			$this->NomAgent->LinkCustomAttributes = "";
			$this->NomAgent->HrefValue = "";

			// PostnomAgent
			$this->PostnomAgent->LinkCustomAttributes = "";
			$this->PostnomAgent->HrefValue = "";

			// PrenomAgent
			$this->PrenomAgent->LinkCustomAttributes = "";
			$this->PrenomAgent->HrefValue = "";

			// SexeAgent
			$this->SexeAgent->LinkCustomAttributes = "";
			$this->SexeAgent->HrefValue = "";

			// EtatCivilAgent
			$this->EtatCivilAgent->LinkCustomAttributes = "";
			$this->EtatCivilAgent->HrefValue = "";

			// TelephoneAgent
			$this->TelephoneAgent->LinkCustomAttributes = "";
			$this->TelephoneAgent->HrefValue = "";

			// userlevel_id
			$this->userlevel_id->LinkCustomAttributes = "";
			$this->userlevel_id->HrefValue = "";

			// Motdepasse
			$this->Motdepasse->LinkCustomAttributes = "";
			$this->Motdepasse->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!$this->MatricAgent->FldIsDetailKey && !is_null($this->MatricAgent->FormValue) && $this->MatricAgent->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->MatricAgent->FldCaption(), $this->MatricAgent->ReqErrMsg));
		}
		if (!$this->NomAgent->FldIsDetailKey && !is_null($this->NomAgent->FormValue) && $this->NomAgent->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->NomAgent->FldCaption(), $this->NomAgent->ReqErrMsg));
		}
		if ($this->SexeAgent->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->SexeAgent->FldCaption(), $this->SexeAgent->ReqErrMsg));
		}
		if (!$this->TelephoneAgent->FldIsDetailKey && !is_null($this->TelephoneAgent->FormValue) && $this->TelephoneAgent->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->TelephoneAgent->FldCaption(), $this->TelephoneAgent->ReqErrMsg));
		}
		if (!$this->userlevel_id->FldIsDetailKey && !is_null($this->userlevel_id->FormValue) && $this->userlevel_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->userlevel_id->FldCaption(), $this->userlevel_id->ReqErrMsg));
		}
		if (!$this->Motdepasse->FldIsDetailKey && !is_null($this->Motdepasse->FormValue) && $this->Motdepasse->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Motdepasse->FldCaption(), $this->Motdepasse->ReqErrMsg));
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Update record based on key values
	function EditRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$conn = &$this->Connection();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// MatricAgent
			$this->MatricAgent->SetDbValueDef($rsnew, $this->MatricAgent->CurrentValue, NULL, $this->MatricAgent->ReadOnly);

			// NomAgent
			$this->NomAgent->SetDbValueDef($rsnew, $this->NomAgent->CurrentValue, NULL, $this->NomAgent->ReadOnly);

			// PostnomAgent
			$this->PostnomAgent->SetDbValueDef($rsnew, $this->PostnomAgent->CurrentValue, NULL, $this->PostnomAgent->ReadOnly);

			// PrenomAgent
			$this->PrenomAgent->SetDbValueDef($rsnew, $this->PrenomAgent->CurrentValue, NULL, $this->PrenomAgent->ReadOnly);

			// SexeAgent
			$this->SexeAgent->SetDbValueDef($rsnew, $this->SexeAgent->CurrentValue, NULL, $this->SexeAgent->ReadOnly);

			// EtatCivilAgent
			$this->EtatCivilAgent->SetDbValueDef($rsnew, $this->EtatCivilAgent->CurrentValue, NULL, $this->EtatCivilAgent->ReadOnly);

			// TelephoneAgent
			$this->TelephoneAgent->SetDbValueDef($rsnew, $this->TelephoneAgent->CurrentValue, NULL, $this->TelephoneAgent->ReadOnly);

			// userlevel_id
			if ($Security->CanAdmin()) { // System admin
			$this->userlevel_id->SetDbValueDef($rsnew, $this->userlevel_id->CurrentValue, NULL, $this->userlevel_id->ReadOnly);
			}

			// Motdepasse
			$this->Motdepasse->SetDbValueDef($rsnew, $this->Motdepasse->CurrentValue, NULL, $this->Motdepasse->ReadOnly || (EW_ENCRYPTED_PASSWORD && $rs->fields('Motdepasse') == $this->Motdepasse->CurrentValue));

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();
		return $EditRow;
	}

	// Show link optionally based on User ID
	function ShowOptionLink($id = "") {
		global $Security;
		if ($Security->IsLoggedIn() && !$Security->IsAdmin() && !$this->UserIDAllow($id))
			return $Security->IsValidUserID($this->IdAgent->CurrentValue);
		return TRUE;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("agentlist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_userlevel_id":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `userlevelid` AS `LinkFld`, `userlevelname` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `userlevels`";
			$sWhereWrk = "";
			$this->userlevel_id->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`userlevelid` = {filter_value}', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->userlevel_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
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
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($agent_edit)) $agent_edit = new cagent_edit();

// Page init
$agent_edit->Page_Init();

// Page main
$agent_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$agent_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fagentedit = new ew_Form("fagentedit", "edit");

// Validate form
fagentedit.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
			elm = this.GetElements("x" + infix + "_MatricAgent");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $agent->MatricAgent->FldCaption(), $agent->MatricAgent->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_NomAgent");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $agent->NomAgent->FldCaption(), $agent->NomAgent->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_SexeAgent");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $agent->SexeAgent->FldCaption(), $agent->SexeAgent->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_TelephoneAgent");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $agent->TelephoneAgent->FldCaption(), $agent->TelephoneAgent->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_userlevel_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $agent->userlevel_id->FldCaption(), $agent->userlevel_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Motdepasse");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $agent->Motdepasse->FldCaption(), $agent->Motdepasse->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Motdepasse");
			if (elm && $(elm).hasClass("ewPasswordStrength") && !$(elm).data("validated"))
				return this.OnError(elm, ewLanguage.Phrase("PasswordTooSimple"));

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
fagentedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fagentedit.ValidateRequired = true;
<?php } else { ?>
fagentedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fagentedit.Lists["x_SexeAgent"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fagentedit.Lists["x_SexeAgent"].Options = <?php echo json_encode($agent->SexeAgent->Options()) ?>;
fagentedit.Lists["x_EtatCivilAgent"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fagentedit.Lists["x_EtatCivilAgent"].Options = <?php echo json_encode($agent->EtatCivilAgent->Options()) ?>;
fagentedit.Lists["x_userlevel_id"] = {"LinkField":"x_userlevelid","Ajax":true,"AutoFill":false,"DisplayFields":["x_userlevelname","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"userlevels"};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$agent_edit->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $agent_edit->ShowPageHeader(); ?>
<?php
$agent_edit->ShowMessage();
?>
<?php if (!$agent_edit->IsModal) { ?>
<form name="ewPagerForm" class="form-horizontal ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($agent_edit->Pager)) $agent_edit->Pager = new cPrevNextPager($agent_edit->StartRec, $agent_edit->DisplayRecs, $agent_edit->TotalRecs) ?>
<?php if ($agent_edit->Pager->RecordCount > 0 && $agent_edit->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($agent_edit->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $agent_edit->PageUrl() ?>start=<?php echo $agent_edit->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($agent_edit->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $agent_edit->PageUrl() ?>start=<?php echo $agent_edit->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $agent_edit->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($agent_edit->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $agent_edit->PageUrl() ?>start=<?php echo $agent_edit->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($agent_edit->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $agent_edit->PageUrl() ?>start=<?php echo $agent_edit->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $agent_edit->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<?php } ?>
<form name="fagentedit" id="fagentedit" class="<?php echo $agent_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($agent_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $agent_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="agent">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($agent_edit->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<!-- Fields to prevent google autofill -->
<input class="hidden" type="text" name="<?php echo ew_Encrypt(ew_Random()) ?>">
<input class="hidden" type="password" name="<?php echo ew_Encrypt(ew_Random()) ?>">
<div>
<?php if ($agent->MatricAgent->Visible) { // MatricAgent ?>
	<div id="r_MatricAgent" class="form-group">
		<label id="elh_agent_MatricAgent" for="x_MatricAgent" class="col-sm-2 control-label ewLabel"><?php echo $agent->MatricAgent->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $agent->MatricAgent->CellAttributes() ?>>
<span id="el_agent_MatricAgent">
<input type="text" data-table="agent" data-field="x_MatricAgent" name="x_MatricAgent" id="x_MatricAgent" size="30" maxlength="3" placeholder="<?php echo ew_HtmlEncode($agent->MatricAgent->getPlaceHolder()) ?>" value="<?php echo $agent->MatricAgent->EditValue ?>"<?php echo $agent->MatricAgent->EditAttributes() ?>>
</span>
<?php echo $agent->MatricAgent->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($agent->NomAgent->Visible) { // NomAgent ?>
	<div id="r_NomAgent" class="form-group">
		<label id="elh_agent_NomAgent" for="x_NomAgent" class="col-sm-2 control-label ewLabel"><?php echo $agent->NomAgent->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $agent->NomAgent->CellAttributes() ?>>
<span id="el_agent_NomAgent">
<input type="text" data-table="agent" data-field="x_NomAgent" name="x_NomAgent" id="x_NomAgent" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($agent->NomAgent->getPlaceHolder()) ?>" value="<?php echo $agent->NomAgent->EditValue ?>"<?php echo $agent->NomAgent->EditAttributes() ?>>
</span>
<?php echo $agent->NomAgent->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($agent->PostnomAgent->Visible) { // PostnomAgent ?>
	<div id="r_PostnomAgent" class="form-group">
		<label id="elh_agent_PostnomAgent" for="x_PostnomAgent" class="col-sm-2 control-label ewLabel"><?php echo $agent->PostnomAgent->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $agent->PostnomAgent->CellAttributes() ?>>
<span id="el_agent_PostnomAgent">
<input type="text" data-table="agent" data-field="x_PostnomAgent" name="x_PostnomAgent" id="x_PostnomAgent" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($agent->PostnomAgent->getPlaceHolder()) ?>" value="<?php echo $agent->PostnomAgent->EditValue ?>"<?php echo $agent->PostnomAgent->EditAttributes() ?>>
</span>
<?php echo $agent->PostnomAgent->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($agent->PrenomAgent->Visible) { // PrenomAgent ?>
	<div id="r_PrenomAgent" class="form-group">
		<label id="elh_agent_PrenomAgent" for="x_PrenomAgent" class="col-sm-2 control-label ewLabel"><?php echo $agent->PrenomAgent->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $agent->PrenomAgent->CellAttributes() ?>>
<span id="el_agent_PrenomAgent">
<input type="text" data-table="agent" data-field="x_PrenomAgent" name="x_PrenomAgent" id="x_PrenomAgent" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($agent->PrenomAgent->getPlaceHolder()) ?>" value="<?php echo $agent->PrenomAgent->EditValue ?>"<?php echo $agent->PrenomAgent->EditAttributes() ?>>
</span>
<?php echo $agent->PrenomAgent->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($agent->SexeAgent->Visible) { // SexeAgent ?>
	<div id="r_SexeAgent" class="form-group">
		<label id="elh_agent_SexeAgent" class="col-sm-2 control-label ewLabel"><?php echo $agent->SexeAgent->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $agent->SexeAgent->CellAttributes() ?>>
<span id="el_agent_SexeAgent">
<div id="tp_x_SexeAgent" class="ewTemplate"><input type="radio" data-table="agent" data-field="x_SexeAgent" data-value-separator="<?php echo $agent->SexeAgent->DisplayValueSeparatorAttribute() ?>" name="x_SexeAgent" id="x_SexeAgent" value="{value}"<?php echo $agent->SexeAgent->EditAttributes() ?>></div>
<div id="dsl_x_SexeAgent" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $agent->SexeAgent->RadioButtonListHtml(FALSE, "x_SexeAgent") ?>
</div></div>
</span>
<?php echo $agent->SexeAgent->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($agent->EtatCivilAgent->Visible) { // EtatCivilAgent ?>
	<div id="r_EtatCivilAgent" class="form-group">
		<label id="elh_agent_EtatCivilAgent" for="x_EtatCivilAgent" class="col-sm-2 control-label ewLabel"><?php echo $agent->EtatCivilAgent->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $agent->EtatCivilAgent->CellAttributes() ?>>
<span id="el_agent_EtatCivilAgent">
<select data-table="agent" data-field="x_EtatCivilAgent" data-value-separator="<?php echo $agent->EtatCivilAgent->DisplayValueSeparatorAttribute() ?>" id="x_EtatCivilAgent" name="x_EtatCivilAgent"<?php echo $agent->EtatCivilAgent->EditAttributes() ?>>
<?php echo $agent->EtatCivilAgent->SelectOptionListHtml("x_EtatCivilAgent") ?>
</select>
</span>
<?php echo $agent->EtatCivilAgent->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($agent->TelephoneAgent->Visible) { // TelephoneAgent ?>
	<div id="r_TelephoneAgent" class="form-group">
		<label id="elh_agent_TelephoneAgent" for="x_TelephoneAgent" class="col-sm-2 control-label ewLabel"><?php echo $agent->TelephoneAgent->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $agent->TelephoneAgent->CellAttributes() ?>>
<span id="el_agent_TelephoneAgent">
<input type="text" data-table="agent" data-field="x_TelephoneAgent" name="x_TelephoneAgent" id="x_TelephoneAgent" size="30" maxlength="13" placeholder="<?php echo ew_HtmlEncode($agent->TelephoneAgent->getPlaceHolder()) ?>" value="<?php echo $agent->TelephoneAgent->EditValue ?>"<?php echo $agent->TelephoneAgent->EditAttributes() ?>>
</span>
<?php echo $agent->TelephoneAgent->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($agent->userlevel_id->Visible) { // userlevel_id ?>
	<div id="r_userlevel_id" class="form-group">
		<label id="elh_agent_userlevel_id" for="x_userlevel_id" class="col-sm-2 control-label ewLabel"><?php echo $agent->userlevel_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $agent->userlevel_id->CellAttributes() ?>>
<?php if (!$Security->IsAdmin() && $Security->IsLoggedIn()) { // Non system admin ?>
<span id="el_agent_userlevel_id">
<p class="form-control-static"><?php echo $agent->userlevel_id->EditValue ?></p>
</span>
<?php } else { ?>
<span id="el_agent_userlevel_id">
<select data-table="agent" data-field="x_userlevel_id" data-value-separator="<?php echo $agent->userlevel_id->DisplayValueSeparatorAttribute() ?>" id="x_userlevel_id" name="x_userlevel_id"<?php echo $agent->userlevel_id->EditAttributes() ?>>
<?php echo $agent->userlevel_id->SelectOptionListHtml("x_userlevel_id") ?>
</select>
<input type="hidden" name="s_x_userlevel_id" id="s_x_userlevel_id" value="<?php echo $agent->userlevel_id->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php echo $agent->userlevel_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($agent->Motdepasse->Visible) { // Motdepasse ?>
	<div id="r_Motdepasse" class="form-group">
		<label id="elh_agent_Motdepasse" for="x_Motdepasse" class="col-sm-2 control-label ewLabel"><?php echo $agent->Motdepasse->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $agent->Motdepasse->CellAttributes() ?>>
<span id="el_agent_Motdepasse">
<div class="input-group" id="ig_Motdepasse">
<input type="password" data-password-strength="pst_Motdepasse" data-password-generated="pgt_Motdepasse" data-table="agent" data-field="x_Motdepasse" name="x_Motdepasse" id="x_Motdepasse" value="<?php echo $agent->Motdepasse->EditValue ?>" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($agent->Motdepasse->getPlaceHolder()) ?>"<?php echo $agent->Motdepasse->EditAttributes() ?>>
<span class="input-group-btn">
	<button type="button" class="btn btn-default ewPasswordGenerator" title="<?php echo ew_HtmlTitle($Language->Phrase("GeneratePassword")) ?>" data-password-field="x_Motdepasse" data-password-confirm="c_Motdepasse" data-password-strength="pst_Motdepasse" data-password-generated="pgt_Motdepasse"><?php echo $Language->Phrase("GeneratePassword") ?></button>
</span>
</div>
<span class="help-block" id="pgt_Motdepasse" style="display: none;"></span>
<div class="progress ewPasswordStrengthBar" id="pst_Motdepasse" style="display: none;">
	<div class="progress-bar" role="progressbar"></div>
</div>
</span>
<?php echo $agent->Motdepasse->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<input type="hidden" data-table="agent" data-field="x_IdAgent" name="x_IdAgent" id="x_IdAgent" value="<?php echo ew_HtmlEncode($agent->IdAgent->CurrentValue) ?>">
<?php if (!$agent_edit->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $agent_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php if (!isset($agent_edit->Pager)) $agent_edit->Pager = new cPrevNextPager($agent_edit->StartRec, $agent_edit->DisplayRecs, $agent_edit->TotalRecs) ?>
<?php if ($agent_edit->Pager->RecordCount > 0 && $agent_edit->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($agent_edit->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $agent_edit->PageUrl() ?>start=<?php echo $agent_edit->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($agent_edit->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $agent_edit->PageUrl() ?>start=<?php echo $agent_edit->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $agent_edit->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($agent_edit->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $agent_edit->PageUrl() ?>start=<?php echo $agent_edit->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($agent_edit->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $agent_edit->PageUrl() ?>start=<?php echo $agent_edit->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $agent_edit->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
<?php } ?>
</form>
<script type="text/javascript">
fagentedit.Init();
</script>
<?php
$agent_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$agent_edit->Page_Terminate();
?>
