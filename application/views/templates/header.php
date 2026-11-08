<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$is_admin = (bool) $this->session->userdata('admin_id');
$current_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo isset($title) ? htmlspecialchars($title) . ' — PKM Unsoed' : 'Peminjaman Ruang PKM Unsoed'; ?></title>

<script>
	// Set tema SEBELUM render agar tidak ada flash warna salah
	(function () {
		var saved = localStorage.getItem('pkm_theme');
		var theme = saved || 'light';
		document.documentElement.setAttribute('data-theme', theme);
	})();
</script>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

<link rel="stylesheet" href="<?php echo base_url('assets/css/style.css'); ?>">
</head>
<body>

<nav class="navbar">
	<div class="navbar-inner">
		<a href="<?php echo base_url(); ?>" class="brand">
			<svg viewBox="0 0 32 32" width="28" height="28" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M2 22 L16 6 L30 22" stroke="currentColor" stroke-width="2.2" stroke-linejoin="round"/>
				<path d="M6 22 L16 12 L26 22" stroke="currentColor" stroke-width="2.2" stroke-linejoin="round"/>
				<rect x="9" y="22" width="14" height="6" rx="1" fill="currentColor" opacity=".15" stroke="currentColor" stroke-width="1.4"/>
			</svg>
			<span>PKM Unsoed <small>Peminjaman Ruang</small></span>
		</a>

		<button class="nav-toggle-btn" id="navToggle" aria-label="Buka menu">
			<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M3 6h18M3 12h18M3 18h18"/></svg>
		</button>

		<div class="nav-links" id="navLinks">
			<a href="<?php echo base_url(); ?>" class="nav-link <?php echo (strpos($current_uri, '/admin') === false && $current_uri === '/' ) ? 'active' : ''; ?>">Kalender</a>
			<a href="<?php echo base_url('ajukan'); ?>" class="nav-link <?php echo (strpos($current_uri, 'ajukan') !== false) ? 'active' : ''; ?>">Ajukan Peminjaman</a>
			<a href="<?php echo base_url('cek-status'); ?>" class="nav-link <?php echo (strpos($current_uri, 'cek-status') !== false) ? 'active' : ''; ?>">Cek Status</a>
			<?php if ($is_admin): ?>
				<a href="<?php echo base_url('admin/dashboard'); ?>" class="nav-link <?php echo (strpos($current_uri, 'admin') !== false) ? 'active' : ''; ?>">Dashboard Admin</a>
				<a href="<?php echo base_url('admin/logout'); ?>" class="nav-link">Logout</a>
			<?php else: ?>
				<a href="<?php echo base_url('admin'); ?>" class="nav-link <?php echo (strpos($current_uri, 'admin') !== false) ? 'active' : ''; ?>">Admin</a>
			<?php endif; ?>

			<button class="theme-toggle" id="themeToggle" aria-label="Ganti tema"></button>
		</div>
	</div>
</nav>

<main class="page">
	<div class="container">
		<?php if ($this->session->flashdata('success')): ?>
			<div class="alert alert-success" data-autohide><?php echo htmlspecialchars($this->session->flashdata('success')); ?></div>
		<?php endif; ?>
		<?php if ($this->session->flashdata('error')): ?>
			<div class="alert alert-error" data-autohide><?php echo htmlspecialchars($this->session->flashdata('error')); ?></div>
		<?php endif; ?>
