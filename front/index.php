<?php
session_start();
$navActive = 'home';
include '../includes/navbar.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Drifter - Your All-in-One Transport Platform</title>
<style>
/* ── HERO ── */
.hero {
  min-height: 100vh;
  display: flex; align-items: center; justify-content: center;
  text-align: center; padding: 100px 24px 60px;
  position: relative; overflow: hidden;
}
.hero::before {
  content: '';
  position: absolute; inset: 0;
  background: url('https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?w=1920&q=80') center/cover no-repeat;
  filter: brightness(0.28) saturate(1.2);
}
.hero::after {
  content: '';
  position: absolute; inset: 0;
  background: linear-gradient(135deg, rgba(33,37,41,0.92) 0%, rgba(255,107,0,0.55) 60%, rgba(255,193,7,0.20) 100%);
}
.hero-content { position: relative; z-index: 2; max-width: 820px; }
.hero-badge {
  display: inline-flex; align-items: center; gap: 8px;
  background: rgba(255,107,0,0.18); border: 1px solid rgba(255,107,0,0.40);
  color: #FFC107; padding: 6px 16px; border-radius: 50px;
  font-size: 0.82rem; font-weight: 600; letter-spacing: 1px;
  text-transform: uppercase; margin-bottom: 24px;
}
.hero h1 {
  font-size: clamp(2.4rem, 6vw, 4rem);
  font-weight: 800; color: white; line-height: 1.15;
  margin-bottom: 20px;
}
.hero h1 span { color: #FFC107; }
.hero p {
  font-size: 1.15rem; color: rgba(255,255,255,0.80);
  max-width: 580px; margin: 0 auto 36px; line-height: 1.7;
}
.hero-btns { display: flex; gap: 14px; justify-content: center; flex-wrap: wrap; }
.btn-primary {
  background: linear-gradient(135deg, #FF6B00, #e05e00);
  color: #fff; padding: 14px 32px; border-radius: var(--radius);
  font-weight: 700; font-size: 1rem; text-decoration: none;
  transition: transform 0.22s ease, box-shadow 0.22s ease, filter 0.22s ease;
  display: inline-flex; align-items: center; gap: 8px;
  box-shadow: 0 4px 20px rgba(255,107,0,0.45);
}
.btn-primary:hover { transform: translateY(-3px); box-shadow: 0 10px 32px rgba(255,107,0,0.60); filter: brightness(1.06); }
.btn-primary:active { transform: translateY(-1px); }
.btn-outline {
  background: transparent; color: white;
  border: 2px solid rgba(255,255,255,0.45);
  padding: 14px 32px; border-radius: var(--radius);
  font-weight: 600; font-size: 1rem; text-decoration: none;
  transition: background 0.22s ease, border-color 0.22s ease, transform 0.22s ease;
  display: inline-flex; align-items: center; gap: 8px;
}
.btn-outline:hover { background: rgba(255,107,0,0.15); border-color: #FF6B00; transform: translateY(-3px); }
.btn-outline:active { transform: translateY(-1px); }

/* stats bar */
.stats-bar {
  background: white; padding: 28px 24px;
  box-shadow: 0 4px 32px rgba(255,107,0,0.10);
  border-bottom: 3px solid #FFC107;
}
.stats-inner {
  max-width: 1280px; margin: 0 auto;
  display: grid; grid-template-columns: repeat(4, 1fr);
  gap: 20px; text-align: center;
}
.stat-item .num {
  font-size: 2rem; font-weight: 800;
  background: linear-gradient(135deg,#FF6B00,#FFC107);
  -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text;
}
.stat-item .lbl { font-size: 0.82rem; color: var(--muted); font-weight: 500; margin-top: 4px; }
@media(max-width:600px){ .stats-inner{grid-template-columns:repeat(2,1fr);} }

/* ── SERVICES ── */
.section { padding: 80px 24px; }
.section-inner { max-width: 1280px; margin: 0 auto; }
.section-head { text-align: center; margin-bottom: 56px; }
.section-head .tag {
  display: inline-block; background: rgba(255,107,0,0.12);
  color: #FF6B00; font-size: 0.78rem; font-weight: 700;
  letter-spacing: 2px; text-transform: uppercase;
  padding: 5px 14px; border-radius: 50px; margin-bottom: 14px;
}
.section-head h2 { font-size: clamp(1.8rem,4vw,2.6rem); font-weight: 800; color: var(--text); margin-bottom: 12px; }
.section-head p { color: var(--muted); font-size: 1rem; max-width: 520px; margin: 0 auto; }

.services-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
  gap: 24px;
}
.service-card {
  background: white; border-radius: 16px;
  padding: 32px 28px; text-align: center;
  box-shadow: var(--shadow); transition: transform 0.28s ease, box-shadow 0.28s ease, border-color 0.28s ease;
  text-decoration: none; color: var(--text);
  border: 2px solid transparent; position: relative; overflow: hidden;
  cursor: pointer;
}
.service-card::before {
  content: ''; position: absolute; bottom: 0; left: 0; right: 0;
  height: 4px; background: var(--card-color, var(--accent));
  transform: scaleX(0); transform-origin: left;
  transition: transform 0.3s ease;
}
.service-card:hover { transform: translateY(-10px); box-shadow: 0 20px 48px rgba(0,0,0,0.13); border-color: var(--card-color, var(--accent)); }
.service-card:hover::before { transform: scaleX(1); }
.service-card:active { transform: translateY(-5px); }
.service-icon {
  width: 72px; height: 72px; border-radius: 18px;
  background: var(--icon-bg, rgba(168,223,142,0.15));
  display: flex; align-items: center; justify-content: center;
  font-size: 2rem; margin: 0 auto 20px;
  transition: transform 0.3s cubic-bezier(0.34,1.56,0.64,1), background 0.3s ease;
}
.service-card:hover .service-icon { transform: scale(1.18) rotate(-8deg); }
.service-card h3 { font-size: 1.2rem; font-weight: 700; margin-bottom: 10px; transition: color 0.2s ease; }
.service-card:hover h3 { color: var(--card-color, var(--accent)); }
.service-card p { font-size: 0.88rem; color: var(--muted); line-height: 1.6; margin-bottom: 20px; }
.service-link {
  display: inline-flex; align-items: center; gap: 6px;
  font-size: 0.85rem; font-weight: 600;
  color: var(--card-color, var(--accent)); text-decoration: none;
}
.service-link i { transition: transform 0.22s ease; }
.service-card:hover .service-link i { transform: translateX(6px); }

/* ── HOW IT WORKS ── */
.how-section {
  padding: 80px 24px;
  background: linear-gradient(135deg, #212529 0%, #343a40 100%);
  position: relative;
}
.steps-grid {
  display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 32px; max-width: 1280px; margin: 0 auto; position: relative; z-index: 1;
}
.step-card {
  text-align: center;
  padding: 28px 20px; border-radius: 14px;
  transition: background 0.25s ease, transform 0.25s ease;
  cursor: default;
}
.step-card:hover {
  background: rgba(168,223,142,0.10);
  transform: translateY(-6px);
}
.step-num {
  width: 56px; height: 56px; border-radius: 50%;
  background: linear-gradient(135deg, #FF6B00, #FFC107);
  color: #fff; font-size: 1.3rem; font-weight: 800;
  display: flex; align-items: center; justify-content: center;
  margin: 0 auto 16px;
  transition: transform 0.3s cubic-bezier(0.34,1.56,0.64,1), box-shadow 0.3s ease;
}
.step-card:hover .step-num {
  transform: scale(1.15);
  box-shadow: 0 6px 20px rgba(255,107,0,0.55);
}
.step-card h4 { color: #FFC107; font-size: 1rem; font-weight: 700; margin-bottom: 8px; }
.step-card p { color: rgba(255,255,255,0.65); font-size: 0.85rem; line-height: 1.6; }

/* ── WHY DRIFTER ── */
.features-grid {
  display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 24px;
}
.feature-card {
  background: white; border-radius: 14px; padding: 28px;
  box-shadow: var(--shadow); display: flex; gap: 18px; align-items: flex-start;
  transition: transform 0.25s ease, box-shadow 0.25s ease;
  border-left: 3px solid transparent;
}
.feature-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 16px 40px rgba(0,0,0,0.12);
  border-left-color: #FF6B00;
}
.feat-icon {
  width: 48px; height: 48px; border-radius: 12px; flex-shrink: 0;
  background: linear-gradient(135deg, #FF6B00, #FFC107);
  display: flex; align-items: center; justify-content: center;
  font-size: 1.2rem; color: #fff;
  transition: transform 0.3s cubic-bezier(0.34,1.56,0.64,1), box-shadow 0.3s ease;
}
.feature-card:hover .feat-icon {
  transform: scale(1.15) rotate(-6deg);
  box-shadow: 0 4px 14px rgba(255,107,0,0.45);
}
.feature-card h4 { font-size: 1rem; font-weight: 700; margin-bottom: 6px; transition: color 0.2s ease; }
.feature-card:hover h4 { color: #FF6B00; }
.feature-card p { font-size: 0.85rem; color: var(--muted); line-height: 1.6; }

/* ── SMART BANNER (new feature) ── */
.smart-banner {
  background: linear-gradient(135deg, #212529 0%, #343a40 100%);
  padding: 48px 24px; text-align: center;
  border-top: 3px solid #FF6B00; border-bottom: 3px solid #FFC107;
}
.smart-banner h2 { font-size: 1.6rem; font-weight: 800; color: #FFC107; margin-bottom: 10px; }
.smart-banner p { color: rgba(255,255,255,0.70); font-size: 0.95rem; margin-bottom: 24px; }
.smart-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px,1fr)); gap: 16px; max-width: 900px; margin: 0 auto; }
.smart-item {
  background: rgba(255,255,255,0.07); border-radius: 12px; padding: 18px 14px;
  border: 1px solid rgba(255,107,0,0.30);
  transition: transform 0.25s ease, box-shadow 0.25s ease;
}
.smart-item:hover { transform: translateY(-4px); box-shadow: 0 10px 28px rgba(255,107,0,0.25); }
.smart-item .si { font-size: 1.8rem; margin-bottom: 8px; }
.smart-item h4 { font-size: 0.88rem; font-weight: 700; color: #FFC107; margin-bottom: 4px; }
.smart-item p { font-size: 0.78rem; color: rgba(255,255,255,0.60); margin: 0; }

/* ── CTA BANNER ── */
.cta-banner {
  padding: 100px 24px; text-align: center;
  position: relative; overflow: hidden;
  background: linear-gradient(135deg, #FF6B00 0%, #e05e00 100%);
}
.cta-banner > * { position: relative; z-index: 1; }
.cta-banner h2 { font-size: clamp(1.8rem,4vw,2.4rem); font-weight: 800; color: white; margin-bottom: 14px; }
.cta-banner p { color: rgba(255,255,255,0.85); font-size: 1rem; margin-bottom: 32px; }
.btn-white {
  background: #fff; color: #FF6B00;
  padding: 14px 36px; border-radius: var(--radius);
  font-weight: 700; font-size: 1rem; text-decoration: none;
  display: inline-flex; align-items: center; gap: 8px;
  transition: transform 0.22s ease, box-shadow 0.22s ease, background 0.22s ease;
  box-shadow: 0 4px 20px rgba(0,0,0,0.12);
}
.btn-white:hover { transform: translateY(-3px); box-shadow: 0 10px 32px rgba(0,0,0,0.18); background: #FFC107; color: #212529; }
.btn-white:active { transform: translateY(-1px); }

/* success toast */
.booking-toast {
  position: fixed; top: 80px; right: 24px; z-index: 99999;
  background: #FF6B00; color: white; padding: 14px 22px;
  border-radius: 10px; font-weight: 600; font-size: 0.9rem;
  box-shadow: 0 8px 24px rgba(255,107,0,0.45);
  transform: translateX(120%); transition: transform 0.4s ease;
}
.booking-toast.show { transform: translateX(0); }
</style>
</head>
<body>

<?php if (isset($_GET['booking_success'])): ?>
<div class="booking-toast" id="bToast">✅ Booking confirmed! Check your dashboard.</div>
<script>
  const t = document.getElementById('bToast');
  setTimeout(() => t.classList.add('show'), 100);
  setTimeout(() => t.classList.remove('show'), 4000);
</script>
<?php endif; ?>

<!-- HERO -->
<section class="hero">
  <div class="hero-content">
    <div class="hero-badge"><i class="fas fa-star"></i> India's #1 Transport Platform</div>
    <h1>Move Anything,<br><span>Anywhere in India</span></h1>
    <p>Transport goods, book travel vehicles, send couriers, or hire packers & movers — all from one platform.</p>
    <div class="hero-btns">
      <a href="#services" class="btn-primary"><i class="fas fa-rocket"></i> Explore Services</a>
      <?php if (!isset($_SESSION['loggedin'])): ?>
        <a href="/Drifter/front/signup.php" class="btn-outline"><i class="fas fa-user-plus"></i> Join Free</a>
      <?php else: ?>
        <a href="/Drifter/front/dashboard_customer.php" class="btn-outline"><i class="fas fa-tachometer-alt"></i> My Dashboard</a>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- LIVE STATS -->
<div class="stats-bar" id="statsBar">
  <div class="stats-inner">
    <div class="stat-item"><div class="num" id="st-vehicles">—</div><div class="lbl">Available Vehicles</div></div>
    <div class="stat-item"><div class="num" id="st-bookings">—</div><div class="lbl">Total Bookings</div></div>
    <div class="stat-item"><div class="num" id="st-customers">—</div><div class="lbl">Registered Customers</div></div>
    <div class="stat-item"><div class="num">24/7</div><div class="lbl">Support Available</div></div>
  </div>
</div>

<!-- SERVICES -->
<section class="section" id="services">
  <div class="section-inner">
    <div class="section-head">
      <div class="tag">What We Offer</div>
      <h2>Our Services</h2>
      <p>Everything you need for transportation, all in one trusted platform.</p>
    </div>
    <div class="services-grid">
      <a href="<?= isset($_SESSION['loggedin']) ? '/Drifter/transport/booking_step1.php' : '/Drifter/front/login.php?redirect=/Drifter/transport/booking_step1.php' ?>"
         class="service-card" style="--card-color:#FF6B00;--icon-bg:rgba(255,107,0,0.12)">
        <div class="service-icon">🚚</div>
        <h3>Transport Goods</h3>
        <p>Reliable goods transportation for all cargo sizes with verified drivers and real-time tracking.</p>
        <span class="service-link">Book Now <i class="fas fa-arrow-right"></i></span>
      </a>
      <a href="<?= isset($_SESSION['loggedin']) ? '/Drifter/travel/booking_step1.php' : '/Drifter/front/login.php?redirect=/Drifter/travel/booking_step1.php' ?>"
         class="service-card" style="--card-color:#FFC107;--icon-bg:rgba(255,193,7,0.15)">
        <div class="service-icon">🚌</div>
        <h3>Travel / Ride</h3>
        <p>Comfortable travel vehicles for daily commutes and long-distance trips at competitive rates.</p>
        <span class="service-link">Book Now <i class="fas fa-arrow-right"></i></span>
      </a>
      <a href="<?= isset($_SESSION['loggedin']) ? '/Drifter/courier/courier.php' : '/Drifter/front/login.php?redirect=/Drifter/courier/courier.php' ?>"
         class="service-card" style="--card-color:#ADB5BD;--icon-bg:rgba(173,181,189,0.18)">
        <div class="service-icon">📦</div>
        <h3>Courier</h3>
        <p>Fast and secure package delivery — same-day, next-day, or scheduled with full tracking.</p>
        <span class="service-link">Send Now <i class="fas fa-arrow-right"></i></span>
      </a>
      <a href="<?= isset($_SESSION['loggedin']) ? '/Drifter/move/movers.php' : '/Drifter/front/login.php?redirect=/Drifter/move/movers.php' ?>"
         class="service-card" style="--card-color:#e05e00;--icon-bg:rgba(224,94,0,0.12)">
        <div class="service-icon">🏠</div>
        <h3>Packers & Movers</h3>
        <p>Professional home and office relocation with full packing, loading, and transportation service.</p>
        <span class="service-link">Get Quote <i class="fas fa-arrow-right"></i></span>
      </a>
    </div>
  </div>
</section>

<!-- SMART FEATURES BANNER -->
<div class="smart-banner">
  <h2>🧠 Smart Platform Features</h2>
  <p>Advanced technology working behind the scenes to give you the best experience.</p>
  <div class="smart-grid">
    <div class="smart-item"><div class="si">⚡</div><h4>Live Availability</h4><p>Real-time vehicle & company availability updates</p></div>
    <div class="smart-item"><div class="si">🔒</div><h4>Secure Bookings</h4><p>CSRF-protected, session-verified transactions</p></div>
    <div class="smart-item"><div class="si">📊</div><h4>Smart Pricing</h4><p>Auto-calculated cost based on distance & rate</p></div>
    <div class="smart-item"><div class="si">🔔</div><h4>Live Polling</h4><p>Dashboard updates every 8 seconds automatically</p></div>
    <div class="smart-item"><div class="si">🛡️</div><h4>Verified Providers</h4><p>All owners & companies are manually verified</p></div>
  </div>
</div>

<!-- HOW IT WORKS -->
<div class="how-section">
  <div class="section-head" style="margin-bottom:48px;position:relative;z-index:1;">
    <div class="tag" style="background:rgba(255,107,0,0.15);color:#FF6B00;">Simple Process</div>
    <h2 style="color:white;">How It Works</h2>
    <p style="color:rgba(240,255,223,0.70);">Get your service booked in 3 easy steps.</p>
  </div>
  <div class="steps-grid">
    <div class="step-card"><div class="step-num">1</div><h4>Create Account</h4><p>Sign up free in under a minute. No credit card required.</p></div>
    <div class="step-card"><div class="step-num">2</div><h4>Choose Service</h4><p>Select transport, travel, courier, or movers based on your need.</p></div>
    <div class="step-card"><div class="step-num">3</div><h4>Enter Details</h4><p>Fill in pickup, drop, date and distance to find available providers.</p></div>
    <div class="step-card"><div class="step-num">4</div><h4>Confirm Booking</h4><p>Pick your provider, confirm, and you're done. It's that simple.</p></div>
  </div>
</div>

<!-- WHY DRIFTER -->
<section class="section" style="background:#fff8f3;">
  <div class="section-inner">
    <div class="section-head">
      <div class="tag">Why Choose Us</div>
      <h2>The Drifter Advantage</h2>
      <p>We combine technology with trust to deliver exceptional service every time.</p>
    </div>
    <div class="features-grid">
      <div class="feature-card"><div class="feat-icon"><i class="fas fa-shield-alt"></i></div><div><h4>Verified Providers</h4><p>Every driver and company is background-checked and verified before listing.</p></div></div>
      <div class="feature-card"><div class="feat-icon"><i class="fas fa-clock"></i></div><div><h4>On-Time Guarantee</h4><p>98% on-time rate across all services. We value your time as much as you do.</p></div></div>
      <div class="feature-card"><div class="feat-icon"><i class="fas fa-rupee-sign"></i></div><div><h4>Transparent Pricing</h4><p>No hidden charges. See the full price before you confirm any booking.</p></div></div>
      <div class="feature-card"><div class="feat-icon"><i class="fas fa-headset"></i></div><div><h4>24/7 Support</h4><p>Our support team is always available to help you with any issue.</p></div></div>
      <div class="feature-card"><div class="feat-icon"><i class="fas fa-route"></i></div><div><h4>Nationwide Network</h4><p>Partners in every major city — we cover the whole country.</p></div></div>
      <div class="feature-card"><div class="feat-icon"><i class="fas fa-mobile-alt"></i></div><div><h4>Easy Booking</h4><p>Book in under 2 minutes from any device, anywhere, anytime.</p></div></div>
    </div>
  </div>
</section>

<!-- CTA -->
<div class="cta-banner">
  <h2>Ready to Get Started?</h2>
  <p>Join thousands of customers who trust Drifter for all their transport needs.</p>
  <?php if (!isset($_SESSION['loggedin'])): ?>
    <a href="/Drifter/front/signup.php" class="btn-white"><i class="fas fa-user-plus"></i> Create Free Account</a>
  <?php else: ?>
    <a href="#services" class="btn-white"><i class="fas fa-rocket"></i> Book a Service Now</a>
  <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
<script>
document.querySelectorAll('a[href^="#"]').forEach(a => {
  a.addEventListener('click', e => {
    const t = document.querySelector(a.getAttribute('href'));
    if (t) { e.preventDefault(); t.scrollIntoView({behavior:'smooth'}); }
  });
});

// Live stats with animated counter
function animateCount(el, target) {
  const start = parseInt(el.textContent) || 0;
  if (start === target) return;
  const step = Math.ceil(Math.abs(target - start) / 25);
  let cur = start;
  const t = setInterval(() => {
    cur = cur < target ? Math.min(cur + step, target) : Math.max(cur - step, target);
    el.textContent = cur + (target > 0 ? '+' : '');
    if (cur === target) clearInterval(t);
  }, 40);
}
function loadStats() {
  fetch('/Drifter/front/api_stats.php')
    .then(r => r.json())
    .then(d => {
      if (d.error) return;
      animateCount(document.getElementById('st-vehicles'),  d.vehicles);
      animateCount(document.getElementById('st-bookings'),  d.total_requests);
      animateCount(document.getElementById('st-customers'), d.customers);
    }).catch(() => {});
}
loadStats();
setInterval(loadStats, 15000);
</script>
</body>
</html>
