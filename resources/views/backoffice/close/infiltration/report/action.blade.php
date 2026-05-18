<form id="deleteForm" action="{{ route('close.infiltration.report.destroy', $data->id) }}" method="post">
    {{ method_field('DELETE') }}
    {{ csrf_field() }}
    <a href="{{ route('close.infiltration.report.show', $data->id) }}" class="btn btn-primary btn-icon btn-sm"
       data-toggle="tooltip"
       data-placement="top"><i class="bi bi-eye-fill text-white"></i></a>
    
</form>
