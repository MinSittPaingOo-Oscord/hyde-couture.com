<?php
include "../connection/connectdb.php";

$query_parent_category = " SELECT DISTINCT p.* FROM category p INNER JOIN category c ON p.categoryID = c.parentID WHERE p.parentID IS NULL";
$result_parent_category = $conn->query($query_parent_category);

$query_parent_child0_category = "SELECT p.* FROM category p LEFT JOIN category c ON p.categoryID = c.parentID WHERE p.parentID IS NULL AND c.categoryID IS NULL";
$result_parent_child0_category = $conn->query($query_parent_child0_category);

if(!isset($currentPage)){
  $currentPage = 'something.php';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Matter Makers Navbar — Rolex Green Slide Menu</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Sancreek&family=Vollkorn:wght@400;500;600&display=swap" rel="stylesheet">
  
  <style>
    :root{
      --rolex-green: #0A8A3B; 
      --rolex-accent: #6ef27a; 
      --menu-text: #ffffff;
      --overlay: rgba(0,0,0,0.35);
    }

    #hyde-nav {
      margin: 0;
      font-family: 'Vollkorn', serif;
      color: #222;
      background-color: #fff;
    }

    #hyde-nav a{
      text-decoration : none !important;
    }

    #hyde-nav .navbar-custom .row .icon-group a{
      color : black !important;
    }

    #hyde-nav .navbar-custom {
      background-color: #fff;
      border-bottom: 1px solid #eee;
      padding: 0.4rem 0;
    }
    #hyde-nav .navbar-brand {
      font-family: 'Sancreek', cursive;
      font-weight: 400;
      letter-spacing: 2px;
      font-size: 1.7rem;
      color: #000;
      white-space: nowrap;
      text-transform: uppercase;
      transition: font-size 0.3s ease;
    }

    #hyde-nav .navbar-toggler { border: none; background: none; padding: 0; }
    #hyde-nav .navbar-toggler i { font-size: 1.2rem; color: #000; }

    #hyde-nav .menu-overlay {
      position: fixed;
      inset: 0;
      background : transparent;
      z-index: 1040;
      visibility: hidden;
      opacity: 0;
      transition: opacity 0.28s ease, visibility 0.28s ease;
    }
    #hyde-nav .menu-overlay.visible {
      background: var(--overlay);
      visibility: visible;
      opacity: 1;
    }

    #hyde-nav .side-menu {
      position: fixed;
      left: -100%; 
      top: 0;
      height: 100vh;
      z-index: 1050;
      overflow-y: auto;
      background: #005A2B;
      color: var(--menu-text);
      box-shadow: 8px 0 30px rgba(0,0,0,0.25);
      transition: left 0.32s cubic-bezier(.2,.9,.2,1);
      -webkit-overflow-scrolling: touch;
      padding: 1.2rem 1rem;
      display: flex;
      flex-direction: column;
    }
    #hyde-nav .side-menu.open { left: 0; }

    @media (max-width: 575.98px) {
      #hyde-nav .side-menu { width: 100%; }
    }
    @media (min-width: 576px) and (max-width: 991.98px) {
      #hyde-nav .side-menu { width: 40%; }
    }
    @media (min-width: 992px) {
      #hyde-nav .side-menu { width: 30%; min-width: 300px; max-width: 380px; }
    }

    #hyde-nav .side-menu .close-btn {
      align-self: flex-end;
      background: transparent;
      border: none;
      color: var(--menu-text);
      font-size: 1rem;
      cursor: pointer;
      padding: 0.25rem;
      margin-bottom: 0.2rem;
    }

    #hyde-nav .side-menu .menu-header {
      display:flex;
      align-items:center;
      gap: 0.6rem;
      margin-bottom: 0.6rem;
    }
    
    #hyde-nav .side-menu .menu-header h3 {
      margin: 0;
      font-family: 'Cinzel', serif;
      letter-spacing: 1px;
      font-weight: 700;
      color: var(--menu-text);
      font-size: 0.9rem;
    }
    #hyde-nav .side-menu .menu-subtitle {
      font-size: 0.78rem;
      opacity: 0.92;
      margin-bottom: 0.8rem;
      color: rgba(255,255,255,0.95);
    }

    #hyde-nav .mmenu {
      list-style: none;
      padding: 0;
      margin: 0;
      width: 100%;
      display: flex;
      flex-direction: column;
      gap: 0.22rem;
    }
    #hyde-nav .mmenu li {
      width: 100%;
    }

    #hyde-nav .menu-item {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 0.6rem;
      padding: 0.45rem 0.15rem;
      cursor: pointer;
      user-select: none;
      border-radius: 4px;
      transition: background 0.14s ease;
      font-weight: 700;
      font-size: 0.7rem;
      letter-spacing: 0.5px;
      color: var(--menu-text);
    }
    #hyde-nav .menu-item:hover { background: rgba(255,255,255,0.03); }

    #hyde-nav .toggle-sign {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      min-width: 28px;
      min-height: 28px;
      font-size: 1.05rem;
      border-radius: 50%;
      border: 1px solid rgba(255,255,255,0.12);
      color: var(--menu-text);
      transition: transform 0.18s ease, background 0.18s ease;
    }
    #hyde-nav .toggle-sign.open { transform: rotate(45deg); }

    #hyde-nav .submenu {
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.3s ease;
      padding-left: 0.6rem;
      margin-top: 0.10rem;
      margin-bottom: 0.10rem;
    }

    #hyde-nav .side-menu a,
    #hyde-nav .side-menu a:link,
    #hyde-nav .side-menu a:visited {
      color: var(--menu-text);   
      text-decoration: none;     
      padding: 0.4rem 0.15rem;
      display: block;
      font-size: 0.7rem;          
      font-weight: 700;
    }

    #hyde-nav .accent-lime { color: #8CFF6B; }
    #hyde-nav .accent-red { color: #ff6b6b; }

    #hyde-nav .menu-bottom {
      margin-top: auto;
      padding-top: 0.6rem;
      border-top: 1px solid rgba(255,255,255,0.06);
    }

    #hyde-nav .menu-item:focus {
      outline: 2px dashed rgba(255,255,255,0.12); 
      outline-offset: 3px; 
    }

  </style>
