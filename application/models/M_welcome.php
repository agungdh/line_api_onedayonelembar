<?php
class M_welcome extends CI_Model{	
	function __construct(){
		parent::__construct();		
	}

	function status_ngaji($id_user) {
		$sql = "SELECT *
				FROM fix
				WHERE id_user = ?
				AND date(waktu) = date(now())
				ORDER BY id DESC
				LIMIT 1";
		$query = $this->db->query($sql, array($id_user));
		$row = $query->row();

		return $row;
	}

	function list_user() {
		$sql = "SELECT id_line
				FROM user";
		$query = $this->db->query($sql, array());
		$row = $query->result();

		return $row;
	}

	function list_admin() {
		$sql = "SELECT id_line
				FROM user
				WHERE admin = 1";
		$query = $this->db->query($sql, array());
		$row = $query->result();

		return $row;
	}

	function tambah_fix($id_user, $tanggal) {
		$sql = "INSERT INTO fix (id_user, halaman, waktu) 
				SELECT id_user, halaman, ? waktu
				FROM sementara
				WHERE id_user = ?;";
		$this->db->query($sql, array($tanggal, $id_user));
	}

	function hapus_sementara($id_user) {
		$sql = "DELETE FROM sementara
				WHERE id_user = ?";
		$this->db->query($sql, array($id_user));
	}

	function tambah_sementara($id_user, $halaman) {
		$sql = "INSERT INTO sementara
				SET id_user = ?,
				halaman = ?";
		$this->db->query($sql, array($id_user, $halaman));
	}

	function update_sementara($id_user, $halaman) {
		$sql = "UPDATE sementara
				SET halaman = ?
				WHERE id_user = ?";
		$this->db->query($sql, array($halaman, $id_user));
	}

	function cek_jumlah_sementara($id_user) {
		$sql = "SELECT count(*) total
				FROM sementara
				WHERE id_user = ?";
		$query = $this->db->query($sql, array($id_user));
		$row = $query->row();

		return $row->total;
	}

	function lihat_halaman_saat_ini($id_user) {
		$sql = "SELECT *
				FROM fix
				WHERE id_user = ?
				ORDER BY id DESC
				LIMIT 1";
		$query = $this->db->query($sql, array($id_user));
		$row = $query->row();

		return $row;
	}

	function lihat_halaman_sementara($id_user) {
		$sql = "SELECT *
				FROM sementara
				WHERE id_user = ?
				ORDER BY id DESC
				LIMIT 1";
		$query = $this->db->query($sql, array($id_user));
		$row = $query->row();

		return $row;
	}

	function keluar($id_user) {
		$sql = "DELETE FROM user
				WHERE id = ?";
		$this->db->query($sql, array($id_user));
		return 1;
	}

	function delete_fix($id_user) {
		$sql = "DELETE FROM fix
				WHERE id_user = ?";
		$this->db->query($sql, array($id_user));
		return 1;
	}

	function delete_sementara($id_user) {
		$sql = "DELETE FROM sementara
				WHERE id_user = ?";
		$this->db->query($sql, array($id_user));
		return 1;
	}

	function daftar($userid, $waktu) {
		$sql = "INSERT INTO user
				SET id_line = ?,
				waktu_daftar = ?";
		$this->db->query($sql, array($userid, $waktu));
		return $this->db->insert_id();
	}

	function cek_daftar($userid) {
		$sql = "SELECT id
				FROM user
				WHERE id_line = ?";
		return $this->db->query($sql, array($userid))->row()->id;
	}
}
?>