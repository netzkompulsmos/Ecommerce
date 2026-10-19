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

        .delete-confirmation-backdrop {
            position: fixed;
            inset: 0;
            z-index: 2000;
            display: grid;
            place-items: center;
            padding: 20px;
            background: rgba(3, 6, 17, 0.76);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }

        .delete-confirmation-backdrop[hidden] {
            display: none;
        }

        .delete-confirmation-dialog {
            width: min(100%, 420px);
            padding: 30px;
            border: 1px solid rgba(255, 85, 85, 0.55);
            border-radius: 24px;
            background: linear-gradient(145deg, rgba(19, 25, 45, 0.99), rgba(10, 15, 30, 0.99));
            box-shadow: 0 24px 70px rgba(0, 0, 0, 0.6), 0 0 28px rgba(255, 68, 68, 0.16);
            color: #fff;
            text-align: center;
        }

        .delete-warning-icon {
            display: grid;
            width: 64px;
            height: 64px;
            place-items: center;
            margin: 0 auto 18px;
            border: 1px solid rgba(255, 85, 85, 0.4);
            border-radius: 50%;
            background: rgba(255, 68, 68, 0.13);
            color: #ff6262;
            font-size: 26px;
        }

        .delete-confirmation-dialog h2 {
            margin-bottom: 10px;
            color: #fff;
            font-size: 21px;
        }

        .delete-confirmation-dialog p {
            color: rgba(226, 232, 240, 0.78);
            font-size: 14px;
            line-height: 1.65;
        }

        .delete-product-name {
            display: block;
            margin: 12px 0 22px;
            overflow-wrap: anywhere;
            color: #fff;
            font-weight: 700;
        }

        .delete-confirmation-actions {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .delete-confirmation-actions button {
            min-height: 42px;
            padding: 0 18px;
            border: 1px solid transparent;
            border-radius: 9999px;
            font: inherit;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
        }

        .delete-cancel-button {
            border-color: rgba(148, 163, 184, 0.35) !important;
            background: rgba(148, 163, 184, 0.12);
            color: #e2e8f0;
        }

        .delete-confirm-button {
            background: #dc3d4b;
            color: #fff;
        }

        .delete-cancel-button:hover {
            background: rgba(148, 163, 184, 0.22);
        }

        .delete-confirm-button:hover {
            background: #f04452;
        }

        @media (max-width: 480px) {
            .delete-confirmation-dialog {
                padding: 24px 18px;
            }

            .delete-confirmation-actions {
                flex-direction: column-reverse;
            }
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
                                    <a class="btn-table btn-delete" href="delete-product.php?id=<?= (int) $row['id'] ?>" data-delete-product="<?= htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') ?>">
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
    <div class="delete-confirmation-backdrop" id="deleteConfirmation" hidden>
        <section class="delete-confirmation-dialog" role="alertdialog" aria-modal="true" aria-labelledby="deleteConfirmationTitle" aria-describedby="deleteConfirmationMessage" tabindex="-1">
            <div class="delete-warning-icon"><i class="fas fa-trash-can"></i></div>
            <h2 id="deleteConfirmationTitle">បញ្ជាក់ការលុបកម្មវិធី</h2>
            <p id="deleteConfirmationMessage">តើអ្នកពិតជាចង់លុបកម្មវិធីនេះមែនទេ? សកម្មភាពនេះមិនអាចត្រឡប់វិញបានទេ។</p>
            <span class="delete-product-name" id="deleteProductName"></span>
            <div class="delete-confirmation-actions">
                <button type="button" class="delete-cancel-button" id="cancelProductDelete">បោះបង់</button>
                <button type="button" class="delete-confirm-button" id="confirmProductDelete"><i class="fas fa-trash"></i> លុបកម្មវិធី</button>
            </div>
        </section>
    </div>
    <script>
        (() => {
            const modal = document.getElementById('deleteConfirmation');
            const dialog = modal.querySelector('[role="alertdialog"]');
            const productName = document.getElementById('deleteProductName');
            const cancelButton = document.getElementById('cancelProductDelete');
            const confirmButton = document.getElementById('confirmProductDelete');
            let pendingDeleteUrl = '';
            let previouslyFocusedElement = null;

            const closeModal = () => {
                modal.hidden = true;
                pendingDeleteUrl = '';
                if (previouslyFocusedElement) previouslyFocusedElement.focus();
            };

            document.querySelectorAll('[data-delete-product]').forEach((link) => {
                link.addEventListener('click', (event) => {
                    event.preventDefault();
                    pendingDeleteUrl = link.href;
                    previouslyFocusedElement = link;
                    productName.textContent = link.dataset.deleteProduct;
                    modal.hidden = false;
                    cancelButton.focus();
                });
            });

            cancelButton.addEventListener('click', closeModal);
            confirmButton.addEventListener('click', () => {
                if (pendingDeleteUrl) window.location.href = pendingDeleteUrl;
            });
            modal.addEventListener('click', (event) => {
                if (event.target === modal) closeModal();
            });
            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && !modal.hidden) closeModal();
            });
        })();
    </script>
</body>

</html>