<form id="deleteForm" action="{{ route('open.singleform.single-form.destroy', $data->id) }}" method="post">
    {{ method_field('DELETE') }}
    {{ csrf_field() }}
    <a href="{{ route('open.singleform.single-form.show', $data->id) }}"
        class="btn btn-primary btn-icon btn-sm" data-toggle="tooltip" data-placement="top" title="Detail"><i
            class="bi bi-eye-fill text-form"></i></a>
    <a href="{{ route('open.singleform.single-form.edit', $data->id) }}"
        class="btn btn-warning btn-icon btn-sm" data-toggle="tooltip" data-placement="top" title="Edit"><i
            class="bi bi-pencil-fill text-white"></i></a>
    <button onclick="deleteData(event)" data-id="{{ $data->id }}"
        class="btn btn-danger btn-icon btn-sm" data-toggle="tooltip" data-placement="top" title="Delete"><i
            class="bi bi-trash-fill text-white"></i></button>
</form>