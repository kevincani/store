<x-admin-layout>
    @section('links')
        <link rel="stylesheet" href="{{Vite::asset('resources/assets/datatables.css')}}">
        <script src="{{Vite::asset('resources/assets/jquery.js')}}"></script>
    @endsection
        <h1 class="mb-4 text-3xl font-extrabold leading-none tracking-tight text-gray-900 md:text-4xl lg:text-5xl dark:text-white">Your Orders</h1>
    <table id="ordersTable" class="table">
        <thead>
        <tr>
            <th>Products</th>
            <th>Date</th>
            <th>Total Price</th>
            <th>Status</th>
            <th></th>
        </tr>
        </thead>
    </table>

    <script>
        $(function () {
            var ordersTable = $('#ordersTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('user.purchased') }}",
                columns: [
                    {
                        className: 'dt-control',
                        orderable: false,
                        data: null,
                        defaultContent: ''
                    },
                    {data: 'date', name: 'date'},
                    {data: 'totalPrice', name: 'totalPrice'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false },
                ]
            });

            $('#ordersTable tbody').on('click', 'td.dt-control', function () {
                var tr = $(this).closest('tr');
                var row = ordersTable.row(tr);
                var tableId = 'childTable-' + row.data().id;

                if (row.child.isShown()) {
                    row.child.hide();
                    tr.removeClass('shown');
                } else {
                    row.child(format(row.data(), tableId)).show();
                    tr.addClass('shown');

                    $('#' + tableId).DataTable();
                }
            });

            function format(d, tableId) {
                var table = '<table id="' + tableId + '" >' +
                    '<thead>' +
                    '<tr>' +
                    '<th>Name</th>' +
                    '<th>Color</th>' +
                    '<th>Category</th>' +
                    '<th>Discount</th>' +
                    '<th>Size</th>' +
                    '<th>Quantity</th>' +
                    '<th>Price</th>' +
                    '</tr>' +
                    '</thead><tbody>';

                // Loop through orderDetails and add rows
                for (var i = 0; i < d.order_details.length; i++) {
                    table += '<tr>' +
                        '<td>' + d.order_details[i].product.name + '</td>' +
                        '<td>' + d.order_details[i].product.colors[0].color + '</td>' +
                        '<td>' + d.order_details[i].product.categories[0].category + '</td>' +
                        '<td>' + d.order_details[i].product.discounts[0].discount_percent + '%'  + '</td>' +
                        '<td>' + d.order_details[i].product.sizes[0].size + '</td>' +
                        '<td>' + d.order_details[i].quantity + '</td>' +
                        '<td>' + d.order_details[i].product.inventary[0].price + '</td>' +
                        '</tr>';
                }

                table += '</tbody></table>';
                return table;
            }

            $('#ordersTable').on('click', 'button[name="refund"]', function(){
                var id = $(this).attr('id');
                $.ajax({
                    url : '{{ route('user.refund') }}',
                    // headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    datatype : "json",
                    data: {id: id},

                    success:function (data){
                        $('#ordersTable').DataTable().ajax.reload();
                        alert(data['message']);
                    },
                    error:function (data){
                        var errors = data.responseJSON;
                        console.log(errors);
                    }
                })
            });

        });
    </script>
</x-admin-layout>
