
{{--<meta name="csrf-token" content="{{ csrf_token() }}">--}}

<div id="productList1">
    <button class="text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
            type="submit" id="checkoutButton" onclick="checkout()">Checkout</button>
    <div class="grid grid-cols-3 gap-8">
        @foreach ($products as $product)

            @if ($product->discounts->isNotEmpty())
                @php
                    $discount = $product->discounts[0]->discount_percent / 100;
                    $newPrice = $product->inventary[0]->price - ($product->inventary[0]->price * $discount);
                @endphp
            @else
                @php
                    $newPrice = $product->inventary[0]->price;
                @endphp
            @endif

            <div class="bg-white p-4 shadow-md">
                    <img src="{{ asset('storage') . '/' . $product->images[0]->name }}" alt="Product Image" class="w-full mb-4">
                    <h2 class="text-lg font-bold">{{ $product->name }}</h2>

                    <div class="flex justify-between items-center">

                        @if ($product->discounts->isNotEmpty())
                            <p>{{ $newPrice }}</p>
                        @endif

                        <p class="line-through"> {{ $product->inventary[0]->price }}</p>


                    </div>
                <br>
                <button type="button" onclick="removeFromCart({{ $product->inventary[0]->id }})" class="text-white bg-gradient-to-br from-purple-600 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2">Remove from Cart</button>

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
        let products = @json($products);

            let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        $.ajax({
            url: '/session',
            method: 'POST',
            data: {
                products: products,
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
