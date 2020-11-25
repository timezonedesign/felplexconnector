<form action="" method="POST" class="form form-horizontal content-form">
    <div class="justify-content-center row">
        <div class="col-xl-10">
            <div class="card">
                <div class="card-block row">
                    <div class="card-text">
                        <div class="form-group row">
                            <label class="form-control-label">NIT</label>
                            <div class="col-sm">
                                <input type="text" id="nit" name="nit" class="form-control" value="">
                            </div>
                            <label class="form-control-label">Name</label>
                            <div class="col-sm">
                                <input type="text" id="name" name="name" class="form-control" value="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="form-control-label">Street</label>
                            <div class="col-sm">
                                <input type="text" id="street" name="street" class="form-control" value="">
                            </div>
                            <label class="form-control-label">City</label>
                            <div class="col-sm">
                                <input type="text" id="city" name="city" class="form-control" value="">
                            </div>
                            <label class="form-control-label">State</label>
                            <div class="col-sm">
                                <input type="text" id="state" name="state" class="form-control" value="">
                            </div>
                            <label class="form-control-label">Zip</label>
                            <div class="col-sm">
                                <input type="text" id="zip" name="zip" class="form-control" value="">
                            </div>
                            <label class="form-control-label">Country</label>
                            <div class="col-sm">
                                <input type="text" id="country" name="country" class="form-control" value="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="form-control-label">Type</label>
                            <div class="col-sm">
                                <select type="text" name="type[0]" class="form-control" value="">
                                    <option value="B">Good</option>
                                    <option value="S">Service</option>
                                </select>
                            </div>
                            <label class="form-control-label">Qty</label>
                            <div class="col-sm">
                                <input type="text" name="qty[0]" class="form-control" value="">
                            </div>
                            <label class="form-control-label">Description</label>
                            <div class="col-sm">
                                <input type="text" name="description[0]" class="form-control" value="">
                            </div>
                            <label class="form-control-label">Price</label>
                            <div class="col-sm">
                                <input type="text" name="price[0]" class="form-control" value="" size="5">
                            </div>
                            <label class="form-control-label">Discount</label>
                            <div class="col-sm">
                                <input type="text" name="discount[0]" class="form-control" value="" size="5">
                            </div>
                            <label class="form-control-label">Without VAT</label>
                            <div class="col-sm">
                                <input type="checkbox" name="without_vat[0]" class="form-control" value="" onclick="withoutVAT(this)">
                                <input type="text" name="tax_amount[0]" class="form-control" value="" size="5">
                            </div>
                        </div>
                        <button id="add_item" name="add_item" type="button" class="btn btn-primary"  style="margin-right:30px;" onclick="addItem(this);">+ Add Item</button>
                        <div class="form-group row">
                            <label class="form-control-label">Email</label>
                            <div class="col-sm">
                                <input type="email" name="emails[0]" class="form-control" value="">
                            </div>
                        </div>
                        <button id="add_email" name="add_email" type="button" class="btn btn-primary"  style="margin-right:30px;" onclick="addEmail(this);">+ Add Email</button>
                        <div class="form-group row">
                            <label class="form-control-label">Email_cc</label>
                            <div class="col-sm">
                                <input type="email" name="emails_cc[0]" class="form-control" value="">
                            </div>
                        </div>
                        <button id="add_email_cc" name="add_email_cc" type="button" class="btn btn-primary"  style="margin-right:30px;" onclick="addEmailCc(this);">+ Add Email_cc</button>

                    </div>
                </div>
                <div id="processing_message"></div>
                <div class="card-footer">
                    <div class="d-flex justify-content-end">
                        <button id="create" name="create" type="submit" class="btn btn-primary"  style="margin-right:30px;">Create</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
