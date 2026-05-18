@unless ($breadcrumbs->isEmpty())
    <ul class="breadcrumb breadcrumb-separatorless fw-semibold">
        @foreach ($breadcrumbs as $breadcrumb)
            @if ($breadcrumb->url && !$loop->last)
                <li class="breadcrumb-item text-gray-600 fw-bold lh-1"><a href="{{ $breadcrumb->url }}" class="text-gray-600 text-hover-primary">{!! $breadcrumb->title !!}</a></li>
                <li class="breadcrumb-item">
                    <i class="ki-outline ki-right fs-7 text-gray-700 mx-n1"></i>
                </li>
            @else
                <li class="breadcrumb-item text-white fw-bold lh-1" aria-current="page">{!! $breadcrumb->title !!}</li>
            @endif
        @endforeach
    </ul>
@endunless
