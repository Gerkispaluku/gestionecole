<?php

namespace PHPMaker2023\gestion_ECOLE;

// Page object
$ClasseDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { classe: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var fclassedelete;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fclassedelete")
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
<form name="fclassedelete" id="fclassedelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="on">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="classe">
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
<?php if ($Page->IdClasse->Visible) { // IdClasse ?>
        <th class="<?= $Page->IdClasse->headerCellClass() ?>"><span id="elh_classe_IdClasse" class="classe_IdClasse"><?= $Page->IdClasse->caption() ?></span></th>
<?php } ?>
<?php if ($Page->NomClasse->Visible) { // NomClasse ?>
        <th class="<?= $Page->NomClasse->headerCellClass() ?>"><span id="elh_classe_NomClasse" class="classe_NomClasse"><?= $Page->NomClasse->caption() ?></span></th>
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
<?php if ($Page->IdClasse->Visible) { // IdClasse ?>
        <td<?= $Page->IdClasse->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_classe_IdClasse" class="el_classe_IdClasse">
<span<?= $Page->IdClasse->viewAttributes() ?>>
<?= $Page->IdClasse->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->NomClasse->Visible) { // NomClasse ?>
        <td<?= $Page->NomClasse->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_classe_NomClasse" class="el_classe_NomClasse">
<span<?= $Page->NomClasse->viewAttributes() ?>>
<?= $Page->NomClasse->getViewValue() ?></span>
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
