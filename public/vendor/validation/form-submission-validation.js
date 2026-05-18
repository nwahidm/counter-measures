$.validator.addMethod("letters", function(value, element) {
    return this.optional(element) || value == value.match(/^[a-z0-9_\.]+$/);
},"Huruf kecil, titik dan underscore saja.");

$("#formSubmission").validate({
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
    }
});