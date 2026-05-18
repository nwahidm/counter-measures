$("#form").validate({
    submitHandler: function(form) {
        var btn = $(event.submitter).attr('name');
        var text = '';
        if(btn == 'setuju') {
            text = 'Anda yakin untuk setuju data ini?';
        }
        else if(btn == 'tolak') {
            text = 'Anda yakin untuk tolak data ini?';
        } 

        swal({
            title: 'Konfirmasi',
            text: text,
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        })
        .then((result) => {
            if (result) {
                $( "button[type='submit']" ).addClass( "disabled" ).addClass( "btn-progress" );
                var input = $("<input>").attr("type", "hidden").attr("name", btn);
                $(form).append($(input));
                form.submit(); //submit it the form
            }
        });
    }
});