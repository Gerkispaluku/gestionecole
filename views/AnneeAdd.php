<?php

namespace PHPMaker2023\gestion_ECOLE;

// Page object
$AnneeAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { annee: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var fanneeadd;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fanneeadd")
        .setPageId("add")

        // Add fields
        .setFields([
            ["LibelleAnnee", [fields.LibelleAnnee.visible && fields.LibelleAnnee.required ? ew.Validators.required(fields.LibelleAnnee.caption) : null], fields.LibelleAnnee.isInvalid],
            ["DatedebutAnnee", [fields.DatedebutAnnee.visible && fields.DatedebutAnnee.required ? ew.Validators.required(fields.DatedebutAnnee.caption) : null, ew.Validators.datetime(fields.DatedebutAnnee.clientFormatPattern)], fields.DatedebutAnnee.isInvalid],
            ["DatefinAnnee", [fields.DatefinAnnee.visible && fields.DatefinAnnee.required ? ew.Validators.required(fields.DatefinAnnee.caption) : null, ew.Validators.datetime(fields.DatefinAnnee.clientFormatPattern)], fields.DatefinAnnee.isInvalid]
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
        })
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
<form name="fanneeadd" id="fanneeadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="on">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="annee">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->LibelleAnnee->Visible) { // LibelleAnnee ?>
    <div id="r_LibelleAnnee"<?= $Page->LibelleAnnee->rowAttributes() ?>>
        <label id="elh_annee_LibelleAnnee" for="x_LibelleAnnee" class="<?= $Page->LeftColumnClass ?>"><?= $Page->LibelleAnnee->caption() ?><?= $Page->LibelleAnnee->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->LibelleAnnee->cellAttributes() ?>>
<span id="el_annee_LibelleAnnee">
<input type="<?= $Page->LibelleAnnee->getInputTextType() ?>" name="x_LibelleAnnee" id="x_LibelleAnnee" data-table="annee" data-field="x_LibelleAnnee" value="<?= $Page->LibelleAnnee->EditValue ?>" size="30" maxlength="25" placeholder="<?= HtmlEncode($Page->LibelleAnnee->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->LibelleAnnee->formatPattern()) ?>"<?= $Page->LibelleAnnee->editAttributes() ?> aria-describedby="x_LibelleAnnee_help">
<?= $Page->LibelleAnnee->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->LibelleAnnee->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->DatedebutAnnee->Visible) { // DatedebutAnnee ?>
    <div id="r_DatedebutAnnee"<?= $Page->DatedebutAnnee->rowAttributes() ?>>
        <label id="elh_annee_DatedebutAnnee" for="x_DatedebutAnnee" class="<?= $Page->LeftColumnClass ?>"><?= $Page->DatedebutAnnee->caption() ?><?= $Page->DatedebutAnnee->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->DatedebutAnnee->cellAttributes() ?>>
<span id="el_annee_DatedebutAnnee">
<input type="<?= $Page->DatedebutAnnee->getInputTextType() ?>" name="x_DatedebutAnnee" id="x_DatedebutAnnee" data-table="annee" data-field="x_DatedebutAnnee" value="<?= $Page->DatedebutAnnee->EditValue ?>" size="30" maxlength="25" placeholder="<?= HtmlEncode($Page->DatedebutAnnee->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->DatedebutAnnee->formatPattern()) ?>"<?= $Page->DatedebutAnnee->editAttributes() ?> aria-describedby="x_DatedebutAnnee_help">
<?= $Page->DatedebutAnnee->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->DatedebutAnnee->getErrorMessage() ?></div>
<?php if (!$Page->DatedebutAnnee->ReadOnly && !$Page->DatedebutAnnee->Disabled && !isset($Page->DatedebutAnnee->EditAttrs["readonly"]) && !isset($Page->DatedebutAnnee->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fanneeadd", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fanneeadd", "x_DatedebutAnnee", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->DatefinAnnee->Visible) { // DatefinAnnee ?>
    <div id="r_DatefinAnnee"<?= $Page->DatefinAnnee->rowAttributes() ?>>
        <label id="elh_annee_DatefinAnnee" for="x_DatefinAnnee" class="<?= $Page->LeftColumnClass ?>"><?= $Page->DatefinAnnee->caption() ?><?= $Page->DatefinAnnee->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->DatefinAnnee->cellAttributes() ?>>
<span id="el_annee_DatefinAnnee">
<input type="<?= $Page->DatefinAnnee->getInputTextType() ?>" name="x_DatefinAnnee" id="x_DatefinAnnee" data-table="annee" data-field="x_DatefinAnnee" value="<?= $Page->DatefinAnnee->EditValue ?>" size="30" maxlength="25" placeholder="<?= HtmlEncode($Page->DatefinAnnee->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->DatefinAnnee->formatPattern()) ?>"<?= $Page->DatefinAnnee->editAttributes() ?> aria-describedby="x_DatefinAnnee_help">
<?= $Page->DatefinAnnee->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->DatefinAnnee->getErrorMessage() ?></div>
<?php if (!$Page->DatefinAnnee->ReadOnly && !$Page->DatefinAnnee->Disabled && !isset($Page->DatefinAnnee->EditAttrs["readonly"]) && !isset($Page->DatefinAnnee->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fanneeadd", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fanneeadd", "x_DatefinAnnee", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fanneeadd"><?= $Language->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fanneeadd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
<?php } ?>
    </div><!-- /buttons offset -->
<?= $Page->IsModal ? "</template>" : "</div>" ?><!-- /buttons .row -->
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("annee");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
