$(function() {
    $("#ms-submit-button").click(function() {
        var button = $(this);

        $.ajax({
            type: "POST",
            dataType: "json",
            url: $('base').attr('href') + 'index.php?route=account/register-seller/jxsavesellerinfo',
            data: $("form#seller-form").serialize(),
            beforeSend: function() {
                button.hide();
                $('p.error').remove();
                $('.warning.main').hide();
                $('.form-group').removeClass("has-error");
            },
            complete: function(jqXHR, textStatus) {
                if (textStatus != 'success') {
                    button.show();
                    $(".warning.main").text(msGlobals.formError).show();
                    window.scrollTo(0,0);
                }
            },
            success: function(jsonData) {
                if (!jQuery.isEmptyObject(jsonData.errors)) {
                    $('#ms-submit-button').show().prev('span.wait').remove();
                    $('.error').text('');

                    for (error in jsonData.errors) {
                        if ($('#error_' + error).length > 0) {
                            $('#error_' + error).text(jsonData.errors[error]);
                            $('#error_' + error).parents('.form-group').addClass('has-error');
                        } else if ($('[name="'+error+'"]').length > 0) {
                            $('[name="' + error + '"]').parents('.form-group').addClass('has-error');
                            $('[name="' + error + '"]').parents('div:first').append('<p class="error">' + jsonData.errors[error] + '</p>');
                        } else $(".warning.main").append("<p>" + jsonData.errors[error] + "</p>").show();
                    }
                    window.scrollTo(0,0);
                } else {
                    window.location = jsonData.redirect;
                }
            }
        });
    });
});