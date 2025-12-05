<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DETERMINE TRIANGLE | MasKentir Engine</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&family=Orbitron:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --neon-cyan: #06b6d4;
            --neon-purple: #8b5cf6;
            --bg-dark: #030712;
        }
        body {
            font-family: 'JetBrains Mono', monospace;
            background-color: var(--bg-dark);
            overflow-x: hidden;
        }
        .font-cyber {
            font-family: 'Orbitron', sans-serif;
        }
        
        /* Animated Background Blobs */
        .blob {
            position: absolute;
            filter: blur(80px);
            z-index: -1;
            opacity: 0.6;
            animation: float 10s infinite ease-in-out;
        }
        .blob-1 { top: -10%; left: -10%; width: 500px; height: 500px; background: var(--neon-purple); animation-delay: 0s; }
        .blob-2 { bottom: -10%; right: -10%; width: 400px; height: 400px; background: var(--neon-cyan); animation-delay: 2s; }
        .blob-3 { top: 40%; left: 40%; width: 300px; height: 300px; background: #ec4899; opacity: 0.4; animation-delay: 4s; }

        @keyframes float {
            0% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(20px, -20px) scale(1.1); }
            100% { transform: translate(0, 0) scale(1); }
        }

        /* Glass Card */
        .glass-panel {
            background: rgba(17, 24, 39, 0.75);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            transition: all 0.5s ease;
        }

        /* Input Styling */
        .cyber-input {
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid #374151;
            transition: all 0.3s ease;
            color: #22d3ee;
            font-weight: bold;
        }
        .cyber-input:focus {
            border-color: #22d3ee;
            box-shadow: 0 0 15px rgba(34, 211, 238, 0.3);
            outline: none;
            transform: scale(1.02);
        }

        /* Button Glitch Effect on Hover */
        .cyber-btn {
            background: linear-gradient(90deg, #06b6d4, #3b82f6);
            position: relative;
            overflow: hidden;
            transition: all 0.2s;
        }
        .cyber-btn:active {
            transform: scale(0.95);
        }
        .cyber-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 20px rgba(6, 182, 212, 0.6);
        }
        .cyber-btn::before {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            transition: 0.5s;
        }
        .cyber-btn:hover::before {
            left: 100%;
        }

        /* Animations */
        @keyframes scanline {
            0% { transform: translateY(-100%); }
            100% { transform: translateY(100%); }
        }
        .scanline {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(to bottom, transparent 50%, rgba(6, 182, 212, 0.05) 51%, transparent 52%);
            background-size: 100% 4px;
            pointer-events: none;
            z-index: 10;
        }
    </style>
    
    <!-- AUDIO ENGINE SCRIPT -->
    <script>
        const Sfx = {
            ctx: null,
            init: function() {
                if (!this.ctx) {
                    this.ctx = new (window.AudioContext || window.webkitAudioContext)();
                }
            },
            playTone: function(freq, type, duration, vol = 0.1) {
                this.init();
                const osc = this.ctx.createOscillator();
                const gain = this.ctx.createGain();
                
                osc.type = type;
                osc.frequency.setValueAtTime(freq, this.ctx.currentTime);
                
                gain.gain.setValueAtTime(vol, this.ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.01, this.ctx.currentTime + duration);
                
                osc.connect(gain);
                gain.connect(this.ctx.destination);
                
                osc.start();
                osc.stop(this.ctx.currentTime + duration);
            },
            hover: function() {
                this.playTone(800, 'sine', 0.05, 0.05);
            },
            click: function() {
                this.playTone(300, 'square', 0.1, 0.1);
                setTimeout(() => this.playTone(150, 'sawtooth', 0.1, 0.1), 50);
            },
            success: function() {
                const now = this.ctx ? this.ctx.currentTime : 0;
                [440, 554, 659, 880].forEach((freq, i) => {
                    setTimeout(() => this.playTone(freq, 'triangle', 0.3, 0.1), i * 80);
                });
            },
            error: function() {
                this.playTone(150, 'sawtooth', 0.3, 0.2);
                setTimeout(() => this.playTone(100, 'sawtooth', 0.3, 0.2), 100);
            }
        };
    </script>
