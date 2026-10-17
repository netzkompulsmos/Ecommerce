<!DOCTYPE html>
<html lang="km">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>អំពីយើង • About NET STORE • DNA Cyber Edition</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" href="./logo/logome.png" type="image/x-icon">
    <link rel="stylesheet" href="./page.css">
    <style>
        .about-cyber-layer {
            position: relative;
            z-index: 10;
            max-width: 920px;
            margin: 40px auto 60px;
            padding: 0 20px;
        }

        .about-cyber-panel {
            background: rgba(10, 16, 32, 0.78);
            backdrop-filter: blur(28px) saturate(200%);
            -webkit-backdrop-filter: blur(28px) saturate(200%);
            border: 1px solid var(--cyber-cyan);
            border-radius: 32px;
            padding: 40px 34px;
            box-shadow: 
                0 25px 60px -10px rgba(0, 0, 0, 0.9),
                0 0 35px rgba(0, 240, 255, 0.25),
                inset 0 1px 1px rgba(255, 255, 255, 0.4);
        }

        .about-cyber-panel h2 {
            font-size: 32px;
            font-weight: 800;
            font-family: 'Space Grotesk', sans-serif;
            letter-spacing: -0.02em;
            color: #fff;
            text-align: center;
            margin-bottom: 24px;
        }

        .about-cyber-panel h3 {
            color: var(--cyber-cyan);
            font-size: 20px;
            font-weight: 700;
            margin: 28px 0 12px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .about-cyber-panel p {
            color: var(--text-muted);
            line-height: 1.7;
            font-size: 15px;
            margin-bottom: 16px;
        }

        .cyber-bullet-matrix {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 14px;
            margin: 20px 0;
        }

        .bullet-chip {
            background: rgba(0, 240, 255, 0.06);
            border: 1px solid rgba(0, 240, 255, 0.2);
            border-radius: 20px;
            padding: 16px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .bullet-chip i {
            color: var(--cyber-emerald);
            font-size: 18px;
            margin-top: 2px;
        }

        .bullet-chip div {
            font-size: 13.5px;
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.4;
        }
    </style>
</head>

<body>
    <!-- Live 3D DNA Canvas Background -->
    <canvas id="dna-canvas"></canvas>
    <div class="canvas-overlay-vignette"></div>
    <div class="cyber-grid-lines"></div>

    <div class="about-cyber-layer">
        <!-- Top Nav -->
        <header class="cyber-nav-container" style="position: relative; top: 0; padding: 0; margin-bottom: 28px;">
            <nav class="cyber-island-nav">
                <a href="index.php" class="brand-emblem">
                    <div class="brand-hologram-cube">
                        <img src="./logo/icon.png" alt="NET Icon" onerror="this.src='./logo/logome.png'">
                    </div>
                    <div class="brand-titles">
                        <h1>NET STORE <span class="dna-badge">ABOUT</span></h1>
                    </div>
                </a>
                <div class="nav-actions">
                    <a href="index.php" class="cyber-glow-btn">
                        <i class="fas fa-arrow-left"></i> ត្រឡប់ទៅទំព័រដើម (Home)
                    </a>
                </div>
            </nav>
        </header>

        <div class="about-cyber-panel">
            <div style="text-align: center; margin-bottom: 20px;">
                <div class="dna-badge" style="font-size: 12px; padding: 5px 14px; display: inline-flex; margin-bottom: 10px;">
                    <i class="fas fa-microchip"></i> Corporate Profile
                </div>
                <h2>អំពី APP ACCOUNT SELLER</h2>
            </div>

            <div style="text-align: center; margin: 24px 0;">
                <div class="hero-centerpiece" style="padding: 24px; max-width: 650px; margin: 0 auto; min-height: 160px;">
                    <img src="./logo/banner.png" alt="Banner" style="width: 100%; height: 100%; object-fit: cover; border-radius: 20px;" onerror="this.src='./logo/logome.png'">
                </div>
            </div>

            <h3><i class="fas fa-bullseye"></i> បេសកកម្មរបស់យើង (Our Mission)</h3>
            <p>
                នៅ <strong>APP ACCOUNT SELLER</strong> បេសកកម្មរបស់យើងគឺផ្តល់ជូននូវវេទិកាដែលមានទំនុកចិត្តខ្ពស់ សុវត្ថិភាព និងភាពងាយស្រួលក្នុងការបញ្ជាទិញគណនីកម្មវិធីឌីជីថល Premium គ្រប់ប្រភេទ។ យើងយល់ច្បាស់ពីតម្រូវការប្រើប្រាស់សេវាកម្មឌីជីថលលំដាប់ពិភពលោកក្នុងតម្លៃសមរម្យ និងសន្សំសំចៃបំផុត។
            </p>

            <h3><i class="fas fa-users"></i> យើងជានរណា? (Who We Are)</h3>
            <p>
                យើងជាក្រុមការងារដែលយកចិត្តទុកដាក់ និងស្រលាញ់សេវាកម្មឌីជីថល ព្រមទាំងការពេញចិត្តរបស់អតិថិជនជាចម្បង។ យើងមានមោទនភាពចំពោះសេវាកម្មដ៏មានតម្លាភាព តម្លៃប្រកួតប្រជែង និងការគាំទ្រអតិថិជនដ៏រហ័សទាន់ចិត្ត 24/7។
            </p>

            <h3><i class="fas fa-award"></i> ហេតុអ្វីត្រូវជ្រើសរើសយើង? (Why Choose Us?)</h3>
            <div class="cyber-bullet-matrix">
                <div class="bullet-chip">
                    <i class="fas fa-check-circle"></i>
                    <div><strong>ភាពជឿជាក់ខ្ពស់ (Reliability):</strong> គណនីគ្រប់ប្រភេទដំណើរការស្របច្បាប់ និងមានស្ថិរភាព 100%។</div>
                </div>
                <div class="bullet-chip">
                    <i class="fas fa-shield-halved"></i>
                    <div><strong>សុវត្ថិភាពទ្វេដង (Security):</strong> ការពារទិន្នន័យ និងព័ត៌មានអតិថិជនយ៉ាងម៉ឺងម៉ាត់បំផុត។</div>
                </div>
                <div class="bullet-chip">
                    <i class="fas fa-tag"></i>
                    <div><strong>តម្លៃសមរម្យ (Affordability):</strong> តម្លៃធូរថ្លៃ និងសន្សំសំចៃជាងការទិញផ្ទាល់តាម store។</div>
                </div>
                <div class="bullet-chip">
                    <i class="fas fa-bolt"></i>
                    <div><strong>លឿនទាន់ចិត្ត (Speed):</strong> ទទួល Account ភ្លាមៗក្នុងរយៈពេលត្រឹមតែប៉ុន្មាននាទីក្រោយការទូទាត់។</div>
                </div>
            </div>

            <div style="text-align: center; margin-top: 36px;">
                <a href="https://t.me/netisnet" target="_blank" rel="noopener noreferrer" class="cyber-glow-btn" style="padding: 12px 28px; font-size: 15px;">
                    <i class="fab fa-telegram-plane"></i> ទាក់ទងយើងតាមរយៈ Telegram (@netisnet)
                </a>
            </div>
        </div>
    </div>

    <!-- 3D DNA Animation -->
    <script>
        const canvas = document.getElementById("dna-canvas");
        const ctx = canvas.getContext("2d");
        let width, height, rotationTime = 0;
        function resizeCanvas() { width = canvas.width = window.innerWidth; height = canvas.height = window.innerHeight; }
        window.addEventListener("resize", resizeCanvas);
        resizeCanvas();

        function renderDNA() {
            ctx.clearRect(0, 0, width, height);
            rotationTime += 0.015;
            const centerX = width / 2;
            const centerY = height / 2;
            const num = 45, radius = 140, len = 1600, fov = 600;

            for (let i = 0; i < num; i++) {
                const prog = i / num;
                const theta = prog * Math.PI * 7 + rotationTime;
                const rawY = (prog - 0.5) * len;
                const x1 = Math.cos(theta) * radius, z1 = Math.sin(theta) * radius;
                const x2 = Math.cos(theta + Math.PI) * radius, z2 = Math.sin(theta + Math.PI) * radius;
                const s1 = fov / (fov + z1 + 500), s2 = fov / (fov + z2 + 500);

                if (s1 > 0 && s2 > 0) {
                    const px1 = centerX + x1 * s1, py1 = centerY + rawY * s1;
                    const px2 = centerX + x2 * s2, py2 = centerY + rawY * s2;
                    const alpha = Math.max(0.15, Math.min(0.85, (z1 + 200) / 400));

                    ctx.beginPath();
                    ctx.moveTo(px1, py1); ctx.lineTo(px2, py2);
                    ctx.strokeStyle = `rgba(0, 240, 255, ${alpha * 0.7})`;
                    ctx.lineWidth = Math.max(1, 2.5 * s1);
                    ctx.stroke();

                    ctx.beginPath(); ctx.arc(px1, py1, Math.max(2, 5 * s1), 0, Math.PI * 2);
                    ctx.fillStyle = `rgba(0, 240, 255, ${alpha})`; ctx.fill();

                    ctx.beginPath(); ctx.arc(px2, py2, Math.max(2, 5 * s2), 0, Math.PI * 2);
                    ctx.fillStyle = `rgba(255, 42, 133, ${alpha})`; ctx.fill();
                }
            }
            requestAnimationFrame(renderDNA);
        }
        renderDNA();
    </script>
</body>

</html>
