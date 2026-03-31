<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: /Drifter/front/login.php?redirect=/Drifter/transport/booking_step1.php"); exit;
}
$navActive = '';
include '../includes/navbar.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Book Transport — Drifter</title>
<style>
.page-hero{
  padding:70px 24px 50px;text-align:center;color:white;
  position:relative;overflow:hidden;
}
.page-hero::before{
  content:'';position:absolute;inset:0;
  background:url('https://images.unsplash.com/photo-1519003722824-194d4455a60c?w=1920&q=80') center/cover no-repeat;
  filter:brightness(0.22) saturate(1.2);
}
.page-hero::after{
  content:'';position:absolute;inset:0;
  background:linear-gradient(135deg,rgba(45,98,53,0.92) 0%,rgba(168,223,142,0.40) 100%);
}
.page-hero h1{font-size:2.2rem;font-weight:800;margin-bottom:8px;position:relative;z-index:1;}
.page-hero p{color:rgba(255,255,255,0.75);font-size:0.95rem;position:relative;z-index:1;}

.steps-nav{display:flex;justify-content:center;align-items:center;
  gap:0;padding:28px 24px;background:white;
  box-shadow:0 2px 12px rgba(0,0,0,0.06);}
.step-item{display:flex;flex-direction:column;align-items:center;gap:6px;
  position:relative;padding:0 32px;}
.step-item:not(:last-child)::after{content:'';position:absolute;
  right:-1px;top:18px;width:60px;height:2px;
  background:#e2e8f0;z-index:0;}
.step-circle{width:40px;height:40px;border-radius:50%;
  display:flex;align-items:center;justify-content:center;
  font-weight:700;font-size:0.9rem;z-index:1;
  background:#e2e8f0;color:#94a3b8;transition:all 0.3s;}
.step-item.active .step-circle{background:#3a7d44;color:white;
  box-shadow:0 4px 12px rgba(58,125,68,0.45);}
.step-item.done .step-circle{background:#A8DF8E;color:#1e3a22;}
.step-label{font-size:0.75rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:0.5px;}
.step-item.active .step-label{color:#3a7d44;}

.form-wrap{max-width:720px;margin:40px auto;padding:0 24px 60px;}
.form-card{background:white;border-radius:16px;padding:40px;
  box-shadow:0 4px 24px rgba(0,0,0,0.08);}
.form-card h2{font-size:1.3rem;font-weight:700;margin-bottom:28px;
  color:var(--text);padding-bottom:16px;border-bottom:2px solid #f1f5f9;}

.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:20px;}
.form-group{display:flex;flex-direction:column;gap:6px;}
.form-group.full{grid-column:span 2;}
.form-group label{font-size:0.82rem;font-weight:600;color:#475569;}
.form-group input{
  padding:12px 14px;border:2px solid #e2e8f0;border-radius:10px;
  font-size:0.95rem;transition:all 0.25s;outline:none;
  font-family:inherit;background:#fafafa;
}
.form-group input:focus{border-color:#3a7d44;background:white;
  box-shadow:0 0 0 3px rgba(168,223,142,0.22);}
.form-group input.err{border-color:#ef4444;}
.err-msg{font-size:0.75rem;color:#ef4444;display:none;}
.form-group.has-err .err-msg{display:block;}
.form-group.has-err input{border-color:#ef4444;}

.user-info{background:#F0FFDF;border:1px solid #A8DF8E;border-radius:10px;
  padding:12px 16px;margin-bottom:24px;font-size:0.85rem;color:#2d6235;
  display:flex;align-items:center;gap:8px;}

.submit-btn{
  width:100%;padding:15px;margin-top:24px;
  background:linear-gradient(135deg,#3a7d44,#2d6235);
  color:white;border:none;border-radius:10px;
  font-size:1rem;font-weight:700;cursor:pointer;transition:all 0.25s;
}
.submit-btn:hover{transform:translateY(-2px);box-shadow:0 8px 24px rgba(58,125,68,0.38);}
.submit-btn:disabled{opacity:0.6;cursor:not-allowed;transform:none;}

@media(max-width:600px){.form-grid{grid-template-columns:1fr;}.form-group.full{grid-column:span 1;}.steps-nav{gap:0;padding:20px 8px;}.step-item{padding:0 12px;}}
</style>
</head>
<body>
<div class="page-hero">
  <h1>🚚 Book Transport Vehicle</h1>
  <p>Find available transport vehicles near your pickup location</p>
</div>

<div class="steps-nav">
  <div class="step-item active"><div class="step-circle">1</div><div class="step-label">Trip Details</div></div>
  <div class="step-item"><div class="step-circle">2</div><div class="step-label">Select Vehicle</div></div>
  <div class="step-item"><div class="step-circle">3</div><div class="step-label">Confirmed</div></div>
</div>

<div class="form-wrap">
  <div class="form-card">
    <h2>Enter Your Booking Details</h2>
    <div class="user-info"><i class="fas fa-user-check"></i> Logged in as <strong><?= htmlspecialchars($_SESSION['username']) ?></strong></div>
    <form action="select_vehicle.php" method="POST" id="bookForm">
      <div class="form-grid">
        <div class="form-group">
          <label>Your Name</label>
          <input type="text" name="user_name" value="<?= htmlspecialchars($_SESSION['username']) ?>" required>
        </div>
        <div class="form-group">
          <label>Mobile Number</label>
          <input type="text" name="user_mobile" id="mobile" placeholder="10-digit number" required>
          <span class="err-msg">Enter a valid 10-digit number</span>
        </div>
        <div class="form-group full">
          <label>Pickup Location / City</label>
          <input type="text" name="pickup_location" placeholder="e.g. Satara, Maharashtra" required>
        </div>
        <div class="form-group full">
          <label>Drop Location / City</label>
          <input type="text" name="drop_location" placeholder="e.g. Pune, Maharashtra" required>
        </div>
        <div class="form-group">
          <label>Date</label>
          <input type="date" name="date" min="<?= date('Y-m-d') ?>" required>
        </div>
        <div class="form-group">
          <label>Time</label>
          <input type="time" name="time" required>
        </div>
        <div class="form-group full">
          <label>Distance (km)</label>
          <input type="number" name="distance_km" placeholder="Approximate distance in km" min="1" required>
        </div>
      </div>
      <button type="submit" class="submit-btn" id="submitBtn">
        <i class="fas fa-search"></i> Find Available Vehicles
      </button>
    </form>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
<script>
const mob = document.getElementById('mobile');
mob.addEventListener('input', () => {
  const ok = /^[0-9]{10}$/.test(mob.value.trim());
  mob.closest('.form-group').classList.toggle('has-err', mob.value && !ok);
});
document.getElementById('bookForm').addEventListener('submit', function(e) {
  if (!/^[0-9]{10}$/.test(mob.value.trim())) {
    e.preventDefault();
    mob.closest('.form-group').classList.add('has-err');
    mob.focus(); return;
  }
  const btn = document.getElementById('submitBtn');
  btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Searching...';
});
</script>
</body></html>
