<?php

?>
<div>

    <div class="w-100">

        <div class="card">
            <div class="card-body">
                <div wire:ignore class="table-responsive">
                    <table id="myDataTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th width="100">
                                    Grade
                                </th>
                                <th class="text-center" width="100">
                                    Nilai
                                </th>
                                <th class="text-center">
                                    Penjelasan
                                </th>
                                <th class="no-export text-center" width="100">
                                    Opsi
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($datas as $index => $data)
                            <tr wire:key="data-{{ $data->id }}">
                                <td>
                                    <h6 class="mb-0 text-center">
                                        {{ $data['predikat'] }}
                                    </h6>
                                </td>
                                <td class="text-center" style="white-space: nowrap;">
                                    {{ $data->nilai ?? '' }}
                                </td>
                                <td class="text-center" style="white-space: nowrap;">
                                    {{ $data->keterangan ?? '' }}
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('grades.edit', $data->id) }}" class="btn btn-primary btn-sm">
                                            <i class="material-icons-outlined">edit</i>
                                        </a>
                                        <button class="btn btn-danger btn-sm"
                                            wire:click="confirmDelete({{ $data->id }})">
                                            <i class="material-icons-outlined">delete</i>
                                        </button>
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
    </style>
    @endpush

    @push('scripts')
    <script src="assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
    <script src="assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {

			var table = $('#myDataTable').DataTable({
                // order: [[1, 'desc']],
                // ordering false
                ordering: false,
				lengthChange: false,
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
			});

			table.buttons().container()
				.appendTo( '#myDataTable_wrapper .col-md-6:eq(0)' );

        });
    </script>
    @endpush
</div>
