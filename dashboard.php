<?php
session_start();

// Pastikan user login
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

// Daftar produk
$barang = [
    ["kode" => "B001", "nama" => "LAPTOP", "harga" => 50000],
    ["kode" => "B002", "nama" => "PRINTER", "harga" => 30000],
    ["kode" => "B003", "nama" => "SCANNER", "harga" => 20000],
    ["kode" => "B004", "nama" => "FLASHDISK", "harga" => 10000],
    ["kode" => "B005", "nama" => "MOUSE", "harga" => 8000],
];

// Random pembelian
$detail_pembelian = [];
$grandtotal = 0;

foreach ($barang as $item) {
    $beli = rand(0, 1); // 0 = tidak dibeli, 1 = dibeli
    if ($beli === 1) {
        $jumlah = rand(1, 5);
        $total_item = $item['harga'] * $jumlah;
        $grandtotal += $total_item;
        $detail_pembelian[] = [
            'kode' => $item['kode'],
            'nama' => $item['nama'],
            'harga' => $item['harga'],
            'jumlah' => $jumlah,
            'total' => $total_item
        ];
    }
}

// Hitung diskon
$diskon = 0;
if ($grandtotal > 100000) {
    $diskon = $grandtotal * 0.10; // diskon 10%
}
$total_akhir = $grandtotal - $diskon;
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Dashboard - POLGAN MART</title>
<style>
    /* ---------- STYLE UMUM ---------- */
    body {
        font-family: 'Segoe UI', Tahoma, sans-serif;
        background: linear-gradient(135deg, #e3f2fd, #e8f5e9);
        color: #333;
        margin: 0;
        padding: 0;
    }

    .navbar {
        background-color: #007bff;
        color: white;
        padding: 15px 40px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }

    .navbar h1 {
        margin: 0;
        font-size: 1.4em;
        letter-spacing: 1px;
    }

    .navbar .user-info {
        font-size: 0.9em;
    }

    .navbar a {
        color: #fff;
        text-decoration: none;
        font-weight: bold;
        margin-left: 20px;
        background-color: #dc3545;
        padding: 6px 12px;
        border-radius: 5px;
    }

    .navbar a:hover {
        background-color: #b52a36;
    }

    /* ---------- KARTU KONTEN ---------- */
    .container {
        width: 85%;
        max-width: 950px;
        margin: 40px auto;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        padding: 30px 40px;
    }

    h2 {
        color: #004d40;
        text-align: center;
        margin-top: 0;
        font-size: 1.6em;
    }

    p.desc {
        text-align: center;
        font-size: 0.9em;
        color: #777;
        margin-top: -5px;
        margin-bottom: 20px;
    }

    /* ---------- TABEL ---------- */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    th, td {
        padding: 12px 14px;
        text-align: left;
    }

    th {
        background-color: #e3f2fd;
        color: #333;
        font-weight: bold;
        border-bottom: 2px solid #90caf9;
    }

    tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    tbody tr:hover {
        background-color: #e1f5fe;
        transition: 0.2s;
    }

    .right {
        text-align: right;
    }

    /* ---------- TOTAL ---------- */
    tfoot td {
        font-weight: bold;
        border-top: 2px solid #ccc;
        padding-top: 15px;
    }

    .total-akhir {
        background-color: #e8f5e9;
        color: #2e7d32;
    }

    .diskon {
        color: #388e3c;
    }

    /* ---------- FOOTER ---------- */
    footer {
        text-align: center;
        margin: 30px 0 20px;
        font-size: 0.85em;
        color: #666;
    }
</style>
</head>
<body>
    <div class="navbar">
        <h1>POLGAN MART</h1>
        <div class="user-info">
            Halo, <?= htmlspecialchars($_SESSION['username']); ?> |
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="container">
        <h2>Dashboard Penjualan</h2>
        <p class="desc">Daftar pembelian acak ditampilkan setiap kali halaman dimuat</p>

        <table>
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($detail_pembelian)): ?>
                    <?php foreach ($detail_pembelian as $item): ?>
                    <tr>
                        <td><?= $item['kode']; ?></td>
                        <td><?= $item['nama']; ?></td>
                        <td>Rp <?= number_format($item['harga'], 0, ',', '.'); ?></td>
                        <td class="right"><?= $item['jumlah']; ?></td>
                        <td class="right">Rp <?= number_format($item['total'], 0, ',', '.'); ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" style="text-align:center; color:#888;">Tidak ada barang dibeli.</td></tr>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="right">Total Belanja:</td>
                    <td class="right">Rp <?= number_format($grandtotal, 0, ',', '.'); ?></td>
                </tr>
                <?php if ($diskon > 0): ?>
                <tr class="diskon">
                    <td colspan="4" class="right">Diskon (10%):</td>
                    <td class="right">- Rp <?= number_format($diskon, 0, ',', '.'); ?></td>
                </tr>
                <?php endif; ?>
                <tr class="total-akhir">
                    <td colspan="4" class="right">Total Akhir:</td>
                    <td class="right">Rp <?= number_format($total_akhir, 0, ',', '.'); ?></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <footer>
        © <?= date('Y'); ?> POLGAN MART - Sistem Penjualan Sederhana
    </footer>
</body>
</html>
