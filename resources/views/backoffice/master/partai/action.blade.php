<form id="deleteForm" action="{{ route('master.partai.destroy', $data->id) }}" method="post">
    {{ method_field('DELETE') }}
    {{ csrf_field() }}
    <a href="{{ route('master.partai.edit', $data->id) }}" class="btn btn-warning btn-icon btn-sm"
        data-toggle="tooltip" data-placement="top" title="Edit"><i class="bi bi-pencil-fill text-white"></i></a>
    <button onclick="deleteData(event)" data-id="{{ $data->id }}" class="btn btn-danger btn-icon btn-sm"
        data-toggle="tooltip" data-placement="top" title="Delete"><i class="bi bi-trash-fill text-white"></i></button>
</form>
