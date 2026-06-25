<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - RM LEZAT JAYA</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(135deg, #ff7e5f 0%, #feb47b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            overflow-x: hidden;
        }
        .login-container {
            width: 100%;
            max-width: 420px;
            padding: 15px;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.5);
            padding: 40px 30px;
            transition: transform 0.3s ease;
        }
        .login-card:hover {
            transform: translateY(-5px);
        }
        .brand-logo {
            text-align: center;
            margin-bottom: 30px;
        }
        .brand-logo i {
            font-size: 3rem;
            color: #ff5e36;
            background: rgba(255, 94, 54, 0.1);
            padding: 15px;
            border-radius: 50%;
            display: inline-block;
            margin-bottom: 10px;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        .brand-name {
            font-weight: 700;
            color: #333;
            font-size: 1.6rem;
            letter-spacing: 0.5px;
        }
        .brand-sub {
            color: #777;
            font-size: 0.9rem;
        }
        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            border: 1px solid #ddd;
            background-color: #fcfcfc;
            transition: all 0.3s;
        }
        .form-control:focus {
            background-color: #fff;
            border-color: #ff5e36;
            box-shadow: 0 0 0 0.25rem rgba(255, 94, 54, 0.25);
        }
        .btn-login {
            background: linear-gradient(135deg, #ff5e36 0%, #ff7e5f 100%);
            border: none;
            color: white;
            padding: 12px;
            font-weight: 600;
            border-radius: 10px;
            width: 100%;
            transition: all 0.3s;
        }
        .btn-login:hover {
            opacity: 0.9;
            transform: translateY(1px);
            box-shadow: 0 5px 15px rgba(255, 94, 54, 0.4);
        }
        .input-group-text {
            background-color: #fcfcfc;
            border-right: none;
            border-radius: 10px 0 0 10px;
            border: 1px solid #ddd;
            color: #777;
        }
        .input-group .form-control {
            border-left: none;
            border-radius: 0 10px 10px 0;
        }
        .input-group-text-focus {
            border-color: #ff5e36;
            color: #ff5e36;
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="login-card">
        <div class="brand-logo">
            <i class="bi bi-shop"></i>
            <div class="brand-name">RM LEZAT JAYA</div>
            <div class="brand-sub">Sistem Kasir Rumah Makan</div>
        </div>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 10px;">
                <i class="bi bi-exclamation-triangle-fill me-2"></i><?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('auth/login') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label for="username" class="form-label text-secondary fw-semibold">Username</label>
                <div class="input-group">
                    <span class="input-group-text" id="basic-addon1"><i class="bi bi-person"></i></span>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username" required value="<?= old('username') ?>">
                </div>
            </div>
            <div class="mb-4">
                <label for="password" class="form-label text-secondary fw-semibold">Password</label>
                <div class="input-group">
                    <span class="input-group-text" id="basic-addon2"><i class="bi bi-lock"></i></span>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
                </div>
            </div>
            <button type="submit" class="btn btn-login mb-2">Masuk ke Sistem</button>
        </form>
    </div>
</div>

<!-- Bootstrap Bundle JS -->
<script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
<script>
    // Visual focus effect for input groups
    const inputs = document.querySelectorAll('.form-control');
    inputs.forEach(input => {
        input.addEventListener('focus', () => {
            const group = input.closest('.input-group');
            if (group) {
                group.querySelector('.input-group-text').style.borderColor = '#ff5e36';
                group.querySelector('.input-group-text').style.color = '#ff5e36';
            }
        });
        input.addEventListener('blur', () => {
            const group = input.closest('.input-group');
            if (group) {
                group.querySelector('.input-group-text').style.borderColor = '#ddd';
                group.querySelector('.input-group-text').style.color = '#777';
            }
        });
    });
</script>
</body>
</html>
