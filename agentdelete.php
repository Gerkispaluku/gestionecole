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

$agent_delete = NULL; // Initialize page object first

class cagent_delete extends cagent {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{84EC96E8-6D54-4BAB-B15E-1EA379B0779B}";

	// Table name
	var $TableName = 'agent';

	// Page object name
	var $PageObjName = 'agent_delete';

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
			define("EW_PAGE_ID", 'delete', TRUE);

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
		if (!$Security->CanDelete()) {
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
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->IdAgent->SetVisibility();
		$this->IdAgent->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
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
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("agentlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in agent class, agentinfo.php

		$this->CurrentFilter = $sFilter;

		// Check if valid user id
		$conn = &$this->Connection();
		$sql = $this->GetSQL($this->CurrentFilter, "");
		if ($this->Recordset = ew_LoadRecordset($sql, $conn)) {
			$res = TRUE;
			while (!$this->Recordset->EOF) {
				$this->LoadRowValues($this->Recordset);
				if (!$this->ShowOptionLink('delete')) {
					$sUserIdMsg = $Language->Phrase("NoDeletePermission");
					$this->setFailureMessage($sUserIdMsg);
					$res = FALSE;
					break;
				}
				$this->Recordset->MoveNext();
			}
			$this->Recordset->Close();
			if (!$res) $this->Page_Terminate("agentlist.php"); // Return to list
		}

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} elseif (@$_GET["a_delete"] == "1") {
			$this->CurrentAction = "D"; // Delete record directly
		} else {
			$this->CurrentAction = "D"; // Delete record directly
		}
		if ($this->CurrentAction == "D") {
			$this->SendEmail = TRUE; // Send email on delete success
			if ($this->DeleteRows()) { // Delete rows
				if ($this->getSuccessMessage() == "")
					$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
				$this->Page_Terminate($this->getReturnUrl()); // Return to caller
			} else { // Delete failed
				$this->Page_Terminate($this->getReturnUrl()); // Return to caller
			}
		}
		if ($this->CurrentAction == "I") { // Load records for display
			if ($this->Recordset = $this->LoadRecordset())
				$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
			if ($this->TotalRecs <= 0) { // No record found, exit
				if ($this->Recordset)
					$this->Recordset->Close();
				$this->Page_Terminate("agentlist.php"); // Return to list
			}
		}
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

			// IdAgent
			$this->IdAgent->LinkCustomAttributes = "";
			$this->IdAgent->HrefValue = "";
			$this->IdAgent->TooltipValue = "";

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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $Language, $Security;
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
		}
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$rows = ($rs) ? $rs->GetRows() : array();
		$conn->BeginTrans();

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['IdAgent'];
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
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
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, $url);
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
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($agent_delete)) $agent_delete = new cagent_delete();

// Page init
$agent_delete->Page_Init();

// Page main
$agent_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$agent_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fagentdelete = new ew_Form("fagentdelete", "delete");

