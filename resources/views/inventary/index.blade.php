<x-admin-layout>

    @section('links')

        <link rel="stylesheet" href="{{Vite::asset('resources/assets/datatables.css')}}">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

        <script src="{{Vite::asset('resources/assets/jquery.js')}}"></script>
        <script src="{{Vite::asset('resources/assets/jquery-validate.js')}}"></script>
    @endsection

        <div align="right">
            <button type="button" name="addInventary" id="addInventary" class="focus:outline-none text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900">Add Product</button>
        </div>


        <table id="inventaryDatatable" class="display">
            <thead>
            <tr>
                <th></th>
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



        <!-- Main modal -->
        <div id="inventaryModal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative w-full max-w-md max-h-full">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="authentication-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                    <div class="px-6 py-6 lg:px-8">
                        <h3 class="mb-4 text-xl font-medium text-gray-900 dark:text-white">Add to inventary</h3>
                        <div id="formContent">
                            <form id="inventaryForm" class="space-y-6" action="">
                                <div>
                                    <label for="product" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Product</label>
                                    <select id="product" name="product_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                                        <option value="">Select Product</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name  }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="color" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Color</label>
                                    <select id="color" name="color_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                                        <option value="">Select Color</option>
                                        @foreach($colors as $color)
                                            <option value="{{ $color->id }}">{{ $color->color }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="size" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Size</label>
                                    <select id="size" name="size_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                                        <option value="">Select Size</option>
                                        @foreach($sizes as $size)
                                            <option value="{{ $size->id }}">{{ $size->size }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="discount" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Discount %</label>
                                    <select id="discount" name="discount_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                                        <option value="">Select Discount</option>
                                        @foreach($discounts as $discount)
                                            <option value="{{ $discount->id }}">{{ $discount->discount_percent }}%</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="category" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Category</label>
                                    <select id="category" name="category_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->category }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="quantity" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Quantity</label>
                                    <input type="number" id="quantity" name="quantity" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                                </div>
                                <div>
                                    <label for="price" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Price</label>
                                    <input type="number" id="price" name="price" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                                </div>
                                <div>
                                    <input type="hidden" name="action" id="action" value="Add" />
                                    <input type="hidden" name="hiddenId" id="hiddenId" />
                                </div>

                                <button type="submit" form="inventaryForm"  name="sumbitBtn" id="sumbitBtn" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
                                <button type="button" form="inventaryForm" id="closeBtn" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600">Decline</button>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Confirm Modal -->
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
                        <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Are you sure you want to delete this user?</h3>
                        <button data-modal-hide="confirmModal" id="okButton" type="button" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                            Yes, I'm sure
                        </button>
                        <button data-modal-hide="confirmModal" id="cancelBtn" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">No, cancel</button>
                    </div>
                </div>
            </div>
        </div>




        <script type="module">
            $(document).ready(function (){
                var datatable = $('#inventaryDatatable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('inventary.datatable') }}",
                    columns: [
                        {
                            data: 'DT_RowIndex',
                            searchable: false,
                            orderable: false
                        },
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

                const $targetEl = document.getElementById('inventaryModal');
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


                var actionUrl = '';

                $('#addInventary').click(function (){
                    $('h3').html('Add to inventary');
                    $('#submitBtn').html('Submit');
                    $('#submitBtn').val('Submit');
                    actionUrl = "{{ route('inventary.store') }}";
                    $('#action').val('Add');
                    $('#inventaryForm').trigger('reset');
                    modal.show();
                });

                $('#closeBtn').click(function() {
                    modal.hide();
                });

                $('#inventaryForm').submit(function (){
                    event.preventDefault();
                    var dataForm = new FormData(this);
                    if($('#action').val() === "Edit"){
                        dataForm.append('_method', 'put');
                    }
                    $.ajax({
                        type: 'post',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url: actionUrl,
                        data: dataForm,
                        processData: false,
                        contentType: false,
                        success: function(data) {
                            console.log('success: '+data);
                            $('#inventaryForm').trigger('reset');
                            $('#inventaryDatatable').DataTable().ajax.reload();
                            modal.hide();
                        },
                        error: function(response) {

                            if(response.status==422){
                                validator.showErrors(response.responseJSON.errors);
                            }else{
                                alert(response)
                            }
                        }
                    });
                });

                $('#inventaryDatatable').on('click', 'button[name="edit"]', function() {
                    var row = $(this).closest('tr');
                    var data = datatable.row(row).data();

                    // $.each(data, function (index, value) {
                    //     $('#formContent #' + index).val(value).trigger('change')
                    // })

                    $('#product').val(data.products.id);
                    $('#color').val(data.colors.id);
                    $('#size').val(data.sizes.id);
                    $('#discount').val(data.discounts.id);
                    $('#category').val(data.categories.id);
                    $('#quantity').val(data.quantity);
                    $('#price').val(data.price);

                    var inventaryId = data.id;
                    console.log(data);
                    actionUrl = "{{ route('inventary.update', ':inventaryId') }}";
                    actionUrl = actionUrl.replace(':inventaryId', inventaryId);

                    $('#action').val('Edit');
                    $('#sumbitBtn').val('Update');
                    $('#sumbitBtn').html('Update');
                    $('h3').html('Edit inventary');
                    modal.show();
                });


                $('#cancelBtn').click(function() {
                    confirmModal.hide();
                });

                var idToDelete;

                $(document).on('click', 'button[name="delete"]', function(){
                    idToDelete = $(this).attr('id');
                    confirmModal.show();
                });

                $('#okButton').click(function(){
                    var url =  "{{ route('inventary.destroy', ':idToDelete') }}";
                    url = url.replace(':idToDelete', idToDelete);
                    console.log(url);
                    $.ajax({
                        type:'delete',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url:url,
                        beforeSend:function(){
                            $('#okButton').text('Deleting...');
                        },
                        success:function(data)
                        {
                            setTimeout(function(){
                                confirmModal.hide();
                                $('#inventaryDatatable').DataTable().ajax.reload();
                                alert('Inventary deleted');
                            }, 300);
                        },
                        complete: function() {
                            $('#okButton').text('Yes, I am sure');
                        }
                    })
                });

            });
        </script>

</x-admin-layout>
