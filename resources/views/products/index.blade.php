<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-lg font-medium text-gray-900 leading-tight">
                Products
            </h2>
            <a href="{{ route('products.create') }}"
                class="text-lg font-medium text-gray-900 leading-tight">
                Add New Product
            </a>
        </div>
    </x-slot>



    <section class="max-w-7xl mx-auto py-8 px-6 bg-black rounded-2xl shadow-lg overflow-x-auto">
        <!-- Global search input -->
        <div style="margin-bottom:12px;">
            <input type="text" id="global_search" placeholder="Global Search..."
                style="width:300px; padding:6px; border-radius:6px; border:1px solid #555; background:#111; color:#eee;" />
        </div>

        <div class="w-full overflow-x-auto">
            <table id="products-table" class="display stripe hover" style="width:100%; color:#e0e0e0;">
                <thead class="bg-gray-900 text-gray-100">
                    <tr>
                        @foreach($columns as $column)
                        <th>{{ $column }}</th>
                        @endforeach
                    </tr>
                    <tr class="filters">
                        @foreach($columns as $column)
                        <th>
                            <input type="text" placeholder="Search {{ $column }}"
                                style="width:100%; padding:3px; background:#111; color:#eee; border:none; border-radius:4px;" />
                        </th>
                        @endforeach
                    </tr>
                </thead>
            </table>
        </div>
    </section>

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/colreorder/1.6.2/css/colReorder.dataTables.min.css" rel="stylesheet" />

    <style>
        table.dataTable {
            background-color: #121212 !important;
            border-color: #333 !important;
        }

        table.dataTable thead th {
            background-color: #222 !important;
            color: #e0e0e0 !important;
        }

        table.dataTable tbody tr:hover {
            background-color: #333 !important;
            background-color: #2563eb !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            background-color: #333 !important;
            color: #e0e0e0 !important;
            border: none !important;
            margin: 2px;
            border-radius: 4px;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background-color: #2563eb !important;
            color: white !important;
        }

        /* Buttons styling */
        .dt-button {
            background: #2563eb !important;
            border: none !important;
            border-radius: 4px !important;
            color: white !important;
            padding: 6px 12px !important;
            margin-right: 6px !important;
        }

        /* Inputs in header */
        thead input {
            background: #111 !important;
            color: #eee !important;
            border: none !important;
            border-radius: 4px !important;
            padding: 3px !important;
        }
        #products-table_info{
             /* background-color: #2563eb !important; */
            color: white !important;
        }
    </style>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.datatables.net/colreorder/1.6.2/js/dataTables.colReorder.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

    <script>
        $(function() {
    var table = $('#products-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        scrollX: true,
        ajax: "{{ route('products.data') }}",
        columns: [
            @foreach($columns as $column)
                @if($column === 'image')
                    {
                        data: '{{ $column }}',
                        name: '{{ $column }}',
                        render: function(data, type, row, meta) {
                            if (data) {
                                return '<img src="' + data + '" alt="Product Image" style="height:40px; width:auto; border-radius:4px;" />';
                            } else {
                                return '';
                            }
                        }
                    },
                @else
                    { data: '{{ $column }}', name: '{{ $column }}' },
                @endif
            @endforeach
        ],
        orderCellsTop: true,
        order: [[0, 'desc']],
        colReorder: true,
        lengthMenu: [ [10, 25, 50, 100], [10, 25, 50, 100] ],
        dom: 'Bfrtip',
                buttons: [
                            {
                                extend: 'copyHtml5',
                                exportOptions: {
                                    columns: ':visible'
                                }
                            },
                            {
                                extend: 'excelHtml5',
                                exportOptions: {
                                    columns: ':visible'
                                }
                            },
                            {
                                extend: 'csvHtml5',
                                exportOptions: {
                                    columns: ':visible'
                                }
                            },
                            {
                                extend: 'pdfHtml5',
                                exportOptions: {
                                    columns: ':visible'
                                }
                            },
                            {
                                extend: 'print',
                                exportOptions: {
                                    columns: ':visible'
                                }
                            },
                            'colvis'
                        ],
        language: {
            emptyTable: "No products found"
        },

        // ✅ Add row ID attribute
        rowCallback: function(row, data) {
            $(row).attr('data-id', data.id); // assuming 'id' is part of the data
            $(row).css('cursor', 'pointer');
        },

        initComplete: function () {
            var api = this.api();

            api.columns().every(function (i) {
                var column = this;
                var input = $('.filters th').eq(i).find('input');
                input.on('keyup change clear', function () {
                    if (column.search() !== this.value) {
                        column.search(this.value).draw();
                    }
                });
            });

            $('#global_search').on('keyup change clear', function () {
                if (api.search() !== this.value) {
                    api.search(this.value).draw();
                }
            });
        }
    });

    // ✅ Row click to redirect
    $('#products-table tbody').on('click', 'tr', function() {
        var id = $(this).data('id');
        if (id) {
            window.location.href = `/products/${id}/dashboard`;
        }
    });
});

    </script>
</x-app-layout>
