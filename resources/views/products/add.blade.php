<x-admin-layout>

    @section('links')

        <script src="{{Vite::asset('resources/assets/jquery.js')}}"></script>

    @endsection

    <form class="flex flex-wrap" id="productForm" enctype="multipart/form-data" method="post">
        @csrf
        <div class="w-full sm:w-1/2 md:w-1/3 mb-6 mx-2">
            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Product Name</label>
            <input type="text" id="name" name="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
        </div>
        <div class="w-full sm:w-1/2 md:w-1/3 mb-12 mx-12">
            <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
            <textarea id="description" name="description" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 h-24 resize-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required></textarea>
        </div>

        <div class="w-full sm:w-1/2 md:w-1/3 mb-6 mx-2">
            <label for="image" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Image</label>
            <input type="file" id="image" name="image">
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
                var fileInput = $('#image')[0];
                formData.append('image', fileInput.files[0]);

                // Send AJAX request
                $.ajax({
                    url: "{{ route('addProduct.store') }}",
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
