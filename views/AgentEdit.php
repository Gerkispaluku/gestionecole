<?php

namespace PHPMaker2023\gestion_ECOLE;

// Page object
$AgentEdit = &$Page;
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
<form name="fagentedit" id="fagentedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="on">
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { agent: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var fagentedit;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fagentedit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["IdAgent", [fields.IdAgent.visible && fields.IdAgent.required ? ew.Validators.required(fields.IdAgent.caption) : null], fields.IdAgent.isInvalid],
            ["MatricAgent", [fields.MatricAgent.visible && fields.MatricAgent.required ? ew.Validators.required(fields.MatricAgent.caption) : null], fields.MatricAgent.isInvalid],
            ["NomAgent", [fields.NomAgent.visible && fields.NomAgent.required ? ew.Validators.required(fields.NomAgent.caption) : null], fields.NomAgent.isInvalid],
            ["PostnomAgent", [fields.PostnomAgent.visible && fields.PostnomAgent.required ? ew.Validators.required(fields.PostnomAgent.caption) : null], fields.PostnomAgent.isInvalid],
            ["PrenomAgent", [fields.PrenomAgent.visible && fields.PrenomAgent.required ? ew.Validators.required(fields.PrenomAgent.caption) : null], fields.PrenomAgent.isInvalid],
            ["SexeAgent", [fields.SexeAgent.visible && fields.SexeAgent.required ? ew.Validators.required(fields.SexeAgent.caption) : null], fields.SexeAgent.isInvalid],
            ["EtatCivilAgent", [fields.EtatCivilAgent.visible && fields.EtatCivilAgent.required ? ew.Validators.required(fields.EtatCivilAgent.caption) : null], fields.EtatCivilAgent.isInvalid],
            ["TelephoneAgent", [fields.TelephoneAgent.visible && fields.TelephoneAgent.required ? ew.Validators.required(fields.TelephoneAgent.caption) : null], fields.TelephoneAgent.isInvalid],
            ["Motsecret", [fields.Motsecret.visible && fields.Motsecret.required ? ew.Validators.required(fields.Motsecret.caption) : null, ew.Validators.integer], fields.Motsecret.isInvalid]
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
            "MatricAgent": <?= $Page->MatricAgent->toClientList($Page) ?>,
            "SexeAgent": <?= $Page->SexeAgent->toClientList($Page) ?>,
            "EtatCivilAgent": <?= $Page->EtatCivilAgent->toClientList($Page) ?>,
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
<input type="hidden" name="t" value="agent">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->IdAgent->Visible) { // IdAgent ?>
    <div id="r_IdAgent"<?= $Page->IdAgent->rowAttributes() ?>>
        <label id="elh_agent_IdAgent" for="x_IdAgent" class="<?= $Page->LeftColumnClass ?>"><?= $Page->IdAgent->caption() ?><?= $Page->IdAgent->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->IdAgent->cellAttributes() ?>>
<span id="el_agent_IdAgent">
<span<?= $Page->IdAgent->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Page->IdAgent->getDisplayValue($Page->IdAgent->EditValue) ?></span></span>
<input type="hidden" data-table="agent" data-field="x_IdAgent" data-hidden="1" name="x_IdAgent" id="x_IdAgent" value="<?= HtmlEncode($Page->IdAgent->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->MatricAgent->Visible) { // MatricAgent ?>
    <div id="r_MatricAgent"<?= $Page->MatricAgent->rowAttributes() ?>>
        <label id="elh_agent_MatricAgent" class="<?= $Page->LeftColumnClass ?>"><?= $Page->MatricAgent->caption() ?><?= $Page->MatricAgent->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->MatricAgent->cellAttributes() ?>>
<span id="el_agent_MatricAgent">
<?php
if (IsRTL()) {
    $Page->MatricAgent->EditAttrs["dir"] = "rtl";
}
?>
<span id="as_x_MatricAgent" class="ew-auto-suggest">
    <input type="<?= $Page->MatricAgent->getInputTextType() ?>" class="form-control" name="sv_x_MatricAgent" id="sv_x_MatricAgent" value="<?= RemoveHtml($Page->MatricAgent->EditValue) ?>" autocomplete="off" size="30" maxlength="3" placeholder="<?= HtmlEncode($Page->MatricAgent->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Page->MatricAgent->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->MatricAgent->formatPattern()) ?>"<?= $Page->MatricAgent->editAttributes() ?> aria-describedby="x_MatricAgent_help">
</span>
<selection-list hidden class="form-control" data-table="agent" data-field="x_MatricAgent" data-input="sv_x_MatricAgent" data-value-separator="<?= $Page->MatricAgent->displayValueSeparatorAttribute() ?>" name="x_MatricAgent" id="x_MatricAgent" value="<?= HtmlEncode($Page->MatricAgent->CurrentValue) ?>"></selection-list>
<?= $Page->MatricAgent->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->MatricAgent->getErrorMessage() ?></div>
<script>
loadjs.ready("fagentedit", function() {
    fagentedit.createAutoSuggest(Object.assign({"id":"x_MatricAgent","forceSelect":false}, { lookupAllDisplayFields: <?= $Page->MatricAgent->Lookup->LookupAllDisplayFields ? "true" : "false" ?> }, ew.vars.tables.agent.fields.MatricAgent.autoSuggestOptions));
});
</script>
<?= $Page->MatricAgent->Lookup->getParamTag($Page, "p_x_MatricAgent") ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->NomAgent->Visible) { // NomAgent ?>
    <div id="r_NomAgent"<?= $Page->NomAgent->rowAttributes() ?>>
        <label id="elh_agent_NomAgent" for="x_NomAgent" class="<?= $Page->LeftColumnClass ?>"><?= $Page->NomAgent->caption() ?><?= $Page->NomAgent->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->NomAgent->cellAttributes() ?>>
<span id="el_agent_NomAgent">
<input type="<?= $Page->NomAgent->getInputTextType() ?>" name="x_NomAgent" id="x_NomAgent" data-table="agent" data-field="x_NomAgent" value="<?= $Page->NomAgent->EditValue ?>" size="30" maxlength="25" placeholder="<?= HtmlEncode($Page->NomAgent->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->NomAgent->formatPattern()) ?>"<?= $Page->NomAgent->editAttributes() ?> aria-describedby="x_NomAgent_help">
<?= $Page->NomAgent->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->NomAgent->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->PostnomAgent->Visible) { // PostnomAgent ?>
    <div id="r_PostnomAgent"<?= $Page->PostnomAgent->rowAttributes() ?>>
        <label id="elh_agent_PostnomAgent" for="x_PostnomAgent" class="<?= $Page->LeftColumnClass ?>"><?= $Page->PostnomAgent->caption() ?><?= $Page->PostnomAgent->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->PostnomAgent->cellAttributes() ?>>
<span id="el_agent_PostnomAgent">
<input type="<?= $Page->PostnomAgent->getInputTextType() ?>" name="x_PostnomAgent" id="x_PostnomAgent" data-table="agent" data-field="x_PostnomAgent" value="<?= $Page->PostnomAgent->EditValue ?>" size="30" maxlength="25" placeholder="<?= HtmlEncode($Page->PostnomAgent->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->PostnomAgent->formatPattern()) ?>"<?= $Page->PostnomAgent->editAttributes() ?> aria-describedby="x_PostnomAgent_help">
<?= $Page->PostnomAgent->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->PostnomAgent->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->PrenomAgent->Visible) { // PrenomAgent ?>
    <div id="r_PrenomAgent"<?= $Page->PrenomAgent->rowAttributes() ?>>
        <label id="elh_agent_PrenomAgent" for="x_PrenomAgent" class="<?= $Page->LeftColumnClass ?>"><?= $Page->PrenomAgent->caption() ?><?= $Page->PrenomAgent->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->PrenomAgent->cellAttributes() ?>>
<span id="el_agent_PrenomAgent">
<input type="<?= $Page->PrenomAgent->getInputTextType() ?>" name="x_PrenomAgent" id="x_PrenomAgent" data-table="agent" data-field="x_PrenomAgent" value="<?= $Page->PrenomAgent->EditValue ?>" size="30" maxlength="25" placeholder="<?= HtmlEncode($Page->PrenomAgent->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->PrenomAgent->formatPattern()) ?>"<?= $Page->PrenomAgent->editAttributes() ?> aria-describedby="x_PrenomAgent_help">
<?= $Page->PrenomAgent->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->PrenomAgent->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->SexeAgent->Visible) { // SexeAgent ?>
    <div id="r_SexeAgent"<?= $Page->SexeAgent->rowAttributes() ?>>
        <label id="elh_agent_SexeAgent" class="<?= $Page->LeftColumnClass ?>"><?= $Page->SexeAgent->caption() ?><?= $Page->SexeAgent->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->SexeAgent->cellAttributes() ?>>
<span id="el_agent_SexeAgent">
<template id="tp_x_SexeAgent">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="agent" data-field="x_SexeAgent" name="x_SexeAgent" id="x_SexeAgent"<?= $Page->SexeAgent->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_SexeAgent" class="ew-item-list"></div>
<selection-list hidden
    id="x_SexeAgent"
    name="x_SexeAgent"
    value="<?= HtmlEncode($Page->SexeAgent->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_SexeAgent"
    data-target="dsl_x_SexeAgent"
    data-repeatcolumn="5"
    class="form-control<?= $Page->SexeAgent->isInvalidClass() ?>"
    data-table="agent"
    data-field="x_SexeAgent"
    data-value-separator="<?= $Page->SexeAgent->displayValueSeparatorAttribute() ?>"
    <?= $Page->SexeAgent->editAttributes() ?>></selection-list>
<?= $Page->SexeAgent->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->SexeAgent->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->EtatCivilAgent->Visible) { // EtatCivilAgent ?>
    <div id="r_EtatCivilAgent"<?= $Page->EtatCivilAgent->rowAttributes() ?>>
        <label id="elh_agent_EtatCivilAgent" for="x_EtatCivilAgent" class="<?= $Page->LeftColumnClass ?>"><?= $Page->EtatCivilAgent->caption() ?><?= $Page->EtatCivilAgent->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->EtatCivilAgent->cellAttributes() ?>>
<span id="el_agent_EtatCivilAgent">
    <select
        id="x_EtatCivilAgent"
        name="x_EtatCivilAgent"
        class="form-select ew-select<?= $Page->EtatCivilAgent->isInvalidClass() ?>"
        <?php if (!$Page->EtatCivilAgent->IsNativeSelect) { ?>
        data-select2-id="fagentedit_x_EtatCivilAgent"
        <?php } ?>
        data-table="agent"
        data-field="x_EtatCivilAgent"
        data-value-separator="<?= $Page->EtatCivilAgent->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->EtatCivilAgent->getPlaceHolder()) ?>"
        <?= $Page->EtatCivilAgent->editAttributes() ?>>
        <?= $Page->EtatCivilAgent->selectOptionListHtml("x_EtatCivilAgent") ?>
    </select>
    <?= $Page->EtatCivilAgent->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->EtatCivilAgent->getErrorMessage() ?></div>
<?php if (!$Page->EtatCivilAgent->IsNativeSelect) { ?>
<script>
loadjs.ready("fagentedit", function() {
    var options = { name: "x_EtatCivilAgent", selectId: "fagentedit_x_EtatCivilAgent" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fagentedit.lists.EtatCivilAgent?.lookupOptions.length) {
        options.data = { id: "x_EtatCivilAgent", form: "fagentedit" };
    } else {
        options.ajax = { id: "x_EtatCivilAgent", form: "fagentedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.agent.fields.EtatCivilAgent.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->TelephoneAgent->Visible) { // TelephoneAgent ?>
    <div id="r_TelephoneAgent"<?= $Page->TelephoneAgent->rowAttributes() ?>>
        <label id="elh_agent_TelephoneAgent" for="x_TelephoneAgent" class="<?= $Page->LeftColumnClass ?>"><?= $Page->TelephoneAgent->caption() ?><?= $Page->TelephoneAgent->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->TelephoneAgent->cellAttributes() ?>>
<span id="el_agent_TelephoneAgent">
<input type="<?= $Page->TelephoneAgent->getInputTextType() ?>" name="x_TelephoneAgent" id="x_TelephoneAgent" data-table="agent" data-field="x_TelephoneAgent" value="<?= $Page->TelephoneAgent->EditValue ?>" size="30" maxlength="13" placeholder="<?= HtmlEncode($Page->TelephoneAgent->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->TelephoneAgent->formatPattern()) ?>"<?= $Page->TelephoneAgent->editAttributes() ?> aria-describedby="x_TelephoneAgent_help">
<?= $Page->TelephoneAgent->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->TelephoneAgent->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Motsecret->Visible) { // Motsecret ?>
    <div id="r_Motsecret"<?= $Page->Motsecret->rowAttributes() ?>>
        <label id="elh_agent_Motsecret" for="x_Motsecret" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Motsecret->caption() ?><?= $Page->Motsecret->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Motsecret->cellAttributes() ?>>
<span id="el_agent_Motsecret">
<div class="input-group">
    <input type="password" name="x_Motsecret" id="x_Motsecret" autocomplete="new-password" data-field="x_Motsecret" value="<?= $Page->Motsecret->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->Motsecret->getPlaceHolder()) ?>"<?= $Page->Motsecret->editAttributes() ?> aria-describedby="x_Motsecret_help">
    <button type="button" class="btn btn-default ew-toggle-password rounded-end" data-ew-action="password"><i class="fa-solid fa-eye"></i></button>
</div>
<?= $Page->Motsecret->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Motsecret->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fagentedit"><?= $Language->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fagentedit" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("agent");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
