<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-fit, initial-scale=1.0">
  <title>Login Admin — Grand Lumina Hotel</title>
  
  {{-- Fonts & Icons --}}
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,400&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <style>
    :root {
      --bg: #FAF8F5;
      --navy: #0F172A;
      --navy-dark: #0B0F19;
      --gold: #C5A059;
      --gold-light: #D4AF37;
      --gold-dark: #9E7D3B;
      --text: #1C1917;
      --muted: #78716C;
      --border: #EBE5DB;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      font-family: 'Poppins', sans-serif;
      background: var(--bg);
      color: var(--text);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
      position: relative;
      overflow: hidden;
    }

    /* Background decoration */
    body::before {
      content: "";
      position: absolute;
      top: -15%;
      right: -10%;
      width: 500px;
      height: 500px;
      background: radial-gradient(circle, rgba(197, 160, 89, 0.12) 0%, rgba(255,255,255,0) 70%);
      z-index: 0;
    }

    body::after {
      content: "";
      position: absolute;
      bottom: -15%;
      left: -10%;
      width: 600px;
      height: 600px;
      background: radial-gradient(circle, rgba(15, 23, 42, 0.08) 0%, rgba(255,255,255,0) 70%);
      z-index: 0;
    }

    .login-wrapper {
      width: 100%;
      max-width: 460px;
      position: relative;
      z-index: 10;
    }

    .brand-header {
      text-align: center;
      margin-bottom: 28px;
    }

    .brand-header h1 {
      font-family: 'Playfair Display', serif;
      font-size: 2.3rem;
      color: var(--navy);
      margin-bottom: 4px;
      letter-spacing: 0.5px;
    }

    .brand-header span {
      font-size: 0.85rem;
      color: var(--gold-dark);
      text-transform: uppercase;
      letter-spacing: 3px;
      font-weight: 700;
    }

    .login-card {
      background: #FFFFFF;
      border-radius: 28px;
      padding: 40px;
      box-shadow: 0 25px 70px rgba(15, 23, 42, 0.1), 0 0 0 1px rgba(197, 160, 89, 0.2);
      position: relative;
      overflow: hidden;
    }

    .login-card::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 6px;
      background: linear-gradient(90deg, #C5A059, #D4AF37, #9E7D3B, #C5A059);
    }

    .login-card h2 {
      font-size: 1.35rem;
      color: var(--navy);
      font-weight: 700;
      margin-bottom: 6px;
    }

    .login-card p {
      font-size: 0.9rem;
      color: var(--muted);
      margin-bottom: 26px;
    }

    .form-group {
      margin-bottom: 22px;
    }

    .form-group label {
      display: flex;
      align-items: center;
      gap: 6px;
      font-weight: 600;
      font-size: 0.92rem;
      color: var(--navy);
      margin-bottom: 8px;
    }

    .form-group label::before {
      content: "✦";
      color: var(--gold);
      font-size: 0.8rem;
    }

    .input-wrapper {
      position: relative;
    }

    .input-wrapper i {
      position: absolute;
      left: 16px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--gold);
      font-size: 1.1rem;
    }

    .form-control {
      width: 100%;
      padding: 14px 18px 14px 46px;
      border-radius: 14px;
      border: 1.5px solid #D6D2C4;
      background: #FCFBF9;
      font-size: 0.95rem;
      font-family: inherit;
      color: var(--text);
      transition: all 0.3s ease;
      font-weight: 500;
    }

    .form-control:focus {
      outline: none;
      border-color: var(--gold);
      box-shadow: 0 0 0 4px rgba(197, 160, 89, 0.15);
      background: #FFFFFF;
    }

    .btn-submit {
      width: 100%;
      padding: 15px 24px;
      border: none;
      border-radius: 14px;
      background: linear-gradient(135deg, #C5A059 0%, #A88434 100%);
      color: #FFFFFF;
      font-weight: 700;
      font-size: 1.02rem;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 8px 22px rgba(197, 160, 89, 0.35);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      text-transform: uppercase;
      letter-spacing: 0.8px;
      margin-top: 10px;
    }

    .btn-submit:hover {
      transform: translateY(-2px);
      box-shadow: 0 12px 28px rgba(197, 160, 89, 0.45);
      background: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%);
    }

    .alert {
      padding: 14px 18px;
      border-radius: 12px;
      margin-bottom: 22px;
      font-size: 0.88rem;
      font-weight: 500;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .alert-danger {
      background: #fee2e2;
      color: #b91c1c;
      border: 1px solid #fca5a5;
    }

    .alert-success {
      background: #dcfce7;
      color: #15803d;
      border: 1px solid #86efac;
    }

    .login-info {
      background: #F8F6F0;
      border: 1px dashed var(--gold);
      border-radius: 14px;
      padding: 16px;
      margin-top: 26px;
      font-size: 0.84rem;
      color: #4A463F;
      line-height: 1.6;
    }

    .login-info strong {
      color: var(--navy);
      font-weight: 700;
    }

    .login-info code {
      background: #EBE5DB;
      padding: 2px 6px;
      border-radius: 6px;
      font-family: monospace;
      color: #9E7D3B;
      font-weight: 700;
    }

    .footer-link {
      text-align: center;
      margin-top: 24px;
    }

    .footer-link a {
      color: var(--muted);
      text-decoration: none;
      font-size: 0.86rem;
      transition: color 0.2s;
    }

    .footer-link a:hover {
      color: var(--navy);
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <div class="login-wrapper">
    
    <div class="brand-header">
      <h1>Grand Lumina</h1>
      <span>Management Console</span>
    </div>

    <div class="login-card">
      <h2>Otorisasi Administrator</h2>
      <p>Silakan masukkan kredensial resmi untuk mengakses sistem</p>

      @if(session('error'))
        <div class="alert alert-danger">
          <i class="bi bi-exclamation-triangle-fill" style="font-size:1.2rem;"></i>
          <span>{{ session('error') }}</span>
        </div>
      @endif

      @if(session('success'))
        <div class="alert alert-success">
          <i class="bi bi-check-circle-fill" style="font-size:1.2rem;"></i>
          <span>{{ session('success') }}</span>
        </div>
      @endif

      <form action="{{ route('admin.login.submit') }}" method="POST">
        @csrf
        
        <div class="form-group">
          <label for="username">Username / Email</label>
          <div class="input-wrapper">
            <i class="bi bi-person-fill"></i>
            <input type="text" id="username" name="username" class="form-control" required placeholder="Masukkan username admin" value="{{ old('username', 'admin') }}">
          </div>
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <div class="input-wrapper">
            <i class="bi bi-lock-fill"></i>
            <input type="password" id="password" name="password" class="form-control" required placeholder="••••••••••••" value="adminLumina123">
          </div>
        </div>

        <button type="submit" class="btn-submit">
          <span>Masuk ke Console</span>
          <i class="bi bi-arrow-right"></i>
        </button>
      </form>

      <div class="login-info">
        💡 <strong>Akses Default Admin:</strong><br>
        Username: <code>admin</code> (atau <code>Lumina</code>)<br>
        Password: <code>adminLumina123</code> (atau <code>admin</code>)
      </div>
    </div>

    <div class="footer-link">
      <a href="{{ route('home') }}"><i class="bi bi-arrow-left"></i> Kembali ke Beranda Resmi Grand Lumina</a>
    </div>

  </div>

</body>
</html>
