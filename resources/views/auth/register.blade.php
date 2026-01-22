<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Laporan Bulanan</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            background: linear-gradient(135deg, #0f0f23 0%, #1a1a3e 50%, #2d1b4e 100%);
            overflow: hidden;
        }

        .bg-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            overflow: hidden;
        }

        .bg-animation::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.15) 0%, transparent 50%),
                        radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.1) 0%, transparent 50%),
                        radial-gradient(circle at 40% 40%, rgba(120, 200, 255, 0.08) 0%, transparent 40%);
            animation: bgFloat 20s ease-in-out infinite;
        }

        @keyframes bgFloat {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(30px, -30px) rotate(5deg); }
            66% { transform: translate(-20px, 20px) rotate(-5deg); }
        }

        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            opacity: 0.6;
            animation: orbFloat 8s ease-in-out infinite;
        }

        .orb-1 {
            width: 300px;
            height: 300px;
            background: linear-gradient(135deg, #7c3aed, #a855f7);
            top: 10%;
            right: 20%;
            animation-delay: 0s;
        }

        .orb-2 {
            width: 200px;
            height: 200px;
            background: linear-gradient(135deg, #06b6d4, #3b82f6);
            bottom: 20%;
            left: 15%;
            animation-delay: 2s;
        }

        @keyframes orbFloat {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-30px) scale(1.05); }
        }

        .container {
            display: flex;
            width: 100%;
            min-height: 100vh;
            z-index: 1;
            justify-content: center;
            align-items: center;
            padding: 40px;
        }

        .register-card {
            width: 100%;
            max-width: 480px;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 48px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .register-header {
            text-align: center;
            margin-bottom: 36px;
        }

        .register-header .logo {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 16px;
            background: linear-gradient(135deg, #a855f7, #06b6d4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .register-header h2 {
            color: white;
            font-size: 1.75rem;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .register-header p {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .form-input {
            width: 100%;
            padding: 14px 18px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            color: white;
            font-size: 1rem;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #a855f7;
            background: rgba(255, 255, 255, 0.12);
            box-shadow: 0 0 0 4px rgba(168, 85, 247, 0.15);
        }

        .form-input::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .avatar-upload {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px dashed rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .avatar-upload:hover {
            border-color: #a855f7;
            background: rgba(168, 85, 247, 0.1);
        }

        .avatar-preview {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #7c3aed, #a855f7);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }

        .avatar-text {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.875rem;
        }

        .avatar-text strong {
            display: block;
            color: white;
            margin-bottom: 4px;
        }

        .btn-register {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #7c3aed, #a855f7);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 1rem;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 8px;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px -10px rgba(168, 85, 247, 0.5);
        }

        .login-link {
            text-align: center;
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.95rem;
            margin-top: 24px;
        }

        .login-link a {
            color: #a855f7;
            text-decoration: none;
            font-weight: 500;
        }

        .login-link a:hover {
            color: #c084fc;
        }

        .error-message {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 0.875rem;
        }

        @media (max-width: 480px) {
            .register-card {
                padding: 32px 24px;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="bg-animation">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
    </div>

    <div class="container">
        <div class="register-card">
            <div class="register-header">
                <div class="logo">📊 Laporan Bulanan</div>
                <h2>Buat Akun Baru</h2>
                <p>Daftar untuk mulai melaporkan kegiatan</p>
            </div>

            @if ($errors->any())
                <div class="error-message">
                    <ul style="margin: 0; padding-left: 16px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="/register" enctype="multipart/form-data">
                @csrf
                
                <div class="form-group">
                    <label class="form-label">Foto Profil (Opsional)</label>
                    <label class="avatar-upload">
                        <div class="avatar-preview">👤</div>
                        <div class="avatar-text">
                            <strong>Upload foto</strong>
                            Klik untuk memilih gambar
                        </div>
                        <input type="file" name="avatar" accept="image/*" style="display: none;">
                    </label>
                </div>

                <div class="form-group">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" class="form-input" placeholder="Masukkan nama lengkap" value="{{ old('name') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-input" placeholder="nama@email.com" value="{{ old('email') }}" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-input" placeholder="Min. 6 karakter" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-input" placeholder="Ulangi password" required>
                    </div>
                </div>

                <button type="submit" class="btn-register">Daftar Sekarang</button>
            </form>

            <p class="login-link">
                Sudah punya akun? <a href="/login">Masuk di sini</a>
            </p>
        </div>
    </div>
</body>
</html>
