<?php

namespace PHPMaker2023\gestion_ECOLE;

// Page object
$AffectationAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { affectation: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var faffectationadd;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("faffectationadd")
        .setPageId("add")

        // Add fields
        .setFields([
            ["IdAffectation", [fields.IdAffectation.visible && fields.IdAffectation.required ? ew.Validators.required(fields.IdAffectation.caption) : null, ew.Validators.integer], fields.IdAffectation.isInvalid],
            ["IdAgent", [fields.IdAgent.visible && fields.IdAgent.required ? ew.Validators.required(fields.IdAgent.caption) : null], fields.IdAgent.isInvalid],
            ["IdFonction", [fields.IdFonction.visible && fields.IdFonction.required ? ew.Validators.required(fields.IdFonction.caption) : null], fields.IdFonction.isInvalid]
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
            "IdAgent": <?= $Page->IdAgent->toClientList($Page) ?>,
            "IdFonction": <?= $Page->IdFonction->toClientList($Page) ?>,
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
<form name="faffectationadd" id="faffectationadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="on">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="affectation">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->IdAffectation->Visible) { // IdAffectation ?>
    <div id="r_IdAffectation"<?= $Page->IdAffectation->rowAttributes() ?>>
        <label id="elh_affectation_IdAffectation" for="x_IdAffectation" class="<?= $Page->LeftColumnClass ?>"><?= $Page->IdAffectation->caption() ?><?= $Page->IdAffectation->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->IdAffectation->cellAttributes() ?>>
<span id="el_affectation_IdAffectation">
<input type="<?= $Page->IdAffectation->getInputTextType() ?>" name="x_IdAffectation" id="x_IdAffectation" data-table="affectation" data-field="x_IdAffectation" value="<?= $Page->IdAffectation->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->IdAffectation->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->IdAffectation->formatPattern()) ?>"<?= $Page->IdAffectation->editAttributes() ?> aria-describedby="x_IdAffectation_help">
<?= $Page->IdAffectation->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->IdAffectation->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->IdAgent->Visible) { // IdAgent ?>
    <div id="r_IdAgent"<?= $Page->IdAgent->rowAttributes() ?>>
        <label id="elh_affectation_IdAgent" class="<?= $Page->LeftColumnClass ?>"><?= $Page->IdAgent->caption() ?><?= $Page->IdAgent->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->IdAgent->cellAttributes() ?>>
<span id="el_affectation_IdAgent">
    <select
        id="x_IdAgent"
        name="x_IdAgent"
        class="form-control ew-select<?= $Page->IdAgent->isInvalidClass() ?>"
        data-select2-id="faffectationadd_x_IdAgent"
        data-table="affectation"
        data-field="x_IdAgent"
        data-caption="<?= HtmlEncode(RemoveHtml($Page->IdAgent->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Page->IdAgent->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->IdAgent->getPlaceHolder()) ?>"
        <?= $Page->IdAgent->editAttributes() ?>>
        <?= $Page->IdAgent->selectOptionListHtml("x_IdAgent") ?>
    </select>
    <?= $Page->IdAgent->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->IdAgent->getErrorMessage() ?></div>
<?= $Page->IdAgent->Lookup->getParamTag($Page, "p_x_IdAgent") ?>
<script>
loadjs.ready("faffectationadd", function() {
    var options = { name: "x_IdAgent", selectId: "faffectationadd_x_IdAgent" };
    if (faffectationadd.lists.IdAgent?.lookupOptions.length) {
        options.data = { id: "x_IdAgent", form: "faffectationadd" };
    } else {
        options.ajax = { id: "x_IdAgent", form: "faffectationadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.affectation.fields.IdAgent.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->IdFonction->Visible) { // IdFonction ?>
    <div id="r_IdFonction"<?= $Page->IdFonction->rowAttributes() ?>>
        <label id="elh_affectation_IdFonction" class="<?= $Page->LeftColumnClass ?>"><?= $Page->IdFonction->caption() ?><?= $Page->IdFonction->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->IdFonction->cellAttributes() ?>>
<span id="el_affectation_IdFonction">
<div class="input-group flex-nowrap">
    <select
        id="x_IdFonction"
        name="x_IdFonction"
        class="form-control ew-select<?= $Page->IdFonction->isInvalidClass() ?>"
        data-select2-id="faffectationadd_x_IdFonction"
        data-table="affectation"
        data-field="x_IdFonction"
        data-caption="<?= HtmlEncode(RemoveHtml($Page->IdFonction->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Page->IdFonction->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->IdFonction->getPlaceHolder()) ?>"
        <?= $Page->IdFonction->editAttributes() ?>>
        <?= $Page->IdFonction->selectOptionListHtml("x_IdFonction") ?>
    </select>
    <button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x_IdFonction" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Page->IdFonction->caption() ?>" data-title="<?= $Page->IdFonction->caption() ?>" data-ew-action="add-option" data-el="x_IdFonction" data-url="<?= GetUrl("FonctionAddopt") ?>"><i class="fa-solid fa-plus ew-icon"></i></button>
</div>
<?= $Page->IdFonction->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->IdFonction->getErrorMessage() ?></div>
<?= $Page->IdFonction->Lookup->getParamTag($Page, "p_x_IdFonction") ?>
<script>
loadjs.ready("faffectationadd", function() {
    var options = { name: "x_IdFonction", selectId: "faffectationadd_x_IdFonction" };
    if (faffectationadd.lists.IdFonction?.lookupOptions.length) {
        options.data = { id: "x_IdFonction", form: "faffectationadd" };
    } else {
        options.ajax = { id: "x_IdFonction", form: "faffectationadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.affectation.fields.IdFonction.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="faffectationadd"><?= $Language->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="faffectationadd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("affectation");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
