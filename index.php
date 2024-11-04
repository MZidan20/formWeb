<?php
session_start();

$error = '';
$nim = '';
$nama = '';
$jenis_kelamin = '';
$alamat = '';
$no_hp = '';
$email = '';


function clean_text($string) {
    $string = trim($string);
    $string = stripslashes($string);
    $string = htmlspecialchars($string);
    return $string;
}

if (isset($_POST["submit"])) {
    if (empty($_POST["nim"])) {
        $error .= '<p><label class="text-danger">Please Enter your NIM</label></p>';
    } else {
        $nim = clean_text($_POST["nim"]);
    }

    if (empty($_POST["nama"])) {
        $error .= '<p><label class="text-danger">Please Enter your Name</label></p>';
    } else {
        $nama = clean_text($_POST["nama"]);
        if (!preg_match("/^[a-zA-Z ]*$/", $nama)) {
            $error .= '<p><label class="text-danger">Only letters and white space allowed</label></p>';
        }
    }

    if (empty($_POST["jenis_kelamin"])) {
        $error .= '<p><label class="text-danger">Please select your Gender</label></p>';
    } else {
        $jenis_kelamin = clean_text($_POST["jenis_kelamin"]);
    }

    if (empty($_POST["alamat"])) {
        $error .= '<p><label class="text-danger">Address is required</label></p>';
    } else {
        $alamat = clean_text($_POST["alamat"]);
    }

    if (empty($_POST["no_hp"])) {
        $error .= '<p><label class="text-danger">Please Enter your Phone Number</label></p>';
    } else {
        $no_hp = clean_text($_POST["no_hp"]);
    }

    if (empty($_POST["email"])) {
        $error .= '<p><label class="text-danger">Please Enter your Email</label></p>';
    } else {
        $email = clean_text($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error .= '<p><label class="text-danger">Invalid email format</label></p>';
        }
    }

    if ($error == '') {
        if (!isset($_SESSION['form_data'])) {
            $_SESSION['form_data'] = [];
        }

        $_SESSION['form_data'][] = [
            'nim' => $nim,
            'nama' => $nama,
            'jenis_kelamin' => $jenis_kelamin,
            'alamat' => $alamat,
            'no_hp' => $no_hp,
            'email' => $email
        ];

   
        $error = '<label class="text-success">Data collected successfully!</label>';
        $nim = '';
        $nama = '';
        $jenis_kelamin = '';
        $alamat = '';
        $no_hp = '';
        $email = '';
    }
}


if (isset($_POST["download"])) {
    if (isset($_SESSION['form_data']) && count($_SESSION['form_data']) > 0) {
        $file_open = fopen("data.csv", "w");
        fputcsv($file_open, ['NIM', 'Nama', 'Jenis Kelamin', 'Alamat', 'No HP', 'Email']); // Add header

        foreach ($_SESSION['form_data'] as $data) {
            fputcsv($file_open, $data);
        }

        fclose($file_open);
        // Reset session data after download
        unset($_SESSION['form_data']);
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="data.csv"');
        readfile("data.csv");
        exit();
    } else {
        $error = '<p><label class="text-danger">No data to download</label></p>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Input Data Mahasiswa</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            color: #2c3e50;
            min-height: 100vh;
        }
        .container {
            margin-top: 50px;
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding: 0 15px;
        }
        .header-title {
            font-weight: 600;
            color: #3498db;
            margin: 0;
            font-size: 24px;
        }
        .form-container {
            background: #ffffff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        .form-group label {
            font-weight: 500;
            color: #34495e;
            margin-bottom: 8px;
        }
        .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 10px 15px;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }
        select.form-control {
            appearance: none;
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1em;
            padding-right: 2.5rem; 
            width: 100%; 
            height: 100%;
        }
        .btn-info {
            background-color: #3498db;
            border: none;
            padding: 12px 25px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }
        .btn-success {
            background-color: #2ecc71;
            border: none;
            padding: 12px 25px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }
        .btn-info:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
        }
        .btn-success:hover {
            background-color: #27ae60;
            transform: translateY(-2px);
        }
        .text-danger {
            color: #e74c3c;
            font-size: 14px;
        }
        .text-success {
            color: #2ecc71;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-container">
            <h2 class="header-title">Form Input Data Mahasiswa</h2>
            <form method="post" style="margin: 0;">
                <input type="submit" name="download" class="btn btn-success" value="Download Data CSV" />
            </form>
        </div>
        <div class="form-container">
            <form method="post">
                <?php echo $error; ?>
                <div class="form-group">
                    <label>NIM</label>
                    <input type="text" name="nim" placeholder="Masukan NIM" class="form-control" value="<?php echo $nim; ?>" />
                </div>
                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" name="nama" class="form-control" placeholder="Masukan Nama" value="<?php echo $nama; ?>" />
                </div>
                <div class="form-group">
                    <label>Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-control">
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="Laki-laki" <?php if ($jenis_kelamin == 'Laki-laki') echo 'selected'; ?>>Laki-laki</option>
                        <option value="Perempuan" <?php if ($jenis_kelamin == 'Perempuan') echo 'selected'; ?>>Perempuan</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Alamat</label>
                    <textarea name="alamat" class="form-control" placeholder="Masukan Alamat" rows="3"><?php echo $alamat; ?></textarea>
                </div>
                <div class="form-group">
                    <label>No HP</label>
                    <input type="text" name="no_hp" class="form-control" placeholder="Masukan Nomor Handphone" value="<?php echo $no_hp; ?>" />
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" placeholder="Masukan Email" value="<?php echo $email; ?>" />
                </div>
                <div class="form-group" style="text-align: center; margin-top: 25px;">
                    <input type="submit" name="submit" class="btn btn-info" value="Submit" />
                </div>
            </form>
        </div>
    </div>
</body>
</html>
