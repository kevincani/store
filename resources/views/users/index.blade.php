<x-admin-layout>
@section('links')
        <link rel="stylesheet" href="{{Vite::asset('resources/assets/datatables.css')}}">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

        <script src="{{Vite::asset('resources/assets/jquery.js')}}"></script>
        <script src="{{Vite::asset('resources/assets/jquery-validate.js')}}"></script>

    @endsection
    <div align="right">
        <button type="button" name="addUser" id="addUser" class="focus:outline-none text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900">Add User</button>
    </div>
    <table id="usersDatatable" class="display" style="width:100%">
        <thead>
        <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Action</th>
        </tr>
        </thead>
    </table>



    <div id="userModal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
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
{{--                    <div id="successAlert" class="hidden mt-4 px-4 py-3 leading-normal text-green-700 bg-green-100 rounded-lg">--}}
{{--                        User created successfully.--}}
{{--                    </div>--}}

                    <form class="space-y-6" id="editModal" method="post" >
                        <!-- Username -->
                        <div>
                            <x-input-label for="username" :value="__('Username')" />
                            <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')" required autofocus autocomplete="username" />
                            <x-input-error :messages="$errors->get('username')" class="mt-2" />
                        </div>

                        <!-- First Name -->
                        <div>
                            <x-input-label for="first_name" :value="__('First Name')" />
                            <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name')" required autofocus autocomplete="first_name" />
                            <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                        </div>

                        <!-- Last Name -->
                        <div>
                            <x-input-label for="last_name" :value="__('Last Name')" />
                            <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name')" required autofocus autocomplete="last_name" />
                            <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                        </div>

                        <!-- Email Address -->
                        <div class="mt-4">
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div class="mt-4" x-show="action === 'Add'">
                            <x-input-label id="passwordLabel" for="password" :value="__('Password')" />

                            <x-text-input id="password" class="block mt-1 w-full"
                                          type="password"
                                          name="password"
                                          required autocomplete="new-password" />

                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Confirm Password -->
                        <div class="mt-4">
                            <x-input-label id="password_confirmationLabel" for="password_confirmation" :value="__('Confirm Password')" />

                            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                          type="password"
                                          name="password_confirmation" required autocomplete="new-password" />

                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <!-- Address -->
                        <div class="mt-4">
                            <x-input-label for="address" :value="__('Address')" />
                            <x-text-input id="address" class="block mt-1 w-full" type="text" name="address" :value="old('address')" required autocomplete="address" />
                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>

                        <!-- Phone -->
                        <div class="mt-4">
                            <x-input-label for="phone" :value="__('Phone Number')" />
                            <x-text-input id="phone" class="block mt-1 w-full" type="number" name="phone" :value="old('phone')" required autocomplete="phone" />
                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                        </div>

                        <input type="hidden" name="action" id="action" value="Add" />
                        <input type="hidden" name="hiddenId" id="hiddenId" />

                </div>
                <!-- Modal footer -->
                <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                    <button type="submit" name="actionButton" id="actionButton" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Add User</button>
                    <button type="button" id="closeBtn" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600">Decline</button>
                </div>
                </form>
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







    {{--    <script>--}}
{{--        $(document).ready(function() {--}}
{{--            // Add validation rules and messages--}}
{{--            $("#editModal").validate({--}}
{{--                rules: {--}}
{{--                    username: {--}}
{{--                        required: true,--}}
{{--                    },--}}
{{--                    first_name: {--}}
{{--                        required: true,--}}
{{--                    },--}}
{{--                    last_name: {--}}
{{--                        required: true,--}}
{{--                    },--}}
{{--                    email: {--}}
{{--                        required: true,--}}
{{--                        email: true,--}}
{{--                    },--}}
{{--                    password: {--}}
{{--                        required: true,--}}
{{--                        minlength: 6,--}}
{{--                    },--}}
{{--                    password_confirmation: {--}}
{{--                        required: true,--}}
{{--                        minlength: 6,--}}
{{--                        equalTo: "#password",--}}
{{--                    },--}}
{{--                    address: {--}}
{{--                        required: true,--}}
{{--                    },--}}
{{--                    phone: {--}}
{{--                        required: true,--}}
{{--                        digits: true,--}}
{{--                    },--}}
{{--                },--}}
{{--                messages: {--}}
{{--                    // Custom error messages for each field--}}
{{--                    username: {--}}
{{--                        required: "Please enter a username.",--}}
{{--                    },--}}
{{--                    first_name: {--}}
{{--                        required: "Please enter a first name.",--}}
{{--                    },--}}
{{--                    last_name: {--}}
{{--                        required: "Please enter a last name.",--}}
{{--                    },--}}
{{--                    email: {--}}
{{--                        required: "Please enter an email address.",--}}
{{--                        email: "Please enter a valid email address.",--}}
{{--                    },--}}
{{--                    password: {--}}
{{--                        required: "Please enter a password.",--}}
{{--                        minlength: "Password must be at least 6 characters long.",--}}
{{--                    },--}}
{{--                    password_confirmation: {--}}
{{--                        required: "Please confirm the password.",--}}
{{--                        minlength: "Password must be at least 6 characters long.",--}}
{{--                        equalTo: "Passwords do not match.",--}}
{{--                    },--}}
{{--                    address: {--}}
{{--                        required: "Please enter an address.",--}}
{{--                    },--}}
{{--                    phone: {--}}
{{--                        required: "Please enter a phone number.",--}}
{{--                        digits: "Please enter a valid phone number.",--}}
{{--                    },--}}
{{--                },--}}
{{--                submitHandler: function(form) {--}}
{{--                    // Callback function to handle form submission--}}
{{--                    // Add your custom logic here--}}
{{--                    form.submit();--}}
{{--                }--}}
{{--            });--}}
{{--        });--}}
{{--    </script>--}}


    <script type="module">
        $(document).ready(function() {
            var datatable = $('#usersDatatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('users.index') }}",
                columns: [
                    { data: 'first_name', name: 'first_name' },
                    { data: 'last_name', name: 'last_name' },
                    { data: 'email', name: 'email' },
                    { data: 'role', name: 'role' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ]
            });


            const $targetEl = document.getElementById('userModal');
            const options = {

                closable: true,
                onHide: () => {
                    console.log('modal is hidden');
                    // if ($('#userModal').hasClass("hidden")) {
                    //     // if (!$('[modal-backdrop]') === null) {
                    //     //     $('[modal-backdrop]').remove();
                    //     // }
                    //     if (!$('.edit').hasClass('hidden')) {
                    //         $('.edit').addClass('hidden');
                    //     }
                    //     if ($('.add').hasClass('hidden')) {
                    //         $('.add').removeClass('hidden');
                    //     }
                    //     if (!$('#alert-3').hasClass('hidden')) {
                    //         $('#alert-3').addClass('hidden');
                    //     }
                    //     if ($('.add').attr('disabled')) {
                    //         $('.add').removeAttr('disabled', 'disabled');
                    //     }
                    //     if ($('.edit').attr('disabled')) {
                    //         $('.edit').removeAttr('disabled', 'disabled');
                    //     }
                    //
                    //     $('#form')[0].reset();
                    //     $('h3').html('Add user');
                    // }
                },
                onShow: () => {
                    console.log('modal is shown');
                },
                onToggle: () => {
                    console.log('modal has been toggled');
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




            $('#addUser').click(function(){
                // $('.modal-title').text('Add New Record');
                $('h3').html('Add User');
                $('#actionButton').val('Add');
                $('#actionButton').html('Add');
                $('#action').val('Add');
                $('#form_result').html('');

                modal.show();
            });
            // Event listener for close button
            $('#closeBtn').click(function() {
                modal.hide();
            });
            $('#editModal').on('submit', function(event){
                event.preventDefault();


                var actionUrl = '';

                if($('#action').val() == 'Add')
                {
                    actionUrl = "{{ route('users.store') }}";
                }

                if($('#action').val() == 'Edit')
                {
                    actionUrl = "{{ route('users.update') }}";
                }
                console.log(actionUrl);
                $.ajax({
                    type: 'post',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: actionUrl,
                    data: $(this).serialize() + "&_token={{ csrf_token() }}",
                    dataType: 'json',
                    success: function(data) {
                        console.log('success: '+data);
                        // $('#successAlert').removeClass('hidden');
                        $('#editModal').trigger('reset');
                        $('#usersDatatable').DataTable().ajax.reload();
                        modal.hide();
                    },
                    error: function(response) {
                        // var errors = data.responseJSON;
                        // console.log(errors);

                        if(response.status==422){
                            validator.showErrors(response.responseJSON.errors);
                        }else{
                            alert(response)
                        }
                    }
                });

            });




            $('#usersDatatable').on('click', 'button[name="edit"]', function() {
                var row = $(this).closest('tr');
                var data = datatable.row(row).data();
                // Perform your edit logic here using the data retrieved from the clicked row
                console.log('Edit button clicked:', data);
                event.preventDefault();
                var id = $(this).attr('id');
                $.ajax({
                    url :"/users/edit/"+id+"/",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    dataType:"json",
                    success:function(data)
                    {
                        console.log('success: '+data);
                        $('#username').val(data.result.username);
                        $('#first_name').val(data.result.first_name);
                        $('#last_name').val(data.result.last_name);
                        $('#email').val(data.result.email);
                        $('#address').val(data.result.address);
                        $('#phone').val(data.result.phone);
                        $('#hiddenId').val(id);
                        // $('.modal-title').text('Edit Record');
                        $('#actionButton').val('Update');
                        $('#actionButton').html('Update');
                        $('#action').val('Edit');
                        $('#password').hide();
                        $('#passwordLabel').hide();
                        $('#password_confirmation').hide();
                        $('#password_confirmationLabel').hide();
                        $('#password').removeAttr('required');
                        $('#password_confirmation').removeAttr('required');
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
                    url:"users/destroy/"+userId,
                    beforeSend:function(){
                        $('#okButton').text('Deleting...');
                    },
                    success:function(data)
                    {
                        setTimeout(function(){
                            confirmModal.hide();
                            $('#usersDatatable').DataTable().ajax.reload();
                            alert('User Deleted');
                        }, 2000);
                    }

                })
            });

        });


    </script>



</x-admin-layout>
