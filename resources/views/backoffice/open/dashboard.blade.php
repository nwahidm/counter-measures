<x-backoffice.layout.app-layout title="Dashboard">
    @push('css')
        <style>
            thead {
                background: #f5f4f8;
                text-align: center;
            }
            .dataTables_wrapper >.row:first-child, .dataTables_wrapper >.row:last-child {
                display: none;
            }
        </style>
    @endpush

    <x-backoffice.toolbar heading="" subheading="" breadcrumb="dashboard" icon="fas fa-users">
        {{-- <div class="d-flex align-self-center flex-center flex-shrink-0">
            <a href="#" class="btn btn-flex btn-sm btn-outline btn-active-color-primary btn-custom px-4" data-bs-toggle="modal" data-bs-target="#kt_modal_invite_friends">
            <i class="ki-outline ki-plus-square fs-4 me-2"></i>Invite</a>
            <a href="#" class="btn btn-sm btn-active-color-primary btn-outline btn-custom ms-3 px-4" data-bs-toggle="modal" data-bs-target="#kt_modal_new_target">Set Your Target</a>
        </div> --}}
    </x-backoffice.toolbar>

    <div class="app-container container-xxl">
        <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
            <div class="d-flex flex-column flex-column-fluid">

                <div id="kt_app_content" class="app-content flex-column-fluid">
                    <div class="row mb-4">
                        <div class="col-md-12">
                            {{-- FILTER TANGGAL --}}
                            <div class="card border-0 pt-6">
                                <div class="card-body">
                                    <div class="d-flex justify-content-start" data-kt-customer-table-toolbar="base">
                                        <form method="GET" action="{{ route('open-dashboard') }}" id="filterForm"
                                              class="d-flex justify-content-between w-100">
                                            <div class="col-md-4">
                                                <div class="d-flex flex-column me-5">
                                                    <label for="bulan" class="fs-6 fw-semibold mb-2 required">
                                                        Bulan dan Tahun Kasus
                                                    </label>
                                                    <input type="month" class="form-control form-control-solid"
                                                           name="bulan"
                                                           value="{{ old('bulan', $bulan) }}"
                                                           max="{{ date('Y-m') }}" 
                                                           id="inputTglSelesai" 
                                                           required/>
                                                </div>
                                            </div>                                            
                                            <div class="col-md-4">
                                                <div class="d-flex flex-column me-5">
                                                    <label for="satker" class="fs-6 fw-semibold mb-2 required">Satuan Kerja</label>
                                                    <select
                                                    class="form-select form-select-solid select @error('satker') is-invalid @enderror"
                                                    name="satker" id="satker"
                                                    data-control="select2" data-hide-search="false"
                                                    @if(auth()->user()->user_roles != "superadmin") disabled @endif>
                                                    <option value="">---Pilih Satuan Kerja---</option>
                                                    @foreach ($satker as $row)
                                                        <option
                                                            value="{{ $row['id'] }}"
                                                            @if($row['id'] == $filterSatker) 
                                                                selected 
                                                            @elseif($row['id'] == auth()->user()?->satker?->id_satker)
                                                                selected 
                                                            @else
                                                            @endif>{{ $row['text'] }}</option>
                                                    @endforeach
                                                </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4 d-flex flex-column me-5">
                                                <div class="d-flex flex-column me-5">
                                                    <label class="fs-6 fw-semibold mb-2">&nbsp;</label>
                                                    <button class="btn btn-success" type="submit" id="btnFilterTanggal"><i
                                                            class="fas fa-filter"></i>
                                                        Filter
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-8">
                        <!--begin::Col-->
                        <div class="col-6">
                            <div class="card card-stretch ">
                                <div class="card-body">
                                    <div class="row text-center mb-3">
                                        <h3>Proses Kasus Metode Terbuka</h3>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <canvas id="myChart1" height="300"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card card-stretch ">
                                <div class="card-body">
                                    <div class="row text-center mb-3">
                                        <h3>Statistik Kasus Metode Terbuka</h3>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <canvas id="myChart2" height="300"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Row-->
                    <div class="row ">
                        <!--begin::Col-->
                        <div class="col-6">
                            <div class="card card-stretch ">
                                <div class="card-body">
                                    <div class="row text-center mb-3">
                                        <h3>Top 5 Terbaru - Kasus Metode Terbuka</h3>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                {{ $lastOpenDataTable->table(['class' => 'table table-striped table-row-bordered gy-5 gs-7 border rounded w-100 text-center', 'id' => 'data-table1'], true) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card card-stretch ">
                                <div class="card-body">
                                    <div class="row text-center mb-3">
                                        <h3>Top 5 Terlama - Kasus Metode Terbuka</h3>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                {{ $earlierOpenDataTable->table(['class' => 'table table-striped table-row-bordered gy-5 gs-7 border rounded w-100 text-center', 'id' => 'data-table2'], true) }}
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
    </div>
    @push('scripts')
        {{ $lastOpenDataTable->scripts() }}
        {{ $earlierOpenDataTable->scripts() }}
    @endpush
    @push('js')
        <script>
            $(document).ready(function() {
                const pieData = @json($pieData);
                const pieLabel = @json($pieLabel);
                const lineData = @json($statistic);
                const allDates = @json($allDates);

                Chart.register(ChartDataLabels);


                const chartDatasetConf = {
                    borderWidth: 1,
                    backgroundColor: [
                        "#ed676f",
                        "#3e7e8a",
                        "#847d6d",
                        "#1eafd2"
                    ],
                    borderColor: "#fff",
                    datalabels: {
                        // Set the color of the datalabel text
                        color: 'white',

                        // Set label position
                        anchor: 'top',
                        align: 'end',
                        // Automatically display the datalabels
                        display: 'auto',
                
                        // Set the font style and weight
                        font: {
                            weight: 'bold',
                        },
                        padding: {
                            bottom: 10 // Add a bottom padding
                        },
                        // Define the formatter function to display the value of each bar
                        formatter: function(value) {
                            return value;
                        },
                    },
                }
                
                const ctx1 = document.getElementById('myChart1');
                const ctx2 = document.getElementById('myChart2');
                let chart1 = new Chart(ctx1, {
                    type: 'pie',
                    data: {
                        labels: pieLabel,
                        datasets: [
                            {
                                data: pieData,
                                ...chartDatasetConf  
                            },
                        ]
                    },
                    options: {
                        scales: {
                            // y: {
                            //     min: 0,
                            //     max: yMax,
                            // },
                        },
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: "bottom"
                            }
                        },
                    }
                });
                // line
                let chart2 = new Chart(ctx2, {
                type: 'line',
                data: {
                    // labels: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"],
                    labels: allDates,
                    datasets: [
                        {
                            data: lineData,
                            // ...chartDatasetConf  
                            fill: false,
                            borderColor: '#ed676f',
                            tension: 0.1
                        },
                    ]
                },
                options: {
                    scales: {
                        x: {
                            type: 'category', // Pastikan x-axis menggunakan kategori
                            title: { display: true, text: 'Tanggal' }
                        },
                        y: {
                            beginAtZero: true,
                            title: { display: true, text: 'Jumlah Kasus' }
                        }
                    },
                    maintainAspectRatio: false,
                        plugins: {
                            legend: false, // Hide legend
                        },
                },
                
            });


            })
            
        </script>
    @endpush
</x-backoffice.layout.app-layout>
