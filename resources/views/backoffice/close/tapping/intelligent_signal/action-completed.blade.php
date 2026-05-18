<form id="deleteForm" action="{{ route('close.tapping.intelligent_signal.destroy', $data->id_tapping_intelligent_signal) }}" method="post">
    {{ method_field('DELETE') }}
    {{ csrf_field() }}
    <a href="{{ route('close.tapping.intelligent_signal.show', $data->id_tapping_intelligent_signal) }}" class="btn btn-primary btn-icon btn-sm"
       data-toggle="tooltip"
       data-placement="top"><i class="bi bi-eye-fill text-white"></i></a>
    <button onclick="deleteData(event)" data-id="{{ $data->id_tapping_intelligent_signal }}" class="btn btn-danger btn-icon btn-sm"
            data-toggle="tooltip" data-placement="top" title="Delete"><i class="bi bi-trash-fill text-white"></i>
    </button>
</form>
