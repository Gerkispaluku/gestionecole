<?php

namespace PHPMaker2023\gestion_ECOLE;

// Page object
$FonctionAddopt = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { fonction: currentTable } });
var currentPageID = ew.PAGE_ID = "addopt";
var currentForm;
var ffonctionaddopt;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("ffonctionaddopt")
        .setPageId("addopt")

        // Add fields
        .setFields([
            ["NomFoction", [fields.NomFoction.visible && fields.NomFoction.required ? ew.Validators.required(fields.NomFoction.caption) : null], fields.NomFoction.isInvalid]
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
<form name="ffonctionaddopt" id="ffonctionaddopt" class="ew-form" action="<?= HtmlEncode(GetUrl(Config("API_URL"))) ?>" method="post" novalidate autocomplete="on">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="<?= Config("API_ACTION_NAME") ?>" id="<?= Config("API_ACTION_NAME") ?>" value="<?= Config("API_ADD_ACTION") ?>">
<input type="hidden" name="<?= Config("API_OBJECT_NAME") ?>" id="<?= Config("API_OBJECT_NAME") ?>" value="fonction">
<input type="hidden" name="addopt" id="addopt" value="1">
<?php if ($Page->NomFoction->Visible) { // NomFoction ?>
    <div<?= $Page->NomFoction->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label" for="x_NomFoction"><?= $Page->NomFoction->caption() ?><?= $Page->NomFoction->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->NomFoction->cellAttributes() ?>>
<input type="<?= $Page->NomFoction->getInputTextType() ?>" name="x_NomFoction" id="x_NomFoction" data-table="fonction" data-field="x_NomFoction" value="<?= $Page->NomFoction->EditValue ?>" size="30" maxlength="25" placeholder="<?= HtmlEncode($Page->NomFoction->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->NomFoction->formatPattern()) ?>"<?= $Page->NomFoction->editAttributes() ?> aria-describedby="x_NomFoction_help">
<?= $Page->NomFoction->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->NomFoction->getErrorMessage() ?></div>
</div></div>
    </div>
<?php } ?>
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("fonction");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
