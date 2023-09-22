<x-admin-layout>
    @section('links')
        <script src="{{Vite::asset('resources/assets/jquery.js')}}"></script>
    @endsection

        <div id="display">
            <div class="grid grid-cols-3 gap-8">
                <div class="col-span-3 mb-4">
                    <!-- Filters and Search Input Container -->
                    <div class="flex items-center justify-between"> <!-- Apply 'flex' class to align items in the same row -->
                        <!-- Filters Container -->
                        <div class="flex items-center space-x-4"> <!-- Keep filters in the same flex container -->
                            <div>
                                <label for="category" class="text-sm font-medium text-gray-900 dark:text-white">Category:</label>
                                <select id="category" name="category" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-auto p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="">All Categories</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->category }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="size" class="text-sm font-medium text-gray-900 dark:text-white">Size:</label>
                                <select name="size" id="size" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-auto p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="">All Sizes</option>
                                    @foreach ($sizes as $size)
                                        <option value="{{ $size->id }}">{{ $size->size }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Search Input Container -->
                        <div>
                            <form class="flex items-center">
                                <label for="default-search" class="text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                        </svg>
                                    </div>
                                    <input type="search" id="search" class="block w-full p-4 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div id="productList">
                @include('display.productList')
            </div>

{{--        <div id="productList">--}}
{{--            <div class="grid grid-cols-3 gap-8">--}}
{{--                @foreach ($products as $product)--}}

{{--                    @if ($product->discounts->isNotEmpty())--}}
{{--                        @php--}}
{{--                            $discount = $product->discounts[0]->discount_percent / 100;--}}
{{--                            $newPrice = $product->pivots[0]->pivot->price - ($product->pivots[0]->pivot->price * $discount);--}}
{{--                        @endphp--}}
{{--                    @else--}}
{{--                        @php--}}
{{--                            $newPrice = $product->pivots[0]->pivot->price;--}}
{{--                        @endphp--}}
{{--                    @endif--}}

{{--                    <div class="bg-white p-4 shadow-md">--}}
{{--                        <img src="{{ asset('storage') . '/' . $product->images[0]->name }}" alt="Product Image" class="w-full mb-4">--}}
{{--                        <h2 class="text-lg font-bold">{{ $product->name }}</h2>--}}

{{--                        <div class="flex justify-between items-center">--}}

{{--                        @if ($product->discounts->isNotEmpty())--}}
{{--                           <p>{{ $newPrice }}</p>--}}
{{--                        @endif--}}

{{--                            <p class="line-through"> {{ $product->pivots[0]->pivot->price }}</p>--}}


{{--                        </div>--}}
{{--                        <a href="{{ route('products.details', $product->id) }}" class="text-blue-500 hover:underline">Show More</a>--}}
{{--                    </div>--}}
{{--                @endforeach--}}
{{--            </div>--}}

{{--            <div class="mt-4">--}}
{{--                {{ $products->links() }}--}}
{{--            </div>--}}
{{--        </div>--}}

    </div>

        <script>
            $(document).ready(function() {
                function updateProductList() {
                    var categoryId = $('#category').val();
                    var sizeId = $('#size').val();
                    var searchQuery = $('#search').val(); // Get the search query

                    $.ajax({
                        url: '{{ route('products.filterCategory') }}',
                        type: 'GET',
                        data: { category: categoryId, size: sizeId, search: searchQuery }, // Include 'search' parameter
                        success: function(data) {
                            $('#productList').html(data);
                        },
                        error: function() {
                            console.log('Error fetching products by category and size.');
                        }
                    });
                }

                // Category, Size, and Search Filters
                $('#category').change(function() {
                    updateProductList();
                });
                $('#size').change(function() {
                    updateProductList();
                });
                $('#search').keyup(function() { // Trigger on keyup event for search input
                    updateProductList();
                });

                @auth
                const user = {
                    id: {{ auth()->user()->id }},
                    username: "{{ auth()->user()->username }}"
                    // Add more user data as needed
                };
                localStorage.setItem('user', JSON.stringify(user));
                console.log('User data stored in local storage:', user);
                @endauth

            });
        </script>
</x-admin-layout>
