<form id="deleteForm" action="{{ route('close.tapping.electronic_device.destroy', $data->id_tapping_electronic_device) }}" method="post">
    {{ method_field('DELETE') }}
    {{ csrf_field() }}
    <a href="{{ route('close.tapping.electronic_device.show', $data->id_tapping_electronic_device) }}" class="btn btn-primary btn-icon btn-sm"
       data-toggle="tooltip"
       data-placement="top"><i class="bi bi-eye-fill text-white"></i></a>
    <a href="{{ route('close.tapping.electronic_device.edit', $data->id_tapping_electronic_device) }}" class="btn btn-warning btn-icon btn-sm"
       data-toggle="tooltip" data-placement="top" title="Edit"><i class="bi bi-pencil-fill text-white"></i></a>
    <button onclick="deleteData(event)" data-id="{{ $data->id_tapping_electronic_device }}" class="btn btn-danger btn-icon btn-sm"
            data-toggle="tooltip" data-placement="top" title="Delete"><i class="bi bi-trash-fill text-white"></i>
    </button>
</form>
