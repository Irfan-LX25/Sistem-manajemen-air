<?php
session_start();
if(empty($_SESSION['user']) && empty($_SESSION['pass'])){
    echo"<script>window.location.replace('../index.php');</script>";
}

include '../assets/func.php';
$air=new klas_air();
$koneksi=$air->koneksi();
$dt_user=$air->dt_user($_SESSION['user']);
$user_level=$dt_user[2];
$pass2 = '';

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['get_last_meter']) && $_POST['get_last_meter'] == '1'){
    if(isset($_POST['username'])){
        $username = $_POST['username'];
        
        // Ambil meter terakhir
        $q = mysqli_query($koneksi, "SELECT meter_akhir FROM pemakaian WHERE username='$username' ORDER BY tgl DESC, no DESC LIMIT 1");
        
        if($q && mysqli_num_rows($q) > 0){
            $d = mysqli_fetch_row($q);
            $meter_terakhir = $d[0];
        } else {
            $meter_terakhir = 0; 
        }
        
        // Cek apakah data bulan ini sudah ada
        $bulan_sekarang = date('m');
        $tahun_sekarang = date('Y');
        $cek_query = mysqli_query($koneksi, "SELECT * FROM pemakaian WHERE username='$username' AND MONTH(tgl)='$bulan_sekarang' AND YEAR(tgl)='$tahun_sekarang'");
        $sudah_ada = (mysqli_num_rows($cek_query) > 0) ? true : false;
        
        header('Content-Type: application/json');
        // Tambahkan status 'sudah_ada' ke dalam response JSON
        echo json_encode(['meter_awal' => $meter_terakhir, 'sudah_ada' => $sudah_ada]);
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['level'])) $level = $_POST['level'];
    if (isset($_POST['tipe'])) $tipe = $_POST['tipe'];
    if (isset($_POST['status'])) $status = $_POST['status'];
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Dashboard - kelompok 2</title>
        <link rel="icon" href="../assets/img/logo.png"> 
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="../css/styles.css?v=20260623-tarif-color" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script>var user_level = "<?php echo $user_level; ?>";</script>
        <script src="../js/air.js?v=20260623-chart-filter"></script>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <a class="navbar-brand ps-3" href="index.php">
                <img src="../assets/img/logo.png" alt="Logo" class="brand-logo-nav" onerror="this.style.display='none';" />
                <span class="brand-title">Sistem Informasi<br><span>Manajemen Air</span></span>
            </a>
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-0 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars fa-fade" style="color:#0d6efd !important;"></i></button>
            <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
                <div class="input-group">
                    <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
                    <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search fa-beat"></i></button>
                </div>
            </form>
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw fa-fade" style="color:#0d6efd !important;"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="#!">Settings</a></li>
                        <li><a class="dropdown-item" href="#!">Activity Log</a></li>
                        <li><hr class="dropdown-divider" /></li>
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Menu</div>
                            <a class="nav-link <?php if((!isset($_GET['page']) || $_GET['page'] == '') && !isset($_GET['p'])) echo 'active'; ?>" href="index.php">
                                <div class="sb-nav-link-icon icon-dashboard"><i class="fas fa-house fa-bounce"></i></div>
                                Dashboard
                            </a>
                            <?php
                            if ($user_level == 'Admin'){
                            ?>
                            <a class="nav-link <?php if(isset($_GET['page']) && $_GET['page'] == 'manajemen-data-user') echo 'active'; ?>" href="index.php?page=manajemen-data-user">
                                <div class="sb-nav-link-icon icon-user"><i class="fas fa-users-gear fa-flip" style="color:#0d6efd !important;"></i></div>
                                    <span style="color:#0d6efd !important;">Manajemen User</span>
                            </a>
                            <a class="nav-link <?php if(isset($_GET['page']) && $_GET['page'] == 'masukkan-data-meter-pemakaian-air') echo 'active'; ?>" href="index.php?page=masukkan-data-meter-pemakaian-air">
                                <div class="sb-nav-link-icon icon-meter"><i class="fas fa-faucet-drip fa-fade" style="color:#fb8c00 !important;"></i></div>
                                    <span style="color:#fb8c00 !important;">Pemakaian Air Warga</span>
                            </a>
                            <a class="nav-link <?php if(isset($_GET['page']) && $_GET['page'] == 'manajemen-data-tarif-air') echo 'active'; ?>" href="index.php?page=manajemen-data-tarif-air">
                                <div class="sb-nav-link-icon icon-tarif"><i class="fas fa-money-bill-wave fa-beat" style="color:#198754 !important;"></i></div>
                                    <span style="color:#198754 !important;">Manajemen Tarif Air</span>
                            </a>
                            <?php
                            } elseif ($user_level == 'Bendahara') {
                            ?>
                            <a class="nav-link <?php if(isset($_GET['page']) && $_GET['page'] == 'masukkan-data-meter-pemakaian-air') echo 'active'; ?>" href="index.php?page=masukkan-data-meter-pemakaian-air">
                                <div class="sb-nav-link-icon icon-payment"><i class="fas fa-receipt fa-fade" style="color:#fb8c00 !important;"></i></div>
                                    <span style="color:#fb8c00 !important;">Lihat Pemakaian Warga</span>
                            </a>
                            <a class="nav-link <?php if(isset($_GET['page']) && $_GET['page'] == 'manajemen-data-tarif-air') echo 'active'; ?>" href="index.php?page=manajemen-data-tarif-air">
                                <div class="sb-nav-link-icon icon-tarif"><i class="fas fa-money-bill-wave fa-beat" style="color:#198754 !important;"></i></div>
                                    <span style="color:#198754 !important;">Manajemen Data Tarif Air</span>
                            </a>
                            <?php
                            } elseif ($user_level == 'Warga') {
                            ?>
                            <a class="nav-link <?php if(isset($_GET['p']) && $_GET['p'] == 'pemakaian_sendiri_list') echo 'active'; ?>" href="index.php?p=pemakaian_sendiri_list">
                                <div class="sb-nav-link-icon icon-payment"><i class="fas fa-receipt fa-fade" style="color:#fb8c00 !important;"></i></div>
                                    <span style="color:#fb8c00 !important;">Lihat Pemakaian</span>
                            </a>
                            <?php
                            }elseif ($user_level == 'Petugas') {
                            ?>
                            <a class="nav-link <?php if(isset($_GET['page']) && $_GET['page'] == 'masukkan-data-meter-pemakaian-air') echo 'active'; ?>" href="index.php?page=masukkan-data-meter-pemakaian-air">
                                <div class="sb-nav-link-icon icon-meter"><i class="fas fa-faucet-drip fa-fade" style="color:#fb8c00 !important;"></i></div>
                                    <span style="color:#fb8c00 !important;">Masukkan Data Meter Pemakaian Air</span>
                            </a>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                    <div class="sb-sidenav-footer" >
                        <div class="small"><i class="fa-solid fa-id-badge fa-fade" style="color:#198754 !important;"></i> Logged in as: <span style="color:#0d6efd !important;"><?php echo $dt_user[2]; ?></span></div>
                        <i class="fa-solid fa-location-dot text-danger fa-fade" style="color:#dc3545 !important;" ></i> <span style="color:#f57c00 !important;"><?php echo $dt_user[0].' ('.$dt_user[1].')'; ?></span>
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <?php
                        $page_key = $_GET['page'] ?? $_GET['p'] ?? '';
                        $is_meter_edit = ($page_key === 'meter_edit');
                        $header_title = 'Dashboard';
                        $header_subtitle = 'Selamat datang di dashboard';
                        $header_icon = 'fa-droplet';
                        $header_accent = 'var(--soft-accent-strong)';
                        $header_bg = 'linear-gradient(145deg, #dff5f8, #c9e9ee)';
                        $header_kicker_bg = 'rgba(255, 255, 255, 0.58)';
                        $header_visual_bg = 'linear-gradient(145deg, #edf9fb, #d8edf1)';
                        $header_icon_bg = 'linear-gradient(145deg, #d0edf4, #9fd5e3)';
                        $header_visual_shadow = '-10px -10px 24px rgba(255, 255, 255, 0.06), 10px 10px 24px rgba(123, 177, 184, 0.34), inset 1px 1px 0 rgba(255, 255, 255, 0.02)';
                        $header_icon_shadow = 'inset -5px -5px 12px rgba(255, 255, 255, 0.02), inset 5px 5px 12px rgba(123, 177, 184, 0.26), 0 10px 20px rgba(123, 177, 184, 0.20)';
                        $header_kicker = 'Overview';

                        if ($page_key === 'manajemen-data-user' || $page_key === 'user_edit') {
                            $header_title = 'Manajemen Data User';
                            $header_subtitle = 'Kelola akun, peran, dan data profil pengguna sistem';
                            $header_icon = 'fa-users-gear';
                            $header_accent = '#0d6efd';
                            $header_bg = 'linear-gradient(145deg, #c4dcff, #8eb8ff)';
                            $header_kicker_bg = 'rgba(13, 110, 253, 0.10)';
                            $header_visual_bg = 'linear-gradient(145deg, #d7e8ff, #a7c8ff)';
                            $header_visual_shadow = '-10px -10px 24px rgba(255, 255, 255, 0.04), 10px 10px 24px rgba(13, 110, 253, 0.22), inset 1px 1px 0 rgba(255, 255, 255, 0.02)';
                            $header_icon_bg = 'linear-gradient(145deg, #f3f8ff, #c9dcff)';
                            $header_icon_shadow = 'inset -4px -4px 10px rgba(255, 255, 255, 0.86), inset 4px 4px 10px rgba(13, 110, 253, 0.18)';
                            $header_kicker = 'Users';
                        } elseif ($page_key === 'manajemen-data-tarif-air' || $page_key === 'tarif_edit') {
                            $header_title = 'Manajemen Data Tarif Air';
                            $header_subtitle = 'Atur tarif air, tipe, dan status';
                            $header_icon = 'fa-money-bill-wave';
                            $header_accent = '#0c5f38';
                            $header_bg = 'linear-gradient(145deg, #d3f5dd, #64c98d)';
                            $header_kicker_bg = 'rgba(12, 95, 56, 0.18)';
                            $header_visual_bg = 'linear-gradient(145deg, #b8eccb, #6ec78f)';
                            $header_visual_shadow = '-10px -10px 24px rgba(255, 255, 255, 0.18), 10px 10px 24px rgba(12, 95, 56, 0.26), inset 1px 1px 0 rgba(255, 255, 255, 0.20)';
                            $header_icon_bg = 'linear-gradient(145deg, #f2fff6, #b9e7ca)';
                            $header_icon_shadow = 'inset -5px -5px 12px rgba(255, 255, 255, 0.94), inset 5px 5px 12px rgba(12, 95, 56, 0.16), 0 10px 20px rgba(12, 95, 56, 0.14)';
                            $header_kicker = 'Tarif';
                        } elseif ($page_key === 'pemakaian_sendiri_list') {
                            $header_title = 'Pemakaian & Tagihan Air';
                            $header_subtitle = 'Ringkasan pemakaian air dan tagihan milik anda';
                            $header_icon = 'fa-receipt';
                            $header_accent = '#fb8c00';
                            $header_bg = 'linear-gradient(145deg, #ffd8a6, #ffae54)';
                            $header_kicker_bg = 'rgba(251, 140, 0, 0.12)';
                            $header_visual_bg = 'linear-gradient(145deg, #ffe0b3, #ffbb68)';
                            $header_visual_shadow = '-10px -10px 24px rgba(255, 255, 255, 0.03), 10px 10px 24px rgba(198, 95, 0, 0.26), inset 1px 1px 0 rgba(255, 255, 255, 0.02)';
                            $header_icon_bg = 'linear-gradient(145deg, #fff0dc, #ffd09d)';
                            $header_icon_shadow = 'inset -4px -4px 10px rgba(255, 255, 255, 0.80), inset 4px 4px 10px rgba(198, 95, 0, 0.16)';
                            $header_kicker = 'Tagihan';
                        } elseif ($page_key === 'masukkan-data-meter-pemakaian-air' || $page_key === 'meter_edit') {
                            $header_title = 'Masukkan Data Meter Pemakaian Air';
                            $header_subtitle = 'Input meter pemakaian dan status pembayaran warga';
                            $header_icon = 'fa-faucet-drip';
                            $header_accent = '#fb8c00';
                            $header_bg = 'linear-gradient(145deg, #ffd8a6, #ffae54)';
                            $header_kicker_bg = 'rgba(251, 140, 0, 0.12)';
                            $header_visual_bg = 'linear-gradient(145deg, #ffe0b3, #ffbb68)';
                            $header_visual_shadow = '-10px -10px 24px rgba(255, 255, 255, 0.03), 10px 10px 24px rgba(198, 95, 0, 0.26), inset 1px 1px 0 rgba(255, 255, 255, 0.02)';
                            $header_icon_bg = 'linear-gradient(145deg, #fff0dc, #ffd09d)';
                            $header_icon_shadow = 'inset -4px -4px 10px rgba(255, 255, 255, 0.80), inset 4px 4px 10px rgba(198, 95, 0, 0.16)';
                            $header_kicker = 'Meter';
                        }
                    ?>
                    <div class="container-fluid px-4 content-wrap" style="--header-accent: <?php echo $header_accent; ?>; --header-bg: <?php echo $header_bg; ?>; --header-kicker-bg: <?php echo $header_kicker_bg; ?>; --header-visual-bg: <?php echo $header_visual_bg; ?>; --header-visual-shadow: <?php echo $header_visual_shadow; ?>; --header-icon-bg: <?php echo $header_icon_bg; ?>; --header-icon-shadow: <?php echo $header_icon_shadow; ?>;">
                        <div class="dashboard-page-header">
                            <div class="dashboard-page-copy">
                                <div class="dashboard-page-kicker"><?php echo $header_kicker; ?></div>
                                <h1><?php echo $header_title; ?></h1>
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item active"><?php echo $header_subtitle; ?></li>
                                </ol>
                            </div>
                            <div class="dashboard-page-visual" aria-hidden="true">
                                <div class="dashboard-page-icon">
                                    <i class="fa-solid <?php echo $header_icon; ?>"></i>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="summary">
                            <div class="row mb-2" id="pilih_waktu">
                                <div class="col-xl-3 col-md-12">
                                    <label for="sel1" class="form-label">Pilih Waktu:</label>
                                    <select class="form-select" id="sel1" name="pilih_waktu">
                                        <option value="">Bulan</option>
                                        <?php
                                        for ($i = 1; $i <= 12; $i++) {
                                            if ($i < 10) $i = "0".$i;
                                            echo "<option value=".date('Y')."-".$i.">".$air->bln($i)." ".date('Y')."</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <?php if ($user_level == 'Warga') { ?>
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card bg-primary text-white h-100 shadow border-0">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="text-xs text-uppercase fw-bold opacity-75 mb-1">Tanggal Catat</div>
                                                <div class="display-6 fw-bold mb-1" id="warga_tgl_val">-</div>
                                                <div class="small fw-bold">Waktu Pencatatan : <span id="warga_waktu_val">-</span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-warning text-white mb-4">
                                    <div class="card-body d-flex justify-content-center">
                                        <h1></h1>
                                        <div class="ms-3">m<sup>3</sup></div>
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-center">
                                        <div class="small text-white">Pemakaian Air</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-success text-white mb-4">
                                    <div class="card-body d-flex justify-content-center">
                                        <h1></h1>
                                        <div class="ms-3">Rp</div>
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-center">
                                        <div class="small text-white">Tagihan</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-danger text-white mb-4">
                                    <div class="card-body d-flex justify-content-center">
                                        <h1 class="text-uppercase" style="font-size: 2.2rem; font-weight: bold;"></h1>
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-center">
                                        <div class="small text-white">Status Tagihan</div>
                                    </div>
                                </div>
                            </div>
                            <?php } else { ?>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-primary text-white mb-4">
                                    <div class="card-body d-flex justify-content-center">
                                        <h1></h1>
                                        <div class="ms-3">orang</div>
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-center">
                                        <div class="small text-white">Pelanggan</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-warning text-white mb-4">
                                    <div class="card-body d-flex justify-content-center">
                                        <h1></h1>
                                        <div class="ms-3"><?php echo ($user_level == 'Bendahara') ? 'Rp' : 'm<sup>3</sup>'; ?></div>
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-center">
                                        <div class="small text-white"><?php echo ($user_level == 'Bendahara') ? 'Pemasukan' : 'Pemakaian Air'; ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-success text-white mb-4">
                                    <div class="card-body d-flex justify-content-center">
                                        <h1></h1>
                                        <div class="ms-3">warga</div>
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-center">
                                        <div class="small text-white"><?php echo ($user_level == 'Bendahara') ? 'Sudah Lunas' : 'Sudah Dicatat'; ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-danger text-white mb-4">
                                    <div class="card-body d-flex justify-content-center">
                                        <h1></h1>
                                        <div class="ms-3">warga</div>
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-center">
                                        <div class="small text-white"><?php echo ($user_level == 'Bendahara') ? 'Belum Bayar' : 'Belum Dicatat'; ?></div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="row" id="chart">
                            <?php if ($user_level == 'Admin' || $user_level == 'Bendahara' || $user_level == 'Petugas') { ?>
                            <div class="col-xl-6">
                                <div class="card mb-4" style="--card-header-bg: linear-gradient(145deg, #bcd8ff, #7fb0ff); --card-header-color: #0a58ca;">
                                    <div class="card-header"><i class="fas fa-chart-line fa-bounce"></i> Total Pemakaian Air <strong id="totalPemakaianAirVal"></strong></div>
                                    <div class="card-body"><canvas id="myAreaChartAdmin" width="100%" height="40"></canvas></div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="card mb-4" style="--card-header-bg: linear-gradient(145deg, #bcd8ff, #7fb0ff); --card-header-color: #0a58ca;">
                                    <div class="card-header"><i class="fas fa-chart-pie fa-bounce"></i> Jumlah Rumah Kos dan Rumah Tinggal</div>
                                    <div class="card-body"><canvas id="myPieChartAdmin" width="100%" height="40"></canvas></div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="card mb-4" style="--card-header-bg: linear-gradient(145deg, #bcd8ff, #7fb0ff); --card-header-color: #0a58ca;">
                                    <div class="card-header"><i class="fas fa-chart-column fa-bounce"></i> Jumlah Warga Tercatat</div>
                                    <div class="card-body"><canvas id="myBarChartAdminTercatat" width="100%" height="40"></canvas></div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="card mb-4 border-danger" style="--card-header-bg: linear-gradient(145deg, #ffb7c1, #ff6f84); --card-header-color: #dc3545;">
                                    <div class="card-header"><i class="fas fa-chart-column fa-bounce"></i> Jumlah Warga Belum Tercatat</div>
                                    <div class="card-body"><canvas id="myBarChartAdminBelum" width="100%" height="40"></canvas></div>
                                </div>
                            </div>

                            <?php if ($user_level == 'Admin' || $user_level == 'Bendahara') { ?>
                            <div class="col-xl-6">
                                <div class="card mb-4" style="--card-header-bg: linear-gradient(145deg, #bcd8ff, #7fb0ff); --card-header-color: #0a58ca;">
                                    <div class="card-header"><i class="fas fa-chart-line fa-bounce"></i> Total Tagihan Air <strong id="totalTagihanAirVal"></strong></div>
                                    <div class="card-body"><canvas id="myAreaChartAdminTagihan" width="100%" height="40"></canvas></div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="card mb-4" style="--card-header-bg: linear-gradient(145deg, #bcd8ff, #7fb0ff); --card-header-color: #0a58ca;">
                                    <div class="card-header"><i class="fas fa-chart-line fa-bounce"></i> Total Pemasukan <strong id="totalPemasukanVal"></strong></div>
                                    <div class="card-body"><canvas id="myAreaChartAdminPemasukan" width="100%" height="40"></canvas></div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="card mb-4" style="--card-header-bg: linear-gradient(145deg, #a9ebc0, #42c97a); --card-header-color: #157347;">
                                    <div class="card-header"><i class="fas fa-chart-column fa-bounce"></i> Jumlah Warga Sudah LUNAS</div>
                                    <div class="card-body"><canvas id="myBarChartAdminSudahLunas" width="100%" height="40"></canvas></div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="card mb-4 border-danger" style="--card-header-bg: linear-gradient(145deg, #ffb7c1, #ff6f84); --card-header-color: #dc3545;">
                                    <div class="card-header"><i class="fas fa-chart-column fa-bounce"></i> Jumlah Warga Belum LUNAS</div>
                                    <div class="card-body"><canvas id="myBarChartAdminBelumLunas" width="100%" height="40"></canvas></div>
                                </div>
                            </div>
                            <?php } ?>

                            <?php } else { ?>
                            <div class="col-xl-6">
                                <div class="card mb-4" style="--card-header-bg: linear-gradient(145deg, #bcd8ff, #7fb0ff); --card-header-color: #0a58ca;">
                                    <div class="card-header">
                                        <i class="fas fa-chart-column fa-bounce"></i>Total Pemakaian Air <strong id="totalPemakaianAirVal"></strong></div>
                                    <div class="card-body"><canvas id="myBarChart" width="100%" height="40"></canvas></div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="card mb-4" style="--card-header-bg: linear-gradient(145deg, #bcd8ff, #7fb0ff); --card-header-color: #0a58ca;">
                                    <div class="card-header">
                                        <i class="fas fa-chart-column fa-bounce"></i>Total Pembayaran Air <strong id="totalPembayaranAirVal"></strong>
                                        <!-- <i class="fas fa-exclamation-circle"></i>Belum Lunas <strong id="totalBelumLunasVal">0</strong> -->
                                        <i class="fas fa-exclamation-circle belum-lunas"></i>Belum Lunas
                                        <strong id="totalBelumLunasVal" class="belum-lunas">0</strong>
                                    </div>
                                    <div class="card-body"><canvas id="myAreaChart" width="100%" height="40"></canvas></div>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                        <?php
                        if(isset($_POST['tombol'])){
                            $t=$_POST['tombol'];
                            if ($t == 'user_add'){
                                $user = $_POST['user'];
                                $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
                                $nama = $_POST['nama'];
                                $alamat = $_POST['alamat'];
                                $kota = $_POST['kota'];
                                $tlp = $_POST['tlp'];
                                $level = $_POST['level'];
                                $tipe = isset($_POST['tipe']) ? $_POST['tipe'] : '';
                                $status = $_POST['status'];
                                $qc = mysqli_query($koneksi, "SELECT username FROM login WHERE username='$user'");
                                $dc = mysqli_fetch_row($qc);

                                if (empty($dc)){
                                    mysqli_query($koneksi, "INSERT INTO login(username,password,nama,alamat,kota,tlp,level,tipe,status) VALUES ('$user','$pass','$nama','$alamat','$kota','$tlp','$level','$tipe','$status')");
                                    if(mysqli_affected_rows($koneksi) > 0){
                                        echo "<div class=\"alert alert-success alert-dismissible fade show\">
                                        <button type='button' class=btn-close data-bs-dismiss=alert></button>
                                        <strong>Data</strong> user berhasil ditambahkan...
                                        </div>";
                                    } else {
                                        echo "<div class=\"alert alert-danger alert-dismissible fade show\">
                                        <button type='button' class=btn-close data-bs-dismiss=alert></button>
                                        <strong>Data</strong> user gagal ditambahkan...
                                        </div>";
                                    }
                                }
                                else {
                                    echo "<div class=\"alert alert-danger alert-dismissible fade show\">
                                    <button type='button' class=btn-close data-bs-dismiss=alert></button>
                                    <strong>Username $user</strong> sudah ada, gunakan username lain...
                                    </div>";
                                }
                            }elseif($t == "user_edit"){
                                $user = $_POST['user'];
                                $pass = trim($_POST['password']);
                                $nama = $_POST['nama'];
                                $alamat = $_POST['alamat'];
                                $kota = $_POST['kota'];
                                $tlp = $_POST['tlp'];
                                $level = $_POST['level'];
                                $tipe = $_POST['tipe'];
                                $status = $_POST['status'];

                                if ($pass === ''){
                                $qu = mysqli_query($koneksi, "UPDATE login SET nama=\"$nama\", alamat='$alamat', kota='$kota', tlp='$tlp', level='$level', tipe='$tipe', status='$status' WHERE username='$user'");
                                } else {
                                    $pass2 = password_hash($pass, PASSWORD_DEFAULT);
                                    $qu = mysqli_query($koneksi, "UPDATE login SET password='$pass2', nama=\"$nama\", alamat='$alamat', kota='$kota', tlp='$tlp', level='$level', tipe='$tipe', status='$status' WHERE username='$user'");
                                }
                                if ($qu === false) {
                                    echo "<div class=\"alert alert-danger alert-dismissible fade show\">
                                    <button type='button' class=btn-close data-bs-dismiss=alert></button>
                                    <strong>Data</strong> user gagal diubah...
                                    </div>";
                                } elseif(mysqli_affected_rows($koneksi) > 0){
                                    echo "<div class=\"alert alert-success alert-dismissible fade show\">
                                    <button type='button' class=btn-close data-bs-dismiss=alert></button>
                                    <strong>Data</strong> user berhasil diubah...
                                    </div>";
                                    echo "<script>setTimeout(function(){ window.location.href = 'index.php?page=manajemen-data-user'; }, 1500);</script>";
                                } else {
                                    echo "<div class=\"alert alert-warning alert-dismissible fade show\">
                                    <button type='button' class=btn-close data-bs-dismiss=alert></button>
                                    <strong>Data</strong> user tidak ada perubahan..
                                    </div>";
                                    echo "<script>setTimeout(function(){ window.location.href = 'index.php?page=manajemen-data-user'; }, 1500);</script>";
                                }
                            } elseif ($t =="user_hapus"){
                                $user = $_POST['user'];
                                mysqli_query($koneksi, "DELETE FROM login WHERE username='$user'");
                                if(mysqli_affected_rows($koneksi) > 0){
                                        echo "<div class=\"alert alert-success alert-dismissible fade show\">
                                        <button type='button' class=btn-close data-bs-dismiss=alert></button>
                                        <strong>Data</strong> user berhasil dihapus...
                                        </div>";
                                    } else {
                                        echo "<div class=\"alert alert-danger alert-dismissible fade show\">
                                        <button type='button' class=btn-close data-bs-dismiss=alert></button>
                                        <strong>Data</strong> user gagal dihapus...
                                        </div>";
                                }
                            } elseif ($t == 'tarif_add'){
                                $kode_tarif = $_POST['kode_tarif'];
                                $tarif = $_POST['tarif'];
                                $tipe = $_POST['tipe'];
                                $status = $_POST['status'];
                                    mysqli_query($koneksi, "INSERT INTO tarif(kd_tarif,tarif,tipe,status) VALUES ('$kode_tarif','$tarif','$tipe','$status')");
                                    if(mysqli_affected_rows($koneksi) > 0){
                                        echo "<div class=\"alert alert-success alert-dismissible fade show\">
                                        <button type='button' class=btn-close data-bs-dismiss=alert></button>
                                        <strong>Data</strong> tarif berhasil ditambahkan...
                                        </div>";
                                    } else {
                                        echo "<div class=\"alert alert-danger alert-dismissible fade show\">
                                        <button type='button' class=btn-close data-bs-dismiss=alert></button>
                                        <strong>Data</strong> tarif gagal ditambahkan...
                                        </div>";
                                    }
                            } elseif($t == "tarif_edit"){
                                $kode_tarif = $_POST['kode_tarif'];
                                $tarif = $_POST['tarif'];
                                $tipe = $_POST['tipe'];
                                $status = $_POST['status'];
                                mysqli_query($koneksi, "UPDATE tarif SET tarif='$tarif', tipe='$tipe', status='$status' WHERE kd_tarif='$kode_tarif'");
                                if(mysqli_affected_rows($koneksi) > 0){
                                    echo "<div class=\"alert alert-success alert-dismissible fade show\">
                                    <button type='button' class=btn-close data-bs-dismiss=alert></button>
                                    <strong>Data</strong> tarif berhasil diubah...
                                    </div>";
                                    echo "<script>setTimeout(function(){ window.location.href = 'index.php?page=manajemen-data-tarif-air'; }, 1500);</script>";
                                } else {
                                    echo "<div class=\"alert alert-warning alert-dismissible fade show\">
                                    <button type='button' class=btn-close data-bs-dismiss=alert></button>
                                    <strong>Data</strong> tarif tidak ada perubahan..
                                    </div>";
                                    echo "<script>setTimeout(function(){ window.location.href = 'index.php?page=manajemen-data-tarif-air'; }, 1500);</script>";
                                }
                            } elseif ($t =="tarif_hapus"){
                                $kode_tarif = $_POST['kode_tarif'] ?? ($_POST['tarif'] ?? '');
                                mysqli_query($koneksi, "DELETE FROM tarif WHERE kd_tarif='$kode_tarif'");
                                if(mysqli_affected_rows($koneksi) > 0){
                                        echo "<div class=\"alert alert-success alert-dismissible fade show\">
                                        <button type='button' class=btn-close data-bs-dismiss=alert></button>
                                        <strong>Data</strong> tarif berhasil dihapus...
                                        </div>";
                                    } else {
                                        echo "<div class=\"alert alert-danger alert-dismissible fade show\">
                                        <button type='button' class=btn-close data-bs-dismiss=alert></button>
                                        <strong>Data</strong> tarif gagal dihapus...
                                        </div>";
                                }
                            } elseif ($t == 'meter_add'){
                                $username = $_POST['username'];
                                $bulan_sekarang = date('m');
                                $tahun_sekarang = date('Y');
                                $cek_query = mysqli_query($koneksi, "SELECT * FROM pemakaian WHERE username='$username' AND MONTH(tgl)='$bulan_sekarang' AND YEAR(tgl)='$tahun_sekarang'");
                                
                                if(mysqli_num_rows($cek_query) > 0){
                                    echo "<div class=\"alert alert-warning alert-dismissible fade show\">
                                    <button type='button' class=btn-close data-bs-dismiss=alert></button>
                                    <strong>Peringatan!</strong> Data meter untuk warga ini sudah diinput pada bulan ini...
                                    </div>";
                                    echo "<script>$(document).ready(function(){ $('#meter_add').show(); $('#meter_list').hide(); });</script>";
                                } else {
                                    $meter_awal = isset($_POST['meter_awal_hidden']) ? $_POST['meter_awal_hidden'] : $_POST['meter_awal'];
                                    $meter_akhir = $_POST['meter_akhir'];
                                    $kd_tarif = $air->user_to_idtarif($username);
                                    $tarif = $air->kdtarif_to_tarif($kd_tarif);
                                    $pemakaian = $meter_akhir - $meter_awal;
                                    $tagihan = $tarif * $pemakaian;
                                    if($pemakaian < 0){
                                        echo "<div class=\"alert alert-danger alert-dismissible fade show\">
                                        <button type='button' class=btn-close data-bs-dismiss=alert></button>
                                        <strong>Data</strong> meter akhir harus lebih besar dari meter awal...
                                        </div>";
                                        echo "<script>$(document).ready(function(){ $('#meter_add').show(); $('#meter_list').hide(); });</script>";
                                    } else {
                                        $status_meter_insert = isset($_POST['status_meter']) ? $_POST['status_meter'] : 'Belum Lunas';
                                        mysqli_query($koneksi, "INSERT INTO pemakaian(username,meter_awal,meter_akhir,pemakaian,tgl,waktu,kd_tarif,tagihan,status) VALUES ('$username','$meter_awal','$meter_akhir','$pemakaian',current_date(),current_time(),'$kd_tarif','$tagihan','$status_meter_insert')");
                                        if(mysqli_affected_rows($koneksi) > 0){
                                            echo "<div class=\"alert alert-success alert-dismissible fade show\">
                                            <button type='button' class=btn-close data-bs-dismiss=alert></button>
                                            <strong>Data</strong> meter berhasil ditambahkan...
                                            </div>";
                                        } else {
                                            echo "<div class=\"alert alert-danger alert-dismissible fade show\">
                                            <button type='button' class=btn-close data-bs-dismiss=alert></button>
                                            <strong>Data</strong> meter gagal ditambahkan...
                                            </div>";
                                        }
                                    }
                                }
                            } elseif($t == "meter_edit"){
                                $no = $_POST['no'];
                                $meter_awal = isset($_POST['meter_awal_hidden']) ? $_POST['meter_awal_hidden'] : $_POST['meter_awal'];
                                $meter_akhir = $_POST['meter_akhir'];
                                $username = $air->no_to_username($no);
                                $kd_tarif = $air->user_to_idtarif($username);
                                $tarif = $air->kdtarif_to_tarif($kd_tarif);
                                $pemakaian = $meter_akhir - $meter_awal;
                                $tagihan = $tarif * $pemakaian;
                                if($pemakaian < 0){
                                    echo "<div class=\"alert alert-danger alert-dismissible fade show\">
                                    <button type='button' class=btn-close data-bs-dismiss=alert></button>
                                    <strong>Data</strong> meter akhir harus lebih besar dari meter awal...
                                    </div>";
                                    echo "<script>$(document).ready(function(){ $('#meter_add').show(); $('#meter_list').hide(); });</script>";
                                } else {
                                    if(isset($_POST['status_meter'])){
                                        $status_meter_update = $_POST['status_meter'];
                                        mysqli_query($koneksi, "UPDATE pemakaian SET meter_awal='$meter_awal', meter_akhir='$meter_akhir', pemakaian='$pemakaian', tagihan='$tagihan', status='$status_meter_update' WHERE no='$no'");
                                    } else {
                                        mysqli_query($koneksi, "UPDATE pemakaian SET meter_awal='$meter_awal', meter_akhir='$meter_akhir', pemakaian='$pemakaian', tagihan='$tagihan' WHERE no='$no'");
                                    }
                                    if(mysqli_affected_rows($koneksi) > 0){
                                        echo "<div class=\"alert alert-success alert-dismissible fade show\">
                                        <button type='button' class=btn-close data-bs-dismiss=alert></button>
                                        <strong>Data</strong> meter berhasil diubah...
                                        </div>";
                                        echo "<script>setTimeout(function(){ window.location.href = 'index.php?page=masukkan-data-meter-pemakaian-air'; }, 1500);</script>";
                                    } else {
                                        echo "<div class=\"alert alert-warning alert-dismissible fade show\">
                                        <button type='button' class=btn-close data-bs-dismiss=alert></button>
                                        <strong>Data</strong> meter tidak ada perubahan..
                                        </div>";
                                        echo "<script>setTimeout(function(){ window.location.href = 'index.php?page=masukkan-data-meter-pemakaian-air'; }, 1500);</script>";
                                    }
                                }
                            } elseif ($t =="meter_hapus"){
                                $no = $_POST['no'];
                                mysqli_query($koneksi, "DELETE FROM pemakaian WHERE no='$no'");
                                if(mysqli_affected_rows($koneksi) > 0){
                                        echo "<div class=\"alert alert-success alert-dismissible fade show\">
                                        <button type='button' class=btn-close data-bs-dismiss=alert></button>
                                        <strong>Data</strong> meter berhasil dihapus...
                                        </div>";
                                    } else {
                                        echo "<div class=\"alert alert-danger alert-dismissible fade show\">
                                        <button type='button' class=btn-close data-bs-dismiss=alert></button>
                                        <strong>Data</strong> meter gagal dihapus...
                                        </div>";
                                }
                            }
                        } elseif (isset($_GET['p'])) {
                            $p = $_GET['p'];
                            if ($p == 'user_edit') {
                                $user = $_GET['user'];
                                $qc = mysqli_query($koneksi, "SELECT password,nama,alamat,kota,tlp,level,tipe,status FROM login WHERE username='$user'");
                                $dc = mysqli_fetch_row($qc);
                                $pass2 = '';
                                $nama = $dc[1];
                                $alamat = $dc[2];
                                $kota = $dc[3];
                                $tlp = $dc[4];
                                $level = $dc[5];
                                $tipe = $dc[6];
                                $status = $dc[7];
                            } elseif ($p == 'tarif_edit') {
                                $kode_tarif = $_GET['kode_tarif'];
                                $qc = mysqli_query($koneksi, "SELECT tarif,tipe,status FROM tarif WHERE kd_tarif='$kode_tarif'");
                                $dc = mysqli_fetch_row($qc);
                                $tarif = $dc[0];
                                $tipe = $dc[1];
                                $status = $dc[2];
                            } elseif ($p == 'meter_edit') {
                                $no = $_GET['no'];
                                $qc = mysqli_query($koneksi, "SELECT username,meter_awal,meter_akhir,pemakaian,status FROM pemakaian WHERE no='$no'");
                                $dc = mysqli_fetch_row($qc);
                                $username = $dc[0];
                                $meter_awal = $dc[1];
                                $meter_akhir = $dc[2];
                                $pemakaian = $dc[3];
                                $status_meter = $dc[4];
                            }
                        }
                        ?>
                        <div class="modal" id="myModal">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Konfirmasi hapus data</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                </div>
                                <div class="modal-footer">
                                    <form method="post">
                                        <button type="submit" name="tombol" value="/" class="btn btn-danger"  data-bs-dismiss="modal">Ya</button>
                                    </form>
                                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">Tidak</button>
                                </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-4" id ="user_add" style="--card-header-bg: linear-gradient(145deg, #bcd8ff, #7fb0ff); --card-header-color: #0a58ca;">
                            <div class="card-header">
                                <i class="fa-solid fa-user-plus fa-beat"></i>
                                User
                            </div>
                            <div class="card-body">
                                <form method="post" class="needs-validation" id="form_user">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="username" class="form-label">Username: <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="username" placeholder="Masukkan username" name="user" value="<?php echo $user ?>" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="password" class="form-label">Password: <?php if (!isset($_GET['p']) || $_GET['p'] != 'user_edit') echo '<span class="text-danger">*</span>'; ?></label>
                                            <input type="password" class="form-control" id="password" placeholder="Masukkan password" name="password" value="<?php echo $pass2 ?>" <?php if (!isset($_GET['p']) || $_GET['p'] != 'user_edit') echo 'required'; ?>>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="nama" class="form-label">Nama: <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="nama" placeholder="Masukkan nama" name="nama" value="<?php echo $nama ?>" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="tlp" class="form-label">No. Telepon:</label>
                                            <input type="text" class="form-control" id="tlp" placeholder="Masukkan telepon" name="tlp" value="<?php echo $tlp ?>">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="alamat" class="form-label">Alamat:</label>
                                        <textarea class="form-control" rows="3" id="alamat" name="alamat"><?php echo $alamat ?></textarea>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="kota" class="form-label">Kota:</label>
                                            <input type="text" class="form-control" id="kota" placeholder="Masukkan kota" name="kota" value="<?php echo $kota ?>">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="status" class="form-label">Status: <span class="text-danger">*</span></label>
                                            <div class="d-flex align-items-center h-100 pb-4">
                                                <div class="d-flex gap-3 flex-wrap">
                                                    <?php
                                                    $st = array('Aktif', 'Non-Aktif');
                                                    foreach ($st as $st2) {
                                                        $checked = ($status == $st2 || (empty($status) && $st2 == 'Aktif')) ? 'checked' : '';
                                                        echo "<div class='form-check form-check-inline mb-0'>";
                                                        echo "<input class='form-check-input' type='radio' name='status' id='status_$st2' value='$st2' $checked required>";
                                                        echo "<label class='form-check-label' for='status_$st2'>" . ucwords($st2) . "</label>";
                                                        echo "</div>";
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="level" class="form-label">Level: <span class="text-danger">*</span></label>
                                            <div class="d-flex align-items-center h-100 pb-4">
                                                <div class="d-flex gap-3 flex-wrap">
                                                    <?php
                                                    $lv = array(
                                                        'Admin' => 'fa-user-shield text-danger fa-shake',
                                                        'Bendahara' => 'fa-wallet text-warning fa-beat',
                                                        'Warga' => 'fa-house-user text-success fa-fade',
                                                        'Petugas' => 'fa-user-tie text-primary fa-bounce'
                                                    );
                                                    foreach ($lv as $lv2 => $icon) {
                                                        $checked = ($level == $lv2 || (empty($level) && $lv2 == 'Warga')) ? 'checked' : '';
                                                        echo "<div class='form-check form-check-inline mb-0'>";
                                                        echo "<input class='form-check-input' type='radio' name='level' id='level_$lv2' value='$lv2' $checked required>";
                                                        echo "<label class='form-check-label' for='level_$lv2'><i class='fas $icon me-1'></i>" . ucwords($lv2) . "</label>";
                                                        echo "</div>";
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="tipe" class="form-label">Tipe:</label>
                                            <div class="d-flex align-items-center h-100 pb-4">
                                                <div class="d-flex gap-3 flex-wrap">
                                                    <?php
                                                    $t = array(
                                                        'RT' => 'fa-house text-info',
                                                        'Kos' => 'fa-bed text-secondary'
                                                    );
                                                    foreach ($t as $t2 => $icon) {
                                                        $checked = ($tipe == $t2) ? 'checked' : '';
                                                        echo "<div class='form-check form-check-inline mb-0'>";
                                                        echo "<input class='form-check-input' type='radio' name='tipe' id='tipe_$t2' value='$t2' $checked>";
                                                        echo "<label class='form-check-label' for='tipe_$t2'><i class='fas $icon me-1'></i>" . ucwords($t2) . "</label>";
                                                        echo "</div>";
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary" name="tombol" value="user_add">Simpan</button>
                                </form> 
                            </div>
                        </div>
                        <div class="card mb-4" id ="tarif_add" style="--card-header-bg: linear-gradient(145deg, #c8efd4, #35b86e); --card-header-color: #0b4f2d; --card-header-icon-bg: linear-gradient(145deg, #f8fff9, #d8f0df); --card-header-icon-shadow: inset -5px -5px 12px rgba(255, 255, 255, 0.96), inset 5px 5px 12px rgba(11, 79, 45, 0.16), 0 8px 18px rgba(11, 79, 45, 0.13);">
                            <div class="card-header">
                                <i class="fa-solid fa-money-bill-wave fa-beat-fade"></i>
                                Data Tarif
                            </div>
                            <div class="card-body">
                                <form method="post" class="needs-validation" id="form_tarif">
                                    <div class="mb-3">
                                        <label for="kode_tarif" class="form-label">Kode Tarif:</label>
                                        <input type="text" class="form-control" id="kode_tarif" placeholder="Masukkan kode tarif" name="kode_tarif" value="<?php echo $kode_tarif ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="tarif" class="form-label">Tarif:</label>
                                        <input type="number" class="form-control" id="tarif" placeholder="Masukkan tarif" name="tarif" value="<?php echo $tarif ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="tipe" class="form-label">Tipe:</label>
                                        <div class="d-flex gap-3">
                                            <?php
                                            $t = array(
                                                'RT' => 'fa-house text-info',
                                                'Kos' => 'fa-bed text-secondary'
                                            );
                                            foreach ($t as $t2 => $icon) {
                                                $checked = ($tipe == $t2) ? 'checked' : '';
                                                echo "<div class='form-check form-check-inline'>";
                                                echo "<input class='form-check-input' type='radio' name='tipe' id='tipe_$t2' value='$t2' $checked>";
                                                echo "<label class='form-check-label' for='tipe_$t2'><i class='fas $icon me-1'></i>" . ucwords($t2) . "</label>";
                                                echo "</div>";
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Status:</label>
                                        <div class="d-flex gap-3">
                                            <?php
                                            $st = array('Aktif', 'Non-Aktif');
                                            foreach ($st as $st2) {
                                                $checked = ($status == $st2) ? 'checked' : '';
                                                echo "<div class='form-check form-check-inline'>";
                                                echo "<input class='form-check-input' type='radio' name='status' id='status_$st2' value='$st2' $checked>";
                                                echo "<label class='form-check-label' for='status_$st2'>" . ucwords($st2) . "</label>";
                                                echo "</div>";
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary" name="tombol" value="tarif_add">Simpan</button>
                                </form> 
                            </div>
                        </div>
                        <?php if ($user_level != 'Bendahara' || ($user_level == 'Bendahara' && $is_meter_edit)) { ?>
                        <div class="card mb-4" id ="meter_add" style="--card-header-bg: linear-gradient(145deg, #ffd7a8, #ff9f3f); --card-header-color: #c65f00;">
                            <div class="card-header">
                                <i class="fa-solid fa-faucet-drip fa-fade"></i>
                                Data Meter
                            </div>
                            <div class="card-body">
                                <?php
                                if ($is_meter_edit) {
                                    $disable = 'disabled';
                                    $readonly_bendahara = ($user_level == 'Bendahara') ? 'readonly' : '';
                                } else {
                                    $disable = '';
                                    $readonly_bendahara = '';
                                    $status_meter = '';
                                }
                                ?>
                                <form method="post" class="needs-validation" id="form_meter">
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Nama Warga:</label>
                                        <select class="form-select" name="username" required <?php echo $disable ?>>
                                            <option value="">Nama Warga </option>
                                            <?php
                                            $q = mysqli_query($koneksi, "SELECT username, nama FROM login WHERE level='Warga' ORDER BY nama ASC");
                                            while ($d = mysqli_fetch_row($q)) {
                                                if ($username == $d[0]) $selected = 'selected';
                                                else $selected = '';
                                                echo "<option value='$d[0]' $selected>$d[1]</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="meter_awal" class="form-label">Meter Awal (m<sup>3</sup>):</label>
                                        <input type="text" class="form-control" id="meter_awal" placeholder="Masukkan meter awal " name="meter_awal" value="<?php echo $meter_awal ?>" required disabled>
                                    </div>
                                    <div class="mb-3">
                                        <label for="meter_akhir" class="form-label">Meter Akhir (m<sup>3</sup>):</label>
                                        <input type="number" class="form-control" id="meter_akhir" placeholder="Masukkan meter akhir " name="meter_akhir" value="<?php echo $meter_akhir ?>" required <?php echo $readonly_bendahara; ?>>
                                    </div>
                                    <?php if ($user_level == 'Admin' || ($user_level == 'Bendahara' && $is_meter_edit)) { ?>
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Status:</label>
                                        <div class="d-flex gap-3 mt-1">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="status_meter" id="status_lunas" value="Lunas" <?php if($status_meter=='Lunas') echo 'checked'; ?> required>
                                                <label class="form-check-label" for="status_lunas">LUNAS</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="status_meter" id="status_blm_lunas" value="Belum Lunas" <?php if($status_meter=='Belum Lunas' || $status_meter=='') echo 'checked'; ?> required>
                                                <label class="form-check-label" for="status_blm_lunas">BELUM LUNAS</label>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <input type="hidden" name="meter_awal_hidden" value="<?php echo $meter_awal ?>">
                                    <?php if($is_meter_edit) { 
                                        echo "<input type='hidden' name='no' value='$no'>";
                                    } ?>
                                    <button type="submit" class="btn btn-primary" name="tombol" value="<?php echo $is_meter_edit ? 'meter_edit' : 'meter_add'; ?>">Simpan</button>
                                </form> 
                            </div>
                        </div>
                        <?php } ?>
                        <div class="card mb-4" id ="user_list" style="--card-header-bg: linear-gradient(145deg, #bcd8ff, #7fb0ff); --card-header-color: #0a58ca;">
                            <div class="card-header">
                                <i class="fa-solid fa-users-gear fa-flip"></i>
                                Data User
                            </div>
                            <div class="card-body table-responsive">
                                <table id="datatablesSimple" class="table table-sm table-striped table-hover align-middle text-center">
                                    <thead>
                                        <tr>
                                            <th>Username</th>
                                            <th>Nama</th>
                                            <th>Alamat</th>
                                            <th>Kota</th>
                                            <th>Telephone</th>
                                            <th>Level</th>
                                            <th>Tipe</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $q = mysqli_query($koneksi,"SELECT username,nama,alamat,kota,tlp,level,tipe,status FROM login ORDER BY level ASC");
                                        while($d=mysqli_fetch_row($q)){
                                            $user = $d[0];
                                            $nama = $d[1];
                                            $alamat = $d[2];
                                            $kota = $d[3];
                                            $tlp = $d[4];
                                            $level = $d[5];
                                            $tipe = $d[6];
                                            $status = $d[7];
                                            echo "<tr>
                                                    <td>$user</td>
                                                    <td>$nama</td>
                                                    <td>$alamat</td>
                                                    <td>$kota</td>
                                                    <td>$tlp</td>
                                                    <td>$level</td>
                                                    <td>$tipe</td>
                                                    <td>$status</td>
                                                    <td>
                                                        <div class='d-flex flex-nowrap gap-1 justify-content-center'>
                                                            <a href=index.php?p=user_edit&user=$user><button type=button class='btn btn-outline-primary'><i class='fas fa-pen-to-square me-1'></i> Ubah</button></a>
                                                            <button type=button class='btn btn-outline-danger' data-bs-toggle=modal data-bs-target=#myModal data-user=$user><i class='fas fa-trash me-1'></i> Hapus</button>
                                                        </div>
                                                    </td>
                                                </tr>";
                                        }
                                        ?> 
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card mb-4" id ="tarif_list" style="--card-header-bg: linear-gradient(145deg, #c8efd4, #35b86e); --card-header-color: #0b4f2d; --card-header-icon-bg: linear-gradient(145deg, #f8fff9, #d8f0df); --card-header-icon-shadow: inset -5px -5px 12px rgba(255, 255, 255, 0.96), inset 5px 5px 12px rgba(11, 79, 45, 0.16), 0 8px 18px rgba(11, 79, 45, 0.13);">
                            <div class="card-header">
                                <i class="fa-solid fa-money-bill-wave fa-beat-fade"></i>
                                Data Tarif
                            </div>
                            <div class="card-body table-responsive">
                                <table id="tarif_table" class="table table-sm table-striped table-hover align-middle text-center">
                                    <thead>
                                        <tr>
                                            <th>Kode Tarif</th>
                                            <th>Tarif</th>
                                            <th>Tipe</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $q = mysqli_query($koneksi,"SELECT kd_tarif,tarif,tipe,status FROM tarif ORDER BY kd_tarif ASC");
                                        while($d=mysqli_fetch_row($q)){
                                            $kode_tarif = $d[0];
                                            $tarif = $d[1];
                                            $tarif_rp = "Rp " . number_format($tarif, 0, ',', '.');
                                            $tipe = $d[2];
                                            $status = $d[3];
                                            echo "<tr>
                                                    <td>$kode_tarif</td>
                                                    <td>$tarif_rp</td>
                                                    <td>$tipe</td>
                                                    <td>$status</td>
                                                    <td class='text-center align-middle action-cell'>
                                                        <div class='action-wrap'>
                                                            <a href=index.php?p=tarif_edit&kode_tarif=$kode_tarif><button type=button class='btn btn-outline-primary'><i class='fas fa-pen-to-square me-1'></i>Ubah</button></a>
                                                            <button type=button class='btn btn-outline-danger' data-bs-toggle=modal data-bs-target=#myModal data-tarif=$kode_tarif><i class='fas fa-trash me-1'></i>Hapus</button>
                                                        </div>
                                                    </td>
                                                </tr>";
                                        }
                                        ?> 
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card mb-4" id ="meter_list" style="--card-header-bg: linear-gradient(145deg, #ffd7a8, #ff9f3f); --card-header-color: #c65f00;">
                            <div class="card-header">
                                <i class="fa-solid fa-faucet-drip fa-fade"></i>
                                Data Meter Warga
                            </div>
                            <div class="card-body table-responsive">
                                <table id="meter_table" class="table table-sm table-striped table-hover align-middle text-center">
                                    <thead>
                                        <tr>
                                            <th class="text-center align-middle">Nama Warga</th>
                                            <th class="text-center align-middle">Tipe</th>
                                            <th class="text-center align-middle">Tanggal & Waktu</th>
                                            <th class="text-center align-middle">Meter Awal (m<sup>3</sup>)</th>
                                            <th class="text-center align-middle">Meter Akhir (m<sup>3</sup>)</th>
                                            <th class="text-center align-middle">Pemakaian (m<sup>3</sup>)</th>
                                            <?php if ($user_level == 'Bendahara' || $user_level == 'Admin') { ?>
                                            <th class="text-center align-middle">Tagihan (Rp)</th>
                                            <th class="text-center align-middle">Status</th>
                                            <?php } ?>
                                            <th class="text-center align-middle">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $q = mysqli_query($koneksi,"SELECT no,username,meter_awal,meter_akhir,pemakaian,tgl,waktu,tagihan,status FROM pemakaian ORDER BY tgl DESC, username ASC");
                                        while($d=mysqli_fetch_row($q)){
                                            $no = $d[0];
                                            $dt_user2 = $air->dt_user($d[1]);
                                            $nama = $dt_user2[0];
                                            $tipe_warga = $dt_user2[3];
                                            $meter_awal = $d[2];
                                            $meter_akhir = $d[3];
                                            $pemakaian = $d[4];
                                            $tgl = $air->tgl_walik($d[5]);
                                            $waktu = $d[6];
                                            $tagihan = $d[7];
                                            $status_tagihan = $d[8];
                                            $level_login = $dt_user[2];
                                            $tgl_meter = date_create($d[5]);
                                            $tgl_sekarang = date_create();
                                            $diff = date_diff($tgl_meter, $tgl_sekarang);
                                            $selisih =$diff->days;
                                            $indo = [
                                                'Monday'=>'Senin', 'Tuesday'=>'Selasa', 'Wednesday'=>'Rabu', 'Thursday'=>'Kamis', 
                                                'Friday'=>'Jumat', 'Saturday'=>'Sabtu', 'Sunday'=>'Minggu',
                                                'January'=>'Januari', 'February'=>'Februari', 'March'=>'Maret', 'April'=>'April', 
                                                'May'=>'Mei', 'June'=>'Juni', 'July'=>'Juli', 'August'=>'Agustus', 
                                                'September'=>'September', 'October'=>'Oktober', 'November'=>'November', 'December'=>'Desember'
                                            ];
                                            $tgl_tampil = strtr(date("d F Y", strtotime($tgl)), $indo);
                                            $tagihan_rp = "Rp " . number_format($tagihan, 0, ',', '.');

                                            echo "<tr>
                                                    <td class='text-center align-middle'>$nama</td>
                                                    <td class='text-center align-middle'>$tipe_warga</td>
                                                    <td class='text-center align-middle'>
                                                        <div class='date-stack'>
                                                            <div class='date-stack__day'>$tgl_tampil</div>
                                                            <div class='date-stack__time'>$waktu</div>
                                                            <div class='date-stack__ago'>$selisih hari lalu</div>
                                                        </div>
                                                    </td> 
                                                    <td class='text-center align-middle'>$meter_awal</td>
                                                    <td class='text-center align-middle'>$meter_akhir</td>
                                                    <td class='text-center align-middle'>$pemakaian</td>";
                                                    
                                            if ($user_level == 'Bendahara' || $user_level == 'Admin') {
                                                        $badge_class = (strtolower($status_tagihan) == 'lunas') ? 'status-pill status-pill--success' : 'status-pill status-pill--danger';
                                                        $badge_icon = (strtolower($status_tagihan) == 'lunas') ? 'fa-circle-check' : 'fa-triangle-exclamation';
                                                $status_display = strtoupper($status_tagihan);
                                                echo "<td class='text-center align-middle fw-bold'>$tagihan_rp</td>
                                                              <td class='text-center align-middle'><span class='$badge_class'><i class='fas $badge_icon'></i><span>$status_display</span></span></td>";
                                            }
                                            if ($level_login == 'Admin' || $level_login == 'Bendahara'){
                                                echo "<td class='text-center align-middle'>
                                                        <div class='d-flex flex-nowrap gap-1 justify-content-center'>
                                                            <a href='index.php?p=meter_edit&no=$no' class='btn btn-outline-primary'><i class='fas fa-pen-to-square'></i></a>
                                                            <button type='button' class='btn btn-outline-danger' data-bs-toggle='modal' data-bs-target='#myModal' data-no='$no'><i class='fas fa-trash'></i></button>
                                                        </div>
                                                    </td>";

                                            } else{
                                                if($selisih <=30){
                                                    echo "<td class='text-center align-middle'>
                                                            <div class='d-flex flex-nowrap gap-1 justify-content-center'>
                                                                <a href='index.php?p=meter_edit&no=$no' class='btn btn-outline-primary'><i class='fas fa-pen-to-square'></i></a>
                                                                <button type='button' class='btn btn-outline-danger' data-bs-toggle='modal' data-bs-target='#myModal' data-no='$no'><i class='fas fa-trash'></i></button>
                                                            </div>
                                                        </td>";
                                                } else {
                                                    echo "<td></td>";
                                                }
                                            }
                                             echo "</tr>";
                                        }
                                        ?> 
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card mb-4" id="pemakaian_sendiri_list" style="display: none; --card-header-bg: linear-gradient(145deg, #ffd7a8, #ff9f3f); --card-header-color: #c65f00;">
                            <div class="card-header">
                                <i class="fas fa-receipt fa-fade"></i>
                                Data Pemakaian dan Pembayaran
                            </div>
                            <div class="card-body table-responsive">
                                <table id="pemakaian_sendiri_table" class="table table-sm table-striped table-hover align-middle text-center">
                                    <thead>
                                        <tr>
                                            <th class="text-center align-middle">Waktu Pencatatan Meter</th>
                                            <th class="text-center align-middle">Kode Tarif</th>
                                            <th class="text-center align-middle">Meter Awal (m<sup>3</sup>)</th>
                                            <th class="text-center align-middle">Meter Akhir (m<sup>3</sup>)</th>
                                            <th class="text-center align-middle">Pemakaian (m<sup>3</sup>)</th>
                                            <th class="text-center align-middle">Tagihan (Rp)</th>
                                            <th class="text-center align-middle">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (isset($_SESSION['user'])) {
                                            $user_login = $_SESSION['user'];
                                            $q_pemakaian = mysqli_query($koneksi,"SELECT tgl, waktu, kd_tarif, meter_awal, meter_akhir, pemakaian, tagihan, status FROM pemakaian WHERE username='$user_login' ORDER BY tgl DESC");
                                            while($dp = mysqli_fetch_row($q_pemakaian)){
                                                $tgl = $air->tgl_walik($dp[0]);
                                                $waktu = $dp[1];
                                                $kd_tarif = $dp[2];
                                                $meter_awal = $dp[3];
                                                $meter_akhir = $dp[4];
                                                $pemakaian = $dp[5];
                                                $tagihan = $dp[6];
                                                $status_tagihan = $dp[7];
                                                $badge_color = (strtolower($status_tagihan) == 'lunas') ? 'bg-success' : 'bg-danger';
                                                $status_display = strtoupper($status_tagihan);
                                                $indo = [
                                                'Monday'=>'Senin', 'Tuesday'=>'Selasa', 'Wednesday'=>'Rabu', 'Thursday'=>'Kamis', 
                                                'Friday'=>'Jumat', 'Saturday'=>'Sabtu', 'Sunday'=>'Minggu',
                                                'January'=>'Januari', 'February'=>'Februari', 'March'=>'Maret', 'April'=>'April', 
                                                'May'=>'Mei', 'June'=>'Juni', 'July'=>'Juli', 'August'=>'Agustus', 
                                                'September'=>'September', 'October'=>'Oktober', 'November'=>'November', 'December'=>'Desember'
                                                ];
                                                $tgl_tampil = strtr(date("d F Y", strtotime($tgl)), $indo);
                                                $tgl_meter = date_create($dp[0]);
                                                $tgl_sekarang = date_create();
                                                $diff = date_diff($tgl_meter, $tgl_sekarang);
                                                $selisih =$diff->days;
                                                $hari_ini = strtr(date("d F Y", strtotime(date("Y-m-d"))), $indo);
                                                $tagihan_rp = "Rp " . number_format($tagihan, 0, ',', '.');
                                                $badge_class = (strtolower($status_tagihan) == 'lunas') ? 'status-pill status-pill--success' : 'status-pill status-pill--danger';
                                                $badge_icon = (strtolower($status_tagihan) == 'lunas') ? 'fa-circle-check' : 'fa-triangle-exclamation';
                                                $status_display = strtoupper($status_tagihan);
                                                
                                                $q_tarif = mysqli_query($koneksi, "SELECT tarif FROM tarif WHERE kd_tarif='$kd_tarif'");
                                                $d_tarif = mysqli_fetch_row($q_tarif);
                                                $tarif_rp = $d_tarif ? "Rp " . number_format($d_tarif[0], 0, ',', '.') : "-";
                                                
                                                echo "<tr>
                                                        <td class='text-center align-middle'>
                                                            <div class='date-stack'>
                                                                <div class='date-stack__day'>$tgl_tampil</div>
                                                                <div class='date-stack__time'>$waktu</div>
                                                                <div class='date-stack__ago'>$selisih hari lalu</div>
                                                            </div>
                                                        </td>
                                                        <td class='text-center align-middle'>
                                                            <div class='date-stack'>
                                                                <div class='date-stack__day'>$kd_tarif</div>
                                                                <div class='date-stack__time'>$tarif_rp</div>
                                                            </div>
                                                        </td>
                                                        <td class='text-center align-middle'>$meter_awal</td>
                                                        <td class='text-center align-middle'>$meter_akhir</td>
                                                        <td class='text-center align-middle'>$pemakaian</td>
                                                        <td class='text-center align-middle fw-bold'>$tagihan_rp</td>
                                                        <td class='text-center align-middle'><span class='$badge_class'><i class='fas $badge_icon'></i><span>$status_display</span></span></td>
                                                    </tr>";
                                            }
                                        }
                                        ?> 
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </main>
                <footer class="py-4 mt-auto" style="background: var(--soft-bg); box-shadow: inset 0 1px 0 rgba(255,255,255,0.58);">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Sistem Informasi Manajemen Air <?php echo date('Y'); ?></div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="../js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="../js/datatables-simple-demo.js"></script>
    </body>
</html>
