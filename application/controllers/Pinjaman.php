<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

require_once APPPATH . 'core/Admin_Controller.php';

class Pinjaman extends Admin_Controller {

    public function __construct(){
        parent::__construct();
    }

    public function index()
    {
        if($this->data['is_can_read']){
            $anggota = "";
            if($this->session->userdata('hak_akses') == 2){
                $anggota = $this->session->userdata('id_anggota');
            }
    
            $this->data['title'] = "Data Pinjaman";
            $this->data['pinjaman'] = $this->koperasiModel->get_data_pinjaman($anggota)->result();
            $this->load->view('templates_admin/header', $this->data);
            $this->load->view('templates_admin/sidebar', $this->data);
            $this->load->view('admin/pinjaman', $this->data);
            $this->load->view('templates_admin/footer');
        }else{
            show_404();
        }
    }

    public function listAnggota()
    {
        if($this->data['is_can_create']){
            $this->data['title'] = "List Anggota";
            $this->data['anggota'] = $this->db->get_where('data_anggota',['hak_akses'=>2])->result();
            $this->load->view('templates_admin/header', $this->data);
            $this->load->view('templates_admin/sidebar', $this->data);
            $this->load->view('admin/listAnggotaPinjaman', $this->data);
            $this->load->view('templates_admin/footer');
        }else{
            show_404();
        }
    }

    public function tambahData($id_anggota)
    {
        if($this->data['is_can_create']){
            $this->data['title'] = "Tambah Data Pinjaman";
            $this->data['anggota'] = $this->db->get_where('data_anggota',['id_anggota'=> $id_anggota])->row();
            $this->load->view('templates_admin/header', $this->data);
            $this->load->view('templates_admin/sidebar', $this->data);
            $this->load->view('admin/formTambahPinjaman', $this->data);
            $this->load->view('templates_admin/footer');
        }else{
            show_404();
        }
    }

    public function tambahDataAksi()
    {
        if($this->data['is_can_create']){
            $this->form_validation->set_rules('id_anggota', 'Anggota', 'required');
            $this->form_validation->set_rules('jumlah_pinjaman', 'Jumlah Pinjaman', 'required');
            $this->form_validation->set_rules('tanggal_pinjaman', 'Tanggal Pinjaman', 'required');
            $this->form_validation->set_rules('lama', 'Lama Pinjaman', 'required');
    
            if($this->form_validation->run() == FALSE) {
                $this->tambahData();
            }else{
                $id_anggota         = $this->input->post('id_anggota');
                $jumlah_pinjaman    = $this->input->post('jumlah_pinjaman');
                $tanggal_pinjaman   = $this->input->post('tanggal_pinjaman');
                $lama               = $this->input->post('lama');
    
                $last_number = $this->koperasiModel->get_total_data_pinjaman($id_anggota); 
                if($last_number){
                    $last_number = str_pad($last_number+1, 3, '0', STR_PAD_LEFT);
                }else{
                    $last_number = '001';
                }
                $no_pinjaman = 'P'.date('ymd').$id_anggota.$last_number;
    
                $data = array(
                    'id_anggota'        => $id_anggota,
                    'no_pinjaman'       => $no_pinjaman,
                    'jumlah_pinjaman'   => $jumlah_pinjaman,
                    'tanggal_pinjaman'  => $tanggal_pinjaman,
                    'lama'              => $lama,
                    'status'            => 'belum lunas'
                );
    
                $this->koperasiModel->insert_data($data, 'data_pinjaman');
                $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Data berhasil ditambahkan!</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>');
                redirect('pinjaman');
            }
        }else{
            show_404();
        }
    }

