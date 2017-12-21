<?php
class M_welcome extends CI_Model{	
	function __construct(){
		parent::__construct();		
	}

	function list_admin() {
		$sql = "SELECT *
				FROM admin";
		$query = $this->db->query($sql, array());
		$row = $query->result();

		return $row;
	}

	function list_user() {
		$sql = "SELECT id_user_line
				FROM fix
				GROUP BY id_user_line";
		$query = $this->db->query($sql, array());
		$row = $query->result();

		return $row;
	}

	function lihat_status($userid) {
		$sql = "SELECT *
				FROM fix
				WHERE id_user_line = ?
				ORDER BY id DESC
				LIMIT 1";
		$query = $this->db->query($sql, array($userid));
		$row = $query->row();

		return $row;
	}

	function lihat_halaman_saat_ini($userid) {
		$sql = "SELECT *
				FROM fix
				WHERE id_user_line = ?
				ORDER BY id DESC
				LIMIT 1";
		$query = $this->db->query($sql, array($userid));
		$row = $query->row();

		return $row;
	}

	function cek_jumlah_sementara($userid) {
		$sql = "SELECT count(*) total
				FROM sementara
				WHERE id_user_line = ?";
		$query = $this->db->query($sql, array($userid));
		$row = $query->row();

		return $row->total;
	}

	function tambah_fix($userid) {
		$sql = "INSERT INTO fix (id_user_line, halaman, waktu) 
				SELECT id_user_line, halaman, waktu
				FROM sementara
				WHERE id_user_line = ?";
		$this->db->query($sql, array($userid));
	}

	function hapus_sementara($userid) {
		$sql = "DELETE FROM sementara
				WHERE id_user_line = ?";
		$this->db->query($sql, array($userid));
	}

	function tambah_sementara($userid, $halaman) {
		$sql = "INSERT INTO sementara
				SET id_user_line = ?,
				halaman = ?";
		$this->db->query($sql, array($userid, $halaman));
	}

	function update_sementara($userid, $halaman) {
		$sql = "UPDATE sementara
				SET halaman = ?,
				waktu = now()
				WHERE id_user_line = ?";
		$this->db->query($sql, array($halaman, $userid));
	}

}
?>