jQuery.validator.addMethod("fileRequired", function(value, element) {
    var gatewayValue = jQuery('input[name="gateway"]:checked').val();
    if ((gatewayValue === 'check1' || gatewayValue === 'check2' || gatewayValue === 'check3' || gatewayValue === 'check4'|| gatewayValue === 'check5'|| gatewayValue === 'check6') && value === '') {
        return false; // If gateway is 'check' and file input is empty, validation fails
    }
    return true; // Otherwise, validation passes
}, "File is required when gateway is 'check'");



jQuery('#checkout-form').validate({
    rules: {
        name: {
            required: true
        },
        fileCheck: {
            fileRequired: true
        },
        mobile: {
            required: true,
            regex: '(09)[0-9]{9}'
        },
        postal_code: {
            required: true,
            digits: true,
            maxlength: 10,
            minlength: 10
        },
        province_id: {
            required: true
        },
        city_id: {
            required: true
        },
        address: {
            maxlength: 300
        },
        description: {
            maxlength: 1000
        },
        carrier_id: {
            required: true
        }
    },
    messages: {
        fileCheck: {
            fileRequired: "لطفا تصویر چک خود را اینجا بارگذاری کنید. "
        }
    },
});

$('#discount-create-form').validate({
    rules: {
        code: {
            required: true
        }
    }
});

$.validator.addMethod(
    'regex',
    function (value, element, regexp) {
        var re = new RegExp(regexp);
        return this.optional(element) || re.test(value);
    },
    'لطفا یک مقدار معتبر وارد کنید'
);

$(document).on('change', '#city, input[name="carrier_id"], input[name="gateway"]', function () {
    var carrier_id = $('input[name="carrier_id"]:checked').val();
    var city_id = $('#city').val();
    var action = $('#city').data('action');
    var gateway = $('input[name="gateway"]:checked').val();

    $.ajax({
        url: action,
        type: 'GET',
        data: {
            city_id: city_id,
            carrier_id: carrier_id,
            gateway: gateway
        },
        success: function (data) {
            $('#checkout-carrier-container').replaceWith(
                data.carriers_container
            );

            $('#checkout-sidebar').replaceWith(data.checkout_sidebar);
            $('[data-toggle="tooltip"]').tooltip();

            if ($('.container .sticky-sidebar').length) {
                $('.container .sticky-sidebar').theiaStickySidebar();
            }

            check_wallet();
        }
    });
});

$(document).ready(function () {
    $('input[name="carrier_id"]').trigger('change');
});

$('#discount-create-form').submit(function (e) {
    e.preventDefault();

    var formData = new FormData(this);
    var form = $(this);

    if ($(this).valid()) {
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            success: function (data) {
                toastr.success('', 'کد تخفیف با موفقیت ثبت شد', {
                    positionClass: 'toast-bottom-left',
                    containerId: 'toast-bottom-left'
                });

                setTimeout(function () {
                    location.reload();
                }, 1000);
            },

            beforeSend: function (xhr) {
                block(form);
            },
            complete: function () {
                unblock(form);
            },

            cache: false,
            contentType: false,
            processData: false
        });
    }
});


function check_wallet() {
    if (
        parseInt($('#final-price').data('value')) >
        parseInt($('#wallet-balance').data('value'))
    ) {
        $('.wallet-select .has-balance').hide();
        $('.wallet-select .increase-balance').show();
    } else {
        $('.wallet-select .has-balance').show();
        $('.wallet-select .increase-balance').hide();
    }
}

check_wallet();