</head>
<body>
<div id="hyde-nav">

  <div class="container-fluid navbar-custom">
    <div class="row align-items-center text-center px-2">

      <div class="col-2 d-flex justify-content-start">
        <button id="hamburgerBtn" class="navbar-toggler" aria-label="Open menu" type="button">
          <i class="bi bi-list"></i>
        </button>
      </div>

      <div class="col-8 d-flex justify-content-center">
        <a class="navbar-brand m-0" href="../views/index.php">HYDE COUTURE</a>
      </div>

      <div class="col-2 d-flex justify-content-end align-items-center icon-group gap-3">
        <a href="../views/user_fav.php"><i class="bi bi-heart fs-5"></i></a>
        <a href="../views/cart.php"><i class="bi bi-bag fs-5"></i></a>
        <a href="../views/profile.php"><i class="bi bi-person fs-5"></i></a>
    </div>
    </div>
  </div>
<?php
  if($currentPage!='register.php'){
    ?>
         <div class="promo-bar" style="background: #005A2B; color:#fff; text-align:center; padding:6px 10px; font-size:0.60rem; font-family:'Cinzel', serif;">
          <strong>NEW CUSTOMERS</strong> GET 6 USD OFF YOUR FIRST PURCHASE.
          <strong>USE CODE: NEWUSER</strong> AT CHECK OUT
        </div>
    <?php
  }