    public function updateData($id)
    {
        if($this->data['is_can_edit']){
            $this->data['title'] = "Update Data Pinjaman";
            $pinjaman = $this->db->get_where('data_pinjaman', ['id' => $id])->row();
            $this->data['pinjaman'] = $pinjaman;
            $this->data['anggota'] = $this->db->get_where('data_anggota',['id_anggota'=> $pinjaman->id_anggota])->row();
            $this->load->view('templates_admin/header', $this->data);
            $this->load->view('templates_admin/sidebar', $this->data);
            $this->load->view('admin/formupdatePinjaman', $this->data);
            $this->load->view('templates_admin/footer');
        }else{
            show_404();
        }
    }

    public function updateDataAksi()
    {
        if($this->data['is_can_edit']){
            $this->form_validation->set_rules('id', 'Anggota', 'required');
            $this->form_validation->set_rules('jumlah_pinjaman', 'Jumlah Pinjaman', 'required');
            $this->form_validation->set_rules('tanggal_pinjaman', 'Tanggal Pinjaman', 'required');
            $this->form_validation->set_rules('lama', 'Lama Pinjaman', 'required');
    
            if($this->form_validation->run() == FALSE) {
                $id         = $this->input->post('id');
                $this->updateData($id);
            }else{
                $id                 = $this->input->post('id');
                $jumlah_pinjaman    = $this->input->post('jumlah_pinjaman');
                $tanggal_pinjaman   = $this->input->post('tanggal_pinjaman');
                $lama               = $this->input->post('lama');
    
                $data = array(
                    'jumlah_pinjaman'   => $jumlah_pinjaman,
                    'tanggal_pinjaman'  => $tanggal_pinjaman,
                    'lama'              => $lama
                );
    
                $this->koperasiModel->update_data('data_pinjaman', $data, ['id' => $id]);
                $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Data berhasil diupdate!</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>');
                redirect('pinjaman');
            }
        }else{
            show_404();
        }
    }

    public function deleteData($id)
    {
        if($this->data['is_can_delete']){
            $where = array('id' => $id);
            $this->koperasiModel->delete_data($where, 'data_pinjaman');
            $where = array('id_pinjaman' => $id);
            $this->koperasiModel->delete_data($where, 'data_angsuran');
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Data berhasil dihapus!</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>');
            redirect('pinjaman');
        }else{
            show_404();
        }
    }

    public function detailAngsuran($id)
    {
        if($this->data['is_can_read']){
            $this->data['title'] = "List Angsuran";
            $pinjaman = $this->db->get_where('data_pinjaman', ['id' => $id])->row();
            $this->data['id_pinjaman'] = $id;
            $sudah_dibayar = $this->db->select('SUM(jumlah_angsuran) as total')->from('data_angsuran')->where('id_pinjaman', $pinjaman->id)->get()->row()->total;
            $this->data['pinjaman'] = $pinjaman;
            if($pinjaman->jumlah_pinjaman > $sudah_dibayar){
                $this->data['belum_dibayar'] = $pinjaman->jumlah_pinjaman - $sudah_dibayar;
            }else{
                $this->data['belum_dibayar'] = 0;
            }
    
            $this->data['sudah_dibayar'] = $sudah_dibayar;
            $this->data['anggota'] = $this->db->get_where('data_anggota',['id_anggota'=> $pinjaman->id_anggota])->row();
            $this->data['angsuran'] = $this->db->get_where('data_angsuran', ['id_pinjaman' => $pinjaman->id])->result();
            $this->load->view('templates_admin/header', $this->data);
            $this->load->view('templates_admin/sidebar', $this->data);
            $this->load->view('admin/angsuran', $this->data);
            $this->load->view('templates_admin/footer');
        }else{
            show_404();
        }
    }

    public function tambahAngsuran($id)
    {
        if($this->data['is_can_create']){
            $this->data['title'] = "Tambah Angsuran";
            $pinjaman       = $this->db->get_where('data_pinjaman', ['id' => $id])->row();
            $this->data['pinjaman']   = $pinjaman;
            $this->data['anggota']    = $this->db->get_where('data_anggota',['id_anggota'=> $pinjaman->id_anggota])->row();
            $this->load->view('templates_admin/header', $this->data);
            $this->load->view('templates_admin/sidebar', $this->data);
            $this->load->view('admin/formTambahAngsuran', $this->data);
            $this->load->view('templates_admin/footer');
        }else{
            show_404();
        }
    }

