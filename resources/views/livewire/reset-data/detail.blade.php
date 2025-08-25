<?php

use Carbon\Carbon;

?>
<div>
    <div class="card w-100 rounded-4">
        <div class="card-body">
            <h5 class="card-title">
                Reset Data {{ $type == 'penilaian' ? 'Penilaian' : 'Evaluasi' }}
            </h5>
            <p class="card-text">Berikut adalah daftar Instansi yang ingin direset {{ $type == 'penilaian' ?
                'Penilaiannya' : 'Evaluasinya' }}</p>


            <div wire:ignore class="table-responsive">
                <table id="myDataTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th width="10">
                                No
                            </th>
                            <th class="text-center" width="200">
                                Nama Instansi
                            </th>
                            <th class="text-center" width="100">
                                Status Penilaian
                            </th>
                            <th class="no-export text-center" width="50">
                                Opsi
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($datas as $index => $data)
                        @php
                        $penilaian = collect($data->Penilaian())->first();
                        @endphp
                        <tr wire:key="data-{{ $data->id }}">
                            <td>
                                {{ $loop->iteration }}
                            </td>
                            <td>
                                <h6 class="mb-0">
                                    {{ $data->name ?? '' }}
                                </h6>
                                <div class="mb-0">
                                    {{ $data->code ?? '' }}
                                </div>
                            </td>
                            <td class="text-center">
                                @if($penilaian)
                                @if($penilaian['is_submitted'] == true && $penilaian['is_verified'] == false)
                                <span class="badge bg-success">
                                    Sudah Disubmit
                                </span>
                                @elseif($penilaian['is_submitted'] == false && $penilaian['is_verified'] == false)
                                <span class="badge bg-warning text-muted">
                                    Belum Disubmit
                                </span>
                                @elseif($penilaian['is_submitted'] == true && $penilaian['is_verified'] == true)
                                <span class="badge bg-success">
                                    Sudah Diverifikasi
                                </span>
                                @endif
                                @else
                                <span class="badge bg-danger">
                                    Belum Ada Penilaian
                                </span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <button class="btn btn-secondary btn-sm px-2 py-1"
                                        wire:click="confirmReset({{ $data->id }})">
                                        <i class="material-icons-outlined" style="font-size: 12px;">restart_alt</i>
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
                ordering: false,
				lengthChange: false,
                order: [[0, 'desc']],
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
