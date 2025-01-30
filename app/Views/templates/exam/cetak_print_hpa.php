<script>
    function cetakPrintHpa() {
        var detailMakroskopis = document.getElementById('print_hpa') ? document.getElementById('print_hpa').value : '';
        var printWindow = window.open('', '', 'height=500,width=900');
        printWindow.document.write(`
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Laporan Radiologi</title>
	<style>
		body{
			width : 346px;
			font-size : 24px;
		}
		table, tr, td{
			border : none;
			margin: 15px;
			font-size : 22px;
		}
        td{
            height: 10px;
            padding: 0 0 0 0;
        }
		.bordered_table, .bordered_table tr, .bordered_table td{
			border : none;
			border-collapse : collapse;
		}
		@media print {
		.header, .hide { visibility: hidden }
		}
		.break { page-break-before: always; }
	</style>
</head>
<body>
    <table width="900"  onload="window.print();">
		<tr>
		<td align="center" nowrap="nowrap"  style="font-size: 24px";><b>RSUD DR. M. SOEWANDHIE</b><br/>
			Jl. Tambakrejo No. 45 - 47, KOTA SURABAYA			 </td>

		</tr>
        <tr>
            <td >
                <table width="500">
		<tr><td width=100 nowrap="nowrap">No RM</td><td> : </td><td nowrap="nowrap">&nbsp;&nbsp;789479</td></tr>
                    <tr><td width=100 nowrap="nowrap">No Register</td><td> : </td><td nowrap="nowrap" >&nbsp;&nbsp;FRS.67/25</td></tr>
                    <tr><td width=100 nowrap="nowrap">Nama </td><td> : </td><td>&nbsp; TIARA CHIKA AGUSTI</td></tr>
                    <tr><td width=100 >Alamat </td><td>:</td><td> &nbsp; ASEM JAYA 10/15&nbsp; </td></tr>
                    <!-- <tr><td width=100 nowrap="nowrap">Tanggal Lahir</td><td> : </td><td nowrap="nowrap">&nbsp; 2001-04-17</td></tr>-->
                    <tr><td width=100 nowrap="nowrap">Jenis & Tgl Lahir</td><td> : </td><td nowrap="nowrap">&nbsp; P  / 2001-04-17</td></tr>
                    <tr><td width=100 nowrap="nowrap">Permintaan </td><td> : </td><td nowrap="nowrap">&nbsp; FNAB*</td></tr>
		    <tr><td width=100 nowrap="nowrap">Unit Asal </td><td> : </td><td  nowrap="nowrap">&nbsp; KLINIK BEDAH</td></tr>
		    <tr><td width=100 nowrap="nowrap">Dokter Pengirim </td><td> : </td><td  nowrap="nowrap">&nbsp; dr. Ihyan Amri, Sp.B.</td></tr>
		</table>
            </td>
            <td >
                <table width="500">
                    <tr>
                        <td align="center" width="500" nowrap="nowrap" colspan=3><h3>UNIT PATOLOGI ANATOMI</h3> </td>
                    </tr>
                    <tr>
                        <!-- <td align="center" width="500" colspan=3>Surabaya, 30-01-2025</td>-->
                    </tr>
					<tr>
                        <td align="center" width="500" colspan=3>&nbsp;</td>
                    </tr>
					<tr><td width=100 nowrap="nowrap">Tanggal Terima </td><td> : </td><td>&nbsp; 30-01-2025 09:00:00</td></tr>
					<tr><td width=100 nowrap="nowrap">Tanggal Hasil </td><td> : </td><td>&nbsp; 30-01-2025 15:00:00</td></tr>
                </table>
            </td>
        </tr>
        <tr ><td align="center" colspan="2" >&nbsp;<b>HASIL PEMERIKSAAN</b></td></tr>
        <tr height="500">
            <td colspan="2" height="400" valign="top">
                <table width="800pt" height="80"><tbody><tr><td style="border: none;" width="200pt"><font size="5" face="verdana"><b>LOKASI  </b></font></td><td style="border: none;" width="10pt"><font size="5" face="verdana"><b>:</b><br></font></td><td style="border: none;" width="590pt"><font size="5" face="verdana"><b>R. Axilla S<br></b></font></td></tr><tr><td style="border: none;" width="200pt"><font size="5" face="verdana"><b>DIAGNOSA KLINIK</b></font></td><td style="border: none;" width="10pt"><font size="5" face="verdana"><b>:</b><br></font></td><td style="border: none;" width="590pt"><font size="5" face="verdana"><b>Limpadenopati r. Axilla S<br></b></font></td></tr><tr><td style="border: none;" width="200pt"><font size="5" face="verdana"><b>ICD</b></font></td><td style="border: none;" width="10pt"><font size="5" face="verdana"><b>:</b></font></td><td style="border: none;" width="590pt"><font size="5" face="verdana"><br></font></td></tr></tbody></table><font size="5" face="verdana"><b>LAPORAN PEMERIKSAAN:<br></b></font><div><font size="5" face="verdana"><b> MAKROSKOPIK :</b></font></div><div><font size="5" face="verdana">Dilakukan2x puncture pada nodul r. axilla S, ukuran 1 x 1 cm, batas tegas, mobile, padat kenyal.<br></font></div><div><font size="5" face="verdana"><br></font></div><div><font size="5" face="verdana"><b>MIKROSKOPIK :</b><br></font></div><font size="5" face="verdana">Hapusan mengandung sebaran debris nekrotik dan beberapa sel radang MN dan beberapa sel histiosit dan sedikit sel PMN. Tidak tampak tanda granuloma pada sediaan ini.<br></font><div><font size="5" face="verdana"><br></font></div><div><font size="5"><font face="verdana"><b>KESIMPULAN :</b> Nodul r. axilla S, FNAB :<br></font></font></div><div><font size="5" face="verdana"><b>PROSES RADANG KRONIS SUPPURATIF <br></b></font></div><div><font size="5" face="verdana"><b>(FOLLICULITIS KRONIS)<br></b></font></div>            </td>
        </tr>
		<tr>
			<td width="700">
				&nbsp;
			</td>
			<td align="center">
				Terimakasih,
				<p>
					<br/>
					<br/>
				</p>
				dr. Ayu Tyasmara Pratiwi, Sp.PA			</td>
		</tr>
    </table>
<!--     <table width="600">
        <tr><td></td></tr>
        <tr><td></td></tr>
        <tr><td></td></tr>
        <tr><td></td></tr>
        <tr> <td>&nbsp;<br/></td></tr>
        <tr> <td></td></tr>
    </table>
	-->

</body>
</html>
    `);

        printWindow.document.close();
        printWindow.print();
    }
</script>