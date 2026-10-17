<!DOCTYPE html>
<html lang="km">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>គណនីអេដមីន • Admin Login • DNA Cyber Edition</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" href="../home/logo/logome.png" type="image/x-icon">
    <link rel="stylesheet" href="../home/page.css">
    <style>
        .login-cyber-layer {
            position: relative;
            z-index: 10;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 16px;
        }

        .login-cyber-card {
            width: 100%;
            max-width: 440px;
            background: rgba(10, 16, 32, 0.78);
            backdrop-filter: blur(28px) saturate(200%);
            -webkit-backdrop-filter: blur(28px) saturate(200%);
            border: 1px solid var(--cyber-cyan);
            border-radius: 32px;
            padding: 40px 32px;
            box-shadow: 
                0 25px 60px -10px rgba(0, 0, 0, 0.9),
                0 0 35px rgba(157, 78, 221, 0.3),
                inset 0 1px 1px rgba(255, 255, 255, 0.4);
            text-align: center;
        }

        .form-control-cyber {
            width: 100%;
            padding: 13px 18px;
            border-radius: 18px;
            background: rgba(0, 240, 255, 0.06);
            border: 1px solid rgba(0, 240, 255, 0.25);
            color: #fff;
            font-family: inherit;
            font-size: 15px;
            outline: none;
            margin-bottom: 16px;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.3);
            transition: all 0.25s ease;
        }

        .form-control-cyber:focus {
            background: rgba(0, 240, 255, 0.12);
            border-color: var(--cyber-cyan);
            box-shadow: 0 0 16px rgba(0, 240, 255, 0.35);
        }
    </style>
</head>

<body>
    <!-- Live 3D DNA Canvas Background -->
    <canvas id="dna-canvas"></canvas>
    <div class="canvas-overlay-vignette"></div>
    <div class="cyber-grid-lines"></div>

    <div class="login-cyber-layer">
        <div class="login-cyber-card">
            <!-- Brand emblem -->
            <div class="brand-hologram-cube" style="width: 58px; height: 58px; margin: 0 auto 16px; border-radius: 18px;">
                <img src="../home/logo/icon.png" alt="NET" onerror="this.src='../home/logo/logome.png'">
            </div>

            <span class="dna-badge" style="margin-bottom: 10px; display: inline-flex;"><i class="fas fa-lock"></i> Secured Node</span>
            <h2 style="font-size: 26px; font-weight: 800; color: #fff; font-family: 'Space Grotesk', sans-serif; margin-bottom: 6px;">
                ផ្ទាំងគ្រប់គ្រង អេដមីន
            </h2>
            <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 24px;">
                សូមបញ្ចូលឈ្មោះគណនី និងលេខសំងាត់ដើម្បីចូលប្រព័ន្ធ
            </p>

            <form action="login.php" method="POST">
                <div style="text-align: left; margin-bottom: 6px;">
                    <label style="font-size: 12.5px; font-weight: 700; color: rgba(255,255,255,0.85);"><i class="fas fa-user" style="color:var(--cyber-cyan); margin-right: 4px;"></i> ឈ្មោះគណនី (Username)</label>
                </div>
                <input type="text" name="username" class="form-control-cyber" placeholder="Username..." required autocomplete="username">

                <div style="text-align: left; margin-bottom: 6px;">
                    <label style="font-size: 12.5px; font-weight: 700; color: rgba(255,255,255,0.85);"><i class="fas fa-key" style="color:var(--cyber-pink); margin-right: 4px;"></i> លេខសំងាត់ (Password)</label>
                </div>
                <input type="password" name="password" class="form-control-cyber" placeholder="Password..." required autocomplete="current-password">

                <button type="submit" class="cyber-buy-action" style="width: 100%; padding: 13px; margin-top: 10px; font-size: 15px;">
                    <i class="fas fa-arrow-right-to-bracket"></i> ចូលគណនី (Sign In)
                </button>
            </form>

            <div style="margin-top: 24px; border-top: 1px solid rgba(0,240,255,0.15); padding-top: 18px;">
                <a href="../home/index.php" class="cyber-glow-btn" style="padding: 8px 22px; font-size: 13px;">
                    <i class="fas fa-arrow-left"></i> ត្រឡប់ទៅទំព័រដើមវិញ
                </a>
            </div>
        </div>
    </div>

    <!-- 3D DNA Canvas Script -->
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
            const centerX = width / 2, centerY = height / 2;
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

                    ctx.beginPath(); ctx.moveTo(px1, py1); ctx.lineTo(px2, py2);
                    ctx.strokeStyle = `rgba(0, 240, 255, ${alpha * 0.7})`; ctx.lineWidth = Math.max(1, 2.5 * s1); ctx.stroke();
                    ctx.beginPath(); ctx.arc(px1, py1, Math.max(2, 5 * s1), 0, Math.PI * 2); ctx.fillStyle = `rgba(0, 240, 255, ${alpha})`; ctx.fill();
                    ctx.beginPath(); ctx.arc(px2, py2, Math.max(2, 5 * s2), 0, Math.PI * 2); ctx.fillStyle = `rgba(255, 42, 133, ${alpha})`; ctx.fill();
                }
            }
            requestAnimationFrame(renderDNA);
        }
        renderDNA();
    </script>
</body>

</html>