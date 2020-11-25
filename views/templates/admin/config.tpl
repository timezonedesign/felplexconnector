<form action="" method="POST" class="form form-horizontal">
    <div class="justify-content-center row">
        <div class="col-xl-10">
            <div class="card">
                <h3 class="card-header">
                    <i class="material-icons">settings</i> Felplex settings
                </h3>
                <div class="card-block row">
                    <div class="card-text">
                        <div class="form-group row">
                            <label class="form-control-label">Entity_ID</label>
                            <div class="col-sm">
                                <input type="text" id="entity_id" name="entity_id" class="form-control" size="64" value="{if isset($entity_id)}{$entity_id}{/if}">
                                <small class="form-text">Input Entity_ID.</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="form-control-label">API_KEY</label>
                            <div class="col-sm">
                                <input type="text" id="api_key" name="api_key" class="form-control" size="64" value="{if isset($api_key)}{$api_key}{/if}">
                                <small class="form-text">Input API_KEY.</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="form-control-label">Invoice Type</label>
                            <div class="col-sm">
                                <input type="text" id="type" name="type" class="form-control" size="64" value="{if isset($type)}{$type}{/if}">
                                <small class="form-text">Input Ivoice Type.</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="processing_message"></div>
                <div class="card-footer">
                    <div class="d-flex justify-content-end">
                        <button id="save" name="save" type="button" class="btn btn-primary" onclick="javascript:save_login();" style="margin-right:30px;">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
