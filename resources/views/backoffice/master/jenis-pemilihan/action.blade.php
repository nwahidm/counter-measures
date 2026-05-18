<form id="deleteForm" action="{{ route('master.jenis-pemilihan.destroy', $data->kode) }}" method="post">
    {{ method_field('DELETE') }}
    {{ csrf_field() }}
    <a href="{{ route('master.jenis-pemilihan.edit', ['jenis_pemilihan' => $data->kode]) }}" class="btn btn-warning btn-icon btn-sm"
        data-toggle="tooltip" data-placement="top" title="Edit"><i class="bi bi-pencil-fill text-white"></i></a>
    <button onclick="deleteData(event)" data-id="{{ $data->kode }}" class="btn btn-danger btn-icon btn-sm"
        data-toggle="tooltip" data-placement="top" title="Delete"><i class="bi bi-trash-fill text-white"></i></button>
</form>
