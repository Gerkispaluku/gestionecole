<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "inscriptioninfo.php" ?>
<?php include_once "agentinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$inscription_edit = NULL; // Initialize page object first

class cinscription_edit extends cinscription {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{84EC96E8-6D54-4BAB-B15E-1EA379B0779B}";

	// Table name
	var $TableName = 'inscription';

	// Page object name
	var $PageObjName = 'inscription_edit';

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

		// Table object (inscription)
		if (!isset($GLOBALS["inscription"]) || get_class($GLOBALS["inscription"]) == "cinscription") {
			$GLOBALS["inscription"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["inscription"];
		}

		// Table object (agent)
		if (!isset($GLOBALS['agent'])) $GLOBALS['agent'] = new cagent();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'inscription', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("inscriptionlist.php"));
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
		$this->IdInscription->SetVisibility();
		$this->IdInscription->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->IdEleve->SetVisibility();
		$this->IdClasse->SetVisibility();
		$this->IdAnnee->SetVisibility();

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
		global $EW_EXPORT, $inscription;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($inscription);
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
		if (@$_GET["IdInscription"] <> "") {
			$this->IdInscription->setQueryStringValue($_GET["IdInscription"]);
			$this->RecKey["IdInscription"] = $this->IdInscription->QueryStringValue;
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
			$this->Page_Terminate("inscriptionlist.php"); // Return to list page
		} elseif ($bLoadCurrentRecord) { // Load current record position
			$this->SetUpStartRec(); // Set up start record position

			// Point to current record
			if (intval($this->StartRec) <= intval($this->TotalRecs)) {
				$bMatchRecord = TRUE;
				$this->Recordset->Move($this->StartRec-1);
			}
		} else { // Match key values
			while (!$this->Recordset->EOF) {
				if (strval($this->IdInscription->CurrentValue) == strval($this->Recordset->fields('IdInscription'))) {
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
					$this->Page_Terminate("inscriptionlist.php"); // Return to list page
				} else {
					$this->LoadRowValues($this->Recordset); // Load row values
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "inscriptionlist.php")
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
		if (!$this->IdInscription->FldIsDetailKey)
			$this->IdInscription->setFormValue($objForm->GetValue("x_IdInscription"));
		if (!$this->IdEleve->FldIsDetailKey) {
			$this->IdEleve->setFormValue($objForm->GetValue("x_IdEleve"));
		}
		if (!$this->IdClasse->FldIsDetailKey) {
			$this->IdClasse->setFormValue($objForm->GetValue("x_IdClasse"));
		}
		if (!$this->IdAnnee->FldIsDetailKey) {
			$this->IdAnnee->setFormValue($objForm->GetValue("x_IdAnnee"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->IdInscription->CurrentValue = $this->IdInscription->FormValue;
		$this->IdEleve->CurrentValue = $this->IdEleve->FormValue;
		$this->IdClasse->CurrentValue = $this->IdClasse->FormValue;
		$this->IdAnnee->CurrentValue = $this->IdAnnee->FormValue;
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
		$this->IdInscription->setDbValue($rs->fields('IdInscription'));
		$this->IdEleve->setDbValue($rs->fields('IdEleve'));
		if (array_key_exists('EV__IdEleve', $rs->fields)) {
			$this->IdEleve->VirtualValue = $rs->fields('EV__IdEleve'); // Set up virtual field value
		} else {
			$this->IdEleve->VirtualValue = ""; // Clear value
		}
		$this->IdClasse->setDbValue($rs->fields('IdClasse'));
		if (array_key_exists('EV__IdClasse', $rs->fields)) {
			$this->IdClasse->VirtualValue = $rs->fields('EV__IdClasse'); // Set up virtual field value
		} else {
			$this->IdClasse->VirtualValue = ""; // Clear value
		}
		$this->IdAnnee->setDbValue($rs->fields('IdAnnee'));
		if (array_key_exists('EV__IdAnnee', $rs->fields)) {
			$this->IdAnnee->VirtualValue = $rs->fields('EV__IdAnnee'); // Set up virtual field value
		} else {
			$this->IdAnnee->VirtualValue = ""; // Clear value
		}
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->IdInscription->DbValue = $row['IdInscription'];
		$this->IdEleve->DbValue = $row['IdEleve'];
		$this->IdClasse->DbValue = $row['IdClasse'];
		$this->IdAnnee->DbValue = $row['IdAnnee'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// IdInscription
		// IdEleve
		// IdClasse
		// IdAnnee

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// IdInscription
		$this->IdInscription->ViewValue = $this->IdInscription->CurrentValue;
		$this->IdInscription->ViewCustomAttributes = "";

		// IdEleve
		if ($this->IdEleve->VirtualValue <> "") {
			$this->IdEleve->ViewValue = $this->IdEleve->VirtualValue;
		} else {
			$this->IdEleve->ViewValue = $this->IdEleve->CurrentValue;
		if (strval($this->IdEleve->CurrentValue) <> "") {
			$sFilterWrk = "`IdEleve`" . ew_SearchString("=", $this->IdEleve->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `IdEleve`, `MatricEleve` AS `DispFld`, `NomEleve` AS `Disp2Fld`, `PostnomElve` AS `Disp3Fld`, `PrenomEleve` AS `Disp4Fld` FROM `eleve`";
		$sWhereWrk = "";
		$this->IdEleve->LookupFilters = array("dx1" => '`MatricEleve`', "dx2" => '`NomEleve`', "dx3" => '`PostnomElve`', "dx4" => '`PrenomEleve`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->IdEleve, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$arwrk[4] = $rswrk->fields('Disp4Fld');
				$this->IdEleve->ViewValue = $this->IdEleve->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->IdEleve->ViewValue = $this->IdEleve->CurrentValue;
			}
		} else {
			$this->IdEleve->ViewValue = NULL;
		}
		}
		$this->IdEleve->ViewCustomAttributes = "";

		// IdClasse
		if ($this->IdClasse->VirtualValue <> "") {
			$this->IdClasse->ViewValue = $this->IdClasse->VirtualValue;
		} else {
			$this->IdClasse->ViewValue = $this->IdClasse->CurrentValue;
		if (strval($this->IdClasse->CurrentValue) <> "") {
			$sFilterWrk = "`IdClasse`" . ew_SearchString("=", $this->IdClasse->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `IdClasse`, `NomClasse` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `classe`";
		$sWhereWrk = "";
		$this->IdClasse->LookupFilters = array("dx1" => '`NomClasse`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->IdClasse, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->IdClasse->ViewValue = $this->IdClasse->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->IdClasse->ViewValue = $this->IdClasse->CurrentValue;
			}
		} else {
			$this->IdClasse->ViewValue = NULL;
		}
		}
		$this->IdClasse->ViewCustomAttributes = "";

		// IdAnnee
		if ($this->IdAnnee->VirtualValue <> "") {
			$this->IdAnnee->ViewValue = $this->IdAnnee->VirtualValue;
		} else {
			$this->IdAnnee->ViewValue = $this->IdAnnee->CurrentValue;
		if (strval($this->IdAnnee->CurrentValue) <> "") {
			$sFilterWrk = "`IdAnnee`" . ew_SearchString("=", $this->IdAnnee->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `IdAnnee`, `LibelleAnnee` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `annee`";
		$sWhereWrk = "";
		$this->IdAnnee->LookupFilters = array("dx1" => '`LibelleAnnee`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->IdAnnee, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->IdAnnee->ViewValue = $this->IdAnnee->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->IdAnnee->ViewValue = $this->IdAnnee->CurrentValue;
			}
		} else {
			$this->IdAnnee->ViewValue = NULL;
		}
		}
		$this->IdAnnee->ViewCustomAttributes = "";

			// IdInscription
			$this->IdInscription->LinkCustomAttributes = "";
			$this->IdInscription->HrefValue = "";
			$this->IdInscription->TooltipValue = "";

			// IdEleve
			$this->IdEleve->LinkCustomAttributes = "";
			$this->IdEleve->HrefValue = "";
			$this->IdEleve->TooltipValue = "";

			// IdClasse
			$this->IdClasse->LinkCustomAttributes = "";
			$this->IdClasse->HrefValue = "";
			$this->IdClasse->TooltipValue = "";

			// IdAnnee
			$this->IdAnnee->LinkCustomAttributes = "";
			$this->IdAnnee->HrefValue = "";
			$this->IdAnnee->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// IdInscription
			$this->IdInscription->EditAttrs["class"] = "form-control";
			$this->IdInscription->EditCustomAttributes = "";
			$this->IdInscription->EditValue = $this->IdInscription->CurrentValue;
			$this->IdInscription->ViewCustomAttributes = "";

			// IdEleve
			$this->IdEleve->EditAttrs["class"] = "form-control";
			$this->IdEleve->EditCustomAttributes = "";
			$this->IdEleve->EditValue = ew_HtmlEncode($this->IdEleve->CurrentValue);
			if (strval($this->IdEleve->CurrentValue) <> "") {
				$sFilterWrk = "`IdEleve`" . ew_SearchString("=", $this->IdEleve->CurrentValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `IdEleve`, `MatricEleve` AS `DispFld`, `NomEleve` AS `Disp2Fld`, `PostnomElve` AS `Disp3Fld`, `PrenomEleve` AS `Disp4Fld` FROM `eleve`";
			$sWhereWrk = "";
			$this->IdEleve->LookupFilters = array("dx1" => '`MatricEleve`', "dx2" => '`NomEleve`', "dx3" => '`PostnomElve`', "dx4" => '`PrenomEleve`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->IdEleve, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
					$arwrk[3] = ew_HtmlEncode($rswrk->fields('Disp3Fld'));
					$arwrk[4] = ew_HtmlEncode($rswrk->fields('Disp4Fld'));
					$this->IdEleve->EditValue = $this->IdEleve->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->IdEleve->EditValue = ew_HtmlEncode($this->IdEleve->CurrentValue);
				}
			} else {
				$this->IdEleve->EditValue = NULL;
			}
			$this->IdEleve->PlaceHolder = ew_RemoveHtml($this->IdEleve->FldCaption());

			// IdClasse
			$this->IdClasse->EditAttrs["class"] = "form-control";
			$this->IdClasse->EditCustomAttributes = "";
			$this->IdClasse->EditValue = ew_HtmlEncode($this->IdClasse->CurrentValue);
			if (strval($this->IdClasse->CurrentValue) <> "") {
				$sFilterWrk = "`IdClasse`" . ew_SearchString("=", $this->IdClasse->CurrentValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `IdClasse`, `NomClasse` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `classe`";
			$sWhereWrk = "";
			$this->IdClasse->LookupFilters = array("dx1" => '`NomClasse`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->IdClasse, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->IdClasse->EditValue = $this->IdClasse->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->IdClasse->EditValue = ew_HtmlEncode($this->IdClasse->CurrentValue);
				}
			} else {
				$this->IdClasse->EditValue = NULL;
			}
			$this->IdClasse->PlaceHolder = ew_RemoveHtml($this->IdClasse->FldCaption());

			// IdAnnee
			$this->IdAnnee->EditAttrs["class"] = "form-control";
			$this->IdAnnee->EditCustomAttributes = "";
			$this->IdAnnee->EditValue = ew_HtmlEncode($this->IdAnnee->CurrentValue);
			if (strval($this->IdAnnee->CurrentValue) <> "") {
				$sFilterWrk = "`IdAnnee`" . ew_SearchString("=", $this->IdAnnee->CurrentValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `IdAnnee`, `LibelleAnnee` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `annee`";
			$sWhereWrk = "";
			$this->IdAnnee->LookupFilters = array("dx1" => '`LibelleAnnee`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->IdAnnee, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->IdAnnee->EditValue = $this->IdAnnee->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->IdAnnee->EditValue = ew_HtmlEncode($this->IdAnnee->CurrentValue);
				}
			} else {
				$this->IdAnnee->EditValue = NULL;
			}
			$this->IdAnnee->PlaceHolder = ew_RemoveHtml($this->IdAnnee->FldCaption());

			// Edit refer script
			// IdInscription

			$this->IdInscription->LinkCustomAttributes = "";
			$this->IdInscription->HrefValue = "";

			// IdEleve
			$this->IdEleve->LinkCustomAttributes = "";
			$this->IdEleve->HrefValue = "";

			// IdClasse
			$this->IdClasse->LinkCustomAttributes = "";
			$this->IdClasse->HrefValue = "";

			// IdAnnee
			$this->IdAnnee->LinkCustomAttributes = "";
			$this->IdAnnee->HrefValue = "";
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

			// IdEleve
			$this->IdEleve->SetDbValueDef($rsnew, $this->IdEleve->CurrentValue, NULL, $this->IdEleve->ReadOnly);

			// IdClasse
			$this->IdClasse->SetDbValueDef($rsnew, $this->IdClasse->CurrentValue, NULL, $this->IdClasse->ReadOnly);

			// IdAnnee
			$this->IdAnnee->SetDbValueDef($rsnew, $this->IdAnnee->CurrentValue, NULL, $this->IdAnnee->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("inscriptionlist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_IdEleve":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `IdEleve` AS `LinkFld`, `MatricEleve` AS `DispFld`, `NomEleve` AS `Disp2Fld`, `PostnomElve` AS `Disp3Fld`, `PrenomEleve` AS `Disp4Fld` FROM `eleve`";
			$sWhereWrk = "{filter}";
			$this->IdEleve->LookupFilters = array("dx1" => '`MatricEleve`', "dx2" => '`NomEleve`', "dx3" => '`PostnomElve`', "dx4" => '`PrenomEleve`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`IdEleve` = {filter_value}', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->IdEleve, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_IdClasse":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `IdClasse` AS `LinkFld`, `NomClasse` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `classe`";
			$sWhereWrk = "{filter}";
			$this->IdClasse->LookupFilters = array("dx1" => '`NomClasse`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`IdClasse` = {filter_value}', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->IdClasse, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_IdAnnee":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `IdAnnee` AS `LinkFld`, `LibelleAnnee` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `annee`";
			$sWhereWrk = "{filter}";
			$this->IdAnnee->LookupFilters = array("dx1" => '`LibelleAnnee`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`IdAnnee` = {filter_value}', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->IdAnnee, $sWhereWrk); // Call Lookup selecting
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
		case "x_IdEleve":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `IdEleve`, `MatricEleve` AS `DispFld`, `NomEleve` AS `Disp2Fld`, `PostnomElve` AS `Disp3Fld`, `PrenomEleve` AS `Disp4Fld` FROM `eleve`";
			$sWhereWrk = "`MatricEleve` LIKE '{query_value}%' OR CONCAT(`MatricEleve`,'" . ew_ValueSeparator(1, $this->IdEleve) . "',`NomEleve`,'" . ew_ValueSeparator(2, $this->IdEleve) . "',`PostnomElve`,'" . ew_ValueSeparator(3, $this->IdEleve) . "',`PrenomEleve`) LIKE '{query_value}%'";
			$this->IdEleve->LookupFilters = array("dx1" => '`MatricEleve`', "dx2" => '`NomEleve`', "dx3" => '`PostnomElve`', "dx4" => '`PrenomEleve`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->IdEleve, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_IdClasse":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `IdClasse`, `NomClasse` AS `DispFld` FROM `classe`";
			$sWhereWrk = "`NomClasse` LIKE '{query_value}%'";
			$this->IdClasse->LookupFilters = array("dx1" => '`NomClasse`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->IdClasse, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_IdAnnee":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `IdAnnee`, `LibelleAnnee` AS `DispFld` FROM `annee`";
			$sWhereWrk = "`LibelleAnnee` LIKE '{query_value}%'";
			$this->IdAnnee->LookupFilters = array("dx1" => '`LibelleAnnee`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->IdAnnee, $sWhereWrk); // Call Lookup selecting
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
if (!isset($inscription_edit)) $inscription_edit = new cinscription_edit();

// Page init
$inscription_edit->Page_Init();

// Page main
$inscription_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$inscription_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = finscriptionedit = new ew_Form("finscriptionedit", "edit");

// Validate form
finscriptionedit.Validate = function() {
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
finscriptionedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
finscriptionedit.ValidateRequired = true;
<?php } else { ?>
finscriptionedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
finscriptionedit.Lists["x_IdEleve"] = {"LinkField":"x_IdEleve","Ajax":true,"AutoFill":false,"DisplayFields":["x_MatricEleve","x_NomEleve","x_PostnomElve","x_PrenomEleve"],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"eleve"};
finscriptionedit.Lists["x_IdClasse"] = {"LinkField":"x_IdClasse","Ajax":true,"AutoFill":false,"DisplayFields":["x_NomClasse","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"classe"};
finscriptionedit.Lists["x_IdAnnee"] = {"LinkField":"x_IdAnnee","Ajax":true,"AutoFill":false,"DisplayFields":["x_LibelleAnnee","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"annee"};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$inscription_edit->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $inscription_edit->ShowPageHeader(); ?>
<?php
$inscription_edit->ShowMessage();
?>
<?php if (!$inscription_edit->IsModal) { ?>
<form name="ewPagerForm" class="form-horizontal ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($inscription_edit->Pager)) $inscription_edit->Pager = new cPrevNextPager($inscription_edit->StartRec, $inscription_edit->DisplayRecs, $inscription_edit->TotalRecs) ?>
<?php if ($inscription_edit->Pager->RecordCount > 0 && $inscription_edit->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($inscription_edit->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $inscription_edit->PageUrl() ?>start=<?php echo $inscription_edit->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($inscription_edit->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $inscription_edit->PageUrl() ?>start=<?php echo $inscription_edit->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $inscription_edit->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($inscription_edit->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $inscription_edit->PageUrl() ?>start=<?php echo $inscription_edit->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($inscription_edit->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $inscription_edit->PageUrl() ?>start=<?php echo $inscription_edit->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $inscription_edit->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<?php } ?>
<form name="finscriptionedit" id="finscriptionedit" class="<?php echo $inscription_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($inscription_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $inscription_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="inscription">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($inscription_edit->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($inscription->IdInscription->Visible) { // IdInscription ?>
	<div id="r_IdInscription" class="form-group">
		<label id="elh_inscription_IdInscription" class="col-sm-2 control-label ewLabel"><?php echo $inscription->IdInscription->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $inscription->IdInscription->CellAttributes() ?>>
<span id="el_inscription_IdInscription">
<span<?php echo $inscription->IdInscription->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $inscription->IdInscription->EditValue ?></p></span>
</span>
<input type="hidden" data-table="inscription" data-field="x_IdInscription" name="x_IdInscription" id="x_IdInscription" value="<?php echo ew_HtmlEncode($inscription->IdInscription->CurrentValue) ?>">
<?php echo $inscription->IdInscription->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($inscription->IdEleve->Visible) { // IdEleve ?>
	<div id="r_IdEleve" class="form-group">
		<label id="elh_inscription_IdEleve" class="col-sm-2 control-label ewLabel"><?php echo $inscription->IdEleve->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $inscription->IdEleve->CellAttributes() ?>>
<span id="el_inscription_IdEleve">
<?php
$wrkonchange = trim(" " . @$inscription->IdEleve->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$inscription->IdEleve->EditAttrs["onchange"] = "";
?>
<span id="as_x_IdEleve" style="white-space: nowrap; z-index: 8980">
	<input type="text" name="sv_x_IdEleve" id="sv_x_IdEleve" value="<?php echo $inscription->IdEleve->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($inscription->IdEleve->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($inscription->IdEleve->getPlaceHolder()) ?>"<?php echo $inscription->IdEleve->EditAttributes() ?>>
</span>
<input type="hidden" data-table="inscription" data-field="x_IdEleve" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $inscription->IdEleve->DisplayValueSeparatorAttribute() ?>" name="x_IdEleve" id="x_IdEleve" value="<?php echo ew_HtmlEncode($inscription->IdEleve->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<input type="hidden" name="q_x_IdEleve" id="q_x_IdEleve" value="<?php echo $inscription->IdEleve->LookupFilterQuery(true) ?>">
<script type="text/javascript">
finscriptionedit.CreateAutoSuggest({"id":"x_IdEleve","forceSelect":true});
</script>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($inscription->IdEleve->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_IdEleve',m:0,n:10,srch:false});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" name="s_x_IdEleve" id="s_x_IdEleve" value="<?php echo $inscription->IdEleve->LookupFilterQuery(false) ?>">
<?php if (AllowAdd(CurrentProjectID() . "eleve")) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $inscription->IdEleve->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_IdEleve',url:'eleveaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_IdEleve"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $inscription->IdEleve->FldCaption() ?></span></button>
<?php } ?>
<input type="hidden" name="s_x_IdEleve" id="s_x_IdEleve" value="<?php echo $inscription->IdEleve->LookupFilterQuery() ?>">
</span>
<?php echo $inscription->IdEleve->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($inscription->IdClasse->Visible) { // IdClasse ?>
	<div id="r_IdClasse" class="form-group">
		<label id="elh_inscription_IdClasse" class="col-sm-2 control-label ewLabel"><?php echo $inscription->IdClasse->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $inscription->IdClasse->CellAttributes() ?>>
<span id="el_inscription_IdClasse">
<?php
$wrkonchange = trim(" " . @$inscription->IdClasse->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$inscription->IdClasse->EditAttrs["onchange"] = "";
?>
<span id="as_x_IdClasse" style="white-space: nowrap; z-index: 8970">
	<input type="text" name="sv_x_IdClasse" id="sv_x_IdClasse" value="<?php echo $inscription->IdClasse->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($inscription->IdClasse->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($inscription->IdClasse->getPlaceHolder()) ?>"<?php echo $inscription->IdClasse->EditAttributes() ?>>
</span>
<input type="hidden" data-table="inscription" data-field="x_IdClasse" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $inscription->IdClasse->DisplayValueSeparatorAttribute() ?>" name="x_IdClasse" id="x_IdClasse" value="<?php echo ew_HtmlEncode($inscription->IdClasse->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<input type="hidden" name="q_x_IdClasse" id="q_x_IdClasse" value="<?php echo $inscription->IdClasse->LookupFilterQuery(true) ?>">
<script type="text/javascript">
finscriptionedit.CreateAutoSuggest({"id":"x_IdClasse","forceSelect":true});
</script>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($inscription->IdClasse->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_IdClasse',m:0,n:10,srch:false});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" name="s_x_IdClasse" id="s_x_IdClasse" value="<?php echo $inscription->IdClasse->LookupFilterQuery(false) ?>">
<?php if (AllowAdd(CurrentProjectID() . "classe")) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $inscription->IdClasse->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_IdClasse',url:'classeaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_IdClasse"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $inscription->IdClasse->FldCaption() ?></span></button>
<?php } ?>
<input type="hidden" name="s_x_IdClasse" id="s_x_IdClasse" value="<?php echo $inscription->IdClasse->LookupFilterQuery() ?>">
</span>
<?php echo $inscription->IdClasse->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($inscription->IdAnnee->Visible) { // IdAnnee ?>
	<div id="r_IdAnnee" class="form-group">
		<label id="elh_inscription_IdAnnee" class="col-sm-2 control-label ewLabel"><?php echo $inscription->IdAnnee->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $inscription->IdAnnee->CellAttributes() ?>>
<span id="el_inscription_IdAnnee">
<?php
$wrkonchange = trim(" " . @$inscription->IdAnnee->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$inscription->IdAnnee->EditAttrs["onchange"] = "";
?>
<span id="as_x_IdAnnee" style="white-space: nowrap; z-index: 8960">
	<input type="text" name="sv_x_IdAnnee" id="sv_x_IdAnnee" value="<?php echo $inscription->IdAnnee->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($inscription->IdAnnee->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($inscription->IdAnnee->getPlaceHolder()) ?>"<?php echo $inscription->IdAnnee->EditAttributes() ?>>
</span>
<input type="hidden" data-table="inscription" data-field="x_IdAnnee" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $inscription->IdAnnee->DisplayValueSeparatorAttribute() ?>" name="x_IdAnnee" id="x_IdAnnee" value="<?php echo ew_HtmlEncode($inscription->IdAnnee->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<input type="hidden" name="q_x_IdAnnee" id="q_x_IdAnnee" value="<?php echo $inscription->IdAnnee->LookupFilterQuery(true) ?>">
<script type="text/javascript">
finscriptionedit.CreateAutoSuggest({"id":"x_IdAnnee","forceSelect":true});
</script>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($inscription->IdAnnee->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_IdAnnee',m:0,n:10,srch:false});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" name="s_x_IdAnnee" id="s_x_IdAnnee" value="<?php echo $inscription->IdAnnee->LookupFilterQuery(false) ?>">
<?php if (AllowAdd(CurrentProjectID() . "annee")) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $inscription->IdAnnee->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_IdAnnee',url:'anneeaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_IdAnnee"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $inscription->IdAnnee->FldCaption() ?></span></button>
<?php } ?>
<input type="hidden" name="s_x_IdAnnee" id="s_x_IdAnnee" value="<?php echo $inscription->IdAnnee->LookupFilterQuery() ?>">
</span>
<?php echo $inscription->IdAnnee->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$inscription_edit->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $inscription_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php if (!isset($inscription_edit->Pager)) $inscription_edit->Pager = new cPrevNextPager($inscription_edit->StartRec, $inscription_edit->DisplayRecs, $inscription_edit->TotalRecs) ?>
<?php if ($inscription_edit->Pager->RecordCount > 0 && $inscription_edit->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($inscription_edit->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $inscription_edit->PageUrl() ?>start=<?php echo $inscription_edit->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($inscription_edit->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $inscription_edit->PageUrl() ?>start=<?php echo $inscription_edit->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $inscription_edit->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($inscription_edit->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $inscription_edit->PageUrl() ?>start=<?php echo $inscription_edit->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($inscription_edit->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $inscription_edit->PageUrl() ?>start=<?php echo $inscription_edit->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $inscription_edit->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
<?php } ?>
</form>
<script type="text/javascript">
finscriptionedit.Init();
</script>
<?php
$inscription_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$inscription_edit->Page_Terminate();
?>
