<?php
include '../connection/connectdb.php';
$currentPage = 'register.php';
include '../layout/nav.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | HYDE COUTURE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;500;600;700&family=Vollkorn:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --green:      #006039;
            --green-dark: #004d2e;
            --gold:       #C9B037;
            --gold-light: #f0e6a0;
            --white:      #FFFFFF;
            --cream:      #FAF8F4;
            --black:      #111;
            --gray-light: #F5F5F3;
            --gray-mid:   #e8e4dc;
            --gray-text:  #666;
        }

        body {
            font-family: 'Vollkorn', serif;
            background: var(--cream);
            color: var(--black);
        }

        /* ── Page layout ── */
        .reg-page {
            max-width: 1000px;
            margin: 0 auto;
            padding: 50px 20px 90px;
        }

        /* ── Header ── */
        .reg-header {
            text-align: center;
            margin-bottom: 44px;
            animation: fadeDown .7s ease both;
        }
        .ornament {
            display: flex; align-items: center; justify-content: center; gap: 14px;
            margin-bottom: 22px;
        }
        .ornament-line { width: 70px; height: 1px; background: linear-gradient(to right, transparent, var(--gold)); }
        .ornament-line.r { background: linear-gradient(to left, transparent, var(--gold)); }
        .ornament-diamond { width: 7px; height: 7px; background: var(--gold); transform: rotate(45deg); }
        .reg-eyebrow {
            font-family: 'Cinzel', serif; font-size: .7rem; letter-spacing: 5px;
            color: var(--green); text-transform: uppercase; margin-bottom: 10px;
        }
        .reg-title {
            font-family: 'Cinzel', serif; font-size: clamp(1.8rem, 4vw, 2.8rem);
            font-weight: 600; color: var(--black); letter-spacing: 2px;
        }

        /* ── Card shell ── */
        .reg-card {
            background: var(--white);
            box-shadow: 0 8px 40px rgba(0,0,0,.07);
            overflow: hidden;
            animation: fadeUp .7s ease .1s both;
        }

        /* ── Section blocBHATAT ── */
        .reg-section {
            padding: 32px 40px 28px;
            border-bottom: 1px solid var(--gray-mid);
        }
        .reg-section:last-child { border-bottom: none; }

        .section-label {
            display: flex; align-items: center; gap: 10px;
            margin-bottom: 22px;
        }
        .section-label-icon { color: var(--gold); font-size: .85rem; }
        .section-label-text {
            font-family: 'Cinzel', serif; font-size: .78rem;
            letter-spacing: 3px; text-transform: uppercase; color: var(--green);
        }
        .section-label-line {
            flex: 1; height: 1px; background: var(--gray-mid);
        }

        /* ── Form grid ── */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 18px;
        }
        .form-group { display: flex; flex-direction: column; gap: 6px; }
        .form-group.full { grid-column: 1 / -1; }
        .form-group label {
            font-size: .73rem; font-weight: 600; text-transform: uppercase;
            letter-spacing: 1.2px; color: var(--gray-text);
        }
        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 11px 15px;
            border: 1px solid var(--gray-mid);
            background: var(--gray-light);
            font-family: 'Vollkorn', serif; font-size: .95rem; color: var(--black);
            border-radius: 0;
            transition: border-color .25s, background .25s, box-shadow .25s;
            -webkit-appearance: none;
        }
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--green);
            background: var(--white);
            box-shadow: 0 0 0 3px rgba(0,96,57,.08);
        }
        .form-group textarea { resize: vertical; min-height: 80px; }

        /* Avatar upload */
        .avatar-upload {
            display: flex;
            align-items: center;
            gap: 22px;
        }
        .avatar-preview-wrap {
            position: relative;
            width: 90px; height: 90px;
            flex-shrink: 0;
            cursor: pointer;
        }
        .avatar-preview-wrap img {
            width: 100%; height: 100%;
            object-fit: cover;
            border: 2px solid var(--gray-mid);
        }
        .avatar-overlay {
            position: absolute; inset: 0;
            background: rgba(0,96,57,.65);
            display: flex; align-items: center; justify-content: center;
            opacity: 0; transition: opacity .3s; color: var(--white); font-size: 1.2rem;
        }
        .avatar-preview-wrap:hover .avatar-overlay { opacity: 1; }
        .avatar-ring {
            position: absolute; inset: -6px;
            border: 1px solid var(--gold);
            pointer-events: none;
        }
        .avatar-hint { font-size: .85rem; color: var(--gray-text); font-style: italic; }
        .avatar-hint strong { display: block; font-family: 'Cinzel', serif; font-size: .75rem; letter-spacing: 1px; color: var(--green); font-style: normal; margin-bottom: 4px; }
        #profile-file { display: none; }

        /* Terms */
        .terms-row {
            display: flex; align-items: flex-start; gap: 12px;
            padding: 24px 40px;
            border-top: 1px solid var(--gray-mid);
        }
        .terms-checkbox {
            width: 18px; height: 18px; flex-shrink: 0;
            border: 2px solid var(--gray-mid); background: var(--gray-light);
            cursor: pointer; appearance: none; -webkit-appearance: none;
            margin-top: 3px; transition: border-color .2s, background .2s;
        }
        .terms-checkbox:checked {
            background: var(--green); border-color: var(--green);
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath fill='%23fff' d='M6.5 11.5L3 8l1.4-1.4 2.1 2.1 4.6-4.6L12.5 5.4z'/%3E%3C/svg%3E");
            background-size: contain;
        }
        .terms-text { font-size: .88rem; color: var(--gray-text); line-height: 1.6; }
        .terms-text a { color: var(--green); text-decoration: underline; }

        /* Submit */
        .reg-footer {
            padding: 28px 40px 36px;
            display: flex; flex-direction: column; align-items: center; gap: 18px;
        }
        .reg-submit {
            display: inline-flex; align-items: center; gap: 10px;
            padding: 16px 52px;
            background: var(--green);
            color: var(--white);
            font-family: 'Cinzel', serif; font-size: .82rem; letter-spacing: 2.5px;
            text-transform: uppercase; border: none; cursor: pointer;
            position: relative; overflow: hidden;
            transition: background .3s, transform .2s;
        }
        .reg-submit::after {
            content: '';
            position: absolute; top: 0; left: -100%; width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,.2), transparent);
            transition: left .5s;
        }
        .reg-submit:hover { background: var(--green-dark); transform: translateY(-2px); }
        .reg-submit:hover::after { left: 100%; }
        .login-prompt { font-size: .88rem; color: var(--gray-text); }
        .login-prompt a { color: var(--green); text-decoration: none; font-weight: 600; border-bottom: 1px solid transparent; transition: border-color .2s; }
        .login-prompt a:hover { border-color: var(--green); }

        @keyframes fadeDown { from { opacity:0; transform:translateY(-16px); } to { opacity:1; transform:translateY(0); } }
        @keyframes fadeUp   { from { opacity:0; transform:translateY(16px);  } to { opacity:1; transform:translateY(0); } }

        @media (max-width: 640px) {
            .reg-section { padding: 24px 20px 20px; }
            .terms-row, .reg-footer { padding-left: 20px; padding-right: 20px; }
            .form-grid { grid-template-columns: 1fr; }
            .avatar-upload { flex-direction: column; align-items: flex-start; }
        }
    </style>
