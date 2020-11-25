$(document).ready(function() {
    carriers.forEach(carrier => {
        items = [];
        if (carrier_suppliers[carrier] != undefined) items = carrier_suppliers[carrier].split(",");
        $('#input-tags' + carrier).selectize({
            plugins: ['remove_button'],
            valueField: 'id_supplier',
            labelField: 'name',
            searchField: ['name', 'id_supplier'],
            options: suppliers,
            items: items,
            delimiter: ',',
            persist: false,
            create: function(input) {
                return {
                    value: input,
                    text: input
                }
            }
        });
    });
});