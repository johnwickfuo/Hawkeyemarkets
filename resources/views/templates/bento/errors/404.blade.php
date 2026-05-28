<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full overflow-hidden">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('404 - Signal Lost') }} - {{ getSetting('name') }}</title>

    <link rel="icon" type="image/png" href="{{ asset('assets/images/' . getSetting('favicon', 'favicon.png')) }}">

    {{-- Bento Template Styles --}}
    @if (config('site.user_vite'))
        @vite(['resources/views/templates/bento/css/app.css'])
    @else
        <link rel="stylesheet" href="{{ asset('assets/templates/bento/css/main.css') }}">
    @endif

    <style>
        body {
            margin: 0;
            padding: 0;
            overflow: hidden !important;
            height: 100vh !important;
            background-color: #050505;
            font-family: 'Inter', sans-serif;
            color: white;
        }

        #lost-canvas {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            opacity: 0.6;
            mix-blend-mode: screen;
            z-index: 5;
            pointer-events: none;
        }

        .content-overlay {
            position: relative;
            z-index: 10;
            pointer-events: none;
        }

        .content-overlay a,
        .content-overlay button {
            pointer-events: auto;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        @keyframes pulse-glow {

            0%,
            100% {
                opacity: 0.5;
                transform: scale(1);
            }

            50% {
                opacity: 0.8;
                transform: scale(1.1);
            }
        }

        .animate-float-slow {
            animation: float 10s ease-in-out 1s infinite;
        }

        .animate-float-delayed {
            animation: float 8s ease-in-out 2s infinite;
        }

        .animate-pulse-glow {
            animation: pulse-glow 4s ease-in-out infinite;
        }
    </style>
</head>

<body class="bg-[#050505] flex flex-col h-screen overflow-hidden selection:bg-accent-primary selection:text-white">

    {{-- Background Layer 1: Ambient Effects --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none z-0">
        {{-- Grid Pattern Overlay --}}
        <div
            class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg viewBox=%220 0 200 200%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cfilter id=%22noiseFilter%22%3E%3CfeTurbulence type=%22fractalNoise%22 baseFrequency=%220.65%22 numOctaves=%223%22 stitchTiles=%22stitch%22/%3E%3C/filter%3E%3Crect width=%22100%25%22 height=%22100%25%22 filter=%22url(%23noiseFilter)%22/%3E%3C/svg%3E')] opacity-20 mix-blend-soft-light">
        </div>

        {{-- Animated Orbs --}}
        <div
            class="absolute top-[-20%] left-[-10%] w-[500px] h-[500px] bg-purple-600/20 rounded-full blur-[120px] animate-float-slow">
        </div>
        <div
            class="absolute bottom-[-20%] right-[-10%] w-[600px] h-[600px] bg-blue-600/10 rounded-full blur-[120px] animate-float-delayed">
        </div>
        <div
            class="absolute top-[30%] left-[50%] -translate-x-1/2 w-[800px] h-[400px] bg-accent-primary/5 rounded-full blur-[100px] animate-pulse-glow">
        </div>

        {{-- Globe Wireframe --}}
        <div class="absolute top-[-150px] left-[-150px] w-[500px] h-[500px] md:w-[800px] md:h-[800px] opacity-30 z-0">
            @include('templates.bento.blades.partials.globe-wireframe')
        </div>
    </div>

    {{-- Background Layer 2: Ping Pong Canvas --}}
    <canvas id="lost-canvas"></canvas>

    {{-- Foreground: Main Content --}}
    <main class="flex-1 relative flex items-center justify-center p-6 z-10">
        <div class="content-overlay container mx-auto text-center space-y-8">
            {{-- Status Pill --}}
            <div
                class="inline-flex items-center gap-3 px-5 py-2.5 rounded-full bg-white/[0.03] border border-white/10 backdrop-blur-xl mb-4">
                <span class="relative flex h-3 w-3">
                    <span
                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-accent-primary opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-accent-primary"></span>
                </span>
                <span
                    class="text-xs font-bold text-white uppercase tracking-[0.2em]">{{ __('Endless Search Loop') }}</span>
            </div>

            <div class="space-y-4">
                <h1
                    class="text-7xl md:text-[12rem] font-bold font-heading tracking-tighter text-white leading-none opacity-90 drop-shadow-2xl">
                    404
                </h1>
                <h2 class="text-2xl md:text-4xl font-bold font-heading tracking-tight text-white/80">
                    {{ __('Lost in Orbit') }}<span class="text-accent-primary">.</span>
                </h2>
                <p class="text-lg text-text-secondary leading-relaxed max-w-xl mx-auto opacity-70">
                    {{ __('The signal you followed led into a recursive loop. The page you seek has been dereferenced from our network.') }}
                </p>
            </div>

            {{-- Actions --}}
            <div class="pt-8 flex flex-col sm:flex-row items-center justify-center gap-6">
                <a href="{{ route('home') }}"
                    class="px-12 py-4 rounded-full bg-white text-black font-bold shadow-2xl hover:bg-white/90 transition-all duration-300 flex items-center gap-3 group">
                    {{ __('Break the Loop') }}
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="transition-transform group-hover:translate-x-1">
                        <path d="M5 12h14m-7-7 7 7-7 7" />
                    </svg>
                </a>

                <button onclick="window.history.back()"
                    class="px-12 py-4 rounded-full bg-transparent border border-white/20 text-white font-bold hover:bg-white/5 transition-all duration-300 backdrop-blur-sm">
                    {{ __('Reverse Pulse') }}
                </button>
            </div>
        </div>
    </main>

    {{-- Scanline Effect --}}
    <div
        class="fixed inset-0 bg-[linear-gradient(to_bottom,transparent_50%,rgba(0,0,0,0.3)_50%)] bg-[length:100%_4px] opacity-10 pointer-events-none z-20">
    </div>

    <script>
        (function() {
            const canvas = document.getElementById('lost-canvas');
            const ctx = canvas.getContext('2d');

            let width, height, isMobile;
            let ball = {
                x: 0,
                y: 0,
                dx: 7,
                dy: 7,
                radius: 24
            };
            let paddleLeft = {
                y: 0,
                height: 260,
                width: 12
            };
            let paddleRight = {
                y: 0,
                height: 260,
                width: 12
            };
            let particles = [];

            function resize() {
                width = window.innerWidth;
                height = window.innerHeight;
                canvas.width = width;
                canvas.height = height;

                isMobile = width < 768;
                const baseSpeed = isMobile ? 4 : 7;

                ball.radius = isMobile ? 12 : 24;
                ball.dx = baseSpeed * (ball.dx > 0 ? 1 : -1);
                ball.dy = baseSpeed * (ball.dy > 0 ? 1 : -1);

                paddleLeft.height = isMobile ? 120 : 260;
                paddleLeft.width = isMobile ? 6 : 12;
                paddleRight.height = isMobile ? 120 : 260;
                paddleRight.width = isMobile ? 6 : 12;

                ball.x = width / 2;
                ball.y = height / 2;
                paddleLeft.y = height / 2 - paddleLeft.height / 2;
                paddleRight.y = height / 2 - paddleRight.height / 2;
            }

            window.addEventListener('resize', resize);
            resize();

            function createParticles(x, y) {
                for (let i = 0; i < 30; i++) {
                    particles.push({
                        x: x,
                        y: y,
                        dx: (Math.random() - 0.5) * 18,
                        dy: (Math.random() - 0.5) * 18,
                        life: 1.0,
                        size: Math.random() * 5 + 1,
                        color: Math.random() > 0.3 ? '#ef4444' : '#f97316' // Red/Orange fire sparks
                    });
                }
            }

            function draw() {
                ctx.clearRect(0, 0, width, height);

                // Grid lines (Subtle)
                ctx.strokeStyle = 'rgba(59, 130, 246, 0.05)';
                ctx.lineWidth = 1;
                for (let i = 1; i < 5; i++) {
                    ctx.beginPath();
                    ctx.moveTo(0, height * (i / 5));
                    ctx.lineTo(width, height * (i / 5));
                    ctx.stroke();
                    ctx.beginPath();
                    ctx.moveTo(width * (i / 5), 0);
                    ctx.lineTo(width * (i / 5), height);
                    ctx.stroke();
                }

                // AI Logic (NEVER MISS)
                const leftTarget = ball.y - paddleLeft.height / 2;
                paddleLeft.y += (leftTarget - paddleLeft.y) * 0.25;

                const rightTarget = ball.y - paddleRight.height / 2;
                paddleRight.y += (rightTarget - paddleRight.y) * 0.25;

                // Clamp paddles
                paddleLeft.y = Math.max(0, Math.min(height - paddleLeft.height, paddleLeft.y));
                paddleRight.y = Math.max(0, Math.min(height - paddleRight.height, paddleRight.y));

                // Ball Movement
                ball.x += ball.dx;
                ball.y += ball.dy;

                // Wall Collisions
                if (ball.y + ball.radius > height || ball.y - ball.radius < 0) {
                    ball.dy *= -1;
                    createParticles(ball.x, ball.y);
                }

                // Paddle Collisions (Left)
                if (ball.dx < 0 && ball.x - ball.radius < 40 + paddleLeft.width) {
                    ball.dx *= -1.02;
                    ball.x = 40 + paddleLeft.width + ball.radius;
                    createParticles(ball.x, ball.y);
                }

                // Paddle Collisions (Right)
                if (ball.dx > 0 && ball.x + ball.radius > width - 40 - paddleRight.width) {
                    ball.dx *= -1.02;
                    ball.x = width - 40 - paddleRight.width - ball.radius;
                    createParticles(ball.x, ball.y);
                }

                const maxSpeed = isMobile ? 10 : 20;
                if (Math.abs(ball.dx) > maxSpeed) ball.dx = maxSpeed * Math.sign(ball.dx);
                if (Math.abs(ball.dy) > maxSpeed) ball.dy = maxSpeed * Math.sign(ball.dy);

                // Draw Paddles
                ctx.fillStyle = 'rgba(59, 130, 246, 0.3)';
                ctx.strokeStyle = 'rgba(59, 130, 246, 0.8)';
                ctx.lineWidth = 4;

                ctx.fillRect(40, paddleLeft.y, paddleLeft.width, paddleLeft.height);
                ctx.strokeRect(40, paddleLeft.y, paddleLeft.width, paddleLeft.height);

                ctx.fillRect(width - 40 - paddleRight.width, paddleRight.y, paddleRight.width, paddleRight.height);
                ctx.strokeRect(width - 40 - paddleRight.width, paddleRight.y, paddleRight.width, paddleRight.height);

                // Draw Ball (No tail)
                ctx.shadowBlur = 50;
                ctx.shadowColor = '#3b82f6';
                ctx.fillStyle = '#fff';
                ctx.beginPath();
                ctx.arc(ball.x, ball.y, ball.radius, 0, Math.PI * 2);
                ctx.fill();
                ctx.shadowBlur = 0;

                // Fire Sparks
                particles.forEach((p, i) => {
                    p.x += p.dx;
                    p.y += p.dy;
                    p.dy += 0.15; // Gravity
                    p.life -= 0.02;
                    if (p.life <= 0) particles.splice(i, 1);

                    ctx.shadowBlur = 15;
                    ctx.shadowColor = p.color;
                    ctx.fillStyle = p.color;
                    ctx.globalAlpha = p.life;
                    ctx.fillRect(p.x, p.y, p.size, p.size);
                    ctx.globalAlpha = 1;
                    ctx.shadowBlur = 0;
                });

                requestAnimationFrame(draw);
            }

            draw();
        })();
    </script>
</body>

</html>
