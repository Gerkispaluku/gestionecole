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

$annee_update = NULL; // Initialize page object first

class cannee_update extends cannee {

	// Page ID
	var $PageID = 'update';

	// Project ID
	var $ProjectID = "{84EC96E8-6D54-4BAB-B15E-1EA379B0779B}";

	// Table name
	var $TableName = 'annee';

	// Page object name
	var $PageObjName = 'annee_update';

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
			define("EW_PAGE_ID", 'update', TRUE);

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
		if (!$Security->CanEdit()) {
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
	var $FormClassName = "form-horizontal ewForm ewUpdateForm";
	var $IsModal = FALSE;
	var $RecKeys;
	var $Disabled;
	var $Recordset;
	var $UpdateCount = 0;

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

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Try to load keys from list form
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		if (@$_POST["a_update"] <> "") {

			// Get action
			$this->CurrentAction = $_POST["a_update"];
			$this->LoadFormValues(); // Get form values

			// Validate form
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->setFailureMessage($gsFormError);
			}
		} else {
			$this->LoadMultiUpdateValues(); // Load initial values to form
		}
		if (count($this->RecKeys) <= 0)
			$this->Page_Terminate("anneelist.php"); // No records selected, return to list
		switch ($this->CurrentAction) {
			case "U": // Update
				if ($this->UpdateRows()) { // Update Records based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Set up update success message
					$this->Page_Terminate($this->getReturnUrl()); // Return to caller
				} else {
					$this->RestoreFormValues(); // Restore form values
				}
		}

		// Render row
		$this->RowType = EW_ROWTYPE_EDIT; // Render edit
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Load initial values to form if field values are identical in all selected records
	function LoadMultiUpdateValues() {
		$this->CurrentFilter = $this->GetKeyFilter();

		// Load recordset
		if ($this->Recordset = $this->LoadRecordset()) {
			$i = 1;
			while (!$this->Recordset->EOF) {
				if ($i == 1) {
					$this->LibelleAnnee->setDbValue($this->Recordset->fields('LibelleAnnee'));
					$this->DatedebutAnnee->setDbValue($this->Recordset->fields('DatedebutAnnee'));
					$this->DatefinAnnee->setDbValue($this->Recordset->fields('DatefinAnnee'));
				} else {
					if (!ew_CompareValue($this->LibelleAnnee->DbValue, $this->Recordset->fields('LibelleAnnee')))
						$this->LibelleAnnee->CurrentValue = NULL;
					if (!ew_CompareValue($this->DatedebutAnnee->DbValue, $this->Recordset->fields('DatedebutAnnee')))
						$this->DatedebutAnnee->CurrentValue = NULL;
					if (!ew_CompareValue($this->DatefinAnnee->DbValue, $this->Recordset->fields('DatefinAnnee')))
						$this->DatefinAnnee->CurrentValue = NULL;
				}
				$i++;
				$this->Recordset->MoveNext();
			}
			$this->Recordset->Close();
		}
	}

	// Set up key value
	function SetupKeyValues($key) {
		$sKeyFld = $key;
		if (!is_numeric($sKeyFld))
			return FALSE;
		$this->IdAnnee->CurrentValue = $sKeyFld;
		return TRUE;
	}

