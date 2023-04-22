<?php

namespace PHPMaker2023\gestion_ECOLE;

// Page object
$ClasseView = &$Page;
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
<form name="fclasseview" id="fclasseview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="on">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { classe: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var fclasseview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fclasseview")
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
<input type="hidden" name="t" value="classe">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->IdClasse->Visible) { // IdClasse ?>
    <tr id="r_IdClasse"<?= $Page->IdClasse->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_classe_IdClasse"><?= $Page->IdClasse->caption() ?></span></td>
        <td data-name="IdClasse"<?= $Page->IdClasse->cellAttributes() ?>>
<span id="el_classe_IdClasse" data-page="1">
<span<?= $Page->IdClasse->viewAttributes() ?>>
<?= $Page->IdClasse->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->NomClasse->Visible) { // NomClasse ?>
    <tr id="r_NomClasse"<?= $Page->NomClasse->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_classe_NomClasse"><?= $Page->NomClasse->caption() ?></span></td>
        <td data-name="NomClasse"<?= $Page->NomClasse->cellAttributes() ?>>
<span id="el_classe_NomClasse" data-page="1">
<span<?= $Page->NomClasse->viewAttributes() ?>>
<?= $Page->NomClasse->getViewValue() ?></span>
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
