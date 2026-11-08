<h1>Hasil Cek Status</h1>

<?php if (empty($bookings)): ?>
	<div class="empty-state card">
		<svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
		<h3>Tidak ditemukan</h3>
		<p>Tidak ada pengajuan yang cocok dengan "<?php echo htmlspecialchars($query); ?>". Periksa kembali kode/NIM &amp; email yang kamu masukkan.</p>
		<a href="<?php echo base_url('cek-status'); ?>" class="btn btn-outline">Coba Lagi</a>
	</div>
<?php else: ?>
	<?php foreach ($bookings as $b): ?>
		<div class="card">
			<div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:.6rem">
				<div>
					<span class="code-chip"><?php echo htmlspecialchars($b->booking_code); ?></span>
					<h3 class="card-title" style="margin-top:.5rem"><?php echo htmlspecialchars($b->nama_acara); ?></h3>
				</div>
				<span class="<?php echo status_badge_class($b->status); ?>"><?php echo status_label($b->status); ?></span>
			</div>

			<div class="detail-grid">
				<div><dt>Ruangan</dt><dd><?php echo htmlspecialchars($b->nama_ruang); ?></dd></div>
				<div><dt>Tanggal</dt><dd><?php echo format_tanggal_indo($b->tanggal); ?></dd></div>
				<div><dt>Jam</dt><dd><?php echo format_jam($b->jam_mulai); ?> - <?php echo format_jam($b->jam_selesai); ?> WIB</dd></div>
				<div><dt>Jumlah Peserta</dt><dd><?php echo (int) $b->jumlah_peserta; ?> orang</dd></div>
			</div>

			<?php if ($b->status === 'ditolak' && $b->alasan_tolak): ?>
				<div class="alert alert-error" style="margin-top:.5rem"><strong>Alasan ditolak:</strong> <?php echo htmlspecialchars($b->alasan_tolak); ?></div>
			<?php endif; ?>
			<?php if ($b->status === 'pending'): ?>
				<div class="alert" style="background:rgba(201,138,43,.1);color:var(--gold);border:1px solid rgba(201,138,43,.25);margin-top:.5rem">Masih menunggu pemeriksaan admin PKM.</div>
			<?php endif; ?>
		</div>
	<?php endforeach; ?>
<?php endif; ?>
