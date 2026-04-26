<?php
if(!isset($currentPage)){
  $currentPage = 'something.php';
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Cormorant+Garamond:ital,wght@0,300;0,400;1,300;1,400&family=Sancreek&display=swap" rel="stylesheet">

<style>
#hyde-nav {
  --forest:     #002D16;
  --emerald:    #005A2B;
  --gold:       #C9A84C;
  --gold-light: #E8C97A;
  --cream:      #FAFAF8;
  --ink:        #111111;
  --border:     rgba(201,168,76,0.20);
}
#hyde-nav * { box-sizing: border-box; margin: 0; padding: 0; }
#hyde-nav a { text-decoration: none !important; }

/* ── Promo Bar ── */
#hyde-nav .promo-bar {
  background: var(--forest);
  color: rgba(255,255,255,0.75);
  text-align: center;
  padding: 6px 12px;
  font-family: 'Cormorant Garamond', serif;
  font-size: 0.68rem;
  letter-spacing: 2.5px;
  text-transform: uppercase;
}
#hyde-nav .promo-bar strong { color: var(--gold-light); }

/* ── Navbar ── */
#hyde-nav .navbar-custom {
  background: var(--cream);
  border-bottom: 1px solid rgba(0,0,0,0.08);
  padding: 0;
}
#hyde-nav .navbar-inner {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 16px 28px;
  position: relative;
}

/* Hamburger */
#hyde-nav .hamburger-btn {
  background: none;
  border: none;
  cursor: pointer;
  padding: 4px;
  display: flex;
  flex-direction: column;
  gap: 5px;
  width: 34px;
}
#hyde-nav .hamburger-btn span {
  display: block;
  height: 1.5px;
  background: var(--ink);
  transition: all 0.3s ease;
  transform-origin: center;
}
#hyde-nav .hamburger-btn span:nth-child(1) { width: 24px; }
#hyde-nav .hamburger-btn span:nth-child(2) { width: 16px; }
#hyde-nav .hamburger-btn span:nth-child(3) { width: 24px; }
#hyde-nav .hamburger-btn:hover span { background: var(--emerald); }
#hyde-nav .hamburger-btn.active span:nth-child(1) { transform: translateY(6.5px) rotate(45deg); width: 24px; }
#hyde-nav .hamburger-btn.active span:nth-child(2) { opacity: 0; }
#hyde-nav .hamburger-btn.active span:nth-child(3) { transform: translateY(-6.5px) rotate(-45deg); width: 24px; }

/* Brand */
#hyde-nav .navbar-brand {
  font-family: 'Sancreek', cursive;
  font-size: clamp(1.25rem, 3vw, 1.75rem);
  color: var(--ink);
  letter-spacing: 3px;
  position: absolute;
  left: 50%;
  transform: translateX(-50%);
  white-space: nowrap;
  transition: color 0.2s;
}
#hyde-nav .navbar-brand:hover { color: var(--emerald); }

/* Icons */
#hyde-nav .icon-group {
  display: flex;
  align-items: center;
  gap: 20px;
}
#hyde-nav .icon-group a {
  color: var(--ink);
  font-size: 1.1rem;
  display: flex;
  align-items: center;
  position: relative;
  transition: color 0.2s, transform 0.2s;
}
#hyde-nav .icon-group a:hover {
  color: var(--emerald);
  transform: translateY(-1px);
}
#hyde-nav .icon-group a::after {
  content: '';
  position: absolute;
  bottom: -4px;
  left: 50%;
  transform: translateX(-50%) scaleX(0);
  width: 14px;
  height: 1px;
  background: var(--gold);
  transition: transform 0.2s;
}
#hyde-nav .icon-group a:hover::after { transform: translateX(-50%) scaleX(1); }

/* ── Overlay ── */
#hyde-nav .menu-overlay {
  position: fixed;
  inset: 0;
  background: transparent;
  z-index: 1040;
  visibility: hidden;
  transition: background 0.35s, visibility 0.35s, backdrop-filter 0.35s;
}
#hyde-nav .menu-overlay.visible {
  background: rgba(0,0,0,0.4);
  visibility: visible;
  backdrop-filter: blur(3px);
}

/* ── Side Drawer ── */
#hyde-nav .side-menu {
  position: fixed;
  left: -100%;
  top: 0;
  height: 100vh;
  z-index: 1050;
  overflow-y: auto;
  background: var(--forest);
  box-shadow: 16px 0 60px rgba(0,0,0,0.4);
  transition: left 0.4s cubic-bezier(.16,1,.3,1);
  display: flex;
  flex-direction: column;
  scrollbar-width: none;
}
#hyde-nav .side-menu::-webkit-scrollbar { display: none; }
#hyde-nav .side-menu.open { left: 0; }

@media (max-width: 480px)  { #hyde-nav .side-menu { width: 80%; } }
@media (min-width: 481px)  { #hyde-nav .side-menu { width: 300px; } }

/* Drawer Header */
#hyde-nav .menu-head {
  padding: 30px 32px 24px;
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  border-bottom: 1px solid var(--border);
}
#hyde-nav .menu-head .brand-wrap .brand {
  font-family: 'Sancreek', cursive;
  font-size: 1rem;
  letter-spacing: 2px;
  color: var(--gold-light);
  display: block;
}
#hyde-nav .menu-head .brand-wrap .tagline {
  font-family: 'Cormorant Garamond', serif;
  font-style: italic;
  font-size: 0.7rem;
  color: rgba(255,255,255,0.35);
  letter-spacing: 1.5px;
  margin-top: 4px;
  display: block;
}
#hyde-nav .close-btn {
  background: none;
  border: 1px solid rgba(255,255,255,0.1);
  color: rgba(255,255,255,0.5);
  width: 30px;
  height: 30px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  font-size: 0.85rem;
  transition: all 0.2s;
  flex-shrink: 0;
  margin-top: 2px;
}
#hyde-nav .close-btn:hover {
  border-color: var(--gold);
  color: var(--gold-light);
}

