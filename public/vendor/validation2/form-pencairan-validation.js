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
        if (selectedPinjaman.length < 1) {
            alert("Silahkan pilih pinjaman");
            return false;
        }
        
        swal({
            title: 'Konfirmasi',
            text: 'Anda yakin untuk simpan data ini?',
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        })
        .then((result) => {
            if (result) {
                var input = $("<input>").attr("type", "hidden").attr("name", 'id_pinjaman').attr("value", JSON.stringify(selectedPinjaman));
                $(form).append($(input));
                $( "button[type='submit']" ).addClass( "disabled" ).addClass( "btn-progress" )
                form.submit(); //submit it the form
            }
        });
    }
});