?>
 

  <div id="menuOverlay" class="menu-overlay" tabindex="-1" aria-hidden="true"></div>

  <aside id="sideMenu" class="side-menu" aria-hidden="true" aria-labelledby="menuTitle" role="dialog">
    <button class="close-btn" id="menuCloseBtn" aria-label="Close menu"><i class="bi bi-x-lg"></i></button>

    <div class="menu-subtitle">Explore — Collections / Men / Women / Accessories</div>

    <ul class="mmenu" id="mainMenu">

      <li>
        <div class="menu-item" tabindex="0"><span class="accent-red"><a href='../views/index.php'>ALL</a></span></div>
      </li>
      
      <?php 
               if ($result_parent_category && $result_parent_category->num_rows > 0) {
                  while ($row_parent_category = $result_parent_category ->fetch_assoc()) {
                      echo "<li>";
                          $parent_id = $row_parent_category['categoryID'];
                          $submenu_id = "submenu_" . $parent_id;

                          echo "<div class='menu-item' data-toggle='collapse' tabindex='0' role='button' aria-expanded='false' aria-controls='".$submenu_id."'>";
                              echo "<span>".$row_parent_category['categoryName']."</span>";
                              echo "<span class='toggle-sign' aria-hidden='true'>+</span>";
                          echo "</div>";

                          $query_child_category = "SELECT * FROM category WHERE parentID =".intval($parent_id);
                          $result_child_category = $conn->query($query_child_category);

                          echo "<div id='".$submenu_id."' class='submenu' aria-hidden='true'>";
                      
                          if ($result_child_category && $result_child_category->num_rows > 0) {
                              while ($row_child_category = $result_child_category ->fetch_assoc()) {
                                  $child_link = "../views/index.php?id=" . $row_child_category['categoryID'];
                                  echo "<a href='".$child_link."'>".$row_child_category['categoryName']."</a>";
                              }
                          }
                          echo "</div>";
                          echo"</li>";
                        }
                    }
                  
                    if ($result_parent_child0_category && $result_parent_child0_category->num_rows > 0) {
                      while ($row_parent_child0_category = $result_parent_child0_category ->fetch_assoc()) {
                        echo "<li>";
                        $child_link = "../views/index.php?id=" . $row_parent_child0_category['categoryID'];
                        echo "<div class='menu-item' tabindex='0'><span class='accent-red'><a href='".$child_link."'>".$row_parent_child0_category['categoryName']."</a></span></div>";
                        echo "</li>";
                      }
                    }
                  ?>

                  <div class="menu-bottom">
                      <li>
                        <div class="menu-item" tabindex="0"><a href="../views/about.php" style="color:var(--menu-text); text-decoration:none">ABOUT US</a></div>
                      </li>
                      <li>
                          <div class="menu-item" tabindex="0"><a href="../views/profile.php" style="color:var(--menu-text); text-decoration:none">MY ACCOUNT</a></div>
                      </li>
                      <li>
                          <div class="menu-item" tabindex="0"><a href="../views/register.php" style="color:var(--menu-text); text-decoration:none">REGISTER</a></div>
                      </li>
                  </div>
    </ul>

  </aside>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    (function(){
      const hamburger = document.getElementById('hamburgerBtn');
      const sideMenu = document.getElementById('sideMenu');
      const overlay = document.getElementById('menuOverlay');
      const closeBtn = document.getElementById('menuCloseBtn');

      function openMenu(){
        sideMenu.classList.add('open');
        overlay.classList.add('visible');
        sideMenu.setAttribute('aria-hidden','false');
        overlay.setAttribute('aria-hidden','false');
        closeBtn.focus();
      }

      function closeMenu(){
        sideMenu.classList.remove('open');
        overlay.classList.remove('visible');
        sideMenu.setAttribute('aria-hidden','true');
        overlay.setAttribute('aria-hidden','true');
        hamburger.focus();
      }

      hamburger.addEventListener('click', openMenu);
      closeBtn.addEventListener('click', closeMenu);
      overlay.addEventListener('click', closeMenu);

      document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && sideMenu.classList.contains('open')) closeMenu();
      });

      document.querySelectorAll('.menu-item[data-toggle="collapse"]').forEach(btn => {
        const controls = btn.getAttribute('aria-controls');
        const submenu = document.getElementById(controls);
        const sign = btn.querySelector('.toggle-sign');

        function openSub(){
          const scrollHeight = submenu.scrollHeight;
          submenu.classList.add('open');
          submenu.style.maxHeight = scrollHeight + 'px';
          btn.setAttribute('aria-expanded','true');
          submenu.setAttribute('aria-hidden','false');
          if(sign) sign.classList.add('open');
        }
        function closeSub(){
          submenu.style.maxHeight = 0;
          submenu.classList.remove('open');
          btn.setAttribute('aria-expanded','false');
          submenu.setAttribute('aria-hidden','true');
          if(sign) sign.classList.remove('open');
        }

        btn.addEventListener('click', function(e){
          const isOpen = submenu.classList.contains('open');
          if(isOpen) closeSub(); else openSub();
        });

        btn.addEventListener('keydown', function(e){
          if(e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            btn.click();
          }
        });

        closeSub();
      });

      window.addEventListener('resize', () => {
        document.querySelectorAll('.submenu.open').forEach(s => {
          s.style.maxHeight = s.scrollHeight + 'px';
        });
      });

    })();
  </script>

</div>
</body>
</html>
