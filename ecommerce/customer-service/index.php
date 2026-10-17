<!DOCTYPE html>
<html lang="km">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ជំនួយអតិថិជន • Support • DNA Cyber Edition</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" href="../home/logo/logome.png" type="image/x-icon">
    <link rel="stylesheet" href="../home/page.css">
    <style>
        .support-cyber-layer {
            position: relative;
            z-index: 10;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 16px;
        }

        .support-cyber-card {
            width: 100%;
            max-width: 520px;
            background: rgba(10, 16, 32, 0.78);
            backdrop-filter: blur(28px) saturate(200%);
            -webkit-backdrop-filter: blur(28px) saturate(200%);
            border: 1px solid var(--cyber-cyan);
            border-radius: 32px;
            padding: 36px 30px;
            box-shadow: 
                0 25px 60px -10px rgba(0, 0, 0, 0.9),
                0 0 35px rgba(0, 240, 255, 0.25),
                inset 0 1px 1px rgba(255, 255, 255, 0.4);
        }

        .form-group {
            margin-bottom: 18px;
            text-align: left;
        }

        .form-group label {
            display: block;
            font-size: 13.5px;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.88);
            margin-bottom: 8px;
        }

        .form-control-cyber {
            width: 100%;
            padding: 13px 18px;
            border-radius: 16px;
            background: rgba(0, 240, 255, 0.06);
            border: 1px solid rgba(0, 240, 255, 0.25);
            color: #fff;
            font-family: inherit;
            font-size: 14.5px;
            outline: none;
            transition: all 0.25s ease;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.3);
        }

        .form-control-cyber:focus {
            background: rgba(0, 240, 255, 0.12);
            border-color: var(--cyber-cyan);
            box-shadow: 0 0 16px rgba(0, 240, 255, 0.35);
        }

        textarea.form-control-cyber {
            min-height: 110px;
            resize: vertical;
        }

        .alert-cyber {
            padding: 12px 18px;
            border-radius: 16px;
            font-size: 13.5px;
            font-weight: 700;
            margin-top: 18px;
            text-align: center;
        }

        .alert-success {
            background: rgba(0, 255, 170, 0.15);
            border: 1px solid var(--cyber-emerald);
            color: var(--cyber-emerald);
        }

        .alert-error {
            background: rgba(255, 42, 133, 0.15);
            border: 1px solid var(--cyber-pink);
            color: #ff66b2;
        }
    </style>
</head>

<body>
    <!-- Live 3D DNA Canvas Background -->
    <canvas id="dna-canvas"></canvas>
    <div class="canvas-overlay-vignette"></div>
    <div class="cyber-grid-lines"></div>

    <div class="support-cyber-layer">
        <div class="support-cyber-card">
            <!-- Header bar -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                <a href="../home/index.php" class="cyber-glow-btn" style="padding: 6px 16px; font-size: 13px;">
                    <i class="fas fa-arrow-left"></i> ត្រឡប់ក្រោយ
                </a>
                <span class="dna-badge" style="font-size: 11px; padding: 4px 10px;">
                    <i class="fas fa-satellite-dish"></i> Support 24/7
                </span>
            </div>

            <div style="text-align: center; margin-bottom: 24px;">
                <h2 style="font-size: 26px; font-weight: 800; color: #fff; font-family: 'Space Grotesk', sans-serif;">
                    ជំនួយ & ទំនាក់ទំនងអតិថិជន
                </h2>
                <p style="font-size: 13.5px; color: var(--text-muted); margin-top: 4px;">
                    សូមបញ្ចេញមតិ ឬស្នើសុំជំនួយពីក្រុមការងារបច្ចេកទេស
                </p>
            </div>

            <form method="POST" action="submit-form.php">
                <div class="form-group">
                    <label><i class="fas fa-user" style="color: var(--cyber-cyan); margin-right: 6px;"></i> ឈ្មោះរបស់អ្នក (Name)</label>
                    <input type="text" name="name" class="form-control-cyber" placeholder="ឧទាហរណ៍: Sokha" required>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-envelope" style="color: var(--cyber-purple); margin-right: 6px;"></i> អ៊ីម៉ែល ឬ Telegram ID</label>
                    <input type="email" name="email" class="form-control-cyber" placeholder="ឧទាហរណ៍: sokha@gmail.com" required>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-comment-dots" style="color: var(--cyber-pink); margin-right: 6px;"></i> មតិយោបល់ ឬសំណួររបស់អ្នក (Message)</label>
                    <textarea name="message" class="form-control-cyber" placeholder="សូមសរសេរសារ ឬបញ្ហារបស់លោកអ្នកនៅទីនេះ..." required></textarea>
                </div>

                <button type="submit" class="cyber-buy-action" style="width: 100%; padding: 13px; margin-top: 8px;">
                    <i class="fas fa-paper-plane"></i> បញ្ជូនសារ (Send Message)
                </button>
            </form>

            <?php
            if (isset($_GET['success'])) {
                echo '<div class="alert-cyber alert-success"><i class="fas fa-check-circle"></i> សាររបស់អ្នកត្រូវបានបញ្ជូនដោយជោគជ័យ!</div>';
            }
            if (isset($_GET['error'])) {
                echo '<div class="alert-cyber alert-error"><i class="fas fa-triangle-exclamation"></i> មានបញ្ហាក្នុងការបញ្ជូនសារ សូមព្យាយាមម្តងទៀត។</div>';
            }
            ?>

            <div style="text-align: center; margin-top: 24px; border-top: 1px solid rgba(0,240,255,0.15); padding-top: 18px;">
                <p style="font-size: 13px; color: var(--text-dim); margin-bottom: 10px;">
                    ឬទាក់ទងមក Admin ផ្ទាល់ 24/7៖
                </p>
                <a href="https://t.me/netisnet" target="_blank" rel="noopener noreferrer" class="cyber-glow-btn" style="width: 100%; padding: 11px; justify-content: center;">
                    <i class="fab fa-telegram-plane"></i> Telegram Support (@netisnet)
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