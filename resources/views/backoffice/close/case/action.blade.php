<form id="deleteForm" action="{{ route('close.case.destroy', $data->id) }}" method="post">
    {{ method_field('DELETE') }}
    {{ csrf_field() }}
    
    <a href="{{ route('close.case.show', $data->id) }}" class="btn btn-primary btn-icon btn-sm" data-toggle="tooltip"
        data-placement="top" ><i class="bi bi-eye-fill text-white"></i></a>
    
    @if ($data->progress?->percentage!= '100') 
        <a href="{{ route('close.case.edit', $data->id) }}" class="btn btn-warning btn-icon btn-sm"
        data-toggle="tooltip" data-placement="top" title="Edit"><i class="bi bi-pencil-fill text-white"></i></a>
    @endif
    @if ($data->progress?->percentage == '0') 
        <button onclick="deleteData(event)" data-id="{{ $data->id }}" class="btn btn-danger btn-icon btn-sm"
        data-toggle="tooltip" data-placement="top" title="Delete"><i class="bi bi-trash-fill text-white"></i></button>
    @endif
</form>
