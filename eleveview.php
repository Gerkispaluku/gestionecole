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

$eleve_view = NULL; // Initialize page object first

class celeve_view extends celeve {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{84EC96E8-6D54-4BAB-B15E-1EA379B0779B}";

	// Table name
	var $TableName = 'eleve';

	// Page object name
	var $PageObjName = 'eleve_view';

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
		$KeyUrl = "";
		if (@$_GET["IdEleve"] <> "") {
			$this->RecKey["IdEleve"] = $_GET["IdEleve"];
			$KeyUrl .= "&amp;IdEleve=" . urlencode($this->RecKey["IdEleve"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Table object (agent)
		if (!isset($GLOBALS['agent'])) $GLOBALS['agent'] = new cagent();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

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

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
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
		if (!$Security->CanView()) {
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
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
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
	var $ExportOptions; // Export options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 1;
	var $DbMasterFilter;
	var $DbDetailFilter;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $RecCnt;
	var $RecKey = array();
	var $IsModal = FALSE;
	var $Recordset;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;
		global $gbSkipHeaderFooter;

		// Check modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;

		// Load current record
		$bLoadCurrentRecord = FALSE;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["IdEleve"] <> "") {
				$this->IdEleve->setQueryStringValue($_GET["IdEleve"]);
				$this->RecKey["IdEleve"] = $this->IdEleve->QueryStringValue;
			} elseif (@$_POST["IdEleve"] <> "") {
				$this->IdEleve->setFormValue($_POST["IdEleve"]);
				$this->RecKey["IdEleve"] = $this->IdEleve->FormValue;
			} else {
				$bLoadCurrentRecord = TRUE;
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
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
					if (!$bMatchRecord) {
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "elevelist.php"; // No matching record, return to list
					} else {
						$this->LoadRowValues($this->Recordset); // Load row values
					}
			}
		} else {
			$sReturnUrl = "elevelist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Set up Breadcrumb
		if ($this->Export == "")
			$this->SetupBreadcrumb();

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = &$options["action"];

		// Add
		$item = &$option->Add("add");
		$addcaption = ew_HtmlTitle($Language->Phrase("ViewPageAddLink"));
		if ($this->IsModal) // Modal
			$item->Body = "<a class=\"ewAction ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->AddUrl) . "',caption:'" . $addcaption . "'});\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());

		// Edit
		$item = &$option->Add("edit");
		$editcaption = ew_HtmlTitle($Language->Phrase("ViewPageEditLink"));
		if ($this->IsModal) // Modal
			$item->Body = "<a class=\"ewAction ewEdit\" title=\"" . $editcaption . "\" data-caption=\"" . $editcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->EditUrl) . "',caption:'" . $editcaption . "'});\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewEdit\" title=\"" . $editcaption . "\" data-caption=\"" . $editcaption . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		$item->Visible = ($this->EditUrl <> "" && $Security->CanEdit());

		// Copy
		$item = &$option->Add("copy");
		$copycaption = ew_HtmlTitle($Language->Phrase("ViewPageCopyLink"));
		if ($this->IsModal) // Modal
			$item->Body = "<a class=\"ewAction ewCopy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->CopyUrl) . "',caption:'" . $copycaption . "'});\">" . $Language->Phrase("ViewPageCopyLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewCopy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("ViewPageCopyLink") . "</a>";
		$item->Visible = ($this->CopyUrl <> "" && $Security->CanAdd());

		// Delete
		$item = &$option->Add("delete");
		$item->Body = "<a onclick=\"return ew_ConfirmDelete(this);\" class=\"ewAction ewDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		$item->Visible = ($this->DeleteUrl <> "" && $Security->CanDelete());

		// Set up action default
		$option = &$options["action"];
		$option->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
		$option->UseImageAndText = TRUE;
		$option->UseDropDownButton = TRUE;
		$option->UseButtonGroup = TRUE;
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
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
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();
		$this->SetupOtherOptions();

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("elevelist.php"), "", $this->TableVar, TRUE);
		$PageId = "view";
		$Breadcrumb->Add("view", $PageId, $url);
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
if (!isset($eleve_view)) $eleve_view = new celeve_view();

// Page init
$eleve_view->Page_Init();

// Page main
$eleve_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$eleve_view->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "view";
var CurrentForm = feleveview = new ew_Form("feleveview", "view");

// Form_CustomValidate event
feleveview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
feleveview.ValidateRequired = true;
<?php } else { ?>
feleveview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
feleveview.Lists["x_SexeEleve"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
feleveview.Lists["x_SexeEleve"].Options = <?php echo json_encode($eleve->SexeEleve->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php if (!$eleve_view->IsModal) { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php $eleve_view->ExportOptions->Render("body") ?>
<?php
	foreach ($eleve_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php if (!$eleve_view->IsModal) { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php $eleve_view->ShowPageHeader(); ?>
<?php
$eleve_view->ShowMessage();
?>
<?php if (!$eleve_view->IsModal) { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($eleve_view->Pager)) $eleve_view->Pager = new cPrevNextPager($eleve_view->StartRec, $eleve_view->DisplayRecs, $eleve_view->TotalRecs) ?>
<?php if ($eleve_view->Pager->RecordCount > 0 && $eleve_view->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($eleve_view->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $eleve_view->PageUrl() ?>start=<?php echo $eleve_view->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($eleve_view->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $eleve_view->PageUrl() ?>start=<?php echo $eleve_view->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $eleve_view->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($eleve_view->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $eleve_view->PageUrl() ?>start=<?php echo $eleve_view->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($eleve_view->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $eleve_view->PageUrl() ?>start=<?php echo $eleve_view->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $eleve_view->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<?php } ?>
<form name="feleveview" id="feleveview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($eleve_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $eleve_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="eleve">
<?php if ($eleve_view->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<table class="table table-bordered table-striped ewViewTable">
<?php if ($eleve->MatricEleve->Visible) { // MatricEleve ?>
	<tr id="r_MatricEleve">
		<td><span id="elh_eleve_MatricEleve"><?php echo $eleve->MatricEleve->FldCaption() ?></span></td>
		<td data-name="MatricEleve"<?php echo $eleve->MatricEleve->CellAttributes() ?>>
<span id="el_eleve_MatricEleve">
<span<?php echo $eleve->MatricEleve->ViewAttributes() ?>>
<?php echo $eleve->MatricEleve->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($eleve->NomEleve->Visible) { // NomEleve ?>
	<tr id="r_NomEleve">
		<td><span id="elh_eleve_NomEleve"><?php echo $eleve->NomEleve->FldCaption() ?></span></td>
		<td data-name="NomEleve"<?php echo $eleve->NomEleve->CellAttributes() ?>>
<span id="el_eleve_NomEleve">
<span<?php echo $eleve->NomEleve->ViewAttributes() ?>>
<?php echo $eleve->NomEleve->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($eleve->PostnomElve->Visible) { // PostnomElve ?>
	<tr id="r_PostnomElve">
		<td><span id="elh_eleve_PostnomElve"><?php echo $eleve->PostnomElve->FldCaption() ?></span></td>
		<td data-name="PostnomElve"<?php echo $eleve->PostnomElve->CellAttributes() ?>>
<span id="el_eleve_PostnomElve">
<span<?php echo $eleve->PostnomElve->ViewAttributes() ?>>
<?php echo $eleve->PostnomElve->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($eleve->PrenomEleve->Visible) { // PrenomEleve ?>
	<tr id="r_PrenomEleve">
		<td><span id="elh_eleve_PrenomEleve"><?php echo $eleve->PrenomEleve->FldCaption() ?></span></td>
		<td data-name="PrenomEleve"<?php echo $eleve->PrenomEleve->CellAttributes() ?>>
<span id="el_eleve_PrenomEleve">
<span<?php echo $eleve->PrenomEleve->ViewAttributes() ?>>
<?php echo $eleve->PrenomEleve->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($eleve->SexeEleve->Visible) { // SexeEleve ?>
	<tr id="r_SexeEleve">
		<td><span id="elh_eleve_SexeEleve"><?php echo $eleve->SexeEleve->FldCaption() ?></span></td>
		<td data-name="SexeEleve"<?php echo $eleve->SexeEleve->CellAttributes() ?>>
<span id="el_eleve_SexeEleve">
<span<?php echo $eleve->SexeEleve->ViewAttributes() ?>>
<?php echo $eleve->SexeEleve->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($eleve->DatenaissanceEleve->Visible) { // DatenaissanceEleve ?>
	<tr id="r_DatenaissanceEleve">
		<td><span id="elh_eleve_DatenaissanceEleve"><?php echo $eleve->DatenaissanceEleve->FldCaption() ?></span></td>
		<td data-name="DatenaissanceEleve"<?php echo $eleve->DatenaissanceEleve->CellAttributes() ?>>
<span id="el_eleve_DatenaissanceEleve">
<span<?php echo $eleve->DatenaissanceEleve->ViewAttributes() ?>>
<?php echo $eleve->DatenaissanceEleve->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($eleve->LieunaissanceEleve->Visible) { // LieunaissanceEleve ?>
	<tr id="r_LieunaissanceEleve">
		<td><span id="elh_eleve_LieunaissanceEleve"><?php echo $eleve->LieunaissanceEleve->FldCaption() ?></span></td>
		<td data-name="LieunaissanceEleve"<?php echo $eleve->LieunaissanceEleve->CellAttributes() ?>>
<span id="el_eleve_LieunaissanceEleve">
<span<?php echo $eleve->LieunaissanceEleve->ViewAttributes() ?>>
<?php echo $eleve->LieunaissanceEleve->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($eleve->NomdupereElve->Visible) { // NomdupereElve ?>
	<tr id="r_NomdupereElve">
		<td><span id="elh_eleve_NomdupereElve"><?php echo $eleve->NomdupereElve->FldCaption() ?></span></td>
		<td data-name="NomdupereElve"<?php echo $eleve->NomdupereElve->CellAttributes() ?>>
<span id="el_eleve_NomdupereElve">
<span<?php echo $eleve->NomdupereElve->ViewAttributes() ?>>
<?php echo $eleve->NomdupereElve->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($eleve->NomdelamereEleve->Visible) { // NomdelamereEleve ?>
	<tr id="r_NomdelamereEleve">
		<td><span id="elh_eleve_NomdelamereEleve"><?php echo $eleve->NomdelamereEleve->FldCaption() ?></span></td>
		<td data-name="NomdelamereEleve"<?php echo $eleve->NomdelamereEleve->CellAttributes() ?>>
<span id="el_eleve_NomdelamereEleve">
<span<?php echo $eleve->NomdelamereEleve->ViewAttributes() ?>>
<?php echo $eleve->NomdelamereEleve->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($eleve->Image_eleve->Visible) { // Image_eleve ?>
	<tr id="r_Image_eleve">
		<td><span id="elh_eleve_Image_eleve"><?php echo $eleve->Image_eleve->FldCaption() ?></span></td>
		<td data-name="Image_eleve"<?php echo $eleve->Image_eleve->CellAttributes() ?>>
<span id="el_eleve_Image_eleve">
<span>
<?php echo ew_GetFileViewTag($eleve->Image_eleve, $eleve->Image_eleve->ViewValue) ?>
</span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($eleve->Description_eleve->Visible) { // Description_eleve ?>
	<tr id="r_Description_eleve">
		<td><span id="elh_eleve_Description_eleve"><?php echo $eleve->Description_eleve->FldCaption() ?></span></td>
		<td data-name="Description_eleve"<?php echo $eleve->Description_eleve->CellAttributes() ?>>
<span id="el_eleve_Description_eleve">
<span<?php echo $eleve->Description_eleve->ViewAttributes() ?>>
<?php echo $eleve->Description_eleve->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php if (!$eleve_view->IsModal) { ?>
<?php if (!isset($eleve_view->Pager)) $eleve_view->Pager = new cPrevNextPager($eleve_view->StartRec, $eleve_view->DisplayRecs, $eleve_view->TotalRecs) ?>
<?php if ($eleve_view->Pager->RecordCount > 0 && $eleve_view->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($eleve_view->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $eleve_view->PageUrl() ?>start=<?php echo $eleve_view->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($eleve_view->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $eleve_view->PageUrl() ?>start=<?php echo $eleve_view->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $eleve_view->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($eleve_view->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $eleve_view->PageUrl() ?>start=<?php echo $eleve_view->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($eleve_view->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $eleve_view->PageUrl() ?>start=<?php echo $eleve_view->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $eleve_view->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
<?php } ?>
</form>
<script type="text/javascript">
feleveview.Init();
</script>
<?php
$eleve_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$eleve_view->Page_Terminate();
?>
