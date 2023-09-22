<x-admin-layout>

    @section('links')

        <script src="{{Vite::asset('resources/assets/jquery.js')}}"></script>

    @endsection

    <form class="flex flex-wrap" id="productForm" enctype="multipart/form-data" method="post">
        @csrf
        <div class="w-full sm:w-1/2 md:w-1/3 mb-6 mx-2">
            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Product</label>
            <select id="name" name="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                <option value="">Select Product</option>
                @foreach($products as $product)
                    <option value="{{ $product->name }}">{{ $product->name  }}</option>
                @endforeach
            </select>
        </div>
        <div class="w-full sm:w-1/2 md:w-1/3 mb-12 mx-12">
            <label for="quantity" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Quantity</label>
            <input type="number" id="quantity" name="quantity" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
        </div>
        <!-- Color -->
        <div class="w-full sm:w-1/2 md:w-1/3 mb-6 mx-2">
            <label for="color" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Color</label>
            <select id="color" name="color" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                <option value="">Select Color</option>
                @foreach($colors as $color)
                    <option value="{{ $color->color }}">{{ $color->color }}</option>
                @endforeach
            </select>
        </div>
        <!-- Size -->
        <div class="w-full sm:w-1/2 md:w-1/3 mb-12 mx-12">
            <label for="size" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Size</label>
            <select id="size" name="size" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                <option value="">Select Size</option>
                @foreach($sizes as $size)
                    <option value="{{ $size->size }}">{{ $size->size }}</option>
                @endforeach
            </select>
        </div>
        <!-- Discount -->
        <div class="w-full sm:w-1/2 md:w-1/3 mb-6 mx-2">
            <label for="discount" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Discount %</label>
            <select id="discount" name="discount" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                <option value="">Select Discount</option>
                @foreach($discounts as $discount)
                    <option value="{{ $discount->discount_percent }}">{{ $discount->discount_percent }}%</option>
                @endforeach
            </select>
        </div>
        <!-- Category -->
        <div class="w-full sm:w-1/2 md:w-1/3 mb-12 mx-12">
            <label for="category" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Category</label>
            <select id="category" name="category" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                <option value="">Select Category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->category }}">{{ $category->category }}</option>
                @endforeach
            </select>
        </div>
        <div class="w-full sm:w-1/2 md:w-1/3 mb-6 mx-2">
            <label for="price" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Price</label>
            <input type="number" id="price" name="price" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
        </div>
    </form>
    <button type="submit" form="productForm" id="submitBtn" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>


    <script>

        $(document).ready(function() {
            $('#submitBtn').click(function(e) {
                e.preventDefault(); // Prevent the default form submission behavior

                // Create a new FormData object
                var formData = new FormData($('#productForm')[0]);

                // Append the file input to the FormData object
                // var fileInput = $('#image')[0];
                // formData.append('image', fileInput.files[0]);

                // Send AJAX request
                $.ajax({
                    url: "{{ route('products.store') }}",
                    type: 'post',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        // Handle success response
                        console.log(response);
                        $('#productForm')[0].reset();
                    },
                    error: function(xhr, status, error) {
                        // Handle error response
                        console.log(xhr.responseText);
                    }
                });
            });
        });


    </script>

</x-admin-layout>
