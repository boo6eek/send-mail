function send(a) {
        var form = $(a).parents('form')[0];

        var error = false;
        $(form).find('input[required="required"]').each(function () { // прoбeжим пo кaждoму пoлю в фoрмe
            $(this).removeClass('false');
            if ($(this).val() == '') { // eсли нaхoдим пустoe
                $(this).addClass('false');
                error = true; // oшибкa
            }
        });

        if (!error) {
            // var ser = form.serialize();
            var ser = fd = new FormData(form);
            $.ajax({
                method: 'POST',
                url: 'send-mail/mainform.php',
                data: ser,
                contentType: false,
                processData: false,
                success: function (data) {
                    $(form)[0].reset();

	                $('.product_add_to_cart').modal('show');

	                $('.product_add_to_cart h4').empty().html(data);

	                setTimeout(function () {

	                    $('.product_add_to_cart').modal('hide');

	                }, 1500);

                }
            })
        } else {
            $('.messages').show().html("Вы не ввели обязательное поле!").css("color",'#b0363a');
        }


    };
    $('#submit').click(function (e) {
        e.preventDefault();
        //alert('asd');
        send(this);
    });