/* Nav Links */
#hyde-nav .menu-body { padding: 12px 0; flex: 1; }

#hyde-nav .nav-label {
  font-family: 'Cormorant Garamond', serif;
  font-size: 0.6rem;
  letter-spacing: 3px;
  text-transform: uppercase;
  color: var(--gold);
  opacity: 0.7;
  padding: 18px 32px 8px;
  display: block;
}

#hyde-nav .nav-link-item {
  display: block;
  padding: 12px 32px;
  font-family: 'Cinzel', serif;
  font-size: 0.68rem;
  font-weight: 600;
  letter-spacing: 2.5px;
  text-transform: uppercase;
  color: rgba(255,255,255,0.80);
  border-left: 2px solid transparent;
  transition: all 0.2s ease;
}
#hyde-nav .nav-link-item:hover {
  color: var(--gold-light);
  border-left-color: var(--gold);
  padding-left: 38px;
  background: rgba(201,168,76,0.05);
}

#hyde-nav .nav-divider {
  height: 1px;
  background: var(--border);
  margin: 10px 32px;
}

/* Bottom links */
#hyde-nav .menu-bottom {
  border-top: 1px solid var(--border);
  padding: 12px 0 28px;
}
#hyde-nav .menu-bottom .nav-link-item {
  font-size: 0.62rem;
  color: rgba(255,255,255,0.45);
  letter-spacing: 2px;
}
#hyde-nav .menu-bottom .nav-link-item:hover { color: rgba(255,255,255,0.85); }
</style>

<div id="hyde-nav">

  <?php if($currentPage != 'register.php'): ?>
  <div class="promo-bar">
    <strong>NEW CUSTOMERS</strong> &mdash; 6 USD OFF YOUR FIRST PURCHASE &nbsp;&middot;&nbsp; CODE: <strong>NEWUSER</strong>
  </div>
  <?php endif; ?>

  <nav class="navbar-custom">
    <div class="navbar-inner">

      <button id="hamburgerBtn" class="hamburger-btn" aria-label="Open menu" type="button">
        <span></span><span></span><span></span>
      </button>

      <a class="navbar-brand" href="../views/index.php">HYDE COUTURE</a>

      <div class="icon-group">
        <a href="../views/user_fav.php" aria-label="Favourites"><i class="bi bi-heart"></i></a>
        <a href="../views/cart.php"     aria-label="Cart"><i class="bi bi-bag"></i></a>
        <a href="../views/profile.php"  aria-label="Account"><i class="bi bi-person"></i></a>
      </div>

    </div>
  </nav>

  <div id="menuOverlay" class="menu-overlay" aria-hidden="true"></div>

  <aside id="sideMenu" class="side-menu" aria-hidden="true" role="dialog">

    <div class="menu-head">
      <div class="brand-wrap">
        <span class="brand">HYDE COUTURE</span>
        <span class="tagline">Refined Essentials</span>
      </div>
      <button class="close-btn" id="menuCloseBtn" aria-label="Close">
        <i class="bi bi-x"></i>
      </button>
    </div>

    <div class="menu-body">
      <span class="nav-label">Shop</span>
      <a class="nav-link-item" href="../views/index.php">All Products</a>
      <a class="nav-link-item" href="../views/user_fav.php">My Favourites</a>

      <div class="nav-divider"></div>

      <span class="nav-label">Orders</span>
      <a class="nav-link-item" href="../views/order_list.php">My Orders</a>
      <a class="nav-link-item" href="../views/cart.php">My Cart</a>
    </div>

    <div class="menu-bottom">
    <a class="nav-link-item" href="../views/about.php">About Us</a>
    <a class="nav-link-item" href="../views/profile.php">My Account</a>

    <!-- ADMIN DASHBOARD LINK (only visible to admins) -->
    <?php if (isset($_SESSION['roleID']) && $_SESSION['roleID'] == 2): ?>
        <a class="nav-link-item" href="../admin/profile.php" style="color: #C9A84C; font-weight: 700;">
            <i class="bi bi-crown-fill"></i> Admin Dashboard
        </a>
    <?php endif; ?>

    <a class="nav-link-item" href="../views/register.php">Register</a>
</div>

  </aside>

</div>

<script>
(function(){
  const hamburger = document.getElementById('hamburgerBtn');
  const sideMenu  = document.getElementById('sideMenu');
  const overlay   = document.getElementById('menuOverlay');
  const closeBtn  = document.getElementById('menuCloseBtn');

  function openMenu(){
    sideMenu.classList.add('open');
    overlay.classList.add('visible');
    sideMenu.setAttribute('aria-hidden','false');
    hamburger.classList.add('active');
    document.body.style.overflow = 'hidden';
    closeBtn.focus();
  }
  function closeMenu(){
    sideMenu.classList.remove('open');
    overlay.classList.remove('visible');
    sideMenu.setAttribute('aria-hidden','true');
    hamburger.classList.remove('active');
    document.body.style.overflow = '';
  }

  hamburger.addEventListener('click', openMenu);
  closeBtn.addEventListener('click', closeMenu);
  overlay.addEventListener('click', closeMenu);
  document.addEventListener('keydown', e => {
    if(e.key === 'Escape' && sideMenu.classList.contains('open')) closeMenu();
  });
})();
</script>