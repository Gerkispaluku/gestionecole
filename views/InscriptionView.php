<?php

namespace PHPMaker2023\gestion_ECOLE;

// Page object
$InscriptionView = &$Page;
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
<form name="finscriptionview" id="finscriptionview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="on">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { inscription: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var finscriptionview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("finscriptionview")
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
<input type="hidden" name="t" value="inscription">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->IdInscription->Visible) { // IdInscription ?>
    <tr id="r_IdInscription"<?= $Page->IdInscription->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_inscription_IdInscription"><?= $Page->IdInscription->caption() ?></span></td>
        <td data-name="IdInscription"<?= $Page->IdInscription->cellAttributes() ?>>
<span id="el_inscription_IdInscription" data-page="1">
<span<?= $Page->IdInscription->viewAttributes() ?>>
<?= $Page->IdInscription->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->IdEleve->Visible) { // IdEleve ?>
    <tr id="r_IdEleve"<?= $Page->IdEleve->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_inscription_IdEleve"><?= $Page->IdEleve->caption() ?></span></td>
        <td data-name="IdEleve"<?= $Page->IdEleve->cellAttributes() ?>>
<span id="el_inscription_IdEleve" data-page="1">
<span<?= $Page->IdEleve->viewAttributes() ?>>
<?= $Page->IdEleve->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->IdClasse->Visible) { // IdClasse ?>
    <tr id="r_IdClasse"<?= $Page->IdClasse->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_inscription_IdClasse"><?= $Page->IdClasse->caption() ?></span></td>
        <td data-name="IdClasse"<?= $Page->IdClasse->cellAttributes() ?>>
<span id="el_inscription_IdClasse" data-page="1">
<span<?= $Page->IdClasse->viewAttributes() ?>>
<?= $Page->IdClasse->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->IdAnnee->Visible) { // IdAnnee ?>
    <tr id="r_IdAnnee"<?= $Page->IdAnnee->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_inscription_IdAnnee"><?= $Page->IdAnnee->caption() ?></span></td>
        <td data-name="IdAnnee"<?= $Page->IdAnnee->cellAttributes() ?>>
<span id="el_inscription_IdAnnee" data-page="1">
<span<?= $Page->IdAnnee->viewAttributes() ?>>
<?= $Page->IdAnnee->getViewValue() ?></span>
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
