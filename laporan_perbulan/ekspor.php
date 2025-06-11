<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_user']) || !isset($_SESSION['role'])) {
    echo "<script>alert('Silakan login terlebih dahulu!'); window.location.href='login.php';</script>";
    exit;
}

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat; // Tambahkan ini

function parseIndoNumber($str)
{
    // Hapus titik ribuan, ganti koma menjadi titik
    $str = str_replace('.', '', $str);
    $str = str_replace(',', '.', $str);
    if (is_numeric($str)) {
        return (float)$str;
    } else {
        return null; // Atau nilai default lain, seperti 0
    }
}

try {
    require_once 'koneksi.php';
    $db = new Database();
    $conn = $db->getConnection();

    // Fetch all laporan_bulanan ordered by id_user then id
    $stmt = $conn->prepare("
        SELECT 
            id, id_user, tahun, bulan, nama_perusahaan, no_hp_pimpinan, 
            tenaga_teknik, no_hp_teknik, nama, no_hp, no_telp_kantor, kabupaten, 
            produksi_sendiri, pemb_sumber_lain, susut_jaringan, penj_ke_pelanggan, 
            penj_ke_pln, pemakaian_sendiri
        FROM laporan_bulanan WHERE status = 'diterima'
        ORDER BY id_user, id
    ");
    $stmt->execute();
    $laporan_rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch all pembangkit ordered by id_user then id
    $stmt = $conn->prepare("
        SELECT 
            id, id_user, tahun, bulan, alamat, longitude, latitude, jenis_pembangkit, fungsi, 
            kapasitas_terpasang, daya_mampu_netto, jumlah_unit, no_unit, tahun_operasi, 
            status_operasi, bahan_bakar_jenis, bahan_bakar_satuan, volume_bb
        FROM pembangkit WHERE status = 'diterima'
        ORDER BY id_user, id
    ");
    $stmt->execute();
    $pembangkit_rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Group laporan by id_user
    $laporan_grouped = [];
    foreach ($laporan_rows as $row) {
        $laporan_grouped[$row['id_user']][] = $row;
    }

    // Group pembangkit by id_user
    $pembangkit_grouped = [];
    foreach ($pembangkit_rows as $row) {
        $pembangkit_grouped[$row['id_user']][] = $row;
    }

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Judul
    $sheet->setCellValue('A1', 'LAPORAN BULANAN DAN DATA PEMBANGKIT');
    $sheet->mergeCells('A1:Z1'); // Update mergeCells
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    // Header - 3 baris
    $sheet->setCellValue('A2', 'No.')->mergeCells('A2:A4');
    $sheet->setCellValue('B2', 'Nama Perusahaan')->mergeCells('B2:B4');

    // Pindahkan header pembangkit ke mulai dari C
    $sheet->setCellValue('C2', 'Data Pembangkit')->mergeCells('C2:G2'); // Update mergeCells
    $sheet->setCellValue('H2', 'Data Teknis Pembangkit')->mergeCells('H2:Q2'); // Update mergeCells
    $sheet->setCellValue('R2', 'Konsumsi Bahan Bakar')->mergeCells('R2:R4'); // kolom volume
    $sheet->setCellValue('S2', 'Tahun')->mergeCells('S2:S4'); // kolom tahun
    $sheet->setCellValue('T2', 'Bulan')->mergeCells('T2:T4'); // kolom bulan
    $sheet->setCellValue('U2', 'Pelaporan Bulanan')->mergeCells('U2:Z2'); // Update mergeCells

    // Baris 3
    $sheet->setCellValue('C3', 'Tahun')->mergeCells('C3:C4'); // Tambah Tahun
    $sheet->setCellValue('D3', 'Bulan')->mergeCells('D3:D4'); // Tambah Bulan
    $sheet->setCellValue('E3', 'Alamat Pembangkit')->mergeCells('E3:E4'); // Geser Alamat
    $sheet->setCellValue('F3', 'Latitude')->mergeCells('F3:F4'); // Geser Latitude
    $sheet->setCellValue('G3', 'Longitude')->mergeCells('G3:G4'); // Geser Longitude
    $sheet->setCellValue('H3', 'Jenis Pembangkit')->mergeCells('H3:H4'); // Geser Jenis Pembangkit
    $sheet->setCellValue('I3', 'Fungsi')->mergeCells('I3:I4'); // Geser Fungsi
    $sheet->setCellValue('J3', 'Kapasitas Terpasang (MW)')->mergeCells('J3:J4'); // Geser Kapasitas
    $sheet->setCellValue('K3', 'Daya Mampu Netto (MW)')->mergeCells('K3:K4'); // Geser Daya
    $sheet->setCellValue('L3', 'Jumlah Unit')->mergeCells('L3:L4'); // Geser Jumlah Unit
    $sheet->setCellValue('M3', 'No. Unit')->mergeCells('M3:M4'); // Geser No Unit
    $sheet->setCellValue('N3', 'Tahun Operasi')->mergeCells('N3:N4'); // Geser Tahun Operasi
    $sheet->setCellValue('O3', 'Status Operasi')->mergeCells('O3:O4'); // Geser Status Operasi
    $sheet->setCellValue('P3', 'Jenis BB')->mergeCells('P3:P4'); // Geser Jenis BB
    $sheet->setCellValue('Q3', 'Satuan BB')->mergeCells('Q3:Q4'); // Geser Satuan BB

    $sheet->setCellValue('U3', 'Produksi Sendiri (kWh)')->mergeCells('U3:U4'); // Geser Produksi
    $sheet->setCellValue('V3', 'Pembelian Sumber Lain (kWh)')->mergeCells('V3:V4'); // Geser Pembelian
    $sheet->setCellValue('W3', 'Susut Jaringan (kWh)')->mergeCells('W3:W4'); // Geser Susut
    $sheet->setCellValue('X3', 'Penjualan ke Pelanggan (kWh)')->mergeCells('X3:X4'); // Geser Penjualan Pelanggan
    $sheet->setCellValue('Y3', 'Penjualan ke PLN (kWh)')->mergeCells('Y3:Y4'); // Geser Penjualan PLN
    $sheet->setCellValue('Z3', 'Pemakaian Sendiri (kWh)')->mergeCells('Z3:Z4'); // Geser Pemakaian Sendiri

    $headerStyle = [
        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F81BD']],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]

    ];
    $headerStyle2 = [
        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F9D69']],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]

    ];
    $sheet->getStyle('A2:R4')->applyFromArray($headerStyle); // Update range
    $sheet->getStyle('S2:Z4')->applyFromArray($headerStyle2); // Update range

    $rowNum = 5;
    $no = 1;

    // Iterate through each user group
    foreach ($laporan_grouped as $id_user => $laporans) {
        $namaPerusahaan = isset($laporans[0]['nama_perusahaan']) ? $laporans[0]['nama_perusahaan'] : 'Tidak Diketahui';
        $sheet->setCellValue('A' . $rowNum, "Nama Perusahaan: $namaPerusahaan");
        $sheet->mergeCells("A{$rowNum}:Z{$rowNum}"); // Update mergeCells
        $sheet->getStyle("A{$rowNum}:Z{$rowNum}")->getFont()->setBold(true);
        // Atur background kuning
        $sheet->getStyle("A{$rowNum}:Z{$rowNum}")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('FFFF00'); // Kuning (hex FFFF00)
        $rowNum++;


        // Get the number of laporan and pembangkit for the current user
        $laporan_count = count($laporans);
        $pembangkit_count = isset($pembangkit_grouped[$id_user]) ? count($pembangkit_grouped[$id_user]) : 0;
        $max_count = max($laporan_count, $pembangkit_count);

        // Fill in data for each row
        for ($i = 0; $i < $max_count; $i++) {
            // Fill in laporan data if available
            if ($i < $laporan_count) {
                $laporan = $laporans[$i];
                $sheet->setCellValue('A' . $rowNum, $no);
                $sheet->setCellValue('B' . $rowNum, $laporan['nama_perusahaan']);
                $sheet->setCellValue('S' . $rowNum, $laporan['tahun']); // Pindah ke S
                $sheet->setCellValue('T' . $rowNum, $laporan['bulan']); // Pindah ke T

                // Format angka dengan NumberFormat
                $produksi_sendiri = parseIndoNumber($laporan['produksi_sendiri']);
                $pemb_sumber_lain = parseIndoNumber($laporan['pemb_sumber_lain']);
                $susut_jaringan = parseIndoNumber($laporan['susut_jaringan']);
                $penj_ke_pelanggan = parseIndoNumber($laporan['penj_ke_pelanggan']);
                $penj_ke_pln = parseIndoNumber($laporan['penj_ke_pln']);
                $pemakaian_sendiri = parseIndoNumber($laporan['pemakaian_sendiri']);

                // Set nilai dan format angka jika valid
                if ($produksi_sendiri !== null) {
                    $sheet->setCellValue('U' . $rowNum, $produksi_sendiri);
                    $sheet->getStyle('U' . $rowNum)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                }
                if ($pemb_sumber_lain !== null) {
                    $sheet->setCellValue('V' . $rowNum, $pemb_sumber_lain);
                    $sheet->getStyle('V' . $rowNum)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                }
                if ($susut_jaringan !== null) {
                    $sheet->setCellValue('W' . $rowNum, $susut_jaringan);
                    $sheet->getStyle('W' . $rowNum)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                }
                if ($penj_ke_pelanggan !== null) {
                    $sheet->setCellValue('X' . $rowNum, $penj_ke_pelanggan);
                    $sheet->getStyle('X' . $rowNum)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                }
                if ($penj_ke_pln !== null) {
                    $sheet->setCellValue('Y' . $rowNum, $penj_ke_pln);
                    $sheet->getStyle('Y' . $rowNum)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                }
                if ($pemakaian_sendiri !== null) {
                    $sheet->setCellValue('Z' . $rowNum, $pemakaian_sendiri);
                    $sheet->getStyle('Z' . $rowNum)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                }
            } else {
                // Kosongkan kolom laporan bulanan jika tidak ada
                $sheet->setCellValue('A' . $rowNum, '');
                $sheet->setCellValue('B' . $rowNum, '');
                $sheet->setCellValue('C' . $rowNum, '');
                $sheet->setCellValue('S' . $rowNum, '');
                $sheet->setCellValue('T' . $rowNum, '');
                $sheet->setCellValue('U' . $rowNum, '');
                $sheet->setCellValue('V' . $rowNum, '');
                $sheet->setCellValue('W' . $rowNum, '');
                $sheet->setCellValue('X' . $rowNum, '');
                $sheet->setCellValue('Y' . $rowNum, '');
                $sheet->setCellValue('Z' . $rowNum, '');
            }

            // Fill in pembangkit data if available
            if (isset($pembangkit_grouped[$id_user]) && $i < $pembangkit_count) {
                $pb = $pembangkit_grouped[$id_user][$i];
                $sheet->setCellValue('C' . $rowNum, $pb['tahun']); // Tambah Tahun
                $sheet->setCellValue('D' . $rowNum, $pb['bulan']); // Tambah Bulan
                $sheet->setCellValue('E' . $rowNum, $pb['alamat']); // Geser Alamat
                $sheet->setCellValue('F' . $rowNum, $pb['latitude']); // Geser Latitude
                $sheet->setCellValue('G' . $rowNum, $pb['longitude']); // Geser Longitude
                $sheet->setCellValue('H' . $rowNum, $pb['jenis_pembangkit']); // Geser Jenis Pembangkit
                $sheet->setCellValue('I' . $rowNum, $pb['fungsi']); // Geser Fungsi
                $sheet->setCellValue('J' . $rowNum, $pb['kapasitas_terpasang']); // Geser Kapasitas
                $sheet->setCellValue('K' . $rowNum, $pb['daya_mampu_netto']); // Geser Daya
                $sheet->setCellValue('L' . $rowNum, $pb['jumlah_unit']); // Geser Jumlah Unit
                $sheet->setCellValue('M' . $rowNum, $pb['no_unit']); // Geser No Unit
                $sheet->setCellValue('N' . $rowNum, $pb['tahun_operasi']); // Geser Tahun Operasi
                $sheet->setCellValue('O' . $rowNum, $pb['status_operasi']); // Geser Status Operasi
                $sheet->setCellValue('P' . $rowNum, $pb['bahan_bakar_jenis']); // Geser Jenis BB
                $sheet->setCellValue('Q' . $rowNum, $pb['bahan_bakar_satuan']); // Geser Satuan BB

                // Format volume bahan bakar
                $volume_bb = parseIndoNumber($pb['volume_bb']);
                if ($volume_bb !== null) {
                    $sheet->setCellValue('R' . $rowNum, $volume_bb);
                    $sheet->getStyle('R' . $rowNum)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                }
            } else {
                // Kosongkan kolom pembangkit jika tidak ada
                $sheet->setCellValue('C' . $rowNum, ''); // Tambah Tahun
                $sheet->setCellValue('D' . $rowNum, ''); // Tambah Bulan
                $sheet->setCellValue('E' . $rowNum, ''); // Geser Alamat
                $sheet->setCellValue('F' . $rowNum, ''); // Geser Latitude
                $sheet->setCellValue('G' . $rowNum, ''); // Geser Longitude
                $sheet->setCellValue('H' . $rowNum, ''); // Geser Jenis Pembangkit
                $sheet->setCellValue('I' . $rowNum, ''); // Geser Fungsi
                $sheet->setCellValue ('J' . $rowNum, ''); // Geser Kapasitas
                $sheet->setCellValue('K' . $rowNum, ''); // Geser Daya
                $sheet->setCellValue('L' . $rowNum, ''); // Geser Jumlah Unit
                $sheet->setCellValue('M' . $rowNum, ''); // Geser No Unit
                $sheet->setCellValue('N' . $rowNum, ''); // Geser Tahun Operasi
                $sheet->setCellValue('O' . $rowNum, ''); // Geser Status Operasi
                $sheet->setCellValue('P' . $rowNum, ''); // Geser Jenis BB
                $sheet->setCellValue('Q' . $rowNum, ''); // Geser Satuan BB
                $sheet->setCellValue('R' . $rowNum, ''); // Geser Volume
            }

            $rowNum++;
            $no++;
        }

        // Add extra spacing before next user
        $rowNum += 2;
    }

    $sheet->getStyle('A5:Z' . ($rowNum - 1))->applyFromArray([ // Update range
        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
    ]);

    foreach (range('A', 'Z') as $col) { // Update range
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    $fileName = 'Laporan_Bulanan_' . time() . '.xlsx';
    $filePath = 'exports/' . $fileName;

    if (!file_exists('exports')) {
        mkdir('exports', 0777, true);
    }

    foreach (glob('exports/*.xlsx') as $file) {
        if (filemtime($file) < time() - 30) {
            unlink($file);
        }
    }

    $writer = new Xlsx($spreadsheet);
    $writer->save($filePath);

    echo "
    <html>
    <head>
        <script>
            function startDownload() {
                document.getElementById('loading').style.display = 'block';
                setTimeout(() => {
                    window.location.href = '$filePath';
                    setTimeout(() => {
                        document.getElementById('loading').innerHTML = 'Download selesai, kembali ke halaman...';
                        setTimeout(() => {
                            window.location.href = '?page=laporan_perbulan';
                        }, 2000);
                    }, 2000);
                }, 1000);
            }
        </script>
        <style>
            #loading {
                display: none;
                width: 100%;
                height: 100vh;
                position: fixed;
                top: 0;
                left: 0;
                background: rgba(0, 0, 0, 0.7);
                color: white;
                font-size: 20px;
                text-align: center;
                line-height: 100vh;
                z-index: 9999;
            }
        </style>
    </head>
    <body onload='startDownload()'>
        <div id='loading'>Sedang mengunduh, harap tunggu...</div>
    </body>
    </html>
    ";
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
