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
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;

$id_user = $_SESSION['id_user'];
$role = $_SESSION['role'];

try {
    require_once 'koneksi.php';
    $db = new Database();
    $conn = $db->getConnection();

    if ($role === 'admin' || $role === 'superadmin') {
        $stmt = $conn->prepare("SELECT * FROM laporan_bulanan");
    } else {
        $stmt = $conn->prepare("SELECT * FROM laporan_bulanan WHERE id_user = :id_user");
        $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
    }

    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Judul
    $sheet->setCellValue('A1', 'LAPORAN BULANAN');
    $sheet->mergeCells('A1:X1');
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    // Header - 3 baris
    // Baris 2
    $sheet->setCellValue('A2', 'No.')->mergeCells('A2:A4');
    $sheet->setCellValue('B2', 'Nama Perusahaan')->mergeCells('B2:B4');
    $sheet->setCellValue('C2', 'Tahun')->mergeCells('C2:C4');
    $sheet->setCellValue('D2', 'Bulan')->mergeCells('D2:D4');
    $sheet->setCellValue('E2', 'Data Pembangkit')->mergeCells('E2:G2');
    $sheet->setCellValue('H2', 'Data Teknis Pembangkit')->mergeCells('H2:Q2');
    $sheet->setCellValue('R2', 'Pelaporan Bulanan')->mergeCells('R2:X2');

    // Baris 3
    $sheet->setCellValue('E3', 'Alamat Pembangkit')->mergeCells('E3:E4');
    $sheet->setCellValue('F3', 'Latitude')->mergeCells('F3:F4');
    $sheet->setCellValue('G3', 'Longitude')->mergeCells('G3:G4');
    $sheet->setCellValue('H3', 'Jenis Pembangkit')->mergeCells('H3:H4');
    $sheet->setCellValue('I3', 'Fungsi')->mergeCells('I3:I4');
    $sheet->setCellValue('J3', 'Kapasitas Terpasang (MW)')->mergeCells('J3:J4');
    $sheet->setCellValue('K3', 'Daya Mampu Netto (MW)')->mergeCells('K3:K4');
    $sheet->setCellValue('L3', 'Jumlah Unit')->mergeCells('L3:L4');
    $sheet->setCellValue('M3', 'No. Unit')->mergeCells('M3:M4');
    $sheet->setCellValue('N3', 'Tahun Operasi')->mergeCells('N3:N4');
    $sheet->setCellValue('O3', 'Status Operasi')->mergeCells('O3:O4');
    $sheet->setCellValue('P3', 'Jenis BB')->mergeCells('P3:P4');
    $sheet->setCellValue('Q3', 'Satuan BB')->mergeCells('Q3:Q4');
    $sheet->setCellValue('R3', 'Volume BB')->mergeCells('R3:R4');
    $sheet->setCellValue('S3', 'Produksi Sendiri (kWh)')->mergeCells('S3:S4');
    $sheet->setCellValue('T3', 'Pembelian Sumber Lain (kWh)')->mergeCells('T3:T4');
    $sheet->setCellValue('U3', 'Susut Jaringan (kWh)')->mergeCells('U3:U4');
    $sheet->setCellValue('V3', 'Penjualan ke Pelanggan (kWh)')->mergeCells('V3:V4');
    $sheet->setCellValue('W3', 'Penjualan ke PLN (kWh)')->mergeCells('W3:W4');
    $sheet->setCellValue('X3', 'Pemakaian Sendiri (kWh)')->mergeCells('X3:X4');

    // Style Header
    $headerStyle = [
        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F81BD']],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
    ];
    $sheet->getStyle('A2:X4')->applyFromArray($headerStyle);

    // Isi Data
    $rowNum = 5;
    $no = 1;
    foreach ($data as $row) {
        $sheet->setCellValue('A' . $rowNum, $no++);
        $sheet->setCellValue('B' . $rowNum, $row['nama_perusahaan']);
        $sheet->setCellValue('C' . $rowNum, $row['tahun']);
        $sheet->setCellValue('D' . $rowNum, $row['bulan']);
        $sheet->setCellValue('E' . $rowNum, $row['alamat']);
        $sheet->setCellValue('F' . $rowNum, $row['latitude']);
        $sheet->setCellValue('G' . $rowNum, $row['longitude']);
        $sheet->setCellValue('H' . $rowNum, $row['jenis_pembangkit']);
        $sheet->setCellValue('I' . $rowNum, $row['fungsi']);
        $sheet->setCellValue('J' . $rowNum, $row['kapasitas_terpasang']);
        $sheet->setCellValue('K' . $rowNum, $row['daya_mampu_netto']);
        $sheet->setCellValue('L' . $rowNum, $row['jumlah_unit']);
        $sheet->setCellValue('M' . $rowNum, $row['no_unit']);
        $sheet->setCellValue('N' . $rowNum, $row['tahun_operasi']);
        $sheet->setCellValue('O' . $rowNum, $row['status_operasi']);
        $sheet->setCellValue('P' . $rowNum, $row['bahan_bakar_jenis']);
        $sheet->setCellValue('Q' . $rowNum, $row['bahan_bakar_satuan']);
        $sheet->setCellValue('R' . $rowNum, $row['volume_bb']);
        $sheet->setCellValue('S' . $rowNum, $row['produksi_sendiri']);
        $sheet->setCellValue('T' . $rowNum, $row['pemb_sumber_lain']);
        $sheet->setCellValue('U' . $rowNum, $row['susut_jaringan']);
        $sheet->setCellValue('V' . $rowNum, $row['penj_ke_pelanggan']);
        $sheet->setCellValue('W' . $rowNum, $row['penj_ke_pln']);
        $sheet->setCellValue('X' . $rowNum, $row['pemakaian_sendiri']);
        $rowNum++;
    }

    // Border data
    $sheet->getStyle('A5:X' . ($rowNum - 1))->applyFromArray([
        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
    ]);

    // Auto-size kolom
    foreach (range('A', 'X') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Simpan file
    $fileName = 'Laporan_Bulanan_' . time() . '.xlsx';
    $filePath = 'exports/' . $fileName;

    if (!file_exists('exports')) {
        mkdir('exports', 0777, true);
    }

    foreach (glob('exports/*.xlsx') as $file) {
        if (filemtime($file) < time() - 86400) {
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
?>
