<?php

namespace PHPMaker2023\gestion_ECOLE;

// Page object
$AgentView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<?php if (!$Page->isExport()) { ?>
<div class="btn-toolbar ew-toolbar">
<?php $Page->ExportOptions->render("body") ?>
<?php $Page->OtherOptions->render("body") ?>
</div>
<?php } ?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="view">
<form name="fagentview" id="fagentview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="on">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { agent: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var fagentview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fagentview")
        .setPageId("view")
        .build();
    window[form.id] = form;
    currentForm = form;
    loadjs.done(form.id);
});
</script>
<?php } ?>
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="agent">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->IdAgent->Visible) { // IdAgent ?>
    <tr id="r_IdAgent"<?= $Page->IdAgent->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_agent_IdAgent"><?= $Page->IdAgent->caption() ?></span></td>
        <td data-name="IdAgent"<?= $Page->IdAgent->cellAttributes() ?>>
<span id="el_agent_IdAgent" data-page="1">
<span<?= $Page->IdAgent->viewAttributes() ?>>
<?= $Page->IdAgent->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->MatricAgent->Visible) { // MatricAgent ?>
    <tr id="r_MatricAgent"<?= $Page->MatricAgent->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_agent_MatricAgent"><?= $Page->MatricAgent->caption() ?></span></td>
        <td data-name="MatricAgent"<?= $Page->MatricAgent->cellAttributes() ?>>
<span id="el_agent_MatricAgent" data-page="1">
<span<?= $Page->MatricAgent->viewAttributes() ?>>
<?= $Page->MatricAgent->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->NomAgent->Visible) { // NomAgent ?>
    <tr id="r_NomAgent"<?= $Page->NomAgent->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_agent_NomAgent"><?= $Page->NomAgent->caption() ?></span></td>
        <td data-name="NomAgent"<?= $Page->NomAgent->cellAttributes() ?>>
<span id="el_agent_NomAgent" data-page="1">
<span<?= $Page->NomAgent->viewAttributes() ?>>
<?= $Page->NomAgent->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->PostnomAgent->Visible) { // PostnomAgent ?>
    <tr id="r_PostnomAgent"<?= $Page->PostnomAgent->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_agent_PostnomAgent"><?= $Page->PostnomAgent->caption() ?></span></td>
        <td data-name="PostnomAgent"<?= $Page->PostnomAgent->cellAttributes() ?>>
<span id="el_agent_PostnomAgent" data-page="1">
<span<?= $Page->PostnomAgent->viewAttributes() ?>>
<?= $Page->PostnomAgent->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->PrenomAgent->Visible) { // PrenomAgent ?>
    <tr id="r_PrenomAgent"<?= $Page->PrenomAgent->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_agent_PrenomAgent"><?= $Page->PrenomAgent->caption() ?></span></td>
        <td data-name="PrenomAgent"<?= $Page->PrenomAgent->cellAttributes() ?>>
<span id="el_agent_PrenomAgent" data-page="1">
<span<?= $Page->PrenomAgent->viewAttributes() ?>>
<?= $Page->PrenomAgent->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->SexeAgent->Visible) { // SexeAgent ?>
    <tr id="r_SexeAgent"<?= $Page->SexeAgent->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_agent_SexeAgent"><?= $Page->SexeAgent->caption() ?></span></td>
        <td data-name="SexeAgent"<?= $Page->SexeAgent->cellAttributes() ?>>
<span id="el_agent_SexeAgent" data-page="1">
<span<?= $Page->SexeAgent->viewAttributes() ?>>
<?= $Page->SexeAgent->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->EtatCivilAgent->Visible) { // EtatCivilAgent ?>
    <tr id="r_EtatCivilAgent"<?= $Page->EtatCivilAgent->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_agent_EtatCivilAgent"><?= $Page->EtatCivilAgent->caption() ?></span></td>
        <td data-name="EtatCivilAgent"<?= $Page->EtatCivilAgent->cellAttributes() ?>>
<span id="el_agent_EtatCivilAgent" data-page="1">
<span<?= $Page->EtatCivilAgent->viewAttributes() ?>>
<?= $Page->EtatCivilAgent->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->TelephoneAgent->Visible) { // TelephoneAgent ?>
    <tr id="r_TelephoneAgent"<?= $Page->TelephoneAgent->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_agent_TelephoneAgent"><?= $Page->TelephoneAgent->caption() ?></span></td>
        <td data-name="TelephoneAgent"<?= $Page->TelephoneAgent->cellAttributes() ?>>
<span id="el_agent_TelephoneAgent" data-page="1">
<span<?= $Page->TelephoneAgent->viewAttributes() ?>>
<?= $Page->TelephoneAgent->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Motsecret->Visible) { // Motsecret ?>
    <tr id="r_Motsecret"<?= $Page->Motsecret->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_agent_Motsecret"><?= $Page->Motsecret->caption() ?></span></td>
        <td data-name="Motsecret"<?= $Page->Motsecret->cellAttributes() ?>>
<span id="el_agent_Motsecret" data-page="1">
<span<?= $Page->Motsecret->viewAttributes() ?>>
<?= $Page->Motsecret->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
</form>
</main>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<?php if (!$Page->isExport()) { ?>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
