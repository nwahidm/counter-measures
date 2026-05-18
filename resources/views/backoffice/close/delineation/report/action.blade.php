<form id="deleteForm" action="{{ route('close.delineation.report.destroy', $data->id) }}" method="post">
    {{ method_field('DELETE') }}
    {{ csrf_field() }}
    <a href="{{ route('close.delineation.report.show', $data->id) }}" class="btn btn-primary btn-icon btn-sm"
       data-toggle="tooltip" 
       target="_blank"
       data-placement="top"><i class="bi bi-eye-fill text-white"></i></a>
    
</form>
