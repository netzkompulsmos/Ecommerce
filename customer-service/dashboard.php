<?php
include 'db.php';
$result = $conn->query("SELECT * FROM messages ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="km">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>សាររបស់អតិថិជន • Customer Messages</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="icon" href="../home/logo/logome.png" type="image/x-icon">
  <link rel="apple-touch-icon" href="../home/logo/logome.png">
  <link rel="stylesheet" href="../home/page.css?v=<?php echo filemtime(__DIR__ . '/../home/page.css'); ?>">
  <style>
    .dashboard-container {
      max-width: 1140px;
      margin: 40px auto 60px;
      padding: 0 20px;
      position: relative;
      z-index: 10;
    }

    .dashboard-header {
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

    .dashboard-table-card {
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
      background: rgba(255, 255, 255, 0.06);
      border-bottom: 1px solid rgba(99, 102, 241, 0.25);
    }

    th {
      padding: 16px 20px;
      font-size: 13.5px;
      font-weight: 700;
      color: var(--cyber-cyan);
      text-transform: uppercase;
      letter-spacing: 0.05em;
    }

    td {
      padding: 16px 20px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.06);
      color: rgba(255, 255, 255, 0.88);
      font-size: 14px;
      vertical-align: middle;
    }

    tbody tr:hover {
      background: rgba(255, 255, 255, 0.04);
    }

    .btn-delete {
      background: rgba(239, 68, 68, 0.15);
      border: 1px solid rgba(239, 68, 68, 0.4);
      color: #f87171;
      padding: 7px 16px;
      border-radius: 9999px;
      font-size: 12.5px;
      font-weight: 700;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      transition: all 0.2s ease;
    }

    .btn-delete:hover {
      background: rgba(239, 68, 68, 0.3);
      border-color: #f87171;
      color: #fff;
    }

    @media screen and (max-width: 768px) {
      .dashboard-table-card {
        overflow-x: auto;
      }
      table {
        min-width: 650px;
      }
    }
  </style>
</head>

<body>
  <div class="cyber-grid-lines"></div>

  <div class="dashboard-container">
    <div class="dashboard-header">
      <div>
        <span class="dna-badge" style="display:inline-flex; margin-bottom: 6px;">
          <i class="fas fa-inbox"></i> Customer Support Hub
        </span>
        <h2 style="font-size: 24px; font-weight: 800; color: #fff;">
          សាររបស់អតិថិជន (Messages)
        </h2>
      </div>

      <div style="display: flex; gap: 10px; flex-wrap: wrap;">
        <a href="../admin/index.php" class="cyber-glow-btn" style="padding: 8px 18px; font-size: 13.5px;">
          <i class="fas fa-arrow-left"></i> ត្រឡប់ក្រោយ (Admin)
        </a>
        <a href="../home/index.php" class="cyber-glow-btn" style="padding: 8px 18px; font-size: 13.5px; border-color: rgba(255,255,255,0.3); background: rgba(255,255,255,0.06);">
          <i class="fas fa-globe"></i> មើលទំព័រមុខ
        </a>
      </div>
    </div>

    <div class="dashboard-table-card">
      <table>
        <thead>
          <tr>
            <th style="width: 60px;">ល.រ</th>
            <th style="width: 160px;">ឈ្មោះ</th>
            <th style="width: 200px;">អ៊ីម៉ែល / Contact</th>
            <th>សាររបស់អតិថិជន</th>
            <th style="width: 170px;">កាលបរិច្ឆេទ</th>
            <th style="width: 110px; text-align: center;">សកម្មភាព</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()) { ?>
              <tr>
                <td style="font-weight: 700; color: var(--cyber-cyan);"><?= $row['id'] ?></td>
                <td style="font-weight: 700; color: #fff;"><?= htmlspecialchars($row['name']) ?></td>
                <td style="color: var(--cyber-purple); font-weight: 600;"><?= htmlspecialchars($row['email']) ?></td>
                <td style="color: rgba(255, 255, 255, 0.85); line-height: 1.5;"><?= htmlspecialchars($row['message']) ?></td>
                <td style="color: var(--text-dim); font-size: 12.5px;"><?= $row['created_at'] ?></td>
                <td style="text-align: center;">
                  <form action="delete-message.php" method="POST" style="display:inline;">
                    <input type="hidden" name="message_id" value="<?= $row['id'] ?>">
                    <button type="submit" class="btn-delete" onclick="return confirm('តើអ្នកពិតជាចង់លុបសារនេះមែនទេ?')">
                      <i class="fas fa-trash"></i> លុប
                    </button>
                  </form>
                </td>
              </tr>
            <?php } ?>
          <?php else: ?>
            <tr>
              <td colspan="6" style="text-align: center; padding: 40px; color: var(--text-muted);">
                មិនទាន់មានសារពីអតិថិជននៅឡើយទេ (No messages received)
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>

</html>