	// Update all selected rows
	function UpdateRows() {
		global $Language;
		$conn = &$this->Connection();
		$conn->BeginTrans();

		// Get old recordset
		$this->CurrentFilter = $this->GetKeyFilter();
		$sSql = $this->SQL();
		$rsold = $conn->Execute($sSql);

		// Update all rows
		$sKey = "";
		foreach ($this->RecKeys as $key) {
			if ($this->SetupKeyValues($key)) {
				$sThisKey = $key;
				$this->SendEmail = FALSE; // Do not send email on update success
				$this->UpdateCount += 1; // Update record count for records being updated
				$UpdateRows = $this->EditRow(); // Update this row
			} else {
				$UpdateRows = FALSE;
			}
			if (!$UpdateRows)
				break; // Update failed
			if ($sKey <> "") $sKey .= ", ";
			$sKey .= $sThisKey;
		}

		// Check if all rows updated
		if ($UpdateRows) {
			$conn->CommitTrans(); // Commit transaction

			// Get new recordset
			$rsnew = $conn->Execute($sSql);
		} else {
			$conn->RollbackTrans(); // Rollback transaction
		}
		return $UpdateRows;
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
		if (!$this->LibelleAnnee->FldIsDetailKey) {
			$this->LibelleAnnee->setFormValue($objForm->GetValue("x_LibelleAnnee"));
		}
		$this->LibelleAnnee->MultiUpdate = $objForm->GetValue("u_LibelleAnnee");
		if (!$this->DatedebutAnnee->FldIsDetailKey) {
			$this->DatedebutAnnee->setFormValue($objForm->GetValue("x_DatedebutAnnee"));
			$this->DatedebutAnnee->CurrentValue = ew_UnFormatDateTime($this->DatedebutAnnee->CurrentValue, 0);
		}
		$this->DatedebutAnnee->MultiUpdate = $objForm->GetValue("u_DatedebutAnnee");
		if (!$this->DatefinAnnee->FldIsDetailKey) {
			$this->DatefinAnnee->setFormValue($objForm->GetValue("x_DatefinAnnee"));
			$this->DatefinAnnee->CurrentValue = ew_UnFormatDateTime($this->DatefinAnnee->CurrentValue, 0);
		}
		$this->DatefinAnnee->MultiUpdate = $objForm->GetValue("u_DatefinAnnee");
		if (!$this->IdAnnee->FldIsDetailKey)
			$this->IdAnnee->setFormValue($objForm->GetValue("x_IdAnnee"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->IdAnnee->CurrentValue = $this->IdAnnee->FormValue;
		$this->LibelleAnnee->CurrentValue = $this->LibelleAnnee->FormValue;
		$this->DatedebutAnnee->CurrentValue = $this->DatedebutAnnee->FormValue;
		$this->DatedebutAnnee->CurrentValue = ew_UnFormatDateTime($this->DatedebutAnnee->CurrentValue, 0);
		$this->DatefinAnnee->CurrentValue = $this->DatefinAnnee->FormValue;
		$this->DatefinAnnee->CurrentValue = ew_UnFormatDateTime($this->DatefinAnnee->CurrentValue, 0);
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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

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

			// Edit refer script
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
		$lUpdateCnt = 0;
		if ($this->LibelleAnnee->MultiUpdate == "1") $lUpdateCnt++;
		if ($this->DatedebutAnnee->MultiUpdate == "1") $lUpdateCnt++;
		if ($this->DatefinAnnee->MultiUpdate == "1") $lUpdateCnt++;
		if ($lUpdateCnt == 0) {
			$gsFormError = $Language->Phrase("NoFieldSelected");
			return FALSE;
		}

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if ($this->DatedebutAnnee->MultiUpdate <> "" && !$this->DatedebutAnnee->FldIsDetailKey && !is_null($this->DatedebutAnnee->FormValue) && $this->DatedebutAnnee->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->DatedebutAnnee->FldCaption(), $this->DatedebutAnnee->ReqErrMsg));
		}
		if ($this->DatedebutAnnee->MultiUpdate <> "") {
			if (!ew_CheckDateDef($this->DatedebutAnnee->FormValue)) {
				ew_AddMessage($gsFormError, $this->DatedebutAnnee->FldErrMsg());
			}
		}
		if ($this->DatefinAnnee->MultiUpdate <> "") {
			if (!ew_CheckDateDef($this->DatefinAnnee->FormValue)) {
				ew_AddMessage($gsFormError, $this->DatefinAnnee->FldErrMsg());
			}
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

			// LibelleAnnee
			$this->LibelleAnnee->SetDbValueDef($rsnew, $this->LibelleAnnee->CurrentValue, NULL, $this->LibelleAnnee->ReadOnly || $this->LibelleAnnee->MultiUpdate <> "1");

			// DatedebutAnnee
			$this->DatedebutAnnee->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->DatedebutAnnee->CurrentValue, 0), NULL, $this->DatedebutAnnee->ReadOnly || $this->DatedebutAnnee->MultiUpdate <> "1");

			// DatefinAnnee
			$this->DatefinAnnee->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->DatefinAnnee->CurrentValue, 0), NULL, $this->DatefinAnnee->ReadOnly || $this->DatefinAnnee->MultiUpdate <> "1");

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

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("anneelist.php"), "", $this->TableVar, TRUE);
		$PageId = "update";
		$Breadcrumb->Add("update", $PageId, $url);
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
if (!isset($annee_update)) $annee_update = new cannee_update();

// Page init
$annee_update->Page_Init();

// Page main
$annee_update->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$annee_update->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "update";
var CurrentForm = fanneeupdate = new ew_Form("fanneeupdate", "update");

