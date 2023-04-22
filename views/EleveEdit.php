<?php

namespace PHPMaker2023\gestion_ECOLE;

// Page object
$EleveEdit = &$Page;
?>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="edit">
<form name="feleveedit" id="feleveedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="on">
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { eleve: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var feleveedit;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("feleveedit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["IdEleve", [fields.IdEleve.visible && fields.IdEleve.required ? ew.Validators.required(fields.IdEleve.caption) : null], fields.IdEleve.isInvalid],
            ["MatricEleve", [fields.MatricEleve.visible && fields.MatricEleve.required ? ew.Validators.required(fields.MatricEleve.caption) : null, ew.Validators.integer], fields.MatricEleve.isInvalid],
            ["NomEleve", [fields.NomEleve.visible && fields.NomEleve.required ? ew.Validators.required(fields.NomEleve.caption) : null], fields.NomEleve.isInvalid],
            ["PostnomElve", [fields.PostnomElve.visible && fields.PostnomElve.required ? ew.Validators.required(fields.PostnomElve.caption) : null], fields.PostnomElve.isInvalid],
            ["PrenomEleve", [fields.PrenomEleve.visible && fields.PrenomEleve.required ? ew.Validators.required(fields.PrenomEleve.caption) : null], fields.PrenomEleve.isInvalid],
            ["SexeEleve", [fields.SexeEleve.visible && fields.SexeEleve.required ? ew.Validators.required(fields.SexeEleve.caption) : null], fields.SexeEleve.isInvalid],
            ["DatenaissanceEleve", [fields.DatenaissanceEleve.visible && fields.DatenaissanceEleve.required ? ew.Validators.required(fields.DatenaissanceEleve.caption) : null, ew.Validators.datetime(fields.DatenaissanceEleve.clientFormatPattern)], fields.DatenaissanceEleve.isInvalid],
            ["LieunaissanceEleve", [fields.LieunaissanceEleve.visible && fields.LieunaissanceEleve.required ? ew.Validators.required(fields.LieunaissanceEleve.caption) : null], fields.LieunaissanceEleve.isInvalid],
            ["NomdupereElve", [fields.NomdupereElve.visible && fields.NomdupereElve.required ? ew.Validators.required(fields.NomdupereElve.caption) : null], fields.NomdupereElve.isInvalid],
            ["NomdelamereEleve", [fields.NomdelamereEleve.visible && fields.NomdelamereEleve.required ? ew.Validators.required(fields.NomdelamereEleve.caption) : null], fields.NomdelamereEleve.isInvalid]
        ])

        // Form_CustomValidate
        .setCustomValidate(
            function (fobj) { // DO NOT CHANGE THIS LINE! (except for adding "async" keyword)!
                    // Your custom validation code here, return false if invalid.
                    return true;
                }
        )

        // Use JavaScript validation or not
        .setValidateRequired(ew.CLIENT_VALIDATE)

        // Dynamic selection lists
        .setLists({
            "IdEleve": <?= $Page->IdEleve->toClientList($Page) ?>,
            "SexeEleve": <?= $Page->SexeEleve->toClientList($Page) ?>,
        })
        .build();
    window[form.id] = form;
    currentForm = form;
    loadjs.done(form.id);
});
</script>
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="eleve">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->IdEleve->Visible) { // IdEleve ?>
    <div id="r_IdEleve"<?= $Page->IdEleve->rowAttributes() ?>>
        <label id="elh_eleve_IdEleve" for="x_IdEleve" class="<?= $Page->LeftColumnClass ?>"><?= $Page->IdEleve->caption() ?><?= $Page->IdEleve->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->IdEleve->cellAttributes() ?>>
<span id="el_eleve_IdEleve">
<span<?= $Page->IdEleve->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Page->IdEleve->getDisplayValue($Page->IdEleve->EditValue) ?></span></span>
<input type="hidden" data-table="eleve" data-field="x_IdEleve" data-hidden="1" name="x_IdEleve" id="x_IdEleve" value="<?= HtmlEncode($Page->IdEleve->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->MatricEleve->Visible) { // MatricEleve ?>
    <div id="r_MatricEleve"<?= $Page->MatricEleve->rowAttributes() ?>>
        <label id="elh_eleve_MatricEleve" for="x_MatricEleve" class="<?= $Page->LeftColumnClass ?>"><?= $Page->MatricEleve->caption() ?><?= $Page->MatricEleve->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->MatricEleve->cellAttributes() ?>>
<span id="el_eleve_MatricEleve">
<input type="<?= $Page->MatricEleve->getInputTextType() ?>" name="x_MatricEleve" id="x_MatricEleve" data-table="eleve" data-field="x_MatricEleve" value="<?= $Page->MatricEleve->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->MatricEleve->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->MatricEleve->formatPattern()) ?>"<?= $Page->MatricEleve->editAttributes() ?> aria-describedby="x_MatricEleve_help">
<?= $Page->MatricEleve->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->MatricEleve->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->NomEleve->Visible) { // NomEleve ?>
    <div id="r_NomEleve"<?= $Page->NomEleve->rowAttributes() ?>>
        <label id="elh_eleve_NomEleve" for="x_NomEleve" class="<?= $Page->LeftColumnClass ?>"><?= $Page->NomEleve->caption() ?><?= $Page->NomEleve->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->NomEleve->cellAttributes() ?>>
<span id="el_eleve_NomEleve">
<input type="<?= $Page->NomEleve->getInputTextType() ?>" name="x_NomEleve" id="x_NomEleve" data-table="eleve" data-field="x_NomEleve" value="<?= $Page->NomEleve->EditValue ?>" size="30" maxlength="25" placeholder="<?= HtmlEncode($Page->NomEleve->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->NomEleve->formatPattern()) ?>"<?= $Page->NomEleve->editAttributes() ?> aria-describedby="x_NomEleve_help">
<?= $Page->NomEleve->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->NomEleve->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->PostnomElve->Visible) { // PostnomElve ?>
    <div id="r_PostnomElve"<?= $Page->PostnomElve->rowAttributes() ?>>
        <label id="elh_eleve_PostnomElve" for="x_PostnomElve" class="<?= $Page->LeftColumnClass ?>"><?= $Page->PostnomElve->caption() ?><?= $Page->PostnomElve->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->PostnomElve->cellAttributes() ?>>
<span id="el_eleve_PostnomElve">
<input type="<?= $Page->PostnomElve->getInputTextType() ?>" name="x_PostnomElve" id="x_PostnomElve" data-table="eleve" data-field="x_PostnomElve" value="<?= $Page->PostnomElve->EditValue ?>" size="30" maxlength="25" placeholder="<?= HtmlEncode($Page->PostnomElve->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->PostnomElve->formatPattern()) ?>"<?= $Page->PostnomElve->editAttributes() ?> aria-describedby="x_PostnomElve_help">
<?= $Page->PostnomElve->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->PostnomElve->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->PrenomEleve->Visible) { // PrenomEleve ?>
    <div id="r_PrenomEleve"<?= $Page->PrenomEleve->rowAttributes() ?>>
        <label id="elh_eleve_PrenomEleve" for="x_PrenomEleve" class="<?= $Page->LeftColumnClass ?>"><?= $Page->PrenomEleve->caption() ?><?= $Page->PrenomEleve->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->PrenomEleve->cellAttributes() ?>>
<span id="el_eleve_PrenomEleve">
<input type="<?= $Page->PrenomEleve->getInputTextType() ?>" name="x_PrenomEleve" id="x_PrenomEleve" data-table="eleve" data-field="x_PrenomEleve" value="<?= $Page->PrenomEleve->EditValue ?>" size="30" maxlength="25" placeholder="<?= HtmlEncode($Page->PrenomEleve->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->PrenomEleve->formatPattern()) ?>"<?= $Page->PrenomEleve->editAttributes() ?> aria-describedby="x_PrenomEleve_help">
<?= $Page->PrenomEleve->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->PrenomEleve->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->SexeEleve->Visible) { // SexeEleve ?>
    <div id="r_SexeEleve"<?= $Page->SexeEleve->rowAttributes() ?>>
        <label id="elh_eleve_SexeEleve" class="<?= $Page->LeftColumnClass ?>"><?= $Page->SexeEleve->caption() ?><?= $Page->SexeEleve->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->SexeEleve->cellAttributes() ?>>
<span id="el_eleve_SexeEleve">
<template id="tp_x_SexeEleve">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="eleve" data-field="x_SexeEleve" name="x_SexeEleve" id="x_SexeEleve"<?= $Page->SexeEleve->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_SexeEleve" class="ew-item-list"></div>
<selection-list hidden
    id="x_SexeEleve"
    name="x_SexeEleve"
    value="<?= HtmlEncode($Page->SexeEleve->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_SexeEleve"
    data-target="dsl_x_SexeEleve"
    data-repeatcolumn="5"
    class="form-control<?= $Page->SexeEleve->isInvalidClass() ?>"
    data-table="eleve"
    data-field="x_SexeEleve"
    data-value-separator="<?= $Page->SexeEleve->displayValueSeparatorAttribute() ?>"
    <?= $Page->SexeEleve->editAttributes() ?>></selection-list>
<?= $Page->SexeEleve->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->SexeEleve->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->DatenaissanceEleve->Visible) { // DatenaissanceEleve ?>
    <div id="r_DatenaissanceEleve"<?= $Page->DatenaissanceEleve->rowAttributes() ?>>
        <label id="elh_eleve_DatenaissanceEleve" for="x_DatenaissanceEleve" class="<?= $Page->LeftColumnClass ?>"><?= $Page->DatenaissanceEleve->caption() ?><?= $Page->DatenaissanceEleve->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->DatenaissanceEleve->cellAttributes() ?>>
<span id="el_eleve_DatenaissanceEleve">
<input type="<?= $Page->DatenaissanceEleve->getInputTextType() ?>" name="x_DatenaissanceEleve" id="x_DatenaissanceEleve" data-table="eleve" data-field="x_DatenaissanceEleve" value="<?= $Page->DatenaissanceEleve->EditValue ?>" placeholder="<?= HtmlEncode($Page->DatenaissanceEleve->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->DatenaissanceEleve->formatPattern()) ?>"<?= $Page->DatenaissanceEleve->editAttributes() ?> aria-describedby="x_DatenaissanceEleve_help">
<?= $Page->DatenaissanceEleve->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->DatenaissanceEleve->getErrorMessage() ?></div>
<?php if (!$Page->DatenaissanceEleve->ReadOnly && !$Page->DatenaissanceEleve->Disabled && !isset($Page->DatenaissanceEleve->EditAttrs["readonly"]) && !isset($Page->DatenaissanceEleve->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["feleveedit", "datetimepicker"], function () {
    let format = "<?= DateFormat(0) ?>",
        options = {
            localization: {
                locale: ew.LANGUAGE_ID + "-u-nu-" + ew.getNumberingSystem(),
                hourCycle: format.match(/H/) ? "h24" : "h12",
                format,
                ...ew.language.phrase("datetimepicker")
            },
            display: {
                icons: {
                    previous: ew.IS_RTL ? "fa-solid fa-chevron-right" : "fa-solid fa-chevron-left",
                    next: ew.IS_RTL ? "fa-solid fa-chevron-left" : "fa-solid fa-chevron-right"
                },
                components: {
                    hours: !!format.match(/h/i),
                    minutes: !!format.match(/m/),
                    seconds: !!format.match(/s/i)
                },
                theme: ew.isDark() ? "dark" : "auto"
            }
        };
    ew.createDateTimePicker("feleveedit", "x_DatenaissanceEleve", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->LieunaissanceEleve->Visible) { // LieunaissanceEleve ?>
    <div id="r_LieunaissanceEleve"<?= $Page->LieunaissanceEleve->rowAttributes() ?>>
        <label id="elh_eleve_LieunaissanceEleve" for="x_LieunaissanceEleve" class="<?= $Page->LeftColumnClass ?>"><?= $Page->LieunaissanceEleve->caption() ?><?= $Page->LieunaissanceEleve->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->LieunaissanceEleve->cellAttributes() ?>>
<span id="el_eleve_LieunaissanceEleve">
<input type="<?= $Page->LieunaissanceEleve->getInputTextType() ?>" name="x_LieunaissanceEleve" id="x_LieunaissanceEleve" data-table="eleve" data-field="x_LieunaissanceEleve" value="<?= $Page->LieunaissanceEleve->EditValue ?>" size="30" maxlength="25" placeholder="<?= HtmlEncode($Page->LieunaissanceEleve->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->LieunaissanceEleve->formatPattern()) ?>"<?= $Page->LieunaissanceEleve->editAttributes() ?> aria-describedby="x_LieunaissanceEleve_help">
<?= $Page->LieunaissanceEleve->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->LieunaissanceEleve->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->NomdupereElve->Visible) { // NomdupereElve ?>
    <div id="r_NomdupereElve"<?= $Page->NomdupereElve->rowAttributes() ?>>
        <label id="elh_eleve_NomdupereElve" for="x_NomdupereElve" class="<?= $Page->LeftColumnClass ?>"><?= $Page->NomdupereElve->caption() ?><?= $Page->NomdupereElve->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->NomdupereElve->cellAttributes() ?>>
<span id="el_eleve_NomdupereElve">
<input type="<?= $Page->NomdupereElve->getInputTextType() ?>" name="x_NomdupereElve" id="x_NomdupereElve" data-table="eleve" data-field="x_NomdupereElve" value="<?= $Page->NomdupereElve->EditValue ?>" size="30" maxlength="25" placeholder="<?= HtmlEncode($Page->NomdupereElve->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->NomdupereElve->formatPattern()) ?>"<?= $Page->NomdupereElve->editAttributes() ?> aria-describedby="x_NomdupereElve_help">
<?= $Page->NomdupereElve->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->NomdupereElve->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->NomdelamereEleve->Visible) { // NomdelamereEleve ?>
    <div id="r_NomdelamereEleve"<?= $Page->NomdelamereEleve->rowAttributes() ?>>
        <label id="elh_eleve_NomdelamereEleve" for="x_NomdelamereEleve" class="<?= $Page->LeftColumnClass ?>"><?= $Page->NomdelamereEleve->caption() ?><?= $Page->NomdelamereEleve->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->NomdelamereEleve->cellAttributes() ?>>
<span id="el_eleve_NomdelamereEleve">
<input type="<?= $Page->NomdelamereEleve->getInputTextType() ?>" name="x_NomdelamereEleve" id="x_NomdelamereEleve" data-table="eleve" data-field="x_NomdelamereEleve" value="<?= $Page->NomdelamereEleve->EditValue ?>" size="30" maxlength="25" placeholder="<?= HtmlEncode($Page->NomdelamereEleve->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->NomdelamereEleve->formatPattern()) ?>"<?= $Page->NomdelamereEleve->editAttributes() ?> aria-describedby="x_NomdelamereEleve_help">
<?= $Page->NomdelamereEleve->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->NomdelamereEleve->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="feleveedit"><?= $Language->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="feleveedit" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
<?php } ?>
    </div><!-- /buttons offset -->
<?= $Page->IsModal ? "</template>" : "</div>" ?><!-- /buttons .row -->
</form>
</main>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("eleve");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
