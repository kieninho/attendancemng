<div class="breadcrumb">
    @if(isset($breadcrumbs))
        <ol class="breadcrumb">
            @foreach ($breadcrumbs as $breadcrumb)
                <li class="breadcrumb-item">
                    @if ($breadcrumb['url'])
                        <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['text'] }}</a>
                    @else
                        {{ $breadcrumb['text'] }}
                    @endif
                </li>
            @endforeach
        </ol>
    @endif
</div>