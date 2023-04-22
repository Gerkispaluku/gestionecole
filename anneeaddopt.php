<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "anneeinfo.php" ?>
<?php include_once "agentinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$annee_addopt = NULL; // Initialize page object first

class cannee_addopt extends cannee {

	// Page ID
	var $PageID = 'addopt';

	// Project ID
	var $ProjectID = "{84EC96E8-6D54-4BAB-B15E-1EA379B0779B}";

	// Table name
	var $TableName = 'annee';

	// Page object name
	var $PageObjName = 'annee_addopt';

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

		// Table object (annee)
		if (!isset($GLOBALS["annee"]) || get_class($GLOBALS["annee"]) == "cannee") {
			$GLOBALS["annee"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["annee"];
		}

		// Table object (agent)
		if (!isset($GLOBALS['agent'])) $GLOBALS['agent'] = new cagent();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'addopt', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'annee', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("anneelist.php"));
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
		$this->LibelleAnnee->SetVisibility();
		$this->DatedebutAnnee->SetVisibility();
		$this->DatefinAnnee->SetVisibility();

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
		global $EW_EXPORT, $annee;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($annee);
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

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;
		set_error_handler("ew_ErrorHandler");

		// Set up Breadcrumb
		//$this->SetupBreadcrumb(); // Not used
		// Process form if post back

