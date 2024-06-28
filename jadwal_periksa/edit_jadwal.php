<?php
include_once "../config.php";
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['login'])) {
    echo "<meta http-equiv='refresh' content='0; url=..'>";
    die();
}

$nama = $_SESSION['username'];
$akses = $_SESSION['akses'];
$id_dokter = $_SESSION['id'];

// Periksa apakah pengguna adalah dokter
if ($akses != 'dokter') {
    echo "<meta http-equiv='refresh' content='0; url=..'>";
    die();
}

// Ambil id jadwal dari URL
$url = $_SERVER['REQUEST_URI'];
$url = explode("/", $url);
$id = $url[count($url) - 1];

// Ambil data jadwal periksa berdasarkan id
$jadwal = query("SELECT * FROM jadwal_periksa WHERE id = $id")[0];

// Proses update jadwal periksa
if (isset($_POST["submit"])) {
    // Ambil data dari form
    $data = [
        'hari' => $_POST['hari'],
        'jam_mulai' => $_POST['jam_mulai'],
        'jam_selesai' => $_POST['jam_selesai'],
        'status' => $_POST['status']
    ];

    // Lakukan validasi form di sini jika diperlukan

    // Update jadwal periksa
    if (updateJadwalPeriksa($data, $id) > 0) {
        echo "<script>
                alert('Data berhasil diupdate');
                window.location.href = '../index_jadwal.php';
              </script>";
        exit;
    } else {
        echo "<script>
                alert('Data gagal diupdate');
                window.location.href = '../index_jadwal.php';
              </script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Dokter</title>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    
        <link rel="stylesheet" href="css/style2.css">

    <!--font google-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Teachers:ital,wght@0,400..800;1,400..800&display=swap" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Teachers:ital,wght@0,400..800;1,400..800&display=swap" rel="stylesheet">
</head>
<body>

<!--main content-->
<div class="main">
            <div class="container-fluid py-2" style="background-color:#FFF5E4;">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <h3></h3>
                        </div>
                        <div class="mx-auto">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Memeriksa pasien</h3>
                                </div>

<div class="container">
    <h2>Edit Jadwal Periksa</h2>
    <form action="" id="editJadwalForm" method="POST">
        <input type="hidden" name="id_dokter" value="<?=$id_dokter?>">
        <div class="form-group">
            <label for="hari">Hari</label>
            <select name="hari" id="hari" class="form-control">
                <?php
                $hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                foreach ($hari as $h):
                    if ($h == $jadwal['hari']) {
                        echo "<option value='$h' selected>$h</option>";
                    } else {
                        echo "<option value='$h'>$h</option>";
                    }
                endforeach;
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="jam_mulai">Jam Mulai</label>
            <input type="time" name="jam_mulai" id="jam_mulai" class="form-control" value="<?= date('H:i', strtotime($jadwal['jam_mulai'])) ?>">
        </div>
        <div class="form-group">
            <label for="jam_selesai">Jam Selesai</label>
            <input type="time" name="jam_selesai" id="jam_selesai" class="form-control" value="<?= date('H:i', strtotime($jadwal['jam_selesai'])) ?>">
        </div>
        
        <div class="form-group">
            <label>Status</label>
            <div>
                <input type="radio" name="status" value="Y" <?= ($jadwal['status'] == 'Y') ? 'checked' : '' ?>> Y
                <input type="radio" name="status" value="N" <?= ($jadwal['status'] == 'N') ? 'checked' : '' ?>> N
            </div>
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>

<script>
    // Validasi form saat submit
    document.getElementById('editJadwalForm').addEventListener('submit', function(e) {
        let jamMulai = document.getElementById('jam_mulai').value;
        let jamSelesai = document.getElementById('jam_selesai').value;

        // Validasi jam mulai dan jam selesai
        if (jamMulai >= jamSelesai) {
            e.preventDefault();
            alert('Jam mulai tidak boleh lebih dari atau sama dengan jam selesai');
        }
    });
</script>

</body>
</html>