    public function tambahAngsuranAksi()
    {
        if($this->data['is_can_create']){
            $this->form_validation->set_rules('id_pinjaman', 'Pinjaman ID', 'required');
            $this->form_validation->set_rules('jumlah_angsuran', 'Jumlah Angsuran', 'required');
            $this->form_validation->set_rules('tanggal_bayar', 'Tanggal Angsuran', 'required');
    
            if($this->form_validation->run() == FALSE) {
                $this->tambahData();
            }else{
                $id_pinjaman        = $this->input->post('id_pinjaman');
                $jumlah_angsuran    = $this->input->post('jumlah_angsuran');
                $tanggal_bayar      = $this->input->post('tanggal_bayar');
    
                $last_number = $this->koperasiModel->get_total_data_angsuran($id_pinjaman); 
                if($last_number){
                    $last_number = str_pad($last_number+1, 3, '0', STR_PAD_LEFT);
                }else{
                    $last_number = '001';
                }
                $no_angsuran = 'A'.date('ymd').$id_pinjaman.$last_number;
    
                $data = array(
                    'id_pinjaman'       => $id_pinjaman,
                    'no_angsuran'       => $no_angsuran,
                    'jumlah_angsuran'   => $jumlah_angsuran,
                    'tanggal_bayar'  => $tanggal_bayar,
                );
                $this->koperasiModel->insert_data($data, 'data_angsuran');
                // check apakah angsuran sudah lunas
                $total_angsuran = $this->koperasiModel->get_data_count('data_angsuran', 'jumlah_angsuran', ['id_pinjaman' => $id_pinjaman])->row()->total;
                $total_pinjaman = $this->koperasiModel->get_data_where('data_pinjaman', 'id', $id_pinjaman)->row();
                if($total_angsuran >= $total_pinjaman->jumlah_pinjaman){
                    $this->koperasiModel->update_data('data_pinjaman', ['status' => 'lunas'], ['id' => $id_pinjaman]);
                }else{
                    $this->koperasiModel->update_data('data_pinjaman', ['status' => 'belum lunas'], ['id' => $id_pinjaman]);
                }
                
                
                $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Data berhasil ditambahkan!</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>');
                redirect('pinjaman/detailAngsuran/'.$id_pinjaman);
            }
        }else{
            show_404();
        }
    }

    public function updateAngsuran($id)
    {
        if($this->data['is_can_edit']){
            $this->data['title'] = "Update Angsuran";
            $angsuran           = $this->db->get_where('data_angsuran', ['id' => $id])->row();
            $pinjaman           = $this->db->get_where('data_pinjaman', ['id' => $angsuran->id_pinjaman])->row();
            $this->data['angsuran']   = $angsuran;
            $this->data['pinjaman']   = $pinjaman;
            $this->data['anggota']    = $this->db->get_where('data_anggota',['id_anggota'=> $pinjaman->id_anggota])->row();
            $this->load->view('templates_admin/header', $this->data);
            $this->load->view('templates_admin/sidebar', $this->data);
            $this->load->view('admin/formUpdateAngsuran', $this->data);
            $this->load->view('templates_admin/footer');
        }else{
            show_404();
        }
    }

