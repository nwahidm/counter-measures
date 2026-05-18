<form id="deleteForm" action="{{ route('open.data.elicit-result.destroy', $data->id_elicitation_result) }}" method="post">
    {{ method_field('DELETE') }}
    {{ csrf_field() }}
    @if ($data->percentage != '100') 
    <a href="{{ route('open.data.elicit-result.edit', $data->id_elicitation_result) }}"
        class="btn btn-warning btn-icon btn-sm" data-toggle="tooltip" data-placement="top" title="Edit"><i
            class="bi bi-pencil-fill text-white"></i></a>

    @endif
    <a href="{{ route('open.data.elicit-result.show', $data->id_elicitation_result) }}"
        class="btn btn-primary btn-icon btn-sm" data-toggle="tooltip" data-placement="top" title="Detail"><i
            class="bi bi-eye-fill text-white"></i></a>
    <button onclick="deleteData(event)" data-id="{{ $data->id_elicitation_result }}"
        class="btn btn-danger btn-icon btn-sm" data-toggle="tooltip" data-placement="top" title="Delete"><i
            class="bi bi-trash-fill text-white"></i></button>
</form>