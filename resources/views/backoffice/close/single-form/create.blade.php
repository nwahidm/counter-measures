<x-backoffice.layout.app-layout title="Tambah Single Form Close Case">
    @push('css')
        <style>
            thead {
                background: #f5f4f8;
                text-align: center;
            }
        </style>
    @endpush
    <x-backoffice.toolbar heading="Tambah Single Form Close Case" subheading="" breadcrumb="close-case-create" icon="fas fa-users">
        <div class="d-flex align-items-center w-25">
            <x-backoffice.notification/>
        </div>
    </x-backoffice.toolbar>

    <div class="app-container container-xxl">
        <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
            <div class="d-flex flex-column flex-column-fluid">
                <div id="kt_app_content" class="app-content flex-column-fluid">
                    <div class="row g-5 g-xl-8">
                        <div class="col-md-12" id="formCheck">
                            <div class="card">
                                <div class="card-body">
                                    <form id="form" action="{{ route('close.singleform.single-form.store') }}" method="post" enctype="multipart/form-data" autocomplete="off">
                                        @csrf
                                        <div class="card ">
                                            <div class="card-body">
                                                <label class="fs-3 fw-bold">DETAIL KASUS:</label> <hr>
                                                <div class="row">
                                                    <div class="form-group col-md-6">
                                                        <label for="case_name" class="fs-6 fw-semibold mb-2 required">Nama Kasus</label>
                                                        <input type="text" class="form-control" name="case_name" id="case_name" placeholder="" value="{{old('case_name')}}">
                                                        <p class="text-danger">{{ $errors->first('case_name') }}</p>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="case_date" class="fs-6 fw-semibold mb-2 required">Tanggal Kasus</label>
                                                        <input type="date" class="form-control" name="case_date" id="case_date" value="{{old('case_date') ?? \Carbon\Carbon::now('Asia/Jakarta')->toDateString()}}"required="required">
                                                        <p class="text-danger">{{ $errors->first('case_date') }}</p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label for="case_description" class="fs-6 fw-semibold mb-2">Deksripsi Kasus</label>
                                                        <textarea class="form-control " name="case_description" id="case_description" type="text" rows='4'
                                                            placeholder="Lengkapi Data...">{{old('case_description')}}</textarea>
                                                        <p class="text-danger">{{ $errors->first('case_description') }}</p>
                                                    </div>
                                                </div>
                                                <div class="row mb-7">
                                                    <div class="form-group col-md-12">
                                                        <label for="satker_id" class="fs-6 fw-semibold mb-2 required">Satuan
                                                                    Kerja</label>
                                                        <select
                                                            class="form-select form-select-solid select @error('satker_id') is-invalid @enderror"
                                                            name="satker_id" id="satker_id"
                                                                    data-control="select2" @if(auth()->user()->user_roles != "superadmin") disabled @endif>>
                                                                    <option value="">---Pilih Satker---</option>
                                                                    @foreach ($satker as $row)
                                                                        <option value="{{ $row['id'] }}"
                                                                                @if($row['id'] === auth()->user()->satker->id_satker) selected @endif>{{ $row['text'] }}</option>
                                                                    @endforeach
                                                                </select>
                                                                @error('satker_id')
                                                                <p class="text-danger">{{ $message }}</p>
                                                                @enderror
                                                    </div>
                                                    @if(auth()->user()->user_roles != "superadmin")
                                                            <input type="hidden" name="satker_id" value="{{ auth()->user()?->satker?->id_satker }}">
                                                        @endif
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        {{-- <div class="card mb-3">
                                            <div class="card-body">

                                                
                                            </div>
                                        </div> --}}
                                        <div class="card mb-3">
                                            <div class="card-body">
                                                <label class="fs-3 fw-bold">BIODATA TARGET:</label> <hr>
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label for="nama_target" class="fs-6 fw-semibold mb-2 required">NIK</label>
                                                        <input type="text" class="form-control" name="nik" id="nik" placeholder="" value="{{old('nik')}}" required>                                                        
                                                        <br>
                                                        <!-- {{-- <div class="form-group">
                                                            <button onclick="processCekNik()" id="buttonProcessNik" type="button" class="btn btn-primary btn-lg btn-block">
                                                                Cari
                                                            </button>
                                                        </div> --}} -->
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="target_name" class="fs-6 fw-semibold mb-2 required">Nama Lengkap Target</label>
                                                        <input type="text" class="form-control" name="target_name" id="target_name" placeholder="" value="{{old('target_name')}}" required="required">
                                                        <p class="text-danger">{{ $errors->first('target_name') }}</p>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="target_religion" class="fs-6 fw-semibold mb-2">Agama</label>
                                                        <input type="text" class="form-control" name="target_religion" id="target_religion" placeholder="" value="{{old('target_religion')}}" >
                                                        <p class="text-danger">{{ $errors->first('target_religion') }}</p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-6">
                                                        <label for="target_education" class="fs-6 fw-semibold mb-2">Pendidikan Terakhir</label>
                                                            <div class="form-group col-md-12">
                                                                <input type="text" class="form-control" name="target_education" id="target_education" placeholder="" value="{{old('target_education')}}">
                                                                <p class="text-danger">{{ $errors->first('target_education') }}</p>
                                                            </div>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="target_occupation" class="fs-6 fw-semibold mb-2">Pekerjaan</label>
                                                            <div class="form-group col-md-12">
                                                                <input type="text" class="form-control" name="target_occupation" id="target_occupation" placeholder="" value="{{old('target_occupation')}}" >
                                                                <p class="text-danger">{{ $errors->first('target_occupation') }}</p>
                                                            </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="form-group col-md-6">
                                                        <label for="target_gender" class="fs-6 fw-semibold mb-2">Jenis Kelamin</label>
                                                            <div class="form-group col-md-12">
                                                                <input type="text" class="form-control" name="target_gender" id="target_gender" placeholder="" value="{{old('target_gender')}}" >
                                                                <p class="text-danger">{{ $errors->first('target_gender') }}</p>
                                                            </div>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="target_address" class="fs-6 fw-semibold mb-2">Alamat</label>
                                                        <div class="form-group col-md-12">
                                                            <textarea type="text" class="form-control" name="target_address" id="target_address" placeholder="" value="{{old('target_address')}}" ></textarea>
                                                            <p class="text-danger">{{ $errors->first('target_address') }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label for="target_image" class="fs-6 fw-semibold mb-2 ">Foto (Multiple Input Max. 2MB Per Foto)</label>
                                                        <input id="target_image" type="file" class="form-control image-input" name="target_image[]" accept=".jpg,.jpeg,.png" autocomplete="off" multiple>
                                                            <p class="text-danger">{{ $errors->first('image') }}</p>
                                                            <div class="image-preview container"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card mb-3">
                                            <div class="card-body">
                                                <div class="form-group col-md-12">
                                                    <label for="procedure_type" class="fs-6 fw-semibold mb-2 required">Close Procedure Type</label>
                                                    <select class="form-select form-select-solid select" name="procedure_type" id="procedure_type">
                                                        <option value="">---Pilih Prosedur---</option>
                                                            <option value="all">Semua</option>
                                                            <option value="observation">Pengamatan</option>
                                                            <option value="delineation">Penggambaran</option>
                                                            <option value="exploration">Penjajakan</option>
                                                            <option value="tailing">Tailing</option>
                                                            <option value="infiltration">Penyusupan</option>
                                                            <option value="intrusion">Penyurupan</option>
                                                            <option value="tapping">Penyadapan</option>
                                                        </select>
                                                    </div>
                                                </div>
                                        </div>


                                        <div class="card mb-3" id="observation">
                                            <div class="card-body">
                                                <label class="fs-3 fw-bold">PENGAMATAN</label> <hr>
                                                <div class="row">
                                                    <div class="row mb-7">
                                                        <div class="form-group col-md-6">
                                                            <label for="observation_surat_perintah"
                                                                class="fs-6 fw-semibold mb-2 required">Nomor Surat
                                                                Perintah</label>
                                                            <input type="text"
                                                                class="form-control form-control-solid @error('observation_surat_perintah') is-invalid @enderror"
                                                                name="observation_surat_perintah" id="observation_surat_perintah"
                                                                value="{{ old('observation_surat_perintah') }}">
                                                            @error('observation_surat_perintah')
                                                            <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        </div>

                                                        <div class="form-group col-md-6">
                                                            <label for="observation_upload_surat_perintah"
                                                                class="fs-6 fw-semibold mb-2 required">Upload File Surat Perintah (Pdf)</label>
                                                            <input
                                                                class="form-control form-control-solid @error('observation_upload_surat_perintah') is-invalid @enderror"
                                                                name="observation_upload_surat_perintah"
                                                                type="file"
                                                                accept=".pdf"
                                                                id="observation_upload_surat_perintah"
                                                                value="{{ old('observation_upload_surat_perintah') }}">
                                                            @error('observation_upload_surat_perintah')
                                                            <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        </div>

                                                    </div>

                                                    <div class="row mb-7">
                                                        <div class="form-group col-md-6">
                                                            <label for="observation_sumber_informasi"
                                                                class="fs-6 fw-semibold mb-2 required">Sumber Informasi</label>
                                                            <input type="text"
                                                                class="form-control form-control-solid @error('observation_sumber_informasi') is-invalid @enderror"
                                                                name="observation_sumber_informasi" id="observation_sumber_informasi"
                                                                value="{{ old('observation_sumber_informasi') }}">
                                                            @error('observation_sumber_informasi')
                                                            <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="row mb-7">
                                                        <div class="form-group col-md-12">
                                                            <label for="observation_detail_informasi"
                                                                   class="fs-6 fw-semibold mb-2 required">Detail Informasi</label>
                                                            <textarea class="form-control ckeditor1" name="observation_detail_informasi"
                                                            rows="4">{!! old('observation_detail_informasi') !!}</textarea>
                                                            @error('observation_detail_informasi')
                                                            <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="row mb-7">
                                                        <div class="form-group col-md-6">
                                                            <label for="observation_ancaman_detail"
                                                                class="fs-6 fw-semibold mb-2 required">Detail Ancaman</label>
                                                            <input type="text"
                                                                class="form-control form-control-solid @error('observation_ancaman_detail') is-invalid @enderror"
                                                                name="observation_ancaman_detail" id="observation_ancaman_detail"
                                                                value="{{ old('observation_ancaman_detail') }}">
                                                            @error('observation_ancaman_detail')
                                                            <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        </div>

                                                        <div class="form-group col-md-6">
                                                            <label for="observation_gangguan_detail"
                                                                class="fs-6 fw-semibold mb-2 required">Detail Gangguan</label>
                                                            <input type="text"
                                                                class="form-control form-control-solid @error('observation_gangguan_detail') is-invalid @enderror"
                                                                name="observation_gangguan_detail" id="observation_gangguan_detail"
                                                                value="{{ old('observation_gangguan_detail') }}">
                                                            @error('observation_gangguan_detail')
                                                            <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="row mb-7">
                                                        <div class="form-group col-md-6">
                                                            <label for="observation_hambatan_detail"
                                                                class="fs-6 fw-semibold mb-2 required">Detail Hambatan</label>
                                                            <input type="text"
                                                                class="form-control form-control-solid @error('observation_hambatan_detail') is-invalid @enderror"
                                                                name="observation_hambatan_detail" id="observation_hambatan_detail"
                                                                value="{{ old('observation_hambatan_detail') }}">
                                                            @error('observation_hambatan_detail')
                                                            <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        </div>

                                                        <div class="form-group col-md-6">
                                                            <label for="observation_tantangan_detail"
                                                                class="fs-6 fw-semibold mb-2 required">Detail Tantangan</label>
                                                            <input type="text"
                                                                class="form-control form-control-solid @error('observation_tantangan_detail') is-invalid @enderror"
                                                                name="observation_tantangan_detail" id="observation_tantangan_detail"
                                                                value="{{ old('observation_tantangan_detail') }}">
                                                            @error('observation_tantangan_detail')
                                                            <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>


                                                    <label class="fs-3 fw-bold mb-3 mt-3">BIODATA TARGET (PENGAMATAN):</label> <hr>
                                                    <div class="form-group col-md-12">
                                                        <label for="nama_target" class="fs-6 fw-semibold mb-2 required">NIK</label>
                                                        <input type="text" class="form-control" name="observation_nik_terkait" id="observation_nik_terkait" placeholder="" value="{{old('observation_nik_terkait')}}" required>                                                        
                                                        <br>
                                                        <!-- <div class="form-group">
                                                            <button onclick="processCekNik()" id="buttonProcessNik" type="button" class="btn btn-primary btn-lg btn-block">
                                                                Cari
                                                            </button>
                                                        </div> -->
                                                    </div>
                                                    <div class="row mb-7">
                                                        <div class="form-group col-md-6">
                                                            <label for="observaiton_nama_terkait" class="fs-6 fw-semibold mb-2 required">Nama</label>
                                                            <input type="text" class="form-control form-control-solid @error('observaiton_nama_terkait') is-invalid @enderror"
                                                                name="observaiton_nama_terkait" id="observaiton_nama_terkait" value="{{ old('observaiton_nama_terkait') }}" required>
                                                            @error('observaiton_nama_terkait')
                                                            <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="observation_jenis_kelamin_terkait" class="fs-6 fw-semibold mb-2">Jenis Kelamin Target</label>
                                                            <input type="text" class="form-control form-control-solid @error('observation_jenis_kelamin_terkait') is-invalid @enderror"
                                                            name="observation_jenis_kelamin_terkait" id="observation_jenis_kelamin_terkait" value="{{ old('observation_jenis_kelamin_terkait') }}">
                                                            @error('observation_jenis_kelamin_terkait')
                                                            <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="row mb-7">
                                                        <div class="row mb-7">
                                                            <div class="form-group col-md-6">
                                                                <label for="observation_pekerjaan_terkait" class="fs-6 fw-semibold mb-2">Pekerjaan</label>
                                                                <input type="text" class="form-control form-control-solid @error('observation_pekerjaan_terkait') is-invalid @enderror"
                                                                name="observation_pekerjaan_terkait" id="observation_pekerjaan_terkait" value="{{ old('observation_pekerjaan_terkait') }}">
                                                                @error('observation_pekerjaan_terkait')
                                                                <p class="text-danger">{{ $message }}</p>
                                                                @enderror
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="observation_pendidikan_terkait" class="fs-6 fw-semibold mb-2">Pendidikan Terakhir</label>
                                                                <input type="text" class="form-control form-control-solid @error('observation_pendidikan_terkait') is-invalid @enderror"
                                                                name="observation_pendidikan_terkait" id="observation_pendidikan_terkait" value="{{ old('observation_pendidikan_terkait') }}">
                                                                @error('observation_pendidikan_terkait')
                                                                <p class="text-danger">{{ $message }}</p>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-7">
                                                        <div class="form-group col-md-6">
                                                            <label for="observation_agama_terkait" class="fs-6 fw-semibold mb-2">Agama</label>
                                                            <input type="text" class="form-control form-control-solid @error('observation_agama_terkait') is-invalid @enderror"
                                                            name="observation_agama_terkait" id="observation_agama_terkait" value="{{ old('observation_agama_terkait') }}">
                                                            @error('observation_agama_terkait')
                                                            <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        </div>                                                    
                                                        <div class="form-group col-md-6">
                                                            <label for="observation_foto_terkait" class="fs-6 fw-semibold mb-2 ">Foto (Multiple Input Max. 2MB Per Foto)</label>
                                                            <input id="observation_foto_terkait" type="file" class="form-control image-input-observation" name="observation_foto_terkait[]" accept=".jpg,.jpeg,.png" autocomplete="off" multiple>
                                                                <p class="text-danger">{{ $errors->first('image') }}</p>
                                                                <div class="image-preview-observation container"></div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="card mb-3" id="delineation">
                                            <div class="card-body">
                                                <label class="fs-3 fw-bold">PENGGAMBARAN</label> <hr>
                                                <div class="row">

                                                    <div class="row mb-7">
                                                        <div class="form-group col-md-4">
                                                            <label for="delineation_informasi_verifikasi_kredibilitas_sumber"
                                                                class="fs-6 fw-semibold mb-2 required">Kredibilitas Sumber</label>
                                                            <select
                                                                class="form-select form-select-solid select @error('delineation_informasi_verifikasi_kredibilitas_sumber') is-invalid @enderror"
                                                                name="delineation_informasi_verifikasi_kredibilitas_sumber" id="delineation_informasi_verifikasi_kredibilitas_sumber"
                                                                data-control="select2" data-hide-search="true">
                                                                <option value="">---Pilih Kredibilitas Sumber---</option>
                                                                <option value="Tinggi" value="Tinggi">Tinggi</option>
                                                                <option value="Sedang" value="Sedang">Sedang</option>
                                                                <option value="Rendah" value="Rendah">Rendah</option>
                                                            </select>
                                                            @error('delineation_informasi_verifikasi_kredibilitas_sumber')
                                                            <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label for="delineation_informasi_verifikasi_metode_verifikasi"
                                                                class="fs-6 fw-semibold mb-2 required">Metode Verifikasi</label>
                                                            <input type="text"
                                                                class="form-control form-control-solid @error('delineation_informasi_verifikasi_metode_verifikasi') is-invalid @enderror"
                                                                name="delineation_informasi_verifikasi_metode_verifikasi" id="delineation_informasi_verifikasi_metode_verifikasi"
                                                                value="{{ old('delineation_informasi_verifikasi_metode_verifikasi') }}">
                                                            @error('delineation_informasi_verifikasi_metode_verifikasi')
                                                            <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label for="delineation_informasi_verifikasi_tanggal_verifikasi"
                                                                class="fs-6 fw-semibold mb-2 required">Tanggal
                                                                Verifikasi</label>
                                                            <input type="date"
                                                                class="form-control form-control-solid @error('delineation_informasi_verifikasi_tanggal_verifikasi') is-invalid @enderror"
                                                                name="delineation_informasi_verifikasi_tanggal_verifikasi" id="delineation_informasi_verifikasi_tanggal_verifikasi"
                                                                value="{{ old('delineation_informasi_verifikasi_tanggal_verifikasi') }}">
                                                            @error('delineation_informasi_verifikasi_tanggal_verifikasi')
                                                            <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="row mb-7">
                                                        <div class="form-group col-md-12">
                                                            <label for="pendahuluan" class="fs-6 fw-semibold mb-2 required">Detail Informasi Verifikasi</label>
                                                            <textarea
                                                                class="form-control form-control-solid @error('pendahuluan') is-invalid @enderror"
                                                                name="delineation_informasi_verifikasi_detail_informasi" id="delineation_informasi_verifikasi_detail_informasi">{{ old('delineation_informasi_verifikasi_detail_informasi') }}
                                                            </textarea>
                                                            @error('delineation_informasi_verifikasi_detail_informasi')
                                                            <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="row mb-7">
                                                        
                                                        
                                                        <div class="form-group col-md-6">
                                                            <label for="delineation_informasi_validasi_metode_validasi"
                                                                class="fs-6 fw-semibold mb-2 required">Metode Validasi</label>
                                                            <input type="text"
                                                                class="form-control form-control-solid @error('delineation_informasi_validasi_metode_validasi') is-invalid @enderror"
                                                                name="delineation_informasi_validasi_metode_validasi" id="delineation_informasi_validasi_metode_validasi"
                                                                value="{{ old('delineation_informasi_validasi_metode_validasi') }}">
                                                            @error('delineation_informasi_validasi_metode_validasi')
                                                            <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        </div>

                                                        <div class="form-group col-md-6">
                                                            <label for="delineation_informasi_validasi_tanggal_validasi"
                                                                class="fs-6 fw-semibold mb-2 required">Tanggal
                                                                Validasi</label>
                                                            <input type="date"
                                                                class="form-control form-control-solid @error('delineation_informasi_validasi_tanggal_validasi') is-invalid @enderror"
                                                                name="delineation_informasi_validasi_tanggal_validasi" id="delineation_informasi_validasi_tanggal_validasi"
                                                                value="{{ old('delineation_informasi_validasi_tanggal_validasi') }}">
                                                            @error('delineation_informasi_validasi_tanggal_validasi')
                                                            <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        </div>

                                                        
                                                        
                                                    </div>
                                                    <div class="row mb-7">

                                                        <div class="form-group col-md-12">
                                                            <label for="delineation_informasi_validasi_hasil_validasi"
                                                                class="fs-6 fw-semibold mb-2 required">Hasil Validasi</label>
                                                            <textarea
                                                                class="form-control form-control-solid @error('delineation_informasi_validasi_hasil_validasi') is-invalid @enderror"
                                                                name="delineation_informasi_validasi_hasil_validasi" id="delineation_informasi_validasi_hasil_validasi">{{ old('delineation_informasi_validasi_hasil_validasi') }}</textarea>
                                                            @error('delineation_informasi_validasi_hasil_validasi')
                                                            <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="row mb-7">
                                                        <div class="form-group col-md-6">
                                                            <label for="delineation_identitas_terhubung_subjek_utama"
                                                                class="fs-6 fw-semibold mb-2 required">Subjek Utama</label>
                                                            <input type="text"
                                                                class="form-control form-control-solid @error('delineation_identitas_terhubung_subjek_utama') is-invalid @enderror"
                                                                name="delineation_identitas_terhubung_subjek_utama" id="delineation_identitas_terhubung_subjek_utama"
                                                                value="{{ old('delineation_identitas_terhubung_subjek_utama') }}">
                                                            @error('delineation_identitas_terhubung_subjek_utama')
                                                            <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    

                                                        <div class="form-group col-md-6">
                                                            <label for="delineation_identitas_terhubung_subjek_terkait"
                                                                class="fs-6 fw-semibold mb-2 required">Subjek Terkait</label>
                                                            <input type="text"
                                                                class="form-control form-control-solid @error('delineation_identitas_terhubung_subjek_terkait') is-invalid @enderror"
                                                                name="delineation_identitas_terhubung_subjek_terkait" id="delineation_identitas_terhubung_subjek_terkait"
                                                                value="{{ old('delineation_identitas_terhubung_subjek_terkait') }}">
                                                            @error('delineation_identitas_terhubung_subjek_terkait')
                                                            <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        </div>

                                                    </div>

                                                    <div class="row mb-7">
                                                        <div class="form-group col-md-6">
                                                            <label for="delineation_identitas_terhubung_jenis_relasi"
                                                                class="fs-6 fw-semibold mb-2 required">Jenis Relasi</label>
                                                            <input type="text"
                                                                class="form-control form-control-solid @error('delineation_identitas_terhubung_jenis_relasi') is-invalid @enderror"
                                                                name="delineation_identitas_terhubung_jenis_relasi" id="delineation_identitas_terhubung_jenis_relasi"
                                                                value="{{ old('delineation_identitas_terhubung_jenis_relasi') }}">
                                                            @error('delineation_identitas_terhubung_jenis_relasi')
                                                            <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    

                                                        <div class="form-group col-md-6">
                                                            <label for="delineation_identitas_terhubung_kekuatan_relasi"
                                                                class="fs-6 fw-semibold mb-2 required">Kekuatan Relasi</label>
                                                            <input type="text"
                                                                class="form-control form-control-solid @error('delineation_identitas_terhubung_kekuatan_relasi') is-invalid @enderror"
                                                                name="delineation_identitas_terhubung_kekuatan_relasi" id="delineation_identitas_terhubung_kekuatan_relasi"
                                                                value="{{ old('delineation_identitas_terhubung_kekuatan_relasi') }}">
                                                            @error('delineation_identitas_terhubung_kekuatan_relasi')
                                                            <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card mb-3" id="exploration">
                                            <div class="card-body">
                                                <label class="fs-3 fw-bold">PENJAJAKAN</label> <hr>
                                                <div class="row">
                                                    <div class="row mb-7">
                                                        <div class="form-group col-md-12">
                                                            <label for="exploration_rencana_aksi" class="fs-6 fw-semibold mb-2 required">Rencana Aksi</label>
                                                            <textarea
                                                                    class="form-control form-control-solid @error('exploration_rencana_aksi') is-invalid @enderror"
                                                                    name="exploration_rencana_aksi" id="exploration_rencana_aksi">{{ old('exploration_rencana_aksi') }}</textarea>
                                                                @error('exploration_rencana_aksi')
                                                                <p class="text-danger">{{ $message }}</p>
                                                                @enderror
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        <label for="nama_target" class="fs-6 fw-semibold mb-2 required">NIK</label>
                                                        <input type="text" class="form-control" name="exploration_identitas_terhubung_nomor_identitas_target" id="exploration_identitas_terhubung_nomor_identitas_target" placeholder="" value="{{old('exploration_identitas_terhubung_nomor_identitas_target')}}" required>                                                        
                                                        <br>
                                                        <!-- <div class="form-group">
                                                            <button onclick="processCekNik()" id="buttonProcessNik" type="button" class="btn btn-primary btn-lg btn-block">
                                                                Cari
                                                            </button>
                                                        </div> -->
                                                    </div>
                                                    <div class="row mb-7">
                                                        <div class="form-group col-md-6">
                                                            <label for="exploration_identitas_terhubung_nama_target" class="fs-6 fw-semibold mb-2 required">Nama</label>
                                                            <input type="text" class="form-control form-control-solid @error('exploration_identitas_terhubung_nama_target') is-invalid @enderror"
                                                                name="exploration_identitas_terhubung_nama_target" id="exploration_identitas_terhubung_nama_target" value="{{ old('exploration_identitas_terhubung_nama_target') }}" required>
                                                            @error('exploration_identitas_terhubung_nama_target')
                                                            <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="exploration_identitas_terhubung_jenis_kelamin_target" class="fs-6 fw-semibold mb-2">Jenis Kelamin Target</label>
                                                            <input type="text" class="form-control form-control-solid @error('exploration_identitas_terhubung_jenis_kelamin_target') is-invalid @enderror"
                                                            name="exploration_identitas_terhubung_jenis_kelamin_target" id="exploration_identitas_terhubung_jenis_kelamin_target" value="{{ old('exploration_identitas_terhubung_jenis_kelamin_target') }}">
                                                            @error('exploration_identitas_terhubung_jenis_kelamin_target')
                                                            <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="row mb-7">
                                                        <div class="row mb-7">
                                                            <div class="form-group col-md-6">
                                                                <label for="exploration_identitas_terhubung_pekerjaan_target" class="fs-6 fw-semibold mb-2">Pekerjaan</label>
                                                                <input type="text" class="form-control form-control-solid @error('exploration_identitas_terhubung_pekerjaan_target') is-invalid @enderror"
                                                                name="exploration_identitas_terhubung_pekerjaan_target" id="exploration_identitas_terhubung_pekerjaan_target" value="{{ old('exploration_identitas_terhubung_pekerjaan_target') }}">
                                                                @error('exploration_identitas_terhubung_pekerjaan_target')
                                                                <p class="text-danger">{{ $message }}</p>
                                                                @enderror
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="exploration_identitas_terhubung_pendidikan_target" class="fs-6 fw-semibold mb-2">Pendidikan Terakhir</label>
                                                                <input type="text" class="form-control form-control-solid @error('exploration_identitas_terhubung_pendidikan_target') is-invalid @enderror"
                                                                name="exploration_identitas_terhubung_pendidikan_target" id="exploration_identitas_terhubung_pendidikan_target" value="{{ old('exploration_identitas_terhubung_pendidikan_target') }}">
                                                                @error('exploration_identitas_terhubung_pendidikan_target')
                                                                <p class="text-danger">{{ $message }}</p>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-7">
                                                        <div class="form-group col-md-6">
                                                            <label for="exploration_identitas_terhubung_agama_target" class="fs-6 fw-semibold mb-2">Agama</label>
                                                            <input type="text" class="form-control form-control-solid @error('exploration_identitas_terhubung_agama_target') is-invalid @enderror"
                                                            name="exploration_identitas_terhubung_agama_target" id="exploration_identitas_terhubung_agama_target" value="{{ old('exploration_identitas_terhubung_agama_target') }}">
                                                            @error('exploration_identitas_terhubung_agama_target')
                                                            <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        </div>    
                                                        <div class="form-group col-md-6">
                                                            <label for="exploration_identitas_terhubung_foto_target" class="fs-6 fw-semibold mb-2 ">Upload Foto</label>

                                                            <div class="form-group col-md-6">
                                                                <label for="exploration_identitas_terhubung_foto_target" class="fs-6 fw-semibold mb-2 ">Foto (Multiple Input Max. 2MB Per Foto)</label>
                                                                <input id="exploration_identitas_terhubung_foto_target" type="file" class="form-control image-input-exploration" name="exploration_identitas_terhubung_foto_target[]" accept=".jpg,.jpeg,.png" autocomplete="off" multiple>
                                                                    <p class="text-danger">{{ $errors->first('image') }}</p>
                                                                    <div class="image-preview-exploration container"></div>
                                                            </div>
                                                            
                                                           
                                                        </div>
                                                    </div>

                                                    <div class="row mb-7">
                                                        <div class="form-group col-md-12">
                                                            <label for="exploration_hasil_yang_dicapai" class="fs-6 fw-semibold mb-2 required">Hasil Capaian</label>
                                                            <textarea
                                                                    class="form-control form-control-solid @error('exploration_hasil_yang_dicapai') is-invalid @enderror"
                                                                    name="exploration_hasil_yang_dicapai" id="exploration_hasil_yang_dicapai">{{ old('exploration_hasil_yang_dicapai') }}</textarea>
                                                                @error('exploration_hasil_yang_dicapai')
                                                                <p class="text-danger">{{ $message }}</p>
                                                                @enderror
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="card mb-3" id="tailing">
                                            <div class="card-body">
                                                <label class="fs-3 fw-bold">TAILING</label> <hr>
                                                <div class="row">
                                                    <label class="fs-3 fw-bold mb-3 mt-3">BIODATA TARGET (TAILING):</label> <hr>
                                                    <div class="form-group col-md-12">
                                                        <label for="tailing_pemahaman_perilaku_nomor_identitas" class="fs-6 fw-semibold mb-2 required">NIK</label>
                                                        <input type="text" class="form-control" name="tailing_pemahaman_perilaku_nomor_identitas" id="tailing_pemahaman_perilaku_nomor_identitas" placeholder="" value="{{old('tailing_pemahaman_perilaku_nomor_identitas')}}" required>                                                        
                                                        <br>
                                                        <!-- <div class="form-group">
                                                            <button onclick="processCekNik()" id="buttonProcessNik" type="button" class="btn btn-primary btn-lg btn-block">
                                                                Cari
                                                            </button>
                                                        </div> -->
                                                    </div>
                                                    <div class="row mb-7">
                                                        <div class="form-group col-md-6">
                                                            <label for="tailing_pemahaman_perilaku_nama" class="fs-6 fw-semibold mb-2 required">Nama</label>
                                                            <input type="text" class="form-control form-control-solid @error('tailing_pemahaman_perilaku_nama') is-invalid @enderror"
                                                                name="tailing_pemahaman_perilaku_nama" id="tailing_pemahaman_perilaku_nama" value="{{ old('tailing_pemahaman_perilaku_nama') }}" required>
                                                            @error('tailing_pemahaman_perilaku_nama')
                                                            <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="tailing_pemahaman_perilaku_jenis_kelamin" class="fs-6 fw-semibold mb-2">Jenis Kelamin Target</label>
                                                            <input type="text" class="form-control form-control-solid @error('tailing_pemahaman_perilaku_jenis_kelamin') is-invalid @enderror"
                                                            name="tailing_pemahaman_perilaku_jenis_kelamin" id="tailing_pemahaman_perilaku_jenis_kelamin" value="{{ old('tailing_pemahaman_perilaku_jenis_kelamin') }}">
                                                            @error('tailing_pemahaman_perilaku_jenis_kelamin')
                                                            <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="row mb-7">
                                                        <div class="row mb-7">
                                                            <div class="form-group col-md-6">
                                                                <label for="tailing_pemahaman_perilaku_pekerjaan" class="fs-6 fw-semibold mb-2">Pekerjaan</label>
                                                                <input type="text" class="form-control form-control-solid @error('tailing_pemahaman_perilaku_pekerjaan') is-invalid @enderror"
                                                                name="tailing_pemahaman_perilaku_pekerjaan" id="tailing_pemahaman_perilaku_pekerjaan" value="{{ old('tailing_pemahaman_perilaku_pekerjaan') }}">
                                                                @error('tailing_pemahaman_perilaku_pekerjaan')
                                                                <p class="text-danger">{{ $message }}</p>
                                                                @enderror
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="tailing_pemahaman_perilaku_pendidikan" class="fs-6 fw-semibold mb-2">Pendidikan Terakhir</label>
                                                                <input type="text" class="form-control form-control-solid @error('tailing_pemahaman_perilaku_pendidikan') is-invalid @enderror"
                                                                name="tailing_pemahaman_perilaku_pendidikan" id="tailing_pemahaman_perilaku_pendidikan" value="{{ old('tailing_pemahaman_perilaku_pendidikan') }}">
                                                                @error('tailing_pemahaman_perilaku_pendidikan')
                                                                <p class="text-danger">{{ $message }}</p>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-7">
                                                        <div class="form-group col-md-6">
                                                            <label for="tailing_pemahaman_perilaku_agama" class="fs-6 fw-semibold mb-2">Agama</label>
                                                            <input type="text" class="form-control form-control-solid @error('tailing_pemahaman_perilaku_agama') is-invalid @enderror"
                                                            name="tailing_pemahaman_perilaku_agama" id="tailing_pemahaman_perilaku_agama" value="{{ old('tailing_pemahaman_perilaku_agama') }}">
                                                            @error('tailing_pemahaman_perilaku_agama')
                                                            <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        </div>                                                    
                                                        <div class="form-group col-md-6">
                                                            <label for="tailing_pemahaman_perilaku_foto" class="fs-6 fw-semibold mb-2 ">Foto (Multiple Input Max. 2MB Per Foto)</label>
                                                            <input id="tailing_pemahaman_perilaku_foto" type="file" class="form-control image-input-tailing" name="tailing_pemahaman_perilaku_foto[]" accept=".jpg,.jpeg,.png" autocomplete="off" multiple>
                                                                <p class="text-danger">{{ $errors->first('image') }}</p>
                                                                <div class="image-preview-tailing container"></div>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-7">
                                                        <div class="form-group col-md-12">
                                                            <label for="tailing_pemahaman_perilaku_perilaku_tercatat" class="fs-6 fw-semibold mb-2 required">Pemahaman Perilaku Tercatat</label>
                                                            <textarea
                                                                    class="form-control form-control-solid @error('tailing_pemahaman_perilaku_perilaku_tercatat') is-invalid @enderror"
                                                                    name="tailing_pemahaman_perilaku_perilaku_tercatat" id="tailing_pemahaman_perilaku_perilaku_tercatat">{{ old('tailing_pemahaman_perilaku_perilaku_tercatat') }}</textarea>
                                                                @error('tailing_pemahaman_perilaku_perilaku_tercatat')
                                                                <p class="text-danger">{{ $message }}</p>
                                                                @enderror
                                                        </div>
                                                    </div>
                                                    <div class="row mb-7">
                                                        <div class="form-group col-md-12">
                                                            <label for="tailing_pemahaman_perilaku_upload_video"
                                                                class="fs-6 fw-semibold mb-2 required">Upload File
                                                                Video (Pemahaman Perilaku)</label>
                                                            <input
                                                                class="form-control form-control-solid @error('tailing_pemahaman_perilaku_upload_video') is-invalid @enderror"
                                                                name="tailing_pemahaman_perilaku_upload_video"
                                                                type="file"
                                                                id="tailing_pemahaman_perilaku_upload_video"
                                                                value="{{ old('tailing_pemahaman_perilaku_upload_video') }}">
                                                            @error('tailing_pemahaman_perilaku_upload_video')
                                                            <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="row mb-7">
                                                        <div class="form-group col-md-6">
                                                            <label for="tailing_rencana_operasi" class="fs-6 fw-semibold mb-2 required">Rencana Operasi</label>
                                                            <textarea
                                                                    class="form-control form-control-solid @error('tailing_rencana_operasi') is-invalid @enderror"
                                                                    name="tailing_rencana_operasi" id="tailing_rencana_operasi">{{ old('tailing_rencana_operasi') }}</textarea>
                                                                @error('tailing_rencana_operasi')
                                                                <p class="text-danger">{{ $message }}</p>
                                                                @enderror
                                                        </div>

                                                        <div class="form-group col-md-6">
                                                            <label for="tailing_target_operasi" class="fs-6 fw-semibold mb-2 required">Target Operasi</label>
                                                            <textarea
                                                                    class="form-control form-control-solid @error('tailing_target_operasi') is-invalid @enderror"
                                                                    name="tailing_target_operasi" id="tailing_target_operasi">{{ old('tailing_target_operasi') }}</textarea>
                                                                @error('tailing_target_operasi')
                                                                <p class="text-danger">{{ $message }}</p>
                                                                @enderror
                                                        </div>
                                                    </div>

                                                    <div class="row mb-7">
                                                        <div class="form-group col-md-12">
                                                            <label for="tailing_target_operasi_upload_video"
                                                                class="fs-6 fw-semibold mb-2 required">Upload File
                                                                Video (Target Operasi)</label>
                                                            <input
                                                                class="form-control form-control-solid @error('tailing_target_operasi_upload_video') is-invalid @enderror"
                                                                name="tailing_target_operasi_upload_video"
                                                                type="file"
                                                                id="tailing_target_operasi_upload_video"
                                                                value="{{ old('tailing_target_operasi_upload_video') }}">
                                                            @error('tailing_target_operasi_upload_video')
                                                            <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="row mb-7">
                                                        <div class="form-group col-md-12">
                                                            <label for="tailing_hasil_yang_dicapai" class="fs-6 fw-semibold mb-2 required">Hasil Capaian</label>
                                                            <textarea
                                                                    class="form-control form-control-solid @error('tailing_hasil_yang_dicapai') is-invalid @enderror"
                                                                    name="tailing_hasil_yang_dicapai" id="tailing_hasil_yang_dicapai">{{ old('tailing_hasil_yang_dicapai') }}</textarea>
                                                                @error('tailing_hasil_yang_dicapai')
                                                                <p class="text-danger">{{ $message }}</p>
                                                                @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card mb-3" id="infiltration">
                                            <div class="card-body">
                                                <label class="fs-3 fw-bold">PENYUSUPAN</label> <hr>
                                                <div class="row">
                                                    <div class="row mb-7">
                                                        <div class="form-group col-md-6">
                                                            <label for="infiltration_nama_operasi_rahasia" class="fs-6 fw-semibold mb-2">Nama Operasi Rahasia</label>
                                                            <input type="text" class="form-control form-control-solid @error('infiltration_nama_operasi_rahasia') is-invalid @enderror"
                                                                name="infiltration_nama_operasi_rahasia" id="infiltration_nama_operasi_rahasia" value="{{ old('infiltration_nama_operasi_rahasia') }}">
                                                                @error('infiltration_nama_operasi_rahasia')
                                                                <p class="text-danger">{{ $message }}</p>
                                                                @enderror
                                                        </div>                                                    
                                                        <div class="form-group col-md-6">
                                                            <label for="infiltration_metode_eksekusi" class="fs-6 fw-semibold mb-2 ">Metode Eksekusi</label>
                                                            <input type="text" class="form-control form-control-solid @error('infiltration_metode_eksekusi') is-invalid @enderror"
                                                                name="infiltration_metode_eksekusi" id="infiltration_metode_eksekusi" value="{{ old('infiltration_metode_eksekusi') }}">
                                                                @error('infiltration_metode_eksekusi')
                                                                <p class="text-danger">{{ $message }}</p>
                                                                @enderror
                                                        </div>
                                                    </div>
                                                    <div class="row mb-7">
                                                        <div class="form-group col-md-12">
                                                            <label for="infiltration_operasi_rahasia_upload_video"
                                                                class="fs-6 fw-semibold mb-2 required">Upload File
                                                                Video (Target Operasi)</label>
                                                            <input
                                                                class="form-control form-control-solid @error('infiltration_operasi_rahasia_upload_video') is-invalid @enderror"
                                                                name="infiltration_operasi_rahasia_upload_video"
                                                                type="file"
                                                                id="infiltration_operasi_rahasia_upload_video"
                                                                value="{{ old('infiltration_operasi_rahasia_upload_video') }}">
                                                            @error('infiltration_operasi_rahasia_upload_video')
                                                            <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="row mb-7">
                                                        <div class="form-group col-md-12">
                                                            <label for="infiltration_dinamika_teramati" class="fs-6 fw-semibold mb-2 required">Dinamika Teramati</label>
                                                            <textarea
                                                                    class="form-control form-control-solid @error('infiltration_dinamika_teramati') is-invalid @enderror"
                                                                    name="infiltration_dinamika_teramati" id="infiltration_dinamika_teramati">{{ old('infiltration_dinamika_teramati') }}</textarea>
                                                                @error('infiltration_dinamika_teramati')
                                                                <p class="text-danger">{{ $message }}</p>
                                                                @enderror
                                                        </div>
                                                    </div>
                                                    <div class="row mb-7">
                                                        <div class="form-group col-md-12">
                                                            <label for="infiltration_dinamika_teramati_upload_video"
                                                                class="fs-6 fw-semibold mb-2 required">Upload File
                                                                Video (Dinamika Teramati)</label>
                                                            <input
                                                                class="form-control form-control-solid @error('infiltration_dinamika_teramati_upload_video') is-invalid @enderror"
                                                                name="infiltration_dinamika_teramati_upload_video"
                                                                type="file"
                                                                id="infiltration_dinamika_teramati_upload_video"
                                                                value="{{ old('infiltration_dinamika_teramati_upload_video') }}">
                                                            @error('infiltration_dinamika_teramati_upload_video')
                                                            <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="row mb-7">
                                                        <div class="form-group col-md-12">
                                                            <label for="infiltration_hasil_yang_dicapai" class="fs-6 fw-semibold mb-2 required">Hasil Capaian</label>
                                                            <textarea
                                                                    class="form-control form-control-solid @error('infiltration_hasil_yang_dicapai') is-invalid @enderror"
                                                                    name="infiltration_hasil_yang_dicapai" id="infiltration_hasil_yang_dicapai">{{ old('infiltration_hasil_yang_dicapai') }}</textarea>
                                                                @error('infiltration_hasil_yang_dicapai')
                                                                <p class="text-danger">{{ $message }}</p>
                                                                @enderror
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="card mb-3" id="intrusion">
                                            <div class="card-body">
                                                <label class="fs-3 fw-bold">PENYURUPAN</label> <hr>
                                                <div class="row">
                                                    <label class="fs-3 fw-bold mb-3 mt-3">BIODATA TARGET (PENYURUPAN):</label> <hr>
                                                    <div class="form-group col-md-12">
                                                        <label for="intrusion_nomor_identitas" class="fs-6 fw-semibold mb-2 required">NIK</label>
                                                        <input type="text" class="form-control" name="intrusion_nomor_identitas" id="intrusion_nomor_identitas" placeholder="" value="{{old('intrusion_nomor_identitas')}}" required>                                                        
                                                        <br>
                                                        <!-- <div class="form-group">
                                                            <button onclick="processCekNik()" id="buttonProcessNik" type="button" class="btn btn-primary btn-lg btn-block">
                                                                Cari
                                                            </button>
                                                        </div> -->
                                                    </div>
                                                    <div class="row mb-7">
                                                        <div class="form-group col-md-6">
                                                            <label for="intrusion_nama" class="fs-6 fw-semibold mb-2 required">Nama</label>
                                                            <input type="text" class="form-control form-control-solid @error('intrusion_nama') is-invalid @enderror"
                                                                name="intrusion_nama" id="intrusion_nama" value="{{ old('intrusion_nama') }}" required>
                                                            @error('intrusion_nama')
                                                            <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="intrusion_jenis_kelamin" class="fs-6 fw-semibold mb-2">Jenis Kelamin Target</label>
                                                            <input type="text" class="form-control form-control-solid @error('intrusion_jenis_kelamin') is-invalid @enderror"
                                                            name="intrusion_jenis_kelamin" id="intrusion_jenis_kelamin" value="{{ old('intrusion_jenis_kelamin') }}">
                                                            @error('intrusion_jenis_kelamin')
                                                            <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="row mb-7">
                                                        <div class="row mb-7">
                                                            <div class="form-group col-md-6">
                                                                <label for="intrusion_pekerjaan" class="fs-6 fw-semibold mb-2">Pekerjaan</label>
                                                                <input type="text" class="form-control form-control-solid @error('intrusion_pekerjaan') is-invalid @enderror"
                                                                name="intrusion_pekerjaan" id="intrusion_pekerjaan" value="{{ old('intrusion_pekerjaan') }}">
                                                                @error('intrusion_pekerjaan')
                                                                <p class="text-danger">{{ $message }}</p>
                                                                @enderror
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="intrusion_pendidikan" class="fs-6 fw-semibold mb-2">Pendidikan Terakhir</label>
                                                                <input type="text" class="form-control form-control-solid @error('intrusion_pendidikan') is-invalid @enderror"
                                                                name="intrusion_pendidikan" id="intrusion_pendidikan" value="{{ old('intrusion_pendidikan') }}">
                                                                @error('intrusion_pendidikan')
                                                                <p class="text-danger">{{ $message }}</p>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-7">
                                                        <div class="form-group col-md-6">
                                                            <label for="intrusion_agama" class="fs-6 fw-semibold mb-2">Agama</label>
                                                            <input type="text" class="form-control form-control-solid @error('intrusion_agama') is-invalid @enderror"
                                                            name="intrusion_agama" id="intrusion_agama" value="{{ old('intrusion_agama') }}">
                                                            @error('intrusion_agama')
                                                            <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        </div>                                                    
                                                        <div class="form-group col-md-6">
                                                            <label for="intrusion_foto" class="fs-6 fw-semibold mb-2 ">Foto (Multiple Input Max. 2MB Per Foto)</label>
                                                            <input id="intrusion_foto" type="file" class="form-control image-input-intrusion" name="intrusion_foto[]" accept=".jpg,.jpeg,.png" autocomplete="off" multiple>
                                                                <p class="text-danger">{{ $errors->first('image') }}</p>
                                                                <div class="image-preview-intrusion container"></div>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-7">
                                                        <div class="form-group col-md-6">
                                                            <label for="intrusion_deskripsi_lokasi" class="fs-6 fw-semibold mb-2 required">Deskripsi Lokasi</label>
                                                            <textarea
                                                                    class="form-control form-control-solid @error('intrusion_deskripsi_lokasi') is-invalid @enderror"
                                                                    name="intrusion_deskripsi_lokasi" id="intrusion_deskripsi_lokasi">{{ old('intrusion_deskripsi_lokasi') }}</textarea>
                                                                @error('intrusion_deskripsi_lokasi')
                                                                <p class="text-danger">{{ $message }}</p>
                                                                @enderror
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="intrusion_tipe_lingkungan" class="fs-6 fw-semibold mb-2 required">Tipe Lingkungan</label>
                                                            <textarea
                                                                    class="form-control form-control-solid @error('intrusion_tipe_lingkungan') is-invalid @enderror"
                                                                    name="intrusion_tipe_lingkungan" id="intrusion_tipe_lingkungan">{{ old('intrusion_tipe_lingkungan') }}</textarea>
                                                                @error('intrusion_tipe_lingkungan')
                                                                <p class="text-danger">{{ $message }}</p>
                                                                @enderror
                                                        </div>
                                                    </div>
                                                    <div class="row mb-7">
                                                    <div class="form-group col-md-6">
                                                            <label for="intrusion_deskripsi_lingkungan" class="fs-6 fw-semibold mb-2 required">Deskripsi Lingkungan</label>
                                                            <textarea
                                                                    class="form-control form-control-solid @error('intrusion_deskripsi_lingkungan') is-invalid @enderror"
                                                                    name="intrusion_deskripsi_lingkungan" id="intrusion_deskripsi_lingkungan">{{ old('intrusion_deskripsi_lingkungan') }}</textarea>
                                                                @error('intrusion_deskripsi_lingkungan')
                                                                <p class="text-danger">{{ $message }}</p>
                                                                @enderror
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="intrusion_hasil_yang_dicapai" class="fs-6 fw-semibold mb-2 required">Hasil Capaian</label>
                                                            <textarea
                                                                    class="form-control form-control-solid @error('intrusion_hasil_yang_dicapai') is-invalid @enderror"
                                                                    name="intrusion_hasil_yang_dicapai" id="intrusion_hasil_yang_dicapai">{{ old('intrusion_hasil_yang_dicapai') }}</textarea>
                                                                @error('intrusion_hasil_yang_dicapai')
                                                                <p class="text-danger">{{ $message }}</p>
                                                                @enderror
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="card mb-3" id="tapping">
                                            <div class="card-body">
                                                <label class="fs-3 fw-bold">PENYADAPAN</label>
                                                <hr>
                                                <div class="row">
                                                    <div class="row mb-7">
                                                        <div class="form-group col-md-6">
                                                            <label for="tapping_sumber_data" class="fs-6 fw-semibold mb-2">Sumber Data</label>
                                                            <input type="text" class="form-control form-control-solid @error('tapping_sumber_data') is-invalid @enderror" 
                                                                name="tapping_sumber_data" id="tapping_sumber_data" value="{{ old('tapping_sumber_data') }}">
                                                            @error('tapping_sumber_data')
                                                            <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="tapping_metode_penyadapan" class="fs-6 fw-semibold mb-2">Metode Penyadapan</label>
                                                            <input type="text" class="form-control form-control-solid @error('tapping_metode_penyadapan') is-invalid @enderror" 
                                                                name="tapping_metode_penyadapan" id="tapping_metode_penyadapan" value="{{ old('tapping_metode_penyadapan') }}">
                                                            @error('tapping_metode_penyadapan')
                                                            <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="row mb-7">
                                                        <div class="form-group col-md-12">
                                                            <label for="tapping_data_perangkat_elektronik_upload_video" class="fs-6 fw-semibold mb-2 required">Upload File Video (Penyadaapn)</label>
                                                            <input type="file" class="form-control form-control-solid @error('tapping_data_perangkat_elektronik_upload_video') is-invalid @enderror" 
                                                                name="tapping_data_perangkat_elektronik_upload_video" id="tapping_data_perangkat_elektronik_upload_video" 
                                                                value="{{ old('tapping_data_perangkat_elektronik_upload_video') }}">
                                                            @error('tapping_data_perangkat_elektronik_upload_video')
                                                            <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="row mb-7">
                                                        <div class="form-group col-md-6">
                                                            <label for="tapping_jenis_sinyal" class="fs-6 fw-semibold mb-2">Jenis Sinyal</label>
                                                            <input type="text" class="form-control form-control-solid @error('tapping_jenis_sinyal') is-invalid @enderror" 
                                                                name="tapping_jenis_sinyal" id="tapping_jenis_sinyal" value="{{ old('tapping_jenis_sinyal') }}">
                                                            @error('tapping_jenis_sinyal')
                                                            <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="row mb-7">
                                                        <div class="form-group col-md-12">
                                                            <label for="tapping_deskripsi_hasil_sinyal" class="fs-6 fw-semibold mb-2 required">Deskripsi Hasil Sinyal</label>
                                                            <textarea class="form-control form-control-solid @error('tapping_deskripsi_hasil_sinyal') is-invalid @enderror" 
                                                                    name="tapping_deskripsi_hasil_sinyal" id="tapping_deskripsi_hasil_sinyal">{{ old('tapping_deskripsi_hasil_sinyal') }}</textarea>
                                                            @error('tapping_deskripsi_hasil_sinyal')
                                                            <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="row mb-7">
                                                        <div class="form-group col-md-12">
                                                            <label for="tapping_hasil_yang_dicapai" class="fs-6 fw-semibold mb-2 required">Hasil Capaian</label>
                                                            <textarea class="form-control form-control-solid @error('tapping_hasil_yang_dicapai') is-invalid @enderror" 
                                                                    name="tapping_hasil_yang_dicapai" id="tapping_hasil_yang_dicapai">{{ old('tapping_hasil_yang_dicapai') }}</textarea>
                                                            @error('tapping_hasil_yang_dicapai')
                                                            <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="row" id="button_div">
                                            <div class="col-md-3">
                                                 <button
                                                    class="btn btn-dark waves-effect waves-classic waves-effect waves-classic"
                                                    type="submit">Simpan
                                                 </button>
                                            </div>
                                        </div>
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

    <script type="module">
            // CKEDITOR SETTING
            document.addEventListener('DOMContentLoaded', () => {
                ClassicEditor
                    .create(document.querySelector('textarea.ckeditor1'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                    .catch(error => {
                        console.error(error);
                });
            });
            ClassicEditor
                    .create(document.querySelector('#case_description'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                    .catch(error => {
                        console.error(error);
                    });
            ClassicEditor
                    .create(document.querySelector('#delineation_informasi_verifikasi_detail_informasi'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                    .catch(error => {
                        console.error(error);
                    });
            ClassicEditor
                    .create(document.querySelector('#delineation_informasi_validasi_hasil_validasi'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                    .catch(error => {
                        console.error(error);
                    });
            ClassicEditor
                    .create(document.querySelector('#exploration_rencana_aksi'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                    .catch(error => {
                        console.error(error);
                    });
            ClassicEditor
                    .create(document.querySelector('#exploration_hasil_yang_dicapai'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                    .catch(error => {
                        console.error(error);
                    });

            ClassicEditor
                    .create(document.querySelector('#tailing_pemahaman_perilaku_perilaku_tercatat'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                    .catch(error => {
                        console.error(error);
                    });

            ClassicEditor
                    .create(document.querySelector('#tailing_rencana_operasi'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                    .catch(error => {
                        console.error(error);
                    });

            ClassicEditor
                    .create(document.querySelector('#tailing_target_operasi'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                    .catch(error => {
                        console.error(error);
                    });

            ClassicEditor
                    .create(document.querySelector('#tailing_hasil_yang_dicapai'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                    .catch(error => {
                        console.error(error);
                    });

            ClassicEditor
                    .create(document.querySelector('#infiltration_dinamika_teramati'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                    .catch(error => {
                        console.error(error);
                    });

            ClassicEditor
                    .create(document.querySelector('#infiltration_hasil_yang_dicapai'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                    .catch(error => {
                        console.error(error);
                    });

            ClassicEditor
                    .create(document.querySelector('#intrusion_deskripsi_lokasi'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                    .catch(error => {
                        console.error(error);
                    });

            ClassicEditor
                    .create(document.querySelector('#intrusion_tipe_lingkungan'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                    .catch(error => {
                        console.error(error);
                    });

            ClassicEditor
                    .create(document.querySelector('#intrusion_deskripsi_lingkungan'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                    .catch(error => {
                        console.error(error);
                    });

            ClassicEditor
                    .create(document.querySelector('#intrusion_hasil_yang_dicapai'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                    .catch(error => {
                        console.error(error);
                    });

            ClassicEditor
                    .create(document.querySelector('#tapping_deskripsi_hasil_sinyal'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                    .catch(error => {
                        console.error(error);
                    });

            ClassicEditor
                    .create(document.querySelector('#tapping_hasil_yang_dicapai'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                    .catch(error => {
                        console.error(error);
                    });
    </script>

    <script >
        var observationDiv = document.getElementById('observation');
        var delineationDiv = document.getElementById('delineation');
        var explorationDiv = document.getElementById('exploration');
        var tailingDiv = document.getElementById('tailing');
        var infiltrationDiv = document.getElementById('infiltration');
        var intrusionDiv = document.getElementById('intrusion');
        var tappingDiv = document.getElementById('tapping');

        observationDiv.style.display = 'none';
        delineationDiv.style.display = 'none';
        explorationDiv.style.display = 'none';
        tailingDiv.style.display = 'none';
        infiltrationDiv.style.display = 'none';
        intrusionDiv.style.display = 'none';
        tappingDiv.style.display = 'none';

        document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('procedure_type').addEventListener('change', function() {
            console.log('Selected value:', this.value);
            var observationDiv = document.getElementById('observation');
            var delineationDiv = document.getElementById('delineation');
            var explorationDiv = document.getElementById('exploration');
            var tailingDiv = document.getElementById('tailing');
            var infiltrationDiv = document.getElementById('infiltration');
            var intrusionDiv = document.getElementById('intrusion');
            var tappingDiv = document.getElementById('tapping');


            if (this.value === '') {
                observationDiv.style.display = 'none';
                delineationDiv.style.display = 'none';
                explorationDiv.style.display = 'none';
                tailingDiv.style.display = 'none';
                infiltrationDiv.style.display = 'none';
                intrusionDiv.style.display = 'none';
                tappingDiv.style.display = 'none';
                
            }
            if (this.value === 'all') {
                observationDiv.style.display = 'block';
                delineationDiv.style.display = 'block';
                explorationDiv.style.display = 'block';
                tailingDiv.style.display = 'block';
                infiltrationDiv.style.display = 'block';
                intrusionDiv.style.display = 'block';
                tappingDiv.style.display = 'block';
            } 

            if (this.value === 'observation') {
                observationDiv.style.display = 'block';
                delineationDiv.style.display = 'none';
                explorationDiv.style.display = 'none';
                tailingDiv.style.display = 'none';
                infiltrationDiv.style.display = 'none';
                intrusionDiv.style.display = 'none';
                tappingDiv.style.display = 'none';
            } 

            if (this.value === 'delineation') {
                observationDiv.style.display = 'none';
                delineationDiv.style.display = 'block';
                explorationDiv.style.display = 'none';
                tailingDiv.style.display = 'none';
                infiltrationDiv.style.display = 'none';
                intrusionDiv.style.display = 'none';
                tappingDiv.style.display = 'none';
                
            }

            if (this.value === 'exploration') {
                observationDiv.style.display = 'none';
                delineationDiv.style.display = 'none';
                explorationDiv.style.display = 'block';
                tailingDiv.style.display = 'none';
                infiltrationDiv.style.display = 'none';
                intrusionDiv.style.display = 'none';
                tappingDiv.style.display = 'none';
                
            }

            if (this.value === 'tailing') {
                observationDiv.style.display = 'none';
                delineationDiv.style.display = 'none';
                explorationDiv.style.display = 'none';
                tailingDiv.style.display = 'block';
                infiltrationDiv.style.display = 'none';
                intrusionDiv.style.display = 'none';
                tappingDiv.style.display = 'none';
                
            }

            if (this.value === 'infiltration') {
                observationDiv.style.display = 'none';
                delineationDiv.style.display = 'none';
                explorationDiv.style.display = 'none';
                tailingDiv.style.display = 'none';
                infiltrationDiv.style.display = 'block';
                intrusionDiv.style.display = 'none';
                tappingDiv.style.display = 'none';
                
            }

            if (this.value === 'intrusion') {
                observationDiv.style.display = 'none';
                delineationDiv.style.display = 'none';
                explorationDiv.style.display = 'none';
                tailingDiv.style.display = 'none';
                infiltrationDiv.style.display = 'none';
                intrusionDiv.style.display = 'block';
                tappingDiv.style.display = 'none';
                
            }

            if (this.value === 'tapping') {
                observationDiv.style.display = 'none';
                delineationDiv.style.display = 'none';
                explorationDiv.style.display = 'none';
                tailingDiv.style.display = 'none';
                infiltrationDiv.style.display = 'none';
                intrusionDiv.style.display = 'none';
                tappingDiv.style.display = 'block';
                
            }

            // Add any additional actions you want to take when the value changes here
        });
       
    });
    </script>
    <script>
        
    </script>
    <script>
        $('#cari').on('click',function(e){
                e.preventDefault();
                var nip = $('#nip').val();
                ambilnip( nip );
        });
        function processCekNik() {
            var $this = $('#buttonProcessNik');
            let nik = $('#nik').val();

            if (nik == '' || nik.length == 0 ) {
                alert("Silahkan isi NIK");
                return false;
            }

            BtnLoading($this);
            var nama_target = $("#nama_target");

            console.log("Starting AJAX request with NIK: ", nik); // Debugging

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('close.kependudukan.ceknik', ['nik' => 'NIK_PLACEHOLDER']) }}".replace('NIK_PLACEHOLDER', nik),
                method: "GET",
                dataType: 'json',
                success: function(response) {
                    // console.log("AJAX response received: ", response); // Debugging
                    if (response.status = 'sukses') {
                        var val_nama = response.nama;
                        var val_agama = response.agama;
                        var val_pendidikan = response.pendidikan;
                        var val_pekerjaan = response.pekerjaan;
                        var val_jenis_kelamin = response.jenis_kelamin;
                        var val_alamat = response.alamat;
                        // console.log("Setting nama_target to: ", val_nama); // Debugging
                        $("#nama_target").val(val_nama);
                        $("#agama").val(val_agama);
                        $("#pendidikan").val(val_pendidikan);
                        $("#pekerjaan").val(val_pekerjaan);
                        $("#jenis_kelamin").val(val_jenis_kelamin);
                        $("#alamat").val(val_alamat);
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    alert('Terjadi kesalahan saat memproses data.');
                    console.log("AJAX error: ", error); // Debugging
                },
                complete: function() {
                    $this.html($this.attr("data-original-text"));
                    $this.prop("disabled", false);
                }
            });
        }

        function BtnLoading(elem) {
            $(elem).attr("data-original-text", $(elem).html());
            $(elem).prop("disabled", true);
            $(elem).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...');
        }

        $(document).ready(function() {
            setTimeout(function() {
            $(".alert").slideUp(500);
            }, 3000);

        });
    </script>
    <script>
        $(document).ready(function(){
            const input = $('.image-input');
            const preview = $('.image-preview');

            // Set the input change event to trigger the preview update
            input.on('change', function() {
                // Clear the preview container
                preview.empty();

                if (input.val()) {
                    // Show the preview container
                    preview.show();
                    // Add a border to the preview container
                    preview.css('border', '1px solid #ccc');
                    preview.css('border-radius', '5px');
                    // Justify the images in the container
                    preview.css('display', 'flex');
                    preview.css('justify-content', 'space-between');
                    preview.css('padding', '5px');
                    preview.css('flex-wrap', 'wrap');
                }
                else {
                    // Hide the preview container
                    preview.hide();
                    // Remove the border from the preview container
                    preview.css('border', 'none');
                }

                // Loop through each selected file
                for (let i = 0; i < this.files.length; i++) {
                    const file = this.files[i];
                    const image_url = URL.createObjectURL(file);

                    // Check if the file size is less than or equal to 2 MB
                    if (file.size <= 2000000) {
                        // Create a new image element and set its source to the image URL
                        const img = $('<img>');
                        img.attr('src', image_url);
                        img.css('max-width', '350px');
                        img.css('margin-right', '10px');
                        img.css('margin-bottom', '10px');

                        // Append the image element to the preview container
                        preview.append(img);
                    }
                    else {
                        // Display an error message if the file size is too large
                        alert('File size should not exceed 2 MB');
                        // Clear the input field
                        input.val('');
                        // Break the loop
                        break;
                    }
                }
            });
        });


        $(document).ready(function(){
            const input = $('.image-input-observation');
            const preview = $('.image-preview-observation');

            // Set the input change event to trigger the preview update
            input.on('change', function() {
                // Clear the preview container
                preview.empty();

                if (input.val()) {
                    // Show the preview container
                    preview.show();
                    // Add a border to the preview container
                    preview.css('border', '1px solid #ccc');
                    preview.css('border-radius', '5px');
                    // Justify the images in the container
                    preview.css('display', 'flex');
                    preview.css('justify-content', 'space-between');
                    preview.css('padding', '5px');
                    preview.css('flex-wrap', 'wrap');
                }
                else {
                    // Hide the preview container
                    preview.hide();
                    // Remove the border from the preview container
                    preview.css('border', 'none');
                }

                // Loop through each selected file
                for (let i = 0; i < this.files.length; i++) {
                    const file = this.files[i];
                    const image_url = URL.createObjectURL(file);

                    // Check if the file size is less than or equal to 2 MB
                    if (file.size <= 2000000) {
                        // Create a new image element and set its source to the image URL
                        const img = $('<img>');
                        img.attr('src', image_url);
                        img.css('max-width', '350px');
                        img.css('margin-right', '10px');
                        img.css('margin-bottom', '10px');

                        // Append the image element to the preview container
                        preview.append(img);
                    }
                    else {
                        // Display an error message if the file size is too large
                        alert('File size should not exceed 2 MB');
                        // Clear the input field
                        input.val('');
                        // Break the loop
                        break;
                    }
                }
            });
        });

        $(document).ready(function(){
            const input = $('.image-input-exploration');
            const preview = $('.image-preview-exploration');

            // Set the input change event to trigger the preview update
            input.on('change', function() {
                // Clear the preview container
                preview.empty();

                if (input.val()) {
                    // Show the preview container
                    preview.show();
                    // Add a border to the preview container
                    preview.css('border', '1px solid #ccc');
                    preview.css('border-radius', '5px');
                    // Justify the images in the container
                    preview.css('display', 'flex');
                    preview.css('justify-content', 'space-between');
                    preview.css('padding', '5px');
                    preview.css('flex-wrap', 'wrap');
                }
                else {
                    // Hide the preview container
                    preview.hide();
                    // Remove the border from the preview container
                    preview.css('border', 'none');
                }

                // Loop through each selected file
                for (let i = 0; i < this.files.length; i++) {
                    const file = this.files[i];
                    const image_url = URL.createObjectURL(file);

                    // Check if the file size is less than or equal to 2 MB
                    if (file.size <= 2000000) {
                        // Create a new image element and set its source to the image URL
                        const img = $('<img>');
                        img.attr('src', image_url);
                        img.css('max-width', '350px');
                        img.css('margin-right', '10px');
                        img.css('margin-bottom', '10px');

                        // Append the image element to the preview container
                        preview.append(img);
                    }
                    else {
                        // Display an error message if the file size is too large
                        alert('File size should not exceed 2 MB');
                        // Clear the input field
                        input.val('');
                        // Break the loop
                        break;
                    }
                }
            });
        });


        $(document).ready(function(){
            const input = $('.image-input-tailing');
            const preview = $('.image-preview-tailing');

            // Set the input change event to trigger the preview update
            input.on('change', function() {
                // Clear the preview container
                preview.empty();

                if (input.val()) {
                    // Show the preview container
                    preview.show();
                    // Add a border to the preview container
                    preview.css('border', '1px solid #ccc');
                    preview.css('border-radius', '5px');
                    // Justify the images in the container
                    preview.css('display', 'flex');
                    preview.css('justify-content', 'space-between');
                    preview.css('padding', '5px');
                    preview.css('flex-wrap', 'wrap');
                }
                else {
                    // Hide the preview container
                    preview.hide();
                    // Remove the border from the preview container
                    preview.css('border', 'none');
                }

                // Loop through each selected file
                for (let i = 0; i < this.files.length; i++) {
                    const file = this.files[i];
                    const image_url = URL.createObjectURL(file);

                    // Check if the file size is less than or equal to 2 MB
                    if (file.size <= 2000000) {
                        // Create a new image element and set its source to the image URL
                        const img = $('<img>');
                        img.attr('src', image_url);
                        img.css('max-width', '350px');
                        img.css('margin-right', '10px');
                        img.css('margin-bottom', '10px');

                        // Append the image element to the preview container
                        preview.append(img);
                    }
                    else {
                        // Display an error message if the file size is too large
                        alert('File size should not exceed 2 MB');
                        // Clear the input field
                        input.val('');
                        // Break the loop
                        break;
                    }
                }
            });
        });

        $(document).ready(function(){
            const input = $('.image-input-intrusion');
            const preview = $('.image-preview-intrusion');

            // Set the input change event to trigger the preview update
            input.on('change', function() {
                // Clear the preview container
                preview.empty();

                if (input.val()) {
                    // Show the preview container
                    preview.show();
                    // Add a border to the preview container
                    preview.css('border', '1px solid #ccc');
                    preview.css('border-radius', '5px');
                    // Justify the images in the container
                    preview.css('display', 'flex');
                    preview.css('justify-content', 'space-between');
                    preview.css('padding', '5px');
                    preview.css('flex-wrap', 'wrap');
                }
                else {
                    // Hide the preview container
                    preview.hide();
                    // Remove the border from the preview container
                    preview.css('border', 'none');
                }

                // Loop through each selected file
                for (let i = 0; i < this.files.length; i++) {
                    const file = this.files[i];
                    const image_url = URL.createObjectURL(file);

                    // Check if the file size is less than or equal to 2 MB
                    if (file.size <= 2000000) {
                        // Create a new image element and set its source to the image URL
                        const img = $('<img>');
                        img.attr('src', image_url);
                        img.css('max-width', '350px');
                        img.css('margin-right', '10px');
                        img.css('margin-bottom', '10px');

                        // Append the image element to the preview container
                        preview.append(img);
                    }
                    else {
                        // Display an error message if the file size is too large
                        alert('File size should not exceed 2 MB');
                        // Clear the input field
                        input.val('');
                        // Break the loop
                        break;
                    }
                }
            });
        });


    </script>
    @endpush
</x-backoffice.layout.app-layout>
