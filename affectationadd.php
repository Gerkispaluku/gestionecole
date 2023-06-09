<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "affectationinfo.php" ?>
<?php include_once "agentinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$affectation_add = NULL; // Initialize page object first

class caffectation_add extends caffectation {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{84EC96E8-6D54-4BAB-B15E-1EA379B0779B}";

	// Table name
	var $TableName = 'affectation';

	// Page object name
	var $PageObjName = 'affectation_add';

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

		// Table object (affectation)
		if (!isset($GLOBALS["affectation"]) || get_class($GLOBALS["affectation"]) == "caffectation") {
			$GLOBALS["affectation"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["affectation"];
		}

		// Table object (agent)
		if (!isset($GLOBALS['agent'])) $GLOBALS['agent'] = new cagent();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'affectation', TRUE);

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
		if (!$Security->CanAdd()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("affectationlist.php"));
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
		$this->IdAffectation->SetVisibility();
		$this->IdAgent->SetVisibility();

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
		global $EW_EXPORT, $affectation;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($affectation);
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
	var $FormClassName = "form-horizontal ewForm ewAddForm";
	var $IsModal = FALSE;
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

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

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = FALSE;
			$this->LoadFormValues(); // Load form values
		} else { // Not post back
			$this->CopyRecord = FALSE;
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
			}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		} else {
			if ($this->CurrentAction == "I") // Load default values for blank record
				$this->LoadDefaultValues();
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("affectationlist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "affectationlist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "affectationview.php")
						$sReturnUrl = $this->GetViewUrl(); // View page, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD; // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->IdAffectation->CurrentValue = NULL;
		$this->IdAffectation->OldValue = $this->IdAffectation->CurrentValue;
		$this->IdAgent->CurrentValue = NULL;
		$this->IdAgent->OldValue = $this->IdAgent->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->IdAffectation->FldIsDetailKey) {
			$this->IdAffectation->setFormValue($objForm->GetValue("x_IdAffectation"));
		}
		if (!$this->IdAgent->FldIsDetailKey) {
			$this->IdAgent->setFormValue($objForm->GetValue("x_IdAgent"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->IdAffectation->CurrentValue = $this->IdAffectation->FormValue;
		$this->IdAgent->CurrentValue = $this->IdAgent->FormValue;
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
		$this->IdAffectation->setDbValue($rs->fields('IdAffectation'));
		$this->IdAgent->setDbValue($rs->fields('IdAgent'));
		if (array_key_exists('EV__IdAgent', $rs->fields)) {
			$this->IdAgent->VirtualValue = $rs->fields('EV__IdAgent'); // Set up virtual field value
		} else {
			$this->IdAgent->VirtualValue = ""; // Clear value
		}
		$this->IdFonction->setDbValue($rs->fields('IdFonction'));
		if (array_key_exists('EV__IdFonction', $rs->fields)) {
			$this->IdFonction->VirtualValue = $rs->fields('EV__IdFonction'); // Set up virtual field value
		} else {
			$this->IdFonction->VirtualValue = ""; // Clear value
		}
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->IdAffectation->DbValue = $row['IdAffectation'];
		$this->IdAgent->DbValue = $row['IdAgent'];
		$this->IdFonction->DbValue = $row['IdFonction'];
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
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// IdAffectation
		// IdAgent
		// IdFonction

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// IdAffectation
		$this->IdAffectation->ViewValue = $this->IdAffectation->CurrentValue;
		$this->IdAffectation->ViewCustomAttributes = "";

		// IdAgent
		if ($this->IdAgent->VirtualValue <> "") {
			$this->IdAgent->ViewValue = $this->IdAgent->VirtualValue;
		} else {
			$this->IdAgent->ViewValue = $this->IdAgent->CurrentValue;
		if (strval($this->IdAgent->CurrentValue) <> "") {
			$sFilterWrk = "`IdAgent`" . ew_SearchString("=", $this->IdAgent->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `IdAgent`, `MatricAgent` AS `DispFld`, `NomAgent` AS `Disp2Fld`, `PostnomAgent` AS `Disp3Fld`, `PrenomAgent` AS `Disp4Fld` FROM `agent`";
		$sWhereWrk = "";
		$this->IdAgent->LookupFilters = array("dx1" => '`MatricAgent`', "dx2" => '`NomAgent`', "dx3" => '`PostnomAgent`', "dx4" => '`PrenomAgent`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->IdAgent, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$arwrk[4] = $rswrk->fields('Disp4Fld');
				$this->IdAgent->ViewValue = $this->IdAgent->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->IdAgent->ViewValue = $this->IdAgent->CurrentValue;
			}
		} else {
			$this->IdAgent->ViewValue = NULL;
		}
		}
		$this->IdAgent->ViewCustomAttributes = "";

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

			// IdAffectation
			$this->IdAffectation->LinkCustomAttributes = "";
			$this->IdAffectation->HrefValue = "";
			$this->IdAffectation->TooltipValue = "";

			// IdAgent
			$this->IdAgent->LinkCustomAttributes = "";
			$this->IdAgent->HrefValue = "";
			$this->IdAgent->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// IdAffectation
			$this->IdAffectation->EditAttrs["class"] = "form-control";
			$this->IdAffectation->EditCustomAttributes = "";
			$this->IdAffectation->EditValue = ew_HtmlEncode($this->IdAffectation->CurrentValue);
			$this->IdAffectation->PlaceHolder = ew_RemoveHtml($this->IdAffectation->FldCaption());

			// IdAgent
			$this->IdAgent->EditAttrs["class"] = "form-control";
			$this->IdAgent->EditCustomAttributes = "";
			$this->IdAgent->EditValue = ew_HtmlEncode($this->IdAgent->CurrentValue);
			if (strval($this->IdAgent->CurrentValue) <> "") {
				$sFilterWrk = "`IdAgent`" . ew_SearchString("=", $this->IdAgent->CurrentValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `IdAgent`, `MatricAgent` AS `DispFld`, `NomAgent` AS `Disp2Fld`, `PostnomAgent` AS `Disp3Fld`, `PrenomAgent` AS `Disp4Fld` FROM `agent`";
			$sWhereWrk = "";
			$this->IdAgent->LookupFilters = array("dx1" => '`MatricAgent`', "dx2" => '`NomAgent`', "dx3" => '`PostnomAgent`', "dx4" => '`PrenomAgent`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->IdAgent, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
					$arwrk[3] = ew_HtmlEncode($rswrk->fields('Disp3Fld'));
					$arwrk[4] = ew_HtmlEncode($rswrk->fields('Disp4Fld'));
					$this->IdAgent->EditValue = $this->IdAgent->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->IdAgent->EditValue = ew_HtmlEncode($this->IdAgent->CurrentValue);
				}
			} else {
				$this->IdAgent->EditValue = NULL;
			}
			$this->IdAgent->PlaceHolder = ew_RemoveHtml($this->IdAgent->FldCaption());

			// Add refer script
			// IdAffectation

			$this->IdAffectation->LinkCustomAttributes = "";
			$this->IdAffectation->HrefValue = "";

			// IdAgent
			$this->IdAgent->LinkCustomAttributes = "";
			$this->IdAgent->HrefValue = "";
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
		if (!$this->IdAffectation->FldIsDetailKey && !is_null($this->IdAffectation->FormValue) && $this->IdAffectation->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->IdAffectation->FldCaption(), $this->IdAffectation->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->IdAffectation->FormValue)) {
			ew_AddMessage($gsFormError, $this->IdAffectation->FldErrMsg());
		}
		if (!$this->IdAgent->FldIsDetailKey && !is_null($this->IdAgent->FormValue) && $this->IdAgent->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->IdAgent->FldCaption(), $this->IdAgent->ReqErrMsg));
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

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		$conn = &$this->Connection();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// IdAffectation
		$this->IdAffectation->SetDbValueDef($rsnew, $this->IdAffectation->CurrentValue, 0, FALSE);

		// IdAgent
		$this->IdAgent->SetDbValueDef($rsnew, $this->IdAgent->CurrentValue, 0, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("affectationlist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_IdAgent":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `IdAgent` AS `LinkFld`, `MatricAgent` AS `DispFld`, `NomAgent` AS `Disp2Fld`, `PostnomAgent` AS `Disp3Fld`, `PrenomAgent` AS `Disp4Fld` FROM `agent`";
			$sWhereWrk = "{filter}";
			$this->IdAgent->LookupFilters = array("dx1" => '`MatricAgent`', "dx2" => '`NomAgent`', "dx3" => '`PostnomAgent`', "dx4" => '`PrenomAgent`');
			if (!$GLOBALS["affectation"]->UserIDAllow("add")) $sWhereWrk = $GLOBALS["agent"]->AddUserIDFilter($sWhereWrk);
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`IdAgent` = {filter_value}', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->IdAgent, $sWhereWrk); // Call Lookup selecting
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
		case "x_IdAgent":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `IdAgent`, `MatricAgent` AS `DispFld`, `NomAgent` AS `Disp2Fld`, `PostnomAgent` AS `Disp3Fld`, `PrenomAgent` AS `Disp4Fld` FROM `agent`";
			$sWhereWrk = "`MatricAgent` LIKE '{query_value}%' OR CONCAT(`MatricAgent`,'" . ew_ValueSeparator(1, $this->IdAgent) . "',`NomAgent`,'" . ew_ValueSeparator(2, $this->IdAgent) . "',`PostnomAgent`,'" . ew_ValueSeparator(3, $this->IdAgent) . "',`PrenomAgent`) LIKE '{query_value}%'";
			$this->IdAgent->LookupFilters = array("dx1" => '`MatricAgent`', "dx2" => '`NomAgent`', "dx3" => '`PostnomAgent`', "dx4" => '`PrenomAgent`');
			if (!$GLOBALS["affectation"]->UserIDAllow("add")) $sWhereWrk = $GLOBALS["agent"]->AddUserIDFilter($sWhereWrk);
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->IdAgent, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
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
if (!isset($affectation_add)) $affectation_add = new caffectation_add();

// Page init
$affectation_add->Page_Init();

// Page main
$affectation_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$affectation_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = faffectationadd = new ew_Form("faffectationadd", "add");

// Validate form
faffectationadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_IdAffectation");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $affectation->IdAffectation->FldCaption(), $affectation->IdAffectation->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_IdAffectation");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($affectation->IdAffectation->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_IdAgent");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $affectation->IdAgent->FldCaption(), $affectation->IdAgent->ReqErrMsg)) ?>");

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
faffectationadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
faffectationadd.ValidateRequired = true;
<?php } else { ?>
faffectationadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
faffectationadd.Lists["x_IdAgent"] = {"LinkField":"x_IdAgent","Ajax":true,"AutoFill":false,"DisplayFields":["x_MatricAgent","x_NomAgent","x_PostnomAgent","x_PrenomAgent"],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"agent"};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$affectation_add->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $affectation_add->ShowPageHeader(); ?>
<?php
$affectation_add->ShowMessage();
?>
<form name="faffectationadd" id="faffectationadd" class="<?php echo $affectation_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($affectation_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $affectation_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="affectation">
<input type="hidden" name="a_add" id="a_add" value="A">
<?php if ($affectation_add->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($affectation->IdAffectation->Visible) { // IdAffectation ?>
	<div id="r_IdAffectation" class="form-group">
		<label id="elh_affectation_IdAffectation" for="x_IdAffectation" class="col-sm-2 control-label ewLabel"><?php echo $affectation->IdAffectation->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $affectation->IdAffectation->CellAttributes() ?>>
<span id="el_affectation_IdAffectation">
<input type="text" data-table="affectation" data-field="x_IdAffectation" name="x_IdAffectation" id="x_IdAffectation" size="30" placeholder="<?php echo ew_HtmlEncode($affectation->IdAffectation->getPlaceHolder()) ?>" value="<?php echo $affectation->IdAffectation->EditValue ?>"<?php echo $affectation->IdAffectation->EditAttributes() ?>>
</span>
<?php echo $affectation->IdAffectation->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($affectation->IdAgent->Visible) { // IdAgent ?>
	<div id="r_IdAgent" class="form-group">
		<label id="elh_affectation_IdAgent" class="col-sm-2 control-label ewLabel"><?php echo $affectation->IdAgent->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $affectation->IdAgent->CellAttributes() ?>>
<span id="el_affectation_IdAgent">
<?php
$wrkonchange = trim(" " . @$affectation->IdAgent->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$affectation->IdAgent->EditAttrs["onchange"] = "";
?>
<span id="as_x_IdAgent" style="white-space: nowrap; z-index: 8980">
	<input type="text" name="sv_x_IdAgent" id="sv_x_IdAgent" value="<?php echo $affectation->IdAgent->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($affectation->IdAgent->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($affectation->IdAgent->getPlaceHolder()) ?>"<?php echo $affectation->IdAgent->EditAttributes() ?>>
</span>
<input type="hidden" data-table="affectation" data-field="x_IdAgent" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $affectation->IdAgent->DisplayValueSeparatorAttribute() ?>" name="x_IdAgent" id="x_IdAgent" value="<?php echo ew_HtmlEncode($affectation->IdAgent->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<input type="hidden" name="q_x_IdAgent" id="q_x_IdAgent" value="<?php echo $affectation->IdAgent->LookupFilterQuery(true) ?>">
<script type="text/javascript">
faffectationadd.CreateAutoSuggest({"id":"x_IdAgent","forceSelect":true});
</script>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($affectation->IdAgent->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_IdAgent',m:0,n:10,srch:false});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" name="s_x_IdAgent" id="s_x_IdAgent" value="<?php echo $affectation->IdAgent->LookupFilterQuery(false) ?>">
<?php if (AllowAdd(CurrentProjectID() . "agent")) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $affectation->IdAgent->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_IdAgent',url:'agentaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_IdAgent"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $affectation->IdAgent->FldCaption() ?></span></button>
<?php } ?>
<input type="hidden" name="s_x_IdAgent" id="s_x_IdAgent" value="<?php echo $affectation->IdAgent->LookupFilterQuery() ?>">
</span>
<?php echo $affectation->IdAgent->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$affectation_add->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $affectation_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
faffectationadd.Init();
</script>
<?php
$affectation_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$affectation_add->Page_Terminate();
?>
