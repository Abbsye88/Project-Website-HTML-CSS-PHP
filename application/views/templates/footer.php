	</div>
</main>

<footer class="site-footer">
	<div class="container">
		<p>&copy; <?php echo date('Y'); ?> Pusat Kegiatan Mahasiswa — Universitas Jenderal Soedirman</p>
		<p>Sistem Peminjaman Ruang &middot; Tugas Besar</p>
	</div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/locales-all.global.min.js"></script>
<script>
	var CALENDAR_EVENTS_URL = '<?php echo base_url('kalender/events'); ?>';
</script>
<script src="<?php echo base_url('assets/js/main.js'); ?>"></script>
</body>
</html>
