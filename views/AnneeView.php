<?php

namespace PHPMaker2023\gestion_ECOLE;

// Page object
$AnneeView = &$Page;
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
<form name="fanneeview" id="fanneeview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="on">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { annee: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var fanneeview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fanneeview")
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
<input type="hidden" name="t" value="annee">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->IdAnnee->Visible) { // IdAnnee ?>
    <tr id="r_IdAnnee"<?= $Page->IdAnnee->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_annee_IdAnnee"><?= $Page->IdAnnee->caption() ?></span></td>
        <td data-name="IdAnnee"<?= $Page->IdAnnee->cellAttributes() ?>>
<span id="el_annee_IdAnnee" data-page="1">
<span<?= $Page->IdAnnee->viewAttributes() ?>>
<?= $Page->IdAnnee->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->LibelleAnnee->Visible) { // LibelleAnnee ?>
    <tr id="r_LibelleAnnee"<?= $Page->LibelleAnnee->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_annee_LibelleAnnee"><?= $Page->LibelleAnnee->caption() ?></span></td>
        <td data-name="LibelleAnnee"<?= $Page->LibelleAnnee->cellAttributes() ?>>
<span id="el_annee_LibelleAnnee" data-page="1">
<span<?= $Page->LibelleAnnee->viewAttributes() ?>>
<?= $Page->LibelleAnnee->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->DatedebutAnnee->Visible) { // DatedebutAnnee ?>
    <tr id="r_DatedebutAnnee"<?= $Page->DatedebutAnnee->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_annee_DatedebutAnnee"><?= $Page->DatedebutAnnee->caption() ?></span></td>
        <td data-name="DatedebutAnnee"<?= $Page->DatedebutAnnee->cellAttributes() ?>>
<span id="el_annee_DatedebutAnnee" data-page="1">
<span<?= $Page->DatedebutAnnee->viewAttributes() ?>>
<?= $Page->DatedebutAnnee->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->DatefinAnnee->Visible) { // DatefinAnnee ?>
    <tr id="r_DatefinAnnee"<?= $Page->DatefinAnnee->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_annee_DatefinAnnee"><?= $Page->DatefinAnnee->caption() ?></span></td>
        <td data-name="DatefinAnnee"<?= $Page->DatefinAnnee->cellAttributes() ?>>
<span id="el_annee_DatefinAnnee" data-page="1">
<span<?= $Page->DatefinAnnee->viewAttributes() ?>>
<?= $Page->DatefinAnnee->getViewValue() ?></span>
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
