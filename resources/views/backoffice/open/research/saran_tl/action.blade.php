<form id="deleteForm" action="{{ route('open.research.advice-measure.destroy', $data->id_saran_dan_tindak_lanjut) }}" method="post">
    {{ method_field('DELETE') }}
    {{ csrf_field() }}
    @if ($data->caseProgress->percentage != '100') 
    <a href="{{ route('open.research.advice-measure.edit', $data->id_saran_dan_tindak_lanjut) }}" class="btn btn-warning btn-icon btn-sm"
       data-toggle="tooltip" data-placement="top" title="Edit"><i class="bi bi-pencil-fill text-white"></i></a>
    @endif
    <a href="{{ route('open.research.advice-measure.show', $data->id_saran_dan_tindak_lanjut) }}" class="btn btn-primary btn-icon btn-sm"
       data-toggle="tooltip"
       data-placement="top"><i class="bi bi-eye-fill text-white"></i></a>

    <button onclick="deleteData(event)" data-id="{{ $data->id_saran_dan_tindak_lanjut }}" class="btn btn-danger btn-icon btn-sm"
            data-toggle="tooltip" data-placement="top" title="Delete"><i class="bi bi-trash-fill text-white"></i>
    </button>

</form>
