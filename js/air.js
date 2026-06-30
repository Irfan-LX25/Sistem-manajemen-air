$(document).ready(function() {
    uri = window.location.href;
    e = uri.split("=");
    console.log("URI: " + uri + " e[1]: " + e[1]);

    if (e[1] === "manajemen-data-user" || e[1] === "user_edit&user") {
        if(e[1] === "manajemen-data-user"){
            $("#summary,#chart,#user_add,#tarif_add,#tarif_list,#meter_add,#meter_list").hide();
        }else {
            $("#summary,#chart,#user_list,#tarif_add,#tarif_list,#meter_add,#meter_list").hide();
            $("#user_add").show();
            $("#form_user button").val("user_edit");
            $("#form_user input[name='user']").attr("disabled", true);
            $("#form_user").append("<input type='hidden' name='user' value='" + e[2] + "'>");
        }
        $(".datatable-dropdown").prepend("<button type='button' style='color:#0d6efd !important;' class='btn btn-primary float-start me-2'><i class='fas fa-user-plus'></i> User</button>");
        $(".datatable-dropdown button").click(function() {
            $("#user_add").show();
            $("#user_list").hide();
            $("#form_user input[type='text'], #form_user input[type='password'], #form_user input[type='number'], #form_user textarea").val("");
            $("#form_user input[type='radio']").prop("checked", false);
        });
        $("button[data-bs-toggle='modal']").click(function() {
            user=$(this).attr('data-user');
            $("#myModal .modal-body").text("Apakah Anda yakin ingin menghapus data user " + user + "?");  
            $(".modal-footer form input[name='user'], .modal-footer form input[name='tarif'], .modal-footer form input[name='kode_tarif']").remove();
            $(".modal-footer form").append("<input type='hidden' name='user' value='" + user + "'>");
            $(".modal-footer form button[name='tombol']").val("user_hapus");
        });
    } else if (e[1] === "manajemen-data-tarif-air" || e[1] === "tarif_edit&kode_tarif") {
        if(e[1] === "manajemen-data-tarif-air"){
            $("#summary,#chart,#tarif_add,#user_add,#user_list,#meter_add,#meter_list").hide();
        }else {
            $("#summary,#chart,#tarif_list,#user_add,#user_list,#meter_add,#meter_list").hide();
            $("#tarif_add").show();
            $("#form_tarif button").val("tarif_edit");
            $("#form_tarif input[name='kode_tarif']").attr("disabled", true);
            $("#form_tarif").append("<input type='hidden' name='kode_tarif' value='" + e[2] + "'>");
        }
        const datatablesSimple = document.getElementById('tarif_table');
            if (datatablesSimple) {
                new simpleDatatables.DataTable(datatablesSimple);
            }       
        $(".datatable-dropdown").prepend("<button type=button style='color:#198754 !important;' class='btn btn-primary float-start me-2'><i class='fas fa-money-bill'></i> Tarif</button>");
        $(".datatable-dropdown button").click(function() {
            $("#tarif_add").show();
            $("#tarif_list").hide();
            $("#form_tarif input[type='text'], #form_tarif input[type='number'], #form_tarif textarea").val("");
            $("#form_tarif input[type='radio']").prop("checked", false);
        });
        $("button[data-bs-toggle='modal']").click(function() {
            kode_tarif=$(this).attr('data-tarif');
            $("#myModal .modal-body").text("Apakah Anda yakin ingin menghapus data tarif " + kode_tarif + "?");  
            $(".modal-footer form input[name='user'], .modal-footer form input[name='tarif'], .modal-footer form input[name='kode_tarif']").remove();
            $(".modal-footer form").append("<input type='hidden' name='kode_tarif' value='" + kode_tarif + "'>");
            $(".modal-footer form button[name='tombol']").val("tarif_hapus");
        });
    } else if (e[1] === "masukkan-data-meter-pemakaian-air" || e[1] == 'meter_edit&no') {
        if(e[1] === "masukkan-data-meter-pemakaian-air"){
            $("#summary,#chart,#tarif_add,#user_add,#user_list,#tarif_list,#meter_add").hide();
        }else {
            $("#summary,#chart,#tarif_list,#user_add,#user_list,#meter_list,#tarif_add").hide();
            $("#meter_add").show();
            $("#form_meter button").val("meter_edit");
            $("#form_meter input[name='no']").attr("disabled", true);
            $("#form_meter").append("<input type='hidden' name='no' value='" + e[2] + "'>");
        }
        const datatablesSimple = document.getElementById('meter_table');
            if (datatablesSimple) {
                new simpleDatatables.DataTable(datatablesSimple);
            }       
        if (typeof user_level !== 'undefined' && user_level !== 'Bendahara') {
            $(".datatable-dropdown").prepend("<button type=button style='color:#fb8c00 !important;' class='btn btn-primary float-start me-2'><i class='fas fa-faucet-drip'></i> Meter</button>");
            $(".datatable-dropdown button").click(function() {
                $("#meter_add").show();
                $("#meter_list").hide();
                $("#form_meter input:not([type=radio])").val("");
                $("#form_meter select").val("");
                $("input[name='meter_awal_hidden']").val("");
            });
        }
        $("#form_meter select[name='username']").change(function() {
            var username = $(this).val();
            
            $("#peringatan-meter-bulan-ini").remove();
            
            if(username !== '') {
                $.ajax({
                    // PASTIKAN NAMA FILE SESUAI. Jika file Anda bernama index (2).php, sesuaikan di sini.
                    url: 'index.php', 
                    method: 'POST',
                    data: { username: username, get_last_meter: 1 },
                    dataType: 'json',
                    success: function(response) {
                        // KODE DEBUGGING - Lihat hasilnya di Inspect Element -> tab Console
                        console.log("Response dari server:", response); 
                        
                        $("#meter_awal").val(response.meter_awal);
                        $("input[name='meter_awal_hidden']").val(response.meter_awal);
                        
                        // Menampilkan peringatan jika respons sudah_ada bernilai true
                        if(response.sudah_ada === true) {
                            $("#form_meter").before('<div id="peringatan-meter-bulan-ini" class="alert alert-warning alert-dismissible fade show"><button type="button" class="btn-close" data-bs-dismiss="alert"></button><strong>Peringatan!</strong> Data meter untuk warga ini sudah diinput pada bulan ini.</div>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('Error fetching meter data:', error);
                        $("#meter_awal").val('0');
                        $("input[name='meter_awal_hidden']").val('0');
                    }
                });
            }
        });
        // ========================================================
        // TAMBAHKAN KODE CEGAT SIMPAN DI SINI
        // ========================================================
        $("#form_meter").submit(function(e) {
            // Mengecek apakah elemen peringatan sedang tampil
            if ($("#peringatan-meter-bulan-ini").length > 0) {
                
                e.preventDefault(); // Mencegah form dikirim/disimpan ke database
                
                
                // Sembunyikan form tambah dan kembalikan tampilan ke tabel daftar meter
                $("#meter_add").hide();
                $("#meter_list").show();
                
                // Bersihkan peringatan dan reset form agar bersih jika dibuka lagi
                $("#peringatan-meter-bulan-ini").remove();
                $("#form_meter")[0].reset();
            }
        });
        $("button[data-bs-toggle='modal']").click(function() {
            no=$(this).attr('data-no');
            $("#myModal .modal-body").text("Apakah Anda yakin ingin menghapus data meter " + no + "?");  
            $(".modal-footer form input[name='user'], .modal-footer form input[name='tarif'], .modal-footer form input[name='kode_tarif']").remove();
            $(".modal-footer form").append("<input type='hidden' name='no' value='" + no + "'>");
            $(".modal-footer form button[name='tombol']").val("meter_hapus");
        });
    } else if (e[1] === "pemakaian_sendiri_list") {
        $("#summary,#chart,#tarif_add,#tarif_list,#user_add,#user_list,#meter_add,#meter_list").hide();
        $("#pemakaian_sendiri_list").show();
        const datatablesSimple = document.getElementById('pemakaian_sendiri_table');
        if (datatablesSimple) {
            new simpleDatatables.DataTable(datatablesSimple);
        }
    }else {
        $("#summary,#chart").show();
        $("#pilih_waktu select[name='pilih_waktu']").on('change', function() {
            var selectedMonth = $(this).val();
            reloadDashboardCharts(selectedMonth);
            $.ajax({
                type:"post",
                url:"../assets/ajax.php",
                data:{p:"summary",t:selectedMonth,level:user_level},
                dataType:"json",
            })
            .done(function(d){
                if (typeof user_level !== 'undefined' && user_level === 'Warga') {
                    // $("#summary .bg-primary h1").text(d[0].tgl || '-');
                    // $("#summary .bg-primary .ms-3").text(d[0].waktu || '-');
                    $("#warga_tgl_val").text(d[0].tgl || '-');
                    $("#warga_waktu_val").text(d[0].waktu || '-');
                    $("#summary .bg-warning h1").text(d[0].pemakaian || 0);
                    $("#summary .bg-success h1").text(
                        Number(d[0].tagihan || 0).toLocaleString('id-ID')
                    );
                    let status = d[0].status;
                    if (status == 'Belum Ada Data') {
                        status = '-';
                    } else if (status != 'Lunas') {
                        status = 'BELUM LUNAS';
                    }
                    $("#summary .bg-danger h1").text(status);
                } else {
                    var pelanggan = parseInt(d[0].pelanggan || 0, 10);
                    $("#summary .bg-primary h1").text(d[0].pelanggan);
                    if (typeof user_level !== 'undefined' && user_level === 'Bendahara') {
                        var lunas = parseInt(d[2].lunas || 0, 10);
                        var belum_bayar = pelanggan - lunas;
                        $("#summary .bg-warning h1").text(
                            Number(d[1].pemasukan || 0).toLocaleString('id-ID')
                        );
                        $("#summary .bg-success h1").text(lunas);
                        $("#summary .bg-danger h1").text(belum_bayar);
                    } else {
                        var tercatat = parseInt(d[2].tercatat || 0, 10);
                        var blm_dicatat = pelanggan - tercatat;
                        $("#summary .bg-warning h1").text(d[1].pemakaian || 0);
                        $("#summary .bg-success h1").text(tercatat);
                        $("#summary .bg-danger h1").text(blm_dicatat);
                    }
                }
            })
            .fail(function(){

            })
        })
        function reloadDashboardCharts(selectedMonth) {
            if (window.Chart && Chart.instances) {
                Object.keys(Chart.instances).forEach(function(chartKey) {
                    if (Chart.instances[chartKey]) {
                        Chart.instances[chartKey].destroy();
                    }
                });
            }

        if (typeof user_level !== 'undefined' && (user_level === 'Admin' || user_level === 'Bendahara' || user_level === 'Petugas')) {
            $.ajax({
                type:"post",
                url:"../assets/ajax.php",
                data:{p:"chart_admin_area",t:selectedMonth,level:user_level},
                dataType:"json",
            })
            .done(function(response){
                let sumbux=response.filter((num, index) => index % 2 == 0);
                let sumbuy=response.filter((num, index) => index % 2 != 0);
                let totalPemakaian = sumbuy.reduce((a, b) => parseFloat(a) + parseFloat(b), 0);
                let maxVal = Math.max(...sumbuy.map(Number));
                if(maxVal === 0 || !isFinite(maxVal)) maxVal = 10;
                
                $("#totalPemakaianAirVal").text(totalPemakaian.toLocaleString('id-ID') + " m³");
                
                Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
                Chart.defaults.global.defaultFontColor = '#292b2c';

                var ctx = document.getElementById("myAreaChartAdmin");
                var myLineChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: sumbux,
                        datasets: [{
                            label: "Pemakaian (m³)",
                            lineTension: 0.3,
                            backgroundColor: "rgba(2,117,216,0.2)",
                            borderColor: "rgba(2,117,216,1)",
                            pointRadius: 5,
                            pointBackgroundColor: "rgba(2,117,216,1)",
                            pointBorderColor: "rgba(255,255,255,0.8)",
                            pointHoverRadius: 5,
                            pointHoverBackgroundColor: "rgba(2,117,216,1)",
                            pointHitRadius: 50,
                            pointBorderWidth: 2,
                            data: sumbuy,
                        }],
                    },
                    options: {
                        scales: {
                            xAxes: [{
                                time: { unit: 'date' },
                                gridLines: { display: false },
                                ticks: { maxTicksLimit: 7 }
                            }],
                            yAxes: [{
                                ticks: { min: 0, max: maxVal, maxTicksLimit: 7 },
                                gridLines: { color: "rgba(0, 0, 0, .125)" }
                            }],
                        },
                        legend: { display: false }
                    }
                });
            });

            $.ajax({
                type:"post",
                url:"../assets/ajax.php",
                data:{p:"chart_admin_pie",t:selectedMonth,level:user_level},
                dataType:"json",
            })
            .done(function(response){
                let pieLabels=response.filter((num, index) => index % 2 == 0);
                let pieData=response.filter((num, index) => index % 2 != 0);
                var ctx = document.getElementById("myPieChartAdmin");
                var myPieChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: pieLabels,
                        datasets: [{
                            data: pieData,
                            backgroundColor: ['#007bff', '#dc3545', '#ffc107', '#28a745'],
                        }],
                    },
                });
            });

            if (user_level === 'Admin' || user_level === 'Bendahara') {
            $.ajax({
                type:"post",
                url:"../assets/ajax.php",
                data:{p:"chart_admin_tagihan",t:selectedMonth,level:user_level},
                dataType:"json",
            })
            .done(function(response){
                let sumbux=response.filter((num, index) => index % 2 == 0);
                let sumbuy=response.filter((num, index) => index % 2 != 0);
                let totalTagihan = sumbuy.reduce((a, b) => parseFloat(a) + parseFloat(b), 0);
                let maxVal = Math.max(...sumbuy.map(Number));
                if(maxVal === 0 || !isFinite(maxVal)) maxVal = 10;
                $("#totalTagihanAirVal").text("Rp " + totalTagihan.toLocaleString('id-ID'));
                var ctx = document.getElementById("myAreaChartAdminTagihan");
                var myLineChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: sumbux,
                        datasets: [{
                            label: "Tagihan Air (Rp)",
                            lineTension: 0.3,
                            backgroundColor: "rgba(2,117,216,0.2)",
                            borderColor: "rgba(2,117,216,1)",
                            pointRadius: 5,
                            pointBackgroundColor: "rgba(2,117,216,1)",
                            pointBorderColor: "rgba(255,255,255,0.8)",
                            pointHoverRadius: 5,
                            pointHoverBackgroundColor: "rgba(2,117,216,1)",
                            pointHitRadius: 50,
                            pointBorderWidth: 2,
                            data: sumbuy,
                        }],
                    },
                    options: {
                        scales: {
                            xAxes: [{
                                time: { unit: 'month' },
                                gridLines: { display: false },
                                ticks: { maxTicksLimit: 7 }
                            }],
                            yAxes: [{
                                ticks: { min: 0, max: maxVal, maxTicksLimit: 7 },
                                gridLines: { color: "rgba(0, 0, 0, .125)" }
                            }],
                        },
                        legend: { display: false }
                    }
                });
            });

            $.ajax({
                type:"post",
                url:"../assets/ajax.php",
                data:{p:"chart_admin_pemasukan",t:selectedMonth,level:user_level},
                dataType:"json",
            })
            .done(function(response){
                let sumbux=response.filter((num, index) => index % 2 == 0);
                let sumbuy=response.filter((num, index) => index % 2 != 0);
                let totalPemasukan = sumbuy.reduce((a, b) => parseFloat(a) + parseFloat(b), 0);
                let maxValPemasukan = Math.max(...sumbuy.map(Number));
                if(maxValPemasukan === 0 || !isFinite(maxValPemasukan)) maxValPemasukan = 10;
                $("#totalPemasukanVal").text("Rp " + totalPemasukan.toLocaleString('id-ID'));
                var ctx = document.getElementById("myAreaChartAdminPemasukan");
                var myLineChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: sumbux,
                        datasets: [{
                            label: "Pemasukan (Rp)",
                            lineTension: 0.3,
                            backgroundColor: "rgba(2,117,216,0.2)",
                            borderColor: "rgba(2,117,216,1)",
                            pointRadius: 5,
                            pointBackgroundColor: "rgba(2,117,216,1)",
                            pointBorderColor: "rgba(255,255,255,0.8)",
                            pointHoverRadius: 5,
                            pointHoverBackgroundColor: "rgba(2,117,216,1)",
                            pointHitRadius: 50,
                            pointBorderWidth: 2,
                            data: sumbuy,
                        }],
                    },
                    options: {
                        scales: {
                            xAxes: [{
                                time: { unit: 'month' },
                                gridLines: { display: false },
                                ticks: { maxTicksLimit: 7 }
                            }],
                            yAxes: [{
                                ticks: { min: 0, max: maxValPemasukan, maxTicksLimit: 7 },
                                gridLines: { color: "rgba(0, 0, 0, .125)" }
                            }],
                        },
                        legend: { display: false }
                    }
                });
            });
            }
            $.when(
                $.ajax({ type:"post", url:"../assets/ajax.php", data:{p:"chart_admin_tercatat",t:selectedMonth,level:user_level}, dataType:"json" }),
                $.ajax({ type:"post", url:"../assets/ajax.php", data:{p:"chart_admin_belum_tercatat",t:selectedMonth,level:user_level}, dataType:"json" })
            ).done(function(resTercatat, resBelumTercatat) {
                let dataTercatat = resTercatat[0];
                let dataBelumTercatat = resBelumTercatat[0];
                let sumbuxT = dataTercatat.filter((num, index) => index % 2 == 0);
                let sumbuyT = dataTercatat.filter((num, index) => index % 2 != 0);
                let sumbuxBT = dataBelumTercatat.filter((num, index) => index % 2 == 0);
                let sumbuyBT = dataBelumTercatat.filter((num, index) => index % 2 != 0);
                let maxValTercatat = Math.max(...sumbuyT.map(Number));
                let maxValBelumTercatat = Math.max(...sumbuyBT.map(Number));
                let sharedMaxTercatat = Math.max(maxValTercatat, maxValBelumTercatat);
                if(sharedMaxTercatat === 0 || !isFinite(sharedMaxTercatat)) sharedMaxTercatat = 10;
                var ctxT = document.getElementById("myBarChartAdminTercatat");
                new Chart(ctxT, {
                    type: 'bar',
                    data: {
                        labels: sumbuxT,
                        datasets: [{
                            label: "Jumlah Warga Tercatat",
                            backgroundColor: "rgba(2,117,216,1)",
                            borderColor: "rgba(2,117,216,1)",
                            data: sumbuyT,
                        }],
                    },
                    options: {
                        scales: {
                            xAxes: [{ time: { unit: 'month' }, gridLines: { display: false }, ticks: { maxTicksLimit: 7 } }],
                            yAxes: [{ ticks: { min: 0, max: sharedMaxTercatat, maxTicksLimit: 7 }, gridLines: { display: true } }],
                        },
                        legend: { display: false }
                    }
                });

                var ctxBT = document.getElementById("myBarChartAdminBelum");
                new Chart(ctxBT, {
                    type: 'bar',
                    data: {
                        labels: sumbuxBT,
                        datasets: [{
                            label: "Jumlah Warga Belum Tercatat",
                            backgroundColor: "rgba(2,117,216,1)",
                            borderColor: "rgba(2,117,216,1)",
                            data: sumbuyBT,
                        }],
                    },
                    options: {
                        scales: {
                            xAxes: [{ time: { unit: 'month' }, gridLines: { display: false }, ticks: { maxTicksLimit: 7 } }],
                            yAxes: [{ ticks: { min: 0, max: sharedMaxTercatat, maxTicksLimit: 7 }, gridLines: { display: true } }],
                        },
                        legend: { display: false }
                    }
                });
            });
            if (user_level === 'Admin' || user_level === 'Bendahara') {
            $.when(
                $.ajax({ type:"post", url:"../assets/ajax.php", data:{p:"chart_admin_sudah_lunas",t:selectedMonth,level:user_level}, dataType:"json" }),
                $.ajax({ type:"post", url:"../assets/ajax.php", data:{p:"chart_admin_belum_lunas",t:selectedMonth,level:user_level}, dataType:"json" })
            ).done(function(resSudahLunas, resBelumLunas) {
                let dataSudahLunas = resSudahLunas[0];
                let dataBelumLunas = resBelumLunas[0];
                let sumbuxSL = dataSudahLunas.filter((num, index) => index % 2 == 0);
                let sumbuySL = dataSudahLunas.filter((num, index) => index % 2 != 0);
                let sumbuxBL = dataBelumLunas.filter((num, index) => index % 2 == 0);
                let sumbuyBL = dataBelumLunas.filter((num, index) => index % 2 != 0);
                let maxValSudahLunas = Math.max(...sumbuySL.map(Number));
                let maxValBelumLunas = Math.max(...sumbuyBL.map(Number));
                let sharedMaxLunas = Math.max(maxValSudahLunas, maxValBelumLunas);
                if(sharedMaxLunas === 0 || !isFinite(sharedMaxLunas)) sharedMaxLunas = 10;

                var ctxSL = document.getElementById("myBarChartAdminSudahLunas");
                new Chart(ctxSL, {
                    type: 'bar',
                    data: {
                        labels: sumbuxSL,
                        datasets: [{
                            label: "Jumlah Warga Sudah LUNAS",
                            backgroundColor: "rgba(2,117,216,1)",
                            borderColor: "rgba(2,117,216,1)",
                            data: sumbuySL,
                        }],
                    },
                    options: {
                        scales: {
                            xAxes: [{ time: { unit: 'month' }, gridLines: { display: false }, ticks: { maxTicksLimit: 8 } }],
                            yAxes: [{ ticks: { min: 0, max: sharedMaxLunas, maxTicksLimit: 8 }, gridLines: { display: true } }],
                        },
                        legend: { display: false }
                    }
                });

                var ctxBL = document.getElementById("myBarChartAdminBelumLunas");
                new Chart(ctxBL, {
                    type: 'bar',
                    data: {
                        labels: sumbuxBL,
                        datasets: [{
                            label: "Jumlah Warga Belum LUNAS",
                            backgroundColor: "rgba(2,117,216,1)",
                            borderColor: "rgba(2,117,216,1)",
                            data: sumbuyBL,
                        }],
                    },
                    options: {
                        scales: {
                            xAxes: [{ time: { unit: 'month' }, gridLines: { display: false }, ticks: { maxTicksLimit: 7 } }],
                            yAxes: [{ ticks: { min: 0, max: sharedMaxLunas, maxTicksLimit: 7 }, gridLines: { display: true } }],
                        },
                        legend: { display: false }
                    }
                });
            });
            }
        } else {
            $.ajax({
                        type:"post",
                        url:"../assets/ajax.php",
                        data:{p:"chart_bar",t:selectedMonth,level:user_level},
                        dataType:"json",
                })
            .done(function(response){
                    let sumbux=response.filter((num, index) => index % 2 == 0);
                    let sumbuy=response.filter((num, index) => index % 2 != 0);
                    let totalPemakaian = sumbuy.reduce((a, b) => parseFloat(a) + parseFloat(b), 0);
                    let maxValBar = Math.max(...sumbuy.map(Number));
                    if (maxValBar === 0 || !isFinite(maxValBar)) maxValBar = 10;
                    $("#totalPemakaianAirVal").text(totalPemakaian.toLocaleString('id-ID') + " m³");
                    
                    Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
                    Chart.defaults.global.defaultFontColor = '#292b2c';

                    var ctx = document.getElementById("myBarChart");
                    var myLineChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: sumbux,
                        datasets: [{
                        label: "Pemakaian m³ ",
                        backgroundColor: "rgba(2,117,216,1)",
                        borderColor: "rgba(2,117,216,1)",
                        data: sumbuy,
                        }],
                    },
                    options: {
                        scales: {
                        xAxes: [{
                            time: {
                            unit: 'month'
                            },
                            gridLines: {
                            display: false
                            },
                            ticks: {
                            maxTicksLimit: 7
                            }
                        }],
                        yAxes: [{
                            ticks: {
                            min: 0,
                            max: maxValBar,
                            maxTicksLimit: 7
                            },
                            gridLines: {
                            display: true
                            }
                        }],
                        },
                        legend: {
                        display: false
                        }
                    }
                });
            });
            $.ajax({
                type: "post",
                url: "../assets/ajax.php",
                data: { p: "chart_area", t: selectedMonth, level: user_level },
                dataType: "json",
            })
            .done(function(response) {
                console.log(response);
                var chart_arr = response.chart || [];
                var sumbux = chart_arr.filter((num, index) => index % 2 == 0);
                var sumbuy = chart_arr.filter((num, index) => index % 2 != 0);
                var valLunas = Number(response.lunas || 0);
                var valBelum = Number(response.belum || 0);

                $("#totalPembayaranAirVal").html(
                    "Rp " + valLunas.toLocaleString('id-ID')
                );

                $("#totalBelumLunasVal").html(
                    "Rp " + valBelum.toLocaleString('id-ID')
                );

                let maxValArea = Math.max(...sumbuy.map(Number));
                if (maxValArea === 0 || !isFinite(maxValArea)) {
                    maxValArea = 10;
                }

                var ctx = document.getElementById("myAreaChart");

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: sumbux,
                        datasets: [{
                            label: "Tagihan (Rp)",
                            lineTension: 0.3,
                            backgroundColor: "rgba(2,117,216,0.2)",
                            borderColor: "rgba(2,117,216,1)",
                            pointRadius: 5,
                            pointBackgroundColor: "rgba(2,117,216,1)",
                            pointBorderColor: "rgba(255,255,255,0.8)",
                            pointHoverRadius: 5,
                            pointHoverBackgroundColor: "rgba(2,117,216,1)",
                            pointHitRadius: 50,
                            pointBorderWidth: 2,
                            data: sumbuy,
                        }],
                    },
                    options: {
                        scales: {
                            xAxes: [{
                                gridLines: {
                                    display: false
                                }
                            }],
                            yAxes: [{
                                ticks: {
                                    min: 0,
                                    max: maxValArea,
                                    maxTicksLimit: 8
                                }
                            }]
                        },
                        legend: {
                            display: false
                        }
                    }
                });
            });
        }
        }
        if (!$("#pilih_waktu select[name='pilih_waktu']").val()) {
            if (user_level !== 'Warga') {
                var today = new Date();
                var month = String(today.getMonth() + 1).padStart(2, '0');
                $("#pilih_waktu select[name='pilih_waktu']").val(today.getFullYear() + "-" + month);
            }
        }
        $("#pilih_waktu select[name='pilih_waktu']").trigger("change");
        $("#tarif_add,#tarif_list,#user_add,#user_list,#meter_add,#meter_list,#pemakaian_sendiri_list").hide();
    }
})