		if ($objForm->GetValue("a_addopt") <> "") {
			$this->CurrentAction = $objForm->GetValue("a_addopt"); // Get form action
			$this->LoadFormValues(); // Load form values

			// Validate form
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->setFailureMessage($gsFormError);
			}
		} else { // Not post back
			$this->CurrentAction = "I"; // Display blank record
			$this->LoadDefaultValues(); // Load default values
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow()) { // Add successful
					$row = array();
					$row["x_IdAnnee"] = $this->IdAnnee->DbValue;
					$row["x_LibelleAnnee"] = $this->LibelleAnnee->DbValue;
					$row["x_DatedebutAnnee"] = $this->DatedebutAnnee->DbValue;
					$row["x_DatefinAnnee"] = $this->DatefinAnnee->DbValue;
					if (!EW_DEBUG_ENABLED && ob_get_length())
						ob_end_clean();
					echo ew_ArrayToJson(array($row));
				} else {
					$this->ShowMessage();
				}
				$this->Page_Terminate();
				exit();
		}

		// Render row
		$this->RowType = EW_ROWTYPE_ADD; // Render add type
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
		$this->LibelleAnnee->CurrentValue = NULL;
		$this->LibelleAnnee->OldValue = $this->LibelleAnnee->CurrentValue;
		$this->DatedebutAnnee->CurrentValue = NULL;
		$this->DatedebutAnnee->OldValue = $this->DatedebutAnnee->CurrentValue;
		$this->DatefinAnnee->CurrentValue = NULL;
		$this->DatefinAnnee->OldValue = $this->DatefinAnnee->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->LibelleAnnee->FldIsDetailKey) {
			$this->LibelleAnnee->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_LibelleAnnee")));
		}
		if (!$this->DatedebutAnnee->FldIsDetailKey) {
			$this->DatedebutAnnee->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_DatedebutAnnee")));
			$this->DatedebutAnnee->CurrentValue = ew_UnFormatDateTime($this->DatedebutAnnee->CurrentValue, 0);
		}
		if (!$this->DatefinAnnee->FldIsDetailKey) {
			$this->DatefinAnnee->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_DatefinAnnee")));
			$this->DatefinAnnee->CurrentValue = ew_UnFormatDateTime($this->DatefinAnnee->CurrentValue, 0);
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LibelleAnnee->CurrentValue = ew_ConvertToUtf8($this->LibelleAnnee->FormValue);
		$this->DatedebutAnnee->CurrentValue = ew_ConvertToUtf8($this->DatedebutAnnee->FormValue);
		$this->DatedebutAnnee->CurrentValue = ew_UnFormatDateTime($this->DatedebutAnnee->CurrentValue, 0);
		$this->DatefinAnnee->CurrentValue = ew_ConvertToUtf8($this->DatefinAnnee->FormValue);
		$this->DatefinAnnee->CurrentValue = ew_UnFormatDateTime($this->DatefinAnnee->CurrentValue, 0);
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
		$this->IdAnnee->setDbValue($rs->fields('IdAnnee'));
		$this->LibelleAnnee->setDbValue($rs->fields('LibelleAnnee'));
		$this->DatedebutAnnee->setDbValue($rs->fields('DatedebutAnnee'));
		$this->DatefinAnnee->setDbValue($rs->fields('DatefinAnnee'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->IdAnnee->DbValue = $row['IdAnnee'];
		$this->LibelleAnnee->DbValue = $row['LibelleAnnee'];
		$this->DatedebutAnnee->DbValue = $row['DatedebutAnnee'];
		$this->DatefinAnnee->DbValue = $row['DatefinAnnee'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// IdAnnee
		// LibelleAnnee
		// DatedebutAnnee
		// DatefinAnnee

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// IdAnnee
		$this->IdAnnee->ViewValue = $this->IdAnnee->CurrentValue;
		$this->IdAnnee->ViewCustomAttributes = "";

		// LibelleAnnee
		$this->LibelleAnnee->ViewValue = $this->LibelleAnnee->CurrentValue;
		$this->LibelleAnnee->ViewCustomAttributes = "";

		// DatedebutAnnee
		$this->DatedebutAnnee->ViewValue = $this->DatedebutAnnee->CurrentValue;
		$this->DatedebutAnnee->ViewValue = ew_FormatDateTime($this->DatedebutAnnee->ViewValue, 0);
		$this->DatedebutAnnee->ViewCustomAttributes = "";

		// DatefinAnnee
		$this->DatefinAnnee->ViewValue = $this->DatefinAnnee->CurrentValue;
		$this->DatefinAnnee->ViewValue = ew_FormatDateTime($this->DatefinAnnee->ViewValue, 0);
		$this->DatefinAnnee->ViewCustomAttributes = "";

			// LibelleAnnee
			$this->LibelleAnnee->LinkCustomAttributes = "";
			$this->LibelleAnnee->HrefValue = "";
			$this->LibelleAnnee->TooltipValue = "";

			// DatedebutAnnee
			$this->DatedebutAnnee->LinkCustomAttributes = "";
			$this->DatedebutAnnee->HrefValue = "";
			$this->DatedebutAnnee->TooltipValue = "";

			// DatefinAnnee
			$this->DatefinAnnee->LinkCustomAttributes = "";
			$this->DatefinAnnee->HrefValue = "";
			$this->DatefinAnnee->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// LibelleAnnee
			$this->LibelleAnnee->EditAttrs["class"] = "form-control";
			$this->LibelleAnnee->EditCustomAttributes = "";
			$this->LibelleAnnee->EditValue = ew_HtmlEncode($this->LibelleAnnee->CurrentValue);
			$this->LibelleAnnee->PlaceHolder = ew_RemoveHtml($this->LibelleAnnee->FldCaption());

			// DatedebutAnnee
			$this->DatedebutAnnee->EditAttrs["class"] = "form-control";
			$this->DatedebutAnnee->EditCustomAttributes = "";
			$this->DatedebutAnnee->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->DatedebutAnnee->CurrentValue, 8));
			$this->DatedebutAnnee->PlaceHolder = ew_RemoveHtml($this->DatedebutAnnee->FldCaption());

			// DatefinAnnee
			$this->DatefinAnnee->EditAttrs["class"] = "form-control";
			$this->DatefinAnnee->EditCustomAttributes = "";
			$this->DatefinAnnee->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->DatefinAnnee->CurrentValue, 8));
			$this->DatefinAnnee->PlaceHolder = ew_RemoveHtml($this->DatefinAnnee->FldCaption());

			// Add refer script
			// LibelleAnnee

			$this->LibelleAnnee->LinkCustomAttributes = "";
			$this->LibelleAnnee->HrefValue = "";

			// DatedebutAnnee
			$this->DatedebutAnnee->LinkCustomAttributes = "";
			$this->DatedebutAnnee->HrefValue = "";

			// DatefinAnnee
			$this->DatefinAnnee->LinkCustomAttributes = "";
			$this->DatefinAnnee->HrefValue = "";
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
		if (!$this->DatedebutAnnee->FldIsDetailKey && !is_null($this->DatedebutAnnee->FormValue) && $this->DatedebutAnnee->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->DatedebutAnnee->FldCaption(), $this->DatedebutAnnee->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->DatedebutAnnee->FormValue)) {
			ew_AddMessage($gsFormError, $this->DatedebutAnnee->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->DatefinAnnee->FormValue)) {
			ew_AddMessage($gsFormError, $this->DatefinAnnee->FldErrMsg());
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

		// LibelleAnnee
		$this->LibelleAnnee->SetDbValueDef($rsnew, $this->LibelleAnnee->CurrentValue, NULL, FALSE);

		// DatedebutAnnee
		$this->DatedebutAnnee->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->DatedebutAnnee->CurrentValue, 0), NULL, FALSE);

		// DatefinAnnee
		$this->DatefinAnnee->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->DatefinAnnee->CurrentValue, 0), NULL, FALSE);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("anneelist.php"), "", $this->TableVar, TRUE);
		$PageId = "addopt";
		$Breadcrumb->Add("addopt", $PageId, $url);
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

	// Custom validate event
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
if (!isset($annee_addopt)) $annee_addopt = new cannee_addopt();

