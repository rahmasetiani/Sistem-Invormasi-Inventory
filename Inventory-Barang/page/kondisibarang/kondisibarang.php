




 <!-- Begin Page Content -->
 <div class="container-fluid">

<!-- DataTales Example -->
<div class="card shadow mb-4">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary">Kondisi Barang Keluar</h6>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
      <thead>
                              <tr>
            <th>No</th>
            <th>Kondisi Barang Keluar</th>
            
            <th>Pengaturan</th>
                               
                              </tr>
          </thead>
          
     
        <tbody>
          <?php 
        
        $no = 1;
        $sql = $koneksi->query("select * from kondisibarang");
        while ($data = $sql->fetch_assoc()) {
          
        ?>
        
                              <tr>
                                  <td><?php echo $no++; ?></td>
            <td><?php echo $data['kondisibarang'] ?></td>
            
                               

            <td>
            <a href="?page=kondisibarang&aksi=ubahkondisi&id=<?php echo $data['id'] ?>" class="btn btn-success" >Ubah</a>
            <a onclick="return confirm('Apakah anda yakin akan menghapus data ini?')" href="?page=kondisibarang&aksi=hapuskondisi&id=<?php echo $data['id'] ?>" class="btn btn-danger" >Hapus</a>
            </td>
                              </tr>
        <?php }?>

             </tbody>
                      </table>
      <a href="?page=kondisibarang&aksi=tambahkondisi" class="btn btn-primary" >Tambah</a>
        </tbody>
      </table>
    </div>
  </div>
</div>

</div>












