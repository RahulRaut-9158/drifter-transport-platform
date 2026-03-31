<style>
.drifter-footer{
  background:var(--primary);
  color:rgba(255,255,255,0.70);padding:60px 24px 30px;margin-top:80px;
  border-top:3px solid var(--orange);
}
.drifter-footer .f-inner{max-width:1280px;margin:0 auto;}
.drifter-footer .f-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:40px;margin-bottom:40px;}
.drifter-footer .f-brand{display:flex;align-items:center;gap:10px;margin-bottom:16px;text-decoration:none;}
.drifter-footer .f-brand-icon{
  width:36px;height:36px;
  background:linear-gradient(135deg,var(--orange),var(--yellow));
  border-radius:8px;display:flex;align-items:center;justify-content:center;
  font-weight:800;color:#fff;font-size:1rem;
  transition:transform 0.25s ease,box-shadow 0.25s ease;
}
.drifter-footer .f-brand:hover .f-brand-icon{transform:rotate(-8deg) scale(1.1);box-shadow:0 4px 14px rgba(255,107,0,0.50);}
.drifter-footer .f-brand-name{font-size:1.3rem;font-weight:800;color:white;}
.drifter-footer .f-desc{font-size:0.88rem;line-height:1.7;color:var(--silver);}
.drifter-footer h4{color:var(--orange);font-size:0.85rem;font-weight:700;margin-bottom:16px;letter-spacing:1px;text-transform:uppercase;}
.drifter-footer ul{list-style:none;display:flex;flex-direction:column;gap:10px;}
.drifter-footer ul li a{
  color:rgba(255,255,255,0.55);text-decoration:none;font-size:0.88rem;
  display:inline-flex;align-items:center;gap:6px;
  transition:color 0.2s ease,padding-left 0.2s ease;
}
.drifter-footer ul li a:hover{color:var(--yellow);padding-left:4px;}
.drifter-footer .f-contact li{font-size:0.88rem;display:flex;align-items:center;gap:8px;color:var(--silver);}
.drifter-footer .f-contact a{color:rgba(255,255,255,0.55);text-decoration:none;transition:color 0.2s ease;}
.drifter-footer .f-contact a:hover{color:var(--yellow);}
.drifter-footer .f-bottom{
  border-top:1px solid rgba(255,107,0,0.20);
  padding-top:24px;text-align:center;font-size:0.82rem;color:var(--silver);
}
.drifter-footer .f-bottom a{color:var(--orange);text-decoration:none;}
.drifter-footer .f-bottom a:hover{color:var(--yellow);}
</style>

<footer class="drifter-footer">
  <div class="f-inner">
    <div class="f-grid">
      <div>
        <a href="/Drifter/front/index.php" class="f-brand">
          <div class="f-brand-icon">D</div>
          <span class="f-brand-name">DRIFTER</span>
        </a>
        <p class="f-desc">Your all-in-one platform for transport, travel, courier &amp; packers and movers across India.</p>
      </div>
      <div>
        <h4>Services</h4>
        <ul>
          <li><a href="/Drifter/transport/booking_step1.php">🚚 Transport Goods</a></li>
          <li><a href="/Drifter/travel/booking_step1.php">🚌 Travel / Ride</a></li>
          <li><a href="/Drifter/courier/courier.php">📦 Courier</a></li>
          <li><a href="/Drifter/move/movers.php">🏠 Packers &amp; Movers</a></li>
        </ul>
      </div>
      <div>
        <h4>Quick Links</h4>
        <ul>
          <li><a href="/Drifter/front/index.php">Home</a></li>
          <li><a href="/Drifter/front/about.php">About Us</a></li>
          <li><a href="/Drifter/front/support.php">Support</a></li>
          <li><a href="/Drifter/front/login.php">Login / Signup</a></li>
        </ul>
      </div>
      <div>
        <h4>Contact</h4>
        <ul class="f-contact">
          <li>📍 KBPCOES, Satara - 415004</li>
          <li>📞 <a href="tel:+918788394028">+91 8788394028</a></li>
          <li>✉️ <a href="mailto:info@drifter.com">info@drifter.com</a></li>
        </ul>
      </div>
    </div>
    <div class="f-bottom">
      &copy; <?= date('Y') ?> <a href="/Drifter/front/index.php">Drifter Transport Services</a>. All rights reserved.
    </div>
  </div>
</footer>
