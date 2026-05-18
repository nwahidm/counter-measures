<script setup>
import { formatDateTime } from './../../../mixins';
import { onMounted, ref } from 'vue';

const formLoading = ref(false);
const form = ref({
    tgl_mulai: null,
    tgl_selesai: null
});
// const logoKejaksaanPngbase64 = ref(null);

async function generatePdf() {
    try {
        formLoading.value = true;

        /* axios.get('/to-base64-path?file_path=image/kejaksaan_compress.png')
            .then((res) => logoKejaksaanPngbase64.value = res.data)
            .catch((error) => console.log(error.message)); */

        const tgl_laporan = form.value.tgl_mulai === form.value.tgl_selesai ?
            formatDateTime(form.value.tgl_mulai, 'DD MMMM YYYY') :
            formatDateTime(form.value.tgl_mulai, 'DD MMMM YYYY') + ' - ' +
            formatDateTime(form.value.tgl_selesai, 'DD MMMM YYYY');

        const res = await axios.post('/report/kegiatan-posko/generate', {
            tgl_mulai: form.value.tgl_mulai,
            tgl_selesai: form.value.tgl_selesai
        }, {
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });

        if (res.status === 200 && res.data.length >= 1) {
            const data = res.data.map((value, index) => {
                const uraian_singkat = JSON.parse(value.uraian_singkat);
                const aght = JSON.parse(value.aght);

                return [
                    {
                        table: {
                            widths: ['auto', 'auto', '*'],
                            body: [
                                [
                                    {
                                        text: (index + 1) + '.',
                                        rowSpan: 7,
                                        bold: true,
                                        alignment: 'right',
                                        style: 'textSizeReg'
                                    },
                                    {
                                        text: 'Sumber Informasi',
                                        bold: true,
                                        style: 'textSizeReg'
                                    },
                                    {
                                        text: (value.nama_satker).toUpperCase(),
                                        bold: true,
                                        style: 'textSizeReg'
                                    },
                                ],
                                [
                                    '',
                                    {
                                        text: 'Hari, Tanggal',
                                        bold: true,
                                        style: 'textSizeReg'
                                    },
                                    {
                                        text: moment(value.tanggal).format('dddd, DD MMMM YYYY'),
                                        style: 'textSizeReg'
                                    }
                                ],
                                [
                                    '',
                                    {
                                        text: 'Perihal',
                                        bold: true,
                                        style: 'textSizeReg'
                                    },
                                    {
                                        text: value.perihal,
                                        alignment: 'justify',
                                        style: 'textSizeReg'
                                    },
                                ],
                                [
                                    '',
                                    {
                                        text: 'Uraian Singkat',
                                        bold: true,
                                        style: 'textSizeReg'
                                    },
                                    {
                                        table: {
                                            widths: ['auto', 'auto', '*'],
                                            body: [
                                                [{ text: 'Apa Kegiatan yang terjadi', style: 'textSizeReg'  }, { text: ':', style: 'textSizeReg'  }, { text: uraian_singkat.apa, alignment: 'justify', style: 'textSizeReg' }],
                                                [{ text: 'Mengapa bisa terjadi', style: 'textSizeReg'  }, { text: ':', style: 'textSizeReg'  }, { text: uraian_singkat.mengapa, alignment: 'justify', style: 'textSizeReg' }],
                                                [{ text: 'Siapa yang terlibat', style: 'textSizeReg'  }, { text: ':', style: 'textSizeReg'  }, { text: uraian_singkat.siapa, alignment: 'justify', style: 'textSizeReg' }],
                                                [{ text: 'Diman kegiatan terjadi', style: 'textSizeReg'  }, { text: ':', style: 'textSizeReg'  }, { text: uraian_singkat.dimana, alignment: 'justify', style: 'textSizeReg' }],
                                                [{ text: 'Bagaimana kegiatan terjadi', style: 'textSizeReg'  }, { text: ':', style: 'textSizeReg'  }, { text: uraian_singkat.bagaimana, alignment: 'justify', style: 'textSizeReg' }],
                                                [{ text: 'Kapan kegiatan terjadi', style: 'textSizeReg'  }, { text: ':', style: 'textSizeReg'  }, { text: uraian_singkat.kapan, alignment: 'justify', style: 'textSizeReg' }]
                                            ]
                                        },
                                        layout: 'noBorders'
                                    }
                                ],
                                [
                                    '',
                                    {
                                        text: 'Tren Perkembangan',
                                        bold: true,
                                        style: 'textSizeReg'
                                    },
                                    {
                                        text: value.trend_perkembangan,
                                        alignment: 'justify',
                                        style: 'textSizeReg'
                                    },
                                ],
                                [
                                    '',
                                    {
                                        text: 'AGHT',
                                        bold: true,
                                        style: 'textSizeReg'
                                    },
                                    {
                                        table: {
                                            widths: ['auto', 'auto', '*'],
                                            body: [
                                                [{ text: 'Ancaman', style: 'textSizeReg'  }, { text: ':', style: 'textSizeReg'  }, { text: aght.ancaman, alignment: 'justify', style: 'textSizeReg' }],
                                                [{ text: 'Gangguan', style: 'textSizeReg'  }, { text: ':', style: 'textSizeReg'  }, { text: aght.gangguan, alignment: 'justify', style: 'textSizeReg' }],
                                                [{ text: 'Hambatan', style: 'textSizeReg'  }, { text: ':', style: 'textSizeReg'  }, { text: aght.hambatan, alignment: 'justify', style: 'textSizeReg' }],
                                                [{ text: 'Tantangan', style: 'textSizeReg'  }, { text: ':', style: 'textSizeReg'  }, { text: aght.tantangan, alignment: 'justify', style: 'textSizeReg' }]
                                            ]
                                        },
                                        layout: 'noBorders'
                                    }
                                ],
                                [
                                    '',
                                    {
                                        text: 'Saran Tindak',
                                        bold: true,
                                        style: 'textSizeReg'
                                    },
                                    {
                                        text: value.saran_tindak,
                                        alignment: 'justify',
                                        style: 'textSizeReg'
                                    },
                                ],
                            ]
                        }
                    }
                ];
            });

            const docDefinition = {
                pageMargins: [36, 36, 36, 36],
                pageSize: {
                    height: 935.43307087,
                    width: 595.27559055
                },
                pageOrientation: 'portrait',
                content: [
                    {
                        columns: [
                            {
                                table: {
                                    widths: ['*'],
                                    body: [
                                        [
                                            /* {
                                                image: logoKejaksaanPngbase64.value,
                                                width: 56,
                                                alignment: 'left',
                                                rowSpan: 2,
                                                border: [false, false, false, true],
                                                margin: [35, 0, 0, 0]
                                            }, */
                                            {
                                                text: 'KEJAKSAAN REPUBLIK INDONESIA',
                                                alignment: 'center',
                                                style: {
                                                    fontSize: 22,
                                                    bold: true
                                                },
                                                border: [false, false, false, false],
                                                // padding: [0, 0, 0, 0],
                                                // margin: [15, 0, 0, 0]
                                            }
                                        ],
                                        [
                                            // '',
                                            {
                                                text: 'KEJAKSAAN AGUNG',
                                                alignment: 'center',
                                                style: {
                                                    fontSize: 22,
                                                    bold: true
                                                },
                                                border: [false, false, false, true],
                                                // margin: [85, 0, 0, 0]
                                            }
                                        ],
                                        [
                                            {
                                                text: '\nLAPORAN KEGIATAN POSKO',
                                                alignment: 'center',
                                                style: {
                                                    fontSize: 16,
                                                    bold: true
                                                },
                                                border: [false, false, false, false],
                                                // colSpan: 2
                                            },
                                            // ''
                                        ],
                                        [
                                            {
                                                text: 'Periode: ' + tgl_laporan,
                                                alignment: 'center',
                                                style: {
                                                    fontSize: 10,
                                                    italics: true
                                                },
                                                border: [false, false, false, false],
                                                // colSpan: 2
                                            },
                                            // ''
                                        ],
                                    ]
                                }
                            }
                        ]
                    },
                    {
                        table: {
                            // headers are automatically repeated if the table spans over multiple pages
                            // you can declare how many rows should be treated as headers
                            // headerRows: 1,
                            widths: ['auto'],
                            body: data,
                        },
                        layout: 'noBorders'
                    }
                ],
                styles: {
                    textSizeReg: {
                        fontSize: 10
                    }
                }
            };

            pdfMake.createPdf(docDefinition).open();
        } else {
            Swal.fire({
                text: "Belum ada Laporan Kegiatan Posko pada tanggal yang dipilih!",
                icon: "warning",
                buttonsStyling: false,
                confirmButtonText: "Baik",
                customClass: {
                    confirmButton: "btn btn-primary"
                }
            });
        }
    } catch (error) {
        console.log(error.message);
    } finally {
        formLoading.value = false;
    }
};

onMounted(() => {
    console.log('Component Report Kegiatan Posko mounted.');

    form.value.tgl_mulai = moment().format('YYYY-MM-DD');
    form.value.tgl_selesai = moment().format('YYYY-MM-DD');
});
</script>

<template>
    <div class="card">
        <div class="card-body">
            <form method="POST" @submit.prevent="generatePdf">
                <label for="tgl_mulai" class="fs-6 fw-semibold mb-2 required">Tanggal Mulai</label>
                <input type="date" class="form-control form-control-solid" name="tgl_mulai" v-model="form.tgl_mulai"
                    required />
                <label for="tgl_selesai" class="fs-6 fw-semibold mb-2 required">Tanggal Selesai</label>
                <input type="date" class="form-control form-control-solid" name="tgl_selesai" v-model="form.tgl_selesai"
                    required />
                <br>
                <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                    <button type="submit" class="btn btn-primary btn-sm" :disabled="formLoading">
                        <span v-if="formLoading">Memuat data... <i class="fa fa-spin fa-spinner"></i></span>
                        <span v-else>Generate Laporan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
