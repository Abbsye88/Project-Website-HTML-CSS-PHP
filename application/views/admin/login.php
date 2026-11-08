<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login Admin — PKM Unsoed</title>
<script>
	(function () {
		var saved = localStorage.getItem('pkm_theme');
		document.documentElement.setAttribute('data-theme', saved || 'light');
	})();
</script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?php echo base_url('assets/css/style.css'); ?>">
<style>
	.login-wrap { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 1.5rem; background: linear-gradient(180deg, var(--surface-soft), var(--bg)); }
	.login-card { width: 100%; max-width: 400px; }
	.login-logo { display:flex; align-items:center; gap:.6rem; justify-content:center; margin-bottom: 1.4rem; color: var(--sogan); }
	.login-logo span { font-family: var(--font-display); font-size:1.2rem; font-weight:700; color: var(--ink); }
</style>
</head>
<body>
<div class="login-wrap">
	<div class="card login-card">
		<div class="login-logo">
			<svg viewBox="0 0 32 32" width="30" height="30" fill="none"><path d="M2 22 L16 6 L30 22" stroke="currentColor" stroke-width="2.2" stroke-linejoin="round"/><path d="M6 22 L16 12 L26 22" stroke="currentColor" stroke-width="2.2" stroke-linejoin="round"/><rect x="9" y="22" width="14" height="6" rx="1" fill="currentColor" opacity=".15" stroke="currentColor" stroke-width="1.4"/></svg>
			<span>Admin PKM Unsoed</span>
		</div>

		<?php if ( ! empty($error)): ?>
			<div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
		<?php endif; ?>

		<form method="post" action="<?php echo base_url('admin/login'); ?>">
			<div class="form-group">
				<label class="form-label" for="email">Email</label>
				<input type="email" class="form-control" id="email" name="email" placeholder="admin@pkm.unsoed.ac.id" required autofocus>
			</div>
			<div class="form-group">
				<label class="form-label" for="password">Password</label>
				<input type="password" class="form-control" id="password" name="password" placeholder="••••••••" required>
			</div>
			<button type="submit" class="btn btn-primary btn-block">Masuk</button>
		</form>

		<p style="text-align:center;margin-top:1.2rem;font-size:.82rem">
			<a href="<?php echo base_url(); ?>">&larr; Kembali ke halaman utama</a>
		</p>
	</div>
</div>
</body>
</html>
