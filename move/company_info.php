<?php
session_start();
if (!isset($_SESSION['loggedin'])) { header('Location: /Drifter/front/login.php'); exit; }
require_once 'db_connect.php';
$navActive = '';
include '../includes/navbar.php';

$username = $_SESSION['username'];
$stmt = $pdo->prepare('SELECT * FROM companies WHERE owner_username=? ORDER BY created_at DESC');
$stmt->execute([$username]);
$companies = $stmt->fetchAll(PDO::FETCH_ASSOC);

$typeLabels = ['packing'=>'Packing Only','moving'=>'Moving Only','packing_moving'=>'Pack + Move','full_service'=>'Full Service','vehicle'=>'Vehicle Transport','international'=>'International'];
$propLabels = ['1bhk'=>'1 BHK','2bhk'=>'2 BHK','3bhk'=>'3 BHK','villa'=>'Villa','office'=>'Office'];

$reqStmt = $pdo->prepare("SELECT COUNT(*) FROM user_requests WHERE status='Pending' AND company_id IN (SELECT id FROM companies WHERE owner_username=?)");
$reqStmt->execute([$username]);
$pendingCount = $reqStmt->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>My Movers Companies — Drifter</title>
<style>
.page-hero{
  padding:50px 24px 30px;text-align:center;color:white;
  position:relative;overflow:hidden;
}
.page-hero::before{
  content:'';position:absolute;inset:0;
  background:url('https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=1920&q=80') center/cover no-repeat;
  filter:brightness(0.20) saturate(1.1);
}
.page-hero::after{
  content:'';position:absolute;inset:0;
  background:linear-gradient(135deg,rgba(45,98,53,0.93) 0%,rgba(168,223,142,0.40) 100%);
}
.page-hero h1{font-size:1.8rem;font-weight:800;margin-bottom:6px;position:relative;z-index:1;}
.page-hero p{color:rgba(255,255,255,0.80);position:relative;z-index:1;}
.wrap{max-width:1280px;margin:0 auto;padding:40px 24px 80px;}
.dash-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:32px;flex-wrap:wrap;gap:16px;}
.dash-header h2{font-size:1.3rem;font-weight:700;}
.add-btn{display:inline-flex;align-items:center;gap:8px;padding:11px 22px;background:linear-gradient(135deg,#3a7d44,#2d6235);color:white;border-radius:10px;text-decoration:none;font-weight:600;font-size:0.9rem;transition:var(--trans);}
.add-btn:hover{transform:translateY(-2px);box-shadow:0 6px 20px rgba(58,125,68,0.38);}
.companies-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(380px,1fr));gap:24px;}
.c-card{background:white;border-radius:16px;overflow:hidden;box-shadow:var(--shadow);}
.c-logo{width:100%;height:160px;object-fit:cover;}
.c-logo-ph{width:100%;height:160px;background:linear-gradient(135deg,#F0FFDF,#A8DF8E);display:flex;align-items:center;justify-content:center;font-size:3.5rem;}
.c-body{padding:20px;}
.c-name{font-size:1.1rem;font-weight:700;margin-bottom:6px;}
.c-meta{display:flex;flex-direction:column;gap:4px;margin-bottom:14px;}
.c-meta-item{font-size:0.82rem;color:var(--muted);display:flex;align-items:center;gap:6px;}
.c-meta-item i{color:#3a7d44;width:14px;}
.c-desc{font-size:0.85rem;color:var(--muted);line-height:1.6;margin-bottom:14px;}
.loc-tags{display:flex;flex-wrap:wrap;gap:6px;margin-bottom:14px;}
.loc-tag{padding:3px 10px;background:#F0FFDF;color:#2d6235;border-radius:50px;font-size:0.75rem;font-weight:600;}
.svc-section{border-top:1px solid #f1f5f9;padding-top:14px;margin-top:14px;}
.svc-title{font-size:0.82rem;font-weight:700;color:var(--text);margin-bottom:10px;}
.svc-row{display:flex;justify-content:space-between;align-items:center;padding:6px 0;border-bottom:1px solid #f8fafc;font-size:0.82rem;}
.svc-row:last-child{border-bottom:none;}
.svc-row span{color:var(--muted);}
.svc-row strong{color:var(--text);}
.requests-section{border-top:1px solid #f1f5f9;padding-top:14px;margin-top:14px;}
.req-row{display:grid;grid-template-columns:1fr 1fr auto;gap:8px;padding:8px 0;border-bottom:1px solid #f8fafc;font-size:0.8rem;align-items:center;}
.req-row:last-child{border-bottom:none;}
.status-pill{padding:3px 10px;border-radius:50px;font-size:0.72rem;font-weight:700;}
.status-pill.pending{background:#fef3c7;color:#92400e;}
.status-pill.assigned{background:#F0FFDF;color:#2d6235;}
.status-pill.completed{background:#A8DF8E;color:#1e3a22;}
.status-pill.cancelled{background:#FFD8DF;color:#7a1a2a;}
.empty-state{text-align:center;padding:80px 24px;background:white;border-radius:16px;box-shadow:var(--shadow);}
.empty-state .icon{font-size:4rem;margin-bottom:16px;}
.empty-state h3{font-size:1.4rem;font-weight:700;margin-bottom:8px;}
.empty-state p{color:var(--muted);margin-bottom:24px;}
</style>
</head>
<body>
<div class="page-hero">
  <h1>🚛 My Packers & Movers Companies</h1>
  <p>Manage your registered companies and view relocation requests</p>
</div>
<div class="wrap">
  <div class="dash-header">
    <h2>Your Companies (<?= count($companies) ?>)
      <?php if ($pendingCount > 0): ?>
        <span style="background:#fef3c7;color:#92400e;padding:3px 10px;border-radius:50px;font-size:0.75rem;margin-left:8px;"><?= $pendingCount ?> pending requests</span>
      <?php endif; ?>
    </h2>
    <a href="add_company.php" class="add-btn"><i class="fas fa-plus"></i> Add Company</a>
  </div>

  <?php if (empty($companies)): ?>
    <div class="empty-state">
      <div class="icon">🚛</div>
      <h3>No Companies Yet</h3>
      <p>Register your packers & movers company to start receiving relocation requests.</p>
      <a href="add_company.php" class="add-btn"><i class="fas fa-plus"></i> Register Company</a>
    </div>
  <?php else: ?>
    <div class="companies-grid">
      <?php foreach ($companies as $c):
        $svcStmt = $pdo->prepare('SELECT * FROM services WHERE company_id=?');
        $svcStmt->execute([$c['id']]);
        $services = $svcStmt->fetchAll(PDO::FETCH_ASSOC);

        $reqStmt2 = $pdo->prepare('SELECT * FROM user_requests WHERE company_id=? ORDER BY created_at DESC LIMIT 5');
        $reqStmt2->execute([$c['id']]);
        $requests = $reqStmt2->fetchAll(PDO::FETCH_ASSOC);
      ?>
      <div class="c-card">
        <?php if ($c['logo_path']): ?>
          <img src="/Drifter/move/<?= htmlspecialchars($c['logo_path']) ?>" class="c-logo" alt="Logo">
        <?php else: ?>
          <div class="c-logo-ph">🚛</div>
        <?php endif; ?>
        <div class="c-body">
          <div class="c-name"><?= htmlspecialchars($c['name']) ?></div>
          <div class="c-meta">
            <div class="c-meta-item"><i class="fas fa-envelope"></i><?= htmlspecialchars($c['email']) ?></div>
            <div class="c-meta-item"><i class="fas fa-phone"></i><?= htmlspecialchars($c['phone']) ?></div>
            <div class="c-meta-item"><i class="fas fa-map-marker-alt"></i><?= htmlspecialchars($c['address']) ?></div>
          </div>
          <div class="c-desc"><?= htmlspecialchars($c['description']) ?></div>
          <div class="loc-tags">
            <?php foreach (array_map('trim', explode(',', $c['service_locations'])) as $loc): ?>
              <span class="loc-tag"><?= htmlspecialchars($loc) ?></span>
            <?php endforeach; ?>
          </div>
          <?php if (!empty($services)): ?>
          <div class="svc-section">
            <div class="svc-title"><i class="fas fa-tags"></i> Pricing</div>
            <?php foreach ($services as $s): ?>
            <div class="svc-row">
              <span><?= ($typeLabels[$s['service_type']] ?? $s['service_type']) . ' · ' . ($propLabels[$s['property_type']] ?? '') ?></span>
              <strong>₹<?= number_format($s['min_price']) ?> – ₹<?= number_format($s['max_price']) ?></strong>
            </div>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>
          <?php if (!empty($requests)): ?>
          <div class="requests-section">
            <div class="svc-title"><i class="fas fa-inbox"></i> Recent Requests (<?= count($requests) ?>)</div>
            <?php foreach ($requests as $r): ?>
            <div class="req-row">
              <div><strong><?= htmlspecialchars($r['customer_name'] ?? 'Customer') ?></strong><br><span style="color:var(--muted)"><?= htmlspecialchars(substr($r['current_address'],0,25)) ?>...</span></div>
              <div><?= date('d M', strtotime($r['moving_date'])) ?><br><span style="color:var(--muted)"><?= $propLabels[$r['property_type']] ?? '' ?></span></div>
              <span class="status-pill <?= strtolower($r['status']) ?>"><?= $r['status'] ?></span>
            </div>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
<?php include '../includes/footer.php'; ?>
</body>
</html>
