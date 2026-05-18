<style>
    table {
        width: 100%;
        border-collapse: collapse;
    }
    table, th, td {
        border: 0px solid black;
    }
    table th, table td {
        text-align: left;
        vertical-align: top;
        padding: 8px;
    }
    th {
        background-color: #f2f2f2;
    }
    .header, .sub-header {
        text-align: center;
        vertical-align: middle;
        font-size: 18px;
        font-weight: bold;
    }
    .sub-header {
        font-size: 14px;
    }
    .image-container {
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .image-container img {
        transform: rotate(90deg); /* Rotate the image by 90 degrees */
        max-width: 100%;
        height: auto;
    }
    .no-border {
        border: none;
    }
    .no-border-top {
        border-top: none;
    }
    .no-border-bottom {
        border-bottom: none;
    }
</style>
<table align="center" border="0" style="width: 100%;">
    <tr>
        <td valign="top"  align="center" rowspan="2" ><img src="{{ public_path('assets/kejaksaan.png') }}" width="12%"> </td>
        {{-- <td valign="top" align="center"  style="font-size:22"><strong>KEJAKSAAN REPUBLIK INDONESIA</strong></td> --}}
        <td valign="top" align="center"  style="font-size:22"><strong> KEJAKSAAN REPUBLIK INDONESIA <br>KEJAKSAAN AGUNG</strong></td>
    </tr>
    <tr>
        <td valign="top" align="center" style="line-height: 1;">Jl. Sultan Hasanuddin  Nomor 1, Kebayoran Baru, Jakarta Selatan <br> Telp (021) 7236510 . www.kejaksaan.go.id</td>
    </tr>
    <tr>
        <td valign="top" width="50%" align="center" colspan="2" style="border-top: 5px solid black; height: 2px;"></td>
    </tr>
</table>
    <h4>History Video </h4>
    <table id="kt_datatable_both_scrolls" class="table table-striped table-row-bordered gy-5 gs-7 border rounded w-100">
        <thead>
            <tr class="fw-semibold fs-6 text-gray-800">
                <th class="min-w-200px">NO</th>
                <th class="min-w-150px">Device</th>
                <th class="min-w-300px">Latitude</th>
                <th class="min-w-300px">Longitude</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->obd_device }}</td>
                    <td>{{ $item->latitude }}</td>
                    <td>{{ $item->longitude }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
