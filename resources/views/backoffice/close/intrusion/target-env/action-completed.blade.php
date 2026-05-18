<form id="deleteForm" action="{{ route('close.intrusion.target-env.destroy', $data->id) }}" method="post">
    {{ method_field('DELETE') }}
    {{ csrf_field() }}
    <a href="{{ route('close.intrusion.target-env.show', $data->id) }}" class="btn btn-primary btn-icon btn-sm"
       data-toggle="tooltip"
       data-placement="top"><i class="bi bi-eye-fill text-white"></i></a>
    <button onclick="deleteData(event)" data-id="{{ $data->id }}" class="btn btn-danger btn-icon btn-sm"
            data-toggle="tooltip" data-placement="top" title="Delete"><i class="bi bi-trash-fill text-white"></i>
    </button>
</form>
