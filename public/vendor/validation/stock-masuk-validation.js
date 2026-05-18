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

        var rowCount = 0;
        $('[id^=data]').each(function(){
            rowCount += $(this).find("tbody").find("tr").length;
        });

        if(rowCount == 0) {
            swal('Error', 'Silakan masukan data!', 'error');
            return;
        }

        var count = 0;
        $('[id^=total]').each(function(){
            var total = parseFloat($(this).val());
            var produk_id = $(this).data("produk_id");
            var sum = 0;
            $('[id=jumlah-'+produk_id+']').each(function(){
                sum += parseFloat($(this).val());  // Or this.innerHTML, this.innerText
            });
            
            if (total != sum) {
                count++;
            }
        });

        if (count > 0) {
            swal('Error', 'Jumlah data yang diterima harus sama dengan jumlah data yang dipesan!', 'error');
            return;
        }

        // var total = parseFloat($('#total').val());
        // var sum = 0;
        // $('[id=jumlah]').each(function(){
        //     sum += parseFloat($(this).val());  // Or this.innerHTML, this.innerText
        // });

        // if (total != sum) {
        //     swal('Error', 'Jumlah data harus sama!', 'error');
        //     return;
        // }

        swal({
            title: 'Konfirmasi',
            text: 'Anda yakin untuk simpan data ini?',
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        })
        .then((result) => {
            if (result) {
                $( "button[type='submit']" ).addClass( "disabled" ).addClass( "btn-progress" )
                form.submit(); //submit it the form
            }
        });
      }
});