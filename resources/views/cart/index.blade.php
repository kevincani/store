<!-- Display Cart Page -->
<x-admin-layout>
    @section('links')
        <script src="{{Vite::asset('resources/assets/jquery.js')}}"></script>
    @endsection
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <div id="productList">

    </div>
    <script>
        // Function to display the cart items for the logged-in user
        $(document).ready(function(){
            // Check if the user is logged in
            const user = JSON.parse(localStorage.getItem('user'));
            console.log(user);
            if (!user) {
                alert('Please log in to view your cart.');
                return;
            }

            // Get the cart data from local storage
            const cart = JSON.parse(localStorage.getItem('cart')) || [];

            // Filter cart items for the logged-in user
            const userCart = cart.filter(item => item.user_id === user.id);
            console.log('User Cart:', userCart);
            var requestData = {};
            var quantityData = {};

            $.each(userCart, function(index, cartItem) {
                requestData[index] = cartItem.id;
                quantityData[index] = cartItem.quantity;
            });

            var postData = {
                requestData: requestData,
                quantityData: quantityData,
            };

            $.ajax({
                type: 'POST', // Adjust the method based on your server's requirements
                url: '{{ route('cart.show') }}',
                data: postData,
                // dataType: 'json', // Change this based on your server response type
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    // Handle the server response if needed
                    console.log('Data sent successfully');
                    $('#productList').html(response);
                },
                error: function(xhr, status, error) {
                    // Handle the error if the request fails
                    console.error('Error sending data:', error);
                }
            });
        });

    </script>



</x-admin-layout>
