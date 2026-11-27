<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - Lapor Bersih Kampus</title>

    <!-- PWA Manifest -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#198754">
    <meta name="description" content="Sistem Pelaporan Kebersihan Kampus">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #e8f5e9, #f5faf5);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Arial, sans-serif;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            background: white;
            padding: 35px;
            border-radius: 18px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        }

        .logo {
            width: 65px;
            height: 65px;
            background: #198754;
            color: white;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            margin: 0 auto 18px;
        }

        .login-title {
            color: #198754;
            font-weight: bold;
        }

        .form-control {
            padding: 12px;
            border-radius: 9px;
        }

        .btn-login {
            background: #198754;
            color: white;
            padding: 12px;
            border-radius: 9px;
            font-weight: bold;
        }

        .btn-login:hover {
            background: #146c43;
            color: white;
        }
    
body {
    min-height: 100vh;
    min-height: 100dvh;
    margin: 0;
    padding: 20px 12px;
    background: #f5f5f5;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: Arial, sans-serif;
}

/* Kartu login */
.login-card {
    width: 100%;
    max-width: 420px;
    min-height: 0;
    height: auto;
    padding: 0;
    background: #ffffff;
    border-radius: 18px;
    overflow: hidden;
    display: block;
    box-shadow: 0 8px 28px rgba(0, 0, 0, 0.08);
}

/* Header hijau EcoCampus */
.login-card > .text-center:first-child {
    height: auto;
    min-height: 170px;
    background: #198754;
    color: white;
    padding: 22px 20px 36px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

/* Lingkaran logo */
.login-card .logo {
    width: 58px;
    height: 58px;
    background: white;
    color: #198754;
    border-radius: 50%;
    font-size: 27px;
    margin: 0 auto 10px;
}

/* Judul EcoCampus */
.login-card .login-title {
    color: white;
    font-size: 23px;
    font-weight: 700;
    margin-bottom: 5px;
}

/* Slogan */
.login-card > .text-center:first-child p {
    color: #e6f5ec !important;
    font-size: 13px;
    margin-bottom: 0 !important;
}

/* Formulir putih */
.login-card form {
    position: relative;
    z-index: 1;
    background: white;
    margin-top: -22px;
    padding: 22px 26px 8px;
    border-radius: 24px 24px 0 0;
}

/* Teks sambutan */
.login-intro h2 {
    color: #183c30;
    font-size: 22px;
    font-weight: 700;
    margin-bottom: 6px;
}

.login-intro p {
    color: #587365;
    font-size: 13px;
    line-height: 1.4;
    margin-bottom: 16px;
}

/* Jarak antarkolom */
.login-card .mb-3 {
    margin-bottom: 12px !important;
}

.login-card .mb-4 {
    margin-bottom: 16px !important;
}

/* Label */
.login-card .form-label {
    color: #183c30;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 6px;
}

/* Input */
.login-card .form-control {
    min-height: 44px;
    padding: 9px 12px;
    background: #f1faf4;
    border: 1px solid #cce5d4;
    border-radius: 11px;
    font-size: 14px;
}

.login-card .form-control:focus {
    background: white;
    border-color: #198754;
    box-shadow: 0 0 0 3px rgba(25, 135, 84, 0.12);
}

/* Tombol login */
.login-card .btn-login {
    width: 100%;
    min-height: 46px;
    background: #198754;
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 15px;
    font-weight: 600;
}

.login-card .btn-login:hover {
    background: #157347;
    color: white;
}

/* Footer */
.login-card > .text-center.mt-4 {
    margin-top: 0 !important;
    padding: 10px 16px 16px;
}

/* Responsivitas */
@media (max-width: 480px) {
    body {
        padding: 12px;
        align-items: flex-start;
    }

    .login-card {
        max-width: 100%;
        min-height: 0;
        height: auto;
    }

    .login-card form {
        padding: 20px 22px 8px;
    }
}
</style>
</head>

<body>

    <div class="login-card">

        <div class="text-center">
            <div class="logo">♻</div>

            <h3 class="login-title">Lapor Bersih Kampus</h3>

            <p class="text-muted mb-4">
                Silakan login untuk melanjutkan
            </p>
        </div>

        <?php if (session()->getFlashdata('error')) : ?>
            <div class="alert alert-danger">
                <?= esc(session()->getFlashdata('error')) ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')) : ?>
            <div class="alert alert-success">
                <?= esc(session()->getFlashdata('success')) ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('errors')) : ?>
            <div class="alert alert-danger">
                <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                    <div><?= esc($error) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="<?= site_url('login') ?>" method="post">

            <?= csrf_field() ?><div class="login-intro">
    <h2>Selamat datang!</h2>
    <p>
        Masuk untuk melaporkan dan memantau
        kebersihan kampus.
    </p>
</div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>

                <input
                    type="email"
                    class="form-control"
                    id="email"
                    name="email"
                    placeholder="Masukkan email"
                    value="<?= old('email') ?>"
                    required
                >
            </div>

            <div class="mb-4">
                <label for="password" class="form-label">Password</label>

                <input
                    type="password"
                    class="form-control"
                    id="password"
                    name="password"
                    placeholder="Masukkan password"
                    required
                >
            </div>

            <button type="submit" class="btn btn-login w-100">
                Login
            </button>

        </form>

        <div class="text-center mt-4">
            <small class="text-muted">
                &copy; <?= date('Y') ?> Lapor Bersih Kampus
            </small>
        </div>

    </div>

    <!-- Registrasi Service Worker -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function () {
                navigator.serviceWorker.register('/service-worker.js')
                    .then(function (registration) {
                        console.log('Service Worker berhasil:', registration.scope);
                    })
                    .catch(function (error) {
                        console.error('Service Worker gagal:', error);
                    });
            });
        }
    </script>

</body>
</html>