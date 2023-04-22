<?php

namespace PHPMaker2023\gestion_ECOLE;

// Page object
$EleveView = &$Page;
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
<form name="feleveview" id="feleveview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="on">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { eleve: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var feleveview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("feleveview")
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
<input type="hidden" name="t" value="eleve">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->IdEleve->Visible) { // IdEleve ?>
    <tr id="r_IdEleve"<?= $Page->IdEleve->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_eleve_IdEleve"><?= $Page->IdEleve->caption() ?></span></td>
        <td data-name="IdEleve"<?= $Page->IdEleve->cellAttributes() ?>>
<span id="el_eleve_IdEleve" data-page="1">
<span<?= $Page->IdEleve->viewAttributes() ?>>
<?= $Page->IdEleve->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->MatricEleve->Visible) { // MatricEleve ?>
    <tr id="r_MatricEleve"<?= $Page->MatricEleve->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_eleve_MatricEleve"><?= $Page->MatricEleve->caption() ?></span></td>
        <td data-name="MatricEleve"<?= $Page->MatricEleve->cellAttributes() ?>>
<span id="el_eleve_MatricEleve" data-page="1">
<span<?= $Page->MatricEleve->viewAttributes() ?>>
<?= $Page->MatricEleve->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->NomEleve->Visible) { // NomEleve ?>
    <tr id="r_NomEleve"<?= $Page->NomEleve->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_eleve_NomEleve"><?= $Page->NomEleve->caption() ?></span></td>
        <td data-name="NomEleve"<?= $Page->NomEleve->cellAttributes() ?>>
<span id="el_eleve_NomEleve" data-page="1">
<span<?= $Page->NomEleve->viewAttributes() ?>>
<?= $Page->NomEleve->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->PostnomElve->Visible) { // PostnomElve ?>
    <tr id="r_PostnomElve"<?= $Page->PostnomElve->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_eleve_PostnomElve"><?= $Page->PostnomElve->caption() ?></span></td>
        <td data-name="PostnomElve"<?= $Page->PostnomElve->cellAttributes() ?>>
<span id="el_eleve_PostnomElve" data-page="1">
<span<?= $Page->PostnomElve->viewAttributes() ?>>
<?= $Page->PostnomElve->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->PrenomEleve->Visible) { // PrenomEleve ?>
    <tr id="r_PrenomEleve"<?= $Page->PrenomEleve->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_eleve_PrenomEleve"><?= $Page->PrenomEleve->caption() ?></span></td>
        <td data-name="PrenomEleve"<?= $Page->PrenomEleve->cellAttributes() ?>>
<span id="el_eleve_PrenomEleve" data-page="1">
<span<?= $Page->PrenomEleve->viewAttributes() ?>>
<?= $Page->PrenomEleve->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->SexeEleve->Visible) { // SexeEleve ?>
    <tr id="r_SexeEleve"<?= $Page->SexeEleve->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_eleve_SexeEleve"><?= $Page->SexeEleve->caption() ?></span></td>
        <td data-name="SexeEleve"<?= $Page->SexeEleve->cellAttributes() ?>>
<span id="el_eleve_SexeEleve" data-page="1">
<span<?= $Page->SexeEleve->viewAttributes() ?>>
<?= $Page->SexeEleve->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->DatenaissanceEleve->Visible) { // DatenaissanceEleve ?>
    <tr id="r_DatenaissanceEleve"<?= $Page->DatenaissanceEleve->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_eleve_DatenaissanceEleve"><?= $Page->DatenaissanceEleve->caption() ?></span></td>
        <td data-name="DatenaissanceEleve"<?= $Page->DatenaissanceEleve->cellAttributes() ?>>
<span id="el_eleve_DatenaissanceEleve" data-page="1">
<span<?= $Page->DatenaissanceEleve->viewAttributes() ?>>
<?= $Page->DatenaissanceEleve->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->LieunaissanceEleve->Visible) { // LieunaissanceEleve ?>
    <tr id="r_LieunaissanceEleve"<?= $Page->LieunaissanceEleve->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_eleve_LieunaissanceEleve"><?= $Page->LieunaissanceEleve->caption() ?></span></td>
        <td data-name="LieunaissanceEleve"<?= $Page->LieunaissanceEleve->cellAttributes() ?>>
<span id="el_eleve_LieunaissanceEleve" data-page="1">
<span<?= $Page->LieunaissanceEleve->viewAttributes() ?>>
<?= $Page->LieunaissanceEleve->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->NomdupereElve->Visible) { // NomdupereElve ?>
    <tr id="r_NomdupereElve"<?= $Page->NomdupereElve->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_eleve_NomdupereElve"><?= $Page->NomdupereElve->caption() ?></span></td>
        <td data-name="NomdupereElve"<?= $Page->NomdupereElve->cellAttributes() ?>>
<span id="el_eleve_NomdupereElve" data-page="1">
<span<?= $Page->NomdupereElve->viewAttributes() ?>>
<?= $Page->NomdupereElve->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->NomdelamereEleve->Visible) { // NomdelamereEleve ?>
    <tr id="r_NomdelamereEleve"<?= $Page->NomdelamereEleve->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_eleve_NomdelamereEleve"><?= $Page->NomdelamereEleve->caption() ?></span></td>
        <td data-name="NomdelamereEleve"<?= $Page->NomdelamereEleve->cellAttributes() ?>>
<span id="el_eleve_NomdelamereEleve" data-page="1">
<span<?= $Page->NomdelamereEleve->viewAttributes() ?>>
<?= $Page->NomdelamereEleve->getViewValue() ?></span>
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
