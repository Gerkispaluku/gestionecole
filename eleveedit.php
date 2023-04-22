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

$eleve_edit = NULL; // Initialize page object first

class celeve_edit extends celeve {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{84EC96E8-6D54-4BAB-B15E-1EA379B0779B}";

	// Table name
	var $TableName = 'eleve';

	// Page object name
	var $PageObjName = 'eleve_edit';

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

		// Table object (eleve)
		if (!isset($GLOBALS["eleve"]) || get_class($GLOBALS["eleve"]) == "celeve") {
			$GLOBALS["eleve"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["eleve"];
		}

		// Table object (agent)
		if (!isset($GLOBALS['agent'])) $GLOBALS['agent'] = new cagent();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("elevelist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
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
		if (@$_GET["IdEleve"] <> "") {
			$this->IdEleve->setQueryStringValue($_GET["IdEleve"]);
			$this->RecKey["IdEleve"] = $this->IdEleve->QueryStringValue;
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
			$this->Page_Terminate("elevelist.php"); // Return to list page
		} elseif ($bLoadCurrentRecord) { // Load current record position
			$this->SetUpStartRec(); // Set up start record position

			// Point to current record
			if (intval($this->StartRec) <= intval($this->TotalRecs)) {
				$bMatchRecord = TRUE;
				$this->Recordset->Move($this->StartRec-1);
			}
		} else { // Match key values
			while (!$this->Recordset->EOF) {
				if (strval($this->IdEleve->CurrentValue) == strval($this->Recordset->fields('IdEleve'))) {
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
					$this->Page_Terminate("elevelist.php"); // Return to list page
				} else {
					$this->LoadRowValues($this->Recordset); // Load row values
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "elevelist.php")
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
		$this->Image_eleve->Upload->Index = $objForm->Index;
		$this->Image_eleve->Upload->UploadFile();
		$this->Image_eleve->CurrentValue = $this->Image_eleve->Upload->FileName;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->IdEleve->FldIsDetailKey)
			$this->IdEleve->setFormValue($objForm->GetValue("x_IdEleve"));
		if (!$this->MatricEleve->FldIsDetailKey) {
			$this->MatricEleve->setFormValue($objForm->GetValue("x_MatricEleve"));
		}
		if (!$this->NomEleve->FldIsDetailKey) {
			$this->NomEleve->setFormValue($objForm->GetValue("x_NomEleve"));
		}
		if (!$this->PostnomElve->FldIsDetailKey) {
			$this->PostnomElve->setFormValue($objForm->GetValue("x_PostnomElve"));
		}
		if (!$this->PrenomEleve->FldIsDetailKey) {
			$this->PrenomEleve->setFormValue($objForm->GetValue("x_PrenomEleve"));
		}
		if (!$this->SexeEleve->FldIsDetailKey) {
			$this->SexeEleve->setFormValue($objForm->GetValue("x_SexeEleve"));
		}
		if (!$this->DatenaissanceEleve->FldIsDetailKey) {
			$this->DatenaissanceEleve->setFormValue($objForm->GetValue("x_DatenaissanceEleve"));
			$this->DatenaissanceEleve->CurrentValue = ew_UnFormatDateTime($this->DatenaissanceEleve->CurrentValue, 0);
		}
		if (!$this->LieunaissanceEleve->FldIsDetailKey) {
			$this->LieunaissanceEleve->setFormValue($objForm->GetValue("x_LieunaissanceEleve"));
		}
		if (!$this->NomdupereElve->FldIsDetailKey) {
			$this->NomdupereElve->setFormValue($objForm->GetValue("x_NomdupereElve"));
		}
		if (!$this->NomdelamereEleve->FldIsDetailKey) {
			$this->NomdelamereEleve->setFormValue($objForm->GetValue("x_NomdelamereEleve"));
		}
		if (!$this->Description_eleve->FldIsDetailKey) {
			$this->Description_eleve->setFormValue($objForm->GetValue("x_Description_eleve"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->IdEleve->CurrentValue = $this->IdEleve->FormValue;
		$this->MatricEleve->CurrentValue = $this->MatricEleve->FormValue;
		$this->NomEleve->CurrentValue = $this->NomEleve->FormValue;
		$this->PostnomElve->CurrentValue = $this->PostnomElve->FormValue;
		$this->PrenomEleve->CurrentValue = $this->PrenomEleve->FormValue;
		$this->SexeEleve->CurrentValue = $this->SexeEleve->FormValue;
		$this->DatenaissanceEleve->CurrentValue = $this->DatenaissanceEleve->FormValue;
		$this->DatenaissanceEleve->CurrentValue = ew_UnFormatDateTime($this->DatenaissanceEleve->CurrentValue, 0);
		$this->LieunaissanceEleve->CurrentValue = $this->LieunaissanceEleve->FormValue;
		$this->NomdupereElve->CurrentValue = $this->NomdupereElve->FormValue;
		$this->NomdelamereEleve->CurrentValue = $this->NomdelamereEleve->FormValue;
		$this->Description_eleve->CurrentValue = $this->Description_eleve->FormValue;
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

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
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

			// PostnomElve
			$this->PostnomElve->LinkCustomAttributes = "";
			$this->PostnomElve->HrefValue = "";
			$this->PostnomElve->TooltipValue = "";

			// PrenomEleve
			$this->PrenomEleve->LinkCustomAttributes = "";
			$this->PrenomEleve->HrefValue = "";
			$this->PrenomEleve->TooltipValue = "";

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

			// NomdupereElve
			$this->NomdupereElve->LinkCustomAttributes = "";
			$this->NomdupereElve->HrefValue = "";
			$this->NomdupereElve->TooltipValue = "";

			// NomdelamereEleve
			$this->NomdelamereEleve->LinkCustomAttributes = "";
			$this->NomdelamereEleve->HrefValue = "";
			$this->NomdelamereEleve->TooltipValue = "";

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
				$this->Image_eleve->LinkAttrs["data-rel"] = "eleve_x_Image_eleve";
				ew_AppendClass($this->Image_eleve->LinkAttrs["class"], "ewLightbox");
			}

			// Description_eleve
			$this->Description_eleve->LinkCustomAttributes = "";
			$this->Description_eleve->HrefValue = "";
			$this->Description_eleve->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// IdEleve
			$this->IdEleve->EditAttrs["class"] = "form-control";
			$this->IdEleve->EditCustomAttributes = "";
			$this->IdEleve->EditValue = $this->IdEleve->CurrentValue;
			$this->IdEleve->ViewCustomAttributes = "";

			// MatricEleve
			$this->MatricEleve->EditAttrs["class"] = "form-control";
			$this->MatricEleve->EditCustomAttributes = "";
			$this->MatricEleve->EditValue = ew_HtmlEncode($this->MatricEleve->CurrentValue);
			$this->MatricEleve->PlaceHolder = ew_RemoveHtml($this->MatricEleve->FldCaption());

			// NomEleve
			$this->NomEleve->EditAttrs["class"] = "form-control";
			$this->NomEleve->EditCustomAttributes = "";
			$this->NomEleve->EditValue = ew_HtmlEncode($this->NomEleve->CurrentValue);
			$this->NomEleve->PlaceHolder = ew_RemoveHtml($this->NomEleve->FldCaption());

			// PostnomElve
			$this->PostnomElve->EditAttrs["class"] = "form-control";
			$this->PostnomElve->EditCustomAttributes = "";
			$this->PostnomElve->EditValue = ew_HtmlEncode($this->PostnomElve->CurrentValue);
			$this->PostnomElve->PlaceHolder = ew_RemoveHtml($this->PostnomElve->FldCaption());

			// PrenomEleve
			$this->PrenomEleve->EditAttrs["class"] = "form-control";
			$this->PrenomEleve->EditCustomAttributes = "";
			$this->PrenomEleve->EditValue = ew_HtmlEncode($this->PrenomEleve->CurrentValue);
			$this->PrenomEleve->PlaceHolder = ew_RemoveHtml($this->PrenomEleve->FldCaption());

			// SexeEleve
			$this->SexeEleve->EditCustomAttributes = "";
			$this->SexeEleve->EditValue = $this->SexeEleve->Options(FALSE);

			// DatenaissanceEleve
			$this->DatenaissanceEleve->EditAttrs["class"] = "form-control";
			$this->DatenaissanceEleve->EditCustomAttributes = "";
			$this->DatenaissanceEleve->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->DatenaissanceEleve->CurrentValue, 8));
			$this->DatenaissanceEleve->PlaceHolder = ew_RemoveHtml($this->DatenaissanceEleve->FldCaption());

			// LieunaissanceEleve
			$this->LieunaissanceEleve->EditAttrs["class"] = "form-control";
			$this->LieunaissanceEleve->EditCustomAttributes = "";
			$this->LieunaissanceEleve->EditValue = ew_HtmlEncode($this->LieunaissanceEleve->CurrentValue);
			$this->LieunaissanceEleve->PlaceHolder = ew_RemoveHtml($this->LieunaissanceEleve->FldCaption());

			// NomdupereElve
			$this->NomdupereElve->EditAttrs["class"] = "form-control";
			$this->NomdupereElve->EditCustomAttributes = "";
			$this->NomdupereElve->EditValue = ew_HtmlEncode($this->NomdupereElve->CurrentValue);
			$this->NomdupereElve->PlaceHolder = ew_RemoveHtml($this->NomdupereElve->FldCaption());

			// NomdelamereEleve
			$this->NomdelamereEleve->EditAttrs["class"] = "form-control";
			$this->NomdelamereEleve->EditCustomAttributes = "";
			$this->NomdelamereEleve->EditValue = ew_HtmlEncode($this->NomdelamereEleve->CurrentValue);
			$this->NomdelamereEleve->PlaceHolder = ew_RemoveHtml($this->NomdelamereEleve->FldCaption());

			// Image_eleve
			$this->Image_eleve->EditAttrs["class"] = "form-control";
			$this->Image_eleve->EditCustomAttributes = "";
			if (!ew_Empty($this->Image_eleve->Upload->DbValue)) {
				$this->Image_eleve->ImageWidth = 200;
				$this->Image_eleve->ImageHeight = 0;
				$this->Image_eleve->ImageAlt = $this->Image_eleve->FldAlt();
				$this->Image_eleve->EditValue = $this->Image_eleve->Upload->DbValue;
			} else {
				$this->Image_eleve->EditValue = "";
			}
			if (!ew_Empty($this->Image_eleve->CurrentValue))
				$this->Image_eleve->Upload->FileName = $this->Image_eleve->CurrentValue;
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->Image_eleve);

			// Description_eleve
			$this->Description_eleve->EditAttrs["class"] = "form-control";
			$this->Description_eleve->EditCustomAttributes = "";
			$this->Description_eleve->EditValue = ew_HtmlEncode($this->Description_eleve->CurrentValue);
			$this->Description_eleve->PlaceHolder = ew_RemoveHtml($this->Description_eleve->FldCaption());

			// Edit refer script
			// IdEleve

			$this->IdEleve->LinkCustomAttributes = "";
			$this->IdEleve->HrefValue = "";

			// MatricEleve
			$this->MatricEleve->LinkCustomAttributes = "";
			$this->MatricEleve->HrefValue = "";

			// NomEleve
			$this->NomEleve->LinkCustomAttributes = "";
			$this->NomEleve->HrefValue = "";

			// PostnomElve
			$this->PostnomElve->LinkCustomAttributes = "";
			$this->PostnomElve->HrefValue = "";

			// PrenomEleve
			$this->PrenomEleve->LinkCustomAttributes = "";
			$this->PrenomEleve->HrefValue = "";

			// SexeEleve
			$this->SexeEleve->LinkCustomAttributes = "";
			$this->SexeEleve->HrefValue = "";

			// DatenaissanceEleve
			$this->DatenaissanceEleve->LinkCustomAttributes = "";
			$this->DatenaissanceEleve->HrefValue = "";

			// LieunaissanceEleve
			$this->LieunaissanceEleve->LinkCustomAttributes = "";
			$this->LieunaissanceEleve->HrefValue = "";

			// NomdupereElve
			$this->NomdupereElve->LinkCustomAttributes = "";
			$this->NomdupereElve->HrefValue = "";

			// NomdelamereEleve
			$this->NomdelamereEleve->LinkCustomAttributes = "";
			$this->NomdelamereEleve->HrefValue = "";

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

			// Description_eleve
			$this->Description_eleve->LinkCustomAttributes = "";
			$this->Description_eleve->HrefValue = "";
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
		if (!ew_CheckInteger($this->MatricEleve->FormValue)) {
			ew_AddMessage($gsFormError, $this->MatricEleve->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->DatenaissanceEleve->FormValue)) {
			ew_AddMessage($gsFormError, $this->DatenaissanceEleve->FldErrMsg());
		}
		if (!$this->Description_eleve->FldIsDetailKey && !is_null($this->Description_eleve->FormValue) && $this->Description_eleve->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Description_eleve->FldCaption(), $this->Description_eleve->ReqErrMsg));
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

			// MatricEleve
			$this->MatricEleve->SetDbValueDef($rsnew, $this->MatricEleve->CurrentValue, NULL, $this->MatricEleve->ReadOnly);

			// NomEleve
			$this->NomEleve->SetDbValueDef($rsnew, $this->NomEleve->CurrentValue, NULL, $this->NomEleve->ReadOnly);

			// PostnomElve
			$this->PostnomElve->SetDbValueDef($rsnew, $this->PostnomElve->CurrentValue, NULL, $this->PostnomElve->ReadOnly);

			// PrenomEleve
			$this->PrenomEleve->SetDbValueDef($rsnew, $this->PrenomEleve->CurrentValue, NULL, $this->PrenomEleve->ReadOnly);

			// SexeEleve
			$this->SexeEleve->SetDbValueDef($rsnew, $this->SexeEleve->CurrentValue, NULL, $this->SexeEleve->ReadOnly);

			// DatenaissanceEleve
			$this->DatenaissanceEleve->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->DatenaissanceEleve->CurrentValue, 0), NULL, $this->DatenaissanceEleve->ReadOnly);

			// LieunaissanceEleve
			$this->LieunaissanceEleve->SetDbValueDef($rsnew, $this->LieunaissanceEleve->CurrentValue, NULL, $this->LieunaissanceEleve->ReadOnly);

			// NomdupereElve
			$this->NomdupereElve->SetDbValueDef($rsnew, $this->NomdupereElve->CurrentValue, NULL, $this->NomdupereElve->ReadOnly);

			// NomdelamereEleve
			$this->NomdelamereEleve->SetDbValueDef($rsnew, $this->NomdelamereEleve->CurrentValue, NULL, $this->NomdelamereEleve->ReadOnly);

			// Image_eleve
			if ($this->Image_eleve->Visible && !$this->Image_eleve->ReadOnly && !$this->Image_eleve->Upload->KeepFile) {
				$this->Image_eleve->Upload->DbValue = $rsold['Image_eleve']; // Get original value
				if ($this->Image_eleve->Upload->FileName == "") {
					$rsnew['Image_eleve'] = NULL;
				} else {
					$rsnew['Image_eleve'] = $this->Image_eleve->Upload->FileName;
				}
			}

			// Description_eleve
			$this->Description_eleve->SetDbValueDef($rsnew, $this->Description_eleve->CurrentValue, NULL, $this->Description_eleve->ReadOnly);
			if ($this->Image_eleve->Visible && !$this->Image_eleve->Upload->KeepFile) {
				if (!ew_Empty($this->Image_eleve->Upload->Value)) {
					$rsnew['Image_eleve'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->Image_eleve->UploadPath), $rsnew['Image_eleve']); // Get new file name
				}
			}

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
					if ($this->Image_eleve->Visible && !$this->Image_eleve->Upload->KeepFile) {
						if (!ew_Empty($this->Image_eleve->Upload->Value)) {
							if (!$this->Image_eleve->Upload->SaveToFile($this->Image_eleve->UploadPath, $rsnew['Image_eleve'], TRUE)) {
								$this->setFailureMessage($Language->Phrase("UploadErrMsg7"));
								return FALSE;
							}
						}
					}
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

		// Image_eleve
		ew_CleanUploadTempPath($this->Image_eleve, $this->Image_eleve->Upload->Index);
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("elevelist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
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
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($eleve_edit)) $eleve_edit = new celeve_edit();

