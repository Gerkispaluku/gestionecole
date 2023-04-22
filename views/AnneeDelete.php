<?php

namespace PHPMaker2023\gestion_ECOLE;

// Page object
$AnneeDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { annee: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var fanneedelete;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fanneedelete")
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
<form name="fanneedelete" id="fanneedelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="on">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="annee">
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
<?php if ($Page->IdAnnee->Visible) { // IdAnnee ?>
        <th class="<?= $Page->IdAnnee->headerCellClass() ?>"><span id="elh_annee_IdAnnee" class="annee_IdAnnee"><?= $Page->IdAnnee->caption() ?></span></th>
<?php } ?>
<?php if ($Page->LibelleAnnee->Visible) { // LibelleAnnee ?>
        <th class="<?= $Page->LibelleAnnee->headerCellClass() ?>"><span id="elh_annee_LibelleAnnee" class="annee_LibelleAnnee"><?= $Page->LibelleAnnee->caption() ?></span></th>
<?php } ?>
<?php if ($Page->DatedebutAnnee->Visible) { // DatedebutAnnee ?>
        <th class="<?= $Page->DatedebutAnnee->headerCellClass() ?>"><span id="elh_annee_DatedebutAnnee" class="annee_DatedebutAnnee"><?= $Page->DatedebutAnnee->caption() ?></span></th>
<?php } ?>
<?php if ($Page->DatefinAnnee->Visible) { // DatefinAnnee ?>
        <th class="<?= $Page->DatefinAnnee->headerCellClass() ?>"><span id="elh_annee_DatefinAnnee" class="annee_DatefinAnnee"><?= $Page->DatefinAnnee->caption() ?></span></th>
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
<?php if ($Page->IdAnnee->Visible) { // IdAnnee ?>
        <td<?= $Page->IdAnnee->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_annee_IdAnnee" class="el_annee_IdAnnee">
<span<?= $Page->IdAnnee->viewAttributes() ?>>
<?= $Page->IdAnnee->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->LibelleAnnee->Visible) { // LibelleAnnee ?>
        <td<?= $Page->LibelleAnnee->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_annee_LibelleAnnee" class="el_annee_LibelleAnnee">
<span<?= $Page->LibelleAnnee->viewAttributes() ?>>
<?= $Page->LibelleAnnee->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->DatedebutAnnee->Visible) { // DatedebutAnnee ?>
        <td<?= $Page->DatedebutAnnee->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_annee_DatedebutAnnee" class="el_annee_DatedebutAnnee">
<span<?= $Page->DatedebutAnnee->viewAttributes() ?>>
<?= $Page->DatedebutAnnee->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->DatefinAnnee->Visible) { // DatefinAnnee ?>
        <td<?= $Page->DatefinAnnee->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_annee_DatefinAnnee" class="el_annee_DatefinAnnee">
<span<?= $Page->DatefinAnnee->viewAttributes() ?>>
<?= $Page->DatefinAnnee->getViewValue() ?></span>
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
