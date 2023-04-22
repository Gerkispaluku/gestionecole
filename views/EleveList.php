<?php

namespace PHPMaker2023\gestion_ECOLE;

// Page object
$EleveList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { eleve: currentTable } });
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
<form name="felevesrch" id="felevesrch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>" novalidate autocomplete="on">
<div id="felevesrch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { eleve: currentTable } });
var currentForm;
var felevesrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery,
        fields = currentTable.fields;

    // Form object for search
    let form = new ew.FormBuilder()
        .setId("felevesrch")
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
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "" ? " active" : "" ?>" form="felevesrch" data-ew-action="search-type"><?= $Language->phrase("QuickSearchAuto") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "=" ? " active" : "" ?>" form="felevesrch" data-ew-action="search-type" data-search-type="="><?= $Language->phrase("QuickSearchExact") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "AND" ? " active" : "" ?>" form="felevesrch" data-ew-action="search-type" data-search-type="AND"><?= $Language->phrase("QuickSearchAll") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "OR" ? " active" : "" ?>" form="felevesrch" data-ew-action="search-type" data-search-type="OR"><?= $Language->phrase("QuickSearchAny") ?></button>
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
<input type="hidden" name="t" value="eleve">
<?php if ($Page->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div id="gmp_eleve" class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>" style="<?= $Page->TableContainerStyle ?>">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit() || $Page->isMultiEdit()) { ?>
<table id="tbl_elevelist" class="<?= $Page->TableClass ?>"><!-- .ew-table -->
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
<?php if ($Page->IdEleve->Visible) { // IdEleve ?>
        <th data-name="IdEleve" class="<?= $Page->IdEleve->headerCellClass() ?>"><div id="elh_eleve_IdEleve" class="eleve_IdEleve"><?= $Page->renderFieldHeader($Page->IdEleve) ?></div></th>
<?php } ?>
<?php if ($Page->MatricEleve->Visible) { // MatricEleve ?>
        <th data-name="MatricEleve" class="<?= $Page->MatricEleve->headerCellClass() ?>"><div id="elh_eleve_MatricEleve" class="eleve_MatricEleve"><?= $Page->renderFieldHeader($Page->MatricEleve) ?></div></th>
<?php } ?>
<?php if ($Page->NomEleve->Visible) { // NomEleve ?>
        <th data-name="NomEleve" class="<?= $Page->NomEleve->headerCellClass() ?>"><div id="elh_eleve_NomEleve" class="eleve_NomEleve"><?= $Page->renderFieldHeader($Page->NomEleve) ?></div></th>
<?php } ?>
<?php if ($Page->PostnomElve->Visible) { // PostnomElve ?>
        <th data-name="PostnomElve" class="<?= $Page->PostnomElve->headerCellClass() ?>"><div id="elh_eleve_PostnomElve" class="eleve_PostnomElve"><?= $Page->renderFieldHeader($Page->PostnomElve) ?></div></th>
<?php } ?>
<?php if ($Page->PrenomEleve->Visible) { // PrenomEleve ?>
        <th data-name="PrenomEleve" class="<?= $Page->PrenomEleve->headerCellClass() ?>"><div id="elh_eleve_PrenomEleve" class="eleve_PrenomEleve"><?= $Page->renderFieldHeader($Page->PrenomEleve) ?></div></th>
<?php } ?>
<?php if ($Page->SexeEleve->Visible) { // SexeEleve ?>
        <th data-name="SexeEleve" class="<?= $Page->SexeEleve->headerCellClass() ?>"><div id="elh_eleve_SexeEleve" class="eleve_SexeEleve"><?= $Page->renderFieldHeader($Page->SexeEleve) ?></div></th>
<?php } ?>
<?php if ($Page->DatenaissanceEleve->Visible) { // DatenaissanceEleve ?>
        <th data-name="DatenaissanceEleve" class="<?= $Page->DatenaissanceEleve->headerCellClass() ?>"><div id="elh_eleve_DatenaissanceEleve" class="eleve_DatenaissanceEleve"><?= $Page->renderFieldHeader($Page->DatenaissanceEleve) ?></div></th>
<?php } ?>
<?php if ($Page->LieunaissanceEleve->Visible) { // LieunaissanceEleve ?>
        <th data-name="LieunaissanceEleve" class="<?= $Page->LieunaissanceEleve->headerCellClass() ?>"><div id="elh_eleve_LieunaissanceEleve" class="eleve_LieunaissanceEleve"><?= $Page->renderFieldHeader($Page->LieunaissanceEleve) ?></div></th>
<?php } ?>
<?php if ($Page->NomdupereElve->Visible) { // NomdupereElve ?>
        <th data-name="NomdupereElve" class="<?= $Page->NomdupereElve->headerCellClass() ?>"><div id="elh_eleve_NomdupereElve" class="eleve_NomdupereElve"><?= $Page->renderFieldHeader($Page->NomdupereElve) ?></div></th>
<?php } ?>
<?php if ($Page->NomdelamereEleve->Visible) { // NomdelamereEleve ?>
        <th data-name="NomdelamereEleve" class="<?= $Page->NomdelamereEleve->headerCellClass() ?>"><div id="elh_eleve_NomdelamereEleve" class="eleve_NomdelamereEleve"><?= $Page->renderFieldHeader($Page->NomdelamereEleve) ?></div></th>
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
    <?php if ($Page->IdEleve->Visible) { // IdEleve ?>
        <td data-name="IdEleve"<?= $Page->IdEleve->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_eleve_IdEleve" class="el_eleve_IdEleve">
<span<?= $Page->IdEleve->viewAttributes() ?>>
<?= $Page->IdEleve->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->MatricEleve->Visible) { // MatricEleve ?>
        <td data-name="MatricEleve"<?= $Page->MatricEleve->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_eleve_MatricEleve" class="el_eleve_MatricEleve">
<span<?= $Page->MatricEleve->viewAttributes() ?>>
<?= $Page->MatricEleve->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->NomEleve->Visible) { // NomEleve ?>
        <td data-name="NomEleve"<?= $Page->NomEleve->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_eleve_NomEleve" class="el_eleve_NomEleve">
<span<?= $Page->NomEleve->viewAttributes() ?>>
<?= $Page->NomEleve->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->PostnomElve->Visible) { // PostnomElve ?>
        <td data-name="PostnomElve"<?= $Page->PostnomElve->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_eleve_PostnomElve" class="el_eleve_PostnomElve">
<span<?= $Page->PostnomElve->viewAttributes() ?>>
<?= $Page->PostnomElve->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->PrenomEleve->Visible) { // PrenomEleve ?>
        <td data-name="PrenomEleve"<?= $Page->PrenomEleve->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_eleve_PrenomEleve" class="el_eleve_PrenomEleve">
<span<?= $Page->PrenomEleve->viewAttributes() ?>>
<?= $Page->PrenomEleve->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->SexeEleve->Visible) { // SexeEleve ?>
        <td data-name="SexeEleve"<?= $Page->SexeEleve->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_eleve_SexeEleve" class="el_eleve_SexeEleve">
<span<?= $Page->SexeEleve->viewAttributes() ?>>
<?= $Page->SexeEleve->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->DatenaissanceEleve->Visible) { // DatenaissanceEleve ?>
        <td data-name="DatenaissanceEleve"<?= $Page->DatenaissanceEleve->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_eleve_DatenaissanceEleve" class="el_eleve_DatenaissanceEleve">
<span<?= $Page->DatenaissanceEleve->viewAttributes() ?>>
<?= $Page->DatenaissanceEleve->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->LieunaissanceEleve->Visible) { // LieunaissanceEleve ?>
        <td data-name="LieunaissanceEleve"<?= $Page->LieunaissanceEleve->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_eleve_LieunaissanceEleve" class="el_eleve_LieunaissanceEleve">
<span<?= $Page->LieunaissanceEleve->viewAttributes() ?>>
<?= $Page->LieunaissanceEleve->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->NomdupereElve->Visible) { // NomdupereElve ?>
        <td data-name="NomdupereElve"<?= $Page->NomdupereElve->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_eleve_NomdupereElve" class="el_eleve_NomdupereElve">
<span<?= $Page->NomdupereElve->viewAttributes() ?>>
<?= $Page->NomdupereElve->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->NomdelamereEleve->Visible) { // NomdelamereEleve ?>
        <td data-name="NomdelamereEleve"<?= $Page->NomdelamereEleve->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_eleve_NomdelamereEleve" class="el_eleve_NomdelamereEleve">
<span<?= $Page->NomdelamereEleve->viewAttributes() ?>>
<?= $Page->NomdelamereEleve->getViewValue() ?></span>
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
    ew.addEventHandlers("eleve");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