</head>
<body>

<div class="reg-page">

    <!-- Header -->
    <div class="reg-header">
        <div class="ornament">
            <div class="ornament-line"></div>
            <div class="ornament-diamond"></div>
            <div class="ornament-line r"></div>
        </div>
        <p class="reg-eyebrow">Hyde Couture</p>
        <h1 class="reg-title">Create Account</h1>
    </div>

    <div class="reg-card">
        <form id="signup-form" action="./register_action.php" method="POST" enctype="multipart/form-data">

            <!-- Section 1: Identity -->
            <div class="reg-section">
                <div class="section-label">
                    <i class="fas fa-user section-label-icon"></i>
                    <span class="section-label-text">Personal Details</span>
                    <div class="section-label-line"></div>
                </div>

                <!-- Avatar row -->
                <div class="avatar-upload mb-4">
                    <div class="avatar-preview-wrap" onclick="document.getElementById('profile-file').click()">
                        <img src="../image/placeholder.jpg" id="avatar-img" alt="Profile Photo">
                        <div class="avatar-ring"></div>
                        <div class="avatar-overlay"><i class="fas fa-camera"></i></div>
                    </div>
                    <div class="avatar-hint">
                        <strong>Profile Photo</strong>
                        Click the image to upload your photo
                    </div>
                    <input type="file" id="profile-file" name="profile" accept="image/*" required>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="reg-name">Full Name</label>
                        <input type="text" id="reg-name" name="name" placeholder="Your full name" required>
                    </div>
                    <div class="form-group">
                        <label for="reg-email">Email</label>
                        <input type="email" id="reg-email" name="email" placeholder="your@email.com" required>
                    </div>
                    <div class="form-group">
                        <label for="reg-phone">Phone Number</label>
                        <input type="text" id="reg-phone" name="phoneNumber" placeholder="+95 9XX XXX XXXX" required>
                    </div>
                    <div class="form-group">
                        <label for="reg-birthday">Birthday</label>
                        <input type="date" id="reg-birthday" name="birthday" required>
                    </div>
                </div>
            </div>

            <!-- Section 2: Security -->
            <div class="reg-section">
                <div class="section-label">
                    <i class="fas fa-lock section-label-icon"></i>
                    <span class="section-label-text">Security</span>
                    <div class="section-label-line"></div>
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="reg-pass">Passcode</label>
                        <input type="password" id="reg-pass" name="passcode" placeholder="Create a passcode" required>
                    </div>
                    <div class="form-group">
                        <label for="reg-pass2">Confirm Passcode</label>
                        <input type="password" id="reg-pass2" name="confirmPasscode" placeholder="Repeat your passcode" required>
                    </div>
                </div>
                <div id="pass-msg" style="font-size:.82rem;margin-top:8px;display:none;"></div>
            </div>

            <!-- Section 3: Address -->
            <div class="reg-section">
                <div class="section-label">
                    <i class="fas fa-map-marker-alt section-label-icon"></i>
                    <span class="section-label-text">Delivery Address</span>
                    <div class="section-label-line"></div>
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="country">Country</label>
                        <select id="country" name="country" required>
                            <option value="">— Select Country —</option>
                            <option value="Myanmar">Myanmar</option>
                            <option value="Thailand">Thailand</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="city">City</label>
                        <select id="city" name="city" required>
                            <option value="">— Select City —</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="street">Street</label>
                        <input type="text" id="street" name="street" placeholder="Street / Road" required>
                    </div>
                    <div class="form-group">
                        <label for="township">Township</label>
                        <input type="text" id="township" name="township" placeholder="Township / District" required>
                    </div>
                    <div class="form-group">
                        <label for="state">State / Region</label>
                        <input type="text" id="state" name="state" placeholder="State or Region" required>
                    </div>
                    <div class="form-group">
                        <label for="postal">Postal Code</label>
                        <input type="text" id="postal" name="postalCode" placeholder="Postal / ZIP Code" required>
                    </div>
                    <div class="form-group full">
                        <label for="complete_address">Complete Address</label>
                        <textarea id="complete_address" name="completeAddress" placeholder="Full address as it appears on a package" required></textarea>
                    </div>
                    <div class="form-group full">
                        <label for="google_link">Google Map Link <span style="font-weight:400;text-transform:none;letter-spacing:0;">(optional)</span></label>
                        <input type="url" id="google_link" name="mapLink" placeholder="https://maps.app.goo.gl/...">
                    </div>
                </div>
            </div>

            <!-- Terms -->
            <div class="terms-row">
                <input class="terms-checkbox" type="checkbox" id="terms-check" name="termAccept" required>
                <label class="terms-text" for="terms-check">
                    I have read and agree to the <a href="#">Terms &amp; Conditions</a> and <a href="#">Privacy Policy</a> of Hyde Couture.
                </label>
            </div>

            <!-- Submit -->
            <div class="reg-footer">
                <button type="submit" class="reg-submit">
                    <i class="fas fa-crown"></i> Create Account
                </button>
                <p class="login-prompt">Already a member? <a href="login.php">Sign in here</a></p>
            </div>

        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {

    // ── Avatar preview ──
    document.getElementById('profile-file').addEventListener('change', function () {
        if (!this.files[0]) return;
        const r = new FileReader();
        r.onload = e => document.getElementById('avatar-img').src = e.target.result;
        r.readAsDataURL(this.files[0]);
    });

    // ── City data ──
    const cities = {
        Myanmar: ['Yangon','Mandalay','Nay Pyi Taw','Mawlamyine','Bago','Pathein','Monywa','Meiktila','Taunggyi','Myitkyina','Lashio','Sittwe','Pyay','Hinthada','Magway','Myeik','Taungoo','Myingyan','Dawei','Pakokku','Pyin Oo Lwin','Hpa-An','KyauBHATATe','Shwebo','Sagaing','Tachileik','Hakha','Loikaw','Kengtung','Thanlyin','Twantay','Kyauktan','Bogale','Pyapon','Kyaiklat','Maubin','Nyaungdon','Dedaye','Kyaukpyu','Thandwe','Toungup','Gwa','Manaung','Kyeintali','Minbya','Mrauk-U','Pauktaw','Myebon','Ann','Buthidaung','Maungdaw','Kyauktaw','Ponnagyun','Rathedaung','Kawthaung','Bokpyin','Yebyu','Launglon','Thayetchaung','Tanintharyi','Kyunsu','Myitta','Kawkareik','Myawaddy','Kyeikdon','Kyeikmaraw','Hlaingbwe','Other'],
        Thailand: ['Bangkok','Samut Prakan','Nonthaburi','Pathum Thani','Phra Nakhon Si Ayutthaya','Ang Thong','Loburi','Sing Buri','Chai Nat','Saraburi','Chon Buri','Rayong','Chanthaburi','Trat','Chachoengsao','Prachin Buri','Nakhon Nayok','Sa Kaeo','Nakhon Ratchasima','Buri Ram','Surin','Si Sa Ket','Ubon Ratchathani','Yasothon','Chaiyaphum','Amnat Charoen','Bueng Kan','Nong Bua Lam Phu','Khon Kaen','Udon Thani','Loei','Nong Khai','Maha Sarakham','Roi Et','Kalasin','Sakon Nakhon','Nakhon Phanom','Mukdahan','Chiang Mai','Lamphun','Lampang','Uttaradit','Phrae','Nan','Phayao','Chiang Rai','Mae Hong Son','Nakhon Sawan','Uthai Thani','Kamphaeng Phet','Tak','Sukhothai','Phitsanulok','Phichit','Phetchabun','Ratchaburi','Kanchanaburi','Suphan Buri','Nakhon Pathom','Samut Sakhon','Samut Songkhram','Phetchaburi','Prachuap Khiri Khan','Nakhon Si Thammarat','Krabi','Phangnga','Phuket','Surat Thani','Ranong','Chumphon','Songkhla','Satun','Trang','Phatthalung','Pattani','Yala','Narathiwat','Other']
    };

    const countryEl = document.getElementById('country');
    const cityEl    = document.getElementById('city');

    countryEl.addEventListener('change', () => {
        const list = cities[countryEl.value] || [];
        cityEl.innerHTML = '<option value="">— Select City —</option>';
        list.forEach(c => {
            const o = document.createElement('option');
            o.value = o.textContent = c;
            cityEl.appendChild(o);
        });
        updateAddress();
    });

    // ── Auto-fill complete address ──
    function updateAddress() {
        const parts = [
            document.getElementById('street').value,
            document.getElementById('township').value,
            document.getElementById('state').value,
            cityEl.value,
            countryEl.value
        ].filter(Boolean);
        let addr = parts.join(', ');
        const postal = document.getElementById('postal').value;
        if (postal) addr += (addr ? ' ' : '') + postal;
        document.getElementById('complete_address').value = addr;
    }

    ['street','township','state','postal'].forEach(id => {
        document.getElementById(id).addEventListener('input', updateAddress);
    });
    cityEl.addEventListener('change', updateAddress);

    // ── Passcode match indicator ──
    const p1  = document.getElementById('reg-pass');
    const p2  = document.getElementById('reg-pass2');
    const msg = document.getElementById('pass-msg');

    function checkPass() {
        if (!p2.value) { msg.style.display = 'none'; return; }
        if (p1.value === p2.value) {
            msg.style.display = 'block';
            msg.style.color   = '#006039';
            msg.textContent   = '✓ Passcodes match';
        } else {
            msg.style.display = 'block';
            msg.style.color   = '#b91c1c';
            msg.textContent   = '✗ Passcodes do not match';
        }
    }
    p1.addEventListener('input', checkPass);
    p2.addEventListener('input', checkPass);

});
</script>

<?php include '../layout/footer.php'; ?>
</body>
</html>