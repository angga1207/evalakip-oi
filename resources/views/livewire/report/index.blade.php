<?php

?>
<div>

    <div class="w-100">

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="myDataTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center" width="65">
                                    #
                                </th>
                                <th>
                                    Nama Instansi
                                </th>
                                <th class="text-center" width="100">
                                    Skor
                                </th>
                                <th class="text-center" width="100">
                                    Grade
                                </th>
                                <th class="no-export text-center" width="100">
                                    Opsi
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($datas as $index => $data)
                            <tr wire:key="data-{{ $data->id }}">
                                <td class="text-center">
                                    {{ $loop->iteration }}
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <img src="{{ asset($data['logo']) }}" width="40" height="40"
                                            class="rounded-circle raised bg-white p-1" alt="">
                                        <div class="flex-grow-1 ms-2">
                                            <h6 class="mb-0">
                                                {{ $data['name'] }}
                                            </h6>
                                            <p class="mb-0 text-muted">
                                                {{ $data['code'] }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center" style="white-space: nowrap;">
                                    {{ number_format($data->GetSkor(), 2) ?? '0' }}
                                </td>
                                <td class="text-center" style="white-space: nowrap;">
                                    {{ $data->GetGrade() }}
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('report.detail', $data->id) }}"
                                            class="btn btn-primary btn-sm d-flex align-items-center">
                                            <i class="material-icons-outlined">visibility</i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .dataTables_filter {
            float: right;
            margin-bottom: 10px;
        }

        .dt-buttons.btn-group {
            gap: 4px;
        }

        .dt-buttons.btn-group button {
            border: none !important
        }

        .dt-buttons.btn-group button:hover {
            background: transparent !important;
        }

        .dataTables_wrapper .row {
            gap: 10px;
        }

        .table th {
            cursor: pointer;
        }

        .table th.sorting::after {
            font-family: "Material Icons Outlined";
            content: "\e8d5";
            font-weight: 900;
            float: right;
            margin-left: 5px;
        }

        .table th.sorting_asc::after {
            content: "\e5db";
            font-family: "Material Icons Outlined";
            font-weight: 900;
            float: right;
            margin-left: 5px;
        }

        .table th.sorting_desc::after {
            content: "\e5db";
            font-family: "Material Icons Outlined";
            font-weight: 900;
            float: right;
            margin-left: 5px;
            rotate: 180deg;
        }
    </style>
    @endpush

    @push('scripts')
    <script src="assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
    <script src="assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
			var table = $('#myDataTable').DataTable( {
				lengthChange: false,
                searching: false,
                ordering: true,
                columnDefs: [
                    { orderable: false, targets: [4] },
                ],
                order: [[2, 'desc']],
				buttons: [
                    {
                        extend: 'copyHtml5',
                        text: '<div class="wh-42 d-flex align-items-center justify-content-center rounded-3 bg-grd-primary"><i class="material-icons-outlined text-white">content_copy</i></div>',
                        titleAttr: 'Copy',
                        className: 'p-0',
                        exportOptions: {
                            columns: ':not(.no-export)'
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<div class="wh-42 d-flex align-items-center justify-content-center rounded-3 bg-grd-success"><i class="material-icons-outlined text-white">file_download</i></div>',
                        titleAttr: 'Export to Excel',
                        className: 'p-0',
                        exportOptions: {
                            columns: ':not(.no-export)'
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<div class="wh-42 d-flex align-items-center justify-content-center rounded-3 bg-grd-branding"><i class="material-icons-outlined text-white">picture_as_pdf</i></div>',
                        titleAttr: 'Export to PDF',
                        className: 'p-0',
                        exportOptions: {
                            columns: ':not(.no-export)'
                        }
                    },
                    {
                        extend: 'print',
                        text: '<div class="wh-42 d-flex align-items-center justify-content-center rounded-3 bg-grd-warning"><i class="material-icons-outlined text-white">print</i></div>',
                        titleAttr: 'Print',
                        className: 'p-0',
                        exportOptions: {
                            columns: ':not(.no-export)'
                        }
                    }
                ],
			} );

			table.buttons().container()
				.appendTo( '#myDataTable_wrapper .col-md-6:eq(0)' );
		});
    </script>
    @endpush
</div>
