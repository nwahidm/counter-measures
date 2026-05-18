<x-backoffice.layout.app-layout title="DETAIL SINGLE FORM OPEN CASE">
    @push('css')
        <style>
            thead {
                background: #f5f4f8;
                text-align: center;
            }

            .image-preview-container {
                border: 1px solid #ccc;
                border-radius: 5px;
                display: flex;
                justify-content: space-evenly;
                padding: 5px;
                flex-wrap: wrap;
            }

            .custom-controls {
                display: flex;
                justify-content: center;
                gap: 10px;
                margin-top: 10px;
            }

            .custom-controls button {
                padding: 10px 20px;
                border-radius: 5px;
                font-size: 16px;
                border: none;
                cursor: pointer;
            }

            #recordStatus {
                display: none;
                color: red;
                font-size: 18px;
                font-weight: bold;
                animation: blink 1s infinite;
                margin-left: 10px;
            }

            @keyframes blink {
                0% {
                    opacity: 1;
                }

                50% {
                    opacity: 0;
                }

                100% {
                    opacity: 1;
                }
            }
        </style>
    @endpush
    <x-backoffice.toolbar heading="DETAIL SINGLE FORM OPEN CASE" subheading="" breadcrumb="detail-single-form-open-case"
        icon="fas fa-users">
        <div class="d-flex align-items-center w-25">
            <x-backoffice.notification />
        </div>
    </x-backoffice.toolbar>

    <head>
        <script src="https://cdn.jsdelivr.net/npm/@ffmpeg/ffmpeg@0.9.8/dist/ffmpeg.min.js"></script>
        <link href="https://vjs.zencdn.net/7.17.0/video-js.css" rel="stylesheet">
        <script src="https://vjs.zencdn.net/7.17.0/video.min.js"></script>

    </head>


    <div class="app-container container-xxl">
        <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
            <div class="d-flex flex-column flex-column-fluid">
                <div id="kt_app_content" class="app-content flex-column-fluid">
                    <div class="row g-5 g-xl-8">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="card ">
                                        <div class="card-header border-0">
                                            <div class="card-title">
                                                <h3 class="card-label
                                                    ">Detail single form open case</h3>
                                            </div>
                                            <div class="card-toolbar">
                                                <div class="d-flex justify-content-end w-100"
                                                    data-kt-customer-table-toolbar="base">
                                                    <button type="button" class="btn btn-dark btn-sm me-2"
                                                        onclick="window.location.href='{{ route('open.singleform.single-form.download-file', encrypt($data->id)) }}'">
                                                        <i class="fas fa-file"></i> Download PDF
                                                    </button>
                                                    <button type="button" class="btn btn-dark btn-sm"
                                                        data-bs-toggle="modal" data-bs-target="#videoStreamModal">
                                                        <i class="fas fa-video"></i> Stream
                                                    </button>
                                                </div>
                                            </div>

                                        </div>
                                        <hr>
                                        <div class="card-body">
                                            <label class="fs-3 fw-bold">DETAIL KASUS:</label>
                                            <hr>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Satuan Kerja</label>
                                                <div class="col-lg-8">
                                                    <span
                                                        class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->satker->nama_satker }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Kasus</label>
                                                <div class="col-lg-8">
                                                    <span
                                                        class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->case_name }}</span>
                                                </div>
                                            </div>

                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Tanggal Kasus</label>
                                                <div class="col-lg-8">
                                                    <span
                                                        class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->case_date }}</span>
                                                </div>
                                            </div>

                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Deskripsi Kasus</label>
                                                <div class="col-lg-8">
                                                    <span
                                                        class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ strip_tags($data->case_description) }}</span>
                                                </div>
                                            </div>

                                            <label class="fs-3 fw-bold">BIODATA TARGET:</label>
                                            <hr>
                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Nama</label>
                                                <div class="col-lg-8">
                                                    <span
                                                        class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->target_name }}</span>
                                                </div>
                                            </div>

                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Nomor Identitas</label>
                                                <div class="col-lg-8">
                                                    <span
                                                        class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->target_identity_number }}</span>
                                                </div>
                                            </div>

                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Agama</label>
                                                <div class="col-lg-8">
                                                    <span
                                                        class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->target_religion }}</span>
                                                </div>
                                            </div>

                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Pendidikan</label>
                                                <div class="col-lg-8">
                                                    <span
                                                        class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->target_education }}</span>
                                                </div>
                                            </div>

                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Pekerjaan</label>
                                                <div class="col-lg-8">
                                                    <span
                                                        class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->target_occupation }}</span>
                                                </div>
                                            </div>


                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Jenis Kelamin</label>
                                                <div class="col-lg-8">
                                                    <span
                                                        class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->target_gender }}</span>
                                                </div>
                                            </div>

                                            <div class="row mb-5">
                                                <label class="col-lg-4 fw-semibold text-muted">Alamat</label>
                                                <div class="col-lg-8">
                                                    <span
                                                        class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->target_address }}</span>
                                                </div>
                                            </div>

                                            <div class="row mb-4">
                                                <label class="col-lg-4 fw-semibold text-muted mb-3">Foto</label>
                                                <div class="image-preview-container">
                                                    @foreach ($images as $image)
                                                        <img class="image-preview"
                                                            style="max-width: 200px; margin-right: 10px; margin-bottom: 10px;"
                                                            src="{{ $image ?? asset('assets/images/placeholder.jpeg') }}"
                                                            alt="Preview">
                                                    @endforeach
                                                </div>
                                            </div>

                                            @if($data->open_procedure_type == 'research' || $data->open_procedure_type == 'all')
                                                <label class="fs-3 fw-bold">Penelitian</label>
                                                <hr>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Pendahuluan</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{!! $data->research_lapinsus_pendahuluan !!}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Data dan Fakta</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{!! $data->research_data_dan_fakta !!}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Informasi
                                                        Diperoleh</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{!! $data->research_informasi_diperoleh !!}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Sumber Informasi</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{!! $data->research_sumber_informasi !!}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Trend
                                                        Perkembangan</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{!! $data->research_tren_perkembangan !!}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Saran dan Tindak
                                                        Lanjut</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{!! $data->research_saran_tindak !!}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Ancaman</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{!! $data->ancaman !!}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Gangguan</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{!! $data->gangguan !!}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Hambatan</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{!! $data->hambatan !!}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Tantangan</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{!! $data->tantangan !!}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted mb-3"> Dokumen</label>
                                                    <div class="col-lg-8">
                                                        @if ($data->research_file_document != null)
                                                            <iframe src="{{ $data->research_file_document }}"
                                                                style="width: 100%; height: 500px;" frameborder="0">
                                                            </iframe>
                                                        @else
                                                            <label class="col-lg-4 fw-semibold text-muted mb-3">Tidak Ada
                                                                File</label>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="row mb-5" style="text-align: justify;">
                                                    <label class="col-lg-4 fw-semibold text-muted">Hasil Analisa</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800 text-justify" style="text-align: justify;">{{ $research_document_pdf_data->doc_analytics_2 ?? '' }}</span>
                                                    </div>
                                                </div>
                                                <div class="row mb-5 " style="text-align: justify;">
                                                    <label class="col-lg-4 fw-semibold text-muted">Hasil Kesimpulan</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800 text-justify" style="text-align: justify;">{{ $research_document_pdf_data->doc_summary_2 ?? ''  }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted mb-3"> Video</label>


                                                    <div class="col-lg-8">
                                                        @if($data->research_file_video)
                                                            <video controls style="width: 100%; height: 500px;">
                                                                <source src="{{$data->research_file_video }}" type="video/mp4">
                                                                Your browser does not support the video tag.
                                                            </video>
                                                        @else
                                                            <label class="col-lg-4 fw-semibold text-muted mb-3">Tidak Ada
                                                                File</label>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="row mb-12">
                                                    <div class="form-group col-md-12">
                                                        <div class="table-responsive">
                                                            <h4> Analisis Video </h4>
                                                            <table id="video_reseacrh"
                                                                class="table table-striped table-row-bordered gy-5 gs-7 border rounded w-100">
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
                                            @endif

                                            @if($data->open_procedure_type == 'interview' || $data->open_procedure_type == 'all')
                                                <label class="fs-3 fw-bold">Wawancara</label>
                                                <hr>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Nama Pewawancara</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->interview_interviewer_name }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Jadwal Wawancara</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->interview_schedule }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Nama</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->interview_target_name }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Nomor Identitas</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->interview_target_identity_number }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Agama</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->interview_target_religion }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Pendidikan</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->interview_target_education }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Jenis Kelamin</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->interview_target_gender }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Alamat</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->interview_target_address }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-4">
                                                    <label class="col-lg-4 fw-semibold text-muted mb-3">Foto</label>
                                                    <div class="image-preview-container">
                                                        @foreach ($interview_images as $image)
                                                            <img class="image-preview"
                                                                style="max-width: 200px; margin-right: 10px; margin-bottom: 10px;"
                                                                src="{{ $image ?? asset('assets/images/placeholder.jpeg') }}"
                                                                alt="Preview">
                                                        @endforeach
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted mb-3"> Dokumen</label>
                                                    <div class="col-lg-8">
                                                        @if($data->interview_file_document)
                                                            <iframe src="{{ $data->interview_file_document }}"
                                                                style="width: 100%; height: 500px;" frameborder="0">
                                                            </iframe>
                                                        @else
                                                            <label class="col-lg-4 fw-semibold text-muted mb-3">Tidak Ada
                                                                File</label>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="row mb-5" style="text-align: justify;">
                                                    <label class="col-lg-4 fw-semibold text-muted">Hasil Analisa</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800 text-justify" style="text-align: justify;">{{ $interview_document_pdf_data->doc_analytics_2 ?? '' }}</span>
                                                    </div>
                                                </div>
                                                <div class="row mb-5 " style="text-align: justify;">
                                                    <label class="col-lg-4 fw-semibold text-muted">Hasil Kesimpulan</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800 text-justify" style="text-align: justify;">{{ $interview_document_pdf_data->doc_summary_2 ?? ''  }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted mb-3"> Video</label>


                                                    <div class="col-lg-8">
                                                        @if($data->interview_file_video)
                                                            <video controls style="width: 100%; height: 500px;">
                                                                <source src="{{$data->interview_file_video }}" type="video/mp4">
                                                                Your browser does not support the video tag.
                                                            </video>
                                                        @else
                                                            <label class="col-lg-4 fw-semibold text-muted mb-3">Tidak Ada
                                                                File</label>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="row mb-12">
                                                    <div class="form-group col-md-12">
                                                        <div class="table-responsive">
                                                            <h4> Analisis Video </h4>
                                                            <table id="video_reseacrh"
                                                                class="table table-striped table-row-bordered gy-5 gs-7 border rounded w-100">
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
                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Saran dan Tindak
                                                        Lanjut</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800" style="text-align: justify;"> {!! $data->interview_saran_dan_tindak_lanjut !!}</span>
                                                    </div>
                                                </div>

                                            @endif

                                            @if($data->open_procedure_type == 'interrogation' || $data->open_procedure_type == 'all')
                                                <label class="fs-3 fw-bold">Interogasi</label>
                                                <hr>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Jaksa Pelaksana</label>
                                                    <div class="col-lg-8">
                                                        @foreach ($interrogation_listJaksa as $jaksa)
                                                            <span class="fw-bold fs-6 text-gray-800">{{ $jaksa }}</span>
                                                            <br>
                                                        @endforeach
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Nama</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->interrogation_target_name }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Nomor Identitas</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->interrogation_target_identity_number }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Agama</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->interrogation_target_religion }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Pendidikan</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->interrogation_target_education }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Jenis Kelamin</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->interrogation_target_gender }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Alamat</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->interrogation_target_address }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-4">
                                                    <label class="col-lg-4 fw-semibold text-muted mb-3">Foto</label>
                                                    <div class="image-preview-container">
                                                        @foreach ($interrogation_images as $image)
                                                            <img class="image-preview"
                                                                style="max-width: 200px; margin-right: 10px; margin-bottom: 10px;"
                                                                src="{{ $image ?? asset('assets/images/placeholder.jpeg') }}"
                                                                alt="Preview">
                                                        @endforeach
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted mb-3"> Dokumen</label>
                                                    <div class="col-lg-8">
                                                        @if($data->interrogation_file_document)
                                                            <iframe src="{{ $data->interrogation_file_document }}"
                                                                style="width: 100%; height: 500px;" frameborder="0">
                                                            </iframe>
                                                        @else
                                                            <label class="col-lg-4 fw-semibold text-muted mb-3">Tidak Ada
                                                                File</label>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="row mb-5" style="text-align: justify;">
                                                    <label class="col-lg-4 fw-semibold text-muted">Hasil Analisa</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800 text-justify" style="text-align: justify;">{{ $interrogation_document_pdf_data->doc_analytics_2 ?? '' }}</span>
                                                    </div>
                                                </div>
                                                <div class="row mb-5 " style="text-align: justify;">
                                                    <label class="col-lg-4 fw-semibold text-muted">Hasil Kesimpulan</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800 text-justify" style="text-align: justify;">{{ $interrogation_document_pdf_data->doc_summary_2 ?? ''  }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted mb-3"> Video</label>
                                                    <div class="col-lg-8">
                                                        @if($data->interrogation_file_video)
                                                            <video controls style="width: 100%; height: 500px;">
                                                                <source src="{{ $data->interrogation_file_video }}"
                                                                    type="video/mp4">
                                                                Your browser does not support the video tag.
                                                            </video>
                                                        @else
                                                            <label class="col-lg-4 fw-semibold text-muted mb-3">Tidak Ada
                                                                File</label>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="row mb-12">
                                                    <div class="form-group col-md-12">
                                                        <div class="table-responsive">
                                                            <h4> Analisis Video </h4>
                                                            <table id="video_reseacrh"
                                                                class="table table-striped table-row-bordered gy-5 gs-7 border rounded w-100">
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
                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Identifikasi
                                                        Target</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{!! $data->interrogation_target_identification !!}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Hasil yang
                                                        Dicapai</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{!! $data->interrogation_result_achievement !!}</span>
                                                    </div>
                                                </div>
                                            @endif

                                            @if($data->open_procedure_type == 'elicitation' || $data->open_procedure_type == 'all')
                                                <label class="fs-3 fw-bold">Elisitasi</label>
                                                <hr>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Nama Pewawancara</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->elicitation_interviewer_name }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Jadwal Wawancara</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->elicitation_interview_schedule }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Nama</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->elicitation_interview_target_name }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Nomor Identitas</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->elicitation_interview_target_identity_number }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Agama</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->elicitation_target_religion }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Pendidikan</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->elicitation_target_education }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Jenis Kelamin</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->elicitation_target_gender }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted">Alamat</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800" style="text-align: justify;">{{ $data->elicitation_target_address }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-4">
                                                    <label class="col-lg-4 fw-semibold text-muted mb-3">Foto</label>
                                                    <div class="image-preview-container">
                                                        @foreach ($elicitation_images as $image)
                                                            <img class="image-preview"
                                                                style="max-width: 200px; margin-right: 10px; margin-bottom: 10px;"
                                                                src="{{ $image ?? asset('assets/images/placeholder.jpeg') }}"
                                                                alt="Preview">
                                                        @endforeach
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted mb-3"> Dokumen</label>
                                                    <div class="col-lg-8">
                                                        @if($data->elicitation_file_document)
                                                            <iframe src="{{ $data->elicitation_file_document }}"
                                                                style="width: 100%; height: 500px;" frameborder="0">
                                                            </iframe>
                                                        @else
                                                            <label class="col-lg-4 fw-semibold text-muted mb-3">Tidak Ada
                                                                File</label>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="row mb-5" style="text-align: justify;">
                                                    <label class="col-lg-4 fw-semibold text-muted">Hasil Analisa</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800 text-justify" style="text-align: justify;">{{ $elicitation_document_pdf_data->doc_analytics_2 ?? '' }}</span>
                                                    </div>
                                                </div>
                                                <div class="row mb-5 " style="text-align: justify;">
                                                    <label class="col-lg-4 fw-semibold text-muted" >Hasil Kesimpulan</label>
                                                    <div class="col-lg-8">
                                                        <span
                                                            class="fw-bold fs-6 text-gray-800 text-justify" style="text-align: justify;">{{ $elicitation_document_pdf_data->doc_summary_2 ?? ''  }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-5">
                                                    <label class="col-lg-4 fw-semibold text-muted mb-3"> Video</label>

                                                    <div class="col-lg-8">
                                                        @if($data->elicitation_file_video)
                                                            <video controls style="width: 100%; height: 500px;">
                                                                <source src="{{$data->elicitation_file_video }}"
                                                                    type="video/mp4">
                                                                Your browser does not support the video tag.
                                                            </video>
                                                        @else
                                                            <label class="col-lg-4 fw-semibold text-muted mb-3">Tidak Ada
                                                                File</label>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="row mb-12">
                                                    <div class="form-group col-md-12">
                                                        <div class="table-responsive">
                                                            <h4> Analisis Video </h4>
                                                            <table id="video_reseacrh"
                                                                class="table table-striped table-row-bordered gy-5 gs-7 border rounded w-100">
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

                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="videoStreamModal" tabindex="-1" aria-labelledby="videoStreamModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="videoStreamModalLabel">Video Stream</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="bodycamSelect" class="form-label">Select Bodycam Stream</label>
                        <select id="bodycamSelect">
                            <option value="" selected>Select a bostream</option>
                            @foreach ($bodycam_devices as $row)
                                <option value="{{ $row['id'] }}" @if($row['id'] === old('id_case')) selected @endif>
                                    {{ $row['text'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3" id="penyelidikan_type">
                        <label for="penyelidikan_type_select" class="form-label">Tipe Penyelidikan</label>
                        <select id="penyelidikan_type_select">
                            <option value="" selected>Pilih Penyelidikan</option>
                            <option value="research" selected>Penelitian</option>
                            <option value="interview" selected>Wawancara</option>
                            <option value="interrogation" selected>Interogasi</option>
                            <option value="elicitation" selected>Elisitasi</option>

                        </select>
                    </div>

                    <!-- <div class="mb-12" id="videoContainer">
                        <video id="videoPlayer" class="video-js vjs-default-skin" width="750" height="500"></video>
                    </div> -->
                    <div class="mb-12" id="videoContainer">
                        <iframe id="playerFrame"
                           
                            src="{{ route('player', 'serverIp=dss.kejaksaanri.id&playerType=real&channelId=1000015$0$26') }}"
                            frameborder="0" style="width: 100%; height: 500px;"></iframe>

                    </div>
                    <div class="custom-controls" id="player_control_button">
                        <button id="fullscreenButton" class="btn btn-success">Full Screen</button>
                        <button id="startRecordButton" class="btn btn-secondary">Start Record</button>
                        <button id="stopRecordButton" class="btn btn-secondary">Stop Record</button>
                        <span id="recordStatus">Recording...</span>
                        <span id="pidProcess"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('js')

    @endpush

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Data from the backend
            if ('{{$data->open_procedure_type}}' != "all") {
                document.getElementById('penyelidikan_type').style.display = 'none';
            }

            // Populate the dropdown
            var bodycamSelect = document.getElementById('bodycamSelect');

            // Handle dropdown change
            bodycamSelect.addEventListener('change', function () {
                var selectedStream = bodycamSelect.value;
                console.log(selectedStream);
                if (selectedStream != "") {
                    $.ajax({
                        url: '/bodycam-device-by-id', // Replace this with the actual route to your controller
                        type: 'GET',
                        data: { bodycam_id: selectedStream },
                        success: function (response) {
                            console.log(response);

                            if (response.device_dahua_id) {
                                // Dynamically construct the channelId and playerUrl using JavaScript
                                var channelId = response.device_dahua_id + '$0$26';
                                // var playerUrl = `http://160.20.104.115/web/player/index.html?serverIp=160.20.104.115&playerType=real&channelId=${channelId}`;

                                // // Log the constructed player URL for debugging
                                // console.log(playerUrl);

                                // // Set the dynamically created playerUrl to the iframe
                                // document.getElementById('playerFrame').src = playerUrl;

                                // Construct the RTSP link dynamically
                                var rtspLink = 'rtsp://160.20.104.115:9100/dss/monitor/param/cameraid=' + response.device_dahua_id + '%240%26substream=1?token=' + response.token;

                                // Send the RTSP link to the iframe using the postMessage method
                                sendMessageToIframe(rtspLink);
                            } else {
                                var channelId = response.device_dahua_id + '$0$26';
                                var playerUrl = `https://dss.kejaksaanri.id/web/player/index.html?serverIp=dss.kejaksaanri.id&playerType=real&channelId=${channelId}`;

                                // Log the constructed player URL for debugging
                                console.log(playerUrl);

                                // Set the dynamically created playerUrl to the iframe
                                document.getElementById('playerFrame').src = playerUrl;

                                // Construct the RTSP link dynamically
                                var rtspLink = 'rtsp://160.20.104.115/dss/monitor/param/cameraid=' + response.device_dahua_id + '%240%26substream=1?token=' + response.token;

                                // Send the RTSP link to the iframe using the postMessage method
                                sendMessageToIframe(rtspLink);
                            }
                        }
                    });
                }


            });



            document.getElementById('fullscreenButton').addEventListener('click', function () {
                console.log("clicked")
                const iframe = document.getElementById('playerFrame');
                if (iframe.requestFullscreen) {
                    iframe.requestFullscreen();
                } else if (iframe.mozRequestFullScreen) { // Firefox
                    iframe.mozRequestFullScreen();
                } else if (iframe.webkitRequestFullscreen) { // Chrome, Safari, and Opera
                    iframe.webkitRequestFullscreen();
                } else if (iframe.msRequestFullscreen) { // IE/Edge
                    iframe.msRequestFullscreen();
                }
            });

            function generateUUID() {
                var chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                var uuid = '';
                for (var i = 0; i < 10; i++) {
                    uuid += chars.charAt(Math.floor(Math.random() * chars.length));
                }
                return uuid;
            }

    

            document.getElementById('startRecordButton').addEventListener('click', () => {

                var selectedStream = bodycamSelect.value;
                console.log(selectedStream);

                if (selectedStream != "") {
                    $.ajax({
                        url: '/bodycam-device-by-id', // Replace with the actual route to your controller
                        type: 'GET',
                        data: { bodycam_id: selectedStream },
                        success: function (response) {
                            console.log(response);

                            if (response.device_dahua_id) {
                                var now = new Date();
                                var year = now.getFullYear();
                                var month = ('0' + (now.getMonth() + 1)).slice(-2); // Months are zero-based
                                var day = ('0' + now.getDate()).slice(-2);
                                var hours = ('0' + now.getHours()).slice(-2);
                                var minutes = ('0' + now.getMinutes()).slice(-2);
                                var seconds = ('0' + now.getSeconds()).slice(-2);
                                var timestamp = year + month + day + '_' + hours + minutes + seconds;

                                // Generate a 10-character UUID
                                var uuid = generateUUID();

                                // Construct the file name
                                var fileName = "open_singleform_" + timestamp + "_" + uuid;
                                // Dynamically construct the channelId and playerUrl using JavaScript
                                var channelId = response.device_dahua_id;

                                // Construct the RTSP link dynamically
                                var rtspLink = 'rtsp://160.20.104.115:9100/dss/monitor/param/cameraid=' + response.device_dahua_id + '%240%26substream=1?token=' + response.token;
                                // Send the RTSP link to the iframe using the postMessage method
                                $.ajax({
                                    url: '/start-recording',
                                    type: 'POST',
                                    data: {
                                        _token: $('meta[name="csrf-token"]').attr('content'), // CSRF token
                                        stream_url: rtspLink,
                                        file_name: fileName// pastikan nama parameternya sesuai dengan yang diterima controller
                                    },
                                    success: function (response) {
                                        console.log(response);
                                        $('#pidProcess').text(response.pid);

                                        document.getElementById('startRecordButton').disabled = true;
                                        document.getElementById('stopRecordButton').disabled = false;
                                        document.getElementById('recordStatus').style.display = 'inline';


                                    },
                                    error: function (error) {
                                        console.error("AJAX request failed:", error);
                                    }
                                });
                            }
                        },
                        error: function (error) {
                            console.error("AJAX request failed:", error);
                        }
                    });
                }




            });

            document.getElementById('stopRecordButton').addEventListener('click', () => {

                $.ajax({
                    url: '/stop-recording',
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'), // CSRF token
                        pid: document.getElementById('pidProcess').innerText // pastikan nama parameternya sesuai dengan yang diterima controller
                    },
                    success: function (response) {
                        console.log(response);
                        document.getElementById('penyelidikan_type').style.display = 'none';
                        var penyelidikan_type_select = document.getElementById('penyelidikan_type_select');
                        var penyelidikan_selected = penyelidikan_type_select.value;

                        $.ajax({
                            url: '/open/interview/hasil/upload-video',
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'), // CSRF token
                                // pastikan nama parameternya sesuai dengan yang diterima controller
                            },
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content'),
                                id: "{!!$data->id!!}",
                                path: response.video_path_save,
                                type: penyelidikan_selected
                            },
                            success: function (response) {
                                console.log('Video uploaded successfully:', response);
                            },
                            error: function (xhr, status, error) {
                                console.error('Video upload failed:', error);
                            }
                        });

                        document.getElementById('startRecordButton').disabled = false;
                        document.getElementById('stopRecordButton').disabled = true;
                        document.getElementById('recordStatus').style.display = 'none';


                    },
                    error: function (error) {
                        console.error("AJAX request failed:", error);
                    }
                });
                }
            });


                // function download() {
                //     var blob = new Blob(recordedChunks, {
                //         type: 'video/mp4'
                //     });
                //     var xhr = new XMLHttpRequest();
                //     xhr.open('POST', '{{ route('open.singleform.single-form.upload.video') }}', true);
                //     xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
                //     //xhr.setRequestHeader('Content-Type', 'application/octet-stream'); // Tidak perlu mengatur Content-Type saat menggunakan FormData

                //     xhr.onload = function () {
                //         console.log(xhr.status);
                //         if (xhr.status === 200) {
                //             alert('Video uploaded successfully');
                //         } else {
                //             alert('Failed to upload video');
                //         }
                //     };

                //     // Membuat FormData untuk mengirim blob dan id
                //     var formData = new FormData();
                //     var penyelidikan_type = "";
                //     if ('{{$data->open_procedure_type}}' == "all") {
                //         document.getElementById('penyelidikan_type').style.display = 'none';
                //         var penyelidikan_type_select = document.getElementById('penyelidikan_type_select');
                //         var penyelidikan_selected = penyelidikan_type_select.value;
                //         penyelidikan_type = penyelidikan_selected;
                //     } else {
                //         penyelidikan_type = '{{$data->open_procedure_type}}'
                //     }


                //     formData.append('video', blob); // blob adalah video yang diunggah
                //     formData.append('type', penyelidikan_type);
                //     formData.append('id', '{{$data->id}}'); // menambahkan id ke dalam formData
                //     console.log('Data ID:', '{{$data->id}}')
                //     xhr.send(formData);
                //     // var url = URL.createObjectURL(blob);
                //     // var a = document.createElement('a');
                //     // document.body.appendChild(a);
                //     // a.style = 'display: none';
                //     // a.href = url;
                //     // a.download = 'recorded_video.mp4';
                //     // a.click();
                //     // window.URL.revokeObjectURL(url);
                // }

                // function uploadToServer(blob) {
                //     var formData = new FormData();
                //     formData.append('video', blob, 'recorded_video.mp4');

                //     $.ajax({
                //         url: '{{url('upload-video')}}',
                //         type: 'POST',
                //         headers: {
                //             'X-CSRF-TOKEN': csrfToken // Include the CSRF token
                //         },
                //         data: formData,
                //         processData: false,
                //         contentType: false,
                //         success: function (response) {
                //             console.log('Video uploaded successfully:', response);
                //         },
                //         error: function (xhr, status, error) {
                //             console.error('Video upload failed:', error);
                //         }
                //     });
                // }

                // $('#videoStreamModal').on('hide.bs.modal', function () {
                //     player.pause();
                // });
            // });
    </script>

</x-backoffice.layout.app-layout>