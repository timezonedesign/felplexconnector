$(document).ready(function() {
    if (typeof carriers != 'undefined')
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

var count_item = 0,
    count_email = 0,
    count_email_cc = 0;


function addItem(e) {
    var names = ['type', 'qty', 'description', 'price', 'discount', 'without_vat', 'tax_amount'];
    count_item++;
    var newItem = e.previousElementSibling.cloneNode(true);
    elements = newItem.getElementsByTagName('input');
    for (i = 0; i < elements.length; i++)
        elements[i].name = `${names[i]}[${count_item}]`;

    e.parentElement.insertBefore(newItem, e);
}

function addEmail(e) {
    var names = ['emails'];
    count_email++;
    var newItem = e.previousElementSibling.cloneNode(true);
    elements = newItem.getElementsByTagName('input')
    for (i = 0; i < elements.length; i++)
        elements[i].name = `${names[i]}[${count_email}]`;

    e.parentElement.insertBefore(newItem, e);
}

function addEmailCc(e) {
    var names = ['emails_cc'];
    count_email_cc++;
    var newItem = e.previousElementSibling.cloneNode(true);
    elements = newItem.getElementsByTagName('input')
    for (i = 0; i < elements.length; i++)
        elements[i].name = `${names[i]}[${count_email_cc}]`;

    e.parentElement.insertBefore(newItem, e);
}

function withoutVAT(e) {
    if (e.checked)
        e.nextElementSibling.style.display = 'none';
    else
        e.nextElementSibling.style.display = 'unset';

}