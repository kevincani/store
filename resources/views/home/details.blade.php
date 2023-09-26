<x-admin-layout>

    @section('links')
        <script src="{{Vite::asset('resources/assets/jquery.js')}}"></script>
    @endsection

    {{--    @if ($products->discounts->isNotEmpty())--}}
    {{--        @php--}}
    {{--            $discount = $products->discounts[0]->discount_percent / 100;--}}
    {{--            $newPrice = $products->pivots[0]->pivot->price - ($products->pivots[0]->pivot->price * $discount);--}}
    {{--        @endphp--}}
    {{--    @else--}}
    {{--        @php--}}
    {{--            $newPrice = $products->pivots[0]->pivot->price;--}}
    {{--        @endphp--}}
    {{--    @endif--}}

    <main class="my-8">
        <div class="container mx-auto px-6">
            <div class="md:flex md:items-center">
                <div class="w-full h-64 md:w-1/2 lg:h-96">
                    <img class="h-full w-full rounded-md object-cover max-w-lg mx-auto" src="{{ asset('storage') . '/' . $inventary[0]->products->images[0]->name }}" alt="Product Image">
                </div>
                <div class="w-full max-w-lg mx-auto mt-5 md:ml-8 md:mt-0 md:w-1/2">
                    <h3 class="text-gray-700 uppercase text-lg">{{ $inventary[0]->products->name }}</h3>
                    <span id="priceValue" class="text-gray-500 mt-3">$</span>
                    <hr class="my-3">
                    <div class="mt-2">
                        <label class="text-gray-700 text-sm" for="count">Quantity:</label>
                        <div class="flex items-center mt-1">
                            <button id="decreaseCount" class="text-gray-500 focus:outline-none focus:text-gray-600">
                                <svg class="h-5 w-5" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor"><path d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </button>
                            <span id="quantityValue" class="text-gray-700 text-lg mx-2">1</span>
                            <button id="increaseCount" class="text-gray-500 focus:outline-none focus:text-gray-600">
                                <svg class="h-5 w-5" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor"><path d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </button>
                        </div>

                    </div>
                    <div class="mt-3">
                        <label class="text-gray-700 text-sm" for="count">Size:</label>
                        <div class="flex items-center mt-1">
                            <select id="size-dropdown"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block px-1 py-1.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                @foreach ($inventary[0]->products->sizes as $index => $size)
                                    <option value="{{ $index }}">{{ $size->size }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="text-gray-700 text-sm" for="count">Color:</label>
                        <div class="flex items-center mt-1">
                            <select id="colorDropdown"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block px-1 py-1.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                @foreach($colors as $color)
                                    <option value="{{ $color->color }}">{{ $color->color }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{--                        <div class="mt-3">--}}
                    {{--                            <label class="text-gray-700 text-sm" for="count">Size:</label>--}}
                    {{--                            <div class="flex items-center mt-1">--}}
                    {{--                                {{$products->sizes[0]->size}}--}}
                    {{--                            </div>--}}
                    {{--                        </div>--}}
                    <div class="flex items-center mt-6">
                        <button type="button" id="addToCartButton" class="text-white bg-gradient-to-br from-purple-600 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2">Add to Cart</button>

                        {{--                            <button class="mx-2 text-gray-600 border rounded-md p-2 hover:bg-gray-200 focus:outline-none">--}}
                        {{--                                <svg class="h-5 w-5" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor"><path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>--}}
                        {{--                            </button>--}}
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-gray-200">
        <div class="container mx-auto px-6 py-3 flex justify-between items-center">
            <a href="#" class="text-xl font-bold text-gray-500 hover:text-gray-400">Brand</a>
            <p class="py-2 text-gray-500 sm:py-0">All rights reserved</p>
        </div>
    </footer>




    <script>
        var selectedSizeIndex= document.getElementById('size-dropdown').selectedIndex;

        document.getElementById('size-dropdown').addEventListener('change', function() {
            selectedSizeIndex = this.selectedIndex;
            maxQuantity = parseInt(inventary[selectedSizeIndex].quantity);
            count = 1;
            updateCount();
            newPrice = parseInt(inventary[selectedSizeIndex].price - (inventary[selectedSizeIndex].price * (discounts[selectedSizeIndex].discount_percent/100)));
            updatePrice();
            console.log("Selected size index:", selectedSizeIndex);
        })


        // Price value
        const discounts = @json($inventary[0]->products->discounts);
        const inventary = @json($inventary);
        var newPrice = parseInt(inventary[selectedSizeIndex].price - (inventary[selectedSizeIndex].price * (discounts[selectedSizeIndex].discount_percent/100)));
        const priceValue = document.getElementById("priceValue");
        const updatePrice = () => {
            priceValue.textContent = "$" + newPrice;
        }
        updatePrice();


        // Quantiy value
        const decreaseButton = document.getElementById("decreaseCount");
        const increaseButton = document.getElementById("increaseCount");
        const quantityValue = document.getElementById("quantityValue");
        {{--const maxQuantity = parseInt("{{ $products->pivots[0]->pivot->quantity }}");--}}
        var maxQuantity = parseInt(inventary[selectedSizeIndex].quantity);
        let count = 1;

        const updateCount = () => {
            quantityValue.textContent = count;
            decreaseButton.disabled = count === 1;
            increaseButton.disabled = count === maxQuantity;
        };

        decreaseButton.addEventListener("click", () => {
            if (count > 1) {
                count--;
                updateCount();
            }
        });

        increaseButton.addEventListener("click", () => {
            if (count < maxQuantity) {
                count++;
                updateCount();
            }
        });

        updateCount();




        // Function to add a product to the cart and save it in local storage
        function addToCart(id, quantity) {
            // Check if the cart already exists in local storage
            let cart = JSON.parse(localStorage.getItem('cart')) || [];

            // Add the user ID to the product data
            const user = JSON.parse(localStorage.getItem('user'));
            let user_id;
            if (user) {
                user_id = user.id;
            }
            // Add the product and quantity to the cart array
            cart.push({ id, quantity, user_id });

            // Save the updated cart array back to local storage
            localStorage.setItem('cart', JSON.stringify(cart));

            // Notify the user that the product has been added to the cart
            alert('Product added to the cart!');
        }





        // Get the product data from the Blade template
        const product = @json($inventary[0]->products->toArray());
        // Add event listener to the "Add to Cart" button
        document.getElementById('addToCartButton').addEventListener('click', function() {

            var selectedColor = document.getElementById('colorDropdown').value;

            var quantity = parseInt(document.getElementById('quantityValue').textContent);

            const sizes = @json($inventary[0]->products->sizes);
            var size = sizes[selectedSizeIndex].size;

            var id = product.id;
            var url = "{{ route('home.checkInventary', '::inventary') }}"
            url = url.replace('::inventary',inventary[selectedSizeIndex].id);
            $.ajax({
                url: url,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data:{
                    selectedColor: selectedColor,
                    quantity: quantity,
                    size: size,
                    id: id
                },

                success: function(response) {
                    if (response.status === 'success') {
                        addToCart(response.inventary_id,quantity);
                    } else if (response.status === 'error') {
                        // Product data is invalid or some other error occurred
                        alert(response.message);
                    } else {
                        // Handle other status values if needed
                        alert('Unexpected response status: ' + response.status);
                    }
                },
                error: function(error) {
                    // Handle any errors that occurred during the AJAX call
                    console.error('Error:', error);
                }
            });

        });


    </script>
</x-admin-layout>
