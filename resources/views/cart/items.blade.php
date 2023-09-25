
{{--<meta name="csrf-token" content="{{ csrf_token() }}">--}}

<div id="productList1">
    <button class="text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
            type="submit" id="checkoutButton" onclick="checkout()">Checkout</button>
    <div class="grid grid-cols-3 gap-8">
        @foreach ($inventaries as $inventary)

            @if ($inventary->discounts !== null)
                @php
                    $discount = $inventary->discounts->discount_percent / 100;
                    $newPrice = $inventary->price - ($inventary->price * $discount);
                @endphp
            @else
                @php
                    $newPrice = $inventary->price;
                @endphp
            @endif

            <div class="bg-gray-100 p-4 shadow-md">
                <img src="{{ asset('storage') . '/' . $inventary->products->images[0]->name }}" alt="Product Image" class="w-full mb-4">
                <h2 class="text-lg font-bold">{{ $inventary->products->name }}</h2>

                <div class="flex justify-between items-center">

                    @if ($inventary->discounts !== null)
                        <p>{{ $newPrice }}</p>
                    @endif

                    <p class="line-through"> {{ $inventary->price }}</p>


                </div>
                <br>
                <button type="button" onclick="removeFromCart({{ $inventary->id }})" class="text-white bg-gradient-to-br from-purple-600 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2">Remove from Cart</button>

            </div>
        @endforeach

    </div>




    {{--    <div class="mt-4 pagination-wrapper">--}}
    {{--        {{ $products->appends(request()->query())->links() }}--}}
    {{--    </div>--}}
</div>
<script>
    function removeFromCart(product_id) {
        console.log(product_id);
        let cart = JSON.parse(localStorage.getItem('cart')) || [];

        // Find the index of the product in the cart array
        const index = cart.findIndex(item => item.id === product_id);

        if (index !== -1) {
            // Remove the product from the cart
            cart.splice(index, 1);

            // Save the updated cart array back to local storage
            localStorage.setItem('cart', JSON.stringify(cart));

            alert('Product removed from the cart!');

            location.reload();

        }
    }

    function checkout() {
        var inventaries = @json($inventaries);

        let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        $.ajax({
            url: '/session',
            method: 'POST',
            data: {
                inventaries: inventaries,
                _token: csrfToken
            },
            dataType: 'json',

            success: function(response) {
                var checkoutUrl = response.checkout_url;
                // Open the Checkout URL in a new window or tab
                window.open(checkoutUrl, '_blank');
            },
            error: function(xhr, status, error) {
                console.error(error);
                alert('An error occurred during checkout. Please try again.');
            }
        });
    }


</script>
