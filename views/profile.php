<?php 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../connection/connectdb.php';
include '../layout/nav.php';
include './log_in_check.php'; 

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile | HYDE COUTURE</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;500;600;700&family=Vollkorn:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --green:      #006039;
            --green-dark: #004d2e;
            --gold:       #C9B037;
            --white:      #FFFFFF;
            --black:      #111111;
            --gray-light: #F5F5F3;
            --gray-mid:   #e8e8e5;
            --gray-text:  #555;
        }

        body {
            font-family: 'Vollkorn', serif;
            background-color: var(--gray-light);
            color: var(--black);
            line-height: 1.6;
            min-height: 100vh;
        }

        /* ── Page wrapper ── */
        .profile-page {
            max-width: 1100px;
            margin: 0 auto;
            padding: 40px 20px 80px;
        }

        /* ── Page heading ── */
        .page-heading {
            text-align: center;
            margin-bottom: 40px;
            animation: fadeUp .7s ease both;
        }
        .page-heading h1 {
            font-family: 'Cinzel', serif;
            font-size: 2rem;
            color: var(--green);
            letter-spacing: 3px;
            text-transform: uppercase;
        }
        .page-heading .gold-line {
            width: 60px; height: 2px;
            background: var(--gold);
            margin: 10px auto 0;
        }

        /* ── Spending banner ── */
        .spending-banner {
            background: linear-gradient(135deg, var(--green) 0%, var(--green-dark) 100%);
            color: var(--white);
            padding: 24px 30px;
            border-radius: 2px;
            margin-bottom: 28px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            overflow: hidden;
            animation: fadeUp .7s ease .1s both;
        }
        .spending-banner::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0;
            height: 3px; background: var(--gold);
        }
        .spending-banner::after {
            content: '';
            position: absolute; right: -40px; top: -40px;
            width: 160px; height: 160px;
            border: 1px solid rgba(201,176,55,.15);
            border-radius: 50%;
        }
        .spending-left h2 {
            font-family: 'Cinzel', serif;
            font-size: 1.5rem;
            letter-spacing: 1px;
        }
        .spending-left p {
            font-size: .9rem;
            opacity: .8;
            margin-top: 4px;
            letter-spacing: 1px;
        }
        .spending-badge {
            background: rgba(201,176,55,.2);
            border: 1px solid var(--gold);
            color: var(--gold);
            padding: 6px 18px;
            font-family: 'Cinzel', serif;
            font-size: .75rem;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        /* ── Two-column layout ── */
        .profile-grid {
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 24px;
        }

        /* ── Sidebar (photo + name) ── */
        .profile-sidebar {
            animation: fadeUp .7s ease .15s both;
        }
        .sidebar-card {
            background: var(--white);
            padding: 36px 24px;
            text-align: center;
            box-shadow: 0 4px 24px rgba(0,0,0,.06);
            border-top: 3px solid var(--gold);
            position: sticky;
            top: 20px;
        }
        .avatar-wrap {
            position: relative;
            display: inline-block;
            margin-bottom: 20px;
        }
        .avatar-wrap img {
            width: 160px; height: 160px;
            object-fit: cover;
            border: 3px solid var(--gray-mid);
            display: block;
        }
        .avatar-wrap .avatar-ring {
            position: absolute;
            inset: -8px;
            border: 1px solid var(--gold);
            pointer-events: none;
        }
        .sidebar-name {
            font-family: 'Cinzel', serif;
            font-size: 1.2rem;
            color: var(--green);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 4px;
        }
        .sidebar-id {
            font-size: .85rem;
            color: var(--gray-text);
            letter-spacing: 1px;
        }

        /* Sidebar nav linBHAT */
        .sidebar-nav {
            margin-top: 28px;
            border-top: 1px solid var(--gray-mid);
            padding-top: 20px;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            font-family: 'Vollkorn', serif;
            font-size: .95rem;
            color: var(--gray-text);
            text-decoration: none;
            letter-spacing: .5px;
            transition: all .25s;
            border-left: 2px solid transparent;
        }
        .sidebar-nav a:hover,
        .sidebar-nav a.active {
            color: var(--green);
            background: rgba(0,96,57,.04);
            border-left-color: var(--green);
        }
        .sidebar-nav a i { width: 16px; text-align: center; }

        /* ── Main content ── */
        .profile-main {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        /* ── Cards ── */
        .profile-card {
            background: var(--white);
            box-shadow: 0 4px 24px rgba(0,0,0,.06);
            animation: fadeUp .7s ease .2s both;
            overflow: hidden;
        }
        .card-header {
            padding: 20px 28px;
            border-bottom: 1px solid var(--gray-mid);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .card-title {
            font-family: 'Cinzel', serif;
            font-size: 1rem;
            color: var(--green);
            letter-spacing: 2px;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .card-title i { color: var(--gold); font-size: .85rem; }
        .card-body { padding: 24px 28px; }

        /* ── Info rows ── */
        .info-row {
            display: flex;
            align-items: baseline;
            padding: 13px 0;
            border-bottom: 1px solid var(--gray-mid);
            transition: background .2s, padding-left .2s;
        }
        .info-row:last-child { border-bottom: none; }
        .info-row:hover { background: rgba(0,96,57,.02); padding-left: 6px; border-radius: 2px; }
        .info-label {
            width: 180px;
            flex-shrink: 0;
            font-size: .78rem;
            font-weight: 600;
            color: var(--gray-text);
            text-transform: uppercase;
            letter-spacing: 1.2px;
        }
        .info-value {
            flex: 1;
            font-size: 1rem;
            color: var(--black);
        }

        /* ── Address cards ── */
        .address-card {
            border: 1px solid var(--gray-mid);
            border-left: 3px solid var(--green);
            padding: 18px 20px;
            margin-bottom: 16px;
            transition: box-shadow .25s;
        }
        .address-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.08); }
        .address-card:last-child { margin-bottom: 0; }
        .addr-label {
            font-family: 'Cinzel', serif;
            font-size: .75rem;
            color: var(--green);
            letter-spacing: 2px;
            margin-bottom: 12px;
        }

        .map-link {
            color: var(--green);
            text-decoration: none;
            font-weight: 600;
            font-size: .9rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: color .2s;
        }
        .map-link:hover { color: var(--green-dark); text-decoration: underline; }

        /* ── Action buttons ── */
        .card-actions {
            padding: 0 28px 24px;
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
        }
        .hc-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 28px;
            font-family: 'Cinzel', serif;
            font-size: .8rem;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all .3s;
            position: relative;
            overflow: hidden;
        }
        .hc-btn::after {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,.25), transparent);
            transition: left .5s;
        }
        .hc-btn:hover::after { left: 100%; }
        .hc-btn:hover { transform: translateY(-2px); }

        .hc-btn-primary { background: var(--green); color: var(--white); }
        .hc-btn-primary:hover { background: var(--green-dark); color: var(--white); }
        .hc-btn-outline {
            background: transparent;
            color: var(--green);
            border: 1.5px solid var(--green);
        }
        .hc-btn-outline:hover { background: var(--green); color: var(--white); }
        .hc-btn-danger { background: #b91c1c; color: var(--white); }
        .hc-btn-danger:hover { background: #991b1b; }

        /* ── No data state ── */
        .no-data {
            text-align: center;
            padding: 40px 20px;
            color: var(--gray-text);
            font-style: italic;
            font-size: 1rem;
        }

        /* ── Animations ── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── Responsive ── */
        @media (max-width: 860px) {
            .profile-grid { grid-template-columns: 1fr; }
            .sidebar-card { position: static; }
        }
        @media (max-width: 540px) {
            .spending-banner { flex-direction: column; gap: 14px; text-align: center; }
            .info-label { width: 120px; }
        }
    </style>
</head>
<body>

<?php
$login = $_SESSION['login'] ?? false;

if ($login):
    $userID = $_SESSION['accountID'];
    $result = $conn->query("SELECT * FROM account JOIN photo ON account.profile = photo.photoID WHERE account.accountID = $userID");
    $row = $result ? $result->fetch_assoc() : null;

    // Total spending
    $spendRes = $conn->query("SELECT COALESCE(SUM(totalCost),0) AS total FROM orderr WHERE accountID = $userID AND paymentStatus != 0");
    $totalSpend = $spendRes ? $spendRes->fetch_assoc()['total'] : 0;

    // Addresses
    $addrRes = $conn->query("SELECT * FROM address WHERE accountID = $userID");

    if ($row):
?>

<div class="profile-page">

    <!-- Page heading -->
    <div class="page-heading">
        <h1>My Profile</h1>
        <div class="gold-line"></div>
    </div>

    <!-- Spending banner -->
    <div class="spending-banner">
        <div class="spending-left">
            <h2>TOTAL SPENDING: <?php echo number_format($totalSpend); ?> BHAT</h2>
            <p>EXCLUSIVE MEMBER · ID #<?php echo $row['accountID']; ?></p>
        </div>
        <div class="spending-badge">Active</div>
    </div>

    <!-- Grid -->
    <div class="profile-grid">
        <!-- Sidebar -->
        <div class="profile-sidebar">
            <div class="sidebar-card">
                <div class="avatar-wrap">
                    <img src="../image/<?php echo htmlspecialchars($row['photoName']); ?>" alt="Profile Photo">
                    <div class="avatar-ring"></div>
                </div>
                <div class="sidebar-name"><?php echo htmlspecialchars($row['name']); ?></div>
                <div class="sidebar-id">Member since <?php echo date('Y', strtotime($row['registerDate'] ?? 'now')); ?></div>
                <nav class="sidebar-nav">
                    <a href="" class="active"><i class="fas fa-user"></i> Profile</a>
                    <a href="user_fav.php"><i class="fas fa-heart"></i> Favourites</a>
                    <a href="order_list.php?userID=<?php echo $row['accountID']; ?>"><i class="fas fa-box"></i> Orders</a>
                    <a href="edit_profile.php?userID=<?php echo $row['accountID']; ?>"><i class="fas fa-edit"></i> Edit Profile</a>
                    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Log Out</a>
                </nav>
            </div>
        </div>

        <!-- Main -->
        <div class="profile-main">

            <!-- Personal Details -->
            <div class="profile-card">
                <div class="card-header">
                    <div class="card-title"><i class="fas fa-crown"></i> Personal Details</div>
                    <a href="edit_profile.php?userID=<?php echo $row['accountID']; ?>" class="hc-btn hc-btn-outline" style="padding:8px 18px;">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <div class="info-label">Full Name</div>
                        <div class="info-value"><?php echo htmlspecialchars($row['name']); ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Email</div>
                        <div class="info-value"><?php echo htmlspecialchars($row['email']); ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Phone</div>
                        <div class="info-value"><?php echo htmlspecialchars($row['phoneNumber']); ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Birthday</div>
                        <div class="info-value"><?php echo htmlspecialchars($row['birthday'] ?? '—'); ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Account ID</div>
                        <div class="info-value">#<?php echo $row['accountID']; ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Registered</div>
                        <div class="info-value"><?php echo htmlspecialchars($row['registerDate'] ?? '—'); ?></div>
                    </div>
                </div>
            </div>

            <!-- Addresses -->
            <div class="profile-card">
                <div class="card-header">
                    <div class="card-title"><i class="fas fa-map-marker-alt"></i> Saved Addresses</div>
                </div>
                <div class="card-body">
                    <?php if ($addrRes && $addrRes->num_rows > 0):
                        $addrNum = 1;
                        while ($addr = $addrRes->fetch_assoc()):
                    ?>
                    <div class="address-card">
                        <div class="addr-label">ADDRESS <?php echo $addrNum++; ?></div>
                        <div class="info-row">
                            <div class="info-label">Street</div>
                            <div class="info-value"><?php echo htmlspecialchars($addr['street']); ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Township</div>
                            <div class="info-value"><?php echo htmlspecialchars($addr['township']); ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">City / State</div>
                            <div class="info-value"><?php echo htmlspecialchars($addr['city'] . ', ' . $addr['state']); ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Country</div>
                            <div class="info-value"><?php echo htmlspecialchars($addr['country']); ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Full Address</div>
                            <div class="info-value"><?php echo htmlspecialchars($addr['completeAddress']); ?></div>
                        </div>
                        <?php if (!empty($addr['mapLink'])): ?>
                        <div style="margin-top:10px;">
                            <a href="<?php echo htmlspecialchars($addr['mapLink']); ?>" target="_blank" class="map-link">
                                <i class="fas fa-map-marker-alt"></i> View on Google Maps
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endwhile; else: ?>
                        <p class="no-data">No addresses saved yet.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Orders -->
            <div class="profile-card">
                <div class="card-header">
                    <div class="card-title"><i class="fas fa-history"></i> Orders</div>
                </div>
                <div class="card-body" style="padding-bottom:0;">
                </div>
                <div class="card-actions">
                    <a href="order_list.php?userID=<?php echo $row['accountID']; ?>" class="hc-btn hc-btn-primary">
                        <i class="fas fa-box"></i> View My Orders
                    </a>
                </div>
            </div>

            <!-- Danger zone -->
            <div class="profile-card">
                <div class="card-header">
                    <div class="card-title"><i class="fas fa-sign-out-alt"></i> Session</div>
                </div>
                <div class="card-actions" style="padding-top:20px;">
                    <a href="logout.php" class="hc-btn hc-btn-danger">
                        <i class="fas fa-sign-out-alt"></i> Log Out
                    </a>
                </div>
            </div>

        </div><!-- /main -->
    </div><!-- /grid -->
</div><!-- /page -->

<?php
    else:
        echo "<div style='text-align:center;padding:80px 20px;font-family:Cinzel,serif;color:#006039;'>User not found. Please log in again.</div>";
    endif;
else:
    include "./tologin_message.php";
endif;

include "../layout/footer.php";
?>
</body>
</html>