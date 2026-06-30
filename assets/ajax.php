<?php
session_start();
include '../assets/func.php';
$air=new klas_air();
$koneksi=$air->koneksi();

function chart_date_filter($field = 'tgl') {
    $bln = isset($_POST['t']) ? $_POST['t'] : '';
    if (empty($bln) || !preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $bln)) {
        return '';
    }

    $start = substr($bln, 0, 4) . '-01-01';
    $end = date('Y-m-d', strtotime($bln . '-01 +1 month'));
    return " AND $field >= '$start' AND $field < '$end'";
}

function chart_month_limit() {
    $bln = isset($_POST['t']) ? $_POST['t'] : '';
    if (empty($bln) || !preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $bln)) {
        return 0;
    }

    return (int)substr($bln, 5, 2);
}

function month_chart_response($month_values, $air) {
    $response = array();
    $month_limit = chart_month_limit();

    if ($month_limit > 0) {
        for ($month = 1; $month <= $month_limit; $month++) {
            $response[] = $air->bln($month);
            $response[] = isset($month_values[$month]) ? $month_values[$month] : 0;
        }
        return $response;
    }

    ksort($month_values);
    foreach ($month_values as $month => $value) {
        $response[] = $air->bln($month);
        $response[] = $value;
    }
    return $response;
}

