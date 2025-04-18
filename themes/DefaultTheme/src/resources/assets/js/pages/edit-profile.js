
$('#birthday_date_picker').pDatepicker({
    format: "YYYY-MM-DD",
    observer: true,
    autoClose: true,
    onSelect: function (date) {
        var date = $('#birthday_date_picker').val();
        $('#birthday_date').val(toEnglishDigit(date));
    }
});
$('#birthday_date_picker').on('keydown', function (e) {
    e.preventDefault();
    $(this).val('');
    $('#birthday_date').val('');
});

function toEnglishDigit (replaceString) {
    var find = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
    var replace = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    var regex;
    for (var i = 0; i < find.length; i++) {
        regex = new RegExp(find[i], 'g');
        replaceString = replaceString.replace(regex, replace[i]);
    }
    return replaceString;
};

$.validator.addMethod(
    "regex",
    function(value, element, regexp) {
        var re = new RegExp(regexp);
        return this.optional(element) || re.test(value);
    },
    "لطفا یک مقدار معتبر وارد کنید"
);

jQuery('#profile-form').validate({

    rules: {
        'first_name': {
            required: true,
        },
        'last_name': {
            required: true,
        },
        'mobile': {
            required: true,
            regex: "(09)[0-9]{9}"
        },
        'postal_code': {
            required: true,
            digits: true,
            maxlength: 10,
            minlength: 10
        },
        'province_id': {
            required: true,
        },
        'city_id': {
            required: true,
        },
        'address': {
            required: true,
            maxlength: 300,
        },
    },
});

$.validator.addMethod(
    "regex",
    function(value, element, regexp) {
        var re = new RegExp(regexp);
        return this.optional(element) || re.test(value);
    },
    "لطفا یک مقدار معتبر وارد کنید"
);

$('#profile-form').submit(function(e) {
    e.preventDefault();

    if ($(this).valid()) {
        var formData = new FormData(this);
        var btn = $('#submit-btn');

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            success: function(data) {
                Swal.fire({
                    title: 'تغییرات با موفقیت ثبت شد',
                    type: 'success',
                    showCancelButton: false,
                    confirmButtonText: 'باشه',
                    closeOnConfirm: false,
                    closeOnCancel: false
                });
            },
            beforeSend: function(xhr) {
                block(btn);
                xhr.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
            },
            complete: function() {
                unblock(btn);
            },

            cache: false,
            contentType: false,
            processData: false
        });
    }
});
