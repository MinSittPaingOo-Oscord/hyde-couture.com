<?php 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../connection/connectdb.php';
$currentPage = "edit_profile.php";
include './log_in_check.php'; 

$userID = $_GET['userID'] ?? $_SESSION['accountID'];

// Delete address
if (isset($_GET['delete_address'])) {
    $deleteID = intval($_GET['delete_address']);
    $conn->query("DELETE FROM address WHERE addressID = $deleteID");
    $_SESSION['success_message'][] = 'Address deleted.';
    header("Location: edit_profile.php?userID=$userID");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Update account
    if (isset($_POST['update_account'])) {
        $name        = $conn->real_escape_string($_POST['name']);
        $email       = $conn->real_escape_string($_POST['email']);
        $phoneNumber = $conn->real_escape_string($_POST['phoneNumber']);
        $birthday    = $conn->real_escape_string($_POST['birthday']);

        $conn->query("UPDATE account SET name='$name', email='$email', phoneNumber='$phoneNumber', birthday='$birthday' WHERE accountID=$userID");
        $_SESSION['success_message'][] = 'Profile updated successfully.';

        if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] == 0) {
            $fileName   = basename($_FILES['profile_photo']['name']);
            $targetPath = '../image/' . $fileName;
            move_uploaded_file($_FILES['profile_photo']['tmp_name'], $targetPath);
            $fn = $conn->real_escape_string($fileName);
            $conn->query("INSERT INTO photo (photoName) VALUES ('$fn')");
            $newPhotoID = $conn->insert_id;
            $conn->query("UPDATE account SET profile=$newPhotoID WHERE accountID=$userID");
            $_SESSION['success_message'][] = 'Profile photo updated.';
        }

        header("Location: edit_profile.php?userID=$userID");
        exit();
    }

    // Edit existing address
    if (isset($_POST['edit_address'])) {
        $addrID          = intval($_POST['addressID']);
        $street          = $conn->real_escape_string($_POST['street']);
        $township        = $conn->real_escape_string($_POST['township']);
        $city            = $conn->real_escape_string($_POST['city']);
        $state           = $conn->real_escape_string($_POST['state']);
        $postalCode      = $conn->real_escape_string($_POST['postalCode']);
        $country         = $conn->real_escape_string($_POST['country']);
        $completeAddress = $conn->real_escape_string($_POST['completeAddress']);
        $mapLink         = $conn->real_escape_string($_POST['mapLink'] ?? '');

        $conn->query("UPDATE address SET street='$street', township='$township', city='$city', state='$state',
                      postalCode='$postalCode', country='$country', completeAddress='$completeAddress', mapLink='$mapLink'
                      WHERE addressID=$addrID AND accountID=$userID");
        $_SESSION['success_message'][] = 'Address updated.';
        header("Location: edit_profile.php?userID=$userID");
        exit();
    }

    // Add address
    if (isset($_POST['add_address'])) {
        $street          = $conn->real_escape_string($_POST['street']);
        $township        = $conn->real_escape_string($_POST['township']);
        $city            = $conn->real_escape_string($_POST['city']);
        $state           = $conn->real_escape_string($_POST['state']);
        $postalCode      = $conn->real_escape_string($_POST['postalCode']);
        $country         = $conn->real_escape_string($_POST['country']);
        $completeAddress = $conn->real_escape_string($_POST['completeAddress']);
        $mapLink         = $conn->real_escape_string($_POST['mapLink'] ?? '');

        $conn->query("INSERT INTO address (street,township,city,state,postalCode,country,completeAddress,mapLink,accountID)
                      VALUES ('$street','$township','$city','$state','$postalCode','$country','$completeAddress','$mapLink',$userID)");
        $_SESSION['success_message'][] = 'New address added.';
        header("Location: edit_profile.php?userID=$userID");
        exit();
    }

    // Update passcode
    if (isset($_POST['update_security'])) {
        $current = $_POST['current_passcode'];
        $new     = $_POST['new_passcode'];
        $confirm = $_POST['confirm_passcode'];

        $secRes = $conn->query("SELECT passcode FROM account WHERE accountID=$userID");
        $secRow = $secRes->fetch_assoc();

        if ($secRow['passcode'] === $current) {
            if (!empty($new) && $new === $confirm) {
                $np = $conn->real_escape_string($new);
                $conn->query("UPDATE account SET passcode='$np' WHERE accountID=$userID");
                $_SESSION['success_message'][] = 'Passcode updated successfully.';
            } else {
                $_SESSION['success_message'][] = 'New passcodes do not match.';
            }
        } else {
            $_SESSION['success_message'][] = 'Current passcode is incorrect.';
        }

        header("Location: edit_profile.php?userID=$userID");
        exit();
    }
}

// Load data
$result         = $conn->query("SELECT * FROM account JOIN photo ON account.profile = photo.photoID WHERE account.accountID = $userID");
$row            = $result ? $result->fetch_assoc() : null;
$result_address = $conn->query("SELECT * FROM address WHERE accountID=$userID");
$addresses      = [];
while ($a = $result_address->fetch_assoc()) $addresses[] = $a;

include '../layout/nav.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile | HYDE COUTURE</title>
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
            --red:        #b91c1c;
        }

        body {
            font-family: 'Vollkorn', serif;
            background-color: var(--gray-light);
            color: var(--black);
            line-height: 1.6;
            min-height: 100vh;
        }

        .edit-page {
            max-width: 1100px;
            margin: 0 auto;
            padding: 40px 20px 80px;
        }

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

        /* Grid */
        .edit-grid {
            display: grid;
            grid-template-columns: 260px 1fr;
            gap: 24px;
        }

        /* Sidebar */
        .edit-sidebar { animation: fadeUp .7s ease .1s both; }
        .sidebar-card {
            background: var(--white);
            box-shadow: 0 4px 24px rgba(0,0,0,.06);
            border-top: 3px solid var(--gold);
            padding: 30px 20px;
            text-align: center;
            position: sticky;
            top: 20px;
        }
        .avatar-wrap {
            position: relative;
            display: inline-block;
            margin-bottom: 16px;
            cursor: pointer;
        }
        .avatar-wrap img {
            width: 140px; height: 140px;
            object-fit: cover;
            border: 3px solid var(--gray-mid);
            display: block;
        }
        .avatar-ring {
            position: absolute;
            inset: -8px;
            border: 1px solid var(--gold);
            pointer-events: none;
        }
        .avatar-upload-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0,96,57,.6);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity .3s;
        }
        .avatar-wrap:hover .avatar-upload-overlay { opacity: 1; }
        .avatar-upload-overlay i { color: var(--white); font-size: 1.4rem; }
        .sidebar-name {
            font-family: 'Cinzel', serif;
            font-size: 1.1rem;
            color: var(--green);
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .sidebar-hint { font-size: .8rem; color: var(--gray-text); margin-top: 6px; font-style: italic; }

        .sidebar-nav {
            margin-top: 24px;
            border-top: 1px solid var(--gray-mid);
            padding-top: 16px;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .sidebar-nav a {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 12px;
            font-size: .9rem; color: var(--gray-text);
            text-decoration: none; letter-spacing: .5px;
            transition: all .2s;
            border-left: 2px solid transparent;
        }
        .sidebar-nav a:hover, .sidebar-nav a.active {
            color: var(--green);
            background: rgba(0,96,57,.04);
            border-left-color: var(--green);
        }
        .sidebar-nav a i { width: 14px; text-align: center; }

        /* Cards */
        .edit-main { display: flex; flex-direction: column; gap: 24px; }
        .edit-card {
            background: var(--white);
            box-shadow: 0 4px 24px rgba(0,0,0,.06);
            animation: fadeUp .7s ease .2s both;
            overflow: hidden;
        }
        .card-header {
            padding: 18px 28px;
            border-bottom: 1px solid var(--gray-mid);
            display: flex; align-items: center; gap: 10px;
        }
        .card-title {
            font-family: 'Cinzel', serif;
            font-size: .9rem; color: var(--green);
            letter-spacing: 2px; text-transform: uppercase;
        }
        .card-title-icon { color: var(--gold); font-size: .85rem; }
        .card-body { padding: 24px 28px; }

        /* Forms */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 18px;
        }
        .form-group { display: flex; flex-direction: column; gap: 6px; }
        .form-group.full { grid-column: 1 / -1; }
        .form-group label {
            font-size: .78rem; font-weight: 600;
            color: var(--gray-text); text-transform: uppercase; letter-spacing: 1px;
        }
        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 10px 14px;
            border: 1px solid var(--gray-mid);
            background: var(--gray-light);
            font-family: 'Vollkorn', serif;
            font-size: .95rem; color: var(--black);
            border-radius: 0;
            transition: border-color .25s, background .25s, box-shadow .25s;
        }
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--green);
            background: var(--white);
            box-shadow: 0 0 0 3px rgba(0,96,57,.08);
        }
        .form-group textarea { resize: vertical; min-height: 70px; }
        #photo-file-input { display: none; }

        /* Buttons */
        .hc-btn {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 11px 26px;
            font-family: 'Cinzel', serif;
            font-size: .78rem; letter-spacing: 1.5px;
            text-transform: uppercase; text-decoration: none;
            border: none; cursor: pointer;
            transition: all .3s;
            position: relative; overflow: hidden;
        }
        .hc-btn::after {
            content: '';
            position: absolute; top: 0; left: -100%;
            width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,.25), transparent);
            transition: left .5s;
        }
        .hc-btn:hover::after { left: 100%; }
        .hc-btn:hover { transform: translateY(-2px); }
        .hc-btn-primary { background: var(--green); color: var(--white); }
        .hc-btn-primary:hover { background: var(--green-dark); }
        .hc-btn-danger  { background: var(--red); color: var(--white); }
        .hc-btn-danger:hover  { background: #991b1b; }
        .hc-btn-ghost {
            background: transparent; color: var(--gray-text);
            border: 1px solid var(--gray-mid);
        }
        .hc-btn-ghost:hover { border-color: var(--green); color: var(--green); }
        .hc-btn-warning { background: #d97706; color: var(--white); }
        .hc-btn-warning:hover { background: #b45309; }

        .form-actions { margin-top: 22px; display: flex; gap: 12px; flex-wrap: wrap; }

        /* Address existing cards */
        .address-existing {
            border: 1px solid var(--gray-mid);
            border-left: 3px solid var(--green);
            margin-bottom: 20px;
            overflow: hidden;
        }
        .address-existing:last-of-type { margin-bottom: 0; }

        .addr-view {
            padding: 20px;
        }
        .addr-num {
            font-family: 'Cinzel', serif;
            font-size: .72rem; color: var(--green);
            letter-spacing: 2px; margin-bottom: 14px;
        }
        .addr-detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px,1fr));
            gap: 10px 20px;
            margin-bottom: 16px;
        }
        .addr-detail-grid span {
            font-size: .75rem; color: var(--gray-text);
            text-transform: uppercase; letter-spacing: .8px;
            display: block; margin-bottom: 2px;
        }
        .addr-detail-grid p { font-size: .95rem; color: var(--black); margin: 0; }
        .addr-actions { display: flex; gap: 10px; flex-wrap: wrap; }

        /* Inline edit form for each address */
        .addr-edit-form {
            display: none;
            padding: 20px;
            background: var(--gray-light);
            border-top: 1px solid var(--gray-mid);
        }
        .addr-edit-form.open { display: block; }
        .addr-edit-form .form-grid { margin-bottom: 0; }

        /* Toast */
        .toast-wrap {
            position: fixed; top: 24px; right: 24px;
            z-index: 9999; display: flex; flex-direction: column; gap: 10px;
        }
        .toast {
            background: var(--green); color: var(--white);
            padding: 14px 20px;
            font-family: 'Vollkorn', serif; font-size: .95rem;
            display: flex; align-items: center; gap: 10px;
            box-shadow: 0 6px 20px rgba(0,0,0,.2);
            animation: toastIn .4s ease both, toastOut .4s ease 3s both;
        }
        @keyframes toastIn  { from { opacity:0; transform:translateX(60px); } to { opacity:1; transform:translateX(0); } }
        @keyframes toastOut { from { opacity:1; } to { opacity:0; pointer-events:none; } }

        @keyframes fadeUp {
            from { opacity:0; transform:translateY(20px); }
            to   { opacity:1; transform:translateY(0); }
        }

        @media (max-width: 820px) {
            .edit-grid { grid-template-columns: 1fr; }
            .sidebar-card { position: static; }
        }
        @media (max-width: 520px) {
            .form-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<!-- Toasts -->
<?php if (isset($_SESSION['success_message'])): ?>
<div class="toast-wrap">
    <?php foreach ($_SESSION['success_message'] as $msg): ?>
        <div class="toast"><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($msg); ?></div>
    <?php endforeach; ?>
</div>
<?php unset($_SESSION['success_message']); endif; ?>

<?php
$login = $_SESSION['login'] ?? false;
if ($login && $row):
?>

<div class="edit-page">

    <div class="page-heading">
        <h1>Edit Profile</h1>
        <div class="gold-line"></div>
    </div>

    <div class="edit-grid">

        <!-- Sidebar -->
        <div class="edit-sidebar">
            <div class="sidebar-card">
                <div class="avatar-wrap" onclick="document.getElementById('photo-file-input').click()">
                    <img src="../image/<?php echo htmlspecialchars($row['photoName']); ?>"
                         alt="Profile Photo" id="avatar-preview">
                    <div class="avatar-ring"></div>
                    <div class="avatar-upload-overlay"><i class="fas fa-camera"></i></div>
                </div>
                <div class="sidebar-name"><?php echo htmlspecialchars($row['name']); ?></div>
                <p class="sidebar-hint">Click photo to change</p>

                <nav class="sidebar-nav">
                    <a href="user.php"><i class="fas fa-user"></i> Profile</a>
                    <a href="user_fav.php"><i class="fas fa-heart"></i> Favourites</a>
                    <a href="order_list.php?userID=<?php echo $row['accountID']; ?>"><i class="fas fa-box"></i> Orders</a>
                    <a href="edit_profile.php?userID=<?php echo $row['accountID']; ?>" class="active"><i class="fas fa-edit"></i> Edit Profile</a>
                    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Log Out</a>
                </nav>
            </div>
        </div>

        <!-- Main -->
        <div class="edit-main">

            <!-- Personal details -->
            <div class="edit-card">
                <div class="card-header">
                    <i class="fas fa-crown card-title-icon"></i>
                    <span class="card-title">Personal Details</span>
                </div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <input type="file" id="photo-file-input" name="profile_photo" accept="image/*">
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Full Name</label>
                                <input type="text" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Phone Number</label>
                                <input type="text" name="phoneNumber" value="<?php echo htmlspecialchars($row['phoneNumber']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Birthday</label>
                                <input type="date" name="birthday" value="<?php echo htmlspecialchars($row['birthday'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" name="update_account" class="hc-btn hc-btn-primary">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                            <a href="user.php" class="hc-btn hc-btn-ghost">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Passcode -->
            <div class="edit-card">
                <div class="card-header">
                    <i class="fas fa-lock card-title-icon"></i>
                    <span class="card-title">Change Passcode</span>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Current Passcode</label>
                                <input type="password" name="current_passcode" required>
                            </div>
                            <div class="form-group">
                                <label>New Passcode</label>
                                <input type="password" name="new_passcode" required>
                            </div>
                            <div class="form-group">
                                <label>Confirm New Passcode</label>
                                <input type="password" name="confirm_passcode" required>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" name="update_security" class="hc-btn hc-btn-primary">
                                <i class="fas fa-key"></i> Update Passcode
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Addresses — edit + delete -->
            <div class="edit-card">
                <div class="card-header">
                    <i class="fas fa-map-marker-alt card-title-icon"></i>
                    <span class="card-title">Saved Addresses</span>
                </div>
                <div class="card-body">
                    <?php if (count($addresses) > 0):
                        foreach ($addresses as $i => $addr):
                            $n = $i + 1;
                    ?>
                    <div class="address-existing" id="addr-block-<?php echo $addr['addressID']; ?>">

                        <!-- View mode -->
                        <div class="addr-view" id="addr-view-<?php echo $addr['addressID']; ?>">
                            <div class="addr-num">ADDRESS <?php echo $n; ?></div>
                            <div class="addr-detail-grid">
                                <div><span>Street</span><p><?php echo htmlspecialchars($addr['street']); ?></p></div>
                                <div><span>Township</span><p><?php echo htmlspecialchars($addr['township']); ?></p></div>
                                <div><span>City</span><p><?php echo htmlspecialchars($addr['city']); ?></p></div>
                                <div><span>State</span><p><?php echo htmlspecialchars($addr['state']); ?></p></div>
                                <div><span>Postal Code</span><p><?php echo htmlspecialchars($addr['postalCode']); ?></p></div>
                                <div><span>Country</span><p><?php echo htmlspecialchars($addr['country']); ?></p></div>
                                <?php if (!empty($addr['completeAddress'])): ?>
                                <div style="grid-column:1/-1"><span>Full Address</span><p><?php echo htmlspecialchars($addr['completeAddress']); ?></p></div>
                                <?php endif; ?>
                            </div>
                            <div class="addr-actions">
                                <button type="button" class="hc-btn hc-btn-warning"
                                        onclick="toggleAddrEdit(<?php echo $addr['addressID']; ?>)"
                                        style="padding:8px 18px;font-size:.72rem;">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <a href="edit_profile.php?userID=<?php echo $userID; ?>&delete_address=<?php echo $addr['addressID']; ?>"
                                   class="hc-btn hc-btn-danger"
                                   onclick="return confirm('Delete this address?');"
                                   style="padding:8px 18px;font-size:.72rem;">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </div>
                        </div>

                        <!-- Inline edit form (hidden by default) -->
                        <div class="addr-edit-form" id="addr-edit-<?php echo $addr['addressID']; ?>">
                            <form method="POST">
                                <input type="hidden" name="addressID" value="<?php echo $addr['addressID']; ?>">
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label>Street</label>
                                        <input type="text" name="street" value="<?php echo htmlspecialchars($addr['street']); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Township</label>
                                        <input type="text" name="township" value="<?php echo htmlspecialchars($addr['township']); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label>City</label>
                                        <input type="text" name="city" value="<?php echo htmlspecialchars($addr['city']); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label>State / Region</label>
                                        <input type="text" name="state" value="<?php echo htmlspecialchars($addr['state']); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Postal Code</label>
                                        <input type="text" name="postalCode" value="<?php echo htmlspecialchars($addr['postalCode']); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Country</label>
                                        <select name="country" required>
                                            <option value="Myanmar"  <?php echo $addr['country']==='Myanmar'  ? 'selected':''; ?>>Myanmar</option>
                                            <option value="Thailand" <?php echo $addr['country']==='Thailand' ? 'selected':''; ?>>Thailand</option>
                                            <option value="Other"    <?php echo ($addr['country']!=='Myanmar'&&$addr['country']!=='Thailand') ? 'selected':''; ?>>Other</option>
                                        </select>
                                    </div>
                                    <div class="form-group full">
                                        <label>Complete Address</label>
                                        <textarea name="completeAddress"><?php echo htmlspecialchars($addr['completeAddress']); ?></textarea>
                                    </div>
                                    <div class="form-group full">
                                        <label>Google Map Link (optional)</label>
                                        <input type="url" name="mapLink" value="<?php echo htmlspecialchars($addr['mapLink'] ?? ''); ?>" placeholder="https://maps.app.goo.gl/...">
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" name="edit_address" class="hc-btn hc-btn-primary">
                                        <i class="fas fa-save"></i> Save Address
                                    </button>
                                    <button type="button" class="hc-btn hc-btn-ghost"
                                            onclick="toggleAddrEdit(<?php echo $addr['addressID']; ?>)">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>

                    </div><!-- /address-existing -->
                    <?php endforeach; else: ?>
                        <p style="color:var(--gray-text);font-style:italic;">No addresses saved yet.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Add new address -->
            <div class="edit-card">
                <div class="card-header">
                    <i class="fas fa-plus card-title-icon"></i>
                    <span class="card-title">Add New Address</span>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Street</label>
                                <input type="text" name="street" placeholder="Street / Road" required>
                            </div>
                            <div class="form-group">
                                <label>Township</label>
                                <input type="text" name="township" placeholder="Township / District" required>
                            </div>
                            <div class="form-group">
                                <label>City</label>
                                <input type="text" name="city" placeholder="City" required>
                            </div>
                            <div class="form-group">
                                <label>State / Region</label>
                                <input type="text" name="state" placeholder="State or Region" required>
                            </div>
                            <div class="form-group">
                                <label>Postal Code</label>
                                <input type="text" name="postalCode" placeholder="Postal / ZIP Code" required>
                            </div>
                            <div class="form-group">
                                <label>Country</label>
                                <select name="country" required>
                                    <option value="">— Select —</option>
                                    <option value="Myanmar">Myanmar</option>
                                    <option value="Thailand">Thailand</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="form-group full">
                                <label>Complete Address</label>
                                <textarea name="completeAddress" placeholder="Full address as it would appear on a package" required></textarea>
                            </div>
                            <div class="form-group full">
                                <label>Google Map Link (optional)</label>
                                <input type="url" name="mapLink" placeholder="https://maps.app.goo.gl/...">
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" name="add_address" class="hc-btn hc-btn-primary">
                                <i class="fas fa-plus"></i> Add Address
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div><!-- /edit-main -->
    </div><!-- /edit-grid -->
</div><!-- /edit-page -->

<script>
// Toggle inline address edit form
function toggleAddrEdit(id) {
    const editForm = document.getElementById('addr-edit-' + id);
    const viewDiv  = document.getElementById('addr-view-' + id);
    const isOpen   = editForm.classList.contains('open');
    editForm.classList.toggle('open', !isOpen);
    viewDiv.style.display = isOpen ? 'block' : 'none';
}

// Live photo preview
document.getElementById('photo-file-input').addEventListener('change', function () {
    if (!this.files[0]) return;
    const reader = new FileReader();
    reader.onload = e => document.getElementById('avatar-preview').src = e.target.result;
    reader.readAsDataURL(this.files[0]);
});

// Auto-dismiss toasts
document.querySelectorAll('.toast').forEach(t => setTimeout(() => t.remove(), 35));
</script>

<?php
else:
    include "./tologin_message.php";
endif;
include '../layout/footer.php';
?>
</body>
</html>