<h1>Dashboard Admin</h1>
<p>Selamat datang, <?php echo htmlspecialchars($this->session->userdata('admin_nama')); ?>. Periksa dan tindak lanjuti pengajuan peminjaman ruang di bawah ini.</p>

<div class="stat-grid">
	<div class="stat-card"><div class="num" style="color:var(--gold)"><?php echo $count_pending; ?></div><div class="label">Menunggu ACC</div></div>
	<div class="stat-card"><div class="num" style="color:var(--joglo)"><?php echo $count_approved; ?></div><div class="label">Disetujui</div></div>
	<div class="stat-card"><div class="num" style="color:var(--danger)"><?php echo $count_rejected; ?></div><div class="label">Ditolak</div></div>
</div>

<div class="filter-tabs">
	<a href="<?php echo base_url('admin/dashboard'); ?>" class="filter-tab <?php echo empty($active_status) ? 'active' : ''; ?>">Semua</a>
	<a href="<?php echo base_url('admin/dashboard?status=pending'); ?>" class="filter-tab <?php echo $active_status === 'pending' ? 'active' : ''; ?>">Menunggu ACC</a>
	<a href="<?php echo base_url('admin/dashboard?status=disetujui'); ?>" class="filter-tab <?php echo $active_status === 'disetujui' ? 'active' : ''; ?>">Disetujui</a>
	<a href="<?php echo base_url('admin/dashboard?status=ditolak'); ?>" class="filter-tab <?php echo $active_status === 'ditolak' ? 'active' : ''; ?>">Ditolak</a>
</div>

<div class="table-wrap">
	<table class="data-table">
		<thead>
			<tr>
				<th>Kode</th>
				<th>Ruangan</th>
				<th>Acara</th>
				<th>Pemohon</th>
				<th>Tanggal &amp; Jam</th>
				<th>Status</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php if (empty($bookings)): ?>
				<tr><td colspan="7" style="text-align:center;color:var(--ink-soft)">Belum ada pengajuan pada kategori ini.</td></tr>
			<?php endif; ?>
			<?php foreach ($bookings as $b): ?>
				<tr>
					<td><span class="code-chip"><?php echo htmlspecialchars($b->booking_code); ?></span></td>
					<td><?php echo htmlspecialchars($b->nama_ruang); ?></td>
					<td><?php echo htmlspecialchars($b->nama_acara); ?></td>
					<td><?php echo htmlspecialchars($b->nama_pemohon); ?></td>
					<td><?php echo format_tanggal_indo($b->tanggal); ?><br><span style="color:var(--ink-soft);font-size:.82rem"><?php echo format_jam($b->jam_mulai); ?>-<?php echo format_jam($b->jam_selesai); ?> WIB</span></td>
					<td><span class="<?php echo status_badge_class($b->status); ?>"><?php echo status_label($b->status); ?></span></td>
					<td><a href="<?php echo base_url('admin/booking/' . $b->id); ?>" class="btn btn-outline btn-sm">Detail</a></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
