<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">

            <h1 class="flex-sm-fill h3 my-2">
                {{ $pageHeader['title'] }}
                <small class="d-block d-sm-inline-block mt-2 mt-sm-0 font-size-base font-w400 text-muted">
                    {{ $pageHeader['subtitle'] }}
                </small>
            </h1>

            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    @foreach($pageHeader['breadcrumbs'] as $item)
                        @if($loop->last)
                            <li class="breadcrumb-item active" aria-current="page">{{ $item['label'] }}</li>
                        @else
                            <li class="breadcrumb-item">
                                <a class="link-fx" href="{{ $item['url'] ?? '#' }}">{{ $item['label'] }}</a>
                            </li>
                        @endif
                    @endforeach
                </ol>
            </nav>

        </div>
    </div>
</div>
