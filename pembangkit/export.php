<?php
ob_start(); // Memulai output buffering
session_start();

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=data_pembangkit.xls");
header("Pragma: no-cache");
header("Expires: 0");

echo "No\tNama Perusahaan\tAlamat\tLongitude\tLatitude\tJenis Pembangkit\tFungsi\tKapasitas Terpasang (MW)\tDaya Mampu Netto (MW)\tJumlah Unit\tNo. Unit\tTahun Operasi\tStatus Operasi\tBahan Bakar Jenis\tBahan Bakar Satuan\n";

try {
    $db = new Database();
    $conn = $db->getConnection();
    $stmt = $conn->prepare("SELECT * FROM pembangkit");
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $no = 1;
    foreach ($result as $row) {
        echo "$no\t" . htmlspecialchars($row['nama_perusahaan']) . "\t" . htmlspecialchars($row['alamat']) . "\t" . 
             htmlspecialchars($row['longitude']) . "\t" . htmlspecialchars($row['latitude']) . "\t" . 
             htmlspecialchars($row['jenis_pembangkit']) . "\t" . htmlspecialchars($row['fungsi']) . "\t" . 
             htmlspecialchars($row['kapasitas_terpasang']) . "\t" . htmlspecialchars($row['daya_mampu_netto']) . "\t" . 
             htmlspecialchars($row['jumlah_unit']) . "\t" . htmlspecialchars($row['no_unit']) . "\t" . 
             htmlspecialchars($row['tahun_operasi']) . "\t" . htmlspecialchars($row['status_operasi']) . "\t" . 
             htmlspecialchars($row['bahan_bakar_jenis']) . "\t" . htmlspecialchars($row['bahan_bakar_satuan']) . "\n";
        $no++;
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

ob_end_flush(); // Mengeluarkan output buffer
?>
