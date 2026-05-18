<x-backoffice.layout.app-layout title="Tambah Jenis Pemilihan">
    @push('css')
        <style>
            thead {
                background: #f5f4f8;
                text-align: center;
            }
        </style>
    @endpush
    <x-backoffice.toolbar heading="Tambah Jenis Pemilihan" subheading="" breadcrumb="master-jenis-pemilihan-create" icon="fas fa-users">
        <div class="d-flex align-items-center w-25">
            <x-backoffice.notification/>
        </div>
    </x-backoffice.toolbar>

    <div class="app-container container-xxl">
        <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
            <div class="d-flex flex-column flex-column-fluid">
                <div id="kt_app_content" class="app-content flex-column-fluid">
                    <div class="row g-5 g-xl-8">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header d-flex align-items-center justify-content-between">
                                    <h4></h4>
                                    <div class="card-header-action">
                                        <button type="button" name="add" id="add" class="btn btn-primary btn-sm">Tambah Form</button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <form id="form" action="{{ route('master.jenis-pemilihan.store') }}" method="post">
                                        @csrf
                                        <div class="table-responsive">
                                            <table class="table table-borderless w-100" id="dynamic_field">
                                                <thead>
                                                    <tr class="fw-bold fs-6 text-gray-800">
                                                        <th width="20%">Kategori</th>
                                                        <th width="35%">Kode</th>
                                                        <th width="40%">Nama</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                        <button class="btn btn-primary waves-effect waves-classic waves-effect waves-classic" type="submit">Simpan</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @push('js')
    <script src="{{ asset('vendor/validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('vendor/validation/messages_id.js') }}"></script>
    <script src="{{ asset('vendor/validation/form-validation.js') }}"></script>
    <script>
        $(document).ready(function(){  
            var i=1;
            let optKategoriPemilu = @json(optKategoriPemilu());
            $('#add').click(function(){  
                i++;
                row = "<tr>"
                row += "<td><select class='form-select form-select-solid' name='data[" +i+ "][kategori]' id='kategori"+i+"' required>"+optKategoriPemilu+"</select></td>";
                row += "<td><input type='text' class='form-control form-control-solid' name='data[" +i+ "][kode]' id='kode"+i+"' required/></td>";
                row += "<td><input type='text' class='form-control form-control-solid' name='data[" +i+ "][nama]' id='nama"+i+"' required/></td>";
                row += "<td><button type='button' id='del-row' class='btn btn-danger btn-sm'><i class='fas fa-trash text-white'></i></button></td>";
                row += "</tr>"

                tableBody = $("table tbody");
                tableBody.append(row);

                $(`#kategori${i}`).select2({
                    placeholder: 'Silahkan pilih'
                });
            });

            $('#dynamic_field').on('click', '#del-row', function() {
                var n = $(this).closest('tr').index();
                $('#dynamic_field >tbody tr:eq('+n+')').remove();
            });
        });
    </script>
    @endpush
</x-backoffice.layout.app-layout>