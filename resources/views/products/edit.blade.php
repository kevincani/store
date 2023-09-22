<x-admin-layout>
    @section('links')
        <link rel="stylesheet" href="{{Vite::asset('resources/assets/datatables.css')}}">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

        <script src="{{Vite::asset('resources/assets/jquery.js')}}"></script>
        <script src="{{Vite::asset('resources/assets/jquery-validate.js')}}"></script>

    @endsection


        <div align="right">
            <button type="button" name="addProduct" id="addProduct" class="focus:outline-none text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900">Add Product</button>
        </div>


        <table id="products-table" class="display">
            <thead>
            <tr>
                <th>Name</th>
                <th>Color</th>
                <th>Category</th>
                <th>Discount</th>
                <th>Size</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Action</th>
            </tr>
            </thead>
        </table>



        <div id="productModal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative w-full max-w-2xl max-h-full">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <!-- Modal header -->
                    <div class="flex items-start justify-between p-5 border-b rounded-t dark:border-gray-600">
                        <h3 class="text-xl font-semibold text-gray-900 lg:text-2xl dark:text-white">
                            Terms of Service
                        </h3>
                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="p-6 space-y-6">
                        <form class="flex flex-wrap" id="editProduct" enctype="multipart/form-data" method="post" >
                            @csrf
                            <div class="w-full sm:w-1/2 md:w-1/3 mb-6 mx-2">
                                <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Product Name</label>
                                <input type="text" id="name" name="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                            </div>
                            <div class="w-full sm:w-1/2 md:w-1/3 mb-12 mx-12">
                                <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
                                <textarea id="description" name="description" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 h-24 resize-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required></textarea>
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
                            <div class="w-full sm:w-1/2 md:w-1/3 mb-12 mx-12">
                                <label for="quantity" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Quantity</label>
                                <input type="number" id="quantity" name="quantity" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                            </div>
                            <!-- Category -->
                            <div class="w-full sm:w-1/2 md:w-1/3 mb-6 mx-2">
                                <label for="category" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Category</label>
                                <select id="category" name="category" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->category }}">{{ $category->category }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w-full sm:w-1/2 md:w-1/3 mb-12 mx-12">
                                <label for="price" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Price</label>
                                <input type="number" id="price" name="price" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                            </div>
                            <div class="w-full sm:w-1/2 md:w-1/3 mb-6 mx-2">
                                <label for="image" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Image</label>
                                <input type="file" id="image" name="image">
                            </div>
                            <div class="w-full sm:w-1/2 md:w-1/3 mb-12 mx-12">
                                <label for="currentImage" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white hidden">Current Image</label>
                                <img src="{{ asset('') }}" alt="Current Image" id="currentImage" class="w-40 h-auto hidden">
                            </div>
                            <input type="hidden" name="action" id="action" value="Add" />
                            <input type="hidden" name="hiddenId" id="hiddenId" />
                        </form>
                    </div>
                    <!-- Modal footer -->
                    <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                        <button type="submit" form="editProduct"  name="actionButton" id="actionButton" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Add User</button>
                        <button type="button" form="editProduct" id="closeBtn" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600">Decline</button>
                    </div>
                </div>
            </div>
        </div>


        <div id="confirmModal" tabindex="-1" class="fixed top-0 left-0 right-0 z-50 hidden p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative w-full max-w-md max-h-full">
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="confirmModal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                    <div class="p-6 text-center">
                        <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                        </svg>
                        <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Are you sure you want to delete this product?</h3>
                        <button data-modal-hide="confirmModal" id="okButton" type="button" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                            Yes, I'm sure
                        </button>
                        <button data-modal-hide="confirmModal" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">No, cancel</button>
                    </div>
                </div>
            </div>
        </div>



        <script type="module">


            $(document).ready(function() {
              var datatable = $('#products-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('editProducts.view') }}",
                    columns: [
                        {data: 'products.name', name: 'products.name'},
                        {data: 'colors.color', name: 'colors.color'},
                        {data: 'categories.category', name: 'categories.category'},
                        {data: 'discounts.discount_percent', name: 'discounts.discount_percent'},
                        {data: 'sizes.size', name: 'sizes.size'},
                        {data: 'quantity', name: 'quantity'},
                        {data: 'price', name: 'price'},
                        {data: 'action', name: 'action', orderable: false, searchable: false},
                    ]
                });

                const $targetEl = document.getElementById('productModal');
                const options = {

                    closable: true,
                    onHide: () => {
                        console.log('editModal is hidden');
                    },
                    onShow: () => {
                        console.log('editModal is shown');
                    },
                    onToggle: () => {
                        console.log('editModal has been toggled');
                    }
                }

                let modal = new Modal($targetEl, options);

                const $targetEl1 = document.getElementById('confirmModal');
                const options1 = {

                    closable: true,
                    onHide: () => {
                        console.log('confirmModal is hidden');
                    },
                    onShow: () => {
                        console.log('confirmModal is shown');
                    },
                    onToggle: () => {
                        console.log('confirmModal has been toggled');
                    }
                }

                let confirmModal = new Modal($targetEl1, options1);


                $('#addProduct').click(function () {
                    // $('.modal-title').text('Add New Record');
                    $('h3').html('Add User');
                    $('#actionButton').val('Add');
                    $('#actionButton').html('Add');
                    $('#action').val('Add');

                    modal.show();
                });
                // Event listener for close button
                $('#closeBtn').click(function () {
                    modal.hide();
                });

                $('#editProduct').on('submit', function (event) {
                    event.preventDefault();


                    var actionUrl = '';

                    if ($('#action').val() == 'Add') {
                        actionUrl = "{{ route('products.store') }}";
                    }

                    if($('#action').val() == 'Edit')
                    {
                        actionUrl = "{{ route('products.update') }}";
                    }

                    var formData = new FormData($('#editProduct')[0]);

                    // Append the file input to the FormData object
                    var fileInput = $('#image')[0];
                    formData.append('image', fileInput.files[0]);

                    $.ajax({
                        type: 'post',
                        url: actionUrl,
                        data: formData ,
                        processData: false,
                        contentType: false,
                        success: function (data) {
                            console.log('success: ' + data);
                            // $('#successAlert').removeClass('hidden');
                            $('#editProduct').trigger('reset');
                            $('#products-table').DataTable().ajax.reload();
                            modal.hide();
                        },
                        error: function (response) {
                            // var errors = data.responseJSON;
                            // console.log(errors);

                            if (response.status == 422) {
                                validator.showErrors(response.responseJSON.errors);
                            } else {
                                alert(response)
                            }
                        }
                    });

                });

                $('#products-table').on('click', 'button[name="edit"]', function() {
                    var row = $(this).closest('tr');
                    var data = datatable.row(row).data();
                    // Perform your edit logic here using the data retrieved from the clicked row
                    console.log('Edit button clicked:', data);
                    event.preventDefault();
                    var id = $(this).attr('id');
                    $.ajax({
                        url :"/editProducts/edit/"+id+"/",
                        processData: false,
                        contentType: false,
                        success:function(response)
                        {
                            console.log('AJAX response:', response);

                            var image = document.getElementById('currentImage');
                            image.classList.remove('hidden');

                            var label = document.querySelector('label[for="currentImage"]');
                            label.classList.remove('hidden');


                            var product = response.result.product;
                            var imageUrl = " {{ asset('storage') }}/" + product.images[0].name;


                            $('#name').val(product.name);
                            $('#description').val(product.desc);
                            $('#color').val(product.colors[0].color);
                            $('#size').val(product.sizes[0].size);
                            $('#discount').val(product.discounts[0].discount_percent);
                            $('#quantity').val(product.inventary[0].quantity);
                            $('#price').val(product.inventary[0].price);
                            $('#category').val(product.categories[0].category);
                            // Display current image
                            $('#currentImage').attr('src', imageUrl);
                            $('#hiddenId').val(id);
                            $('#actionButton').val('Update');
                            $('#actionButton').html('Update');
                            $('#action').val('Edit');
                            $('h3').html('Edit User');
                            modal.show();
                        },
                        error: function(data) {
                            var errors = data.responseJSON;
                            console.log(errors);
                        }
                    })
                });

                var userId;

                $(document).on('click', 'button[name="delete"]', function(){
                    userId = $(this).attr('id');
                    confirmModal.show();
                });

                $('#okButton').click(function(){
                    $.ajax({
                        url:"editProducts/destroy/"+userId+"/",
                        beforeSend:function(){
                            $('#okButton').text('Deleting...');
                        },
                        success:function(data)
                        {
                            setTimeout(function(){
                                confirmModal.hide();
                                $('#products-table').DataTable().ajax.reload();
                                alert('User Deleted');
                            }, 1000);
                        }

                    })
                });

            });



        </script>

</x-admin-layout>
