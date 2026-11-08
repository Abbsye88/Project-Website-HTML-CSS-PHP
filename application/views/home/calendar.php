<section class="hero">
	<div class="hero-inner">
		<span class="hero-eyebrow">Pusat Kegiatan Mahasiswa &middot; Unsoed</span>
		<h1>Kalender Peminjaman Ruang</h1>
		<p style="max-width:560px">Lihat jadwal yang sudah terisi di keempat ruang PKM, lalu ajukan peminjaman untuk tanggal yang masih kosong.</p>
		<a href="<?php echo base_url('ajukan'); ?>" class="btn btn-primary">Ajukan Peminjaman Sekarang</a>
	</div>

	<svg class="roofline" viewBox="0 0 1280 110" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
		<polygon points="0,110 0,70 70,25 140,70 140,110" fill="currentColor"/>
		<polygon points="140,110 140,75 230,15 320,75 320,110" fill="currentColor"/>
		<polygon points="320,110 320,68 400,35 480,68 480,110" fill="currentColor"/>
		<polygon points="480,110 480,80 580,10 680,80 680,110" fill="currentColor"/>
		<polygon points="680,110 680,72 750,30 820,72 820,110" fill="currentColor"/>
		<polygon points="820,110 820,78 920,18 1020,78 1020,110" fill="currentColor"/>
		<polygon points="1020,110 1020,70 1100,32 1180,70 1180,110" fill="currentColor"/>
		<polygon points="1180,110 1180,82 1230,55 1280,82 1280,110" fill="currentColor"/>
	</svg>
</section>

<div style="padding-top:1.6rem">
	<div class="calendar-legend">
		<span><span class="legend-dot" style="background:var(--joglo)"></span> Disetujui (jadwal terisi)</span>
		<span><span class="legend-dot" style="background:var(--gold)"></span> Menunggu ACC admin</span>
	</div>

	<div id="calendar"></div>

	<h2 style="margin-top:2.4rem">Ruang yang Tersedia</h2>
	<div class="room-grid">
		<?php foreach ($rooms as $room): ?>
			<div class="card room-card" style="border-left-color: <?php echo htmlspecialchars($room->warna); ?>">
				<h3 class="card-title"><?php echo htmlspecialchars($room->nama); ?></h3>
				<p style="margin-bottom:.4rem;font-size:.88rem">Kapasitas &plusmn; <?php echo (int) $room->kapasitas; ?> orang</p>
				<p style="font-size:.86rem"><?php echo htmlspecialchars($room->deskripsi); ?></p>
			</div>
		<?php endforeach; ?>
	</div>
</div>
