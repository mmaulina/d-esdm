<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_user']) || !isset($_SESSION['role'])) {
    echo "&lt;script&gt;alert('Silakan login terlebih dahulu!'); window.location.href='login.php';&lt;/script&gt;";
    exit;
}

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

$id_user = $_SESSION['id_user'];
$role = $_SESSION['role'];

try {
    require_once 'koneksi.php';
    $db = new Database();
    $conn = $db->getConnection();

    if ($role === 'adminbulanan' || $role === 'superadmin') {
        $stmt = $conn->prepare("
            SELECT 
                lb.id as lb_id, lb.id_user, lb.tahun, lb.bulan, lb.nama_perusahaan, lb.no_hp_pimpinan, 
                lb.tenaga_teknik, lb.no_hp_teknik, lb.nama, lb.no_hp, lb.no_telp_kantor, lb.kabupaten, 
                lb.produksi_sendiri, lb.pemb_sumber_lain, lb.susut_jaringan, lb.penj_ke_pelanggan, 
                lb.penj_ke_pln, lb.pemakaian_sendiri,
                pb.id as pb_id, pb.alamat, pb.longitude, pb.latitude, pb.jenis_pembangkit, pb.fungsi, pb.kapasitas_terpasang,
                pb.daya_mampu_netto, pb.jumlah_unit, pb.no_unit, pb.tahun_operasi, 
                pb.status_operasi, pb.bahan_bakar_jenis, pb.bahan_bakar_satuan, pb.volume_bb
            FROM laporan_bulanan lb
            LEFT JOIN pembangkit pb ON lb.id_user = pb.id_user
            ORDER BY lb.id, pb.id
        ");
    } else {
        $stmt = $conn->prepare("
            SELECT 
                lb.id as lb_id, lb.id_user, lb.tahun, lb.bulan, lb.nama_perusahaan, lb.no_hp_pimpinan, 
                lb.tenaga_teknik, lb.no_hp_teknik, lb.nama, lb.no_hp, lb.no_telp_kantor, lb.kabupaten, 
                lb.produksi_sendiri, lb.pemb_sumber_lain, lb.susut_jaringan, lb.penj_ke_pelanggan, 
                lb.penj_ke_pln, lb.pemakaian_sendiri,
                pb.id as pb_id, pb.alamat, pb.longitude, pb.latitude, pb.jenis_pembangkit, pb.fungsi, pb.kapasitas_terpasang,
                pb.daya_mampu_netto, pb.jumlah_unit, pb.no_unit, pb.tahun_operasi, 
                pb.status_operasi, pb.bahan_bakar_jenis, pb.bahan_bakar_satuan, pb.volume_bb
            FROM laporan_bulanan lb
            LEFT JOIN pembangkit pb ON lb.id_user = pb.id_user
            WHERE lb.id_user = :id_user
            ORDER BY lb.id, pb.id
        ");
        $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
    }

    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Kelompokkan data per lb_id
    $grouped = [];
    foreach ($rows as $row) {
        $lb_id = $row['lb_id'];
        if (!isset($grouped[$lb_id])) {
            $grouped[$lb_id] = [
                'laporan' => $row,
                'pembangkit' => []
            ];
        }

        // Masukkan data pembangkit ke array pembangkit, jika pb_id null (tidak ada pembangkit), tetap push nilai kosong
        if ($row['pb_id'] !== null) {
            $grouped[$lb_id]['pembangkit'][] = [
                'alamat' => $row['alamat'],
                'latitude' => $row['latitude'],
                'longitude' => $row['longitude'],
                'jenis_pembangkit' => $row['jenis_pembangkit'],
                'fungsi' => $row['fungsi'],
                'kapasitas_terpasang' => $row['kapasitas_terpasang'],
                'daya_mampu_netto' => $row['daya_mampu_netto'],
                'jumlah_unit' => $row['jumlah_unit'],
                'no_unit' => $row['no_unit'],
                'tahun_operasi' => $row['tahun_operasi'],
                'status_operasi' => $row['status_operasi'],
                'bahan_bakar_jenis' => $row['bahan_bakar_jenis'],
                'bahan_bakar_satuan' => $row['bahan_bakar_satuan'],
                'volume_bb' => $row['volume_bb']
            ];
        } else {
            // Jika tidak ada pembangkit, pastikan array pembangkit kosong tapi ada 1 entry kosong agar tetap muncul 1 row
            if (count($grouped[$lb_id]['pembangkit']) === 0) {
                $grouped[$lb_id]['pembangkit'][] = [
                    'alamat' => '',
                    'latitude' => '',
                    'longitude' => '',
                    'jenis_pembangkit' => '',
                    'fungsi' => '',
                    'kapasitas_terpasang' => '',
                    'daya_mampu_netto' => '',
                    'jumlah_unit' => '',
                    'no_unit' => '',
                    'tahun_operasi' => '',
                    'status_operasi' => '',
                    'bahan_bakar_jenis' => '',
                    'bahan_bakar_satuan' => '',
                    'volume_bb' => ''
                ];
            }
        }
    }

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Judul
    $sheet->setCellValue('A1', 'LAPORAN BULANAN');
    $sheet->mergeCells('A1:X1');
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    // Header - 3 baris
    $sheet->setCellValue('A2', 'No.')->mergeCells('A2:A4');
    $sheet->setCellValue('B2', 'Nama Perusahaan')->mergeCells('B2:B4');
    $sheet->setCellValue('C2', 'Tahun')->mergeCells('C2:C4');
    $sheet->setCellValue('D2', 'Bulan')->mergeCells('D2:D4');
    $sheet->setCellValue('E2', 'Data Pembangkit')->mergeCells('E2:G2');
    $sheet->setCellValue('H2', 'Data Teknis Pembangkit')->mergeCells('H2:Q2');
    $sheet->setCellValue('R2', 'Pelaporan Bulanan')->mergeCells('R2:X2');

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

    $headerStyle = [
        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F81BD']],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
    ];
    $sheet->getStyle('A2:X4')->applyFromArray($headerStyle);

    $rowNum = 5;
    $no = 1;

    foreach ($grouped as $group) {
        $laporan = $group['laporan'];
        $pembangkits = $group['pembangkit'];
        $firstPembangkit = true;

        foreach ($pembangkits as $pb) {
            if ($firstPembangkit) {
                $sheet->setCellValue('A' . $rowNum, $no);
                $sheet->setCellValue('B' . $rowNum, $laporan['nama_perusahaan']);
                $sheet->setCellValue('C' . $rowNum, $laporan['tahun']);
                $sheet->setCellValue('D' . $rowNum, $laporan['bulan']);
                $sheet->setCellValue('S' . $rowNum, $laporan['produksi_sendiri']);
                $sheet->setCellValue('T' . $rowNum, $laporan['pemb_sumber_lain']);
                $sheet->setCellValue('U' . $rowNum, $laporan['susut_jaringan']);
                $sheet->setCellValue('V' . $rowNum, $laporan['penj_ke_pelanggan']);
                $sheet->setCellValue('W' . $rowNum, $laporan['penj_ke_pln']);
                $sheet->setCellValue('X' . $rowNum, $laporan['pemakaian_sendiri']);
                $firstPembangkit = false;
                $no++;
            } else {
                // Kosongkan kolom laporan bulanan pada baris berikut untuk menghindari duplikasi
                $sheet->setCellValue('A' . $rowNum, '');
                $sheet->setCellValue('B' . $rowNum, '');
                $sheet->setCellValue('C' . $rowNum, '');
                $sheet->setCellValue('D' . $rowNum, '');
                $sheet->setCellValue('S' . $rowNum, '');
                $sheet->setCellValue('T' . $rowNum, '');
                $sheet->setCellValue('U' . $rowNum, '');
                $sheet->setCellValue('V' . $rowNum, '');
                $sheet->setCellValue('W' . $rowNum, '');
                $sheet->setCellValue('X' . $rowNum, '');
            }

            // Isi data pembangkit untuk semua baris (termasuk baris pertama)
            $sheet->setCellValue('E' . $rowNum, $pb['alamat']);
            $sheet->setCellValue('F' . $rowNum, $pb['latitude']);
            $sheet->setCellValue('G' . $rowNum, $pb['longitude']);
            $sheet->setCellValue('H' . $rowNum, $pb['jenis_pembangkit']);
            $sheet->setCellValue('I' . $rowNum, $pb['fungsi']);
            $sheet->setCellValue('J' . $rowNum, $pb['kapasitas_terpasang']);
            $sheet->setCellValue('K' . $rowNum, $pb['daya_mampu_netto']);
            $sheet->setCellValue('L' . $rowNum, $pb['jumlah_unit']);
            $sheet->setCellValue('M' . $rowNum, $pb['no_unit']);
            $sheet->setCellValue('N' . $rowNum, $pb['tahun_operasi']);
            $sheet->setCellValue('O' . $rowNum, $pb['status_operasi']);
            $sheet->setCellValue('P' . $rowNum, $pb['bahan_bakar_jenis']);
            $sheet->setCellValue('Q' . $rowNum, $pb['bahan_bakar_satuan']);
            $sheet->setCellValue('R' . $rowNum, $pb['volume_bb']);

            $rowNum++;
        }
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
?>

