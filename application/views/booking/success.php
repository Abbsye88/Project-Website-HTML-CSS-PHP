<div class="card" style="max-width:640px;text-align:center;padding:2.4rem 1.6rem">
	<svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="var(--joglo)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto 1rem"><circle cx="12" cy="12" r="10"/><path d="M8 12.5l3 3 5-6"/></svg>

	<h1>Pengajuan Terkirim!</h1>
	<p>Pengajuan kamu sudah masuk ke admin PKM dan menunggu ACC. Simpan kode di bawah ini untuk mengecek status sewaktu-waktu.</p>

	<div class="code-chip" style="font-size:1.1rem;padding:.6rem 1.2rem;margin:1rem 0;display:inline-block"><?php echo htmlspecialchars($booking->booking_code); ?></div>

	<div class="detail-grid" style="text-align:left;margin-top:1.4rem">
		<div><dt>Ruangan</dt><dd><?php echo htmlspecialchars($booking->nama_ruang); ?></dd></div>
		<div><dt>Status</dt><dd><span class="<?php echo status_badge_class($booking->status); ?>"><?php echo status_label($booking->status); ?></span></dd></div>
		<div><dt>Acara</dt><dd><?php echo htmlspecialchars($booking->nama_acara); ?></dd></div>
		<div><dt>Tanggal</dt><dd><?php echo format_tanggal_indo($booking->tanggal); ?></dd></div>
		<div><dt>Jam</dt><dd><?php echo format_jam($booking->jam_mulai); ?> - <?php echo format_jam($booking->jam_selesai); ?> WIB</dd></div>
		<div><dt>Email Notifikasi</dt><dd><?php echo htmlspecialchars($booking->email); ?></dd></div>
	</div>

	<div style="margin-top:1.6rem;display:flex;gap:.7rem;justify-content:center;flex-wrap:wrap">
		<a href="<?php echo base_url('cek-status'); ?>" class="btn btn-outline">Cek Status Lain</a>
		<a href="<?php echo base_url(); ?>" class="btn btn-primary">Lihat Kalender</a>
	</div>
</div>
