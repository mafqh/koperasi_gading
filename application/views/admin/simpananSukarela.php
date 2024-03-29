<div class="container-fluid">
    <!-- Page Heading -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0 text-gray-800"><?php echo $title ?></h1>
        </div>
        <div class="col-md-4">
            <?php if($is_can_export_excel){ ?>
                <div class="row">
                    <div class="col-md-2 pt-1"><label>Tahun</label></div>
                    <div class="col-md-5">
                        </option>
    
                        <select class="form-control form-control-sm" name="tahun" id="tahun" onchange="changeTahun(this)">
                            <?php for ($i=2017; $i <= 2050 ; $i++) { ?>
                                <option value="<?php echo $i ?>" <?php if (date("Y") == $i) {echo "selected";}?>>
                                <?php echo $i; ?>
                            <?php }?>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <a href="<?php echo base_url('SimpananSukarela/exportExcel/').date('Y'); ?>" class="btn btn-sm btn-success shadow-sm" id="btn-export-excel"><i class="fas fa-file-excel fa-sm text-white-50"></i> Export Excel</a>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

        <!-- Begin Page Content -->
    <table class="table table-striped table-bordered" id="myTable">
        <thead>
            <th class="text-centre">No</th>
            <th class="text-centre">No. Anggota</th>
            <th class="text-centre">Nama Anggota</th>
            <th class="text-centre">Total Tabungan</th>
            <th class="text-centre">Action</th>
        </thead>
        
        <tbody>
            <?php $no=1;
            $jenis_simpanan = $this->uri->segment(3);
            foreach($jenis as $a) : ?>
                <tr>
                    <td><?php echo $no++ ?></td>
                    <td><?php echo $a->nik ?></td>
                    <td><?php echo $a->nama_anggota ?></td>
                    <td><?php echo "Rp " . number_format($a->total,0,',','.'); ?></td>
                    <td>
                        <center>
                            <?php if($is_can_create){ ?>
                                <a class="btn btn-sm btn-primary" href="<?php echo base_url('simpananSukarela/tambahSimpananSukarela/'. $a->id_anggota) ?>"><i class="fas fa-plus"></i> Tabungan</a>
                                <a class="btn btn-sm btn-danger" href="<?php echo base_url('simpananSukarela/ambilSimpananSukarela/'. $a->id_anggota) ?>"><i class="fas fa-minus"></i> Tabungan</a>
                            <?php } ?>
                            
                            <?php if($is_can_read){ ?>
                                <a class="btn btn-sm btn-success" href="<?php echo base_url('simpananSukarela/detailSimpananSukarela/'. $a->id_anggota) ?>">Detail Simpanan Tabungan</a>
                            <?php } ?>
                        </center>
                    </td>
    
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>

<script>
    $(document).ready( function () {
        $('#myTable').DataTable();

    });

    function changeTahun(e) {
        var tahun = $(e).val();
        var url_excel = '<?php echo base_url('SimpananSukarela/exportExcel/'); ?>' + tahun;
        $("#btn-export-excel").attr("href", url_excel);
    }
</script>