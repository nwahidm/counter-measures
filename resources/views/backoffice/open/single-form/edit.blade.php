<x-backoffice.layout.app-layout title="Ubah Single Form Open Case">
    @push('css')
        <style>
            thead {
                background: #f5f4f8;
                text-align: center;
            }
        </style>
    @endpush
    <x-backoffice.toolbar heading="Ubah Single Form Open Case" subheading="" breadcrumb="open-case-create" icon="fas fa-users">
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
                                    <form id="form" action="{{ route('open.singleform.single-form.update', $data->id) }}" method="post" enctype="multipart/form-data" autocomplete="off">
                                        @csrf
                                        @method('PATCH')
                                        <div class="card ">
                                            <div class="card-body">
                                                <label class="fs-3 fw-bold">DETAIL KASUS:</label> <hr>
                                                <div class="row">
                                                    <div class="form-group col-md-6">
                                                        <label for="case_name" class="fs-6 fw-semibold mb-2 required">Nama Kasus</label>
                                                        <input type="text" class="form-control" name="case_name" id="case_name" placeholder="" value="{{$data->case_name}}">
                                                        <p class="text-danger">{{ $errors->first('case_name') }}</p>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="case_date" class="fs-6 fw-semibold mb-2 required">Tanggal Kasus</label>
                                                        <input type="date" class="form-control" name="case_date" id="case_date" value="{{$data->case_date ?? \Carbon\Carbon::now('Asia/Jakarta')->toDateString()}}"required="required">
                                                        <p class="text-danger">{{ $errors->first('case_date') }}</p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label for="case_description" class="fs-6 fw-semibold mb-2">Deksripsi Kasus</label>
                                                        <textarea class="form-control " name="case_description" type="text" rows='4' placeholder="Lengkapi Data...">{{$data->case_description}}</textarea>
                                                        <p class="text-danger">{{ $errors->first('case_description') }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card mb-3">
                                            <div class="card-body">

                                                <div class="row mb-7">
                                                    <div class="form-group col-md-12">
                                                        <label for="satker_id" class="fs-6 fw-semibold mb-2 required">Satuan
                                                                    Kerja</label>
                                                        <select class="form-select form-select-solid select @error('satker_id') is-invalid @enderror" 
                                                        name="satker_id" id="satker_id" data-control="select2" 
                                                        data-hide-search="true" @if(auth()->user()->user_roles != "superadmin") disabled @endif>>
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
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card mb-3">
                                            <div class="card-body">
                                                <label class="fs-3 fw-bold">BIODATA TARGET:</label> <hr>
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label for="nama_target" class="fs-6 fw-semibold mb-2">NIK</label>
                                                        <input type="text" class="form-control" name="nik" id="nik" placeholder="" value="{{$data->target_identity_number}}" >                                                        
                                                        <br>
                                                        <!-- <div class="form-group">
                                                            {{-- <button onclick="processCekNik()" id="buttonProcessNik" type="button" class="btn btn-primary btn-lg btn-block">
                                                                Cari
                                                            </button> --}}
                                                        </div> -->
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="target_name" class="fs-6 fw-semibold mb-2 required">Nama Lengkap Target</label>
                                                        <input type="text" class="form-control" name="target_name" id="target_name" placeholder="" value="{{$data->target_name}}" required="required">
                                                        <p class="text-danger">{{ $errors->first('target_name') }}</p>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="target_religion" class="fs-6 fw-semibold mb-2">Agama</label>
                                                        <input type="text" class="form-control" name="target_religion" id="target_education" placeholder="" value="{{$data->target_education}}" >
                                                        <p class="text-danger">{{ $errors->first('target_religion') }}</p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-6">
                                                        <label for="target_education" class="fs-6 fw-semibold mb-2">Pendidikan Terakhir</label>
                                                            <div class="form-group col-md-12">
                                                                <input type="text" class="form-control" name="target_education" id="target_education" placeholder="" value="{{$data->target_education}}">
                                                                <p class="text-danger">{{ $errors->first('target_education') }}</p>
                                                            </div>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="target_occupation" class="fs-6 fw-semibold mb-2">Pekerjaan</label>
                                                            <div class="form-group col-md-12">
                                                                <input type="text" class="form-control" name="target_occupation" id="target_occupation" placeholder="" value="{{$data->target_occupation}}" >
                                                                <p class="text-danger">{{ $errors->first('target_occupation') }}</p>
                                                            </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="form-group col-md-6">
                                                        <label for="target_gender" class="fs-6 fw-semibold mb-2">Jenis Kelamin</label>
                                                            <div class="form-group col-md-12">
                                                                <input type="text" class="form-control" name="target_gender" id="target_gender" placeholder="" value="{{$data->target_gender}}" >
                                                                <p class="text-danger">{{ $errors->first('target_gender') }}</p>
                                                            </div>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="target_address" class="fs-6 fw-semibold mb-2">Alamat</label>
                                                        <div class="form-group col-md-12">
                                                            <textarea type="text" class="form-control" name="target_address" id="target_address" placeholder="" value="{{$data->target_address}}" ></textarea>
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
                                                    <label for="procedure_type" class="fs-6 fw-semibold mb-2 required">Open Procedure Type</label>
                                                    <select class="form-select form-select-solid select" name="procedure_type" id="procedure_type">
                                                        <option value="">---Pilih Prosedur---</option>
                                                            <option value="all">Semua</option>
                                                            <option value="research">Penelitian</option>
                                                            <option value="interview">Wawancara</option>
                                                            <option value="interrogation">Interogasi</option>
                                                            <option value="elicitation">Elisitasi</option>
                                                        </select>
                                                    </div>
                                                </div>
                                        </div>


                                        <div class="card mb-3">
                                            <div class="card-body">
                                                <div class="card mb-3" id="research">
                                                    <div class="card-body">
                                                        <label class="fs-3 fw-bold">PENELITIAN</label> <hr>
                                                        <div class="row">
                                                            <div class="row mb-7">
                                                                <div class="form-group col-md-6">
                                                                    <label for="research_pendahuluan"
                                                                        class="fs-6 fw-semibold mb-2 required">Pendahuluan</label>
                                                                    <textarea
                                                                        class="form-control form-control-solid @error('research_pendahuluan') is-invalid @enderror"
                                                                        name="research_pendahuluan" id="research_pendahuluan">{{ old('research_pendahuluan', $data->research_lapinsus_pendahuluan) }}</textarea>
                                                                    @error('research_pendahuluan')
                                                                    <p class="text-danger">{{ $message }}</p>
                                                                    @enderror
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="research_data_fakta"
                                                                        class="fs-6 fw-semibold mb-2 required">Data & Fakta</label>
                                                                    <textarea
                                                                        class="form-control form-control-solid @error('research_data_fakta') is-invalid @enderror"
                                                                        name="research_data_fakta" id="research_data_fakta">{{ old('research_data_fakta', $data->research_data_dan_fakta) }}</textarea>
                                                                    @error('research_data_fakta')
                                                                    <p class="text-danger">{{ $message }}</p>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="row mb-7">
                                                                <div class="form-group col-md-6">
                                                                    <label for="research_informasi_diperoleh"
                                                                           class="fs-6 fw-semibold mb-2 required">Informasi Yang Diperoleh</label>
                                                                    <textarea
                                                                        class="form-control form-control-solid @error('research_informasi_diperoleh') is-invalid @enderror"
                                                                        name="research_informasi_diperoleh" id="research_informasi_diperoleh">{{ old('research_informasi_diperoleh', $data->research_informasi_diperoleh) }}</textarea>
                                                                    @error('research_informasi_diperoleh')
                                                                    <p class="text-danger">{{ $message }}</p>
                                                                    @enderror
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="research_sumber_informasi"
                                                                           class="fs-6 fw-semibold mb-2 required">Sumber Informasi</label>
                                                                    <textarea
                                                                        class="form-control form-control-solid @error('research_sumber_informasi') is-invalid @enderror"
                                                                        name="research_sumber_informasi" id="research_sumber_informasi">{{ old('research_sumber_informasi', $data->research_sumber_informasi) }}</textarea>
                                                                    @error('research_sumber_informasi')
                                                                    <p class="text-danger">{{ $message }}</p>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="row mb-7">
                                                                <div class="form-group col-md-6">
                                                                    <label for="research_tren_perkembangan"
                                                                           class="fs-6 fw-semibold mb-2 required">Tren Perkembangan / Perkiraan</label>
                                                                    <textarea
                                                                        class="form-control form-control-solid @error('research_tren_perkembangan') is-invalid @enderror"
                                                                        name="research_tren_perkembangan" id="research_tren_perkembangan">{{ old('research_tren_perkembangan', $data->research_tren_perkembangan) }}</textarea>
                                                                    @error('research_tren_perkembangan')
                                                                    <p class="text-danger">{{ $message }}</p>
                                                                    @enderror
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="research_saran_tindak"
                                                                           class="fs-6 fw-semibold mb-2 required">Saran / Tindak</label>
                                                                    <textarea
                                                                        class="form-control form-control-solid @error('research_saran_tindak') is-invalid @enderror"
                                                                        name="research_saran_tindak" id="research_saran_tindak">{{ old('research_saran_tindak', $data->research_saran_tindak) }}</textarea>
                                                                    @error('saran_tindak')
                                                                    <p class="text-danger">{{ $message }}</p>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="row mb-7">
                                                                <div class="form-group col-md-6">
                                                                    <label for="ancaman"
                                                                        class="fs-6 fw-semibold mb-2 required">Ancaman</label>
                                                                    <textarea
                                                                        class="form-control form-control-solid @error('ancaman') is-invalid @enderror"
                                                                        name="ancaman" id="ancaman">{{ old('ancaman', $data->ancamans) }}</textarea>
                                                                    @error('ancaman')
                                                                    <p class="text-danger">{{ $message }}</p>
                                                                    @enderror
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="gangguan"
                                                                        class="fs-6 fw-semibold mb-2 required">Gangguan</label>
                                                                    <textarea
                                                                        class="form-control form-control-solid @error('gangguan') is-invalid @enderror"
                                                                        name="gangguan" id="gangguan">{{ old('gangguan', $data->gangguan) }}</textarea>
                                                                    @error('gangguan')
                                                                    <p class="text-danger">{{ $message }}</p>
                                                                    @enderror
                                                                </div>
                                                            </div>

                                                            <div class="row mb-7">
                                                                <div class="form-group col-md-6">
                                                                    <label for="hambatan"
                                                                        class="fs-6 fw-semibold mb-2 required">Hambatan</label>
                                                                    <textarea
                                                                        class="form-control form-control-solid @error('hambatan') is-invalid @enderror"
                                                                        name="hambatan" id="hambatan">{{ old('hambatan', $data->hambatan) }}</textarea>
                                                                    @error('hambatan')
                                                                    <p class="text-danger">{{ $message }}</p>
                                                                    @enderror
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="tantangan"
                                                                        class="fs-6 fw-semibold mb-2 required">Tantangan</label>
                                                                    <textarea
                                                                        class="form-control form-control-solid @error('tantangan') is-invalid @enderror"
                                                                        name="tantangan" id="tantangan">{{ old('tantangan', $data->tantangan) }}</textarea>
                                                                    @error('tantangan')
                                                                    <p class="text-danger">{{ $message }}</p>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="row mb-7">
                                                                <div class="form-group col-md-6">
                                                                    <label for="research_dokumen_upload"
                                                                        class="fs-6 fw-semibold mb-2">Upload File
                                                                        Dokument</label>
                                                                    <input
                                                                        class="form-control form-control-solid @error('research_dokumen_upload') is-invalid @enderror"
                                                                        name="research_dokumen_upload"
                                                                        type="file"
                                                                        id="research_dokumen_upload"
                                                                        value="{{ old('research_dokumen_upload') }}">
                                                                    @error('research_dokumen_upload')
                                                                    <p class="text-danger">{{ $message }}</p>
                                                                    @enderror
                                                                </div>
        
                                                                <div class="form-group col-md-4">
                                                                    <label for="research_video_upload"
                                                                        class="fs-6 fw-semibold mb-2 required">Upload File Video</label>
                                                                    <input
                                                                        class="form-control form-control-solid @error('research_video_upload') is-invalid @enderror"
                                                                        name="research_video_upload"
                                                                        type="file"
                                                                        id="research_video_upload"
                                                                        value="{{ old('research_video_upload') }}">
                                                                    @error('research_video_upload')
                                                                    <p class="text-danger">{{ $message }}</p>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            {{-- <div class="card">
                                                                <div class="row mb-12">                                                           
                                                                    <div class="form-group col-md-12">
                                                                        <div class="table-responsive">
                                                                            <h4> Analisis Dokumen </h4>
                                                                            <table id="video_reseacrh" class="table table-striped table-row-bordered gy-5 gs-7 border rounded w-100">
                                                                                <thead>
                                                                                    <tr class="fw-semibold fs-6 text-gray-800">
                                                                                        <th class="min-w-5px">No</th>
                                                                                        <th class="min-w-300px">Analisa</th>
                                                                                        <th class="min-w-300px">Ringkasan</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    @foreach($penelitiandoc as $index => $video)
                                                                                    <tr>
                                                                                        <td>{{ $index + 1 }}</td>
                                                                                        <td>{{ $video->doc_analytics_2 }}</td>
                                                                                        <td>{{ $video->doc_summary_2 }}</td>
                                                                                    </tr>
                                                                                    @endforeach
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-12">                                                           
                                                                    <div class="form-group col-md-12">
                                                                        <div class="table-responsive">
                                                                            <h4> Analisis Video </h4>
                                                                            <table id="video_reseacrh" class="table table-striped table-row-bordered gy-5 gs-7 border rounded w-100">
                                                                                <thead>
                                                                                    <tr class="fw-semibold fs-6 text-gray-800">
                                                                                        <th class="min-w-5px">No</th>
                                                                                        <th class="min-w-5px">Waktu</th>
                                                                                        <th class="min-w-300px">Analisa</th>
                                                                                        <th class="min-w-300px">Ringkasan</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    @foreach($penelitianvideo as $index => $video)
                                                                                    <tr>
                                                                                        <td>{{ $index + 1 }}</td>
                                                                                        <td>{{ $video->video_doc_note }}</td>
                                                                                        <td>{{ $video->video_doc_analytic_2 }}</td>
                                                                                        <td>{{ $video->video_doc_summary_2 }}</td>
                                                                                    </tr>
                                                                                    @endforeach
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div> --}}
                                                        </div>
                                                    </div>
                                                </div>
        
                                                <div class="card mb-3" id="interview">
                                                    <div class="card-body">
                                                        <label class="fs-3 fw-bold">WAWANCARA</label> <hr>
                                                        <div class="row">
                                                            <div class="row mb-7">
                                                                <div class="form-group col-md-6">
                                                                    <label for="interview_interviewer_name"
                                                                        class="fs-6 fw-semibold mb-2 required">Nama Pewawancara</label>
                                                                    <input type="text"
                                                                        class="form-control form-control-solid @error('interview_interviewer_name') is-invalid @enderror"
                                                                        name="interview_interviewer_name" id="interview_interviewer_name"
                                                                        value="{{ old('interview_interviewer_name', $data->interview_interviewer_name) }}">
                                                                    @error('interview_interviewer_name')
                                                                    <p class="text-danger">{{ $message }}</p>
                                                                    @enderror
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="interview_interviewer_schedule"
                                                                        class="fs-6 fw-semibold mb-2 required">Jadwal
                                                                        Pewawancara</label>
                                                                    <input type="date"
                                                                        class="form-control form-control-solid @error('interview_interviewer_schedule') is-invalid @enderror"
                                                                        name="interview_interviewer_schedule" id="interview_interviewer_schedule"
                                                                        value="{{ old('interview_interviewer_schedule', $data->interview_schedule) }}">
                                                                    @error('interview_interviewer_schedule')
                                                                    <p class="text-danger">{{ $message }}</p>
                                                                    @enderror
                                                                </div>
                                                            </div>
        
                                                            <div class="form-group col-md-12">
                                                                <label for="interview_nik" class="fs-6 fw-semibold mb-2 required">NIK</label>
                                                                <input type="text" class="form-control" name="interview_nik" id="interview_nik" placeholder="" value="{{ old('interview_nik', $data->interview_target_identity_number) }}">                                                        
                                                                <br>
                                                            </div>
                                                            <div class="row mb-12">
                                                                <div class="form-group col-md-6">
                                                                    <label for="interview_target_name" class="fs-6 fw-semibold mb-2 required">Nama Diwawancara</label>
                                                                    <input type="text" class="form-control form-control-solid @error('interview_target_name') is-invalid @enderror"
                                                                        name="interview_target_name" id="interview_target_name" value="{{ old('interview_target_name', $data->interview_target_name) }}">
                                                                    @error('interview_target_name')
                                                                    <p class="text-danger">{{ $message }}</p>
                                                                    @enderror
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="interview_gender" class="fs-6 fw-semibold mb-2">Jenis Kelamin Target</label>
                                                                    <input type="text" class="form-control form-control-solid @error('interview_gender') is-invalid @enderror"
                                                                    name="interview_gender" id="interview_gender" value="{{ old('interview_gender', $data->interview_target_gender) }}">
                                                                    @error('interview_gender')
                                                                    <p class="text-danger">{{ $message }}</p>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="row mb-12">
                                                                <div class="form-group col-md-6">
                                                                    <label for="interview_occupation" class="fs-6 fw-semibold mb-2">Pekerjaan</label>
                                                                    <input type="text" class="form-control form-control-solid @error('interview_occupation') is-invalid @enderror"
                                                                    name="interview_occupation" id="interview_occupation" value="{{ old('interview_occupation', $data->interview_target_occupation) }}">
                                                                    @error('interview_occupation')
                                                                    <p class="text-danger">{{ $message }}</p>
                                                                    @enderror
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="interview_education" class="fs-6 fw-semibold mb-2">Pendidikan Terakhir</label>
                                                                    <input type="text" class="form-control form-control-solid @error('interview_education') is-invalid @enderror"
                                                                    name="interview_education" id="interview_education" value="{{ old('interview_education', $data->interview_target_education) }}">
                                                                    @error('interview_education')
                                                                    <p class="text-danger">{{ $message }}</p>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="row mb-12">
                                                                <div class="form-group col-md-6">
                                                                    <label for="interview_religion" class="fs-6 fw-semibold mb-2">Agama</label>
                                                                    <input type="text" class="form-control form-control-solid @error('interview_religion') is-invalid @enderror"
                                                                    name="interview_religion" id="interview_religion" value="{{ old('interview_religion', $data->interview_target_religion) }}">
                                                                    @error('interview_religion')
                                                                    <p class="text-danger">{{ $message }}</p>
                                                                    @enderror
                                                                </div>
        
                                                                <div class="row">
                                                                    <div class="form-group col-md-12">
                                                                        <label for="interview_target_photo" class="fs-6 fw-semibold mb-2 ">Foto (Multiple Input Max. 2MB Per Foto)</label>
                                                                        <input id="interview_target_photo" type="file" class="form-control image-input-interview" name="interview_target_photo[]" accept=".jpg,.jpeg,.png" autocomplete="off" multiple>
                                                                            <p class="text-danger">{{ $errors->first('interview_target_photo') }}</p>
                                                                            <div class="image-preview-interview container"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                                
                                                               
                                                            </div>
        
                                                            <div class="row mb-7">
                                                                
                                                                <div class="form-group col-md-12">
                                                                    <label for="interview_address"
                                                                        class="fs-6 fw-semibold mb-2">Alamat </label>
                                                                    <textarea
                                                                        class="form-control form-control-solid @error('interview_address') is-invalid @enderror"
                                                                        name="interview_address"
                                                                        id="interview_address">{{ old('interview_address',$data->interview_target_address) }}</textarea>
                                                                    @error('interview_address')
                                                                    <p class="text-danger">{{ $message }}</p>
                                                                    @enderror
                                                                </div>
                                                            </div>
        
        
                                                            <div class="row mb-7">
                                                                <div class="form-group col-md-6">
                                                                    <label for="interview_upload_dokumen_wawancara"
                                                                        class="fs-6 fw-semibold mb-2 required">Dokumen Wawancara</label>
                                                                    <input
                                                                        class="form-control form-control-solid @error('upload_dokumen_wawancara') is-invalid @enderror"
                                                                        name="interview_upload_dokumen_wawancara"
                                                                        type="file"
                                                                        id="interview_upload_dokumen_wawancara"
                                                                        value="{{ old('interview_upload_dokumen_wawancara') }}">
                                                                    @error('interview_upload_dokumen_wawancara')
                                                                    <p class="text-danger">{{ $message }}</p>
                                                                    @enderror
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="interview_upload_video_wawancara"
                                                                        class="fs-6 fw-semibold mb-2 required">Video Wawancara</label>
                                                                    <input
                                                                        class="form-control form-control-solid @error('interview_upload_video_wawancara') is-invalid @enderror"
                                                                        name="interview_upload_video_wawancara"
                                                                        type="file"
                                                                        id="interview_upload_video_wawancara"
                                                                        value="{{ old('interview_upload_video_wawancara') }}">
                                                                    @error('interview_upload_video_wawancara')
                                                                    <p class="text-danger">{{ $message }}</p>
                                                                    @enderror
                                                                </div>
                                                            </div>
        
                                                            <div class="row mb-7">
                                                                
                                                                <div class="form-group col-md-12">
                                                                    <label for="interview_saran_dan_tindak_lanjut"
                                                                        class="fs-6 fw-semibold mb-2 required">Saran Tindak
                                                                        Lanjut</label>
                                                                    <textarea
                                                                        class="form-control form-control-solid @error('interview_saran_dan_tindak_lanjut') is-invalid @enderror"
                                                                        name="interview_saran_dan_tindak_lanjut"
                                                                        id="interview_saran_dan_tindak_lanjut">{{ old('interview_saran_dan_tindak_lanjut',$data->interview_saran_dan_tindak_lanjut) }}</textarea>
                                                                    @error('interview_saran_dan_tindak_lanjut')
                                                                    <p class="text-danger">{{ $message }}</p>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            {{-- <div class="card">
                                                                <div class="row mb-12">                                                           
                                                                    <div class="form-group col-md-12">
                                                                        <div class="table-responsive">
                                                                            <h4> Analisis Dokumen </h4>
                                                                            <table id="video_reseacrh" class="table table-striped table-row-bordered gy-5 gs-7 border rounded w-100">
                                                                                <thead>
                                                                                    <tr class="fw-semibold fs-6 text-gray-800">
                                                                                        <th class="min-w-5px">No</th>
                                                                                        <th class="min-w-300px">Analisa</th>
                                                                                        <th class="min-w-300px">Ringkasan</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    @foreach($interviewdoc as $index => $video)
                                                                                    <tr>
                                                                                        <td>{{ $index + 1 }}</td>
                                                                                        <td>{{ $video->doc_analytics_2 }}</td>
                                                                                        <td>{{ $video->doc_summary_2 }}</td>
                                                                                    </tr>
                                                                                    @endforeach
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-12">                                                           
                                                                    <div class="form-group col-md-12">
                                                                        <div class="table-responsive">
                                                                            <h4> Analisis Video </h4>
                                                                            <table id="video_reseacrh" class="table table-striped table-row-bordered gy-5 gs-7 border rounded w-100">
                                                                                <thead>
                                                                                    <tr class="fw-semibold fs-6 text-gray-800">
                                                                                        <th class="min-w-5px">No</th>
                                                                                        <th class="min-w-5px">Waktu</th>
                                                                                        <th class="min-w-300px">Analisa</th>
                                                                                        <th class="min-w-300px">Ringkasan</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    @foreach($interviewvideo as $index => $video)
                                                                                    <tr>
                                                                                        <td>{{ $index + 1 }}</td>
                                                                                        <td>{{ $video->video_doc_note }}</td>
                                                                                        <td>{{ $video->video_doc_analytic_2 }}</td>
                                                                                        <td>{{ $video->video_doc_summary_2 }}</td>
                                                                                    </tr>
                                                                                    @endforeach
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div> --}}
                                                        </div>
                                                    </div>
                                                </div>
        
        
                                                <div class="card mb-3" id="interrogation">
                                                    <div class="card-body">
                                                        <label class="fs-3 fw-bold">INTEROGASI</label> <hr>
                                                        <div class="row mb-12">
                                                            <div class="form-group col-md-12">
                                                                <label for="interrogation_pegawai" class="fs-6 fw-semibold mb-2">Jaksa Peminta Keterangan</label>
                                                                <select class="form-control form-control-solid select" id="interrogation_pegawai" name="interrogation_pegawai[]" multiple="multiple">
                                                                    @foreach($interrogation_listPegawai as $pegawai)
                                                                        @if($data->interrogation_jaksa)
                                                                            <option value="{{ $pegawai['nip'] }}" {{ in_array($pegawai['nip'], json_decode($data->interrogation_jaksa)) ? 'selected' : '' }}>{{ $pegawai['text'] }}</option>
                                                                        @else
                                                                            <option value="{{ $pegawai['nip'] }}" >{{ $pegawai['text'] }}</option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                                @error('interrogation_pegawai')
                                                                <p class="text-danger">{{ $message }}</p>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                           
                                                            <div class="form-group col-md-12">
                                                                <label for="interrogation_target_identity_number" class="fs-6 fw-semibold mb-2 required">NIK</label>
                                                                <input type="text" class="form-control form-control-solid @error('interrogation_target_identity_number') is-invalid @enderror" name="interrogation_target_identity_number" id="interrogation_target_identity_number" value="{{ old('interrogation_target_identity_number', $data->interrogation_target_identity_number) }}">
                                                                @error('interrogation_target_identity_number')
                                                                <p class="text-danger">{{ $message }}</p>
                                                                @enderror
                                                            </div>
                                                            <div class="row mb-12">
                                                                <div class="form-group col-md-6">
                                                                    <label for="interrogation_target_name" class="fs-6 fw-semibold mb-2 required">Nama Diwawancara</label>
                                                                    <input type="text" class="form-control form-control-solid @error('interrogation_target_name') is-invalid @enderror" name="interrogation_target_name" id="interrogation_target_name" value="{{ old('interrogation_target_name', $data->interrogation_target_name) }}">
                                                                    @error('interrogation_target_name')
                                                                    <p class="text-danger">{{ $message }}</p>
                                                                    @enderror
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="interrogation_gender" class="fs-6 fw-semibold mb-2 required">Jenis Kelamin Target</label>
                                                                    <input type="text" class="form-control form-control-solid @error('interrogation_gender') is-invalid @enderror" name="interrogation_gender" id="interrogation_gender" value="{{ old('interrogation_gender', $data->interrogation_target_gender) }}">
                                                                    @error('interrogation_gender')
                                                                    <p class="text-danger">{{ $message }}</p>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="row mb-12">
                                                                <div class="form-group col-md-6">
                                                                    <label for="interrogation_occupation" class="fs-6 fw-semibold mb-2 required">Pekerjaan</label>
                                                                    <input type="text" class="form-control form-control-solid @error('interrogation_occupation') is-invalid @enderror" name="interrogation_occupation" id="interrogation_occupation" value="{{ old('interrogation_occupation', $data->interrogation_target_occupation) }}">
                                                                    @error('interrogation_occupation')
                                                                    <p class="text-danger">{{ $message }}</p>
                                                                    @enderror
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="interrogation_education" class="fs-6 fw-semibold mb-2 required">Pendidikan Terakhir</label>
                                                                    <input type="text" class="form-control form-control-solid @error('interrogation_education') is-invalid @enderror" name="interrogation_education" id="interrogation_education" value="{{ old('interrogation_education', $data->interrogation_target_education) }}">
                                                                    @error('interrogation_education')
                                                                    <p class="text-danger">{{ $message }}</p>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="row mb-12">
                                                                <div class="form-group col-md-6">
                                                                    <label for="interrogation_target_religion" class="fs-6 fw-semibold mb-2 required">Agama</label>
                                                                    <input type="text" class="form-control form-control-solid @error('interrogation_target_religion') is-invalid @enderror" name="interrogation_target_religion" id="interrogation_target_religion" value="{{ old('interrogation_target_religion', $data->interrogation_target_religion) }}">
                                                                    @error('interrogation_target_religion')
                                                                    <p class="text-danger">{{ $message }}</p>
                                                                    @enderror
                                                                </div>
                                                            </div>
        
                                                            <div class="row">
                                                                <div class="form-group col-md-12">
                                                                    <label for="interrogation_target_photo" class="fs-6 fw-semibold mb-2">Foto (Multiple Input Max. 2MB Per Foto)</label>
                                                                    <input id="interrogation_target_photo" type="file" class="form-control image-input-interrogation" name="interrogation_target_photo[]" accept=".jpg,.jpeg,.png" autocomplete="off" multiple>
                                                                    <p class="text-danger">{{ $errors->first('interrogation_target_photo') }}</p>
                                                                    <div class="image-preview-interrogation container"></div>
                                                                </div>
                                                            </div>
        
                                                            <div class="row mb-7">
                                                                <div class="form-group col-md-12">
                                                                    <label for="interrogation_address" class="fs-6 fw-semibold mb-2 required">Alamat</label>
                                                                    <textarea class="form-control form-control-solid @error('interrogation_address') is-invalid @enderror" name="interrogation_address" id="interrogation_address">{{ old('interrogation_address', $data->interrogation_target_address) }}</textarea>
                                                                    @error('interrogation_address')
                                                                    <p class="text-danger">{{ $message }}</p>
                                                                    @enderror
                                                                </div>
                                                            </div>
        
                                                            <div class="row mb-7">
                                                                <div class="form-group col-md-6">
                                                                    <label for="interrogation_upload_dokumen_wawancara" class="fs-6 fw-semibold mb-2 required">Dokumen Interogasi</label>
                                                                    <input class="form-control form-control-solid @error('interrogation_upload_dokumen_wawancara') is-invalid @enderror" name="interrogation_upload_dokumen_wawancara" type="file" id="interrogation_upload_dokumen_wawancara">
                                                                    @error('interrogation_upload_dokumen_wawancara')
                                                                    <p class="text-danger">{{ $message }}</p>
                                                                    @enderror
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="interrogation_upload_video_wawancara" class="fs-6 fw-semibold mb-2 required">Video Interogasi</label>
                                                                    <input class="form-control form-control-solid @error('interrogation_upload_video_wawancara') is-invalid @enderror" name="interrogation_upload_video_wawancara" type="file" id="interrogation_upload_video_wawancara">
                                                                    @error('interrogation_upload_video_wawancara')
                                                                    <p class="text-danger">{{ $message }}</p>
                                                                    @enderror
                                                                </div>
                                                            </div>
        
                                                            <div class="row mb-7">
                                                                <div class="form-group col-md-6">
                                                                    <label for="interrogation_target_identification" class="fs-6 fw-semibold mb-2 required">Identifikasi Target</label>
                                                                    <textarea class="form-control form-control-solid @error('interrogation_target_identification') is-invalid @enderror" name="interrogation_target_identification" id="interrogation_target_identification">{{ old('interrogation_target_identification', $data->interrogation_target_identification) }}</textarea>
                                                                    @error('interrogation_target_identification')
                                                                    <p class="text-danger">{{ $message }}</p>
                                                                    @enderror
                                                                </div>
        
                                                                <div class="form-group col-md-6">
                                                                    <label for="interrogation_result_achievement" class="fs-6 fw-semibold mb-2 required">Hasil yang Dicapai</label>
                                                                    <textarea class="form-control form-control-solid @error('interrogation_result_achievement') is-invalid @enderror" name="interrogation_result_achievement" id="interrogation_result_achievement">{{ old('interrogation_result_achievement', $data->interrogation_result_achievement) }}</textarea>
                                                                    @error('interrogation_result_achievement')
                                                                    <p class="text-danger">{{ $message }}</p>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            {{-- <div class="card">
                                                                <div class="row mb-12">                                                           
                                                                    <div class="form-group col-md-12">
                                                                        <div class="table-responsive">
                                                                            <h4> Analisis Dokumen </h4>
                                                                            <table id="video_reseacrh" class="table table-striped table-row-bordered gy-5 gs-7 border rounded w-100">
                                                                                <thead>
                                                                                    <tr class="fw-semibold fs-6 text-gray-800">
                                                                                        <th class="min-w-5px">No</th>
                                                                                        <th class="min-w-300px">Analisa</th>
                                                                                        <th class="min-w-300px">Ringkasan</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    @foreach($interrogationdoc as $index => $video)
                                                                                    <tr>
                                                                                        <td>{{ $index + 1 }}</td>
                                                                                        <td>{{ $video->doc_analytics_2 }}</td>
                                                                                        <td>{{ $video->doc_summary_2 }}</td>
                                                                                    </tr>
                                                                                    @endforeach
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-12">                                                           
                                                                    <div class="form-group col-md-12">
                                                                        <div class="table-responsive">
                                                                            <h4> Analisis Video </h4>
                                                                            <table id="video_reseacrh" class="table table-striped table-row-bordered gy-5 gs-7 border rounded w-100">
                                                                                <thead>
                                                                                    <tr class="fw-semibold fs-6 text-gray-800">
                                                                                        <th class="min-w-5px">No</th>
                                                                                        <th class="min-w-5px">Waktu</th>
                                                                                        <th class="min-w-300px">Analisa</th>
                                                                                        <th class="min-w-300px">Ringkasan</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    @foreach($interrogationvideo as $index => $video)
                                                                                    <tr>
                                                                                        <td>{{ $index + 1 }}</td>
                                                                                        <td>{{ $video->video_doc_note }}</td>
                                                                                        <td>{{ $video->video_doc_analytic_2 }}</td>
                                                                                        <td>{{ $video->video_doc_summary_2 }}</td>
                                                                                    </tr>
                                                                                    @endforeach
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div> --}}
        
                                                        </div>
                                                    </div>
                                                </div>
        
        
                                                <div class="card mb-3" id="elicitation">
                                                    <div class="card-body">
                                                        <label class="fs-3 fw-bold">Elisitasi/ Pemancingan</label> <hr>
                                                        <div class="row">
                                                            <div class="row mb-7">
                                                                <div class="form-group col-md-6">
                                                                    <label for="elicitation_interviewer_name"
                                                                        class="fs-6 fw-semibold mb-2 required">Nama Pewawancara</label>
                                                                    <input type="text"
                                                                        class="form-control form-control-solid @error('elicitation_interviewer_name') is-invalid @enderror"
                                                                        name="elicitation_interviewer_name" id="elicitation_interviewer_name"
                                                                        value="{{ old('elicitation_interviewer_name', $data->elicitation_interviewer_name) }}">
                                                                    @error('elicitation_interviewer_name')
                                                                    <p class="text-danger">{{ $message }}</p>
                                                                    @enderror
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="elicitation_interview_schedule"
                                                                        class="fs-6 fw-semibold mb-2 required">Jadwal
                                                                        Pewawancara</label>
                                                                    <input type="date"
                                                                        class="form-control form-control-solid @error('elicitation_interview_schedule') is-invalid @enderror"
                                                                        name="elicitation_interview_schedule" id="elicitation_interview_schedule"
                                                                        value="{{ old('elicitation_interview_schedule', $data->elicitation_interview_schedule) }}">
                                                                    @error('elicitation_interview_schedule')
                                                                    <p class="text-danger">{{ $message }}</p>
                                                                    @enderror
                                                                </div>
                                                            </div>
        
                                                            <div class="form-group col-md-12">
                                                                <label for="elicitation_interview_target_identity_number" class="fs-6 fw-semibold mb-2 required">NIK</label>
                                                                <input type="text" class="form-control" name="elicitation_interview_target_identity_number" id="elicitation_interview_target_identity_number" placeholder="" value="{{ old('elicitation_interview_target_identity_number', $data->elicitation_interview_target_identity_number) }}" >                                                        
                                                                <br>
                                                                <!-- {{-- <div class="form-group">
                                                                    <button onclick="processCekNikElicitation()" id="buttonProcessNikElicitation" type="button" class="btn btn-primary btn-lg btn-block">
                                                                        Cari
                                                                    </button>
                                                                </div> --}} -->
                                                            </div>
                                                            <div class="row mb-12">
                                                                <div class="form-group col-md-6">
                                                                    <label for="elicitation_interview_target_name" class="fs-6 fw-semibold mb-2 required">Nama Diwawancara</label>
                                                                    <input type="text" class="form-control form-control-solid @error('elicitation_interview_target_name') is-invalid @enderror"
                                                                        name="elicitation_interview_target_name" id="elicitation_interview_target_name" value="{{ old('elicitation_interview_target_name', $data->elicitation_interview_target_name) }}">
                                                                    @error('elicitation_interview_target_name')
                                                                    <p class="text-danger">{{ $message }}</p>
                                                                    @enderror
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="elicitation_target_gender" class="fs-6 fw-semibold mb-2">Jenis Kelamin Target</label>
                                                                    <input type="text" class="form-control form-control-solid @error('elicitation_target_gender') is-invalid @enderror"
                                                                    name="elicitation_target_gender" id="elicitation_target_gender" value="{{ old('elicitation_target_gender', $data->elicitation_target_gender) }}">
                                                                    @error('elicitation_target_gender')
                                                                    <p class="text-danger">{{ $message }}</p>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="row mb-12">
                                                                <div class="form-group col-md-6">
                                                                    <label for="elicitation_target_occupation" class="fs-6 fw-semibold mb-2">Pekerjaan</label>
                                                                    <input type="text" class="form-control form-control-solid @error('elicitation_target_occupation') is-invalid @enderror"
                                                                    name="elicitation_target_occupation" id="elicitation_target_occupation" value="{{ old('elicitation_target_occupation', $data->elicitation_target_occupation) }}">
                                                                    @error('elicitation_target_occupation')
                                                                    <p class="text-danger">{{ $message }}</p>
                                                                    @enderror
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="elicitation_target_education" class="fs-6 fw-semibold mb-2">Pendidikan Terakhir</label>
                                                                    <input type="text" class="form-control form-control-solid @error('elicitation_target_education') is-invalid @enderror"
                                                                    name="elicitation_target_education" id="elicitation_target_education" value="{{ old('elicitation_target_education', $data->elicitation_target_education) }}">
                                                                    @error('elicitation_target_education')
                                                                    <p class="text-danger">{{ $message }}</p>
                                                                    @enderror
                                                                </div>
                                                            </div>
        
                                                            <div class="row mb-7">
                                                                
                                                                <div class="form-group col-md-12">
                                                                    <label for="elicitation_target_address"
                                                                        class="fs-6 fw-semibold mb-2">Alamat </label>
                                                                    <textarea
                                                                        class="form-control form-control-solid @error('elicitation_target_address') is-invalid @enderror"
                                                                        name="elicitation_target_address"
                                                                        id="elicitation_target_address">{{ old('elicitation_target_address', $data->elicitation_target_address) }}</textarea>
                                                                    @error('elicitation_target_address')
                                                                    <p class="text-danger">{{ $message }}</p>
                                                                    @enderror
                                                                </div>
                                                            </div>
        
                                                            <div class="row">
                                                                    <div class="form-group col-md-12">
                                                                        <label for="elicitation_target_photo" class="fs-6 fw-semibold mb-2 ">Foto (Multiple Input Max. 2MB Per Foto)</label>
                                                                        <input id="elicitation_target_photo" type="file" class="form-control image-input-elicitation" name="elicitation_target_photo[]" accept=".jpg,.jpeg,.png" autocomplete="off" multiple>
                                                                            <p class="text-danger">{{ $errors->first('image') }}</p>
                                                                            <div class="image-preview-elicitation container"></div>
                                                                    </div>
                                                            </div>
        
                                                            <div class="row mb-12">
                                                                <div class="form-group col-md-6">
                                                                    <label for="elicitation_target_religion" class="fs-6 fw-semibold mb-2">Agama</label>
                                                                    <input type="text" class="form-control form-control-solid @error('elicitation_target_religion') is-invalid @enderror"
                                                                    name="elicitation_target_religion" id="elicitation_target_religion" value="{{ old('elicitation_target_religion', $data->elicitation_target_religion) }}">
                                                                    @error('elicitation_target_religion')
                                                                    <p class="text-danger">{{ $message }}</p>
                                                                    @enderror
                                                                </div>
                                                               
                                                            </div>
        
        
                                                            <div class="row mb-7">
                                                                <div class="form-group col-md-6">
                                                                    <label for="elicitation_upload_dokumen_wawancara"
                                                                        class="fs-6 fw-semibold mb-2 required">Dokumen Wawancara</label>
                                                                    <input
                                                                        class="form-control form-control-solid @error('upload_dokumen_wawancara') is-invalid @enderror"
                                                                        name="elicitation_upload_dokumen_wawancara"
                                                                        type="file"
                                                                        id="elicitation_upload_dokumen_wawancara"
                                                                        value="{{ old('elicitation_upload_dokumen_wawancara') }}">
                                                                    @error('elicitation_upload_dokumen_wawancara')
                                                                    <p class="text-danger">{{ $message }}</p>
                                                                    @enderror
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="elicitation_upload_video_wawancara"
                                                                        class="fs-6 fw-semibold mb-2 required">Video Wawancara</label>
                                                                    <input
                                                                        class="form-control form-control-solid @error('elicitation_upload_video_wawancara') is-invalid @enderror"
                                                                        name="elicitation_upload_video_wawancara"
                                                                        type="file"
                                                                        id="elicitation_upload_video_wawancara"
                                                                        value="{{ old('elicitation_upload_video_wawancara') }}">
                                                                    @error('elicitation_upload_video_wawancara')
                                                                    <p class="text-danger">{{ $message }}</p>
                                                                    @enderror
                                                                </div>
                                                            </div>
        
                                                            <div class="row mb-7">
                                                                <div class="form-group col-md-6">
                                                                    <label for="elicitation_pendahuluan"
                                                                        class="fs-6 fw-semibold mb-2 required">Pendahuluan</label>
                                                                    <textarea
                                                                        class="form-control form-control-solid @error('elicitation_pendahuluan') is-invalid @enderror"
                                                                        name="elicitation_pendahuluan"
                                                                        id="elicitation_pendahuluan">{{ old('elicitation_pendahuluan', $data->elicitation_pendahuluan) }}</textarea>
                                                                    @error('elicitation_pendahuluan')
                                                                    <p class="text-danger">{{ $message }}</p>
                                                                    @enderror
                                                                </div>
        
                                                                <div class="form-group col-md-6">
                                                                    <label for="elicitation_pelaksanaan_kegiatan"
                                                                        class="fs-6 fw-semibold mb-2 required">Pelaksanaan Kegiatan</label>
                                                                    <textarea
                                                                        class="form-control form-control-solid @error('elicitation_pelaksanaan_kegiatan') is-invalid @enderror"
                                                                        name="elicitation_pelaksanaan_kegiatan"
                                                                        id="elicitation_pelaksanaan_kegiatan">{{ old('elicitation_pelaksanaan_kegiatan', $data->elicitation_pelaksanaan_kegiatan) }}</textarea>
                                                                    @error('elicitation_pelaksanaan_kegiatan')
                                                                    <p class="text-danger">{{ $message }}</p>
                                                                    @enderror
                                                                </div>
        
                                                                <div class="form-group col-md-6">
                                                                    <label for="elicitation_kendala"
                                                                        class="fs-6 fw-semibold mb-2 required">Kendala</label>
                                                                    <textarea
                                                                        class="form-control form-control-solid @error('elicitation_kendala') is-invalid @enderror"
                                                                        name="elicitation_kendala"
                                                                        id="elicitation_kendala">{{ old('elicitation_kendala', $data->elicitation_kendala) }}</textarea>
                                                                    @error('elicitation_kendala')
                                                                    <p class="text-danger">{{ $message }}</p>
                                                                    @enderror
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="elicitation_analisa"
                                                                        class="fs-6 fw-semibold mb-2 required">Analisa</label>
                                                                    <textarea
                                                                        class="form-control form-control-solid @error('elicitation_analisa') is-invalid @enderror"
                                                                        name="elicitation_analisa"
                                                                        id="elicitation_analisa">{{ old('elicitation_analisa', $data->elicitation_analisa) }}</textarea>
                                                                    @error('elicitation_analisa')
                                                                    <p class="text-danger">{{ $message }}</p>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            {{-- <div class="card">
                                                                <div class="row mb-12">                                                           
                                                                    <div class="form-group col-md-12">
                                                                        <div class="table-responsive">
                                                                            <h4> Analisis Dokumen </h4>
                                                                            <table id="video_reseacrh" class="table table-striped table-row-bordered gy-5 gs-7 border rounded w-100">
                                                                                <thead>
                                                                                    <tr class="fw-semibold fs-6 text-gray-800">
                                                                                        <th class="min-w-5px">No</th>
                                                                                        <th class="min-w-300px">Analisa</th>
                                                                                        <th class="min-w-300px">Ringkasan</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    @foreach($elicitationdoc as $index => $video)
                                                                                    <tr>
                                                                                        <td>{{ $index + 1 }}</td>
                                                                                        <td>{{ $video->doc_analytics_2 }}</td>
                                                                                        <td>{{ $video->doc_summary_2 }}</td>
                                                                                    </tr>
                                                                                    @endforeach
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-12">                                                           
                                                                    <div class="form-group col-md-12">
                                                                        <div class="table-responsive">
                                                                            <h4> Analisis Video </h4>
                                                                            <table id="video_reseacrh" class="table table-striped table-row-bordered gy-5 gs-7 border rounded w-100">
                                                                                <thead>
                                                                                    <tr class="fw-semibold fs-6 text-gray-800">
                                                                                        <th class="min-w-5px">No</th>
                                                                                        <th class="min-w-5px">Waktu</th>
                                                                                        <th class="min-w-300px">Analisa</th>
                                                                                        <th class="min-w-300px">Ringkasan</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    @foreach($elicitationvideo as $index => $video)
                                                                                    <tr>
                                                                                        <td>{{ $index + 1 }}</td>
                                                                                        <td>{{ $video->video_doc_note }}</td>
                                                                                        <td>{{ $video->video_doc_analytic_2 }}</td>
                                                                                        <td>{{ $video->video_doc_summary_2 }}</td>
                                                                                    </tr>
                                                                                    @endforeach
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div> --}}
        
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
    <script>
        $(document).ready(function() {
            $("#video_reseacrh").DataTable({
            });
        });
    </script>
    <script type="module">
        document.onreadystatechange = function () {
            $('#interrogation_pegawai').select2({
                placeholder: 'Pilih Pegawai',
                allowClear: true,
                multiple: true
            });
            ClassicEditor.create(document.querySelector('#ancaman'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                .catch(error => {
                    console.error(error);
            });
            ClassicEditor.create(document.querySelector('#gangguan'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                .catch(error => {
                    console.error(error);
            });
            ClassicEditor.create(document.querySelector('#hambatan'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                .catch(error => {
                    console.error(error);
            });
            ClassicEditor.create(document.querySelector('#tantangan'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                .catch(error => {
                    console.error(error);
            });
            ClassicEditor.create(document.querySelector('#case_description'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                .catch(error => {
                    console.error(error);
            });

            ClassicEditor.create(document.querySelector('#research_pendahuluan'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                .catch(error => {
                    console.error(error);
            });

            ClassicEditor.create(document.querySelector('#research_data_fakta'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                .catch(error => {
                    console.error(error);
            });

            ClassicEditor.create(document.querySelector('#research_informasi_diperoleh'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                .catch(error => {
                    console.error(error);
            });

            ClassicEditor.create(document.querySelector('#research_sumber_informasi'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                .catch(error => {
                    console.error(error);
            });
            ClassicEditor.create(document.querySelector('#research_tren_perkembangan'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                .catch(error => {
                    console.error(error);
            });
            ClassicEditor.create(document.querySelector('#research_saran_tindak'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                .catch(error => {
                    console.error(error);
            });
            ClassicEditor.create(document.querySelector('#research_aght_description'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                .catch(error => {
                    console.error(error);
            });
            ClassicEditor.create(document.querySelector('#interview_saran_dan_tindak_lanjut'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                .catch(error => {
                    console.error(error);
            });
            ClassicEditor.create(document.querySelector('#interview_keterangan'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                .catch(error => {
                    console.error(error);
            });
            ClassicEditor.create(document.querySelector('#interrogation_target_identification'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                .catch(error => {
                    console.error(error);
            });
            ClassicEditor.create(document.querySelector('#interrogation_result_achievement'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                .catch(error => {
                    console.error(error);
            });
            ClassicEditor.create(document.querySelector('#elicitation_pendahuluan'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                .catch(error => {
                    console.error(error);
            });
            ClassicEditor.create(document.querySelector('#elicitation_pelaksanaan_kegiatan'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                .catch(error => {
                    console.error(error);
            });
            ClassicEditor.create(document.querySelector('#elicitation_kendala'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                .catch(error => {
                    console.error(error);
            });
            ClassicEditor.create(document.querySelector('#elicitation_analisa'),{
                            ckfinder: {
                                uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                            }
                        })
                .catch(error => {
                    console.error(error);
            });
        };
    </script>
    <script >
        var researchDiv = document.getElementById('research');
        researchDiv.style.display = 'none';
        var interviewDiv = document.getElementById('interview');
        interviewDiv.style.display = 'none';
        var interrogationDiv = document.getElementById('interrogation');
        interrogationDiv.style.display = 'none';
        var elicitationnDiv = document.getElementById('elicitation');
        elicitationnDiv.style.display = 'none';

        document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('procedure_type').addEventListener('change', function() {
            console.log('Selected value:', this.value);
            var researchDiv = document.getElementById('research');
            var interviewDiv = document.getElementById('interview');
            var interrogationDiv = document.getElementById('interrogation');
            var elicitationDiv = document.getElementById('elicitation');
            
            if (this.value === '') {
                researchDiv.style.display = 'none';
                interviewDiv.style.display = 'none';
                interrogationDiv.style.display = 'none';
                elicitationDiv.style.display = 'none';
                
            }
            if (this.value === 'all') {
                researchDiv.style.display = 'block';
                interviewDiv.style.display = 'block';
                interrogationDiv.style.display = 'block';
                elicitationDiv.style.display = 'block';
                
            } 

            if (this.value === 'research') {
                researchDiv.style.display = 'block';
                interviewDiv.style.display = 'none';
                interrogationDiv.style.display = 'none';
                elicitationDiv.style.display = 'none';
            } 

            if (this.value === 'interview') {
                researchDiv.style.display = 'none';
                interviewDiv.style.display = 'block';
                interrogationDiv.style.display = 'none';
                elicitationDiv.style.display = 'none';
                
            }

            if (this.value === 'interrogation') {
                researchDiv.style.display = 'none';
                interviewDiv.style.display = 'none';
                interrogationDiv.style.display = 'block';
                elicitationDiv.style.display = 'none';
                
            }

            if (this.value === 'elicitation') {
                researchDiv.style.display = 'none';
                interviewDiv.style.display = 'none';
                interrogationDiv.style.display = 'none';
                elicitationDiv.style.display = 'block';
                
            }

            // Add any additional actions you want to take when the value changes here
        });
        var procedureTypeSelect = document.getElementById('procedure_type');
        procedureTypeSelect.value = '{{ $data->open_procedure_type }}';
        procedureTypeSelect.dispatchEvent(new Event('change'));
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
                url: "{{ route('open.kependudukan.ceknik', ['nik' => 'NIK_PLACEHOLDER']) }}".replace('NIK_PLACEHOLDER', nik),
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
            const input = $('.image-input-interview');
            const preview = $('.image-preview-interview');

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
            const input = $('.image-input-interrogation');
            const preview = $('.image-preview-interrogation');

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
            const input = $('.image-input-elicitation');
            const preview = $('.image-preview-elicitation');

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

        document.onreadystatechange = function () {
            if (document.readyState === 'complete') {
                // $('.select').select2();
                $('#interrogation_pegawai').select2({
                    placeholder: 'Pilih Pegawai',
                    allowClear: true,
                    multiple: true
                });
            }
        }
    </script>
    @endpush
</x-backoffice.layout.app-layout>
