<?php

namespace PHPMaker2023\gestion_ECOLE;

// Page object
$InscriptionList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { inscription: currentTable } });
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
</div>
<?php } ?>
<?php if (!$Page->IsModal) { ?>
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
<input type="hidden" name="t" value="inscription">
<?php if ($Page->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div id="gmp_inscription" class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>" style="<?= $Page->TableContainerStyle ?>">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit() || $Page->isMultiEdit()) { ?>
<table id="tbl_inscriptionlist" class="<?= $Page->TableClass ?>"><!-- .ew-table -->
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
<?php if ($Page->IdInscription->Visible) { // IdInscription ?>
        <th data-name="IdInscription" class="<?= $Page->IdInscription->headerCellClass() ?>"><div id="elh_inscription_IdInscription" class="inscription_IdInscription"><?= $Page->renderFieldHeader($Page->IdInscription) ?></div></th>
<?php } ?>
<?php if ($Page->IdEleve->Visible) { // IdEleve ?>
        <th data-name="IdEleve" class="<?= $Page->IdEleve->headerCellClass() ?>"><div id="elh_inscription_IdEleve" class="inscription_IdEleve"><?= $Page->renderFieldHeader($Page->IdEleve) ?></div></th>
<?php } ?>
<?php if ($Page->IdClasse->Visible) { // IdClasse ?>
        <th data-name="IdClasse" class="<?= $Page->IdClasse->headerCellClass() ?>"><div id="elh_inscription_IdClasse" class="inscription_IdClasse"><?= $Page->renderFieldHeader($Page->IdClasse) ?></div></th>
<?php } ?>
<?php if ($Page->IdAnnee->Visible) { // IdAnnee ?>
        <th data-name="IdAnnee" class="<?= $Page->IdAnnee->headerCellClass() ?>"><div id="elh_inscription_IdAnnee" class="inscription_IdAnnee"><?= $Page->renderFieldHeader($Page->IdAnnee) ?></div></th>
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
    <?php if ($Page->IdInscription->Visible) { // IdInscription ?>
        <td data-name="IdInscription"<?= $Page->IdInscription->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_inscription_IdInscription" class="el_inscription_IdInscription">
<span<?= $Page->IdInscription->viewAttributes() ?>>
<?= $Page->IdInscription->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->IdEleve->Visible) { // IdEleve ?>
        <td data-name="IdEleve"<?= $Page->IdEleve->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_inscription_IdEleve" class="el_inscription_IdEleve">
<span<?= $Page->IdEleve->viewAttributes() ?>>
<?= $Page->IdEleve->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->IdClasse->Visible) { // IdClasse ?>
        <td data-name="IdClasse"<?= $Page->IdClasse->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_inscription_IdClasse" class="el_inscription_IdClasse">
<span<?= $Page->IdClasse->viewAttributes() ?>>
<?= $Page->IdClasse->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->IdAnnee->Visible) { // IdAnnee ?>
        <td data-name="IdAnnee"<?= $Page->IdAnnee->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_inscription_IdAnnee" class="el_inscription_IdAnnee">
<span<?= $Page->IdAnnee->viewAttributes() ?>>
<?= $Page->IdAnnee->getViewValue() ?></span>
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
    ew.addEventHandlers("inscription");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
