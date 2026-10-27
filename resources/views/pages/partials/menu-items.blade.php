<ul class="menu-list">
    @foreach ($items as $item)
        <li>
            @if ($item['url'])
                <a href="{{ $item['url'] }}" @if (request()->url() === $item['url']) aria-current="page" @endif>{{ $item['label'] }}</a>
            @else
                <span>{{ $item['label'] }}</span>
            @endif
            @if ($item['children'])
                @include('pages.partials.menu-items', ['items' => $item['children']])
            @endif
        </li>
    @endforeach
</ul>
