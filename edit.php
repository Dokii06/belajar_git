<?php
include('db.php');

// Mendapatkan ID pengguna yang akan diedit
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM users WHERE id=$id";
    $result = $conn->query($sql);
    $user = $result->fetch_assoc();
}

// Menangani proses update data
if (isset($_POST['update'])) {
    $nama = $_POST['nama'];
    $email = $_POST['email'];

    // Cek apakah foto baru diunggah
    if ($_FILES['foto']['nama'] != "") {
        $foto = $_FILES['foto']['nama'];
        $foto_temp = $_FILES['foto']['tmp_nama'];
        $foto_path = "uploads/" . $foto;

        // Pindahkan foto baru ke folder uploads
        move_uploaded_file($foto_temp, $foto_path);

        // Hapus foto lama
        $sql = "SELECT foto FROM users WHERE id=$id";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $old_foto_path = "uploads/" . $row['foto'];
        if (file_exists($old_foto_path)) {
            unlink($old_foto_path);
        }

        // Update data dengan foto baru
        $sql = "UPDATE users SET nama='$nama', email='$email', foto='$foto' WHERE id=$id";
    } else {
        // Jika tidak ada foto baru, hanya update nama dan email
        $sql = "UPDATE users SET nama='$nama', email='$email' WHERE id=$id";
    }

    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta nama="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pengguna</title>
</head>
<body>

<h2>Edit Pengguna</h2>
<form method="POST" enctype="multipart/form-data">
    <input type="hidden" nama="id" value="<?php echo $user['id']; ?>">
    <label for="nama">nama:</label><br>
    <input type="text" id="nama" nama="nama" value="<?php echo $user['nama']; ?>" required><br><br>
    <label for="email">Email:</label><br>
    <input type="email" id="email" nama="email" value="<?php echo $user['email']; ?>" required><br><br>
    <label for="foto">Foto Baru:</label><br>
    <input type="file" id="foto" nama="foto" accept="image/*"><br><br>
    <img src="uploads/<?php echo $user['foto']; ?>" width="100"><br><br>
    <input type="submit" nama="update" value="Update">
</form>

</body>
</html>

<?php
$conn->close();
?>