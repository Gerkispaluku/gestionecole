<?php

namespace PHPMaker2023\gestion_ECOLE;

// Page object
$EleveDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { eleve: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var felevedelete;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("felevedelete")
        .setPageId("delete")
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
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="felevedelete" id="felevedelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="on">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="eleve">
<input type="hidden" name="action" id="action" value="delete">
<?php foreach ($Page->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode(Config("COMPOSITE_KEY_SEPARATOR"), $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?= HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="card ew-card ew-grid <?= $Page->TableGridClass ?>">
<div class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>" style="<?= $Page->TableContainerStyle ?>">
<table class="<?= $Page->TableClass ?>">
    <thead>
    <tr class="ew-table-header">
<?php if ($Page->IdEleve->Visible) { // IdEleve ?>
        <th class="<?= $Page->IdEleve->headerCellClass() ?>"><span id="elh_eleve_IdEleve" class="eleve_IdEleve"><?= $Page->IdEleve->caption() ?></span></th>
<?php } ?>
<?php if ($Page->MatricEleve->Visible) { // MatricEleve ?>
        <th class="<?= $Page->MatricEleve->headerCellClass() ?>"><span id="elh_eleve_MatricEleve" class="eleve_MatricEleve"><?= $Page->MatricEleve->caption() ?></span></th>
<?php } ?>
<?php if ($Page->NomEleve->Visible) { // NomEleve ?>
        <th class="<?= $Page->NomEleve->headerCellClass() ?>"><span id="elh_eleve_NomEleve" class="eleve_NomEleve"><?= $Page->NomEleve->caption() ?></span></th>
<?php } ?>
<?php if ($Page->PostnomElve->Visible) { // PostnomElve ?>
        <th class="<?= $Page->PostnomElve->headerCellClass() ?>"><span id="elh_eleve_PostnomElve" class="eleve_PostnomElve"><?= $Page->PostnomElve->caption() ?></span></th>
<?php } ?>
<?php if ($Page->PrenomEleve->Visible) { // PrenomEleve ?>
        <th class="<?= $Page->PrenomEleve->headerCellClass() ?>"><span id="elh_eleve_PrenomEleve" class="eleve_PrenomEleve"><?= $Page->PrenomEleve->caption() ?></span></th>
<?php } ?>
<?php if ($Page->SexeEleve->Visible) { // SexeEleve ?>
        <th class="<?= $Page->SexeEleve->headerCellClass() ?>"><span id="elh_eleve_SexeEleve" class="eleve_SexeEleve"><?= $Page->SexeEleve->caption() ?></span></th>
<?php } ?>
<?php if ($Page->DatenaissanceEleve->Visible) { // DatenaissanceEleve ?>
        <th class="<?= $Page->DatenaissanceEleve->headerCellClass() ?>"><span id="elh_eleve_DatenaissanceEleve" class="eleve_DatenaissanceEleve"><?= $Page->DatenaissanceEleve->caption() ?></span></th>
<?php } ?>
<?php if ($Page->LieunaissanceEleve->Visible) { // LieunaissanceEleve ?>
        <th class="<?= $Page->LieunaissanceEleve->headerCellClass() ?>"><span id="elh_eleve_LieunaissanceEleve" class="eleve_LieunaissanceEleve"><?= $Page->LieunaissanceEleve->caption() ?></span></th>
<?php } ?>
<?php if ($Page->NomdupereElve->Visible) { // NomdupereElve ?>
        <th class="<?= $Page->NomdupereElve->headerCellClass() ?>"><span id="elh_eleve_NomdupereElve" class="eleve_NomdupereElve"><?= $Page->NomdupereElve->caption() ?></span></th>
<?php } ?>
<?php if ($Page->NomdelamereEleve->Visible) { // NomdelamereEleve ?>
        <th class="<?= $Page->NomdelamereEleve->headerCellClass() ?>"><span id="elh_eleve_NomdelamereEleve" class="eleve_NomdelamereEleve"><?= $Page->NomdelamereEleve->caption() ?></span></th>
<?php } ?>
    </tr>
    </thead>
    <tbody>
<?php
$Page->RecordCount = 0;
$i = 0;
while (!$Page->Recordset->EOF) {
    $Page->RecordCount++;
    $Page->RowCount++;

    // Set row properties
    $Page->resetAttributes();
    $Page->RowType = ROWTYPE_VIEW; // View

    // Get the field contents
    $Page->loadRowValues($Page->Recordset);

    // Render row
    $Page->renderRow();
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php if ($Page->IdEleve->Visible) { // IdEleve ?>
        <td<?= $Page->IdEleve->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_eleve_IdEleve" class="el_eleve_IdEleve">
<span<?= $Page->IdEleve->viewAttributes() ?>>
<?= $Page->IdEleve->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->MatricEleve->Visible) { // MatricEleve ?>
        <td<?= $Page->MatricEleve->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_eleve_MatricEleve" class="el_eleve_MatricEleve">
<span<?= $Page->MatricEleve->viewAttributes() ?>>
<?= $Page->MatricEleve->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->NomEleve->Visible) { // NomEleve ?>
        <td<?= $Page->NomEleve->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_eleve_NomEleve" class="el_eleve_NomEleve">
<span<?= $Page->NomEleve->viewAttributes() ?>>
<?= $Page->NomEleve->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->PostnomElve->Visible) { // PostnomElve ?>
        <td<?= $Page->PostnomElve->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_eleve_PostnomElve" class="el_eleve_PostnomElve">
<span<?= $Page->PostnomElve->viewAttributes() ?>>
<?= $Page->PostnomElve->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->PrenomEleve->Visible) { // PrenomEleve ?>
        <td<?= $Page->PrenomEleve->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_eleve_PrenomEleve" class="el_eleve_PrenomEleve">
<span<?= $Page->PrenomEleve->viewAttributes() ?>>
<?= $Page->PrenomEleve->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->SexeEleve->Visible) { // SexeEleve ?>
        <td<?= $Page->SexeEleve->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_eleve_SexeEleve" class="el_eleve_SexeEleve">
<span<?= $Page->SexeEleve->viewAttributes() ?>>
<?= $Page->SexeEleve->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->DatenaissanceEleve->Visible) { // DatenaissanceEleve ?>
        <td<?= $Page->DatenaissanceEleve->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_eleve_DatenaissanceEleve" class="el_eleve_DatenaissanceEleve">
<span<?= $Page->DatenaissanceEleve->viewAttributes() ?>>
<?= $Page->DatenaissanceEleve->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->LieunaissanceEleve->Visible) { // LieunaissanceEleve ?>
        <td<?= $Page->LieunaissanceEleve->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_eleve_LieunaissanceEleve" class="el_eleve_LieunaissanceEleve">
<span<?= $Page->LieunaissanceEleve->viewAttributes() ?>>
<?= $Page->LieunaissanceEleve->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->NomdupereElve->Visible) { // NomdupereElve ?>
        <td<?= $Page->NomdupereElve->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_eleve_NomdupereElve" class="el_eleve_NomdupereElve">
<span<?= $Page->NomdupereElve->viewAttributes() ?>>
<?= $Page->NomdupereElve->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->NomdelamereEleve->Visible) { // NomdelamereEleve ?>
        <td<?= $Page->NomdelamereEleve->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_eleve_NomdelamereEleve" class="el_eleve_NomdelamereEleve">
<span<?= $Page->NomdelamereEleve->viewAttributes() ?>>
<?= $Page->NomdelamereEleve->getViewValue() ?></span>
</span>
</td>
<?php } ?>
    </tr>
<?php
    $Page->Recordset->moveNext();
}
$Page->Recordset->close();
?>
</tbody>
</table>
</div>
</div>
<div class="ew-buttons ew-desktop-buttons">
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?= $Language->phrase("DeleteBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
</div>
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
