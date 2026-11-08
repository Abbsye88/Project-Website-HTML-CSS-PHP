<h1>Cek Status Pengajuan</h1>
<p style="max-width:600px">Tidak perlu login — cukup masukkan kode pengajuan, atau NIM &amp; email yang kamu pakai saat mengajukan.</p>

<div class="card" style="max-width:560px">
	<div class="filter-tabs" id="modeTabs">
		<button type="button" class="filter-tab active" data-mode="code">Pakai Kode Pengajuan</button>
		<button type="button" class="filter-tab" data-mode="nim">Pakai NIM &amp; Email</button>
	</div>

	<form action="<?php echo base_url('cek-status/hasil'); ?>" method="post" id="formCode">
		<input type="hidden" name="mode" value="code">
		<div class="form-group">
			<label class="form-label" for="booking_code">Kode Pengajuan</label>
			<input type="text" class="form-control" id="booking_code" name="booking_code" placeholder="cth. PKM-20260618-7F3A" required>
		</div>
		<button type="submit" class="btn btn-primary btn-block">Cek Status</button>
	</form>

	<form action="<?php echo base_url('cek-status/hasil'); ?>" method="post" id="formNim" style="display:none">
		<input type="hidden" name="mode" value="nim">
		<div class="form-group">
			<label class="form-label" for="nim2">NIM</label>
			<input type="text" class="form-control" id="nim2" name="nim" placeholder="cth. H1A021001" required>
		</div>
		<div class="form-group">
			<label class="form-label" for="email2">Email</label>
			<input type="email" class="form-control" id="email2" name="email" placeholder="nama@mhs.unsoed.ac.id" required>
		</div>
		<button type="submit" class="btn btn-primary btn-block">Cek Status</button>
	</form>
</div>

<script>
	document.querySelectorAll('#modeTabs .filter-tab').forEach(function (tab) {
		tab.addEventListener('click', function () {
			document.querySelectorAll('#modeTabs .filter-tab').forEach(function (t) { t.classList.remove('active'); });
			tab.classList.add('active');
			var mode = tab.getAttribute('data-mode');
			document.getElementById('formCode').style.display = mode === 'code' ? 'block' : 'none';
			document.getElementById('formNim').style.display = mode === 'nim' ? 'block' : 'none';
		});
	});
</script>
