<?php
session_start();
if (!isset($_SESSION['loggedin']))         { header("Location: /Drifter/front/login.php"); exit; }
if (($_SESSION['role'] ?? '') !== 'owner') { header("Location: /Drifter/front/index.php"); exit; }

$conn = new mysqli("localhost","root","","db");
if ($conn->connect_error) die('DB Error: '.$conn->connect_error);
$username = $_SESSION['username'];

$eRow = $conn->query("SELECT email FROM signup WHERE username='".mysqli_real_escape_string($conn,$username)."' LIMIT 1")->fetch_assoc();
$ownerEmail = $eRow['email'] ?? $username;

$vstmt = $conn->prepare("SELECT * FROM vehicles WHERE (owner_name=? OR owner_name=?) AND vehicle_category='travel' ORDER BY created_at DESC");
$vstmt->bind_param("ss",$username,$ownerEmail); $vstmt->execute();
$vehicles = $vstmt->get_result()->fetch_all(MYSQLI_ASSOC);

$allBookings = [];
if (!empty($vehicles)) {
    $vids = implode(',', array_map(fn($v)=>(int)$v['id'], $vehicles));
    $bAll = $conn->query("SELECT b.*, v.owner_name, v.capacity, v.rate_per_km FROM booking b JOIN vehicles v ON b.vehicle_id=v.id WHERE b.vehicle_id IN ($vids) ORDER BY b.id DESC");
    $allBookings = $bAll->fetch_all(MYSQLI_ASSOC);
}

$total     = count($allBookings);
$pending   = count(array_filter($allBookings, fn($b)=>$b['status']==='Pending'));
$confirmed = count(array_filter($allBookings, fn($b)=>$b['status']==='Confirmed'));
$cancelled = count(array_filter($allBookings, fn($b)=>$b['status']==='Cancelled'));
$revenue   = array_sum(array_map(fn($b)=>$b['status']==='Confirmed'?(float)$b['total_cost']:0, $allBookings));

