<?php

// Global variable for table object
$agent = NULL;

//
// Table class for agent
//
class cagent extends cTable {
	var $IdAgent;
	var $MatricAgent;
	var $NomAgent;
	var $PostnomAgent;
	var $PrenomAgent;
	var $SexeAgent;
	var $EtatCivilAgent;
	var $TelephoneAgent;
	var $IdFonction;
	var $userlevel_id;
	var $Motdepasse;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'agent';
		$this->TableName = 'agent';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`agent`";
		$this->DBID = 'DB';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = PHPExcel_Worksheet_PageSetup::ORIENTATION_DEFAULT; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4; // Page size (PHPExcel only)
		$this->DetailAdd = TRUE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// IdAgent
		$this->IdAgent = new cField('agent', 'agent', 'x_IdAgent', 'IdAgent', '`IdAgent`', '`IdAgent`', 3, -1, FALSE, '`IdAgent`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->IdAgent->Sortable = TRUE; // Allow sort
		$this->IdAgent->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['IdAgent'] = &$this->IdAgent;

		// MatricAgent
		$this->MatricAgent = new cField('agent', 'agent', 'x_MatricAgent', 'MatricAgent', '`MatricAgent`', '`MatricAgent`', 200, -1, FALSE, '`MatricAgent`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->MatricAgent->Sortable = TRUE; // Allow sort
		$this->fields['MatricAgent'] = &$this->MatricAgent;

		// NomAgent
		$this->NomAgent = new cField('agent', 'agent', 'x_NomAgent', 'NomAgent', '`NomAgent`', '`NomAgent`', 200, -1, FALSE, '`NomAgent`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->NomAgent->Sortable = TRUE; // Allow sort
		$this->fields['NomAgent'] = &$this->NomAgent;

		// PostnomAgent
		$this->PostnomAgent = new cField('agent', 'agent', 'x_PostnomAgent', 'PostnomAgent', '`PostnomAgent`', '`PostnomAgent`', 200, -1, FALSE, '`PostnomAgent`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->PostnomAgent->Sortable = TRUE; // Allow sort
		$this->fields['PostnomAgent'] = &$this->PostnomAgent;

		// PrenomAgent
		$this->PrenomAgent = new cField('agent', 'agent', 'x_PrenomAgent', 'PrenomAgent', '`PrenomAgent`', '`PrenomAgent`', 200, -1, FALSE, '`PrenomAgent`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->PrenomAgent->Sortable = TRUE; // Allow sort
		$this->fields['PrenomAgent'] = &$this->PrenomAgent;

		// SexeAgent
		$this->SexeAgent = new cField('agent', 'agent', 'x_SexeAgent', 'SexeAgent', '`SexeAgent`', '`SexeAgent`', 200, -1, FALSE, '`SexeAgent`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->SexeAgent->Sortable = TRUE; // Allow sort
		$this->SexeAgent->OptionCount = 2;
		$this->fields['SexeAgent'] = &$this->SexeAgent;

		// EtatCivilAgent
		$this->EtatCivilAgent = new cField('agent', 'agent', 'x_EtatCivilAgent', 'EtatCivilAgent', '`EtatCivilAgent`', '`EtatCivilAgent`', 200, -1, FALSE, '`EtatCivilAgent`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->EtatCivilAgent->Sortable = TRUE; // Allow sort
		$this->EtatCivilAgent->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->EtatCivilAgent->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->EtatCivilAgent->OptionCount = 4;
		$this->fields['EtatCivilAgent'] = &$this->EtatCivilAgent;

		// TelephoneAgent
		$this->TelephoneAgent = new cField('agent', 'agent', 'x_TelephoneAgent', 'TelephoneAgent', '`TelephoneAgent`', '`TelephoneAgent`', 3, -1, FALSE, '`TelephoneAgent`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->TelephoneAgent->Sortable = TRUE; // Allow sort
		$this->fields['TelephoneAgent'] = &$this->TelephoneAgent;

		// IdFonction
		$this->IdFonction = new cField('agent', 'agent', 'x_IdFonction', 'IdFonction', '`IdFonction`', '`IdFonction`', 3, -1, FALSE, '`EV__IdFonction`', TRUE, TRUE, TRUE, 'FORMATTED TEXT', 'TEXT');
		$this->IdFonction->Sortable = TRUE; // Allow sort
		$this->IdFonction->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['IdFonction'] = &$this->IdFonction;

		// userlevel_id
		$this->userlevel_id = new cField('agent', 'agent', 'x_userlevel_id', 'userlevel_id', '`userlevel_id`', '`userlevel_id`', 3, -1, FALSE, '`userlevel_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->userlevel_id->Sortable = TRUE; // Allow sort
		$this->userlevel_id->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->userlevel_id->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->userlevel_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['userlevel_id'] = &$this->userlevel_id;

		// Motdepasse
		$this->Motdepasse = new cField('agent', 'agent', 'x_Motdepasse', 'Motdepasse', '`Motdepasse`', '`Motdepasse`', 200, -1, FALSE, '`Motdepasse`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'PASSWORD');
		$this->Motdepasse->Sortable = TRUE; // Allow sort
		$this->fields['Motdepasse'] = &$this->Motdepasse;
	}

	// Set Field Visibility
	function SetFieldVisibility($fldparm) {
		global $Security;
		return $this->$fldparm->Visible; // Returns original value
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
			$sSortFieldList = ($ofld->FldVirtualExpression <> "") ? $ofld->FldVirtualExpression : $sSortField;
			$this->setSessionOrderByList($sSortFieldList . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Session ORDER BY for List page
	function getSessionOrderByList() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_ORDER_BY_LIST];
	}

	function setSessionOrderByList($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_ORDER_BY_LIST] = $v;
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`agent`";
	}

	function SqlFrom() { // For backward compatibility
		return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
		$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
		return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
		$this->_SqlSelect = $v;
	}
	var $_SqlSelectList = "";

	function getSqlSelectList() { // Select for List page
		$select = "";
		$select = "SELECT * FROM (" .
			"SELECT *, (SELECT `NomFoction` FROM `fonction` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`IdFonction` = `agent`.`IdFonction` LIMIT 1) AS `EV__IdFonction` FROM `agent`" .
			") `EW_TMP_TABLE`";
		return ($this->_SqlSelectList <> "") ? $this->_SqlSelectList : $select;
	}

	function SqlSelectList() { // For backward compatibility
		return $this->getSqlSelectList();
	}

	function setSqlSelectList($v) {
		$this->_SqlSelectList = $v;
	}
	var $_SqlWhere = "";

	function getSqlWhere() { // Where
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
		return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
		$this->_SqlWhere = $v;
	}
	var $_SqlGroupBy = "";

	function getSqlGroupBy() { // Group By
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
		return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
		$this->_SqlGroupBy = $v;
	}
	var $_SqlHaving = "";

	function getSqlHaving() { // Having
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
		return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
		$this->_SqlHaving = $v;
	}
	var $_SqlOrderBy = "";

	function getSqlOrderBy() { // Order By
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "";
	}

	function SqlOrderBy() { // For backward compatibility
		return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
		$this->_SqlOrderBy = $v;
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		global $Security;

		// Add User ID filter
		if ($Security->CurrentUserID() <> "" && !$Security->IsAdmin()) { // Non system admin
			$sFilter = $this->AddUserIDFilter($sFilter);
		}
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = $this->UserIDAllowSecurity;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	function SelectSQL() {
		$sFilter = $this->getSessionWhere();
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$this->Recordset_Selecting($sFilter);
		if ($this->UseVirtualFields()) {
			$sSort = $this->getSessionOrderByList();
			return ew_BuildSelectSql($this->getSqlSelectList(), $this->getSqlWhere(), $this->getSqlGroupBy(),
				$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
		} else {
			$sSort = $this->getSessionOrderBy();
			return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(),
				$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
		}
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = ($this->UseVirtualFields()) ? $this->getSessionOrderByList() : $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Check if virtual fields is used in SQL
	function UseVirtualFields() {
		$sWhere = $this->getSessionWhere();
		$sOrderBy = $this->getSessionOrderByList();
		if ($sWhere <> "")
			$sWhere = " " . str_replace(array("(",")"), array("",""), $sWhere) . " ";
		if ($sOrderBy <> "")
			$sOrderBy = " " . str_replace(array("(",")"), array("",""), $sOrderBy) . " ";
		if ($this->IdFonction->AdvancedSearch->SearchValue <> "" ||
			$this->IdFonction->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->IdFonction->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->IdFonction->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		return FALSE;
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		$cnt = -1;
		if (($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') && preg_match("/^SELECT \* FROM/i", $sSql)) {
			$sSql = "SELECT COUNT(*) FROM" . preg_replace('/^SELECT\s([\s\S]+)?\*\sFROM/i', "", $sSql);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		$conn = &$this->Connection();
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);

		//$sSql = $this->SQL();
		$sSql = $this->GetSQL($this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function SelectRecordCount() {
		$sSql = $this->SelectSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			$conn = &$this->Connection();
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// INSERT statement
	function InsertSQL(&$rs) {
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			if (EW_ENCRYPTED_PASSWORD && $name == 'Motdepasse')
				$value = (EW_CASE_SENSITIVE_PASSWORD) ? ew_EncryptPassword($value) : ew_EncryptPassword(strtolower($value));
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, -1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, -1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		$conn = &$this->Connection();
		$bInsert = $conn->Execute($this->InsertSQL($rs));
		if ($bInsert) {

			// Get insert id if necessary
			$this->IdAgent->setDbValue($conn->Insert_ID());
			$rs['IdAgent'] = $this->IdAgent->DbValue;
		}
		return $bInsert;
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			if (EW_ENCRYPTED_PASSWORD && $name == 'Motdepasse') {
				$value = (EW_CASE_SENSITIVE_PASSWORD) ? ew_EncryptPassword($value) : ew_EncryptPassword(strtolower($value));
			}
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL, $curfilter = TRUE) {
		$conn = &$this->Connection();
		$bUpdate = $conn->Execute($this->UpdateSQL($rs, $where, $curfilter));
		return $bUpdate;
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		if ($rs) {
			if (array_key_exists('IdAgent', $rs))
				ew_AddFilter($where, ew_QuotedName('IdAgent', $this->DBID) . '=' . ew_QuotedValue($rs['IdAgent'], $this->IdAgent->FldDataType, $this->DBID));
		}
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "", $curfilter = TRUE) {
		$conn = &$this->Connection();
		$bDelete = $conn->Execute($this->DeleteSQL($rs, $where, $curfilter));
		return $bDelete;
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`IdAgent` = @IdAgent@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->IdAgent->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@IdAgent@", ew_AdjustSql($this->IdAgent->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "agentlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "agentlist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("agentview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("agentview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "agentadd.php?" . $this->UrlParm($parm);
		else
			$url = "agentadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("agentedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("agentadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("agentdelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "IdAgent:" . ew_VarToJson($this->IdAgent->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->IdAgent->CurrentValue)) {
			$sUrl .= "IdAgent=" . urlencode($this->IdAgent->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return $this->AddMasterUrl(ew_CurrentPage() . "?" . $sUrlParm);
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = ew_StripSlashes($_POST["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
		} elseif (!empty($_GET) || !empty($_POST)) {
			$isPost = ew_IsHttpPost();
			if ($isPost && isset($_POST["IdAgent"]))
				$arKeys[] = ew_StripSlashes($_POST["IdAgent"]);
			elseif (isset($_GET["IdAgent"]))
				$arKeys[] = ew_StripSlashes($_GET["IdAgent"]);
			else
				$arKeys = NULL; // Do not setup

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		if (is_array($arKeys)) {
			foreach ($arKeys as $key) {
				if (!is_numeric($key))
					continue;
				$ar[] = $key;
			}
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->IdAgent->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$conn = &$this->Connection();
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->IdAgent->setDbValue($rs->fields('IdAgent'));
		$this->MatricAgent->setDbValue($rs->fields('MatricAgent'));
		$this->NomAgent->setDbValue($rs->fields('NomAgent'));
		$this->PostnomAgent->setDbValue($rs->fields('PostnomAgent'));
		$this->PrenomAgent->setDbValue($rs->fields('PrenomAgent'));
		$this->SexeAgent->setDbValue($rs->fields('SexeAgent'));
		$this->EtatCivilAgent->setDbValue($rs->fields('EtatCivilAgent'));
		$this->TelephoneAgent->setDbValue($rs->fields('TelephoneAgent'));
		$this->IdFonction->setDbValue($rs->fields('IdFonction'));
		$this->userlevel_id->setDbValue($rs->fields('userlevel_id'));
		$this->Motdepasse->setDbValue($rs->fields('Motdepasse'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
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

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// IdAgent
		$this->IdAgent->EditAttrs["class"] = "form-control";
		$this->IdAgent->EditCustomAttributes = "";
		$this->IdAgent->EditValue = $this->IdAgent->CurrentValue;
		$this->IdAgent->ViewCustomAttributes = "";

		// MatricAgent
		$this->MatricAgent->EditAttrs["class"] = "form-control";
		$this->MatricAgent->EditCustomAttributes = "";
		$this->MatricAgent->EditValue = $this->MatricAgent->CurrentValue;
		$this->MatricAgent->PlaceHolder = ew_RemoveHtml($this->MatricAgent->FldCaption());

		// NomAgent
		$this->NomAgent->EditAttrs["class"] = "form-control";
		$this->NomAgent->EditCustomAttributes = "";
		$this->NomAgent->EditValue = $this->NomAgent->CurrentValue;
		$this->NomAgent->PlaceHolder = ew_RemoveHtml($this->NomAgent->FldCaption());

		// PostnomAgent
		$this->PostnomAgent->EditAttrs["class"] = "form-control";
		$this->PostnomAgent->EditCustomAttributes = "";
		$this->PostnomAgent->EditValue = $this->PostnomAgent->CurrentValue;
		$this->PostnomAgent->PlaceHolder = ew_RemoveHtml($this->PostnomAgent->FldCaption());

		// PrenomAgent
		$this->PrenomAgent->EditAttrs["class"] = "form-control";
		$this->PrenomAgent->EditCustomAttributes = "";
		$this->PrenomAgent->EditValue = $this->PrenomAgent->CurrentValue;
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
		$this->TelephoneAgent->EditValue = $this->TelephoneAgent->CurrentValue;
		$this->TelephoneAgent->PlaceHolder = ew_RemoveHtml($this->TelephoneAgent->FldCaption());

		// IdFonction
		$this->IdFonction->EditAttrs["class"] = "form-control";
		$this->IdFonction->EditCustomAttributes = "";
		$this->IdFonction->EditValue = $this->IdFonction->CurrentValue;
		$this->IdFonction->PlaceHolder = ew_RemoveHtml($this->IdFonction->FldCaption());

		// userlevel_id
		$this->userlevel_id->EditAttrs["class"] = "form-control";
		$this->userlevel_id->EditCustomAttributes = "";
		if (!$Security->CanAdmin()) { // System admin
			$this->userlevel_id->EditValue = $Language->Phrase("PasswordMask");
		} else {
		}

		// Motdepasse
		$this->Motdepasse->EditAttrs["class"] = "form-control ewPasswordStrength";
		$this->Motdepasse->EditCustomAttributes = "";
		$this->Motdepasse->EditValue = $this->Motdepasse->CurrentValue;
		$this->Motdepasse->PlaceHolder = ew_RemoveHtml($this->Motdepasse->FldCaption());

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {

		// Call Row Rendered event
		$this->Row_Rendered();
	}
	var $ExportDoc;

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;
		if (!$Doc->ExportCustom) {

			// Write header
			$Doc->ExportTableHeader();
			if ($Doc->Horizontal) { // Horizontal format, write header
				$Doc->BeginExportRow();
				if ($ExportPageType == "view") {
					if ($this->MatricAgent->Exportable) $Doc->ExportCaption($this->MatricAgent);
					if ($this->NomAgent->Exportable) $Doc->ExportCaption($this->NomAgent);
					if ($this->PostnomAgent->Exportable) $Doc->ExportCaption($this->PostnomAgent);
					if ($this->PrenomAgent->Exportable) $Doc->ExportCaption($this->PrenomAgent);
					if ($this->SexeAgent->Exportable) $Doc->ExportCaption($this->SexeAgent);
					if ($this->EtatCivilAgent->Exportable) $Doc->ExportCaption($this->EtatCivilAgent);
					if ($this->TelephoneAgent->Exportable) $Doc->ExportCaption($this->TelephoneAgent);
					if ($this->userlevel_id->Exportable) $Doc->ExportCaption($this->userlevel_id);
					if ($this->Motdepasse->Exportable) $Doc->ExportCaption($this->Motdepasse);
				} else {
					if ($this->IdAgent->Exportable) $Doc->ExportCaption($this->IdAgent);
					if ($this->MatricAgent->Exportable) $Doc->ExportCaption($this->MatricAgent);
					if ($this->NomAgent->Exportable) $Doc->ExportCaption($this->NomAgent);
					if ($this->PostnomAgent->Exportable) $Doc->ExportCaption($this->PostnomAgent);
					if ($this->PrenomAgent->Exportable) $Doc->ExportCaption($this->PrenomAgent);
					if ($this->SexeAgent->Exportable) $Doc->ExportCaption($this->SexeAgent);
					if ($this->EtatCivilAgent->Exportable) $Doc->ExportCaption($this->EtatCivilAgent);
					if ($this->TelephoneAgent->Exportable) $Doc->ExportCaption($this->TelephoneAgent);
					if ($this->IdFonction->Exportable) $Doc->ExportCaption($this->IdFonction);
					if ($this->userlevel_id->Exportable) $Doc->ExportCaption($this->userlevel_id);
					if ($this->Motdepasse->Exportable) $Doc->ExportCaption($this->Motdepasse);
				}
				$Doc->EndExportRow();
			}
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->MatricAgent->Exportable) $Doc->ExportField($this->MatricAgent);
						if ($this->NomAgent->Exportable) $Doc->ExportField($this->NomAgent);
						if ($this->PostnomAgent->Exportable) $Doc->ExportField($this->PostnomAgent);
						if ($this->PrenomAgent->Exportable) $Doc->ExportField($this->PrenomAgent);
						if ($this->SexeAgent->Exportable) $Doc->ExportField($this->SexeAgent);
						if ($this->EtatCivilAgent->Exportable) $Doc->ExportField($this->EtatCivilAgent);
						if ($this->TelephoneAgent->Exportable) $Doc->ExportField($this->TelephoneAgent);
						if ($this->userlevel_id->Exportable) $Doc->ExportField($this->userlevel_id);
						if ($this->Motdepasse->Exportable) $Doc->ExportField($this->Motdepasse);
					} else {
						if ($this->IdAgent->Exportable) $Doc->ExportField($this->IdAgent);
						if ($this->MatricAgent->Exportable) $Doc->ExportField($this->MatricAgent);
						if ($this->NomAgent->Exportable) $Doc->ExportField($this->NomAgent);
						if ($this->PostnomAgent->Exportable) $Doc->ExportField($this->PostnomAgent);
						if ($this->PrenomAgent->Exportable) $Doc->ExportField($this->PrenomAgent);
						if ($this->SexeAgent->Exportable) $Doc->ExportField($this->SexeAgent);
						if ($this->EtatCivilAgent->Exportable) $Doc->ExportField($this->EtatCivilAgent);
						if ($this->TelephoneAgent->Exportable) $Doc->ExportField($this->TelephoneAgent);
						if ($this->IdFonction->Exportable) $Doc->ExportField($this->IdFonction);
						if ($this->userlevel_id->Exportable) $Doc->ExportField($this->userlevel_id);
						if ($this->Motdepasse->Exportable) $Doc->ExportField($this->Motdepasse);
					}
					$Doc->EndExportRow();
				}
			}

			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
			$Recordset->MoveNext();
		}
		if (!$Doc->ExportCustom) {
			$Doc->ExportTableFooter();
		}
	}

	// User ID filter
	function UserIDFilter($userid) {
		$sUserIDFilter = '`IdAgent` = ' . ew_QuotedValue($userid, EW_DATATYPE_NUMBER, EW_USER_TABLE_DBID);
		return $sUserIDFilter;
	}

	// Add User ID filter
	function AddUserIDFilter($sFilter) {
		global $Security;
		$sFilterWrk = "";
		$id = (CurrentPageID() == "list") ? $this->CurrentAction : CurrentPageID();
		if (!$this->UserIDAllow($id) && !$Security->IsAdmin()) {
			$sFilterWrk = $Security->UserIDList();
			if ($sFilterWrk <> "")
				$sFilterWrk = '`IdAgent` IN (' . $sFilterWrk . ')';
		}

		// Call User ID Filtering event
		$this->UserID_Filtering($sFilterWrk);
		ew_AddFilter($sFilter, $sFilterWrk);
		return $sFilter;
	}

	// User ID subquery
	function GetUserIDSubquery(&$fld, &$masterfld) {
		global $UserTableConn;
		$sWrk = "";
		$sSql = "SELECT " . $masterfld->FldExpression . " FROM `agent`";
		$sFilter = $this->AddUserIDFilter("");
		if ($sFilter <> "") $sSql .= " WHERE " . $sFilter;

		// Use subquery
		if (EW_USE_SUBQUERY_FOR_MASTER_USER_ID) {
			$sWrk = $sSql;
		} else {

			// List all values
			if ($rs = $UserTableConn->Execute($sSql)) {
				while (!$rs->EOF) {
					if ($sWrk <> "") $sWrk .= ",";
					$sWrk .= ew_QuotedValue($rs->fields[0], $masterfld->FldDataType, EW_USER_TABLE_DBID);
					$rs->MoveNext();
				}
				$rs->Close();
			}
		}
		if ($sWrk <> "") {
			$sWrk = $fld->FldExpression . " IN (" . $sWrk . ")";
		}
		return $sWrk;
	}

	// Get auto fill value
	function GetAutoFill($id, $val) {
		$rsarr = array();
		$rowcnt = 0;

		// Output
		if (is_array($rsarr) && $rowcnt > 0) {
			$fldcnt = count($rsarr[0]);
			for ($i = 0; $i < $rowcnt; $i++) {
				for ($j = 0; $j < $fldcnt; $j++) {
					$str = strval($rsarr[$i][$j]);
					$str = ew_ConvertToUtf8($str);
					if (isset($post["keepCRLF"])) {
						$str = str_replace(array("\r", "\n"), array("\\r", "\\n"), $str);
					} else {
						$str = str_replace(array("\r", "\n"), array(" ", " "), $str);
					}
					$rsarr[$i][$j] = $str;
				}
			}
			return ew_ArrayToJson($rsarr);
		} else {
			return FALSE;
		}
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here	
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here	
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here	
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Grid Inserting event
	function Grid_Inserting() {

		// Enter your code here
		// To reject grid insert, set return value to FALSE

		return TRUE;
	}

	// Grid Inserted event
	function Grid_Inserted($rsnew) {

		//echo "Grid Inserted";
	}

	// Grid Updating event
	function Grid_Updating($rsold) {

		// Enter your code here
		// To reject grid update, set return value to FALSE

		return TRUE;
	}

	// Grid Updated event
	function Grid_Updated($rsold, $rsnew) {

		//echo "Grid Updated";
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		//var_dump($fld->FldName, $fld->LookupFilters, $filter); // Uncomment to view the filter
		// Enter your code here

	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here	
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>); 

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
