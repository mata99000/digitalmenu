<div>
    <h1>Items</h1>
    <ul>
        @foreach ($items as $item)
            <li>
                <h2>{{ $item->name }}</h2>
                <p>{{ $item->description }}</p>
                <p>{{ $item->price }}</p>
                <p>{{ $item->comment }}</p>
                @if ($item->image)
                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}">
                @endif
            </li>
        @endforeach
    </ul>
</div>
