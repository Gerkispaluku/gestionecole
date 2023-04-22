<?php

namespace PHPMaker2023\gestion_ECOLE;

// Page object
$FonctionView = &$Page;
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
<form name="ffonctionview" id="ffonctionview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="on">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { fonction: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var ffonctionview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("ffonctionview")
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
<input type="hidden" name="t" value="fonction">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->IdFonction->Visible) { // IdFonction ?>
    <tr id="r_IdFonction"<?= $Page->IdFonction->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_fonction_IdFonction"><?= $Page->IdFonction->caption() ?></span></td>
        <td data-name="IdFonction"<?= $Page->IdFonction->cellAttributes() ?>>
<span id="el_fonction_IdFonction" data-page="1">
<span<?= $Page->IdFonction->viewAttributes() ?>>
<?= $Page->IdFonction->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->NomFoction->Visible) { // NomFoction ?>
    <tr id="r_NomFoction"<?= $Page->NomFoction->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_fonction_NomFoction"><?= $Page->NomFoction->caption() ?></span></td>
        <td data-name="NomFoction"<?= $Page->NomFoction->cellAttributes() ?>>
<span id="el_fonction_NomFoction" data-page="1">
<span<?= $Page->NomFoction->viewAttributes() ?>>
<?= $Page->NomFoction->getViewValue() ?></span>
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