// Page init
$eleve_edit->Page_Init();

// Page main
$eleve_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$eleve_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = feleveedit = new ew_Form("feleveedit", "edit");

// Validate form
feleveedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_MatricEleve");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($eleve->MatricEleve->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_DatenaissanceEleve");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($eleve->DatenaissanceEleve->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_Description_eleve");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $eleve->Description_eleve->FldCaption(), $eleve->Description_eleve->ReqErrMsg)) ?>");

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
feleveedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
feleveedit.ValidateRequired = true;
<?php } else { ?>
feleveedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
feleveedit.Lists["x_SexeEleve"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
feleveedit.Lists["x_SexeEleve"].Options = <?php echo json_encode($eleve->SexeEleve->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$eleve_edit->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $eleve_edit->ShowPageHeader(); ?>
<?php
$eleve_edit->ShowMessage();
?>
<?php if (!$eleve_edit->IsModal) { ?>
<form name="ewPagerForm" class="form-horizontal ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($eleve_edit->Pager)) $eleve_edit->Pager = new cPrevNextPager($eleve_edit->StartRec, $eleve_edit->DisplayRecs, $eleve_edit->TotalRecs) ?>
<?php if ($eleve_edit->Pager->RecordCount > 0 && $eleve_edit->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($eleve_edit->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $eleve_edit->PageUrl() ?>start=<?php echo $eleve_edit->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($eleve_edit->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $eleve_edit->PageUrl() ?>start=<?php echo $eleve_edit->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $eleve_edit->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($eleve_edit->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $eleve_edit->PageUrl() ?>start=<?php echo $eleve_edit->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($eleve_edit->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $eleve_edit->PageUrl() ?>start=<?php echo $eleve_edit->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $eleve_edit->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<?php } ?>
<form name="feleveedit" id="feleveedit" class="<?php echo $eleve_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($eleve_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $eleve_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="eleve">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($eleve_edit->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($eleve->IdEleve->Visible) { // IdEleve ?>
	<div id="r_IdEleve" class="form-group">
		<label id="elh_eleve_IdEleve" class="col-sm-2 control-label ewLabel"><?php echo $eleve->IdEleve->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $eleve->IdEleve->CellAttributes() ?>>
<span id="el_eleve_IdEleve">
<span<?php echo $eleve->IdEleve->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $eleve->IdEleve->EditValue ?></p></span>
</span>
<input type="hidden" data-table="eleve" data-field="x_IdEleve" name="x_IdEleve" id="x_IdEleve" value="<?php echo ew_HtmlEncode($eleve->IdEleve->CurrentValue) ?>">
<?php echo $eleve->IdEleve->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($eleve->MatricEleve->Visible) { // MatricEleve ?>
	<div id="r_MatricEleve" class="form-group">
		<label id="elh_eleve_MatricEleve" for="x_MatricEleve" class="col-sm-2 control-label ewLabel"><?php echo $eleve->MatricEleve->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $eleve->MatricEleve->CellAttributes() ?>>
<span id="el_eleve_MatricEleve">
<input type="text" data-table="eleve" data-field="x_MatricEleve" name="x_MatricEleve" id="x_MatricEleve" size="30" placeholder="<?php echo ew_HtmlEncode($eleve->MatricEleve->getPlaceHolder()) ?>" value="<?php echo $eleve->MatricEleve->EditValue ?>"<?php echo $eleve->MatricEleve->EditAttributes() ?>>
</span>
<?php echo $eleve->MatricEleve->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($eleve->NomEleve->Visible) { // NomEleve ?>
	<div id="r_NomEleve" class="form-group">
		<label id="elh_eleve_NomEleve" for="x_NomEleve" class="col-sm-2 control-label ewLabel"><?php echo $eleve->NomEleve->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $eleve->NomEleve->CellAttributes() ?>>
<span id="el_eleve_NomEleve">
<input type="text" data-table="eleve" data-field="x_NomEleve" name="x_NomEleve" id="x_NomEleve" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($eleve->NomEleve->getPlaceHolder()) ?>" value="<?php echo $eleve->NomEleve->EditValue ?>"<?php echo $eleve->NomEleve->EditAttributes() ?>>
</span>
<?php echo $eleve->NomEleve->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($eleve->PostnomElve->Visible) { // PostnomElve ?>
	<div id="r_PostnomElve" class="form-group">
		<label id="elh_eleve_PostnomElve" for="x_PostnomElve" class="col-sm-2 control-label ewLabel"><?php echo $eleve->PostnomElve->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $eleve->PostnomElve->CellAttributes() ?>>
<span id="el_eleve_PostnomElve">
<input type="text" data-table="eleve" data-field="x_PostnomElve" name="x_PostnomElve" id="x_PostnomElve" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($eleve->PostnomElve->getPlaceHolder()) ?>" value="<?php echo $eleve->PostnomElve->EditValue ?>"<?php echo $eleve->PostnomElve->EditAttributes() ?>>
</span>
<?php echo $eleve->PostnomElve->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($eleve->PrenomEleve->Visible) { // PrenomEleve ?>
	<div id="r_PrenomEleve" class="form-group">
		<label id="elh_eleve_PrenomEleve" for="x_PrenomEleve" class="col-sm-2 control-label ewLabel"><?php echo $eleve->PrenomEleve->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $eleve->PrenomEleve->CellAttributes() ?>>
<span id="el_eleve_PrenomEleve">
<input type="text" data-table="eleve" data-field="x_PrenomEleve" name="x_PrenomEleve" id="x_PrenomEleve" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($eleve->PrenomEleve->getPlaceHolder()) ?>" value="<?php echo $eleve->PrenomEleve->EditValue ?>"<?php echo $eleve->PrenomEleve->EditAttributes() ?>>
</span>
<?php echo $eleve->PrenomEleve->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($eleve->SexeEleve->Visible) { // SexeEleve ?>
	<div id="r_SexeEleve" class="form-group">
		<label id="elh_eleve_SexeEleve" class="col-sm-2 control-label ewLabel"><?php echo $eleve->SexeEleve->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $eleve->SexeEleve->CellAttributes() ?>>
<span id="el_eleve_SexeEleve">
<div id="tp_x_SexeEleve" class="ewTemplate"><input type="radio" data-table="eleve" data-field="x_SexeEleve" data-value-separator="<?php echo $eleve->SexeEleve->DisplayValueSeparatorAttribute() ?>" name="x_SexeEleve" id="x_SexeEleve" value="{value}"<?php echo $eleve->SexeEleve->EditAttributes() ?>></div>
<div id="dsl_x_SexeEleve" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $eleve->SexeEleve->RadioButtonListHtml(FALSE, "x_SexeEleve") ?>
</div></div>
</span>
<?php echo $eleve->SexeEleve->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($eleve->DatenaissanceEleve->Visible) { // DatenaissanceEleve ?>
	<div id="r_DatenaissanceEleve" class="form-group">
		<label id="elh_eleve_DatenaissanceEleve" for="x_DatenaissanceEleve" class="col-sm-2 control-label ewLabel"><?php echo $eleve->DatenaissanceEleve->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $eleve->DatenaissanceEleve->CellAttributes() ?>>
<span id="el_eleve_DatenaissanceEleve">
<input type="text" data-table="eleve" data-field="x_DatenaissanceEleve" name="x_DatenaissanceEleve" id="x_DatenaissanceEleve" placeholder="<?php echo ew_HtmlEncode($eleve->DatenaissanceEleve->getPlaceHolder()) ?>" value="<?php echo $eleve->DatenaissanceEleve->EditValue ?>"<?php echo $eleve->DatenaissanceEleve->EditAttributes() ?>>
<?php if (!$eleve->DatenaissanceEleve->ReadOnly && !$eleve->DatenaissanceEleve->Disabled && !isset($eleve->DatenaissanceEleve->EditAttrs["readonly"]) && !isset($eleve->DatenaissanceEleve->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("feleveedit", "x_DatenaissanceEleve", 0);
</script>
<?php } ?>
</span>
<?php echo $eleve->DatenaissanceEleve->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($eleve->LieunaissanceEleve->Visible) { // LieunaissanceEleve ?>
	<div id="r_LieunaissanceEleve" class="form-group">
		<label id="elh_eleve_LieunaissanceEleve" for="x_LieunaissanceEleve" class="col-sm-2 control-label ewLabel"><?php echo $eleve->LieunaissanceEleve->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $eleve->LieunaissanceEleve->CellAttributes() ?>>
<span id="el_eleve_LieunaissanceEleve">
<input type="text" data-table="eleve" data-field="x_LieunaissanceEleve" name="x_LieunaissanceEleve" id="x_LieunaissanceEleve" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($eleve->LieunaissanceEleve->getPlaceHolder()) ?>" value="<?php echo $eleve->LieunaissanceEleve->EditValue ?>"<?php echo $eleve->LieunaissanceEleve->EditAttributes() ?>>
</span>
<?php echo $eleve->LieunaissanceEleve->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($eleve->NomdupereElve->Visible) { // NomdupereElve ?>
	<div id="r_NomdupereElve" class="form-group">
		<label id="elh_eleve_NomdupereElve" for="x_NomdupereElve" class="col-sm-2 control-label ewLabel"><?php echo $eleve->NomdupereElve->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $eleve->NomdupereElve->CellAttributes() ?>>
<span id="el_eleve_NomdupereElve">
<input type="text" data-table="eleve" data-field="x_NomdupereElve" name="x_NomdupereElve" id="x_NomdupereElve" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($eleve->NomdupereElve->getPlaceHolder()) ?>" value="<?php echo $eleve->NomdupereElve->EditValue ?>"<?php echo $eleve->NomdupereElve->EditAttributes() ?>>
</span>
<?php echo $eleve->NomdupereElve->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($eleve->NomdelamereEleve->Visible) { // NomdelamereEleve ?>
	<div id="r_NomdelamereEleve" class="form-group">
		<label id="elh_eleve_NomdelamereEleve" for="x_NomdelamereEleve" class="col-sm-2 control-label ewLabel"><?php echo $eleve->NomdelamereEleve->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $eleve->NomdelamereEleve->CellAttributes() ?>>
<span id="el_eleve_NomdelamereEleve">
<input type="text" data-table="eleve" data-field="x_NomdelamereEleve" name="x_NomdelamereEleve" id="x_NomdelamereEleve" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($eleve->NomdelamereEleve->getPlaceHolder()) ?>" value="<?php echo $eleve->NomdelamereEleve->EditValue ?>"<?php echo $eleve->NomdelamereEleve->EditAttributes() ?>>
</span>
<?php echo $eleve->NomdelamereEleve->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($eleve->Image_eleve->Visible) { // Image_eleve ?>
	<div id="r_Image_eleve" class="form-group">
		<label id="elh_eleve_Image_eleve" class="col-sm-2 control-label ewLabel"><?php echo $eleve->Image_eleve->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $eleve->Image_eleve->CellAttributes() ?>>
<span id="el_eleve_Image_eleve">
<div id="fd_x_Image_eleve">
<span title="<?php echo $eleve->Image_eleve->FldTitle() ? $eleve->Image_eleve->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($eleve->Image_eleve->ReadOnly || $eleve->Image_eleve->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="eleve" data-field="x_Image_eleve" name="x_Image_eleve" id="x_Image_eleve"<?php echo $eleve->Image_eleve->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_Image_eleve" id= "fn_x_Image_eleve" value="<?php echo $eleve->Image_eleve->Upload->FileName ?>">
<?php if (@$_POST["fa_x_Image_eleve"] == "0") { ?>
<input type="hidden" name="fa_x_Image_eleve" id= "fa_x_Image_eleve" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_Image_eleve" id= "fa_x_Image_eleve" value="1">
<?php } ?>
<input type="hidden" name="fs_x_Image_eleve" id= "fs_x_Image_eleve" value="25">
<input type="hidden" name="fx_x_Image_eleve" id= "fx_x_Image_eleve" value="<?php echo $eleve->Image_eleve->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_Image_eleve" id= "fm_x_Image_eleve" value="<?php echo $eleve->Image_eleve->UploadMaxFileSize ?>">
</div>
<table id="ft_x_Image_eleve" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $eleve->Image_eleve->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($eleve->Description_eleve->Visible) { // Description_eleve ?>
	<div id="r_Description_eleve" class="form-group">
		<label id="elh_eleve_Description_eleve" class="col-sm-2 control-label ewLabel"><?php echo $eleve->Description_eleve->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $eleve->Description_eleve->CellAttributes() ?>>
<span id="el_eleve_Description_eleve">
<?php ew_AppendClass($eleve->Description_eleve->EditAttrs["class"], "editor"); ?>
<textarea data-table="eleve" data-field="x_Description_eleve" name="x_Description_eleve" id="x_Description_eleve" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($eleve->Description_eleve->getPlaceHolder()) ?>"<?php echo $eleve->Description_eleve->EditAttributes() ?>><?php echo $eleve->Description_eleve->EditValue ?></textarea>
<script type="text/javascript">
ew_CreateEditor("feleveedit", "x_Description_eleve", 35, 4, <?php echo ($eleve->Description_eleve->ReadOnly || FALSE) ? "true" : "false" ?>);
</script>
</span>
<?php echo $eleve->Description_eleve->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$eleve_edit->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $eleve_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php if (!isset($eleve_edit->Pager)) $eleve_edit->Pager = new cPrevNextPager($eleve_edit->StartRec, $eleve_edit->DisplayRecs, $eleve_edit->TotalRecs) ?>
<?php if ($eleve_edit->Pager->RecordCount > 0 && $eleve_edit->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($eleve_edit->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $eleve_edit->PageUrl() ?>start=<?php echo $eleve_edit->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($eleve_edit->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $eleve_edit->PageUrl() ?>start=<?php echo $eleve_edit->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $eleve_edit->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($eleve_edit->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $eleve_edit->PageUrl() ?>start=<?php echo $eleve_edit->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($eleve_edit->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $eleve_edit->PageUrl() ?>start=<?php echo $eleve_edit->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $eleve_edit->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
<?php } ?>
</form>
<script type="text/javascript">
feleveedit.Init();
</script>
<?php
$eleve_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$eleve_edit->Page_Terminate();
?>
