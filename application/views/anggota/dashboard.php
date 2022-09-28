<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?php echo $title ?></h1>
    </div>

    <div class="alert alert-success font-weight-bold mb-4" style="width: 65%">Selamat datang, Anda login sebagai anggota.</div>

    <div class="card" style="margin-bottom: 120px; width: 65%">
        <div class="card-header font-weight-bold bg-primary text-white">
            Data Anggota
        </div>
        <?php foreach($anggota as $p) : ?>

            <?php 
            $jabatan = '';
            if($p->status==1){
                $jabatan = 'Ketua';
            }else if($p->status==2){
                 $jabatan = 'Sekretaris';
            }else if($p->status==3){
                 $jabatan = 'Bendahara';
            }else if($p->status==4){
                 $jabatan = 'Anggota Internal';
            }else if($p->status==5){
                 $jabatan = 'Anggota Eksternal';
            }
        
                ?>
        <div class="card-body">
            <div class="row">
                <div class="col-md-5">
                    <img style="width: 250px" src="<?php echo base_url('assets/photo/'.$p->photo) ?>">
                </div>
            
                <div class="col-md-7">
                    <table class="table">
                        <tr>
                            <td>No. Anggota</td>
                            <td>:</td>
                            <td><?php echo $p->nik ?></td>
                        </tr>
                        <tr>
                            <td>Nama Pegawai</td>
                            <td>:</td>
                            <td><?php echo $p->nama_anggota ?></td>
                        </tr>
                        <tr>
                            <td>Username</td>
                            <td>:</td>
                            <td><?php echo $p->username ?></td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td>:</td>
                            <td><?php echo $jabatan ?></td>
                        </tr>
                        <tr>
                            <td>Tanggal Masuk</td>
                            <td>:</td>
                            <td><?php echo date('d - M - Y', strtotime($p->tanggal_masuk)) ?></td>
                        </tr>
                        <tr>
                            <td>Alamat Anggota</td>
                            <td>:</td>
                            <td><?php echo $p->alamat_anggota ?></td>
                        </tr>
                        <tr>
                            <td>Jenis Kelamin</td>
                            <td>:</td>
                            <td><?php echo $p->jenis_kelamin ?></td>
                        </tr>
                        <tr>
                            <td>Nomor Telepon</td>
                            <td>:</td>
                            <td><?php echo $p->no_telp ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <?php endforeach; ?>

    </div>

</div>