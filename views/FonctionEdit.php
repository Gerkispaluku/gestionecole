<?php

namespace PHPMaker2023\gestion_ECOLE;

// Page object
$FonctionEdit = &$Page;
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
<form name="ffonctionedit" id="ffonctionedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="on">
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { fonction: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var ffonctionedit;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("ffonctionedit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["IdFonction", [fields.IdFonction.visible && fields.IdFonction.required ? ew.Validators.required(fields.IdFonction.caption) : null], fields.IdFonction.isInvalid],
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
            "IdFonction": <?= $Page->IdFonction->toClientList($Page) ?>,
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
<input type="hidden" name="t" value="fonction">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->IdFonction->Visible) { // IdFonction ?>
    <div id="r_IdFonction"<?= $Page->IdFonction->rowAttributes() ?>>
        <label id="elh_fonction_IdFonction" for="x_IdFonction" class="<?= $Page->LeftColumnClass ?>"><?= $Page->IdFonction->caption() ?><?= $Page->IdFonction->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->IdFonction->cellAttributes() ?>>
<span id="el_fonction_IdFonction">
<span<?= $Page->IdFonction->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Page->IdFonction->getDisplayValue($Page->IdFonction->EditValue) ?></span></span>
<input type="hidden" data-table="fonction" data-field="x_IdFonction" data-hidden="1" name="x_IdFonction" id="x_IdFonction" value="<?= HtmlEncode($Page->IdFonction->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->NomFoction->Visible) { // NomFoction ?>
    <div id="r_NomFoction"<?= $Page->NomFoction->rowAttributes() ?>>
        <label id="elh_fonction_NomFoction" for="x_NomFoction" class="<?= $Page->LeftColumnClass ?>"><?= $Page->NomFoction->caption() ?><?= $Page->NomFoction->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->NomFoction->cellAttributes() ?>>
<span id="el_fonction_NomFoction">
<input type="<?= $Page->NomFoction->getInputTextType() ?>" name="x_NomFoction" id="x_NomFoction" data-table="fonction" data-field="x_NomFoction" value="<?= $Page->NomFoction->EditValue ?>" size="30" maxlength="25" placeholder="<?= HtmlEncode($Page->NomFoction->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->NomFoction->formatPattern()) ?>"<?= $Page->NomFoction->editAttributes() ?> aria-describedby="x_NomFoction_help">
<?= $Page->NomFoction->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->NomFoction->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="ffonctionedit"><?= $Language->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="ffonctionedit" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("fonction");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
