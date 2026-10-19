<?php
include '../api/db.php';
$sql = "SELECT * FROM products ORDER BY id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="km">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>គ្រប់គ្រងព័ត៏មានកម្មវិធី • Admin Dashboard • iOS 27 Liquid Glass</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" href="../home/logo/logome.png" type="image/x-icon">
    <link rel="stylesheet" href="../home/page.css?v=<?php echo filemtime(__DIR__ . '/../home/page.css'); ?>">
    <style>
        .admin-dashboard-container {
            max-width: 1140px;
            margin: 40px auto 60px;
            padding: 0 20px;
            position: relative;
            z-index: 10;
        }

        .admin-header-glass {
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.88) 0%, rgba(30, 27, 75, 0.8) 50%, rgba(15, 23, 42, 0.92) 100%);
            backdrop-filter: blur(28px) saturate(180%);
            -webkit-backdrop-filter: blur(28px) saturate(180%);
            border: 1px solid rgba(99, 102, 241, 0.35);
            border-radius: 28px;
            padding: 24px 30px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.3), 0 20px 40px rgba(0, 0, 0, 0.6);
        }

        .admin-table-glass {
            width: 100%;
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.85) 0%, rgba(20, 25, 55, 0.8) 100%);
            backdrop-filter: blur(28px) saturate(180%);
            -webkit-backdrop-filter: blur(28px) saturate(180%);
            border: 1px solid rgba(99, 102, 241, 0.28);
            border-radius: 28px;
            overflow: hidden;
            box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.3), 0 30px 60px -10px rgba(0, 0, 0, 0.65);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        thead {
            background: rgba(255, 255, 255, 0.08);
            border-bottom: 1px solid rgba(255, 255, 255, 0.16);
        }

        th {
            padding: 16px 20px;
            font-size: 13.5px;
            font-weight: 700;
            color: #00f2fe;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        td {
            padding: 16px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            color: rgba(255, 255, 255, 0.88);
            font-size: 14px;
            vertical-align: middle;
        }

        tbody tr:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .app-icon-img {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            object-fit: cover;
            border: 1px solid rgba(255, 255, 255, 0.25);
            background: rgba(255, 255, 255, 0.08);
        }

        .btn-table {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 9999px;
            font-size: 12.5px;
            font-weight: 600;
            text-decoration: none;
            border: 1px solid transparent;
            transition: all 0.2s ease;
        }

        .btn-edit {
            background: rgba(0, 242, 254, 0.15);
            border-color: rgba(0, 242, 254, 0.4);
            color: #00f2fe;
        }

        .btn-edit:hover {
            background: rgba(0, 242, 254, 0.3);
            transform: translateY(-2px);
        }

        .btn-delete {
            background: rgba(255, 68, 68, 0.15);
            border-color: rgba(255, 68, 68, 0.4);
            color: #ff5555;
            margin-left: 6px;
        }

        .btn-delete:hover {
            background: rgba(255, 68, 68, 0.3);
            transform: translateY(-2px);
        }
    </style>
</head>

<body>
    <div class="cyber-grid-lines"></div>

    <div class="admin-dashboard-container">
        <!-- Top bar -->
        <div class="admin-header-glass">
            <div>
                <span class="section-tag"><i class="fas fa-shield-alt"></i> Control Center</span>
                <h2 style="font-size: 24px; font-weight: 800; color: #fff; margin-top: 4px;">គ្រប់គ្រងព័ត៏មានកម្មវិធី (Products)</h2>
            </div>
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <a href="add-product.php" class="ios-glass-btn btn-aurora">
                    <i class="fas fa-plus"></i> + បន្ថែមកម្មវិធីថ្មី
                </a>
                <a href="../customer-service/dashboard.php" class="ios-glass-btn">
                    <i class="fas fa-comments"></i> មើលសារអតិថិជន
                </a>
                <a href="../home/index.php" class="ios-glass-btn">
                    <i class="fas fa-external-link-alt"></i> មើលទំព័រមុខ
                </a>
                <a href="../admin-login/logout.php" class="ios-glass-btn" style="border-color: rgba(255,75,75,0.4); color: #ff6b6b;">
                    <i class="fas fa-sign-out-alt"></i> ចាកចេញ
                </a>
            </div>
        </div>

        <!-- Table -->
        <div class="admin-table-glass">
            <table>
                <thead>
                    <tr>
                        <th style="width: 60px;">ល.រ</th>
                        <th style="width: 80px;">រូបភាព</th>
                        <th>ឈ្មោះកម្មវិធី</th>
                        <th>ព័ត៌មានលម្អិត</th>
                        <th>តម្លៃ</th>
                        <th style="width: 180px; text-align: center;">សកម្មភាព</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td style="font-weight: 700; color: #00f2fe;"><?= $row['id'] ?></td>
                                <td>
                                    <?php if (!empty($row['image'])): ?>
                                        <img src="../<?= htmlspecialchars($row['image']) ?>" class="app-icon-img" alt="<?= htmlspecialchars($row['name']) ?>">
                                    <?php else: ?>
                                        <div class="app-icon-img" style="display:flex;align-items:center;justify-content:center;color:#666;">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td style="font-weight: 700; color: #fff;"><?= htmlspecialchars($row['name']) ?></td>
                                <td style="color: var(--text-muted); font-size: 13px;"><?= htmlspecialchars($row['description'] ?? '') ?></td>
                                <td>
                                    <span style="display:inline-block; padding: 4px 10px; border-radius: 9999px; background: rgba(0,245,160,0.15); border: 1px solid rgba(0,245,160,0.3); color: #00f5a0; font-weight: 700;">
                                        $<?= number_format($row['price'], 2) ?>
                                    </span>
                                </td>
                                <td style="text-align: center;">
                                    <a class="btn-table btn-edit" href="edit-product.php?id=<?= $row['id'] ?>">
                                        <i class="fas fa-pen"></i> កែប្រែ
                                    </a>
                                    <a class="btn-table btn-delete" href="delete-product.php?id=<?= $row['id'] ?>" onclick="return confirm('តើអ្នកចង់លុបកម្មវីធីនេះមែនឬ?')">
                                        <i class="fas fa-trash"></i> លុប
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 40px; color: var(--text-muted);">
                                មិនទាន់មានទិន្នន័យកម្មវិធីនៅឡើយទេ (No products found)
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>