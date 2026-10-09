<!DOCTYPE html>
<html lang="id">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Sidesang</title>
	<link rel="icon" type="image/png" href="<?= base_url('assets/brand/favicon-32x32.png') ?>">
	<meta name="theme-color" content="#20321f">
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
			background: #20321f;
			padding: 28px;
			font-family: Georgia, 'Times New Roman', serif;
			color: #2c2a24;
		}

		.sheet {
			width: 100%;
			max-width: 540px;
			background: #f6f2e9;
			border-radius: 4px;
			padding: 46px 44px 38px;
			box-shadow: 0 10px 30px rgba(0, 0, 0, .25);
		}

		.sheet p {
			font-size: 17px;
			line-height: 1.85;
			margin-bottom: 18px;
		}

		.hai {
			font-size: 20px;
			margin-bottom: 22px;
		}

		.sign {
			margin-top: 30px;
			font-size: 16px;
			color: #5b574c;
		}

		.foot {
			margin-top: 40px;
			padding-top: 18px;
			border-top: 1px solid #ddd6c6;
			display: flex;
			align-items: center;
			justify-content: space-between;
		}

		.foot span {
			font-family: Arial, sans-serif;
			font-size: 12px;
			letter-spacing: .5px;
			color: #8a8575;
			text-transform: uppercase;
		}

		.foot a {
			font-family: Arial, sans-serif;
			font-size: 13px;
			color: #3a5a3a;
		}

		.foot a:hover {
			color: #20321f;
		}
	</style>
</head>

<body>
	<div class="sheet">
		<p class="hai">Jek,</p>
		<p>Ini Sidesang. Dari idemu yang dulu cuma obrolan, sekarang udah jadi beneran. Udah online, udah dipakai buat ngurus arsip surat di Desa Bulukandang.</p>
		<p>Bagian teknisnya udah aku kelarin. Sisanya giliran kamu. Skripsi itu dikelarin juga ya, jangan ditunda terus. Pelan nggak apa-apa, yang penting jalan.</p>
		<p>Kalau nanti udah kelar, kabarin. Biar aku ikut seneng.</p>
		<p class="sign">Semangat, Jek.</p>
		<div class="foot">
			<span>Sidesang &middot; Arsip Desa Bulukandang</span>
			<a href="<?= site_url('logout') ?>">Keluar</a>
		</div>
	</div>
</body>

</html>
