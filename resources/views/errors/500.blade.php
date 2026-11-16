<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gangguan Layanan | Web Hotel</title>
  <style>
    body {
      margin: 0;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #08101E;
      color: #ffffff;
      font-family: 'Poppins', system-ui, sans-serif;
      padding: 20px;
    }
    .box {
      max-width: 480px;
      width: 100%;
      background: #0C1526;
      border: 1px solid rgba(201, 150, 42, 0.35);
      border-radius: 16px;
      padding: 36px 28px;
      text-align: center;
    }
    .code {
      font-size: 0.8rem;
      letter-spacing: 2px;
      color: #E8B84B;
      font-weight: 700;
      margin-bottom: 10px;
    }
    h1 {
      font-size: 1.5rem;
      margin: 0 0 10px;
    }
    p {
      color: #8FA4C8;
      font-size: 0.92rem;
      line-height: 1.6;
      margin: 0 0 24px;
    }
    a {
      display: inline-block;
      background: #C9962A;
      color: #08101E;
      text-decoration: none;
      font-weight: 700;
      padding: 10px 22px;
      border-radius: 8px;
    }
  </style>
</head>
<body>
  <div class="box">
    <div class="code">SERVICE RECOVERY</div>
    <h1>Terjadi Gangguan Sementara</h1>
    <p>Sistem sedang memproses pemulihan layanan. Silakan muat ulang beberapa saat lagi atau kembali ke beranda.</p>
    <a href="{{ url('/') }}">Kembali ke Beranda</a>
  </div>
</body>
</html>
