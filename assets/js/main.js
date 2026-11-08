(function () {
	'use strict';

	/* -------------------------------------------------
	   DARK / LIGHT MODE
	   Disimpan di localStorage (real browser, bukan artifact)
	   ------------------------------------------------- */
	var root = document.documentElement;
	var toggleBtn = document.getElementById('themeToggle');

	function applyTheme(theme) {
		root.setAttribute('data-theme', theme);
		localStorage.setItem('pkm_theme', theme);
		if (toggleBtn) {
			toggleBtn.innerHTML = theme === 'dark' ? ICONS.sun : ICONS.moon;
			toggleBtn.setAttribute('aria-label', theme === 'dark' ? 'Ganti ke mode terang' : 'Ganti ke mode gelap');
		}
		// refresh warna FullCalendar bila ada di halaman ini
		if (window.__pkmCalendar) {
			window.__pkmCalendar.render();
		}
	}

	var ICONS = {
		sun: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.2 4.2l1.4 1.4M18.4 18.4l1.4 1.4M2 12h2M20 12h2M4.2 19.8l1.4-1.4M18.4 5.6l1.4-1.4"/></svg>',
		moon: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>'
	};

	if (toggleBtn) {
		toggleBtn.addEventListener('click', function () {
			var current = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
			applyTheme(current);
		});
		// set ikon awal sesuai tema yang sudah aktif (di-set inline di header sebelum render)
		var initial = root.getAttribute('data-theme') || 'light';
		toggleBtn.innerHTML = initial === 'dark' ? ICONS.sun : ICONS.moon;
	}

	/* -------------------------------------------------
	   MOBILE NAV TOGGLE
	   ------------------------------------------------- */
	var navToggle = document.getElementById('navToggle');
	var navLinks = document.getElementById('navLinks');
	if (navToggle && navLinks) {
		navToggle.addEventListener('click', function () {
			navLinks.classList.toggle('open');
		});
	}

	/* -------------------------------------------------
	   ROOM PICKER (form pengajuan) — highlight kartu terpilih
	   ------------------------------------------------- */
	var roomCards = document.querySelectorAll('.room-pick-card');
	roomCards.forEach(function (card) {
		var input = card.querySelector('input[type="radio"]');
		if (!input) return;
		if (input.checked) card.classList.add('selected');
		card.addEventListener('click', function () {
			roomCards.forEach(function (c) { c.classList.remove('selected'); });
			input.checked = true;
			card.classList.add('selected');
		});
	});

	/* -------------------------------------------------
	   VALIDASI JAM SELESAI > JAM MULAI (di sisi klien)
	   ------------------------------------------------- */
	var jamMulai = document.getElementById('jam_mulai');
	var jamSelesai = document.getElementById('jam_selesai');
	var bookingForm = document.getElementById('bookingForm');
	if (bookingForm && jamMulai && jamSelesai) {
		bookingForm.addEventListener('submit', function (e) {
			if (jamSelesai.value && jamMulai.value && jamSelesai.value <= jamMulai.value) {
				e.preventDefault();
				alert('Jam selesai harus lebih besar dari jam mulai.');
			}
		});
	}

	/* -------------------------------------------------
	   FULLCALENDAR INIT (halaman Kalender)
	   ------------------------------------------------- */
	var calendarEl = document.getElementById('calendar');
	if (calendarEl && window.FullCalendar) {
		var calendar = new FullCalendar.Calendar(calendarEl, {
			initialView: window.innerWidth < 700 ? 'listMonth' : 'dayGridMonth',
			locale: 'id',
			height: 'auto',
			headerToolbar: {
				left: 'prev,next today',
				center: 'title',
				right: window.innerWidth < 700 ? 'listMonth' : 'dayGridMonth,timeGridWeek,listMonth'
			},
			buttonText: { today: 'Hari ini', month: 'Bulan', week: 'Minggu', list: 'Daftar' },
			events: CALENDAR_EVENTS_URL,
			eventDidMount: function (info) {
				info.el.title = info.event.title + (info.event.classNames.indexOf('event-pending') > -1 ? ' (menunggu ACC)' : ' (disetujui)');
			}
		});
		calendar.render();
		window.__pkmCalendar = calendar;
	}

	/* -------------------------------------------------
	   AUTO-DISMISS ALERT setelah beberapa detik
	   ------------------------------------------------- */
	document.querySelectorAll('.alert[data-autohide]').forEach(function (el) {
		setTimeout(function () {
			el.style.transition = 'opacity .4s ease';
			el.style.opacity = '0';
			setTimeout(function () { el.remove(); }, 400);
		}, 4500);
	});
})();
