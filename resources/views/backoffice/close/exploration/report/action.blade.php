{{-- <form id="deleteForm" >
    {{ method_field('DELETE') }}
    {{ csrf_field() }}
</form> --}}

<a href="{{ route('close.exploration.report.download', $data->id) }}" class="btn btn-primary btn-icon btn-sm"
    data-toggle="tooltip" data-placement="top"><i class="bi bi-download text-white"></i></a>
