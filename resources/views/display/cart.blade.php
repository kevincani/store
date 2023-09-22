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
            var productData = {};

            $.each(userCart, function(index, cartItem) {
                requestData[index] = cartItem.id;
                quantityData[index] = cartItem.quantity;
                productData[index] = cartItem.productId;
            });

            var postData = {
                requestData: requestData,
                quantityData: quantityData,
                productData: productData
            };

            $.ajax({
                type: 'POST', // Adjust the method based on your server's requirements
                url: '{{ route('products.cartDisplay') }}',
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



            // userCart.forEach(item => {
            //
            // });


        // Call the displayCart function to show the cart items on page load
        // displayCart();
    </script>


{{--    const cartContainer = document.getElementById('cartContainer');--}}
{{--    // Clear existing cart items--}}
{{--    cartContainer.innerHTML = '';--}}

{{--    // Iterate over the user's cart data and render cart items--}}
{{--    userCart.forEach(item => {--}}
{{--    // Clone the cart item template--}}
{{--    const cartItemTemplate = document.getElementById('cartItemTemplate').cloneNode(true);--}}
{{--    cartItemTemplate.classList.remove('hidden');--}}

{{--    // Update the cart item data--}}
{{--    cartItemTemplate.querySelector('.product-name').textContent = item.product.name;--}}
{{--    cartItemTemplate.querySelector('.product-price').textContent = item.product.pivots[0].pivot.price;--}}
{{--    cartItemTemplate.querySelector('img').src = "{{ asset('storage') . '/' }}" + item.product.images[0].name; // Prepend the correct URL path--}}
{{--    // Append the cart item to the container--}}
{{--    cartContainer.appendChild(cartItemTemplate);--}}
{{--    });--}}

</x-admin-layout>
