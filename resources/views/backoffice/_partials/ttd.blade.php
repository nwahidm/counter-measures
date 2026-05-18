@if ($status == 'preview')
    <table class="table" border='0' width=100% style="font-family:Arial, Helvetica, sans-serif; font-size:16;">
        <tr>
            <td width=30%></td>
            <td width=70% style="text-align: center;">@if (isset($pelaksana)) {{ $pelaksana->jenis }}  @endif{{ ucwords(strtolower($pejabat->nama_struktur)) }} @if($main == 0)<br>@endif{{ucwords(strtolower($last_name))}},</td>
        </tr>
		@if (isset($pelaksana) && $pelaksana->jenis <> "Plt.")
		<tr>
			<td width=50%></td>
			<td width=50% style="text-align: center;">{{ ucwords(strtolower(auth()->user()->organisasi->jabatan->nama_jabatan)) }},</td>
		</tr>
		@endif
        <tr>
            <td style="text-align: center;"><br>
                <br><br><br><br><br><br>
            </td>
            <td style="text-align: center;">
                
            </td>
        <tr>
            <td></td>
			@if (isset($pelaksana))
			<td style="text-align: center;">
				@if(auth()->user()->organisasi->jabatan->eselon > 1)
				<u>{{ $pejabat->nama }}</u><br>
				  {{ $pejabat->pangkat }} NIP. {{ $pejabat->nip }} 
				@else
					{{ $pejabat->nama }}
				@endif
			</td>
			@else
            <td style="text-align: center;">
                @if($surat->eselon > 1)
				<u>{{ $pejabat->nama }}</u><br>
                  {{ $pejabat->pangkat }} NIP. {{ $pejabat->nip }} 
				@else
					{{ $pejabat->nama }}
				@endif
            </td>
			@endif
        </tr>
        </tr>
    </table>
    @else
        <table class="table" border='0' width=100% style="font-family:Arial, Helvetica, sans-serif; font-size:16;">
            <tr>
                <td width=30%></td>
                <td width=70% style="text-align: center;">{{ ucwords(strtolower($pejabat->nama_struktur)) }} @if($main == 0)<br>@endif {{ucwords(strtolower($last_name))}},</td>
            </tr>
            <tr>
                <td style="text-align: center;"><br>
                    Powered By<br><img src="{{ public_path('assets/image/bsre.png') }}" width="150" alt="Balai Sertifikat Elektronik">
                </td>
                <td style="text-align: center;">
                    <table border='0' width=100%>
                        <tr>
                            <?php $path = url('storage/upload/suratkeluar/'); ?>
                            <td width=50% valign=top align=right><img style="width: 90px;" src="data:image/png;base64, {{ base64_encode(QrCode::format('png')->errorCorrection('H')->merge(public_path('assets/image/kejaksaannew2.jpg'), .3, true)->size(800)->margin(0)->generate(footerQrContent($host_media,'/storage/suratkeluar/',$filename_signed, $surat->id_surat_keluar ?? ''))) }} "> </td>
                            <td width=50% valign=top align=left style="font-size:13px;"><br><br>Dokumen ini telah
                                <br>ditandatangani secara Elektronik </td>
                        </tr>
                    </table>
                </td>
            <tr>
                <td></td>
                <td style="text-align: center;">
                    @if(auth()->user()->organisasi->jabatan->eselon > 1)
					<u>{{ $pejabat->nama }}</u><br>
					  {{ $pejabat->pangkat }} NIP. {{ $pejabat->nip }} 
					@else
						{{ $pejabat->nama }}
					@endif
                </td>
            </tr>
            </tr>
        </table>
    @endif    
@endif