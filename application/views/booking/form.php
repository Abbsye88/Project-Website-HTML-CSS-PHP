<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$old = isset($old) ? $old : array();
function old_val($old, $field) { return isset($old[$field]) ? htmlspecialchars($old[$field]) : ''; }
?>
<h1>Ajukan Peminjaman Ruang</h1>
<p style="max-width:640px">Isi form di bawah dengan lengkap. Pengajuan akan masuk ke admin PKM untuk diperiksa (ACC). Kamu akan menerima notifikasi lewat email kampus, dan bisa cek status kapan saja lewat menu <a href="<?php echo base_url('cek-status'); ?>">Cek Status</a>.</p>

<?php if ( ! empty($errors)): ?>
	<div class="alert alert-error">
		<strong>Pengajuan belum bisa diproses:</strong>
		<ul><?php foreach ($errors as $e): ?><li><?php echo htmlspecialchars($e); ?></li><?php endforeach; ?></ul>
	</div>
<?php endif; ?>
<?php if (validation_errors()): ?>
	<div class="alert alert-error"><?php echo validation_errors(); ?></div>
<?php endif; ?>

<form action="<?php echo base_url('ajukan/kirim'); ?>" method="post" id="bookingForm" class="card" style="max-width:760px">

	<div class="form-group">
		<label class="form-label">Pilih Ruangan</label>
		<div class="room-picker">
			<?php foreach ($rooms as $i => $room): ?>
				<label class="room-pick-card <?php echo (old_val($old,'room_id') == $room->id) ? 'selected' : ''; ?>">
					<input type="radio" name="room_id" value="<?php echo $room->id; ?>" <?php echo (old_val($old,'room_id') == $room->id || (empty($old) && $i === 0)) ? 'checked' : ''; ?>>
					<strong><?php echo htmlspecialchars($room->nama); ?></strong>
					<span>Kapasitas &plusmn; <?php echo (int) $room->kapasitas; ?> orang</span>
				</label>
			<?php endforeach; ?>
		</div>
	</div>

	<div class="form-grid">
		<div class="form-group">
			<label class="form-label" for="nama_pemohon">Nama Lengkap Pemohon</label>
			<input type="text" class="form-control" id="nama_pemohon" name="nama_pemohon" value="<?php echo old_val($old,'nama_pemohon'); ?>" placeholder="cth. Ahmad Fauzan" required>
		</div>
		<div class="form-group">
			<label class="form-label" for="nim">NIM</label>
			<input type="text" class="form-control" id="nim" name="nim" value="<?php echo old_val($old,'nim'); ?>" placeholder="cth. H1A021001" required>
		</div>
	</div>

	<div class="form-grid">
		<div class="form-group">
			<label class="form-label" for="email">Email Mahasiswa</label>
			<input type="email" class="form-control" id="email" name="email" value="<?php echo old_val($old,'email'); ?>" placeholder="nama@mhs.unsoed.ac.id" required>
			<p class="form-hint">Wajib domain @mhs.unsoed.ac.id — notifikasi ACC/tolak dikirim ke sini.</p>
		</div>
		<div class="form-group">
			<label class="form-label" for="no_hp">No. HP / WhatsApp</label>
			<input type="text" class="form-control" id="no_hp" name="no_hp" value="<?php echo old_val($old,'no_hp'); ?>" placeholder="08xxxxxxxxxx" required>
		</div>
	</div>

	<div class="form-group">
		<label class="form-label" for="organisasi">Organisasi / UKM (opsional)</label>
		<input type="text" class="form-control" id="organisasi" name="organisasi" value="<?php echo old_val($old,'organisasi'); ?>" placeholder="cth. BEM Fakultas Teknik">
	</div>

	<div class="form-group">
		<label class="form-label" for="nama_acara">Nama Acara / Kegiatan</label>
		<input type="text" class="form-control" id="nama_acara" name="nama_acara" value="<?php echo old_val($old,'nama_acara'); ?>" placeholder="cth. Rapat Koordinasi Pengurus" required>
	</div>

	<div class="form-group">
		<label class="form-label" for="deskripsi">Deskripsi Kegiatan</label>
		<textarea class="form-control" id="deskripsi" name="deskripsi" placeholder="Jelaskan secara singkat tujuan & rangkaian kegiatan..." required><?php echo old_val($old,'deskripsi'); ?></textarea>
	</div>

	<div class="form-grid">
		<div class="form-group">
			<label class="form-label" for="jumlah_peserta">Jumlah Peserta</label>
			<input type="number" class="form-control" id="jumlah_peserta" name="jumlah_peserta" min="1" value="<?php echo old_val($old,'jumlah_peserta'); ?>" placeholder="cth. 30" required>
		</div>
		<div class="form-group">
			<label class="form-label" for="tanggal">Tanggal Kegiatan</label>
			<input type="date" class="form-control" id="tanggal" name="tanggal" min="<?php echo date('Y-m-d'); ?>" value="<?php echo old_val($old,'tanggal'); ?>" required>
		</div>
	</div>

	<div class="form-grid">
		<div class="form-group">
			<label class="form-label" for="jam_mulai">Jam Mulai</label>
			<input type="time" class="form-control" id="jam_mulai" name="jam_mulai" value="<?php echo old_val($old,'jam_mulai'); ?>" required>
		</div>
		<div class="form-group">
			<label class="form-label" for="jam_selesai">Jam Selesai</label>
			<input type="time" class="form-control" id="jam_selesai" name="jam_selesai" value="<?php echo old_val($old,'jam_selesai'); ?>" required>
		</div>
	</div>

	<button type="submit" class="btn btn-primary btn-block">Kirim Pengajuan</button>
</form>
