<form id="deleteForm" action="{{ route('open.case.destroy', $data->id) }}" method="post">
    {{ method_field('DELETE') }}
    {{ csrf_field() }}
    <a href="{{ route('open.summary.detail', $data->id) }}" class="btn btn-primary btn-icon btn-sm" data-toggle="tooltip"
        data-placement="top" ><i class="bi bi-eye-fill text-white"></i></a>
</form>
