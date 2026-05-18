$.validator.addMethod("letters", function(value, element) {
    return this.optional(element) || value == value.match(/^[a-z0-9_\.]+$/);
},"Huruf kecil, titik dan underscore saja.");

$("#form").validate({
    errorElement: 'span',
    errorPlacement: function (error, element) {
        error.addClass('invalid-feedback');
        if(element.hasClass('select2-hidden-accessible')) {
            error.insertAfter(element.next('.select2-container'));
        } else if (element.attr("type") == "checkbox") {
            error.insertAfter(element.parents('.selectgroup'));
        } else if (element.attr("type") == "radio") {
            error.insertAfter(element.parents('.custom-switches-stacked'));
        } 
        else {
            error.insertAfter(element);
        }
    },
    highlight: function (element, errorClass, validClass) {
        if ($(element).attr("type") == "radio") {
            $(element).parents('.selectgroup').addClass('is-invalid');
        } else {
            $(element).addClass('is-invalid');
        }
    },
    unhighlight: function (element, errorClass, validClass) {
        if ($(element).attr("type") == "radio") {
            $(element).parents('.selectgroup').removeClass('is-invalid');
        } else {
            $(element).removeClass('is-invalid');
        }
    },
    submitHandler: function(form) {
        var btn = $(event.submitter).attr('name');
        Swal.fire({
            title: "Konfirmasi",
            text: "Anda yakin untuk simpan data ini?",
            icon: "warning",
            buttonsStyling: false,
            showCancelButton: true,
            confirmButtonText: "Yes!",
            cancelButtonText: 'No',
            customClass: {
                confirmButton: "btn btn-primary",
                cancelButton: 'btn btn-danger'
            },
            reverseButtons: true
        }).then(function (result) {
            if (result.isConfirmed) {
                $( "button[type='submit']" ).prop('data-kt-indicator', 'on').prop('disabled', true);
                if (btn !== undefined) {
                    var input = $("<input>").attr("type", "hidden").attr("name", btn);
                    $(form).append($(input));
                }
                form.submit();
            }
        });
    }
});