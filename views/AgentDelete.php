<?php

namespace PHPMaker2023\gestion_ECOLE;

// Page object
$AgentDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { agent: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var fagentdelete;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fagentdelete")
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
<form name="fagentdelete" id="fagentdelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="on">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="agent">
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
<?php if ($Page->IdAgent->Visible) { // IdAgent ?>
        <th class="<?= $Page->IdAgent->headerCellClass() ?>"><span id="elh_agent_IdAgent" class="agent_IdAgent"><?= $Page->IdAgent->caption() ?></span></th>
<?php } ?>
<?php if ($Page->MatricAgent->Visible) { // MatricAgent ?>
        <th class="<?= $Page->MatricAgent->headerCellClass() ?>"><span id="elh_agent_MatricAgent" class="agent_MatricAgent"><?= $Page->MatricAgent->caption() ?></span></th>
<?php } ?>
<?php if ($Page->NomAgent->Visible) { // NomAgent ?>
        <th class="<?= $Page->NomAgent->headerCellClass() ?>"><span id="elh_agent_NomAgent" class="agent_NomAgent"><?= $Page->NomAgent->caption() ?></span></th>
<?php } ?>
<?php if ($Page->PostnomAgent->Visible) { // PostnomAgent ?>
        <th class="<?= $Page->PostnomAgent->headerCellClass() ?>"><span id="elh_agent_PostnomAgent" class="agent_PostnomAgent"><?= $Page->PostnomAgent->caption() ?></span></th>
<?php } ?>
<?php if ($Page->PrenomAgent->Visible) { // PrenomAgent ?>
        <th class="<?= $Page->PrenomAgent->headerCellClass() ?>"><span id="elh_agent_PrenomAgent" class="agent_PrenomAgent"><?= $Page->PrenomAgent->caption() ?></span></th>
<?php } ?>
<?php if ($Page->SexeAgent->Visible) { // SexeAgent ?>
        <th class="<?= $Page->SexeAgent->headerCellClass() ?>"><span id="elh_agent_SexeAgent" class="agent_SexeAgent"><?= $Page->SexeAgent->caption() ?></span></th>
<?php } ?>
<?php if ($Page->EtatCivilAgent->Visible) { // EtatCivilAgent ?>
        <th class="<?= $Page->EtatCivilAgent->headerCellClass() ?>"><span id="elh_agent_EtatCivilAgent" class="agent_EtatCivilAgent"><?= $Page->EtatCivilAgent->caption() ?></span></th>
<?php } ?>
<?php if ($Page->TelephoneAgent->Visible) { // TelephoneAgent ?>
        <th class="<?= $Page->TelephoneAgent->headerCellClass() ?>"><span id="elh_agent_TelephoneAgent" class="agent_TelephoneAgent"><?= $Page->TelephoneAgent->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Motsecret->Visible) { // Motsecret ?>
        <th class="<?= $Page->Motsecret->headerCellClass() ?>"><span id="elh_agent_Motsecret" class="agent_Motsecret"><?= $Page->Motsecret->caption() ?></span></th>
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
<?php if ($Page->IdAgent->Visible) { // IdAgent ?>
        <td<?= $Page->IdAgent->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_agent_IdAgent" class="el_agent_IdAgent">
<span<?= $Page->IdAgent->viewAttributes() ?>>
<?= $Page->IdAgent->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->MatricAgent->Visible) { // MatricAgent ?>
        <td<?= $Page->MatricAgent->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_agent_MatricAgent" class="el_agent_MatricAgent">
<span<?= $Page->MatricAgent->viewAttributes() ?>>
<?= $Page->MatricAgent->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->NomAgent->Visible) { // NomAgent ?>
        <td<?= $Page->NomAgent->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_agent_NomAgent" class="el_agent_NomAgent">
<span<?= $Page->NomAgent->viewAttributes() ?>>
<?= $Page->NomAgent->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->PostnomAgent->Visible) { // PostnomAgent ?>
        <td<?= $Page->PostnomAgent->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_agent_PostnomAgent" class="el_agent_PostnomAgent">
<span<?= $Page->PostnomAgent->viewAttributes() ?>>
<?= $Page->PostnomAgent->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->PrenomAgent->Visible) { // PrenomAgent ?>
        <td<?= $Page->PrenomAgent->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_agent_PrenomAgent" class="el_agent_PrenomAgent">
<span<?= $Page->PrenomAgent->viewAttributes() ?>>
<?= $Page->PrenomAgent->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->SexeAgent->Visible) { // SexeAgent ?>
        <td<?= $Page->SexeAgent->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_agent_SexeAgent" class="el_agent_SexeAgent">
<span<?= $Page->SexeAgent->viewAttributes() ?>>
<?= $Page->SexeAgent->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->EtatCivilAgent->Visible) { // EtatCivilAgent ?>
        <td<?= $Page->EtatCivilAgent->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_agent_EtatCivilAgent" class="el_agent_EtatCivilAgent">
<span<?= $Page->EtatCivilAgent->viewAttributes() ?>>
<?= $Page->EtatCivilAgent->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->TelephoneAgent->Visible) { // TelephoneAgent ?>
        <td<?= $Page->TelephoneAgent->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_agent_TelephoneAgent" class="el_agent_TelephoneAgent">
<span<?= $Page->TelephoneAgent->viewAttributes() ?>>
<?= $Page->TelephoneAgent->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Motsecret->Visible) { // Motsecret ?>
        <td<?= $Page->Motsecret->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_agent_Motsecret" class="el_agent_Motsecret">
<span<?= $Page->Motsecret->viewAttributes() ?>>
<?= $Page->Motsecret->getViewValue() ?></span>
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
