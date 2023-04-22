<?php

// Global variable for table object
$eleve = NULL;

//
// Table class for eleve
//
class celeve extends cTable {
	var $IdEleve;
	var $MatricEleve;
	var $NomEleve;
	var $PostnomElve;
	var $PrenomEleve;
	var $SexeEleve;
	var $DatenaissanceEleve;
	var $LieunaissanceEleve;
	var $NomdupereElve;
	var $NomdelamereEleve;
	var $Image_eleve;
	var $Description_eleve;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'eleve';
		$this->TableName = 'eleve';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`eleve`";
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

		// IdEleve
		$this->IdEleve = new cField('eleve', 'eleve', 'x_IdEleve', 'IdEleve', '`IdEleve`', '`IdEleve`', 3, -1, FALSE, '`IdEleve`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->IdEleve->Sortable = TRUE; // Allow sort
		$this->IdEleve->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['IdEleve'] = &$this->IdEleve;

		// MatricEleve
		$this->MatricEleve = new cField('eleve', 'eleve', 'x_MatricEleve', 'MatricEleve', '`MatricEleve`', '`MatricEleve`', 3, -1, FALSE, '`MatricEleve`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->MatricEleve->Sortable = TRUE; // Allow sort
		$this->MatricEleve->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['MatricEleve'] = &$this->MatricEleve;

		// NomEleve
		$this->NomEleve = new cField('eleve', 'eleve', 'x_NomEleve', 'NomEleve', '`NomEleve`', '`NomEleve`', 200, -1, FALSE, '`NomEleve`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->NomEleve->Sortable = TRUE; // Allow sort
		$this->fields['NomEleve'] = &$this->NomEleve;

		// PostnomElve
		$this->PostnomElve = new cField('eleve', 'eleve', 'x_PostnomElve', 'PostnomElve', '`PostnomElve`', '`PostnomElve`', 200, -1, FALSE, '`PostnomElve`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->PostnomElve->Sortable = TRUE; // Allow sort
		$this->fields['PostnomElve'] = &$this->PostnomElve;

		// PrenomEleve
		$this->PrenomEleve = new cField('eleve', 'eleve', 'x_PrenomEleve', 'PrenomEleve', '`PrenomEleve`', '`PrenomEleve`', 200, -1, FALSE, '`PrenomEleve`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->PrenomEleve->Sortable = TRUE; // Allow sort
		$this->fields['PrenomEleve'] = &$this->PrenomEleve;

		// SexeEleve
		$this->SexeEleve = new cField('eleve', 'eleve', 'x_SexeEleve', 'SexeEleve', '`SexeEleve`', '`SexeEleve`', 200, -1, FALSE, '`SexeEleve`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->SexeEleve->Sortable = TRUE; // Allow sort
		$this->SexeEleve->OptionCount = 2;
		$this->fields['SexeEleve'] = &$this->SexeEleve;

		// DatenaissanceEleve
		$this->DatenaissanceEleve = new cField('eleve', 'eleve', 'x_DatenaissanceEleve', 'DatenaissanceEleve', '`DatenaissanceEleve`', ew_CastDateFieldForLike('`DatenaissanceEleve`', 0, "DB"), 133, 0, FALSE, '`DatenaissanceEleve`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->DatenaissanceEleve->Sortable = TRUE; // Allow sort
		$this->DatenaissanceEleve->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['DatenaissanceEleve'] = &$this->DatenaissanceEleve;

		// LieunaissanceEleve
		$this->LieunaissanceEleve = new cField('eleve', 'eleve', 'x_LieunaissanceEleve', 'LieunaissanceEleve', '`LieunaissanceEleve`', '`LieunaissanceEleve`', 200, -1, FALSE, '`LieunaissanceEleve`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->LieunaissanceEleve->Sortable = TRUE; // Allow sort
		$this->fields['LieunaissanceEleve'] = &$this->LieunaissanceEleve;

		// NomdupereElve
		$this->NomdupereElve = new cField('eleve', 'eleve', 'x_NomdupereElve', 'NomdupereElve', '`NomdupereElve`', '`NomdupereElve`', 200, -1, FALSE, '`NomdupereElve`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->NomdupereElve->Sortable = TRUE; // Allow sort
		$this->fields['NomdupereElve'] = &$this->NomdupereElve;

		// NomdelamereEleve
		$this->NomdelamereEleve = new cField('eleve', 'eleve', 'x_NomdelamereEleve', 'NomdelamereEleve', '`NomdelamereEleve`', '`NomdelamereEleve`', 200, -1, FALSE, '`NomdelamereEleve`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->NomdelamereEleve->Sortable = TRUE; // Allow sort
		$this->fields['NomdelamereEleve'] = &$this->NomdelamereEleve;

		// Image_eleve
		$this->Image_eleve = new cField('eleve', 'eleve', 'x_Image_eleve', 'Image_eleve', '`Image_eleve`', '`Image_eleve`', 200, -1, TRUE, '`Image_eleve`', FALSE, FALSE, FALSE, 'IMAGE', 'FILE');
		$this->Image_eleve->Sortable = TRUE; // Allow sort
		$this->fields['Image_eleve'] = &$this->Image_eleve;

		// Description_eleve
		$this->Description_eleve = new cField('eleve', 'eleve', 'x_Description_eleve', 'Description_eleve', '`Description_eleve`', '`Description_eleve`', 201, -1, FALSE, '`Description_eleve`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->Description_eleve->Sortable = TRUE; // Allow sort
		$this->fields['Description_eleve'] = &$this->Description_eleve;
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
		} else {
			$ofld->setSort("");
		}
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`eleve`";
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
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
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
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
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
			$this->IdEleve->setDbValue($conn->Insert_ID());
			$rs['IdEleve'] = $this->IdEleve->DbValue;
		}
		return $bInsert;
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
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
			if (array_key_exists('IdEleve', $rs))
				ew_AddFilter($where, ew_QuotedName('IdEleve', $this->DBID) . '=' . ew_QuotedValue($rs['IdEleve'], $this->IdEleve->FldDataType, $this->DBID));
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
		return "`IdEleve` = @IdEleve@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->IdEleve->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@IdEleve@", ew_AdjustSql($this->IdEleve->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "elevelist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "elevelist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("eleveview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("eleveview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "eleveadd.php?" . $this->UrlParm($parm);
		else
			$url = "eleveadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("eleveedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("eleveadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("elevedelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "IdEleve:" . ew_VarToJson($this->IdEleve->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->IdEleve->CurrentValue)) {
			$sUrl .= "IdEleve=" . urlencode($this->IdEleve->CurrentValue);
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
			if ($isPost && isset($_POST["IdEleve"]))
				$arKeys[] = ew_StripSlashes($_POST["IdEleve"]);
			elseif (isset($_GET["IdEleve"]))
				$arKeys[] = ew_StripSlashes($_GET["IdEleve"]);
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
			$this->IdEleve->CurrentValue = $key;
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
		$this->Description_eleve->setDbValue($rs->fields('Description_eleve'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
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

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// IdEleve
		$this->IdEleve->EditAttrs["class"] = "form-control";
		$this->IdEleve->EditCustomAttributes = "";
		$this->IdEleve->EditValue = $this->IdEleve->CurrentValue;
		$this->IdEleve->ViewCustomAttributes = "";

		// MatricEleve
		$this->MatricEleve->EditAttrs["class"] = "form-control";
		$this->MatricEleve->EditCustomAttributes = "";
		$this->MatricEleve->EditValue = $this->MatricEleve->CurrentValue;
		$this->MatricEleve->PlaceHolder = ew_RemoveHtml($this->MatricEleve->FldCaption());

		// NomEleve
		$this->NomEleve->EditAttrs["class"] = "form-control";
		$this->NomEleve->EditCustomAttributes = "";
		$this->NomEleve->EditValue = $this->NomEleve->CurrentValue;
		$this->NomEleve->PlaceHolder = ew_RemoveHtml($this->NomEleve->FldCaption());

		// PostnomElve
		$this->PostnomElve->EditAttrs["class"] = "form-control";
		$this->PostnomElve->EditCustomAttributes = "";
		$this->PostnomElve->EditValue = $this->PostnomElve->CurrentValue;
		$this->PostnomElve->PlaceHolder = ew_RemoveHtml($this->PostnomElve->FldCaption());

		// PrenomEleve
		$this->PrenomEleve->EditAttrs["class"] = "form-control";
		$this->PrenomEleve->EditCustomAttributes = "";
		$this->PrenomEleve->EditValue = $this->PrenomEleve->CurrentValue;
		$this->PrenomEleve->PlaceHolder = ew_RemoveHtml($this->PrenomEleve->FldCaption());

		// SexeEleve
		$this->SexeEleve->EditCustomAttributes = "";
		$this->SexeEleve->EditValue = $this->SexeEleve->Options(FALSE);

		// DatenaissanceEleve
		$this->DatenaissanceEleve->EditAttrs["class"] = "form-control";
		$this->DatenaissanceEleve->EditCustomAttributes = "";
		$this->DatenaissanceEleve->EditValue = ew_FormatDateTime($this->DatenaissanceEleve->CurrentValue, 8);
		$this->DatenaissanceEleve->PlaceHolder = ew_RemoveHtml($this->DatenaissanceEleve->FldCaption());

		// LieunaissanceEleve
		$this->LieunaissanceEleve->EditAttrs["class"] = "form-control";
		$this->LieunaissanceEleve->EditCustomAttributes = "";
		$this->LieunaissanceEleve->EditValue = $this->LieunaissanceEleve->CurrentValue;
		$this->LieunaissanceEleve->PlaceHolder = ew_RemoveHtml($this->LieunaissanceEleve->FldCaption());

		// NomdupereElve
		$this->NomdupereElve->EditAttrs["class"] = "form-control";
		$this->NomdupereElve->EditCustomAttributes = "";
		$this->NomdupereElve->EditValue = $this->NomdupereElve->CurrentValue;
		$this->NomdupereElve->PlaceHolder = ew_RemoveHtml($this->NomdupereElve->FldCaption());

		// NomdelamereEleve
		$this->NomdelamereEleve->EditAttrs["class"] = "form-control";
		$this->NomdelamereEleve->EditCustomAttributes = "";
		$this->NomdelamereEleve->EditValue = $this->NomdelamereEleve->CurrentValue;
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

		// Description_eleve
		$this->Description_eleve->EditAttrs["class"] = "form-control";
		$this->Description_eleve->EditCustomAttributes = "";
		$this->Description_eleve->EditValue = $this->Description_eleve->CurrentValue;
		$this->Description_eleve->PlaceHolder = ew_RemoveHtml($this->Description_eleve->FldCaption());

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
					if ($this->MatricEleve->Exportable) $Doc->ExportCaption($this->MatricEleve);
					if ($this->NomEleve->Exportable) $Doc->ExportCaption($this->NomEleve);
					if ($this->PostnomElve->Exportable) $Doc->ExportCaption($this->PostnomElve);
					if ($this->PrenomEleve->Exportable) $Doc->ExportCaption($this->PrenomEleve);
					if ($this->SexeEleve->Exportable) $Doc->ExportCaption($this->SexeEleve);
					if ($this->DatenaissanceEleve->Exportable) $Doc->ExportCaption($this->DatenaissanceEleve);
					if ($this->LieunaissanceEleve->Exportable) $Doc->ExportCaption($this->LieunaissanceEleve);
					if ($this->NomdupereElve->Exportable) $Doc->ExportCaption($this->NomdupereElve);
					if ($this->NomdelamereEleve->Exportable) $Doc->ExportCaption($this->NomdelamereEleve);
					if ($this->Image_eleve->Exportable) $Doc->ExportCaption($this->Image_eleve);
					if ($this->Description_eleve->Exportable) $Doc->ExportCaption($this->Description_eleve);
				} else {
					if ($this->IdEleve->Exportable) $Doc->ExportCaption($this->IdEleve);
					if ($this->MatricEleve->Exportable) $Doc->ExportCaption($this->MatricEleve);
					if ($this->NomEleve->Exportable) $Doc->ExportCaption($this->NomEleve);
					if ($this->PostnomElve->Exportable) $Doc->ExportCaption($this->PostnomElve);
					if ($this->PrenomEleve->Exportable) $Doc->ExportCaption($this->PrenomEleve);
					if ($this->SexeEleve->Exportable) $Doc->ExportCaption($this->SexeEleve);
					if ($this->DatenaissanceEleve->Exportable) $Doc->ExportCaption($this->DatenaissanceEleve);
					if ($this->LieunaissanceEleve->Exportable) $Doc->ExportCaption($this->LieunaissanceEleve);
					if ($this->NomdupereElve->Exportable) $Doc->ExportCaption($this->NomdupereElve);
					if ($this->NomdelamereEleve->Exportable) $Doc->ExportCaption($this->NomdelamereEleve);
					if ($this->Image_eleve->Exportable) $Doc->ExportCaption($this->Image_eleve);
					if ($this->Description_eleve->Exportable) $Doc->ExportCaption($this->Description_eleve);
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
						if ($this->MatricEleve->Exportable) $Doc->ExportField($this->MatricEleve);
						if ($this->NomEleve->Exportable) $Doc->ExportField($this->NomEleve);
						if ($this->PostnomElve->Exportable) $Doc->ExportField($this->PostnomElve);
						if ($this->PrenomEleve->Exportable) $Doc->ExportField($this->PrenomEleve);
						if ($this->SexeEleve->Exportable) $Doc->ExportField($this->SexeEleve);
						if ($this->DatenaissanceEleve->Exportable) $Doc->ExportField($this->DatenaissanceEleve);
						if ($this->LieunaissanceEleve->Exportable) $Doc->ExportField($this->LieunaissanceEleve);
						if ($this->NomdupereElve->Exportable) $Doc->ExportField($this->NomdupereElve);
						if ($this->NomdelamereEleve->Exportable) $Doc->ExportField($this->NomdelamereEleve);
						if ($this->Image_eleve->Exportable) $Doc->ExportField($this->Image_eleve);
						if ($this->Description_eleve->Exportable) $Doc->ExportField($this->Description_eleve);
					} else {
						if ($this->IdEleve->Exportable) $Doc->ExportField($this->IdEleve);
						if ($this->MatricEleve->Exportable) $Doc->ExportField($this->MatricEleve);
						if ($this->NomEleve->Exportable) $Doc->ExportField($this->NomEleve);
						if ($this->PostnomElve->Exportable) $Doc->ExportField($this->PostnomElve);
						if ($this->PrenomEleve->Exportable) $Doc->ExportField($this->PrenomEleve);
						if ($this->SexeEleve->Exportable) $Doc->ExportField($this->SexeEleve);
						if ($this->DatenaissanceEleve->Exportable) $Doc->ExportField($this->DatenaissanceEleve);
						if ($this->LieunaissanceEleve->Exportable) $Doc->ExportField($this->LieunaissanceEleve);
						if ($this->NomdupereElve->Exportable) $Doc->ExportField($this->NomdupereElve);
						if ($this->NomdelamereEleve->Exportable) $Doc->ExportField($this->NomdelamereEleve);
						if ($this->Image_eleve->Exportable) $Doc->ExportField($this->Image_eleve);
						if ($this->Description_eleve->Exportable) $Doc->ExportField($this->Description_eleve);
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