if (isset($_POST['p'])) {
    $p= $_POST['p'];
    if($p == "summary"){
        $bln = isset($_POST['t']) ? $_POST['t'] : '';
        $level = isset($_POST['level']) ? $_POST['level'] : '';
        if (!empty($bln) && !preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $bln)) {
            $bln = '';
        }

        if ($level == 'Warga') {
            $username = $_SESSION['user'];
            if (empty($bln)) {
                $q = mysqli_query($koneksi, "SELECT tgl, waktu, pemakaian, tagihan, status FROM pemakaian WHERE username='$username' ORDER BY tgl DESC, no DESC LIMIT 1");
            } else {
                $q = mysqli_query($koneksi, "SELECT tgl, waktu, pemakaian, tagihan, status FROM pemakaian WHERE username='$username' AND tgl LIKE '$bln%' LIMIT 1");
            }

            if ($d = mysqli_fetch_assoc($q)) {
                $bulanIndo = array(
                    '01' => 'Jan', '02' => 'Feb', '03' => 'Mar',
                    '04' => 'Apr', '05' => 'Mei', '06' => 'Jun',
                    '07' => 'Jul', '08' => 'Agt', '09' => 'Sep',
                    '10' => 'Okt', '11' => 'Nov', '12' => 'Des'
                );
                $timestamp = strtotime($d['tgl']);
                $tgl_angka = (int)date('d', $timestamp);
                $bln_angka = date('m', $timestamp);
                $thn_angka = date('Y', $timestamp);
                $tanggal = $tgl_angka . ' ' . $bulanIndo[$bln_angka] . ' ' . $thn_angka;
                $data[] = array(
                    'tgl' => $tanggal,
                    'waktu' => $d['waktu'],
                    'pemakaian' => $d['pemakaian'],
                    'tagihan' => $d['tagihan'],
                    'status' => $d['status']
                );
            } else {
                $data[] = array(
                    'tgl' => '-',
                    'waktu' => '-',
                    'pemakaian' => 0,
                    'tagihan' => 0,
                    'status' => 'Belum Ada Data'
                );
            }
        } else {
            if (empty($bln)) {
                $bln = date('Y-m');
            }

            $ql = mysqli_query($koneksi, "SELECT COUNT(username) as jml_pelanggan FROM login WHERE level='Warga'");
            $dl = mysqli_fetch_assoc($ql);
            $data[] = array('pelanggan' => $dl['jml_pelanggan']);

            if ($level == 'Bendahara') {
                $q2 = mysqli_query($koneksi, "SELECT COALESCE(SUM(tagihan), 0) as jml_pemasukan FROM pemakaian WHERE tgl LIKE '$bln%' AND status='Lunas'");
                $d2 = mysqli_fetch_assoc($q2);
                $data[] = array('pemasukan' => $d2['jml_pemasukan']);

                $q3 = mysqli_query($koneksi, "SELECT COUNT(DISTINCT username) as sdh_lunas FROM pemakaian WHERE tgl LIKE '$bln%' AND status='Lunas'");
                $d3 = mysqli_fetch_assoc($q3);
                $data[] = array('lunas' => $d3['sdh_lunas']);
            } else {
                $q2 = mysqli_query($koneksi, "SELECT COALESCE(SUM(pemakaian), 0) as jml_pemakaian FROM pemakaian WHERE tgl LIKE '$bln%'");
                $d2 = mysqli_fetch_assoc($q2);
                $data[] = array('pemakaian' => $d2['jml_pemakaian']);

                $q3 = mysqli_query($koneksi, "SELECT COUNT(username) as sdh_dicatat FROM pemakaian WHERE tgl LIKE '$bln%'");
                $d3 = mysqli_fetch_assoc($q3);
                $data[] = array('tercatat' => $d3['sdh_dicatat']);
            }
        }
        echo json_encode($data);
    } elseif ($p=="chart_bar"){
        $yuser=$_SESSION['user'];
        $chart_filter = chart_date_filter();
        $response = array();
        $q=mysqli_query($koneksi,"SELECT MONTH(tgl) as bln, SUM(pemakaian) as pemakaian FROM pemakaian WHERE username='$yuser' $chart_filter GROUP BY MONTH(tgl) ORDER BY MONTH(tgl)");
        while($d=mysqli_fetch_assoc($q)){
            $response[]=$air->bln($d['bln']);
            $response[]=$d['pemakaian'];
        }
        echo json_encode($response);
    } elseif ($p=="chart_area"){
        $yuser = $_SESSION['user'];
        $chart_data = array();
        $chart_filter = chart_date_filter();
        $q_chart = mysqli_query($koneksi,"
            SELECT MONTH(tgl) as bln, SUM(tagihan) as total
            FROM pemakaian
            WHERE username='$yuser' $chart_filter
            GROUP BY MONTH(tgl)
            ORDER BY MONTH(tgl)
        ");

        while($d_chart = mysqli_fetch_assoc($q_chart)){
            $chart_data[] = $air->bln($d_chart['bln']);
            $chart_data[] = (float)$d_chart['total'];
        }

        $q_lunas = mysqli_query($koneksi,"
            SELECT SUM(tagihan) as total_lunas
            FROM pemakaian
            WHERE username='$yuser'
            $chart_filter
            AND LOWER(TRIM(status))='lunas'
        ");

        $d_lunas = mysqli_fetch_assoc($q_lunas);
        $lunas = (float)($d_lunas['total_lunas'] ?? 0);

        $q_belum = mysqli_query($koneksi,"
            SELECT SUM(tagihan) as total_belum
            FROM pemakaian
            WHERE username='$yuser'
            $chart_filter
            AND LOWER(TRIM(status))!='lunas'
        ");

        $d_belum = mysqli_fetch_assoc($q_belum);
        $belum = (float)($d_belum['total_belum'] ?? 0);

        echo json_encode(array(
            "chart" => $chart_data,
            "lunas" => $lunas,
            "belum" => $belum
        ));

        exit;
    } elseif ($p=="chart_admin_area"){
        $chart_filter = chart_date_filter();
        $q=mysqli_query($koneksi,"SELECT MONTH(tgl) as bln, SUM(pemakaian) as total_pemakaian FROM pemakaian WHERE 1=1 $chart_filter GROUP BY MONTH(tgl) ORDER BY MONTH(tgl)");
        $response = array();
        while($d=mysqli_fetch_assoc($q)){
            $response[]=$air->bln($d['bln']);
            $response[]=$d['total_pemakaian'];
        }
        echo json_encode($response);
    } elseif ($p=="chart_admin_pie"){
        $q=mysqli_query($koneksi,"SELECT tipe, COUNT(username) as jumlah FROM login WHERE level='Warga' GROUP BY tipe");
        $response = array();
        while($d=mysqli_fetch_assoc($q)){
            $response[]=$d['tipe'];
            $response[]=$d['jumlah'];
        }
        echo json_encode($response);
    } elseif ($p=="chart_admin_tagihan"){
        $chart_filter = chart_date_filter();
        $q=mysqli_query($koneksi,"SELECT MONTH(tgl) as bln, SUM(tagihan) as total_tagihan FROM pemakaian WHERE 1=1 $chart_filter GROUP BY MONTH(tgl) ORDER BY MONTH(tgl)");
        $response = array();
        while($d=mysqli_fetch_assoc($q)){
            $response[]=$air->bln($d['bln']);
            $response[]=$d['total_tagihan'];
        }
        echo json_encode($response);
    } elseif ($p=="chart_admin_pemasukan"){
        $chart_filter = chart_date_filter();
        $q=mysqli_query($koneksi,"SELECT MONTH(tgl) as bln, SUM(CASE WHEN status='Lunas' THEN tagihan ELSE 0 END) as total_pemasukan FROM pemakaian WHERE 1=1 $chart_filter GROUP BY MONTH(tgl) ORDER BY MONTH(tgl)");
        $response = array();
        while($d=mysqli_fetch_assoc($q)){
            $response[]=$air->bln($d['bln']);
            $response[]=$d['total_pemasukan'];
        }
        echo json_encode($response);
    } elseif ($p=="chart_admin_tercatat"){
        $chart_filter = chart_date_filter();
        $q=mysqli_query($koneksi,"SELECT MONTH(tgl) as bln, COUNT(username) as tercatat FROM pemakaian WHERE 1=1 $chart_filter GROUP BY MONTH(tgl) ORDER BY MONTH(tgl)");
        $response = array();
        while($d=mysqli_fetch_assoc($q)){
            $response[]=$air->bln($d['bln']);
            $response[]=$d['tercatat'];
        }
        echo json_encode($response);
    } elseif ($p=="chart_admin_belum_tercatat"){
        $qt = mysqli_query($koneksi, "SELECT COUNT(username) as total FROM login WHERE level='Warga'");
        $dt = mysqli_fetch_assoc($qt);
        $total_warga = $dt['total'];

        $chart_filter = chart_date_filter();
        $q=mysqli_query($koneksi,"SELECT MONTH(tgl) as bln, COUNT(username) as tercatat FROM pemakaian WHERE 1=1 $chart_filter GROUP BY MONTH(tgl) ORDER BY MONTH(tgl)");
        $response = array();
        while($d=mysqli_fetch_assoc($q)){
            $response[]=$air->bln($d['bln']);
            $response[]=$total_warga - $d['tercatat'];
        }
        echo json_encode($response);
    } elseif ($p=="chart_admin_sudah_lunas"){
        $chart_filter = chart_date_filter();
        $q=mysqli_query($koneksi,"SELECT MONTH(tgl) as bln, SUM(CASE WHEN status='Lunas' THEN 1 ELSE 0 END) as sudah_lunas FROM pemakaian WHERE 1=1 $chart_filter GROUP BY MONTH(tgl) ORDER BY MONTH(tgl)");
        $response = array();
        while($d=mysqli_fetch_assoc($q)){
            $response[]=$air->bln($d['bln']);
            $response[]=$d['sudah_lunas'];
        }
        echo json_encode($response);
    } elseif ($p=="chart_admin_belum_lunas"){
        $qt = mysqli_query($koneksi, "SELECT COUNT(username) as total FROM login WHERE level='Warga'");
        $dt = mysqli_fetch_assoc($qt);
        $total_warga = $dt['total'];

        $chart_filter = chart_date_filter();
        $q=mysqli_query($koneksi,"SELECT MONTH(tgl) as bln, SUM(CASE WHEN status='Lunas' THEN 1 ELSE 0 END) as sudah_lunas FROM pemakaian WHERE 1=1 $chart_filter GROUP BY MONTH(tgl) ORDER BY MONTH(tgl)");
        $response = array();
        while($d=mysqli_fetch_assoc($q)){
            $response[]=$air->bln($d['bln']);
            $response[]=$total_warga - $d['sudah_lunas'];
        }
        echo json_encode($response);
    }
}
