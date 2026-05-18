<form id="deleteForm"
    action="{{ route('open.data.interogg-target-id.destroy', $data->id_interogation_target_identification) }}"
    method="post">
    {{ method_field('DELETE') }}
    {{ csrf_field() }}
    <a href="{{ route('open.data.interogg-target-id.show', $data->id_interogation_target_identification) }}"
        class="btn btn-primary btn-icon btn-sm" data-toggle="tooltip" data-placement="top" title="Detail"><i
            class="bi bi-eye-fill text-white"></i></a>
    <a href="{{ route('open.data.interogg-target-id.edit', $data->id_interogation_target_identification) }}"
        class="btn btn-warning btn-icon btn-sm" data-toggle="tooltip" data-placement="top" title="Edit"><i
            class="bi bi-pencil-fill text-white"></i></a>
    <button onclick="deleteData(event)" data-id="{{ $data->id_interogation_target_identification }}"
        class="btn btn-danger btn-icon btn-sm" data-toggle="tooltip" data-placement="top" title="Delete"><i
            class="bi bi-trash-fill text-white"></i></button>
</form>