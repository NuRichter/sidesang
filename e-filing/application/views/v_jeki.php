<!DOCTYPE html>
<html lang="id">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Sidesang | Untuk Jeki</title>
	<link rel="icon" type="image/png" href="<?= base_url('assets/brand/favicon-32x32.png') ?>">
	<meta name="theme-color" content="#0B3D2E">
	<style>
		* {
			box-sizing: border-box;
			margin: 0;
			padding: 0;
		}

		body {
			min-height: 100vh;
			display: flex;
			align-items: center;
			justify-content: center;
			font-family: 'Segoe UI', Tahoma, Arial, sans-serif;
			background: radial-gradient(1200px 600px at 50% -10%, #16A374 0%, #0B6E4F 42%, #0B3D2E 100%);
			color: #fff;
			overflow: hidden;
			position: relative;
			padding: 24px;
		}

		.confetti {
			position: fixed;
			top: -12px;
			width: 10px;
			height: 16px;
			border-radius: 2px;
			opacity: .9;
			animation: fall linear infinite;
			z-index: 1;
		}

		@keyframes fall {
			0% {
				transform: translateY(-20px) rotate(0);
			}

			100% {
				transform: translateY(104vh) rotate(720deg);
			}
		}

		.card {
			position: relative;
			z-index: 2;
			max-width: 620px;
			width: 100%;
			text-align: center;
			background: rgba(255, 255, 255, .06);
			border: 1px solid rgba(255, 255, 255, .18);
			border-radius: 28px;
			padding: 48px 36px 40px;
			backdrop-filter: blur(6px);
			box-shadow: 0 24px 60px rgba(0, 0, 0, .28);
			animation: pop .7s cubic-bezier(.2, .8, .2, 1) both;
		}

		@keyframes pop {
			from {
				opacity: 0;
				transform: translateY(24px) scale(.96);
			}

			to {
				opacity: 1;
				transform: none;
			}
		}

		.cap {
			font-size: 64px;
			line-height: 1;
			animation: wiggle 2.4s ease-in-out infinite;
			display: inline-block;
		}

		@keyframes wiggle {

			0%,
			100% {
				transform: rotate(-8deg);
			}

			50% {
				transform: rotate(8deg);
			}
		}

		.hi {
			margin-top: 14px;
			font-size: 18px;
			letter-spacing: 2px;
			text-transform: uppercase;
			color: #D7F2E6;
			font-weight: 600;
		}

		h1 {
			margin: 10px 0 6px;
			font-size: clamp(30px, 6vw, 46px);
			font-weight: 800;
			line-height: 1.15;
		}

		h1 .gold {
			color: #FFD24A;
		}

		p.sub {
			font-size: 17px;
			color: rgba(255, 255, 255, .9);
			line-height: 1.6;
			margin-top: 10px;
		}

		.credit {
			margin-top: 26px;
			font-size: 14px;
			color: rgba(215, 242, 230, .85);
		}

		.logo {
			margin-top: 22px;
		}

		.logo img {
			height: 44px;
			opacity: .95;
		}

		.out {
			display: inline-block;
			margin-top: 26px;
			color: rgba(255, 255, 255, .85);
			text-decoration: none;
			font-size: 14px;
			border: 1px solid rgba(255, 255, 255, .35);
			padding: 9px 20px;
			border-radius: 999px;
			transition: .2s;
		}

		.out:hover {
			background: rgba(255, 255, 255, .14);
			color: #fff;
		}
	</style>
</head>

<body>
	<div class="card">
		<span class="cap">🎓</span>
		<div class="hi">Halo<?= $nama ? ', ' . htmlspecialchars($nama, ENT_QUOTES) : '' ?> 👋</div>
		<h1>Semoga tahun ini <span class="gold">cepet lulus</span>, Jek!</h1>
		<p class="sub">Sidesang lahir dari idemu. Sekarang udah jalan, online, dan kepake buat Desa Bulukandang.<br>Giliran skripsimu yang kelar. Semangat terus — kamu pasti bisa! 🚀</p>
		<div class="credit">Sidesang &middot; Arsip Desa Bulukandang &mdash; dari ide <b>Jeki</b></div>
		<div class="logo"><img src="<?= base_url('assets/brand/sidesang-logo-white.png') ?>" alt="Sidesang"></div>
		<br>
		<a class="out" href="<?= site_url('logout') ?>">Keluar</a>
	</div>

	<script>
		(function () {
			var colors = ['#FFD24A', '#D7F2E6', '#16A374', '#ffffff', '#F2B705'];
			for (var i = 0; i < 70; i++) {
				var c = document.createElement('div');
				c.className = 'confetti';
				c.style.left = Math.random() * 100 + 'vw';
				c.style.background = colors[i % colors.length];
				c.style.animationDuration = (3 + Math.random() * 3) + 's';
				c.style.animationDelay = (-Math.random() * 5) + 's';
				c.style.transform = 'scale(' + (0.6 + Math.random()) + ')';
				document.body.appendChild(c);
			}
		})();
	</script>
</body>

</html>