// Validate form
fanneeupdate.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	if (!ew_UpdateSelected(fobj)) {
		ew_Alert(ewLanguage.Phrase("NoFieldSelected"));
		return false;
	}
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
			elm = this.GetElements("x" + infix + "_DatedebutAnnee");
			uelm = this.GetElements("u" + infix + "_DatedebutAnnee");
			if (uelm && uelm.checked) {
				if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
					return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $annee->DatedebutAnnee->FldCaption(), $annee->DatedebutAnnee->ReqErrMsg)) ?>");
			}
			elm = this.GetElements("x" + infix + "_DatedebutAnnee");
			uelm = this.GetElements("u" + infix + "_DatedebutAnnee");
			if (uelm && uelm.checked && elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($annee->DatedebutAnnee->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_DatefinAnnee");
			uelm = this.GetElements("u" + infix + "_DatefinAnnee");
			if (uelm && uelm.checked && elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($annee->DatefinAnnee->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}
	return true;
}

// Form_CustomValidate event
fanneeupdate.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fanneeupdate.ValidateRequired = true;
<?php } else { ?>
fanneeupdate.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$annee_update->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $annee_update->ShowPageHeader(); ?>
<?php
$annee_update->ShowMessage();
?>
<form name="fanneeupdate" id="fanneeupdate" class="<?php echo $annee_update->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($annee_update->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $annee_update->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="annee">
<input type="hidden" name="a_update" id="a_update" value="U">
<?php if ($annee_update->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<?php foreach ($annee_update->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div id="tbl_anneeupdate">
	<div class="checkbox">
		<label><input type="checkbox" name="u" id="u" onclick="ew_SelectAll(this);"> <?php echo $Language->Phrase("UpdateSelectAll") ?></label>
	</div>
<?php if ($annee->LibelleAnnee->Visible) { // LibelleAnnee ?>
	<div id="r_LibelleAnnee" class="form-group">
		<label for="x_LibelleAnnee" class="col-sm-2 control-label">
<input type="checkbox" name="u_LibelleAnnee" id="u_LibelleAnnee" value="1"<?php echo ($annee->LibelleAnnee->MultiUpdate == "1") ? " checked" : "" ?>>
 <?php echo $annee->LibelleAnnee->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $annee->LibelleAnnee->CellAttributes() ?>>
<span id="el_annee_LibelleAnnee">
<input type="text" data-table="annee" data-field="x_LibelleAnnee" name="x_LibelleAnnee" id="x_LibelleAnnee" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($annee->LibelleAnnee->getPlaceHolder()) ?>" value="<?php echo $annee->LibelleAnnee->EditValue ?>"<?php echo $annee->LibelleAnnee->EditAttributes() ?>>
</span>
<?php echo $annee->LibelleAnnee->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($annee->DatedebutAnnee->Visible) { // DatedebutAnnee ?>
	<div id="r_DatedebutAnnee" class="form-group">
		<label for="x_DatedebutAnnee" class="col-sm-2 control-label">
<input type="checkbox" name="u_DatedebutAnnee" id="u_DatedebutAnnee" value="1"<?php echo ($annee->DatedebutAnnee->MultiUpdate == "1") ? " checked" : "" ?>>
 <?php echo $annee->DatedebutAnnee->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $annee->DatedebutAnnee->CellAttributes() ?>>
<span id="el_annee_DatedebutAnnee">
<input type="text" data-table="annee" data-field="x_DatedebutAnnee" name="x_DatedebutAnnee" id="x_DatedebutAnnee" placeholder="<?php echo ew_HtmlEncode($annee->DatedebutAnnee->getPlaceHolder()) ?>" value="<?php echo $annee->DatedebutAnnee->EditValue ?>"<?php echo $annee->DatedebutAnnee->EditAttributes() ?>>
<?php if (!$annee->DatedebutAnnee->ReadOnly && !$annee->DatedebutAnnee->Disabled && !isset($annee->DatedebutAnnee->EditAttrs["readonly"]) && !isset($annee->DatedebutAnnee->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fanneeupdate", "x_DatedebutAnnee", 0);
</script>
<?php } ?>
</span>
<?php echo $annee->DatedebutAnnee->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($annee->DatefinAnnee->Visible) { // DatefinAnnee ?>
	<div id="r_DatefinAnnee" class="form-group">
		<label for="x_DatefinAnnee" class="col-sm-2 control-label">
<input type="checkbox" name="u_DatefinAnnee" id="u_DatefinAnnee" value="1"<?php echo ($annee->DatefinAnnee->MultiUpdate == "1") ? " checked" : "" ?>>
 <?php echo $annee->DatefinAnnee->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $annee->DatefinAnnee->CellAttributes() ?>>
<span id="el_annee_DatefinAnnee">
<input type="text" data-table="annee" data-field="x_DatefinAnnee" name="x_DatefinAnnee" id="x_DatefinAnnee" placeholder="<?php echo ew_HtmlEncode($annee->DatefinAnnee->getPlaceHolder()) ?>" value="<?php echo $annee->DatefinAnnee->EditValue ?>"<?php echo $annee->DatefinAnnee->EditAttributes() ?>>
<?php if (!$annee->DatefinAnnee->ReadOnly && !$annee->DatefinAnnee->Disabled && !isset($annee->DatefinAnnee->EditAttrs["readonly"]) && !isset($annee->DatefinAnnee->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fanneeupdate", "x_DatefinAnnee", 0);
</script>
<?php } ?>
</span>
<?php echo $annee->DatefinAnnee->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if (!$annee_update->IsModal) { ?>
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("UpdateBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $annee_update->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
		</div>
	</div>
<?php } ?>
</div>
</form>
<script type="text/javascript">
fanneeupdate.Init();
</script>
<?php
$annee_update->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$annee_update->Page_Terminate();
?>
