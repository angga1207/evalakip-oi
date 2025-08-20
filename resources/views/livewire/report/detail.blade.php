<div>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="myDataTable" class="table table-stripped align-middle">
                    <thead>
                        <tr class="align-middle bg-info">
                            <th rowspan="2" class="border text-center text-dark">
                                Komponen
                            </th>
                            <th rowspan="2" class="border text-center text-dark">
                                Bobot Komponen
                            </th>
                            <th colspan="3" class="border text-center text-dark">
                                Bobot Sub Komponen
                            </th>
                            <th rowspan="2" class="border text-center text-dark">
                                Total Nilai
                            </th>
                        </tr>
                        <tr class="align-middle bg-info">
                            <th class="border text-center text-dark">
                                1 (20%) Keberandaan
                            </th>
                            <th class="border text-center text-dark">
                                2 (30%) Kualitas
                            </th>
                            <th class="border text-center text-dark">
                                3 (20%) Pemanfaatan
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($datas as $data)
                        <tr>
                            <td class="border">
                                {{ $data['nama'] }}
                            </td>
                            <td class="border text-center">
                                {{ number_format($data['bobot'],2) }}
                            </td>
                            <td class="border text-center">
                                {{ number_format($data['nilai1'],2) }}
                            </td>
                            <td class="border text-center">
                                {{ number_format($data['nilai2'],2) }}
                            </td>
                            <td class="border text-center">
                                {{ number_format($data['nilai3'],2) }}
                            </td>
                            <td class="border text-center">
                                {{ number_format($data['totalNilai'],2) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                    <tfoot>
                        <tr class="align-middle bg-info">
                            <th class="border text-center text-dark">
                                Nilai Akuntabilitas Kinerja
                            </th>
                            <th class="border text-center text-dark">
                                {{ number_format(collect($datas)->sum('bobot'),2) }}
                            </th>
                            <th class="border text-center text-dark">
                                {{ number_format(collect($datas)->sum('nilai1'),2) }}
                            </th>
                            <th class="border text-center text-dark">
                                {{ number_format(collect($datas)->sum('nilai2'),2) }}
                            </th>
                            <th class="border text-center text-dark">
                                {{ number_format(collect($datas)->sum('nilai3'),2) }}
                            </th>
                            <th class="border text-center text-dark">
                                {{ number_format(collect($datas)->sum('totalNilai'),2) }}
                            </th>
                        </tr>
                    </tfoot>
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

        .dataTables_wrapper .row {
            gap: 10px;
        }
    </style>
    @endpush

    @push('scripts')
    <script src="{{ asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>
    <script>
        $(document).ready(function() {
			var table = $('#myDataTable').DataTable( {
				lengthChange: false,
                searching: false,
                paging: false,
                info: false,
                ordering: false,

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
