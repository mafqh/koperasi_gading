<?php

class DataAnggota extends CI_Controller{

    public function __construct(){
        parent::__construct();

        if(!$this->session->userdata('hak_akses')){
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
					<strong>Anda belum login!</strong>
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>');
				redirect('login');
        }
    }

    public function index()
    {
        $data['title'] = "Data Anggota";
        $data['anggota'] = $this->db->get_where('data_anggota',['hak_akses'=>2])->result();
        $this->load->view('templates_admin/header', $data);
        $this->load->view('templates_admin/sidebar');
        $this->load->view('admin/dataAnggota', $data);
        $this->load->view('templates_admin/footer');
    }

    public function tambahData()
    {
        $data['title'] = "Tambah Data Anggota";
        $this->load->view('templates_admin/header', $data);
        $this->load->view('templates_admin/sidebar');
        $this->load->view('admin/formTambahAnggota', $data);
        $this->load->view('templates_admin/footer');
    }

    public function tambahDataAksi()
    {
        $this->_rules();

        if($this->form_validation->run() == FALSE) {
            $this->tambahData();
        }else{
            $nik             = $this->input->post('nik');
            $alamat_anggota  = $this->input->post('alamat_anggota');
            $no_telp         = $this->input->post('no_telp');
            $nama_anggota    = $this->input->post('nama_anggota');
            $jenis_kelamin   = $this->input->post('jenis_kelamin');
            $tanggal_masuk   = $this->input->post('tanggal_masuk');
            $status          = $this->input->post('status');
            $username        = $this->input->post('username');
            $password        = md5($this->input->post('password'));
            $photo           = $_FILES['photo']['name'];
            if($photo=''){}else{
                $config ['upload_path'] = './assets/photo';
                $config ['allowed_types'] = 'jpg|jpeg|png|tiff';
                $this->load->library('upload', $config);
                if(!$this->upload->do_upload('photo')){
                    echo "Photo Gagal diupload!";
                }else{
                    $photo = $this->upload->data('file_name');
                }
            }

            $data = array(
                'nik'            => $nik,
                'alamat_anggota' => $alamat_anggota,
                'no_telp'        => $no_telp,
                'nama_anggota'   => $nama_anggota,
                'jenis_kelamin'  => $jenis_kelamin,
                'tanggal_masuk'  => $tanggal_masuk,
                'status'         => $status,
                'hak_akses'      => 2,
                'username'       => $username,
                'password'       => $password,
                'photo'          => $photo,
            );

            $this->koperasiModel->insert_data($data, 'data_anggota');
            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Data berhasil ditambahkan!</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>');
            redirect('admin/DataAnggota');
        }
    }

    public function detailAnggota($id)
    {
        $where = array('id_anggota' => $id);
        $data['title'] = "Detail Data Anggota";
        $data['anggota'] = $this->db->get_where('data_anggota',$where)->result();
        $this->load->view('templates_admin/header', $data);
        $this->load->view('templates_admin/sidebar');
        $this->load->view('admin/detailDataAnggota', $data);
        $this->load->view('templates_admin/footer');
    }

    public function updateData($id)
    {
        $where = array('id_anggota' => $id);
        $data['title'] = 'Update Data Anggota';
        $data['anggota'] = $this->db->get_where('data_anggota',$where)->result();
        $data['jabatan'] = $this->koperasiModel->get_data_where('data_jabatan','is_pengurus','0')->result();
        $this->load->view('templates_admin/header', $data);
        $this->load->view('templates_admin/sidebar');
        $this->load->view('admin/formUpdateAnggota', $data);
        $this->load->view('templates_admin/footer');
    }

    public function updateDataAksi()
    {
        $this->_rules();
        
            $id              = $this->input->post('id_anggota');
            $nik             = $this->input->post('nik');
            $nama_anggota    = $this->input->post('nama_anggota');
            $alamat_anggota  = $this->input->post('alamat_anggota');
            $no_telp         = $this->input->post('no_telp');
            $jenis_kelamin   = $this->input->post('jenis_kelamin');
            $tanggal_masuk   = $this->input->post('tanggal_masuk');
            $status          = $this->input->post('status');
            $username        = $this->input->post('username');
            $status          = $this->input->post('status');
            $photo           = $_FILES['photo']['name'];
            if($photo){
                $config ['upload_path'] = './assets/photo';
                $config ['allowed_types'] = 'jpg|jpeg|png|tiff';
                $this->load->library('upload', $config);
                if($this->upload->do_upload('photo')){
                    echo "Photo Gagal diupload!";
                    $photo = $this->upload->data('file_name');
                    $this->db->set('photo', $photo);
                }else{
                    echo $this->upload->display_errors();
                }
            }

            $data = array(
                'nik'            => $nik,
                'nama_anggota'   => $nama_anggota,
                'alamat_anggota' => $alamat_anggota,
                'no_telp'        => $no_telp,
                'jenis_kelamin'  => $jenis_kelamin,
                'tanggal_masuk'  => $tanggal_masuk,
                'status'         => $status,
                'hak_akses'      => 2,
                'username'       => $username,
            );

            $where = array(
                'id_anggota' => $id
            );

            $this->koperasiModel->update_data('data_anggota', $data, $where);
            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Data berhasil diupdate!</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>');
            redirect('admin/DataAnggota');
    }

    public function _rules()
    {
        $this->form_validation->set_rules('nik', 'NIK', 'required');
        $this->form_validation->set_rules('nama_anggota', 'nama anggota', 'required');
        $this->form_validation->set_rules('alamat_anggota', 'alamat anggota', 'required');
        $this->form_validation->set_rules('jenis_kelamin', 'jenis kelamin', 'required');
        $this->form_validation->set_rules('tanggal_masuk', 'tanggal masuk', 'required');
        $this->form_validation->set_rules('status', 'status', 'required');
        $this->form_validation->set_rules('no_telp', 'nomor telepon', 'required');
    }

    public function deleteData($id)
    {
        $where = array('id_anggota' => $id);
        $this->koperasiModel->delete_data($where, 'data_anggota');
        $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Data berhasil dihapus!</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>');
            redirect('admin/dataAnggota');
    }
}

?>