@unless ($breadcrumbs->isEmpty())
    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
        @foreach ($breadcrumbs as $breadcrumb)
            @if ($breadcrumb->url && !$loop->last)
                <li class="breadcrumb-item text-muted"><a href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a></li>
                <li class="breadcrumb-item">
                    <span class="bullet bg-gray-400 w-5px h-2px"></span>
                </li>
            @else
                <li class="breadcrumb-item text-dark" aria-current="page">{{ $breadcrumb->title }}</li>
            @endif
        @endforeach
    </ul>
@endunless