$navActive = '';
include '../includes/navbar.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Owner Dashboard — Travel — Drifter</title>
<style>
.page-hero{padding:48px 24px 36px;text-align:center;color:white;position:relative;overflow:hidden;background:#212529;}
.page-hero::after{content:'';position:absolute;inset:0;background:url('https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?w=1920&q=80') center/cover no-repeat;filter:brightness(0.12);z-index:0;}
.page-hero h1{font-size:1.7rem;font-weight:800;margin-bottom:4px;position:relative;z-index:1;}
.page-hero p{color:rgba(255,255,255,0.55);font-size:0.88rem;position:relative;z-index:1;}
.hero-tabs{position:relative;z-index:1;display:flex;gap:10px;justify-content:center;margin-top:18px;}
.hero-tab{padding:7px 18px;border-radius:8px;text-decoration:none;font-size:0.84rem;font-weight:600;border:2px solid rgba(255,255,255,0.22);color:rgba(255,255,255,0.65);transition:all 0.2s;}
.hero-tab:hover,.hero-tab.active{background:#FF6B00;border-color:#FF6B00;color:white;}
.stats-row{max-width:1280px;margin:-28px auto 0;padding:0 24px;display:grid;grid-template-columns:repeat(5,1fr);gap:14px;position:relative;z-index:2;}
.stat-card{background:white;border-radius:12px;padding:18px 16px;box-shadow:0 4px 20px rgba(0,0,0,0.10);border-top:3px solid #e9ecef;text-align:center;transition:transform 0.2s,box-shadow 0.2s;}
.stat-card:hover{transform:translateY(-3px);box-shadow:0 8px 28px rgba(0,0,0,0.13);}
.stat-card:nth-child(1){border-top-color:#FF6B00;}
.stat-card:nth-child(2){border-top-color:#f59e0b;}
.stat-card:nth-child(3){border-top-color:#22c55e;}
.stat-card:nth-child(4){border-top-color:#ef4444;}
.stat-card:nth-child(5){border-top-color:#FFC107;}
.stat-icon{font-size:1.4rem;margin-bottom:6px;}
.stat-num{font-size:1.6rem;font-weight:800;color:#212529;line-height:1;}
.stat-lbl{font-size:0.72rem;color:#6c757d;margin-top:3px;font-weight:500;}
.dash-wrap{max-width:1280px;margin:32px auto 80px;padding:0 24px;}
.section-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;flex-wrap:wrap;gap:12px;}
.section-head h2{font-size:1.1rem;font-weight:700;color:#212529;display:flex;align-items:center;gap:8px;}
.section-head h2 i{color:#FF6B00;}
.add-btn{display:inline-flex;align-items:center;gap:7px;padding:9px 18px;background:#FF6B00;color:white;border-radius:8px;text-decoration:none;font-weight:600;font-size:0.85rem;transition:all 0.2s;box-shadow:0 3px 10px rgba(255,107,0,0.30);}
.add-btn:hover{background:#e05e00;transform:translateY(-2px);}
.tab-bar{display:flex;gap:4px;background:white;border-radius:10px;padding:5px;box-shadow:0 2px 12px rgba(0,0,0,0.07);margin-bottom:20px;flex-wrap:wrap;}
.tab-btn{flex:1;min-width:100px;padding:8px 14px;border:none;border-radius:7px;font-size:0.82rem;font-weight:600;cursor:pointer;background:transparent;color:#6c757d;transition:all 0.2s;font-family:inherit;display:flex;align-items:center;justify-content:center;gap:6px;}
.tab-btn.active{background:#FF6B00;color:white;box-shadow:0 3px 10px rgba(255,107,0,0.30);}
.tab-btn .cnt{background:rgba(255,107,0,0.12);color:#FF6B00;border-radius:50px;padding:1px 7px;font-size:0.68rem;font-weight:700;}
.tab-btn.active .cnt{background:rgba(255,255,255,0.25);color:white;}
.tab-panel{display:none;} .tab-panel.active{display:block;}
.bookings-table{width:100%;border-collapse:collapse;background:white;border-radius:12px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,0.07);}
.bookings-table th{background:#f8f9fa;padding:11px 14px;text-align:left;font-size:0.75rem;font-weight:700;color:#6c757d;text-transform:uppercase;letter-spacing:0.5px;border-bottom:1px solid #e9ecef;}
.bookings-table td{padding:12px 14px;font-size:0.82rem;color:#212529;border-bottom:1px solid #f8f9fa;vertical-align:middle;}
.bookings-table tr:last-child td{border-bottom:none;}
.bookings-table tr:hover td{background:#fafafa;}
.customer-name{font-weight:600;color:#212529;}
.route-text{color:#6c757d;font-size:0.75rem;margin-top:2px;}
.status-pill{padding:3px 10px;border-radius:50px;font-size:0.70rem;font-weight:700;white-space:nowrap;display:inline-block;}
.status-pill.pending{background:#fef3c7;color:#92400e;}
.status-pill.confirmed{background:#dcfce7;color:#166534;}
.status-pill.cancelled{background:#fee2e2;color:#991b1b;}
.action-btns{display:flex;gap:5px;flex-wrap:wrap;}
.act-btn{padding:5px 11px;border:none;border-radius:6px;font-size:0.72rem;font-weight:700;cursor:pointer;transition:all 0.18s;font-family:inherit;display:inline-flex;align-items:center;gap:4px;}
.act-btn.confirm{background:#dcfce7;color:#166534;}
.act-btn.confirm:hover{background:#bbf7d0;}
.act-btn.cancel{background:#fee2e2;color:#991b1b;}
.act-btn.cancel:hover{background:#fecaca;}
.act-btn:disabled{opacity:0.5;cursor:not-allowed;}
.empty-row td{text-align:center;padding:40px;color:#6c757d;font-style:italic;}
.vehicles-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:18px;}
.v-card{background:white;border-radius:12px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,0.07);border:1px solid #e9ecef;transition:transform 0.22s,box-shadow 0.22s;}
.v-card:hover{transform:translateY(-4px);box-shadow:0 8px 28px rgba(0,0,0,0.12);}
.v-img{width:100%;height:170px;object-fit:cover;}
.v-img-ph{width:100%;height:170px;background:linear-gradient(135deg,#f8f9fa,#e9ecef);display:flex;align-items:center;justify-content:center;font-size:2.8rem;}
.v-body{padding:16px;}
.v-top{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:10px;}
.v-name{font-size:0.95rem;font-weight:700;color:#212529;}
.avail-badge{padding:3px 10px;border-radius:50px;font-size:0.70rem;font-weight:700;}
.avail-badge.on{background:#dcfce7;color:#166534;border:1px solid #86efac;}
.avail-badge.off{background:#fee2e2;color:#991b1b;border:1px solid #fca5a5;}
.v-meta{display:grid;grid-template-columns:1fr 1fr;gap:6px;margin-bottom:12px;background:#f8f9fa;border-radius:8px;padding:10px;}
.v-meta-item{font-size:0.75rem;color:#6c757d;}
.v-meta-item strong{display:block;color:#212529;font-size:0.82rem;margin-bottom:1px;}
.toggle-btn{width:100%;padding:8px;border:none;border-radius:7px;font-weight:600;font-size:0.82rem;cursor:pointer;transition:all 0.2s;font-family:inherit;}
.toggle-btn.on{background:#fee2e2;color:#991b1b;}
.toggle-btn.on:hover{background:#fecaca;}
.toggle-btn.off{background:#dcfce7;color:#166534;}
.toggle-btn.off:hover{background:#bbf7d0;}
.toggle-btn:disabled{opacity:0.6;cursor:not-allowed;}
.success-banner{background:#f0fdf4;border:1px solid #86efac;border-radius:10px;padding:12px 16px;margin-bottom:20px;font-weight:600;color:#166534;display:flex;align-items:center;gap:8px;font-size:0.86rem;}
.empty-state{text-align:center;padding:60px 24px;background:white;border-radius:12px;box-shadow:0 2px 12px rgba(0,0,0,0.07);}
.empty-state .ei{font-size:3rem;margin-bottom:12px;}
.empty-state h3{font-size:1.1rem;font-weight:700;margin-bottom:6px;color:#212529;}
.empty-state p{color:#6c757d;margin-bottom:18px;font-size:0.86rem;}
#toast{position:fixed;bottom:24px;right:24px;z-index:99999;padding:12px 18px;border-radius:10px;font-weight:600;font-size:0.85rem;box-shadow:0 6px 24px rgba(0,0,0,0.18);transform:translateY(80px);opacity:0;transition:all 0.3s cubic-bezier(0.34,1.56,0.64,1);display:flex;align-items:center;gap:8px;}
#toast.show{transform:translateY(0);opacity:1;}
#toast.success{background:#22c55e;color:white;}
#toast.error{background:#ef4444;color:white;}
#toast.info{background:#FF6B00;color:white;}
@media(max-width:900px){.stats-row{grid-template-columns:repeat(3,1fr);}}
@media(max-width:600px){.stats-row{grid-template-columns:repeat(2,1fr);}.bookings-table thead{display:none;}.bookings-table td{display:block;padding:6px 14px;}.bookings-table td::before{content:attr(data-label);font-weight:700;color:#6c757d;font-size:0.70rem;display:block;}}
</style>
</head>
<body>

<div class="page-hero">
  <h1>🚌 Owner Dashboard</h1>
  <p>Manage your vehicles and incoming bookings</p>
  <div class="hero-tabs">
    <a href="/Drifter/front/your_vehicle_info.php" class="hero-tab"><i class="fas fa-truck"></i> Transport</a>
    <a href="/Drifter/front/your_vehicle_travel.php" class="hero-tab active"><i class="fas fa-bus"></i> Travel</a>
  </div>
</div>

<div class="stats-row">
  <div class="stat-card"><div class="stat-icon">🚌</div><div class="stat-num"><?= count($vehicles) ?></div><div class="stat-lbl">My Vehicles</div></div>
  <div class="stat-card"><div class="stat-icon">📋</div><div class="stat-num"><?= $total ?></div><div class="stat-lbl">Total Bookings</div></div>
  <div class="stat-card"><div class="stat-icon">⏳</div><div class="stat-num"><?= $pending ?></div><div class="stat-lbl">Pending</div></div>
  <div class="stat-card"><div class="stat-icon">✅</div><div class="stat-num"><?= $confirmed ?></div><div class="stat-lbl">Confirmed</div></div>
  <div class="stat-card"><div class="stat-icon">💰</div><div class="stat-num">₹<?= number_format($revenue,0) ?></div><div class="stat-lbl">Revenue</div></div>
</div>

<div class="dash-wrap">
  <?php if (isset($_GET['registered'])): ?>
  <div class="success-banner"><i class="fas fa-check-circle"></i> Travel vehicle registered successfully!</div>
  <?php endif; ?>

  <div class="section-head">
    <h2><i class="fas fa-calendar-check"></i> Bookings</h2>
  </div>

  <div class="tab-bar">
    <button class="tab-btn active" onclick="switchTab('all',this)">All <span class="cnt"><?= $total ?></span></button>
    <button class="tab-btn" onclick="switchTab('pending',this)">Pending <span class="cnt"><?= $pending ?></span></button>
    <button class="tab-btn" onclick="switchTab('confirmed',this)">Confirmed <span class="cnt"><?= $confirmed ?></span></button>
    <button class="tab-btn" onclick="switchTab('cancelled',this)">Cancelled <span class="cnt"><?= $cancelled ?></span></button>
  </div>

  <?php
  $tabs = ['all'=>$allBookings,'pending'=>[],'confirmed'=>[],'cancelled'=>[]];
  foreach ($allBookings as $b) { $tabs[strtolower($b['status'])][] = $b; }
  foreach ($tabs as $tabName => $rows):
  ?>
  <div id="tab-<?= $tabName ?>" class="tab-panel <?= $tabName==='all'?'active':'' ?>">
    <table class="bookings-table">
      <thead><tr><th>Customer</th><th>Route</th><th>Date</th><th>Distance</th><th>Amount</th><th>Status</th><th>Action</th></tr></thead>
      <tbody>
        <?php if (empty($rows)): ?>
        <tr class="empty-row"><td colspan="7">No <?= $tabName ?> bookings</td></tr>
        <?php else: foreach ($rows as $b): ?>
        <tr id="brow-<?= $b['id'] ?>">
          <td data-label="Customer"><div class="customer-name"><?= htmlspecialchars($b['user_name']) ?></div><div class="route-text"><?= htmlspecialchars($b['user_mobile'] ?? '') ?></div></td>
          <td data-label="Route"><?= htmlspecialchars($b['pickup_location']) ?> → <?= htmlspecialchars($b['drop_location']) ?></td>
          <td data-label="Date"><?= date('d M Y', strtotime($b['date'])) ?></td>
          <td data-label="Distance"><?= $b['distance_km'] ?> km</td>
          <td data-label="Amount"><strong>₹<?= number_format($b['total_cost'],0) ?></strong></td>
          <td data-label="Status"><span class="status-pill <?= strtolower($b['status']) ?>" id="bstatus-<?= $b['id'] ?>"><?= $b['status'] ?></span></td>
          <td data-label="Action">
            <div class="action-btns" id="bactions-<?= $b['id'] ?>">
              <?php if ($b['status'] === 'Pending'): ?>
                <button class="act-btn confirm" onclick="updateBooking(<?= $b['id'] ?>,'confirm',this)"><i class="fas fa-check"></i> Confirm</button>
                <button class="act-btn cancel"  onclick="updateBooking(<?= $b['id'] ?>,'cancel',this)"><i class="fas fa-times"></i> Cancel</button>
              <?php elseif ($b['status'] === 'Confirmed'): ?>
                <button class="act-btn cancel"  onclick="updateBooking(<?= $b['id'] ?>,'cancel',this)"><i class="fas fa-times"></i> Cancel</button>
              <?php else: ?>
                <span style="color:#6c757d;font-size:0.75rem;">—</span>
              <?php endif; ?>
            </div>
          </td>
        </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
  <?php endforeach; ?>

  <div class="section-head" style="margin-top:40px;">
    <h2><i class="fas fa-bus"></i> My Travel Vehicles</h2>
    <a href="/Drifter/travel/add_vehicle.php" class="add-btn"><i class="fas fa-plus"></i> Add Vehicle</a>
  </div>

  <?php if (empty($vehicles)): ?>
    <div class="empty-state">
      <div class="ei">🚌</div>
      <h3>No Travel Vehicles Yet</h3>
      <p>Register your first travel vehicle to start receiving passenger bookings.</p>
      <a href="/Drifter/travel/add_vehicle.php" class="add-btn"><i class="fas fa-plus"></i> Add Your First Vehicle</a>
    </div>
  <?php else: ?>
    <div class="vehicles-grid">
      <?php foreach ($vehicles as $v): $vid=(int)$v['id']; ?>
      <div class="v-card">
        <?php if ($v['vehicle_image']): ?>
          <img src="/Drifter/travel/<?= htmlspecialchars($v['vehicle_image']) ?>" class="v-img" alt="Vehicle">
        <?php else: ?>
          <div class="v-img-ph">🚌</div>
        <?php endif; ?>
        <div class="v-body">
          <div class="v-top">
            <div class="v-name"><?= htmlspecialchars($v['owner_name']) ?>'s Travel Vehicle</div>
            <span class="avail-badge <?= $v['is_available']?'on':'off' ?>" id="badge-<?= $vid ?>">
              <?= $v['is_available']?'Available':'Unavailable' ?>
            </span>
          </div>
          <div class="v-meta">
            <div class="v-meta-item"><strong><?= htmlspecialchars($v['capacity']) ?> seats</strong>Capacity</div>
            <div class="v-meta-item"><strong>₹<?= htmlspecialchars($v['rate_per_km']) ?>/km</strong>Rate</div>
            <div class="v-meta-item"><strong><?= htmlspecialchars($v['mobile']) ?></strong>Mobile</div>
            <div class="v-meta-item" style="grid-column:span 2"><strong><?= htmlspecialchars($v['address']) ?></strong>Base</div>
          </div>
          <button class="toggle-btn <?= $v['is_available']?'on':'off' ?>" id="tbtn-<?= $vid ?>"
            onclick="toggleAvail(<?= $vid ?>,<?= $v['is_available'] ?>)">
            <?= $v['is_available']?'🔴 Mark Unavailable':'🟢 Mark Available' ?>
          </button>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<div id="toast"></div>
<?php include '../includes/footer.php'; ?>
<script>
function switchTab(name, btn) {
  document.querySelectorAll('.tab-btn').forEach(b=>b.classList.remove('active'));
  document.querySelectorAll('.tab-panel').forEach(p=>p.classList.remove('active'));
  btn.classList.add('active');
  document.getElementById('tab-'+name).classList.add('active');
}
function showToast(msg, type='info') {
  const t = document.getElementById('toast');
  t.innerHTML = `<i class="fas fa-${type==='success'?'check-circle':type==='error'?'times-circle':'info-circle'}"></i> ${msg}`;
  t.className = 'show '+type;
  setTimeout(()=>t.className='', 3500);
}
function updateBooking(id, action, btn) {
  if (action === 'cancel' && !confirm('Cancel this booking?')) return;
  btn.disabled = true;
  fetch('/Drifter/front/api_update_booking.php', {
    method:'POST', body:new URLSearchParams({booking_id:id, action})
  }).then(r=>r.json()).then(d=>{
    if (d.success) {
      document.querySelectorAll('#bstatus-'+id).forEach(p=>{
        p.textContent = d.status;
        p.className = 'status-pill '+d.status.toLowerCase();
      });
      const ab = document.getElementById('bactions-'+id);
      if (ab) {
        if (d.status === 'Confirmed') {
          ab.innerHTML = '<button class="act-btn cancel" onclick="updateBooking('+id+',\'cancel\',this)"><i class="fas fa-times"></i> Cancel</button>';
        } else {
          ab.innerHTML = '<span style="color:#6c757d;font-size:0.75rem;">—</span>';
        }
      }
      showToast('Booking '+d.status.toLowerCase()+' successfully.', d.status==='Confirmed'?'success':'info');
    } else {
      showToast(d.msg || 'Action failed.', 'error');
      btn.disabled = false;
    }
  }).catch(()=>{ showToast('Network error.','error'); btn.disabled=false; });
}
function toggleAvail(id, current) {
  const btn = document.getElementById('tbtn-'+id);
  const badge = document.getElementById('badge-'+id);
  btn.disabled = true; btn.textContent = 'Updating...';
  fetch('/Drifter/front/toggle_vehicle.php', {method:'POST', body:new URLSearchParams({id})})
    .then(r=>r.json()).then(d=>{
      if (d.success) {
        const on = d.is_available;
        badge.textContent = on?'Available':'Unavailable';
        badge.className = 'avail-badge '+(on?'on':'off');
        btn.className = 'toggle-btn '+(on?'on':'off');
        btn.textContent = on?'🔴 Mark Unavailable':'🟢 Mark Available';
        btn.onclick = ()=>toggleAvail(id,on);
        showToast('Vehicle marked '+(on?'available':'unavailable')+'.','info');
      }
      btn.disabled = false;
    });
}
</script>
</body>
</html>
