<?php
    class klas_air
    {
        function koneksi(){
            $koneksi = mysqli_connect("localhost", "teemyid_kelompok2", "(h5.K68.pT#$!km_", "teemyid_kelompok2");
            return $koneksi;
        }
        function dt_user($sesi_user){
            $q=mysqli_query($this->koneksi(), "SELECT nama,kota,level,tipe FROM login WHERE username='$sesi_user'");
            $d=mysqli_fetch_row($q);
            return $d;
        }
        function user_to_idtarif($username){
                $q=mysqli_query($this->koneksi(), "SELECT tipe FROM login WHERE username='$username' ");
                $d=mysqli_fetch_row($q);
                $tipe = $d[0];
                $kd_tarif = $this->tipe_to_kdtarif($tipe);
                return $kd_tarif;
        }
        function tipe_to_kdtarif($tipe){
                $q=mysqli_query($this->koneksi(), "SELECT kd_tarif FROM tarif WHERE tipe='$tipe' AND status='Aktif' ");
                $d=mysqli_fetch_row($q);
                return $d[0];
        }
        function kdtarif_to_tarif($kd_tarif){
                $q=mysqli_query($this->koneksi(), "SELECT tarif FROM tarif WHERE kd_tarif='$kd_tarif' AND status='Aktif' ");
                $d=mysqli_fetch_row($q);
                return $d[0];
        }       
        function tgl_walik ($tgl){
            $e = explode("-", $tgl);
            $tgl_baru = "$e[2]-$e[1]-$e[0]";
            return $tgl_baru;
        }
        function no_to_username($no){
            $q=mysqli_query($this->koneksi(), "SELECT username FROM pemakaian WHERE no='$no' ");
            $d=mysqli_fetch_row($q);
            return $d[0];
        }
        function bln($no){
            if ($no == 1) $bln = "Januari";
            else if ($no == 2) $bln = "Februari";
            else if ($no == 3) $bln = "Maret";
            else if ($no == 4) $bln = "April";
            else if ($no == 5) $bln = "Mei";
            else if ($no == 6) $bln = "Juni";
            else if ($no == 7) $bln = "Juli";
            else if ($no == 8) $bln = "Agustus";
            else if ($no == 9) $bln = "September";
            else if ($no == 10) $bln = "Oktober";
            else if ($no == 11) $bln = "November";
            else $bln = "Desember";
            return $bln;
        }
    }
?>
