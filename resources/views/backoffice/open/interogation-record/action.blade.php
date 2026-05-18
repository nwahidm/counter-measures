<form id="deleteForm" action="{{ route('open.data.interrog-record.destroy', $data->id_interogation_record) }}" method="post">
    {{ method_field('DELETE') }}
    {{ csrf_field() }}
    @if ($data->percentage != '100')  
    <a href="{{ route('open.data.interrog-record.edit', $data->id_interogation_record) }}"
        class="btn btn-warning btn-icon btn-sm" data-toggle="tooltip" data-placement="top" title="Edit"><i
            class="bi bi-pencil-fill text-white"></i></a>

    @endif
    <a href="{{ route('open.data.interrog-record.show', $data->id_interogation_record) }}"
        class="btn btn-primary btn-icon btn-sm" data-toggle="tooltip" data-placement="top" title="Detail"><i
            class="bi bi-eye-fill text-white"></i></a>
    <button onclick="deleteData(event)" data-id="{{ $data->id_interogation_record }}"
        class="btn btn-danger btn-icon btn-sm" data-toggle="tooltip" data-placement="top" title="Delete"><i
            class="bi bi-trash-fill text-white"></i></button>
</form>