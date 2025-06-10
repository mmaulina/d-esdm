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

function parseIndoNumber($str)
{
    // Hapus titik ribuan, ganti koma menjadi titik
    $str = str_replace('.', '', $str);
    $str = str_replace(',', '.', $str);
    return is_numeric($str) ? (float)$str : $str;
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
            id, id_user, alamat, longitude, latitude, jenis_pembangkit, fungsi, 
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
    $sheet->mergeCells('A1:X1');
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    // Header - 3 baris
    $sheet->setCellValue('A2', 'No.')->mergeCells('A2:A4');
    $sheet->setCellValue('B2', 'Nama Perusahaan')->mergeCells('B2:B4');

    // Pindahkan header pembangkit ke mulai dari C
    $sheet->setCellValue('C2', 'Data Pembangkit')->mergeCells('C2:E2');
    $sheet->setCellValue('F2', 'Data Teknis Pembangkit')->mergeCells('F2:O2');
    $sheet->setCellValue('P2', 'Konsumsi Bahan Bakar')->mergeCells('P2:P4'); // kolom volume
    $sheet->setCellValue('Q2', 'Tahun')->mergeCells('Q2:Q4'); // kolom tahun
    $sheet->setCellValue('R2', 'Bulan')->mergeCells('R2:R4'); // kolom bulan
    $sheet->setCellValue('S2', 'Pelaporan Bulanan')->mergeCells('S2:X2');

    // Baris 3
    $sheet->setCellValue('C3', 'Alamat Pembangkit')->mergeCells('C3:C4');
    $sheet->setCellValue('D3', 'Latitude')->mergeCells('D3:D4');
    $sheet->setCellValue('E3', 'Longitude')->mergeCells('E3:E4');
    $sheet->setCellValue('F3', 'Jenis Pembangkit')->mergeCells('F3:F4');
    $sheet->setCellValue('G3', 'Fungsi')->mergeCells('G3:G4');
    $sheet->setCellValue('H3', 'Kapasitas Terpasang (MW)')->mergeCells('H3:H4');
    $sheet->setCellValue('I3', 'Daya Mampu Netto (MW)')->mergeCells('I3:I4');
    $sheet->setCellValue('J3', 'Jumlah Unit')->mergeCells('J3:J4');
    $sheet->setCellValue('K3', 'No. Unit')->mergeCells('K3:K4');
    $sheet->setCellValue('L3', 'Tahun Operasi')->mergeCells('L3:L4');
    $sheet->setCellValue('M3', 'Status Operasi')->mergeCells('M3:M4');
    $sheet->setCellValue('N3', 'Jenis BB')->mergeCells('N3:N4');
    $sheet->setCellValue('O3', 'Satuan BB')->mergeCells('O3:O4');

    $sheet->setCellValue('S3', 'Produksi Sendiri (kWh)')->mergeCells('S3:S4');
    $sheet->setCellValue('T3', 'Pembelian Sumber Lain (kWh)')->mergeCells('T3:T4');
    $sheet->setCellValue('U3', 'Susut Jaringan (kWh)')->mergeCells('U3:U4');
    $sheet->setCellValue('V3', 'Penjualan ke Pelanggan (kWh)')->mergeCells('V3:V4');
    $sheet->setCellValue('W3', 'Penjualan ke PLN (kWh)')->mergeCells('W3:W4');
    $sheet->setCellValue('X3', 'Pemakaian Sendiri (kWh)')->mergeCells('X3:X4');

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
    $sheet->getStyle('A2:P4')->applyFromArray($headerStyle);
    $sheet->getStyle('Q2:X4')->applyFromArray($headerStyle2);

    $rowNum = 5;
    $no = 1;

    // Iterate through each user group
    foreach ($laporan_grouped as $id_user => $laporans) {
        $namaPerusahaan = isset($laporans[0]['nama_perusahaan']) ? $laporans[0]['nama_perusahaan'] : 'Tidak Diketahui';
        $sheet->setCellValue('A' . $rowNum, "Nama Perusahaan: $namaPerusahaan");
        $sheet->mergeCells("A{$rowNum}:X{$rowNum}");
        $sheet->getStyle("A{$rowNum}:X{$rowNum}")->getFont()->setBold(true);
        // Atur background kuning
        $sheet->getStyle("A{$rowNum}:X{$rowNum}")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
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
                $sheet->setCellValue('Q' . $rowNum, $laporan['tahun']); // Pindah ke Q
                $sheet->setCellValue('R' . $rowNum, $laporan['bulan']); // Pindah ke R
                $sheet->setCellValue('S' . $rowNum, parseIndoNumber($laporan['produksi_sendiri']));
                $sheet->setCellValue('T' . $rowNum, parseIndoNumber($laporan['pemb_sumber_lain']));
                $sheet->setCellValue('U' . $rowNum, parseIndoNumber($laporan['susut_jaringan']));
                $sheet->setCellValue('V' . $rowNum, parseIndoNumber($laporan['penj_ke_pelanggan']));
                $sheet->setCellValue('W' . $rowNum, parseIndoNumber($laporan['penj_ke_pln']));
                $sheet->setCellValue('X' . $rowNum, parseIndoNumber($laporan['pemakaian_sendiri']));
            } else {
                // Kosongkan kolom laporan bulanan jika tidak ada
                $sheet->setCellValue('A' . $rowNum, '');
                $sheet->setCellValue('B' . $rowNum, '');
                $sheet->setCellValue('C' . $rowNum, '');
                $sheet->setCellValue('Q' . $rowNum, '');
                $sheet->setCellValue('R' . $rowNum, '');
                $sheet->setCellValue('T' . $rowNum, '');
                $sheet->setCellValue('U' . $rowNum, '');
                $sheet->setCellValue('V' . $rowNum, '');
                $sheet->setCellValue('W' . $rowNum, '');
                $sheet->setCellValue('X' . $rowNum, '');
            }

            // Fill in pembangkit data if available
            if (isset($pembangkit_grouped[$id_user]) && $i < $pembangkit_count) {
                $pb = $pembangkit_grouped[$id_user][$i];
                $sheet->setCellValue('C' . $rowNum, $pb['alamat']);
                $sheet->setCellValue('D' . $rowNum, $pb['latitude']);
                $sheet->setCellValue('E' . $rowNum, $pb['longitude']);
                $sheet->setCellValue('F' . $rowNum, $pb['jenis_pembangkit']);
                $sheet->setCellValue('G' . $rowNum, $pb['fungsi']);
                $sheet->setCellValue('H' . $rowNum, $pb['kapasitas_terpasang']);
                $sheet->setCellValue('I' . $rowNum, $pb['daya_mampu_netto']);
                $sheet->setCellValue('J' . $rowNum, $pb['jumlah_unit']);
                $sheet->setCellValue('K' . $rowNum, $pb['no_unit']);
                $sheet->setCellValue('L' . $rowNum, $pb['tahun_operasi']);
                $sheet->setCellValue('M' . $rowNum, $pb['status_operasi']);
                $sheet->setCellValue('N' . $rowNum, $pb['bahan_bakar_jenis']);
                $sheet->setCellValue('O' . $rowNum, $pb['bahan_bakar_satuan']);
                $sheet->setCellValue('P' . $rowNum, parseIndoNumber($pb['volume_bb']));
            } else {
                // Kosongkan kolom pembangkit jika tidak ada
                $sheet->setCellValue('C' . $rowNum, '');
                $sheet->setCellValue('D' . $rowNum, '');
                $sheet->setCellValue('E' . $rowNum, '');
                $sheet->setCellValue('F' . $rowNum, '');
                $sheet->setCellValue('G' . $rowNum, '');
                $sheet->setCellValue('H' . $rowNum, '');
                $sheet->setCellValue('I' . $rowNum, '');
                $sheet->setCellValue('J' . $rowNum, '');
                $sheet->setCellValue('K' . $rowNum, '');
                $sheet->setCellValue('L' . $rowNum, '');
                $sheet->setCellValue('M' . $rowNum, '');
                $sheet->setCellValue('N' . $rowNum, '');
                $sheet->setCellValue('O' . $rowNum, '');
                $sheet->setCellValue('P' . $rowNum, '');
            }

            $rowNum++;
            $no++;
        }

        // Add extra spacing before next user
        $rowNum += 2;
    }

    $sheet->getStyle('A5:X' . ($rowNum - 1))->applyFromArray([
        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
    ]);

    foreach (range('A', 'X') as $col) {
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
