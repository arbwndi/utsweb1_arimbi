<?php
session_start();

// Jika belum login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}



// Tambah barang ke keranjang
if (isset($_POST['tambah'])) {
    $kode = $_POST['kode'];
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $jumlah = $_POST['jumlah'];

    $total = $harga * $jumlah;

    $_SESSION['keranjang'][] = [
        'kode' => $kode,
        'nama' => $nama,
        'harga' => $harga,
        'jumlah' => $jumlah,
        'total' => $total
    ];
}

// Kosongkan keranjang
if (isset($_POST['clear'])) {
    unset($_SESSION['keranjang']);
}

// Hitung total
$totalBelanja = 0;
if (isset($_SESSION['keranjang'])) {
    foreach ($_SESSION['keranjang'] as $b) {
        $totalBelanja += $b['total'];
    }
}

$diskon = $totalBelanja * 0.10;
$totalBayar = $totalBelanja - $diskon;

?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Dashboard Penjualan</title>
<style>
body{
    font-family: Arial, sans-serif;
    background: #eef1f7;
    margin:0;
    padding:0;
}
.navbar{
    display:flex;
    justify-content:space-between;
    padding:20px;
    background:white;
    box-shadow:0 2px 4px rgba(0,0,0,0.1);
}
.container{
    width:80%;
    margin:30px auto;
    background:white;
    padding:30px;
    border-radius:15px;
    box-shadow:0 4px 10px rgba(0,0,0,0.1);
}
input,button{
    padding:12px;
    width:100%;
    margin-top:5px;
    border-radius:8px;
    border:1px solid #ccc;
}
button{
    width:auto;
    background:#0d6efd;
    color:white;
    border:none;
    cursor:pointer;
    margin-right:10px;
}
button:hover{
    opacity:0.8;
}
table{
    width:100%;
    margin-top:20px;
    border-collapse:collapse;
}
table th, table td{
    border-bottom:1px solid #ddd;
    padding:10px;
    text-align:left;
}
.total-row{
    font-weight:bold;
    text-align:right;
}
</style>
</head>

<body>

<div class="navbar">
    <div><strong>--POLGAN MART--</strong><br><small>Sistem Penjualan Sederhana</small></div>
    <div>
        Selamat datang, <strong><?= $_SESSION['username'] ?></strong>  
        <form method="post" action="logout.php" style="display:inline;">
            <button style="background:#dc3545; color:white;">Logout</button>
        </form>
    </div>
</div>

<div class="container">
    <h3>Input Barang</h3>

    <form method="post">

        <label>Kode Barang</label>
        <input type="text" name="kode" autocomplete="off" placeholder="Masukan Kode Barang" required>

        <label>Nama Barang</label>
        <input type="text" name="nama"autocomplete="off"  placeholder="Masukan Nama Barang" required>

        <label>Harga</label>
        <input type="number" name="harga" autocomplete="off"  placeholder="Masukan Harga" required>

        <label>Jumlah</label>
        <input type="number" name="jumlah" autocomplete="off"  placeholder="Masukan Jumlah" required>

        <br><br>
        <button type="submit" name="tambah">Tambahkan</button>
        <button type="reset" style="background:#6c757d;">Batal</button>
    </form>

    <h3 style="text-align:center; margin-top:40px;">Daftar Pembelian</h3>

    <table>
        <tr>
            <th>Kode</th>
            <th>Nama Barang</th>
            <th>Harga</th>
            <th>Jumlah</th>
            <th>Total</th>
        </tr>

        <?php if (!empty($_SESSION['keranjang'])): ?>
            <?php foreach ($_SESSION['keranjang'] as $b): ?>
            <tr>
                <td><?= $b['kode'] ?></td>
                <td><?= $b['nama'] ?></td>
                <td>Rp <?= number_format($b['harga'],0,',','.') ?></td>
                <td><?= $b['jumlah'] ?></td>
                <td>Rp <?= number_format($b['total'],0,',','.') ?></td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>

        <tr class="total-row">
            <td colspan="4">Total Belanja</td>
            <td>Rp <?= number_format($totalBelanja,0,',','.') ?></td>
        </tr>

        <tr class="total-row">
            <td colspan="4">Diskon (10%)</td>
            <td>Rp <?= number_format($diskon,0,',','.') ?></td>
        </tr>

        <tr class="total-row">
            <td colspan="4">Total Bayar</td>
            <td>Rp <?= number_format($totalBayar,0,',','.') ?></td>
        </tr>
    </table>

    <form method="post">
        <button name="clear" style="background:#dc3545; margin-top:20px;">Kosongkan Keranjang</button>
    </form>
</div>

</body>
</html>
