
<style>
    /* Remove blue color and underline from links within the product list */
    #productList a {
        color: #333;
        text-decoration: none;
    }

    #productList a:hover {
        color: #0a59da ;
    }
    .pagination-wrapper {
        display: flex;
        justify-content: flex-end;
</style>

<div id="productList1">
    <div class="grid grid-cols-3 gap-8">
        @foreach ($inventaries as $inventary)

            @if ($inventary->discounts !== null)
                @php
                    $discount = $inventary->discounts->discount_percent / 100;
                    $newPrice = $inventary->price - ($inventary->price * $discount);
                @endphp
            @endif

            <div class="bg-gray-100 p-4 shadow-md">
                <a href="{{ route('home.details', $inventary->id) }}" class="text-blue-500 hover:underline">
                    <img src="{{ asset('storage') . '/' . $inventary->products->images[0]->name }}" alt="Product Image" class="h-auto max-w-lg rounded-lg" width="200" height="500">
                    <h2 class="text-lg font-bold">{{ $inventary->products->name }}</h2>
                    <h3> {{$inventary->products->desc}}</h3>
                </a>
            </div>
        @endforeach
    </div>

    <div class="mt-4 pagination-wrapper">
        {{ $inventaries->appends(request()->query())->links() }}
    </div>
</div>