// Page init
$annee_addopt->Page_Init();

// Page main
$annee_addopt->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$annee_addopt->Page_Render();
?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "addopt";
var CurrentForm = fanneeaddopt = new ew_Form("fanneeaddopt", "addopt");

// Validate form
fanneeaddopt.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_DatedebutAnnee");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $annee->DatedebutAnnee->FldCaption(), $annee->DatedebutAnnee->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_DatedebutAnnee");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($annee->DatedebutAnnee->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_DatefinAnnee");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($annee->DatefinAnnee->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}
	return true;
}

// Form_CustomValidate event
fanneeaddopt.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fanneeaddopt.ValidateRequired = true;
<?php } else { ?>
fanneeaddopt.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php
$annee_addopt->ShowMessage();
?>
<form name="fanneeaddopt" id="fanneeaddopt" class="ewForm form-horizontal" action="anneeaddopt.php" method="post">
<?php if ($annee_addopt->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $annee_addopt->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="annee">
<input type="hidden" name="a_addopt" id="a_addopt" value="A">
<?php if ($annee->LibelleAnnee->Visible) { // LibelleAnnee ?>
	<div class="form-group">
		<label class="col-sm-3 control-label ewLabel" for="x_LibelleAnnee"><?php echo $annee->LibelleAnnee->FldCaption() ?></label>
		<div class="col-sm-9">
<input type="text" data-table="annee" data-field="x_LibelleAnnee" name="x_LibelleAnnee" id="x_LibelleAnnee" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($annee->LibelleAnnee->getPlaceHolder()) ?>" value="<?php echo $annee->LibelleAnnee->EditValue ?>"<?php echo $annee->LibelleAnnee->EditAttributes() ?>>
</div>
	</div>
<?php } ?>	
<?php if ($annee->DatedebutAnnee->Visible) { // DatedebutAnnee ?>
	<div class="form-group">
		<label class="col-sm-3 control-label ewLabel" for="x_DatedebutAnnee"><?php echo $annee->DatedebutAnnee->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-9">
<input type="text" data-table="annee" data-field="x_DatedebutAnnee" name="x_DatedebutAnnee" id="x_DatedebutAnnee" placeholder="<?php echo ew_HtmlEncode($annee->DatedebutAnnee->getPlaceHolder()) ?>" value="<?php echo $annee->DatedebutAnnee->EditValue ?>"<?php echo $annee->DatedebutAnnee->EditAttributes() ?>>
<?php if (!$annee->DatedebutAnnee->ReadOnly && !$annee->DatedebutAnnee->Disabled && !isset($annee->DatedebutAnnee->EditAttrs["readonly"]) && !isset($annee->DatedebutAnnee->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fanneeaddopt", "x_DatedebutAnnee", 0);
</script>
<?php } ?>
</div>
	</div>
<?php } ?>	
<?php if ($annee->DatefinAnnee->Visible) { // DatefinAnnee ?>
	<div class="form-group">
		<label class="col-sm-3 control-label ewLabel" for="x_DatefinAnnee"><?php echo $annee->DatefinAnnee->FldCaption() ?></label>
		<div class="col-sm-9">
<input type="text" data-table="annee" data-field="x_DatefinAnnee" name="x_DatefinAnnee" id="x_DatefinAnnee" placeholder="<?php echo ew_HtmlEncode($annee->DatefinAnnee->getPlaceHolder()) ?>" value="<?php echo $annee->DatefinAnnee->EditValue ?>"<?php echo $annee->DatefinAnnee->EditAttributes() ?>>
<?php if (!$annee->DatefinAnnee->ReadOnly && !$annee->DatefinAnnee->Disabled && !isset($annee->DatefinAnnee->EditAttrs["readonly"]) && !isset($annee->DatefinAnnee->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fanneeaddopt", "x_DatefinAnnee", 0);
</script>
<?php } ?>
</div>
	</div>
<?php } ?>	
</form>
<script type="text/javascript">
fanneeaddopt.Init();
</script>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php
$annee_addopt->Page_Terminate();
?>