    public function updateAngsuranAksi()
    {
        if($this->data['is_can_edit']){
            $this->form_validation->set_rules('id_angsuran', 'Angsuran Id', 'required');
            $this->form_validation->set_rules('jumlah_angsuran', 'Jumlah Angsuran', 'required');
            $this->form_validation->set_rules('tanggal_bayar', 'Tanggal Angsuran', 'required');
    
            if($this->form_validation->run() == FALSE) {
                $this->tambahData();
            }else{
                $id_pinjaman        = $this->input->post('id_pinjaman');
                $id_angsuran        = $this->input->post('id_angsuran');
                $jumlah_angsuran    = $this->input->post('jumlah_angsuran');
                $tanggal_bayar      = $this->input->post('tanggal_bayar');
    
                $data = array(
                    'jumlah_angsuran'   => $jumlah_angsuran,
                    'tanggal_bayar'     => $tanggal_bayar,
                );
    
                $this->koperasiModel->update_data('data_angsuran', $data, ['id' => $id_angsuran]);
    
                // check apakah angsuran sudah lunas
                $total_angsuran = $this->koperasiModel->get_data_count('data_angsuran', 'jumlah_angsuran', ['id_pinjaman' => $id_pinjaman])->row()->total;
                $total_pinjaman = $this->koperasiModel->get_data_where('data_pinjaman', 'id', $id_pinjaman)->row();
                if($total_angsuran >= $total_pinjaman->jumlah_pinjaman){
                    $this->koperasiModel->update_data('data_pinjaman', ['status' => 'lunas'], ['id' => $id_pinjaman]);
                }else{
                    $this->koperasiModel->update_data('data_pinjaman', ['status' => 'belum lunas'], ['id' => $id_pinjaman]);
                }
    
                $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Data berhasil ditambahkan!</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>');
                redirect('pinjaman/detailAngsuran/'.$id_pinjaman);
            }
        }else{
            show_404();
        }
    }

    public function deleteDataAngsuran($id)
    {
        if($this->data['is_can_delete']){
            $angsuran = $this->db->get_where('data_angsuran', ['id' => $id])->row();
            $where = array('id' => $id);
            $this->koperasiModel->delete_data($where, 'data_angsuran');
    
            // check apakah angsuran sudah lunas
            $total_angsuran = $this->koperasiModel->get_data_count('data_angsuran', 'jumlah_angsuran', ['id_pinjaman' => $angsuran->id_pinjaman])->row()->total;
            $total_pinjaman = $this->koperasiModel->get_data_where('data_pinjaman', 'id', $angsuran->id_pinjaman)->row();
            if($total_angsuran >= $total_pinjaman->jumlah_pinjaman){
                $this->koperasiModel->update_data('data_pinjaman', ['status' => 'lunas'], ['id' => $angsuran->id_pinjaman]);
            }else{
                $this->koperasiModel->update_data('data_pinjaman', ['status' => 'belum lunas'], ['id' => $angsuran->id_pinjaman]);
            }
    
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Data berhasil dihapus!</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>');
            redirect('pinjaman/detailAngsuran/'.$angsuran->id_pinjaman);
        }else{
            show_404();
        }
    }

