<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Laporan Bulanan</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #6C3FF5;
            --primary-foreground: #ffffff;
            --background: #0f0f23;
            --foreground: #ffffff;
            --muted-foreground: rgba(255, 255, 255, 0.6);
            --border: rgba(255, 255, 255, 0.15);
            --input-bg: rgba(255, 255, 255, 0.08);
            --accent: rgba(255, 255, 255, 0.1);
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 1fr;
            background: var(--background);
        }

        /* Left Content Section */
        .left-section {
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background: linear-gradient(135deg, rgba(108, 63, 245, 0.9), rgba(108, 63, 245, 1), rgba(108, 63, 245, 0.8));
            padding: 48px;
            color: var(--primary-foreground);
            overflow: hidden;
        }

        /* Decorative elements */
        .grid-overlay {
            position: absolute;
            inset: 0;
            background-image: 
                linear-gradient(rgba(255,255,255,0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.05) 1px, transparent 1px);
            background-size: 20px 20px;
        }

        .blur-circle-1 {
            position: absolute;
            top: 25%;
            right: 25%;
            width: 256px;
            height: 256px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            filter: blur(48px);
        }

        .blur-circle-2 {
            position: absolute;
            bottom: 25%;
            left: 25%;
            width: 384px;
            height: 384px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            filter: blur(48px);
        }

        .brand {
            position: relative;
            z-index: 20;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 1.125rem;
            font-weight: 600;
        }

        .brand-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .brand-icon img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Characters container */
        .characters-container {
            position: relative;
            z-index: 20;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            height: 500px;
        }

        .characters-wrapper {
            position: relative;
            width: 550px;
            height: 400px;
        }

        /* Character base styles */
        .character {
            position: absolute;
            bottom: 0;
            transition: all 0.7s ease-in-out;
            transform-origin: bottom center;
        }

        /* Purple character */
        .purple-char {
            left: 70px;
            width: 180px;
            height: 400px;
            background-color: #4B5320;
            border-radius: 10px 10px 0 0;
            z-index: 1;
        }

        .purple-char.typing {
            height: 440px;
        }

        /* Black character */
        .black-char {
            left: 240px;
            width: 120px;
            height: 310px;
            background-color: #2D2D2D;
            border-radius: 8px 8px 0 0;
            z-index: 2;
        }

        /* Orange character */
        .orange-char {
            left: 0;
            width: 240px;
            height: 200px;
            background-color: #FF9B6B;
            border-radius: 120px 120px 0 0;
            z-index: 3;
        }

        /* Yellow character */
        .yellow-char {
            left: 310px;
            width: 140px;
            height: 230px;
            background-color: #E8D754;
            border-radius: 70px 70px 0 0;
            z-index: 4;
        }

        /* Eyes container */
        .eyes {
            position: absolute;
            display: flex;
            transition: all 0.2s ease-out;
        }

        .purple-char .eyes {
            gap: 32px;
            left: 45px;
            top: 40px;
        }

        .black-char .eyes {
            gap: 24px;
            left: 26px;
            top: 32px;
        }

        .orange-char .eyes {
            gap: 32px;
            left: 82px;
            top: 90px;
        }

        .yellow-char .eyes {
            gap: 24px;
            left: 52px;
            top: 40px;
        }

        /* Eyeball styles */
        .eyeball {
            width: 18px;
            height: 18px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            transition: height 0.15s ease;
        }

        .eyeball.blinking {
            height: 2px;
        }

        .eyeball.small {
            width: 16px;
            height: 16px;
        }

        .pupil {
            width: 7px;
            height: 7px;
            background: #2D2D2D;
            border-radius: 50%;
            transition: transform 0.1s ease-out;
        }

        .pupil.small {
            width: 6px;
            height: 6px;
        }

        /* Pupil only (no eyeball) */
        .pupil-only {
            width: 12px;
            height: 12px;
            background: #2D2D2D;
            border-radius: 50%;
            transition: transform 0.1s ease-out;
        }

        /* Yellow mouth */
        .yellow-mouth {
            position: absolute;
            width: 80px;
            height: 4px;
            background: #2D2D2D;
            border-radius: 2px;
            left: 40px;
            top: 88px;
            transition: all 0.2s ease-out;
        }

        /* Footer links */
        .footer-links {
            position: relative;
            z-index: 20;
            display: flex;
            align-items: center;
            gap: 32px;
            font-size: 0.875rem;
            color: rgba(255, 255, 255, 0.6);
        }

        .footer-links a {
            color: inherit;
            text-decoration: none;
            transition: color 0.2s;
        }

        .footer-links a:hover {
            color: white;
        }

        /* Right Login Section */
        .right-section {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px;
            background: var(--background);
        }

        .login-container {
            width: 100%;
            max-width: 420px;
        }

        /* Mobile Logo */
        .mobile-logo {
            display: none;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 48px;
            color: var(--foreground);
        }

        .mobile-logo .brand-icon {
            background: rgba(108, 63, 245, 0.1);
        }

        .mobile-logo .brand-icon svg {
            color: var(--primary);
        }

        /* Header */
        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .login-header h1 {
            font-size: 1.875rem;
            font-weight: 700;
            letter-spacing: -0.025em;
            margin-bottom: 8px;
            color: var(--foreground);
        }

        /* Shiny text - cursor following */
        .shiny-text {
            position: relative;
            background: linear-gradient(
                90deg,
                rgba(255, 255, 255, 0.4) 0%,
                rgba(255, 255, 255, 0.4) 35%,
                rgba(255, 255, 255, 1) 45%,
                rgba(108, 63, 245, 1) 50%,
                rgba(236, 72, 153, 1) 55%,
                rgba(255, 255, 255, 1) 60%,
                rgba(255, 255, 255, 0.4) 70%,
                rgba(255, 255, 255, 0.4) 100%
            );
            background-size: 300% 100%;
            background-position: 100% center;
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            transition: background-position 0.1s ease-out;
        }

        .login-header p {
            color: var(--muted-foreground);
            font-size: 0.875rem;
        }

        /* Form styles */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 8px;
            color: var(--foreground);
        }

        .form-input {
            width: 100%;
            height: 48px;
            padding: 0 16px;
            background: var(--input-bg);
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 1rem;
            font-family: inherit;
            color: var(--foreground);
            transition: all 0.2s;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            background: rgba(255, 255, 255, 0.12);
        }

        .form-input::placeholder {
            color: var(--muted-foreground);
        }

        .password-wrapper {
            position: relative;
        }

        .password-wrapper .form-input {
            padding-right: 48px;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--muted-foreground);
            cursor: pointer;
            padding: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s;
        }

        .password-toggle:hover {
            color: var(--foreground);
        }

        .password-toggle svg {
            width: 20px;
            height: 20px;
        }

        /* Remember & Forgot */
        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .remember-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.875rem;
            color: var(--foreground);
            cursor: pointer;
        }

        .remember-label input {
            width: 16px;
            height: 16px;
            accent-color: var(--primary);
        }

        .forgot-link {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--primary);
            text-decoration: none;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        /* Error message */
        .error-message {
            padding: 12px;
            font-size: 0.875rem;
            color: #f87171;
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            border-radius: 8px;
            margin-bottom: 20px;
        }

        /* Submit button */
        .btn-submit {
            width: 100%;
            height: 48px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 500;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-submit:hover {
            background: rgba(108, 63, 245, 0.9);
        }

        .btn-submit:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Google button */
        .btn-google {
            width: 100%;
            height: 48px;
            margin-top: 24px;
            background: transparent;
            color: var(--foreground);
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 1rem;
            font-family: inherit;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s;
        }

        .btn-google:hover {
            background: var(--accent);
        }

        .btn-google svg {
            width: 20px;
            height: 20px;
        }

        /* Sign up link */
        .signup-text {
            text-align: center;
            font-size: 0.875rem;
            color: var(--muted-foreground);
            margin-top: 32px;
        }

        .signup-text a {
            color: var(--foreground);
            font-weight: 500;
            text-decoration: none;
        }

        .signup-text a:hover {
            text-decoration: underline;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            body {
                grid-template-columns: 1fr;
            }

            .left-section {
                display: none;
            }

            .mobile-logo {
                display: flex;
            }
        }

        @media (max-width: 480px) {
            .right-section {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Left Content Section -->
    <div class="left-section">
        <div class="grid-overlay"></div>
        <div class="blur-circle-1"></div>
        <div class="blur-circle-2"></div>

        <div class="brand">
            <div class="brand-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 3l1.912 5.813a2 2 0 001.272 1.272L21 12l-5.813 1.912a2 2 0 00-1.272 1.272L12 21l-1.912-5.813a2 2 0 00-1.272-1.272L3 12l5.813-1.912a2 2 0 001.272-1.272L12 3z"/>
                </svg>
            </div>
            <span>Laporan Bulanan</span>
        </div>

        <div class="characters-container">
            <div class="characters-wrapper">
                <!-- Purple Character -->
                <div class="character purple-char" id="purpleChar">
                    <div class="eyes" id="purpleEyes">
                        <div class="eyeball" id="purpleEye1">
                            <div class="pupil" id="purplePupil1"></div>
                        </div>
                        <div class="eyeball" id="purpleEye2">
                            <div class="pupil" id="purplePupil2"></div>
                        </div>
                    </div>
                </div>

                <!-- Black Character -->
                <div class="character black-char" id="blackChar">
                    <div class="eyes" id="blackEyes">
                        <div class="eyeball small" id="blackEye1">
                            <div class="pupil small" id="blackPupil1"></div>
                        </div>
                        <div class="eyeball small" id="blackEye2">
                            <div class="pupil small" id="blackPupil2"></div>
                        </div>
                    </div>
                </div>

                <!-- Orange Character -->
                <div class="character orange-char" id="orangeChar">
                    <div class="eyes" id="orangeEyes">
                        <div class="pupil-only" id="orangePupil1"></div>
                        <div class="pupil-only" id="orangePupil2"></div>
                    </div>
                </div>

                <!-- Yellow Character -->
                <div class="character yellow-char" id="yellowChar">
                    <div class="eyes" id="yellowEyes">
                        <div class="pupil-only" id="yellowPupil1"></div>
                        <div class="pupil-only" id="yellowPupil2"></div>
                    </div>
                    <div class="yellow-mouth" id="yellowMouth"></div>
                </div>
            </div>
        </div>

        <div class="footer-links">
            <a href="#">Privacy Policy</a>
            <a href="#">Terms of Service</a>
            <a href="#">Contact</a>
        </div>
    </div>

    <!-- Right Login Section -->
    <div class="right-section">
        <div class="login-container">
            <!-- Mobile Logo -->
            <div class="mobile-logo">
                <div class="brand-icon">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 3l1.912 5.813a2 2 0 001.272 1.272L21 12l-5.813 1.912a2 2 0 00-1.272 1.272L12 21l-1.912-5.813a2 2 0 00-1.272-1.272L3 12l5.813-1.912a2 2 0 001.272-1.272L12 3z"/>
                    </svg>
                </div>
                <span>Laporan Bulanan</span>
            </div>

            <!-- Header -->
            <div class="login-header">
                <h1 class="shiny-text">Selamat Datang!</h1>
                <p>Silakan masukkan detail Anda</p>
            </div>

            <!-- Login Form -->
            <form method="POST" action="/login" id="loginForm">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="form-input" 
                        placeholder="nama@email.com" 
                        value="{{ old('email') }}" 
                        required
                        autocomplete="off"
                    >
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <div class="password-wrapper">
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="form-input" 
                            placeholder="••••••••" 
                            required
                        >
                        <button type="button" class="password-toggle" id="passwordToggle">
                            <svg id="eyeIcon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            <svg id="eyeOffIcon" style="display: none;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/>
                                <line x1="1" y1="1" x2="23" y2="23"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="form-options">
                    <label class="remember-label">
                        <input type="checkbox" name="remember">
                        Remember me
                    </label>
                    <a href="#" class="forgot-link">Forgot password?</a>
                </div>

                @if ($errors->any())
                    <div class="error-message">
                        {{ $errors->first() }}
                    </div>
                @endif

                <button type="submit" class="btn-submit" id="submitBtn">Sign In</button>
            </form>

            <!-- Sign Up Link -->
            <p class="signup-text">
                Belum punya akun? <a href="/register">Daftar Sekarang</a>
            </p>
        </div>
    </div>

    <script>
        // State
        let mouseX = 0;
        let mouseY = 0;
        let isTyping = false;
        let isPasswordVisible = false;
        let passwordValue = '';
        let isPurplePeeking = false;

        // Elements
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const passwordToggle = document.getElementById('passwordToggle');
        const eyeIcon = document.getElementById('eyeIcon');
        const eyeOffIcon = document.getElementById('eyeOffIcon');

        // Characters
        const purpleChar = document.getElementById('purpleChar');
        const blackChar = document.getElementById('blackChar');
        const orangeChar = document.getElementById('orangeChar');
        const yellowChar = document.getElementById('yellowChar');

        // Eyes
        const purpleEyes = document.getElementById('purpleEyes');
        const blackEyes = document.getElementById('blackEyes');
        const orangeEyes = document.getElementById('orangeEyes');
        const yellowEyes = document.getElementById('yellowEyes');

        // Pupils
        const purplePupil1 = document.getElementById('purplePupil1');
        const purplePupil2 = document.getElementById('purplePupil2');
        const blackPupil1 = document.getElementById('blackPupil1');
        const blackPupil2 = document.getElementById('blackPupil2');
        const orangePupil1 = document.getElementById('orangePupil1');
        const orangePupil2 = document.getElementById('orangePupil2');
        const yellowPupil1 = document.getElementById('yellowPupil1');
        const yellowPupil2 = document.getElementById('yellowPupil2');
        const yellowMouth = document.getElementById('yellowMouth');

        // Eyeballs for blinking
        const purpleEye1 = document.getElementById('purpleEye1');
        const purpleEye2 = document.getElementById('purpleEye2');
        const blackEye1 = document.getElementById('blackEye1');
        const blackEye2 = document.getElementById('blackEye2');

        // Mouse tracking
        document.addEventListener('mousemove', (e) => {
            mouseX = e.clientX;
            mouseY = e.clientY;
            if (!isTyping && !(passwordValue && isPasswordVisible)) {
                updateEyePositions();
                updateCharacterLean();
            }
        });

        // Calculate pupil position based on mouse
        function calculatePupilOffset(element, maxDistance = 5) {
            const rect = element.getBoundingClientRect();
            const centerX = rect.left + rect.width / 2;
            const centerY = rect.top + rect.height / 2;

            const deltaX = mouseX - centerX;
            const deltaY = mouseY - centerY;
            const distance = Math.min(Math.sqrt(deltaX ** 2 + deltaY ** 2), maxDistance * 20);

            const angle = Math.atan2(deltaY, deltaX);
            const x = Math.cos(angle) * Math.min(distance / 20, maxDistance);
            const y = Math.sin(angle) * Math.min(distance / 20, maxDistance);

            return { x, y };
        }

        // Update all eye positions
        function updateEyePositions() {
            // Purple pupils
            const purpleOffset = calculatePupilOffset(purplePupil1, 5);
            purplePupil1.style.transform = `translate(${purpleOffset.x}px, ${purpleOffset.y}px)`;
            purplePupil2.style.transform = `translate(${purpleOffset.x}px, ${purpleOffset.y}px)`;

            // Black pupils
            const blackOffset = calculatePupilOffset(blackPupil1, 4);
            blackPupil1.style.transform = `translate(${blackOffset.x}px, ${blackOffset.y}px)`;
            blackPupil2.style.transform = `translate(${blackOffset.x}px, ${blackOffset.y}px)`;

            // Orange pupils
            const orangeOffset = calculatePupilOffset(orangePupil1, 5);
            orangePupil1.style.transform = `translate(${orangeOffset.x}px, ${orangeOffset.y}px)`;
            orangePupil2.style.transform = `translate(${orangeOffset.x}px, ${orangeOffset.y}px)`;

            // Yellow pupils
            const yellowOffset = calculatePupilOffset(yellowPupil1, 5);
            yellowPupil1.style.transform = `translate(${yellowOffset.x}px, ${yellowOffset.y}px)`;
            yellowPupil2.style.transform = `translate(${yellowOffset.x}px, ${yellowOffset.y}px)`;
        }

        // Calculate and update character lean based on mouse
        function updateCharacterLean() {
            const chars = [
                { el: purpleChar, ref: purpleChar },
                { el: blackChar, ref: blackChar },
                { el: orangeChar, ref: orangeChar },
                { el: yellowChar, ref: yellowChar }
            ];

            chars.forEach(({ el, ref }) => {
                const rect = ref.getBoundingClientRect();
                const centerX = rect.left + rect.width / 2;
                const deltaX = mouseX - centerX;
                const skew = Math.max(-6, Math.min(6, -deltaX / 120));
                el.style.transform = `skewX(${skew}deg)`;
            });
        }

        // Blinking effect
        function setupBlinking(eyeball1, eyeball2, minInterval = 3000, maxInterval = 7000) {
            function blink() {
                eyeball1.classList.add('blinking');
                eyeball2.classList.add('blinking');
                
                setTimeout(() => {
                    eyeball1.classList.remove('blinking');
                    eyeball2.classList.remove('blinking');
                }, 150);

                const nextBlink = Math.random() * (maxInterval - minInterval) + minInterval;
                setTimeout(blink, nextBlink);
            }

            const initialDelay = Math.random() * (maxInterval - minInterval) + minInterval;
            setTimeout(blink, initialDelay);
        }

        // Initialize blinking for purple and black characters
        setupBlinking(purpleEye1, purpleEye2);
        setupBlinking(blackEye1, blackEye2);

        // Typing detection - characters look at each other
        function handleTypingStart() {
            isTyping = true;
            
            // Purple looks right, black looks left
            purpleEyes.style.left = '55px';
            purpleEyes.style.top = '65px';
            purplePupil1.style.transform = 'translate(3px, 4px)';
            purplePupil2.style.transform = 'translate(3px, 4px)';

            blackEyes.style.left = '32px';
            blackEyes.style.top = '12px';
            blackPupil1.style.transform = 'translate(0px, -4px)';
            blackPupil2.style.transform = 'translate(0px, -4px)';

            // Characters lean towards each other
            purpleChar.classList.add('typing');
            purpleChar.style.transform = 'skewX(-12deg) translateX(40px)';
            blackChar.style.transform = 'skewX(10deg) translateX(20px)';

            setTimeout(() => {
                if (!passwordValue || !isPasswordVisible) {
                    resetCharacters();
                }
            }, 800);
        }

        function handleTypingEnd() {
            isTyping = false;
            if (!passwordValue || !isPasswordVisible) {
                resetCharacters();
            }
        }

        function resetCharacters() {
            purpleChar.classList.remove('typing');
            purpleEyes.style.left = '45px';
            purpleEyes.style.top = '40px';
            blackEyes.style.left = '26px';
            blackEyes.style.top = '32px';
            
            updateEyePositions();
            updateCharacterLean();
        }

        // Password visibility - all look away
        function handlePasswordVisible() {
            // All characters look away (left and up)
            const lookAwayX = -4;
            const lookAwayY = -4;

            // Purple
            purpleEyes.style.left = '20px';
            purpleEyes.style.top = '35px';
            purplePupil1.style.transform = `translate(${lookAwayX}px, ${lookAwayY}px)`;
            purplePupil2.style.transform = `translate(${lookAwayX}px, ${lookAwayY}px)`;

            // Black
            blackEyes.style.left = '10px';
            blackEyes.style.top = '28px';
            blackPupil1.style.transform = `translate(${lookAwayX}px, ${lookAwayY}px)`;
            blackPupil2.style.transform = `translate(${lookAwayX}px, ${lookAwayY}px)`;

            // Orange
            orangeEyes.style.left = '50px';
            orangeEyes.style.top = '85px';
            orangePupil1.style.transform = `translate(-5px, ${lookAwayY}px)`;
            orangePupil2.style.transform = `translate(-5px, ${lookAwayY}px)`;

            // Yellow
            yellowEyes.style.left = '20px';
            yellowEyes.style.top = '35px';
            yellowPupil1.style.transform = `translate(-5px, ${lookAwayY}px)`;
            yellowPupil2.style.transform = `translate(-5px, ${lookAwayY}px)`;
            yellowMouth.style.left = '10px';
            yellowMouth.style.top = '88px';

            // Reset transforms
            purpleChar.classList.remove('typing');
            purpleChar.style.transform = 'skewX(0deg)';
            blackChar.style.transform = 'skewX(0deg)';
            orangeChar.style.transform = 'skewX(0deg)';
            yellowChar.style.transform = 'skewX(0deg)';

            // Purple sneaky peek
            startPurplePeeking();
        }

        function handlePasswordHidden() {
            stopPurplePeeking();
            resetAllEyes();
            updateEyePositions();
            updateCharacterLean();
        }

        function resetAllEyes() {
            purpleEyes.style.left = '45px';
            purpleEyes.style.top = '40px';
            blackEyes.style.left = '26px';
            blackEyes.style.top = '32px';
            orangeEyes.style.left = '82px';
            orangeEyes.style.top = '90px';
            yellowEyes.style.left = '52px';
            yellowEyes.style.top = '40px';
            yellowMouth.style.left = '40px';
            yellowMouth.style.top = '88px';
        }

        // Purple peeking animation when password is visible
        let peekInterval = null;

        function startPurplePeeking() {
            if (peekInterval) return;
            
            function peek() {
                if (!isPasswordVisible || !passwordValue) {
                    stopPurplePeeking();
                    return;
                }

                isPurplePeeking = true;
                // Sneaky look at password
                purplePupil1.style.transform = 'translate(4px, 5px)';
                purplePupil2.style.transform = 'translate(4px, 5px)';

                setTimeout(() => {
                    if (isPasswordVisible && passwordValue) {
                        // Look away again
                        purplePupil1.style.transform = 'translate(-4px, -4px)';
                        purplePupil2.style.transform = 'translate(-4px, -4px)';
                    }
                    isPurplePeeking = false;
                }, 800);
            }

            peekInterval = setInterval(peek, 3000);
            setTimeout(peek, 1000); // First peek after 1 second
        }

        function stopPurplePeeking() {
            if (peekInterval) {
                clearInterval(peekInterval);
                peekInterval = null;
            }
            isPurplePeeking = false;
        }

        // Event Listeners
        emailInput.addEventListener('focus', handleTypingStart);
        emailInput.addEventListener('blur', handleTypingEnd);

        passwordInput.addEventListener('input', (e) => {
            passwordValue = e.target.value;
            if (passwordValue && isPasswordVisible) {
                handlePasswordVisible();
            }
        });

        passwordToggle.addEventListener('click', () => {
            isPasswordVisible = !isPasswordVisible;
            
            if (isPasswordVisible) {
                passwordInput.type = 'text';
                eyeIcon.style.display = 'none';
                eyeOffIcon.style.display = 'block';
                if (passwordValue) {
                    handlePasswordVisible();
                }
            } else {
                passwordInput.type = 'password';
                eyeIcon.style.display = 'block';
                eyeOffIcon.style.display = 'none';
                handlePasswordHidden();
            }
        });

        // Initial setup
        updateEyePositions();
        updateCharacterLean();
        // Shiny text cursor tracking
        const shinyText = document.querySelector('.shiny-text');
        if (shinyText) {
            shinyText.addEventListener('mousemove', (e) => {
                const rect = shinyText.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const percentage = (x / rect.width) * 100;
                // Map percentage to background position (inverted for correct direction)
                const bgPosition = 100 - (percentage * 1.5);
                shinyText.style.backgroundPosition = `${bgPosition}% center`;
            });

            shinyText.addEventListener('mouseleave', () => {
                shinyText.style.backgroundPosition = '100% center';
            });
        }
    </script>
</body>
</html>
