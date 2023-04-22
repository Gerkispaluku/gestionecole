<?php

namespace PHPMaker2023\gestion_ECOLE;

// Page object
$AgentList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { agent: currentTable } });
var currentPageID = ew.PAGE_ID = "list";
var currentForm;
var <?= $Page->FormName ?>;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("<?= $Page->FormName ?>")
        .setPageId("list")
        .setSubmitWithFetch(<?= $Page->UseAjaxActions ? "true" : "false" ?>)
        .setFormKeyCountName("<?= $Page->FormKeyCountName ?>")
        .build();
    window[form.id] = form;
    currentForm = form;
    loadjs.done(form.id);
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<?php if (!$Page->isExport()) { ?>
<div class="btn-toolbar ew-toolbar">
<?php if ($Page->TotalRecords > 0 && $Page->ExportOptions->visible()) { ?>
<?php $Page->ExportOptions->render("body") ?>
<?php } ?>
<?php if ($Page->ImportOptions->visible()) { ?>
<?php $Page->ImportOptions->render("body") ?>
<?php } ?>
<?php if ($Page->SearchOptions->visible()) { ?>
<?php $Page->SearchOptions->render("body") ?>
<?php } ?>
<?php if ($Page->FilterOptions->visible()) { ?>
<?php $Page->FilterOptions->render("body") ?>
<?php } ?>
</div>
<?php } ?>
<?php if (!$Page->IsModal) { ?>
<form name="fagentsrch" id="fagentsrch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>" novalidate autocomplete="on">
<div id="fagentsrch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { agent: currentTable } });
var currentForm;
var fagentsrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery,
        fields = currentTable.fields;

    // Form object for search
    let form = new ew.FormBuilder()
        .setId("fagentsrch")
        .setPageId("list")
<?php if ($Page->UseAjaxActions) { ?>
        .setSubmitWithFetch(true)
<?php } ?>

        // Dynamic selection lists
        .setLists({
        })

        // Filters
        .setFilterList(<?= $Page->getFilterList() ?>)
        .build();
    window[form.id] = form;
    currentSearchForm = form;
    loadjs.done(form.id);
});
</script>
<input type="hidden" name="cmd" value="search">
<?php if ($Security->canSearch()) { ?>
<?php if (!$Page->isExport() && !($Page->CurrentAction && $Page->CurrentAction != "search") && $Page->hasSearchFields()) { ?>
<div class="ew-extended-search container-fluid ps-2">
<div class="row mb-0">
    <div class="col-sm-auto px-0 pe-sm-2">
        <div class="ew-basic-search input-group">
            <input type="search" name="<?= Config("TABLE_BASIC_SEARCH") ?>" id="<?= Config("TABLE_BASIC_SEARCH") ?>" class="form-control ew-basic-search-keyword" value="<?= HtmlEncode($Page->BasicSearch->getKeyword()) ?>" placeholder="<?= HtmlEncode($Language->phrase("Search")) ?>" aria-label="<?= HtmlEncode($Language->phrase("Search")) ?>">
            <input type="hidden" name="<?= Config("TABLE_BASIC_SEARCH_TYPE") ?>" id="<?= Config("TABLE_BASIC_SEARCH_TYPE") ?>" class="ew-basic-search-type" value="<?= HtmlEncode($Page->BasicSearch->getType()) ?>">
            <button type="button" data-bs-toggle="dropdown" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split" aria-haspopup="true" aria-expanded="false">
                <span id="searchtype"><?= $Page->BasicSearch->getTypeNameShort() ?></span>
            </button>
            <div class="dropdown-menu dropdown-menu-end">
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "" ? " active" : "" ?>" form="fagentsrch" data-ew-action="search-type"><?= $Language->phrase("QuickSearchAuto") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "=" ? " active" : "" ?>" form="fagentsrch" data-ew-action="search-type" data-search-type="="><?= $Language->phrase("QuickSearchExact") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "AND" ? " active" : "" ?>" form="fagentsrch" data-ew-action="search-type" data-search-type="AND"><?= $Language->phrase("QuickSearchAll") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "OR" ? " active" : "" ?>" form="fagentsrch" data-ew-action="search-type" data-search-type="OR"><?= $Language->phrase("QuickSearchAny") ?></button>
            </div>
        </div>
    </div>
    <div class="col-sm-auto mb-3">
        <button class="btn btn-primary" name="btn-submit" id="btn-submit" type="submit"><?= $Language->phrase("SearchBtn") ?></button>
    </div>
</div>
</div><!-- /.ew-extended-search -->
<?php } ?>
<?php } ?>
</div><!-- /.ew-search-panel -->
</form>
<?php } ?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="list<?= ($Page->TotalRecords == 0 && !$Page->isAdd()) ? " ew-no-record" : "" ?>">
<div id="ew-list">
<?php if ($Page->TotalRecords > 0 || $Page->CurrentAction) { ?>
<div class="card ew-card ew-grid<?= $Page->isAddOrEdit() ? " ew-grid-add-edit" : "" ?> <?= $Page->TableGridClass ?>">
<?php if (!$Page->isExport()) { ?>
<div class="card-header ew-grid-upper-panel">
<?php if (!$Page->isGridAdd() && !($Page->isGridEdit() && $Page->ModalGridEdit) && !$Page->isMultiEdit()) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
<div class="ew-list-other-options">
<?php $Page->OtherOptions->render("body") ?>
</div>
</div>
<?php } ?>
<form name="<?= $Page->FormName ?>" id="<?= $Page->FormName ?>" class="ew-form ew-list-form" action="<?= $Page->PageAction ?>" method="post" novalidate autocomplete="on">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="agent">
<?php if ($Page->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div id="gmp_agent" class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>" style="<?= $Page->TableContainerStyle ?>">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit() || $Page->isMultiEdit()) { ?>
<table id="tbl_agentlist" class="<?= $Page->TableClass ?>"><!-- .ew-table -->
<thead>
    <tr class="ew-table-header">
<?php
// Header row
$Page->RowType = ROWTYPE_HEADER;

// Render list options
$Page->renderListOptions();

// Render list options (header, left)
$Page->ListOptions->render("header", "left");
?>
<?php if ($Page->IdAgent->Visible) { // IdAgent ?>
        <th data-name="IdAgent" class="<?= $Page->IdAgent->headerCellClass() ?>"><div id="elh_agent_IdAgent" class="agent_IdAgent"><?= $Page->renderFieldHeader($Page->IdAgent) ?></div></th>
<?php } ?>
<?php if ($Page->MatricAgent->Visible) { // MatricAgent ?>
        <th data-name="MatricAgent" class="<?= $Page->MatricAgent->headerCellClass() ?>"><div id="elh_agent_MatricAgent" class="agent_MatricAgent"><?= $Page->renderFieldHeader($Page->MatricAgent) ?></div></th>
<?php } ?>
<?php if ($Page->NomAgent->Visible) { // NomAgent ?>
        <th data-name="NomAgent" class="<?= $Page->NomAgent->headerCellClass() ?>"><div id="elh_agent_NomAgent" class="agent_NomAgent"><?= $Page->renderFieldHeader($Page->NomAgent) ?></div></th>
<?php } ?>
<?php if ($Page->PostnomAgent->Visible) { // PostnomAgent ?>
        <th data-name="PostnomAgent" class="<?= $Page->PostnomAgent->headerCellClass() ?>"><div id="elh_agent_PostnomAgent" class="agent_PostnomAgent"><?= $Page->renderFieldHeader($Page->PostnomAgent) ?></div></th>
<?php } ?>
<?php if ($Page->PrenomAgent->Visible) { // PrenomAgent ?>
        <th data-name="PrenomAgent" class="<?= $Page->PrenomAgent->headerCellClass() ?>"><div id="elh_agent_PrenomAgent" class="agent_PrenomAgent"><?= $Page->renderFieldHeader($Page->PrenomAgent) ?></div></th>
<?php } ?>
<?php if ($Page->SexeAgent->Visible) { // SexeAgent ?>
        <th data-name="SexeAgent" class="<?= $Page->SexeAgent->headerCellClass() ?>"><div id="elh_agent_SexeAgent" class="agent_SexeAgent"><?= $Page->renderFieldHeader($Page->SexeAgent) ?></div></th>
<?php } ?>
<?php if ($Page->EtatCivilAgent->Visible) { // EtatCivilAgent ?>
        <th data-name="EtatCivilAgent" class="<?= $Page->EtatCivilAgent->headerCellClass() ?>"><div id="elh_agent_EtatCivilAgent" class="agent_EtatCivilAgent"><?= $Page->renderFieldHeader($Page->EtatCivilAgent) ?></div></th>
<?php } ?>
<?php if ($Page->TelephoneAgent->Visible) { // TelephoneAgent ?>
        <th data-name="TelephoneAgent" class="<?= $Page->TelephoneAgent->headerCellClass() ?>"><div id="elh_agent_TelephoneAgent" class="agent_TelephoneAgent"><?= $Page->renderFieldHeader($Page->TelephoneAgent) ?></div></th>
<?php } ?>
<?php if ($Page->Motsecret->Visible) { // Motsecret ?>
        <th data-name="Motsecret" class="<?= $Page->Motsecret->headerCellClass() ?>"><div id="elh_agent_Motsecret" class="agent_Motsecret"><?= $Page->renderFieldHeader($Page->Motsecret) ?></div></th>
<?php } ?>
<?php
// Render list options (header, right)
$Page->ListOptions->render("header", "right");
?>
    </tr>
</thead>
<tbody data-page="<?= $Page->getPageNumber() ?>">
<?php
$Page->setupGrid();
while ($Page->RecordCount < $Page->StopRecord || $Page->RowIndex === '$rowindex$') {
    $Page->RecordCount++;
    if ($Page->RecordCount >= $Page->StartRecord) {
        $Page->setupRow();
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php
// Render list options (body, left)
$Page->ListOptions->render("body", "left", $Page->RowCount);
?>
    <?php if ($Page->IdAgent->Visible) { // IdAgent ?>
        <td data-name="IdAgent"<?= $Page->IdAgent->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_agent_IdAgent" class="el_agent_IdAgent">
<span<?= $Page->IdAgent->viewAttributes() ?>>
<?= $Page->IdAgent->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->MatricAgent->Visible) { // MatricAgent ?>
        <td data-name="MatricAgent"<?= $Page->MatricAgent->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_agent_MatricAgent" class="el_agent_MatricAgent">
<span<?= $Page->MatricAgent->viewAttributes() ?>>
<?= $Page->MatricAgent->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->NomAgent->Visible) { // NomAgent ?>
        <td data-name="NomAgent"<?= $Page->NomAgent->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_agent_NomAgent" class="el_agent_NomAgent">
<span<?= $Page->NomAgent->viewAttributes() ?>>
<?= $Page->NomAgent->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->PostnomAgent->Visible) { // PostnomAgent ?>
        <td data-name="PostnomAgent"<?= $Page->PostnomAgent->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_agent_PostnomAgent" class="el_agent_PostnomAgent">
<span<?= $Page->PostnomAgent->viewAttributes() ?>>
<?= $Page->PostnomAgent->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->PrenomAgent->Visible) { // PrenomAgent ?>
        <td data-name="PrenomAgent"<?= $Page->PrenomAgent->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_agent_PrenomAgent" class="el_agent_PrenomAgent">
<span<?= $Page->PrenomAgent->viewAttributes() ?>>
<?= $Page->PrenomAgent->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->SexeAgent->Visible) { // SexeAgent ?>
        <td data-name="SexeAgent"<?= $Page->SexeAgent->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_agent_SexeAgent" class="el_agent_SexeAgent">
<span<?= $Page->SexeAgent->viewAttributes() ?>>
<?= $Page->SexeAgent->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->EtatCivilAgent->Visible) { // EtatCivilAgent ?>
        <td data-name="EtatCivilAgent"<?= $Page->EtatCivilAgent->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_agent_EtatCivilAgent" class="el_agent_EtatCivilAgent">
<span<?= $Page->EtatCivilAgent->viewAttributes() ?>>
<?= $Page->EtatCivilAgent->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->TelephoneAgent->Visible) { // TelephoneAgent ?>
        <td data-name="TelephoneAgent"<?= $Page->TelephoneAgent->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_agent_TelephoneAgent" class="el_agent_TelephoneAgent">
<span<?= $Page->TelephoneAgent->viewAttributes() ?>>
<?= $Page->TelephoneAgent->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->Motsecret->Visible) { // Motsecret ?>
        <td data-name="Motsecret"<?= $Page->Motsecret->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_agent_Motsecret" class="el_agent_Motsecret">
<span<?= $Page->Motsecret->viewAttributes() ?>>
<?= $Page->Motsecret->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Page->ListOptions->render("body", "right", $Page->RowCount);
?>
    </tr>
<?php
    }
    if (
        $Page->Recordset &&
        !$Page->Recordset->EOF &&
        $Page->RowIndex !== '$rowindex$' &&
        (!$Page->isGridAdd() || $Page->CurrentMode == "copy") &&
        (!(($Page->isCopy() || $Page->isAdd()) && $Page->RowIndex == 0))
    ) {
        $Page->Recordset->moveNext();
    }
    // Reset for template row
    if ($Page->RowIndex === '$rowindex$') {
        $Page->RowIndex = 0;
    }
    // Reset inline add/copy row
    if (($Page->isCopy() || $Page->isAdd()) && $Page->RowIndex == 0) {
        $Page->RowIndex = 1;
    }
}
?>
</tbody>
</table><!-- /.ew-table -->
<?php } ?>
</div><!-- /.ew-grid-middle-panel -->
<?php if (!$Page->CurrentAction && !$Page->UseAjaxActions) { ?>
<input type="hidden" name="action" id="action" value="">
<?php } ?>
</form><!-- /.ew-list-form -->
<?php
// Close recordset
if ($Page->Recordset) {
    $Page->Recordset->close();
}
?>
<?php if (!$Page->isExport()) { ?>
<div class="card-footer ew-grid-lower-panel">
<?php if (!$Page->isGridAdd() && !($Page->isGridEdit() && $Page->ModalGridEdit) && !$Page->isMultiEdit()) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
<div class="ew-list-other-options">
<?php $Page->OtherOptions->render("body", "bottom") ?>
</div>
</div>
<?php } ?>
</div><!-- /.ew-grid -->
<?php } else { ?>
<div class="ew-list-other-options">
<?php $Page->OtherOptions->render("body") ?>
</div>
<?php } ?>
</div>
</main>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<?php if (!$Page->isExport()) { ?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("agent");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
