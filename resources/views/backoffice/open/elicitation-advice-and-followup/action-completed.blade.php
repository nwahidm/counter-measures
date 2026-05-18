<form id="deleteForm" action="{{ route('open.data.elicit-adfoll.destroy', $data->id_elicitation_saran_dan_tindak_lanjut) }}" method="post">
    {{ method_field('DELETE') }}
    {{ csrf_field() }}
    <a href="{{ route('open.data.elicit-adfoll.show', $data->id_elicitation_saran_dan_tindak_lanjut) }}"
        class="btn btn-primary btn-icon btn-sm" data-toggle="tooltip" data-placement="top" title="Detail"><i
            class="bi bi-eye-fill text-white"></i></a>

    <button onclick="deleteData(event)" data-id="{{ $data->id_elicitation_saran_dan_tindak_lanjut }}"
        class="btn btn-danger btn-icon btn-sm" data-toggle="tooltip" data-placement="top" title="Delete"><i
            class="bi bi-trash-fill text-white"></i></button>
</form>