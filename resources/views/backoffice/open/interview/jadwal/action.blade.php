<form id="deleteForm" action="{{ route('open.interview.jadwal.destroy', $data->id_interview_scheduler) }}" method="post">
    {{ method_field('DELETE') }}
    {{ csrf_field() }}
    @if ($data->caseProgress->percentage != '100')  
    <a href="{{ route('open.interview.jadwal.edit', $data->id_interview_scheduler) }}" class="btn btn-warning btn-icon btn-sm"
      data-toggle="tooltip" data-placement="top" title="Edit"><i class="bi bi-pencil-fill text-white"></i></a>
    @endif
    <a href="{{ route('open.interview.jadwal.show', $data->id_interview_scheduler) }}" class="btn btn-primary btn-icon btn-sm"
       data-toggle="tooltip"
       data-placement="top"><i class="bi bi-eye-fill text-white"></i></a>
 
    <button onclick="deleteData(event)" data-id="{{ $data->id_interview_scheduler }}" class="btn btn-danger btn-icon btn-sm"
            data-toggle="tooltip" data-placement="top" title="Delete"><i class="bi bi-trash-fill text-white"></i>
    </button>

</form>