    public function exportExcel() 
    {
        if($this->data['is_can_export_excel']){
            $spreadsheet = new Spreadsheet();
            \PhpOffice\PhpSpreadsheet\Cell\Cell::setValueBinder(new \PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder());
    
            $sheet = $spreadsheet->getActiveSheet();
    
            //set widht kolom
            $batas = "K";
            for($i = 'A'; $i <= 'Z'; $i++) {
                $sheet->getColumnDimension($i)->setAutoSize(true);
                if($batas == $i){
                    break;
                }
            }
    
            //set header tingkat 1
            $sheet->setCellValue('A1', "LAPORAN PINJAMAN ANGGOTA KOPERASI BOGOR GADING RESIDENCE");
            $sheet->getStyle("A1")->getFont()->setSize(16)->setBold(true);
            $sheet->mergeCells("A1:H1");
            
            $sheet->setCellValue('A4', 'NO.');
            $sheet->setCellValue('B4', 'NO. ANGGOTA');
            $sheet->setCellValue('C4', 'NAMA ANGGOTA');
            $sheet->setCellValue('D4', 'NO PINJAMAN');
            $sheet->setCellValue('E4', 'TANGGAL PEMINJAMAN');
            $sheet->setCellValue('F4', 'JUMLAH PINJAMAN');
            $sheet->setCellValue('G4', 'LAMA PINJAMAN');
            $sheet->setCellValue('H4', 'STATUS');
            $sheet->setCellValue('I4', 'JUMLAH PEMBAYARAN');
            $sheet->setCellValue('J4', 'JUMLAH PERIODE PEMBAYARAN');
            $sheet->setCellValue('K4', 'SISA YANG HARUS DIBAYAR');
            
            $sheet->getStyle("A4:K4")->getFont()->setBold(true);
            $sheet->getStyle('A4:K4')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('A4:K4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    
            $datas = $this->koperasiModel->get_all_data_pinjaman(); 
            //isi data
            $total_simpanan_pokok = 0;
            $total_sisa = 0;
            $no = 1;
            $x = 5;
            if (!empty($datas)) {
                foreach ($datas as $row) {
                    $sisa = $row->jumlah_pinjaman - $row->total_bayar;
    
                    $sheet->setCellValueExplicit('A' . $x, $no++, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $sheet->setCellValueExplicit('B' . $x, $row->nik, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $sheet->setCellValueExplicit('C' . $x, $row->nama_anggota, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $sheet->setCellValueExplicit('D' . $x, $row->no_pinjaman, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $sheet->setCellValueExplicit('E' . $x, $row->tanggal_pinjaman, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $sheet->setCellValueExplicit('F' . $x, $row->jumlah_pinjaman, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
                    $sheet->setCellValueExplicit('G' . $x, $row->lama." Bulan", \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $sheet->setCellValueExplicit('H' . $x, ucwords($row->status), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $sheet->setCellValueExplicit('I' . $x, $row->total_bayar, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
                    $sheet->setCellValueExplicit('J' . $x, $row->total_periode, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
                    $sheet->setCellValueExplicit('K' . $x, $sisa, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
                    
                    $x++;
                }
            }
    
            //set border
            $batas_akhir = intval($x) - 1;
            $styleArray = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '00000000'],
                    ],
                ],
            ];
            $sheet->getStyle('A4:K' . $batas_akhir)->applyFromArray($styleArray);
            //set align
            $sheet->getStyle('A5:K' . $batas_akhir)->getAlignment()->setVertical(Alignment::VERTICAL_TOP);
            $sheet->getStyle('A5:K' . $batas_akhir)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('B5:K' . $batas_akhir)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
    
            $writer = new Xlsx($spreadsheet);
            $filename = 'LAPORAN PINJAMAN ANGGOTA KOPERASI BOGOR GADING RESIDENCE TANGGAL '.date('d-m-Y');
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
            header('Cache-Control: max-age=0');
            $writer->save('php://output');
        }else{
            show_404();
        }
	}

    public function exportPdf($id)
    {
        if($this->data['is_can_export_pdf']){
            $this->load->library('pdf');
    
            $this->pdf->setPaper('A4', 'portrait');
            $this->pdf->set_option('isRemoteEnabled', TRUE);
            $this->pdf->set_option('defaultFont', 'arial');
            $this->pdf->set_base_path("/");
            $data = [];
            $data["listData"] = $this->db->order_by('id', 'ASC')->get_where("data_angsuran", ["id_pinjaman" => $id])->result();
            $pinjaman = $this->db->get_where('data_pinjaman', ['id' => $id])->row();
            $data['pinjaman'] = $pinjaman;
            $data['anggota']  = $this->db->get_where('data_anggota',['id_anggota'=> $pinjaman->id_anggota])->row();
            $this->pdf->filename = "Pinjaman ".$pinjaman->no_pinjaman." ".date("dmY").".pdf";
            $html = $this->load->view('admin/pdfPinjaman', $data, TRUE);
    
            $this->pdf->load_html($html);
            $this->pdf->render();
    
            $output = $this->pdf->output();
            $this->pdf->stream($this->pdf->filename, array("Attachment" => FALSE));
        }else{
            show_404();
        }
    }
}

?>