<x-backoffice.layout.app-layout title="Tambah Partai">
    @push('css')
        <style>
            thead {
                background: #f5f4f8;
                text-align: center;
            }
        </style>
    @endpush
    <x-backoffice.toolbar heading="Tambah Partai" subheading="" breadcrumb="master-partai-create" icon="fas fa-users">
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
                                    <form id="form" action="{{ route('master.partai.store') }}" method="post">
                                        @csrf
                                        <div class="table-responsive">
                                            <table class="table table-borderless w-100" id="dynamic_field">
                                                <thead>
                                                    <tr class="fw-bold fs-6 text-gray-800">
                                                        <th width="40%">Nama</th>
                                                        <th width="20%">Tanggal Berdiri</th>
                                                        <th width="35%">Ketua Umum</th>
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
            $('#add').click(function(){  
                i++;
                row = "<tr>"
                row += "<td><input type='text' class='form-control form-control-solid' name='partai[" +i+ "][nama]' id='nama' placeholder='ex: Partai Berdikari' required/></td>";
                row += "<td><input type='text' class='form-control form-control-solid' name='partai[" +i+ "][tanggal_berdiri]' id='tanggal_berdiri"+i+"' placeholder='ex: 1990-02-10' required/></td>";
                row += "<td><input type='text' class='form-control form-control-solid' name='partai[" +i+ "][ketua_umum]' id='ketua_umum' placeholder='ex: Mr. X' required/></td>";
                row += "<td><button type='button' id='del-row' class='btn btn-danger'><i class='fas fa-trash text-white'></i></button></td>";
                row += "</tr>"

                tableBody = $("table tbody");
                tableBody.append(row);

                prompDatepicker(`#tanggal_berdiri${i}`);
            });

            $('#dynamic_field').on('click', '#del-row', function() {
                var n = $(this).closest('tr').index();
                $('#dynamic_field >tbody tr:eq('+n+')').remove();
            });
        });

        function prompDatepicker(id) {
            $(id).daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                autoClose: true,
                locale: {
                    format: 'YYYY-MM-DD'
                }
            });
        }
    </script>
    @endpush
</x-backoffice.layout.app-layout>