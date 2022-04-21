function checkCurrency(value, id) {
    $.ajax({
        type: "POST",
        url: "currency.php",
        data: {
            'value': value,
            'id': id
        },
        success: function (result) {
            result = JSON.parse(result);
            $('#USD').val(result['USD']);
            $('#EUR').val(result['EUR']);
            $('#RUB').val(result['RUB']);
            $('#BYN').val(result['BYN']);
        }
    })
}