</head>
<body class="flex items-center justify-center min-h-screen text-white relative px-4 py-12">

    <!-- Background Effects -->
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>
    <div class="scanline"></div>

    <div class="glass-panel rounded-2xl p-8 w-full max-w-lg relative z-20 border-t border-white/10 mx-auto my-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-block px-3 py-1 rounded-full bg-cyan-900/30 border border-cyan-500/30 text-cyan-400 text-xs tracking-widest mb-2 font-bold uppercase">
                System Online
            </div>
            <h1 class="text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 via-blue-400 to-purple-500 font-cyber tracking-wider drop-shadow-lg">
                TRIANGLE<span class="text-white text-lg align-top opacity-50">.EXE</span>
            </h1>
            <p class="text-gray-400 text-xs mt-2 tracking-[0.2em] uppercase">MasKentir Analytical Engine v2.0</p>
        </div>

        <!-- Form Input -->
        <form method="post" class="space-y-6">
            <div class="grid grid-cols-3 gap-4">
                <?php
                $inputs = ["a" => "Sisi A", "b" => "Sisi B", "c" => "Sisi C"];
                foreach ($inputs as $name => $label) {
                    $val = isset($_POST[$name]) ? $_POST[$name] : '';
                    echo "
                    <div class='group'>
                        <label for='$name' class='block text-xs text-cyan-500/80 mb-2 uppercase tracking-widest text-center group-hover:text-cyan-300 transition-colors'>$label</label>
                        <input type='number' step='0.01' name='$name' id='$name' value='$val' required placeholder='0.00'
                            onmouseenter='Sfx.hover()'
                            class='cyber-input w-full rounded-xl text-center p-3 text-lg font-mono placeholder-gray-600'>
                    </div>";
                }
                ?>
            </div>

            <button type="submit" onmouseenter="Sfx.hover()" onclick="Sfx.click()"
                class="cyber-btn w-full text-white font-bold font-cyber uppercase tracking-widest py-4 rounded-xl shadow-lg mt-4 group flex items-center justify-center gap-2">
                <span>Inisialisasi Analisis</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:rotate-90 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                </svg>
            </button>
        </form>

        <!-- PHP Logic & Visualization -->
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $a = floatval($_POST['a']);
            $b = floatval($_POST['b']);
            $c = floatval($_POST['c']);

            $valid = false;
            $type = "";
            $desc = "";
            $color = "#64748b";
            $soundType = "error";

            // Check validity
            if ($a <= 0 || $b <= 0 || $c <= 0) {
                $type = "INVALID DATA";
                $desc = "Nilai sisi harus > 0";
                $color = "#ef4444";
                $soundType = "error";
            } else {
                $sides = [$a, $b, $c];
                sort($sides);
                [$x, $y, $z] = $sides;

                if ($z >= $x + $y) {
                    $type = "ERROR: NOT A TRIANGLE";
                    $desc = "Sisi terpanjang ($z) >= Jumlah sisi lain (" . ($x+$y) . ")";
                    $color = "#ef4444";
                    $soundType = "error";
                } else {
                    $valid = true;
                    $soundType = "success";

                    function almostEqual($v1, $v2) { return abs($v1 - $v2) < 0.01; }

                    if (almostEqual($x, $y) && almostEqual($y, $z)) {
                        $type = "EQUILATERAL";
                        $desc = "Segitiga Sama Sisi";
                        $color = "#06b6d4";
                    } elseif (almostEqual($x, $y) || almostEqual($y, $z) || almostEqual($x, $z)) {
                        $type = "ISOSCELES";
                        $desc = "Segitiga Sama Kaki";
                        $color = "#a855f7";
                    } elseif (abs($z*$z - ($x*$x + $y*$y)) < 0.01) {
                        $type = "RIGHT ANGLE";
                        $desc = "Segitiga Siku-Siku";
                        $color = "#22c55e";
                    } else {
                        $type = "SCALENE";
                        $desc = "Segitiga Sembarang";
                        $color = "#f59e0b";
                    }
                }
            }

            // --- OUTPUT SECTION ---
            echo "<div class='mt-8 pt-6 border-t border-dashed border-gray-700 animate-[fadeIn_0.5s_ease-out]'>";
            
            echo "<script>window.addEventListener('load', function() { setTimeout(() => Sfx.$soundType(), 300); });</script>";
            
            echo "
            <div class='flex items-center justify-between mb-4'>
                <div>
                    <h2 class='text-2xl font-cyber font-bold' style='color: $color; text-shadow: 0 0 10px $color;'>$type</h2>
                    <p class='text-gray-400 text-sm'>$desc</p>
                </div>
                <div class='h-10 w-10 rounded-full flex items-center justify-center' style='background: {$color}20; border: 1px solid $color; box-shadow: 0 0 10px {$color}40'>
                    <div class='h-3 w-3 rounded-full animate-pulse' style='background: $color;'></div>
                </div>
            </div>";

            if ($valid) {
                echo "
                <div class='relative rounded-xl overflow-hidden border border-gray-700 bg-[#0B1120] shadow-inner'>
                    <!-- Grid Overlay -->
                    <div class='absolute inset-0' style='background-image: linear-gradient(#1f2937 1px, transparent 1px), linear-gradient(90deg, #1f2937 1px, transparent 1px); background-size: 20px 20px; opacity: 0.3;'></div>
                    
                    <canvas id='triangleCanvas' width='420' height='300' class='relative z-10 w-full'></canvas>
                    
                    <!-- Tech Overlay Info -->
                    <div class='absolute bottom-2 right-2 text-[10px] text-gray-500 font-mono'>
                        RENDER_MODE: VECTOR<br>SCALE: AUTO
                    </div>
                </div>

                <script>
                    (function() {
                        const a = $a, b = $b, c = $c;
                        const canvas = document.getElementById('triangleCanvas');
                        const ctx = canvas.getContext('2d');
                        const color = '$color';

                        const dpr = window.devicePixelRatio || 1;
                        const rect = canvas.getBoundingClientRect();
                        canvas.width = rect.width * dpr;
                        canvas.height = rect.height * dpr;
                        ctx.scale(dpr, dpr);
                        
                        const width = rect.width;
                        const height = rect.height;

                        ctx.clearRect(0,0,width,height);

                        const angleC = Math.acos((a*a + b*b - c*c) / (2*a*b));
                        
                        let xA = 0, yA = 0;
                        let xC = b, yC = 0;
                        let xB = a * Math.cos(angleC), yB = a * Math.sin(angleC);
                        
                        const minX = Math.min(xA, xB, xC);
                        const maxX = Math.max(xA, xB, xC);
                        const minY = Math.min(yA, yB, yC);
                        const maxY = Math.max(yA, yB, yC);
                        
                        const triW = maxX - minX;
                        const triH = maxY - minY;
                        
                        const padding = 60;
                        const scale = Math.min((width - padding) / triW, (height - padding) / triH);
                        
                        const offsetX = (width - triW * scale) / 2 - minX * scale;
                        const offsetY = (height - triH * scale) / 2 - minY * scale;
                        
                        const drawXA = xA * scale + offsetX;
                        const drawYA = height - (yA * scale + offsetY);
                        
                        const drawXC = xC * scale + offsetX;
                        const drawYC = height - (yC * scale + offsetY);
                        
                        const drawXB = xB * scale + offsetX;
                        const drawYB = height - (yB * scale + offsetY);

                        ctx.shadowColor = color;
                        ctx.shadowBlur = 20;
                        ctx.strokeStyle = color;
                        ctx.lineWidth = 3;
                        ctx.lineJoin = 'round';

                        const gradient = ctx.createLinearGradient(drawXA, drawYA, drawXC, drawYC);
                        gradient.addColorStop(0, color + '22');
                        gradient.addColorStop(1, color + '66');
                        ctx.fillStyle = gradient;

                        ctx.beginPath();
                        ctx.moveTo(drawXA, drawYA);
                        ctx.lineTo(drawXC, drawYC);
                        ctx.lineTo(drawXB, drawYB);
                        ctx.closePath();
                        
                        ctx.fill();
                        ctx.stroke();

                        ctx.shadowBlur = 0;

                        ctx.fillStyle = '#fff';
                        [
                            {x: drawXA, y: drawYA, l: 'A'}, 
                            {x: drawXC, y: drawYC, l: 'C'}, 
                            {x: drawXB, y: drawYB, l: 'B'}
                        ].forEach(p => {
                            ctx.beginPath();
                            ctx.arc(p.x, p.y, 5, 0, Math.PI * 2);
                            ctx.fill();
                            
                            ctx.fillStyle = color;
                            ctx.font = 'bold 14px Orbitron';
                            let lx = p.x, ly = p.y;
                            if (p.y < height/2) ly -= 15; else ly += 25;
                            ctx.fillText(p.l, lx - 5, ly);
                            ctx.fillStyle = '#fff';
                        });

                        ctx.fillStyle = '#94a3b8';
                        ctx.font = '12px JetBrains Mono';
                        
                        ctx.fillText('b=' + b, (drawXA + drawXC)/2 - 10, (drawYA + drawYC)/2 + 20);
                        ctx.fillText('c=' + c, (drawXA + drawXB)/2 - 25, (drawYA + drawYB)/2);
                        ctx.fillText('a=' + a, (drawXB + drawXC)/2 + 10, (drawYB + drawYC)/2);

                        ctx.font = 'bold 16px Courier New';
                        ctx.fillStyle = color;
                        ctx.globalAlpha = 0.8;
                        ctx.textAlign = 'center';
                        ctx.fillText('MASKENTIR ENGINE', width / 2, height - 20);
                    })();
                </script>
                ";
            }
            echo "</div>";
        }
        ?>
        
        <div class="mt-8 text-center border-t border-gray-800 pt-4">
            <p class="text-[10px] text-gray-600 font-mono">
                SECURE CONNECTION ESTABLISHED // ID: <?php echo uniqid(); ?>
            </p>
        </div>
    </div>

</body>
</html>