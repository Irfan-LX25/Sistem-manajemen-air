<?php
// koneksi ke database MySQL
session_start();
include './assets/func.php';
$air = new klas_air();
$koneksi = $air->koneksi();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Login - Kelompok2</title>
        <link rel="icon" href="/assets/img/logo.png">
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <style>
            :root {
                --neo-bg: #dff4f7;
                --neo-surface: #d8eff2;
                --neo-surface-2: #e4f6f8;
                --neo-text: #12333d;
                --neo-muted: #5a7480;
                --neo-accent: #5dade2;
                --neo-shadow-dark: rgba(123, 177, 184, 0.45);
                --neo-shadow-light: rgba(255, 255, 255, 0.82);
            }

            body.login-page {
                min-height: 100vh;
                margin: 0;
                overflow: hidden;
                background:
                    linear-gradient(145deg, #d8eff2, #e3f7f9 40%, #d4eaee);
                color: var(--neo-text);
            }

            .auth-page {
                min-height: calc(100vh - 44px);
                width: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 16px;
                box-sizing: border-box;
            }

            .auth-card {
                width: min(460px, 100%);
                background: linear-gradient(145deg, #e9fafb, #d2e9ed);
                border: 1px solid rgba(255, 255, 255, 0.52);
                border-radius: 22px;
                box-shadow:
                    -14px -14px 30px rgba(255, 255, 255, 0.9),
                    14px 14px 30px rgba(123, 177, 184, 0.42),
                    inset 1px 1px 0 rgba(255, 255, 255, 0.6);
                padding: 28px;
            }

            .auth-logo {
                width: 150px;
                height: 150px;
                display: block;
                margin: 0 auto 14px;
                object-fit: contain;
                background: transparent;
                border-radius: 0;
                padding: 0;
                box-shadow:
                    none;
            }

            .auth-title {
                margin: 0;
                text-align: center;
                font-size: 1.6rem;
                font-weight: 800;
                color: var(--neo-text);
            }

            .auth-subtitle {
                margin: 8px 0 24px;
                text-align: center;
                color: var(--neo-muted);
                font-size: 0.95rem;
            }

            .auth-card .form-floating > .form-control {
                background: linear-gradient(145deg, #fefefe, #e0f2f5) !important;
                border: 1px solid rgba(255, 255, 255, 0.56);
                border-radius: 14px;
                box-shadow:
                    inset -7px -7px 14px rgba(255, 255, 255, 0.98),
                    inset 7px 7px 14px rgba(123, 177, 184, 0.22),
                    0 2px 0 rgba(255, 255, 255, 0.55);
            }

            .auth-card .form-floating > label {
                color: var(--neo-muted);
            }

            .auth-row {
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 12px;
                flex-wrap: wrap;
                margin-top: 4px;
                color: var(--neo-muted);
            }

            .btn-login {
                min-width: 120px;
                border: 0 !important;
                border-radius: 14px;
                background: linear-gradient(145deg, #91d4f4, #5dade2) !important;
                color: #060606 !important;
                font-weight: 700;
                box-shadow:
                    -7px -7px 14px rgba(255, 255, 255, 0.88),
                    9px 9px 18px rgba(93, 173, 226, 0.34),
                    inset 1px 1px 0 rgba(255, 255, 255, 0.25);
            }

            .btn-login:hover {
                color: #090909 !important;
                transform: translateY(-1px);
            }

            .auth-link {
                color: #0f5f70;
                font-weight: 600;
                text-decoration: none;
            }

            .auth-link:hover {
                text-decoration: underline;
            }

            .btn-dev {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                width: 100%;
                margin-top: 16px;
                padding: 12px 16px;
                border-radius: 14px;
                background: linear-gradient(145deg, #f3fcfd, #d7edf1);
                color: #0f5f70;
                font-weight: 700;
                text-decoration: none;
                border: 1px solid rgba(255, 255, 255, 0.58);
                box-shadow:
                    -7px -7px 14px rgba(255, 255, 255, 0.88),
                    8px 8px 16px rgba(123, 177, 184, 0.26);
            }

            .form-check-input {
                background: linear-gradient(145deg, #fefefe, #d8ecef);
                border: 1px solid rgba(123, 177, 184, 0.28);
                box-shadow:
                    -4px -4px 10px rgba(255, 255, 255, 0.9),
                    4px 4px 10px rgba(123, 177, 184, 0.18);
            }

            .form-check-input:checked {
                background-color: #5dade2;
                border-color: #5dade2;
                box-shadow:
                    inset -2px -2px 4px rgba(255, 255, 255, 0.35),
                    inset 2px 2px 4px rgba(38, 102, 139, 0.22),
                    0 0 0 3px rgba(93, 173, 226, 0.12);
            }

            .page-footer {
                position: fixed;
                left: 0;
                right: 0;
                bottom: 0;
                padding: 10px 16px 12px;
                color: var(--neo-muted);
                text-align: center;
                width: 100%;
                box-sizing: border-box;
                background: transparent;
                pointer-events: none;
            }

            @media (max-width: 576px) {
                .auth-card {
                    padding: 20px;
                }

                .auth-row {
                    flex-direction: column;
                    align-items: stretch;
                }

                .btn-login {
                    width: 100%;
                }

                .auth-logo {
                    width: 80px;
                    height: 80px;
                }

                .auth-title {
                    font-size: 1.45rem;
                }

                .auth-subtitle {
                    margin-bottom: 18px;
                }
            }
        </style>
    </head>
    <body class="login-page">
        <main class="auth-page">
            <section class="auth-card">
                <img src="/assets/img/logo.png" alt="Logo Sistem Informasi Manajemen Air" class="auth-logo" onerror="this.style.display='none';" />
                <h1 class="auth-title">Login</h1>
                <p class="auth-subtitle">Sistem Informasi Manajemen Air</p>

                <?php
                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tombol'])) {
                        $username = $_POST['username'];
                        $password = $_POST['password'];

                        $qc = mysqli_query($koneksi, "SELECT username, password FROM login WHERE username='$username'");
                        $dc = mysqli_fetch_row($qc);

                        if (!empty($dc[0])) $user_cek = $dc[0];

                        if (!empty($user_cek)) {
                            $pass_cek = $dc[1];
                            if (password_verify($password, $pass_cek)) {
                                $_SESSION['user'] = $username;
                                $_SESSION['pass'] = $password;
                                echo "<script>window.location.replace('./login/index.php');</script>";
                            } else {
                                echo "<div class=\"alert alert-danger alert-dismissible fade show\">
                                    <button type='button' class=btn-close data-bs-dismiss=alert></button>
                                    <strong>Login</strong> tidak berhasil, pastikan username dan password benar...
                                </div>";
                            }
                        } else {
                            echo "<div class=\"alert alert-danger alert-dismissible fade show\">
                                <button type='button' class=btn-close data-bs-dismiss=alert></button>
                                <strong>Username</strong> tidak ketemu...
                            </div>";
                        }
                    }
                ?>

                <form method="post" action="" class="needs-validation">
                    <div class="form-floating mb-3">
                        <input class="form-control" id="inputUser" type="text" placeholder="Username" name="username" autocomplete="username" required />
                        <label for="inputUser">Username</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input class="form-control" id="inputPassword" type="password" placeholder="Password" name="password" autocomplete="current-password" required />
                        <label for="inputPassword">Password</label>
                    </div>
                    <div class="auth-row">
                        <div class="form-check">
                            <input class="form-check-input" id="inputRememberPassword" type="checkbox" value="" />
                            <label class="form-check-label" for="inputRememberPassword">Remember Password</label>
                        </div>
                        <a class="auth-link" href="password.html">Forgot Password?</a>
                    </div>
                    <div class="auth-row" style="margin-top: 18px;">
                        <a class="auth-link" href="profile_developer.php">Touch The Developers</a>
                        <button class="btn btn-login" type="submit" name="tombol" value="1">Login</button>
                    </div>
                </form>
            </section>
        </main>

        <div class="page-footer">
            <small>Copyright &copy; Sistem Informasi Manajemen Air <?php echo date('Y'); ?></small>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
    </body>
</html>
