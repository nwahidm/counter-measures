function deleteData(event)
{
    event.preventDefault();
    let form = $("#deleteForm");
    let url = form.attr('action');
    let id = event.currentTarget.getAttribute('data-id');
    url = url.replace(':id', id);

    swal({
            title: 'Konfirmasi',
            text: 'Anda yakin untuk menghapus data ini?',
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        })
        .then((result) => {
            if (result) {
                $( "button#deleteButton" ).addClass( "disabled" ).addClass( "btn-progress" )
                form.attr('action', url);
                form.submit();
            }
        });
}