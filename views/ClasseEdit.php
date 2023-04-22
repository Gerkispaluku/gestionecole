<?php

namespace PHPMaker2023\gestion_ECOLE;

// Page object
$ClasseEdit = &$Page;
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
<form name="fclasseedit" id="fclasseedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="on">
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { classe: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var fclasseedit;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fclasseedit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["IdClasse", [fields.IdClasse.visible && fields.IdClasse.required ? ew.Validators.required(fields.IdClasse.caption) : null], fields.IdClasse.isInvalid],
            ["NomClasse", [fields.NomClasse.visible && fields.NomClasse.required ? ew.Validators.required(fields.NomClasse.caption) : null], fields.NomClasse.isInvalid]
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
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="classe">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->IdClasse->Visible) { // IdClasse ?>
    <div id="r_IdClasse"<?= $Page->IdClasse->rowAttributes() ?>>
        <label id="elh_classe_IdClasse" class="<?= $Page->LeftColumnClass ?>"><?= $Page->IdClasse->caption() ?><?= $Page->IdClasse->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->IdClasse->cellAttributes() ?>>
<span id="el_classe_IdClasse">
<span<?= $Page->IdClasse->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->IdClasse->getDisplayValue($Page->IdClasse->EditValue))) ?>"></span>
<input type="hidden" data-table="classe" data-field="x_IdClasse" data-hidden="1" name="x_IdClasse" id="x_IdClasse" value="<?= HtmlEncode($Page->IdClasse->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->NomClasse->Visible) { // NomClasse ?>
    <div id="r_NomClasse"<?= $Page->NomClasse->rowAttributes() ?>>
        <label id="elh_classe_NomClasse" for="x_NomClasse" class="<?= $Page->LeftColumnClass ?>"><?= $Page->NomClasse->caption() ?><?= $Page->NomClasse->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->NomClasse->cellAttributes() ?>>
<span id="el_classe_NomClasse">
<input type="<?= $Page->NomClasse->getInputTextType() ?>" name="x_NomClasse" id="x_NomClasse" data-table="classe" data-field="x_NomClasse" value="<?= $Page->NomClasse->EditValue ?>" size="30" maxlength="25" placeholder="<?= HtmlEncode($Page->NomClasse->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->NomClasse->formatPattern()) ?>"<?= $Page->NomClasse->editAttributes() ?> aria-describedby="x_NomClasse_help">
<?= $Page->NomClasse->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->NomClasse->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fclasseedit"><?= $Language->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fclasseedit" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("classe");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
