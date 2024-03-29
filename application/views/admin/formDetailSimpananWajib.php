<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?php echo $title ?></h1> 
        <?php if($is_can_export_pdf){ ?>
            <a href="<?php echo base_url('SimpananWajib/exportPdf/').$id_anggota; ?>" target="_blank" class="btn btn-sm btn-danger shadow-sm" id="btn-export-pdf"><i class="fas fa-file-pdf fa-sm"></i> Export PDF</a>
        <?php } ?>
    </div>
<?php $nominal = 240000; ?>
    <div class="alert alert-success font-weight-bold mb-4" style="width: 65%">Jumlah Simpanan Wajib yang harus dibayar <?= "Rp " . number_format($nominal,0,',','.'); ?></div>

<table class="table table-striped table-bordered">
<thead>
    <tr>
        <th>No</th>
        <th>NIK</th>
        <th>Nama Anggota</th>
        <th>Jenis Kelamin</th>
        <th>Jumlah</th>
        <th>Tanggal Bayar</th>
        <?php if($is_can_edit || $is_can_delete){ ?>
        <th>Aksi</th>
        <?php } ?>
    </tr>
</thead>
<tbody>
    <?php 
    $i = 1;
    $total = 0;
    $sisa =0;
    foreach ($anggota as $data) {
 
    ?>
    <tr>
        <td><?= $i++ ?></td>
        <td><?= $data->nik ?></td>
        <td><?= $data->nama_anggota ?></td>
        <td><?= $data->jenis_kelamin ?></td>
        <td><?= "Rp " . number_format($data->jumlah,0,',','.'); ?></td>
        <td><?= date('d - M - Y', strtotime($data->tanggal)) ?></td>
        <?php if($is_can_edit || $is_can_delete){ ?>
        <td>
            <center>
                <?php if($is_can_edit){ ?>
                    <a class="btn btn-sm btn-primary" href="<?php echo base_url('simpananWajib/editSimpananWajib/'.$data->id_simpanan_wajib) ?>"><i class="fas fa-edit"></i> Edit</a>
                <?php } ?>
                
                <?php if($is_can_delete){ ?>
                    <a class="btn btn-sm btn-danger" onclick="return confirm('yakin menghapus data ?')" href="<?php echo base_url('simpananWajib/deleteData/'.$data->id_simpanan_wajib.'/'.$data->id_anggota) ?>">Hapus</a>
                <?php } ?>
            </center>
        </td>
        <?php } ?>

    </tr>

    <?php $total = $total+$data->jumlah;?>
    <?php } ?>
    <?php $sisa = $nominal-$total ?>

    <?php
        $total_row = 5; 
        if($is_can_edit || $is_can_delete){ 
            $total_row = 6; 
        }
    ?>
    <tr>
        <th colspan="<?php echo $total_row; ?>">Total</th>
        <th><?= "Rp " . number_format($total,0,',','.'); ?></th>
    </tr>
    <tr>
        <th colspan="<?php echo $total_row; ?>">Sisa bayar</th>
        <th><?= "Rp ". number_format($sisa,0,',','.') ?></th>
    </tr>
</tbody>

</table>




</div>