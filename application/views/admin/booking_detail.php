<a href="<?php echo base_url('admin/dashboard'); ?>" style="font-size:.88rem">&larr; Kembali ke Dashboard</a>

<div class="card" style="max-width:720px;margin-top:1rem">
	<div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:.6rem">
		<div>
			<span class="code-chip"><?php echo htmlspecialchars($booking->booking_code); ?></span>
			<h1 style="margin-top:.6rem"><?php echo htmlspecialchars($booking->nama_acara); ?></h1>
		</div>
		<span class="<?php echo status_badge_class($booking->status); ?>"><?php echo status_label($booking->status); ?></span>
	</div>

	<div class="detail-grid">
		<div><dt>Ruangan</dt><dd><?php echo htmlspecialchars($booking->nama_ruang); ?></dd></div>
		<div><dt>Tanggal</dt><dd><?php echo format_tanggal_indo($booking->tanggal); ?></dd></div>
		<div><dt>Jam</dt><dd><?php echo format_jam($booking->jam_mulai); ?> - <?php echo format_jam($booking->jam_selesai); ?> WIB</dd></div>
		<div><dt>Jumlah Peserta</dt><dd><?php echo (int) $booking->jumlah_peserta; ?> orang</dd></div>
		<div><dt>Nama Pemohon</dt><dd><?php echo htmlspecialchars($booking->nama_pemohon); ?></dd></div>
		<div><dt>NIM</dt><dd><?php echo htmlspecialchars($booking->nim); ?></dd></div>
		<div><dt>Email</dt><dd><?php echo htmlspecialchars($booking->email); ?></dd></div>
		<div><dt>No. HP</dt><dd><?php echo htmlspecialchars($booking->no_hp); ?></dd></div>
		<div><dt>Organisasi/UKM</dt><dd><?php echo htmlspecialchars($booking->organisasi ?: '—'); ?></dd></div>
		<div><dt>Diajukan pada</dt><dd><?php echo format_tanggal_indo(substr($booking->created_at,0,10)); ?></dd></div>
	</div>

	<div>
		<dt style="font-size:.74rem;text-transform:uppercase;letter-spacing:.04em;color:var(--ink-soft);font-family:var(--font-mono)">Deskripsi Kegiatan</dt>
		<p style="margin-top:.3rem"><?php echo nl2br(htmlspecialchars($booking->deskripsi)); ?></p>
	</div>

	<?php if ($booking->status === 'ditolak' && $booking->alasan_tolak): ?>
		<div class="alert alert-error"><strong>Alasan ditolak:</strong> <?php echo htmlspecialchars($booking->alasan_tolak); ?></div>
	<?php endif; ?>

	<?php if ($booking->status === 'pending'): ?>
		<hr style="border:none;border-top:1px solid var(--border);margin:1.4rem 0">
		<h3>Tindak Lanjut</h3>
		<div style="display:flex;gap:.7rem;flex-wrap:wrap;margin-bottom:1.2rem">
			<form method="post" action="<?php echo base_url('admin/approve/' . $booking->id); ?>" onsubmit="return confirm('Setujui pengajuan ini?');">
				<button type="submit" class="btn btn-success">✓ Setujui (ACC)</button>
			</form>
			<button type="button" class="btn btn-danger" onclick="document.getElementById('rejectBox').style.display='block'">✕ Tolak</button>
		</div>

		<form method="post" action="<?php echo base_url('admin/reject/' . $booking->id); ?>" id="rejectBox" style="display:none">
			<div class="form-group">
				<label class="form-label" for="alasan_tolak">Alasan Penolakan (wajib diisi, akan dikirim ke mahasiswa)</label>
				<textarea class="form-control" id="alasan_tolak" name="alasan_tolak" placeholder="cth. Jadwal berbenturan dengan kegiatan lain yang sudah disetujui." required></textarea>
			</div>
			<button type="submit" class="btn btn-danger">Kirim Penolakan</button>
		</form>
	<?php endif; ?>
</div>
