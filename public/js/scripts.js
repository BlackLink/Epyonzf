$(document).ready(function() {
    var activeSystemClass = $('.list-group-item.active');

    //something is entered in search form
    $('#system-search').keyup(function() {
        var that = this;
        // affect all table rows on in systems table
        var tableBody = $('.table-list-search tbody');
        var tableRowsClass = $('.table-list-search tbody tr');
        $('.search-sf').remove();
        tableRowsClass.each(function(i, val) {

            //Lower text for case insensitive
            var rowText = $(val).text().toLowerCase();
            var inputText = $(that).val().toLowerCase();
            if (inputText != '')
            {
                $('.search-query-sf').remove();
                tableBody.prepend('<tr class="search-query-sf"><td colspan="6"><strong>Searching for: "'
                        + $(that).val()
                        + '"</strong></td></tr>');
            }
            else
            {
                $('.search-query-sf').remove();
            }

            if (rowText.indexOf(inputText) == -1)
            {
                //hide rows
                tableRowsClass.eq(i).hide();

            }
            else
            {
                $('.search-sf').remove();
                tableRowsClass.eq(i).show();
            }
        });
        //all tr elements are hidden
        if (tableRowsClass.children(':visible').length == 0)
        {
            tableBody.append('<tr class="search-sf"><td class="text-muted" colspan="6">No entries found.</td></tr>');
        }
    });
});

// Masked Input Plugin

jQuery(function($) {
    $("#cDataNascimento").mask("9999-99-99", {placeholder: "yyyy/mm/dd"});
    $("#cCpf").mask("999.999.999-99");
    $("#cTelefone").mask("(99) 9999-9999");
    $("#cCelular").mask("(99) 9999-9999");
    $("#cRg").mask("99.999.999-99");
    $("#cCep").mask("99.999-999");
    $("#cCnpj").mask("99.999.999/9999-99");
   //$('#ValorPrev').priceFormat({
   //    prefix: 'R$ ',
   //    clearPrefix: true});
   //$('#ValorEf').priceFormat({
   //    prefix: 'R$ ',
   //    clearPrefix: true});
   //$('#custo').priceFormat({
   //    prefix: 'R$ ',
   //    clearPrefix: true});
});

//validar email
$(document).ready(function() {
    $("#form1").submit(function() {
        var email = $("#cEmail").val();
        if (email != "")
        {
            var filtro = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
            if (filtro.test(email))
            {
                return true;
            } else {
                alert("Este endereço de email inválido!");
                return false;
            }
        }
        else {
            
            return false;
        }
    });
});

function setValor() {
    var iSelect = document.getElementById("nivel");
    var input = document.getElementById("custo");
    var input1 = document.getElementById("custo1");

    if (iSelect.value == 1){
        input.value = 6.25;
        input1.value = 6.25;
    }
        
    if (iSelect.value == 2){
        input.value = 12.50;
        input1.value = 12.50;
    }
        
    if (iSelect.value == 3){
        input.value = 18.75;
        input1.value = 18.75;
    }
        
    if (iSelect.value == 4){
         input.value = 25.00;
          input1.value = 25.00;
    }
       
    if (iSelect.value == 5){
        input.value = 31.25;
        input1.value = 31.25;
    }
        
}
