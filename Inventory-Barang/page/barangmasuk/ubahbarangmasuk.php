<script>
function sum() {
    var jumlahmasuk = document.getElementById('jumlahmasuk').value;
    var stok_terkini = <?php echo $stok_terkini; ?>;
    var result = parseInt(jumlahmasuk) + parseInt(stok_terkini);
    if (!isNaN(result)) {
        document.getElementById('jumlah').value = result;
    }

    if (parseInt(jumlahmasuk) <= 0) {
        document.getElementById('error_jumlah').innerText = 'Jumlah Masuk harus lebih besar dari 0.';
    } else {
        document.getElementById('error_jumlah').innerText = '';
    }
}
</script>



<?php
$koneksi = new mysqli("localhost", "root", "", "inventori");

if ($koneksi->connect_error) {
    die("Koneksi ke database gagal: " . $koneksi->connect_error);
}

$id_transaksi = $_GET['id_transaksi'];

$query = "SELECT * FROM barang_masuk WHERE id_transaksi = '$id_transaksi'";
$hasil = $koneksi->query($query);
$data = $hasil->fetch_assoc();

$format = $data['id_transaksi'];
$tanggal_masuk = $data['tanggal'];
$barang = $data['kode_barang'] . '.' . $data['nama_barang'];
$jumlah_masuk = $data['jumlah'];
$pengirim = $data['pengirim'];
$kondisibarang = $data['kondisibarang'];
?>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Ubah Barang Masuk</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <div class="body">
                    <form method="POST" enctype="multipart/form-data">
                        <label for="">Id Transaksi</label>
                        <div class="form-group">
                            <div class="form-line">
                                <input type="text" name="id_transaksi" class="form-control" id="id_transaksi" value="<?php echo $format; ?>" readonly />
                            </div>
                        </div>

                        <label for="">Tanggal Masuk</label>
                        <div class="form-group">
                            <div class="form-line">
                                <input type="date" name="tanggal_masuk" class="form-control" id="tanggal_masuk" value="<?php echo $tanggal_masuk; ?>" min="<?php echo $tanggal_masuk; ?>" max="<?php echo $tanggal_masuk; ?>" />
                            </div>
                        </div>

                        <label for="">Barang</label>
                        <div class="form-group">
                            <div class="form-line">
                                <select name="barang" id="cmb_barang" class="form-control" />
                                    <option value="">-- Pilih Barang  --</option>
                                    <?php
                                    $sql = $koneksi->query("select * from gudang order by kode_barang");
                                    while ($data = $sql->fetch_assoc()) {
                                        $selected = ($barang == $data['kode_barang'] . '.' . $data['nama_barang']) ? 'selected' : '';
                                        echo "<option value='$data[kode_barang].$data[nama_barang]' $selected>$data[kode_barang] | $data[nama_barang]</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="tampung"></div>

						<label for="">Jumlah</label>
<div class="form-group">
    <div class="form-line">
        <input type="text" name="jumlahmasuk" id="jumlahmasuk" onkeyup="sum()" class="form-control" value="<?php echo $jumlah_masuk; ?>" />
    </div>
    <span id="error_jumlah" style="color: red;"></span>
</div>


<label for="jumlah">Total Stok</label>
<div class="form-group">
    <div class="form-line">
        <input readonly="readonly" name="jumlah" id="jumlah" type="number" class="form-control" value="<?php echo $jumlah_masuk + $stok_terkini; ?>">
    </div>
</div>


                        <div class="tampung1"></div>

                        <label for="">Supplier</label>
                        <div class="form-group">
                            <div class="form-line">
                                <select name="pengirim" class="form-control" />
                                <option value="">-- Pilih Supplier  --</option>
                                <?php
                                $sql = $koneksi->query("select * from tb_supplier order by nama_supplier");
                                while ($data = $sql->fetch_assoc()) {
                                    $selected = ($pengirim == $data['nama_supplier']) ? 'selected' : '';
                                    echo "<option value='$data[nama_supplier]' $selected>$data[nama_supplier]</option>";
                                }
                                ?>
                                </select>
                            </div>
                        </div>

                        <label for="">Kondisi Barang</label>
                        <div class="form-group">
                            <div class="form-line">
                                <select name="kondisibarang" class="form-control" />
                                <option value="">-- Pilih Kondisi Barang  --</option>
                                <?php
                                $sql = $koneksi->query("select * from kondisibarang order by kondisibarang");
                                while ($data = $sql->fetch_assoc()) {
                                    $selected = ($kondisibarang == $data['kondisibarang']) ? 'selected' : '';
                                    echo "<option value='$data[kondisibarang]' $selected>$data[kondisibarang]</option>";
                                }
                                ?>
                                </select>
                            </div>
                        </div>

                        <input type="submit" name="simpan" value="Simpan" class="btn btn-primary">

                    </form>

					<?php

// Koneksi ke database
$koneksi = new mysqli("localhost", "root", "", "inventori");

if ($koneksi->connect_error) {
    die("Koneksi ke database gagal: " . $koneksi->connect_error);
}

if (isset($_POST['simpan'])) {
    $id_transaksi = $_POST['id_transaksi'];
    $tanggal_masuk = $_POST['tanggal_masuk'];
    $barang = $_POST['barang'];
    $pecah_barang = explode(".", $barang);
    $kode_barang = $pecah_barang[0];
    $nama_barang = $pecah_barang[1];
    $jumlah_masuk = $_POST['jumlahmasuk'];
    $pengirim = $_POST['pengirim'];
    $satuan = $_POST['satuan'];
    $kondisibarang = $_POST['kondisibarang'];

    // Lakukan validasi input di sini, seperti memastikan bahwa jumlah_masuk adalah angka yang valid.

    // Mulai transaksi
    $koneksi->begin_transaction();

    if ($jumlah_masuk <= 0) {
        echo '<div class="alert alert-danger">Jumlah masuk harus lebih besar dari 0.</div>';
    } elseif (empty($barang) || empty($jumlah_masuk) || empty($tanggal_masuk) || empty($pengirim)) {
        echo '<center><div class="alert alert-danger">Harap lengkapi semua data.</div></center>';
    } else {
        try {
            // Cek apakah sudah ada entri dengan tanggal, kode_barang, nama_barang, dan pengirim yang sama
            $sqlCheckDuplicate = $koneksi->query("SELECT * FROM barang_masuk WHERE tanggal = '$tanggal_masuk' AND kode_barang = '$kode_barang' AND nama_barang = '$nama_barang' AND pengirim = '$pengirim' ");

            if ($sqlCheckDuplicate->num_rows > 0) {
                // Jika sudah ada, lakukan update jumlah masuk
                $dataDuplicate = $sqlCheckDuplicate->fetch_assoc();
                $jumlah_terkini_masuk = $dataDuplicate['jumlah'];
                $jumlah_baru_masuk = $jumlah_terkini_masuk + $jumlah_masuk;

                $sqlUpdate = $koneksi->query("UPDATE barang_masuk SET jumlah = '$jumlah_baru_masuk' WHERE tanggal = '$tanggal_masuk' AND kode_barang = '$kode_barang' AND nama_barang = '$nama_barang' AND pengirim = '$pengirim' ");
                if (!$sqlUpdate) {
                    throw new Exception("Gagal memperbarui jumlah masuk: " . $koneksi->error);
                }
            } else {
                // Jika belum ada, buat entri baru
                $sqlInsert = $koneksi->query("INSERT INTO barang_masuk (id_transaksi, tanggal, kode_barang, nama_barang, pengirim, jumlah, satuan, kondisibarang) VALUES ('$id_transaksi', '$tanggal_masuk', '$kode_barang', '$nama_barang', '$pengirim', '$jumlah_masuk', '$satuan', '$kondisibarang')");
                if (!$sqlInsert) {
                    throw new Exception("Gagal menyimpan data masuk: " . $koneksi->error);
                }
            }

            $sqlUpdate = $koneksi->query("UPDATE barang_masuk SET jumlah = '$jumlah_masuk' WHERE id_transaksi = '$id_transaksi'");
            if (!$sqlUpdate) {
                throw new Exception("Gagal memperbarui jumlah masuk: " . $koneksi->error);
            }

            // Hitung jumlah total barang masuk untuk kode_barang dan nama_barang tertentu
            $sqlTotalJumlahMasuk = $koneksi->query("SELECT SUM(jumlah) AS total_jumlah_masuk FROM barang_masuk WHERE kode_barang = '$kode_barang' AND nama_barang = '$nama_barang'");
            $total_jumlah_masuk = $sqlTotalJumlahMasuk->fetch_assoc()['total_jumlah_masuk'];

            // Hitung jumlah total barang keluar untuk kode_barang dan nama_barang tertentu
            $sqlTotalJumlahKeluar = $koneksi->query("SELECT SUM(jumlah) AS total_jumlah_keluar FROM barang_keluar WHERE kode_barang = '$kode_barang' AND nama_barang = '$nama_barang'");
            $total_jumlah_keluar = $sqlTotalJumlahKeluar->fetch_assoc()['total_jumlah_keluar'];

            // Hitung jumlah stok tersisa di gudang
            $stok_terkini = $total_jumlah_masuk - $total_jumlah_keluar;

            // Update jumlah di tabel gudang
            $sqlUpdateGudang = $koneksi->query("UPDATE gudang SET jumlah = '$stok_terkini' WHERE kode_barang = '$kode_barang' AND nama_barang = '$nama_barang'");
            if (!$sqlUpdateGudang) {
                throw new Exception("Gagal memperbarui jumlah di gudang: " . $koneksi->error);
            }

            // Commit transaksi jika semua query berhasil
            $koneksi->commit();

            if ($sqlUpdate) {
                ?>
                <script type="text/javascript">
                    alert("Data Berhasil Disimpan");
                    window.location.href="?page=barangmasuk";
                </script>
                <?php
            }

            echo "Sukses memperbarui jumlah masuk dan stok di gudang.";
        } catch (Exception $e) {
            // Rollback transaksi jika ada kesalahan
            $koneksi->rollback();
            echo "Terjadi kesalahan: " . $e->getMessage();
        }
    }
}

?>


                </div>
            </div>
        </div>
    </div>
</div>