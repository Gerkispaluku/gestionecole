<?php

namespace PHPMaker2023\gestion_ECOLE;

// Page object
$InscriptionAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { inscription: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var finscriptionadd;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("finscriptionadd")
        .setPageId("add")

        // Add fields
        .setFields([
            ["IdEleve", [fields.IdEleve.visible && fields.IdEleve.required ? ew.Validators.required(fields.IdEleve.caption) : null, ew.Validators.integer], fields.IdEleve.isInvalid],
            ["IdClasse", [fields.IdClasse.visible && fields.IdClasse.required ? ew.Validators.required(fields.IdClasse.caption) : null, ew.Validators.integer], fields.IdClasse.isInvalid],
            ["IdAnnee", [fields.IdAnnee.visible && fields.IdAnnee.required ? ew.Validators.required(fields.IdAnnee.caption) : null, ew.Validators.integer], fields.IdAnnee.isInvalid]
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
            "IdClasse": <?= $Page->IdClasse->toClientList($Page) ?>,
            "IdAnnee": <?= $Page->IdAnnee->toClientList($Page) ?>,
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
<form name="finscriptionadd" id="finscriptionadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="on">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="inscription">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->IdEleve->Visible) { // IdEleve ?>
    <div id="r_IdEleve"<?= $Page->IdEleve->rowAttributes() ?>>
        <label id="elh_inscription_IdEleve" class="<?= $Page->LeftColumnClass ?>"><?= $Page->IdEleve->caption() ?><?= $Page->IdEleve->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->IdEleve->cellAttributes() ?>>
<span id="el_inscription_IdEleve">
    <select
        id="x_IdEleve"
        name="x_IdEleve"
        class="form-control ew-select<?= $Page->IdEleve->isInvalidClass() ?>"
        data-select2-id="finscriptionadd_x_IdEleve"
        data-table="inscription"
        data-field="x_IdEleve"
        data-caption="<?= HtmlEncode(RemoveHtml($Page->IdEleve->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Page->IdEleve->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->IdEleve->getPlaceHolder()) ?>"
        <?= $Page->IdEleve->editAttributes() ?>>
        <?= $Page->IdEleve->selectOptionListHtml("x_IdEleve") ?>
    </select>
    <?= $Page->IdEleve->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->IdEleve->getErrorMessage() ?></div>
<?= $Page->IdEleve->Lookup->getParamTag($Page, "p_x_IdEleve") ?>
<script>
loadjs.ready("finscriptionadd", function() {
    var options = { name: "x_IdEleve", selectId: "finscriptionadd_x_IdEleve" };
    if (finscriptionadd.lists.IdEleve?.lookupOptions.length) {
        options.data = { id: "x_IdEleve", form: "finscriptionadd" };
    } else {
        options.ajax = { id: "x_IdEleve", form: "finscriptionadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.inscription.fields.IdEleve.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->IdClasse->Visible) { // IdClasse ?>
    <div id="r_IdClasse"<?= $Page->IdClasse->rowAttributes() ?>>
        <label id="elh_inscription_IdClasse" class="<?= $Page->LeftColumnClass ?>"><?= $Page->IdClasse->caption() ?><?= $Page->IdClasse->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->IdClasse->cellAttributes() ?>>
<span id="el_inscription_IdClasse">
    <select
        id="x_IdClasse"
        name="x_IdClasse"
        class="form-control ew-select<?= $Page->IdClasse->isInvalidClass() ?>"
        data-select2-id="finscriptionadd_x_IdClasse"
        data-table="inscription"
        data-field="x_IdClasse"
        data-caption="<?= HtmlEncode(RemoveHtml($Page->IdClasse->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Page->IdClasse->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->IdClasse->getPlaceHolder()) ?>"
        <?= $Page->IdClasse->editAttributes() ?>>
        <?= $Page->IdClasse->selectOptionListHtml("x_IdClasse") ?>
    </select>
    <?= $Page->IdClasse->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->IdClasse->getErrorMessage() ?></div>
<?= $Page->IdClasse->Lookup->getParamTag($Page, "p_x_IdClasse") ?>
<script>
loadjs.ready("finscriptionadd", function() {
    var options = { name: "x_IdClasse", selectId: "finscriptionadd_x_IdClasse" };
    if (finscriptionadd.lists.IdClasse?.lookupOptions.length) {
        options.data = { id: "x_IdClasse", form: "finscriptionadd" };
    } else {
        options.ajax = { id: "x_IdClasse", form: "finscriptionadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.inscription.fields.IdClasse.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->IdAnnee->Visible) { // IdAnnee ?>
    <div id="r_IdAnnee"<?= $Page->IdAnnee->rowAttributes() ?>>
        <label id="elh_inscription_IdAnnee" class="<?= $Page->LeftColumnClass ?>"><?= $Page->IdAnnee->caption() ?><?= $Page->IdAnnee->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->IdAnnee->cellAttributes() ?>>
<span id="el_inscription_IdAnnee">
    <select
        id="x_IdAnnee"
        name="x_IdAnnee"
        class="form-control ew-select<?= $Page->IdAnnee->isInvalidClass() ?>"
        data-select2-id="finscriptionadd_x_IdAnnee"
        data-table="inscription"
        data-field="x_IdAnnee"
        data-caption="<?= HtmlEncode(RemoveHtml($Page->IdAnnee->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Page->IdAnnee->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->IdAnnee->getPlaceHolder()) ?>"
        <?= $Page->IdAnnee->editAttributes() ?>>
        <?= $Page->IdAnnee->selectOptionListHtml("x_IdAnnee") ?>
    </select>
    <?= $Page->IdAnnee->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->IdAnnee->getErrorMessage() ?></div>
<?= $Page->IdAnnee->Lookup->getParamTag($Page, "p_x_IdAnnee") ?>
<script>
loadjs.ready("finscriptionadd", function() {
    var options = { name: "x_IdAnnee", selectId: "finscriptionadd_x_IdAnnee" };
    if (finscriptionadd.lists.IdAnnee?.lookupOptions.length) {
        options.data = { id: "x_IdAnnee", form: "finscriptionadd" };
    } else {
        options.ajax = { id: "x_IdAnnee", form: "finscriptionadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.inscription.fields.IdAnnee.modalLookupOptions);
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
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="finscriptionadd"><?= $Language->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="finscriptionadd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("inscription");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
