$(function() {
    $('#ms-pay,#ms-pay-all').click(function() {
        if (!$("#ms-pay-all").length) {
            // payments
            if ($('#form tbody input:checkbox:checked').length == 0) return; else {
                var func = 'Payment';
                var data = $('#form').serialize();
            }
        } else {
            // sellers
            if (this.id == 'ms-pay' && $('#list-sellers input:checkbox:checked').length == 0) return; else {
                var func = 'MassPay';
                var data = $('#list-sellers input:checkbox').serialize() + '&' + this.id + '=1';
            }
        }

        $.ajax({
            type: "POST",
            dataType: "json",
            url: 'index.php?route=module/multimerch_masspay/jxConfirm'+func+'&token='+msGlobals.token,
            data: data,
            success: function(jsonData) {
                if (jsonData.error) {
                    alert(jsonData.error);
                } else {
                    console.log('success');
                    $('<div />').html(jsonData.html).dialog({
                        dialogClass: "msBlack",
                        resizable: false,
                        width: 600,
                        title: msGlobals.ms_payment_confirmation,
                        modal: true,
                        buttons: [
                            {
                                id: "button-pay",
                                text: msGlobals.ms_payment_pay,
                                click: function() {
                                    var dialog = $(this);
                                    $('#button-pay').remove();
                                    $('#button-cancel').attr('disabled','disabled');
                                    $.ajax({
                                        type: "POST",
                                        dataType: "json",
                                        url: 'index.php?route=module/multimerch_masspay/jxComplete'+func+'&token='+msGlobals.token,
                                        data: data,
                                        success: function(jsonData) {
                                            $('#button-pay').remove();
                                            $('#button-cancel').removeAttr('disabled').find("span").html("OK");

                                            if (!jQuery.isEmptyObject(jsonData.error)) {
                                                dialog.html('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i>'+jsonData.error+'</div>');
                                                if (!jQuery.isEmptyObject(jsonData.response)) {
                                                    dialog.append('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i>'+jsonData.response+'</div>');
                                                }
                                                dialog.children('.ui-dialog-buttonset button:first').remove();
                                            } else {
                                                dialog.html('<div class="alert alert-success"><i class="fa fa-check-circle"></i>'+jsonData.success+'</div>');
                                                $('#button-cancel').unbind('click').click(function() {
                                                    dialog.dialog("close");
                                                    window.location.reload();
                                                });
                                            }
                                        }
                                    });
                                }
                            },
                            {
                                id: "button-cancel",
                                text: msGlobals.button_cancel,
                                click: function() {
                                    $(this).dialog("close");
                                }
                            }
                        ]
                    });
                }
            }
        });
    });

    /*
    $('#massDialog').on('show.bs.modal', function () {
      // do something…
    })

    $("#submitPayment").click(function() {
        if (this.id == 'ms-pay' && $('#list-sellers input:checkbox:checked').length == 0) return;
        var data = $('#list-sellers input:checkbox').serialize() + '&' + this.id + '=1';

        $.ajax({
            type: "POST",
            dataType: "json",
            url: 'index.php?route=module/multimerch_masspay/jxConfirmMasspay&token=<?php echo $token; ?>',
            data: data,
            beforeSend: function() {
            },
            complete: function() {
            },
            success: function(data) {
                $('#contactDialog').find('.alert-danger, .alert-success').remove();

                if (!jQuery.isEmptyObject(data.errors)) {
                    console.log(data.errors);
                    for (error in data.errors) {
                        $('#contactDialog .modal-body').append('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i>' + data.errors[error] + '</div>');
                    }
                }

                if (data.success) {
                    $('#contactDialog').find('textarea').val('');
                    $('#contactDialog .modal-body').append('<div class="alert alert-success"><i class="fa fa-exclamation-circle"></i>' + data.success + '</div>');
                }
            }
        });
    });
    */
});
