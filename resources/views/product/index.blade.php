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
        <table id="productDatatable">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>



        <!-- Main modal -->
        <div id="productModal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative w-full max-w-md max-h-full">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="productModal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                    <div class="px-6 py-6 lg:px-8">

                        <h3 class="mb-4 text-xl font-medium text-gray-900 dark:text-white">Add Product</h3>

                                <form id="productForm" class="space-y-6" action="">
                                    <div id="formContent">
                                    <div>
                                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Product Name</label>
                                        <input type="text" id="name" name="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                                    </div>
                                    <div>
                                        <label for="desc" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
                                        <textarea id="desc" name="desc" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 h-24 resize-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required></textarea>
                                    </div>
                                    </div>
                                    <div id="foto">
                                        <label for="image" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Image</label>
                                        <input type="file" id="images" name="images[]" multiple>
                                    </div>
                                    <div>
                                        <input type="hidden" name="action" id="action" value="Add" />
                                    </div>
                                    <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                                        <button type="submit" id="submitBtn" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
                                        <button type="button" id="closeBtn" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600">Decline</button>
                                    </div>
                                </form>

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
                        <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Are you sure you want to delete this user?</h3>
                        <button data-modal-hide="confirmModal" id="okButton" type="button" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                            Yes, I'm sure
                        </button>
                        <button data-modal-hide="confirmModal" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">No, cancel</button>
                    </div>
                </div>
            </div>
        </div>



        <script>
            $(document).ready(function (){
                var datatable = $('#productDatatable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('product.datatable') }}",
                    columns: [
                        { data: 'name', name: 'name' },
                        { data: 'desc', name: 'desc' },
                        { data: 'action', name: 'action', orderable: false, searchable: false },
                    ]
                });

                // $('h3').html('test');
                // $('#submitBtn').html('test');

                const $targetEl = document.getElementById('productModal');
                const options = {
                    backdrop: 'dynamic',
                    backdropClasses: 'bg-gray-900 bg-opacity-50 dark:bg-opacity-80 fixed inset-0 z-40',
                    closable: true,
                    onHide: () => {
                        console.log('modal is hidden');
                    },
                    onShow: () => {
                        console.log('modal is shown');
                    },
                    onToggle: () => {
                        console.log('modal has been toggled');
                    }
                };

                const modal = new Modal($targetEl, options);


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

                $('#addProduct').click(function (){
                    $('h3').html('Add Product');
                    $('#submitBtn').html('Submit');
                    $('#submitBtn').val('Submit');
                    actionUrl = "{{ route('product.store') }}";
                    $('#action').val('Add');
                    $('#productForm').trigger('reset');
                    $('#foto').show();
                    // $('#foto').attr('src', '').addClass('hidden');
                    modal.show();
                })

                $('#closeBtn').click(function() {
                    modal.hide();
                });

                $('#productForm').on('submit',function (){
                    event.preventDefault();

                    console.log(this);
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
                            // $('#successAlert').removeClass('hidden');
                            $('#productForm').trigger('reset');
                            $('#productDatatable').DataTable().ajax.reload();
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

                $('#productDatatable').on('click', 'button[name="edit"]', function() {
                    var row = $(this).closest('tr');
                    var data = datatable.row(row).data();

                    $.each(data, function (index, value) {
                        $('#formContent #' + index).val(value).trigger('change')
                    })

                    var productId = data.id;
                    actionUrl = "{{ route('product.update', ':productId') }}";
                    actionUrl = actionUrl.replace(':productId', productId);


                    $('#foto').hide();
                    $('#action').val('Edit');
                    $('#actionButton').val('Update');
                    $('#actionButton').html('Update');
                    $('h3').html('Edit User');
                    modal.show();
                });


                var idToDelete;

                $(document).on('click', 'button[name="delete"]', function(){
                    idToDelete = $(this).attr('id');
                    confirmModal.show();
                });

                $('#okButton').click(function(){
                    var url =  "{{ route('product.destroy', ':idToDelete') }}";
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
                                $('#productDatatable').DataTable().ajax.reload();
                                alert('Product Deleted');
                            }, 300);
                        },
                        complete: function() {
                            $('#okButton').text('Yes, I am sure');
                        }

                    })
                });

                // $('#images').change(function() {
                //     var imagePreview = $('#imagePreview');
                //     imagePreview.html('');
                //
                //     var files = this.files;
                //     for (var i = 0; i < files.length; i++) {
                //         var file = files[i];
                //         var reader = new FileReader();
                //
                //         reader.onload = function(e) {
                //             var img = $('<img>').attr('src', e.target.result);
                //             img.addClass('h-36 w-36 rounded-md object-cover max-w-lg mx-auto mb-4');
                //             imagePreview.append(img);
                //         }
                //
                //         reader.readAsDataURL(file);
                //     }
                // });

            });
        </script>

</x-admin-layout>
