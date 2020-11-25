<form method="post" action="" class="content-form">
    <div class="row buttons">
        <button type="submit" class="btn btn-primary create_all" name="create_all">Create All</button>
        <button type="submit" class="btn btn-primary delete_all" name="delete_all">Delete All</button>
    </div>
    <table>
        <thead>
            <tr>
                <th>id_order</th>
                <th>Products</th>
                <th>Total Price</th>
                <th>Payment Method</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        {foreach from=$orders item=order key=key}
            <tr>
                <td>{$order.id_order}</td>
                <td>{$order.products}</td>
                <td>{$order.total_products_wt}</td>
                <td>{$order.payment}</td>
                <td>
                {if $order.invoice}
                    <button type="submit" class="btn btn-primary delete" name="delete" onclick="this.form.id_order.value={$order.id_order};">Delete</button>
                {else}
                    <button type="submit" class="btn btn-primary create" name="create" onclick="this.form.id_order.value={$order.id_order};">Create</button>
                {/if}
                </td>
            </tr>
        {/foreach}
        </tbody>
    </table>
    <input type="hidden" name="id_order" value="-1">
</form>
<script>

</script>
