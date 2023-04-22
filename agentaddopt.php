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

$agent_addopt = NULL; // Initialize page object first

class cagent_addopt extends cagent {

	// Page ID
	var $PageID = 'addopt';

	// Project ID
	var $ProjectID = "{84EC96E8-6D54-4BAB-B15E-1EA379B0779B}";

	// Table name
	var $TableName = 'agent';

	// Page object name
	var $PageObjName = 'agent_addopt';

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
			define("EW_PAGE_ID", 'addopt', TRUE);

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
		if (!$Security->CanAdd()) {
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
		$this->IdFonction->SetVisibility();
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
					$row["x_IdAgent"] = $this->IdAgent->DbValue;
					$row["x_MatricAgent"] = $this->MatricAgent->DbValue;
					$row["x_NomAgent"] = $this->NomAgent->DbValue;
					$row["x_PostnomAgent"] = $this->PostnomAgent->DbValue;
					$row["x_PrenomAgent"] = $this->PrenomAgent->DbValue;
					$row["x_SexeAgent"] = $this->SexeAgent->DbValue;
					$row["x_EtatCivilAgent"] = $this->EtatCivilAgent->DbValue;
					$row["x_TelephoneAgent"] = $this->TelephoneAgent->DbValue;
					$row["x_IdFonction"] = $this->IdFonction->DbValue;
					$row["x_userlevel_id"] = $this->userlevel_id->DbValue;
					$row["x_Motdepasse"] = $this->Motdepasse->DbValue;
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
		$this->MatricAgent->CurrentValue = NULL;
		$this->MatricAgent->OldValue = $this->MatricAgent->CurrentValue;
		$this->NomAgent->CurrentValue = NULL;
		$this->NomAgent->OldValue = $this->NomAgent->CurrentValue;
		$this->PostnomAgent->CurrentValue = NULL;
		$this->PostnomAgent->OldValue = $this->PostnomAgent->CurrentValue;
		$this->PrenomAgent->CurrentValue = NULL;
		$this->PrenomAgent->OldValue = $this->PrenomAgent->CurrentValue;
		$this->SexeAgent->CurrentValue = NULL;
		$this->SexeAgent->OldValue = $this->SexeAgent->CurrentValue;
		$this->EtatCivilAgent->CurrentValue = NULL;
		$this->EtatCivilAgent->OldValue = $this->EtatCivilAgent->CurrentValue;
		$this->TelephoneAgent->CurrentValue = NULL;
		$this->TelephoneAgent->OldValue = $this->TelephoneAgent->CurrentValue;
		$this->IdFonction->CurrentValue = NULL;
		$this->IdFonction->OldValue = $this->IdFonction->CurrentValue;
		$this->userlevel_id->CurrentValue = NULL;
		$this->userlevel_id->OldValue = $this->userlevel_id->CurrentValue;
		$this->Motdepasse->CurrentValue = NULL;
		$this->Motdepasse->OldValue = $this->Motdepasse->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->MatricAgent->FldIsDetailKey) {
			$this->MatricAgent->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_MatricAgent")));
		}
		if (!$this->NomAgent->FldIsDetailKey) {
			$this->NomAgent->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_NomAgent")));
		}
		if (!$this->PostnomAgent->FldIsDetailKey) {
			$this->PostnomAgent->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_PostnomAgent")));
		}
		if (!$this->PrenomAgent->FldIsDetailKey) {
			$this->PrenomAgent->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_PrenomAgent")));
		}
		if (!$this->SexeAgent->FldIsDetailKey) {
			$this->SexeAgent->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_SexeAgent")));
		}
		if (!$this->EtatCivilAgent->FldIsDetailKey) {
			$this->EtatCivilAgent->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_EtatCivilAgent")));
		}
		if (!$this->TelephoneAgent->FldIsDetailKey) {
			$this->TelephoneAgent->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_TelephoneAgent")));
		}
		if (!$this->IdFonction->FldIsDetailKey) {
			$this->IdFonction->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_IdFonction")));
		}
		if (!$this->userlevel_id->FldIsDetailKey) {
			$this->userlevel_id->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_userlevel_id")));
		}
		if (!$this->Motdepasse->FldIsDetailKey) {
			$this->Motdepasse->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_Motdepasse")));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->MatricAgent->CurrentValue = ew_ConvertToUtf8($this->MatricAgent->FormValue);
		$this->NomAgent->CurrentValue = ew_ConvertToUtf8($this->NomAgent->FormValue);
		$this->PostnomAgent->CurrentValue = ew_ConvertToUtf8($this->PostnomAgent->FormValue);
		$this->PrenomAgent->CurrentValue = ew_ConvertToUtf8($this->PrenomAgent->FormValue);
		$this->SexeAgent->CurrentValue = ew_ConvertToUtf8($this->SexeAgent->FormValue);
		$this->EtatCivilAgent->CurrentValue = ew_ConvertToUtf8($this->EtatCivilAgent->FormValue);
		$this->TelephoneAgent->CurrentValue = ew_ConvertToUtf8($this->TelephoneAgent->FormValue);
		$this->IdFonction->CurrentValue = ew_ConvertToUtf8($this->IdFonction->FormValue);
		$this->userlevel_id->CurrentValue = ew_ConvertToUtf8($this->userlevel_id->FormValue);
		$this->Motdepasse->CurrentValue = ew_ConvertToUtf8($this->Motdepasse->FormValue);
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

			// IdFonction
			$this->IdFonction->LinkCustomAttributes = "";
			$this->IdFonction->HrefValue = "";
			$this->IdFonction->TooltipValue = "";

			// userlevel_id
			$this->userlevel_id->LinkCustomAttributes = "";
			$this->userlevel_id->HrefValue = "";
			$this->userlevel_id->TooltipValue = "";

			// Motdepasse
			$this->Motdepasse->LinkCustomAttributes = "";
			$this->Motdepasse->HrefValue = "";
			$this->Motdepasse->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

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

			// IdFonction
			$this->IdFonction->EditAttrs["class"] = "form-control";
			$this->IdFonction->EditCustomAttributes = "";
			$this->IdFonction->EditValue = ew_HtmlEncode($this->IdFonction->CurrentValue);
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
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->IdFonction->EditValue = $this->IdFonction->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->IdFonction->EditValue = ew_HtmlEncode($this->IdFonction->CurrentValue);
				}
			} else {
				$this->IdFonction->EditValue = NULL;
			}
			$this->IdFonction->PlaceHolder = ew_RemoveHtml($this->IdFonction->FldCaption());

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

			// Add refer script
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

			// IdFonction
			$this->IdFonction->LinkCustomAttributes = "";
			$this->IdFonction->HrefValue = "";

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

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;

		// Check if valid User ID
		$bValidUser = FALSE;
		if ($Security->CurrentUserID() <> "" && !ew_Empty($this->IdAgent->CurrentValue) && !$Security->IsAdmin()) { // Non system admin
			$bValidUser = $Security->IsValidUserID($this->IdAgent->CurrentValue);
			if (!$bValidUser) {
				$sUserIdMsg = str_replace("%c", CurrentUserID(), $Language->Phrase("UnAuthorizedUserID"));
				$sUserIdMsg = str_replace("%u", $this->IdAgent->CurrentValue, $sUserIdMsg);
				$this->setFailureMessage($sUserIdMsg);
				return FALSE;
			}
		}
		$conn = &$this->Connection();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// MatricAgent
		$this->MatricAgent->SetDbValueDef($rsnew, $this->MatricAgent->CurrentValue, NULL, FALSE);

		// NomAgent
		$this->NomAgent->SetDbValueDef($rsnew, $this->NomAgent->CurrentValue, NULL, FALSE);

		// PostnomAgent
		$this->PostnomAgent->SetDbValueDef($rsnew, $this->PostnomAgent->CurrentValue, NULL, FALSE);

		// PrenomAgent
		$this->PrenomAgent->SetDbValueDef($rsnew, $this->PrenomAgent->CurrentValue, NULL, FALSE);

		// SexeAgent
		$this->SexeAgent->SetDbValueDef($rsnew, $this->SexeAgent->CurrentValue, NULL, FALSE);

		// EtatCivilAgent
		$this->EtatCivilAgent->SetDbValueDef($rsnew, $this->EtatCivilAgent->CurrentValue, NULL, FALSE);

		// TelephoneAgent
		$this->TelephoneAgent->SetDbValueDef($rsnew, $this->TelephoneAgent->CurrentValue, NULL, FALSE);

		// IdFonction
		$this->IdFonction->SetDbValueDef($rsnew, $this->IdFonction->CurrentValue, NULL, FALSE);

		// userlevel_id
		if ($Security->CanAdmin()) { // System admin
		$this->userlevel_id->SetDbValueDef($rsnew, $this->userlevel_id->CurrentValue, NULL, FALSE);
		}

		// Motdepasse
		$this->Motdepasse->SetDbValueDef($rsnew, $this->Motdepasse->CurrentValue, NULL, FALSE);

		// IdAgent
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("agentlist.php"), "", $this->TableVar, TRUE);
		$PageId = "addopt";
		$Breadcrumb->Add("addopt", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_IdFonction":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `IdFonction` AS `LinkFld`, `NomFoction` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `fonction`";
			$sWhereWrk = "{filter}";
			$this->IdFonction->LookupFilters = array("dx1" => '`NomFoction`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`IdFonction` = {filter_value}', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->IdFonction, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
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
		case "x_IdFonction":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `IdFonction`, `NomFoction` AS `DispFld` FROM `fonction`";
			$sWhereWrk = "`NomFoction` LIKE '{query_value}%'";
			$this->IdFonction->LookupFilters = array("dx1" => '`NomFoction`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->IdFonction, $sWhereWrk); // Call Lookup selecting
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
if (!isset($agent_addopt)) $agent_addopt = new cagent_addopt();

// Page init
$agent_addopt->Page_Init();

// Page main
$agent_addopt->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$agent_addopt->Page_Render();
?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "addopt";
var CurrentForm = fagentaddopt = new ew_Form("fagentaddopt", "addopt");

// Validate form
fagentaddopt.Validate = function() {
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
	return true;
}

// Form_CustomValidate event
fagentaddopt.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fagentaddopt.ValidateRequired = true;
<?php } else { ?>
fagentaddopt.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fagentaddopt.Lists["x_SexeAgent"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fagentaddopt.Lists["x_SexeAgent"].Options = <?php echo json_encode($agent->SexeAgent->Options()) ?>;
fagentaddopt.Lists["x_EtatCivilAgent"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fagentaddopt.Lists["x_EtatCivilAgent"].Options = <?php echo json_encode($agent->EtatCivilAgent->Options()) ?>;
fagentaddopt.Lists["x_IdFonction"] = {"LinkField":"x_IdFonction","Ajax":true,"AutoFill":false,"DisplayFields":["x_NomFoction","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"fonction"};
fagentaddopt.Lists["x_userlevel_id"] = {"LinkField":"x_userlevelid","Ajax":true,"AutoFill":false,"DisplayFields":["x_userlevelname","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"userlevels"};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php
$agent_addopt->ShowMessage();
?>
<form name="fagentaddopt" id="fagentaddopt" class="ewForm form-horizontal" action="agentaddopt.php" method="post">
<?php if ($agent_addopt->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $agent_addopt->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="agent">
<input type="hidden" name="a_addopt" id="a_addopt" value="A">
<?php if ($agent->MatricAgent->Visible) { // MatricAgent ?>
	<div class="form-group">
		<label class="col-sm-3 control-label ewLabel" for="x_MatricAgent"><?php echo $agent->MatricAgent->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-9">
<input type="text" data-table="agent" data-field="x_MatricAgent" name="x_MatricAgent" id="x_MatricAgent" size="30" maxlength="3" placeholder="<?php echo ew_HtmlEncode($agent->MatricAgent->getPlaceHolder()) ?>" value="<?php echo $agent->MatricAgent->EditValue ?>"<?php echo $agent->MatricAgent->EditAttributes() ?>>
</div>
	</div>
<?php } ?>	
<?php if ($agent->NomAgent->Visible) { // NomAgent ?>
	<div class="form-group">
		<label class="col-sm-3 control-label ewLabel" for="x_NomAgent"><?php echo $agent->NomAgent->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-9">
<input type="text" data-table="agent" data-field="x_NomAgent" name="x_NomAgent" id="x_NomAgent" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($agent->NomAgent->getPlaceHolder()) ?>" value="<?php echo $agent->NomAgent->EditValue ?>"<?php echo $agent->NomAgent->EditAttributes() ?>>
</div>
	</div>
<?php } ?>	
<?php if ($agent->PostnomAgent->Visible) { // PostnomAgent ?>
	<div class="form-group">
		<label class="col-sm-3 control-label ewLabel" for="x_PostnomAgent"><?php echo $agent->PostnomAgent->FldCaption() ?></label>
		<div class="col-sm-9">
<input type="text" data-table="agent" data-field="x_PostnomAgent" name="x_PostnomAgent" id="x_PostnomAgent" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($agent->PostnomAgent->getPlaceHolder()) ?>" value="<?php echo $agent->PostnomAgent->EditValue ?>"<?php echo $agent->PostnomAgent->EditAttributes() ?>>
</div>
	</div>
<?php } ?>	
<?php if ($agent->PrenomAgent->Visible) { // PrenomAgent ?>
	<div class="form-group">
		<label class="col-sm-3 control-label ewLabel" for="x_PrenomAgent"><?php echo $agent->PrenomAgent->FldCaption() ?></label>
		<div class="col-sm-9">
<input type="text" data-table="agent" data-field="x_PrenomAgent" name="x_PrenomAgent" id="x_PrenomAgent" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($agent->PrenomAgent->getPlaceHolder()) ?>" value="<?php echo $agent->PrenomAgent->EditValue ?>"<?php echo $agent->PrenomAgent->EditAttributes() ?>>
</div>
	</div>
<?php } ?>	
<?php if ($agent->SexeAgent->Visible) { // SexeAgent ?>
	<div class="form-group">
		<label class="col-sm-3 control-label ewLabel"><?php echo $agent->SexeAgent->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-9">
<div id="tp_x_SexeAgent" class="ewTemplate"><input type="radio" data-table="agent" data-field="x_SexeAgent" data-value-separator="<?php echo $agent->SexeAgent->DisplayValueSeparatorAttribute() ?>" name="x_SexeAgent" id="x_SexeAgent" value="{value}"<?php echo $agent->SexeAgent->EditAttributes() ?>></div>
<div id="dsl_x_SexeAgent" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $agent->SexeAgent->RadioButtonListHtml(FALSE, "x_SexeAgent") ?>
</div></div>
</div>
	</div>
<?php } ?>	
<?php if ($agent->EtatCivilAgent->Visible) { // EtatCivilAgent ?>
	<div class="form-group">
		<label class="col-sm-3 control-label ewLabel" for="x_EtatCivilAgent"><?php echo $agent->EtatCivilAgent->FldCaption() ?></label>
		<div class="col-sm-9">
<select data-table="agent" data-field="x_EtatCivilAgent" data-value-separator="<?php echo $agent->EtatCivilAgent->DisplayValueSeparatorAttribute() ?>" id="x_EtatCivilAgent" name="x_EtatCivilAgent"<?php echo $agent->EtatCivilAgent->EditAttributes() ?>>
<?php echo $agent->EtatCivilAgent->SelectOptionListHtml("x_EtatCivilAgent") ?>
</select>
</div>
	</div>
<?php } ?>	
<?php if ($agent->TelephoneAgent->Visible) { // TelephoneAgent ?>
	<div class="form-group">
		<label class="col-sm-3 control-label ewLabel" for="x_TelephoneAgent"><?php echo $agent->TelephoneAgent->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-9">
<input type="text" data-table="agent" data-field="x_TelephoneAgent" name="x_TelephoneAgent" id="x_TelephoneAgent" size="30" maxlength="13" placeholder="<?php echo ew_HtmlEncode($agent->TelephoneAgent->getPlaceHolder()) ?>" value="<?php echo $agent->TelephoneAgent->EditValue ?>"<?php echo $agent->TelephoneAgent->EditAttributes() ?>>
</div>
	</div>
<?php } ?>	
<?php if ($agent->IdFonction->Visible) { // IdFonction ?>
	<div class="form-group">
		<label class="col-sm-3 control-label ewLabel"><?php echo $agent->IdFonction->FldCaption() ?></label>
		<div class="col-sm-9">
<?php
$wrkonchange = trim(" " . @$agent->IdFonction->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$agent->IdFonction->EditAttrs["onchange"] = "";
?>
<span id="as_x_IdFonction" style="white-space: nowrap; z-index: 8910">
	<input type="text" name="sv_x_IdFonction" id="sv_x_IdFonction" value="<?php echo $agent->IdFonction->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($agent->IdFonction->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($agent->IdFonction->getPlaceHolder()) ?>"<?php echo $agent->IdFonction->EditAttributes() ?>>
</span>
<input type="hidden" data-table="agent" data-field="x_IdFonction" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $agent->IdFonction->DisplayValueSeparatorAttribute() ?>" name="x_IdFonction" id="x_IdFonction" value="<?php echo ew_HtmlEncode($agent->IdFonction->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<input type="hidden" name="q_x_IdFonction" id="q_x_IdFonction" value="<?php echo $agent->IdFonction->LookupFilterQuery(true) ?>">
<script type="text/javascript">
fagentaddopt.CreateAutoSuggest({"id":"x_IdFonction","forceSelect":true});
</script>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($agent->IdFonction->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_IdFonction',m:0,n:10,srch:false});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" name="s_x_IdFonction" id="s_x_IdFonction" value="<?php echo $agent->IdFonction->LookupFilterQuery(false) ?>">
<input type="hidden" name="s_x_IdFonction" id="s_x_IdFonction" value="<?php echo $agent->IdFonction->LookupFilterQuery() ?>">
</div>
	</div>
<?php } ?>	
<?php if ($agent->userlevel_id->Visible) { // userlevel_id ?>
	<div class="form-group">
		<label class="col-sm-3 control-label ewLabel" for="x_userlevel_id"><?php echo $agent->userlevel_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-9">
<?php if (!$Security->IsAdmin() && $Security->IsLoggedIn()) { // Non system admin ?>
<p class="form-control-static"><?php echo $agent->userlevel_id->EditValue ?></p>
<?php } else { ?>
<select data-table="agent" data-field="x_userlevel_id" data-value-separator="<?php echo $agent->userlevel_id->DisplayValueSeparatorAttribute() ?>" id="x_userlevel_id" name="x_userlevel_id"<?php echo $agent->userlevel_id->EditAttributes() ?>>
<?php echo $agent->userlevel_id->SelectOptionListHtml("x_userlevel_id") ?>
</select>
<input type="hidden" name="s_x_userlevel_id" id="s_x_userlevel_id" value="<?php echo $agent->userlevel_id->LookupFilterQuery() ?>">
<?php } ?>
</div>
	</div>
<?php } ?>	
<?php if ($agent->Motdepasse->Visible) { // Motdepasse ?>
	<div class="form-group">
		<label class="col-sm-3 control-label ewLabel" for="x_Motdepasse"><?php echo $agent->Motdepasse->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-9">
<div class="input-group" id="ig_Motdepasse">
<input type="password" data-password-strength="pst_Motdepasse" data-password-generated="pgt_Motdepasse" data-table="agent" data-field="x_Motdepasse" name="x_Motdepasse" id="x_Motdepasse" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($agent->Motdepasse->getPlaceHolder()) ?>"<?php echo $agent->Motdepasse->EditAttributes() ?>>
<span class="input-group-btn">
	<button type="button" class="btn btn-default ewPasswordGenerator" title="<?php echo ew_HtmlTitle($Language->Phrase("GeneratePassword")) ?>" data-password-field="x_Motdepasse" data-password-confirm="c_Motdepasse" data-password-strength="pst_Motdepasse" data-password-generated="pgt_Motdepasse"><?php echo $Language->Phrase("GeneratePassword") ?></button>
</span>
</div>
<span class="help-block" id="pgt_Motdepasse" style="display: none;"></span>
<div class="progress ewPasswordStrengthBar" id="pst_Motdepasse" style="display: none;">
	<div class="progress-bar" role="progressbar"></div>
</div>
</div>
	</div>
<?php } ?>	
</form>
<script type="text/javascript">
fagentaddopt.Init();
</script>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php
$agent_addopt->Page_Terminate();
?>
