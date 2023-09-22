<x-admin-layout>
    @section('links')
        <link rel="stylesheet" href="{{Vite::asset('resources/assets/datatables.css')}}">
        <script src="{{Vite::asset('resources/assets/jquery.js')}}"></script>
    @endsection

    <table class="table" id="raportTable">
        <thead>
        <tr>
            <th>Product Name</th>
            <th>Product Size</th>
            <th>Product Color</th>
            <th>Product Category</th>
            <th>Quantity</th>
        </tr>
        </thead>
    </table>

    <script>
        $(document).ready(function (){
            $('#raportTable').dataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.raport') }}",
                columns: [
                    { data : 'productName', name: 'productName' },
                    { data: 'productSize', name: 'productSize' },
                    { data: 'productColor', name: 'productColor' },
                    { data: 'productCategory', name: 'productCategory' },
                    { data: 'quantity', name: 'quantity' }
                ]
            });
        });
    </script>

</x-admin-layout>
