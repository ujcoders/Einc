<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-medium text-gray-900 leading-tight">
            Client Records
        </h2>
    </x-slot>

    <section class="max-w-7xl mx-auto py-8 px-6 bg-black rounded-2xl shadow-lg overflow-x-auto">
        <!-- Global search input -->
        <div style="margin-bottom:12px;">
            <input type="text" id="global_search" placeholder="Global Search..."
                style="width:300px; padding:6px; border-radius:6px; border:1px solid #555; background:#111; color:#eee;" />
        </div>

        <div class="w-full overflow-x-auto">
            <table id="clientrecords-table" class="display stripe hover" style="width:100%; color:#e0e0e0;">
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
        #clientrecords-table_info{
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
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script> <!-- Added for column visibility -->
    <script src="https://cdn.datatables.net/colreorder/1.6.2/js/dataTables.colReorder.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

    <script>
        $(function() {
            var table = $('#clientrecords-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                scrollX: true,
                ajax: "{{ route('clientrecords.data') }}",
                columns: [
                    @foreach($columns as $column)
                        { data: '{{ $column }}', name: '{{ $column }}' },
                    @endforeach
                ],
                orderCellsTop: true,
                order: [[0, 'desc']],
                colReorder: true,
                lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
                dom: 'lBfrtip',  // <-- length selector added here
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
                    emptyTable: "No client records found"
                },
                initComplete: function () {
                    var api = this.api();

                    // Per-column search
                    api.columns().every(function (i) {
                        var column = this;
                        var input = $('.filters th').eq(i).find('input');
                        input.on('keyup change clear', function () {
                            if (column.search() !== this.value) {
                                column.search(this.value).draw();
                            }
                        });
                    });

                    // Global search external input
                    $('#global_search').on('keyup change clear', function () {
                        if (api.search() !== this.value) {
                            api.search(this.value).draw();
                        }
                    });
                }
            });
        });
    </script>
</x-app-layout>
