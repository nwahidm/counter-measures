<form id="deleteForm" action="{{ route('open.interview.saran_tl.destroy', $data->id_interview_advice_and_follow_up) }}" method="post">
    {{ method_field('DELETE') }}
    {{ csrf_field() }}
    <a href="{{ route('open.interview.saran_tl.show', $data->id_interview_advice_and_follow_up) }}" class="btn btn-primary btn-icon btn-sm"
      data-toggle="tooltip"
      data-placement="top"><i class="bi bi-eye-fill text-white"></i></a>
    @if ($data->caseProgress->percentage != '100')     
    <a href="{{ route('open.interview.saran_tl.edit', $data->id_interview_advice_and_follow_up) }}" class="btn btn-warning btn-icon btn-sm"
      data-toggle="tooltip" data-placement="top" title="Edit"><i class="bi bi-pencil-fill text-white"></i></a>
    @endif
    

   <button onclick="deleteData(event)" data-id="{{ $data->id_interview_advice_and_follow_up }}" class="btn btn-danger btn-icon btn-sm"
            data-toggle="tooltip" data-placement="top" title="Delete"><i class="bi bi-trash-fill text-white"></i>
    </button>

</form>