// Form_CustomValidate event
fagentdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fagentdelete.ValidateRequired = true;
<?php } else { ?>
fagentdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fagentdelete.Lists["x_SexeAgent"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fagentdelete.Lists["x_SexeAgent"].Options = <?php echo json_encode($agent->SexeAgent->Options()) ?>;
fagentdelete.Lists["x_EtatCivilAgent"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fagentdelete.Lists["x_EtatCivilAgent"].Options = <?php echo json_encode($agent->EtatCivilAgent->Options()) ?>;
fagentdelete.Lists["x_IdFonction"] = {"LinkField":"x_IdFonction","Ajax":true,"AutoFill":false,"DisplayFields":["x_NomFoction","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"fonction"};
fagentdelete.Lists["x_userlevel_id"] = {"LinkField":"x_userlevelid","Ajax":true,"AutoFill":false,"DisplayFields":["x_userlevelname","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"userlevels"};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $agent_delete->ShowPageHeader(); ?>
<?php
$agent_delete->ShowMessage();
?>
<form name="fagentdelete" id="fagentdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($agent_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $agent_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="agent">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($agent_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $agent->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($agent->IdAgent->Visible) { // IdAgent ?>
		<th><span id="elh_agent_IdAgent" class="agent_IdAgent"><?php echo $agent->IdAgent->FldCaption() ?></span></th>
<?php } ?>
<?php if ($agent->MatricAgent->Visible) { // MatricAgent ?>
		<th><span id="elh_agent_MatricAgent" class="agent_MatricAgent"><?php echo $agent->MatricAgent->FldCaption() ?></span></th>
<?php } ?>
<?php if ($agent->NomAgent->Visible) { // NomAgent ?>
		<th><span id="elh_agent_NomAgent" class="agent_NomAgent"><?php echo $agent->NomAgent->FldCaption() ?></span></th>
<?php } ?>
<?php if ($agent->PostnomAgent->Visible) { // PostnomAgent ?>
		<th><span id="elh_agent_PostnomAgent" class="agent_PostnomAgent"><?php echo $agent->PostnomAgent->FldCaption() ?></span></th>
<?php } ?>
<?php if ($agent->PrenomAgent->Visible) { // PrenomAgent ?>
		<th><span id="elh_agent_PrenomAgent" class="agent_PrenomAgent"><?php echo $agent->PrenomAgent->FldCaption() ?></span></th>
<?php } ?>
<?php if ($agent->SexeAgent->Visible) { // SexeAgent ?>
		<th><span id="elh_agent_SexeAgent" class="agent_SexeAgent"><?php echo $agent->SexeAgent->FldCaption() ?></span></th>
<?php } ?>
<?php if ($agent->EtatCivilAgent->Visible) { // EtatCivilAgent ?>
		<th><span id="elh_agent_EtatCivilAgent" class="agent_EtatCivilAgent"><?php echo $agent->EtatCivilAgent->FldCaption() ?></span></th>
<?php } ?>
<?php if ($agent->TelephoneAgent->Visible) { // TelephoneAgent ?>
		<th><span id="elh_agent_TelephoneAgent" class="agent_TelephoneAgent"><?php echo $agent->TelephoneAgent->FldCaption() ?></span></th>
<?php } ?>
<?php if ($agent->IdFonction->Visible) { // IdFonction ?>
		<th><span id="elh_agent_IdFonction" class="agent_IdFonction"><?php echo $agent->IdFonction->FldCaption() ?></span></th>
<?php } ?>
<?php if ($agent->userlevel_id->Visible) { // userlevel_id ?>
		<th><span id="elh_agent_userlevel_id" class="agent_userlevel_id"><?php echo $agent->userlevel_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($agent->Motdepasse->Visible) { // Motdepasse ?>
		<th><span id="elh_agent_Motdepasse" class="agent_Motdepasse"><?php echo $agent->Motdepasse->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$agent_delete->RecCnt = 0;
$i = 0;
while (!$agent_delete->Recordset->EOF) {
	$agent_delete->RecCnt++;
	$agent_delete->RowCnt++;

	// Set row properties
	$agent->ResetAttrs();
	$agent->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$agent_delete->LoadRowValues($agent_delete->Recordset);

	// Render row
	$agent_delete->RenderRow();
?>
	<tr<?php echo $agent->RowAttributes() ?>>
<?php if ($agent->IdAgent->Visible) { // IdAgent ?>
		<td<?php echo $agent->IdAgent->CellAttributes() ?>>
<span id="el<?php echo $agent_delete->RowCnt ?>_agent_IdAgent" class="agent_IdAgent">
<span<?php echo $agent->IdAgent->ViewAttributes() ?>>
<?php echo $agent->IdAgent->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($agent->MatricAgent->Visible) { // MatricAgent ?>
		<td<?php echo $agent->MatricAgent->CellAttributes() ?>>
<span id="el<?php echo $agent_delete->RowCnt ?>_agent_MatricAgent" class="agent_MatricAgent">
<span<?php echo $agent->MatricAgent->ViewAttributes() ?>>
<?php echo $agent->MatricAgent->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($agent->NomAgent->Visible) { // NomAgent ?>
		<td<?php echo $agent->NomAgent->CellAttributes() ?>>
<span id="el<?php echo $agent_delete->RowCnt ?>_agent_NomAgent" class="agent_NomAgent">
<span<?php echo $agent->NomAgent->ViewAttributes() ?>>
<?php echo $agent->NomAgent->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($agent->PostnomAgent->Visible) { // PostnomAgent ?>
		<td<?php echo $agent->PostnomAgent->CellAttributes() ?>>
<span id="el<?php echo $agent_delete->RowCnt ?>_agent_PostnomAgent" class="agent_PostnomAgent">
<span<?php echo $agent->PostnomAgent->ViewAttributes() ?>>
<?php echo $agent->PostnomAgent->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($agent->PrenomAgent->Visible) { // PrenomAgent ?>
		<td<?php echo $agent->PrenomAgent->CellAttributes() ?>>
<span id="el<?php echo $agent_delete->RowCnt ?>_agent_PrenomAgent" class="agent_PrenomAgent">
<span<?php echo $agent->PrenomAgent->ViewAttributes() ?>>
<?php echo $agent->PrenomAgent->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($agent->SexeAgent->Visible) { // SexeAgent ?>
		<td<?php echo $agent->SexeAgent->CellAttributes() ?>>
<span id="el<?php echo $agent_delete->RowCnt ?>_agent_SexeAgent" class="agent_SexeAgent">
<span<?php echo $agent->SexeAgent->ViewAttributes() ?>>
<?php echo $agent->SexeAgent->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($agent->EtatCivilAgent->Visible) { // EtatCivilAgent ?>
		<td<?php echo $agent->EtatCivilAgent->CellAttributes() ?>>
<span id="el<?php echo $agent_delete->RowCnt ?>_agent_EtatCivilAgent" class="agent_EtatCivilAgent">
<span<?php echo $agent->EtatCivilAgent->ViewAttributes() ?>>
<?php echo $agent->EtatCivilAgent->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($agent->TelephoneAgent->Visible) { // TelephoneAgent ?>
		<td<?php echo $agent->TelephoneAgent->CellAttributes() ?>>
<span id="el<?php echo $agent_delete->RowCnt ?>_agent_TelephoneAgent" class="agent_TelephoneAgent">
<span<?php echo $agent->TelephoneAgent->ViewAttributes() ?>>
<?php echo $agent->TelephoneAgent->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($agent->IdFonction->Visible) { // IdFonction ?>
		<td<?php echo $agent->IdFonction->CellAttributes() ?>>
<span id="el<?php echo $agent_delete->RowCnt ?>_agent_IdFonction" class="agent_IdFonction">
<span<?php echo $agent->IdFonction->ViewAttributes() ?>>
<?php echo $agent->IdFonction->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($agent->userlevel_id->Visible) { // userlevel_id ?>
		<td<?php echo $agent->userlevel_id->CellAttributes() ?>>
<span id="el<?php echo $agent_delete->RowCnt ?>_agent_userlevel_id" class="agent_userlevel_id">
<span<?php echo $agent->userlevel_id->ViewAttributes() ?>>
<?php echo $agent->userlevel_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($agent->Motdepasse->Visible) { // Motdepasse ?>
		<td<?php echo $agent->Motdepasse->CellAttributes() ?>>
<span id="el<?php echo $agent_delete->RowCnt ?>_agent_Motdepasse" class="agent_Motdepasse">
<span<?php echo $agent->Motdepasse->ViewAttributes() ?>>
<?php echo $agent->Motdepasse->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$agent_delete->Recordset->MoveNext();
}
$agent_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $agent_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fagentdelete.Init();
</script>
<?php
$agent_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$agent_delete->Page_Terminate();
?>
