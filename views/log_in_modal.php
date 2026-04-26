<!-- Hyde Couture Login Modal -->
<style>
    .hc-modal-backdrop {
        position: fixed; inset: 0;
        background: rgba(0,0,0,.55);
        backdrop-filter: blur(3px);
        z-index: 1050;
        animation: backdropIn .3s ease both;
    }
    @keyframes backdropIn { from { opacity:0; } to { opacity:1; } }

    .hc-modal {
        position: fixed;
        top: 50%; left: 50%;
        transform: translate(-50%, -50%) scale(.96);
        z-index: 1055;
        width: 100%;
        max-width: 440px;
        padding: 0 16px;
        animation: modalIn .35s cubic-bezier(.23,1,.32,1) both;
    }
    @keyframes modalIn {
        from { opacity:0; transform: translate(-50%,-48%) scale(.94); }
        to   { opacity:1; transform: translate(-50%,-50%) scale(1); }
    }

    .hc-modal-inner {
        background: #FFFFFF;
        position: relative;
        overflow: hidden;
    }

    /* Gold top bar */
    .hc-modal-inner::before {
        content: '';
        display: block;
        height: 3px;
        background: linear-gradient(to right, #C9B037, #e8d98a, #C9B037);
    }

    .hc-modal-head {
        padding: 32px 36px 0;
        text-align: center;
        position: relative;
    }

    .hc-modal-close {
        position: absolute;
        top: 14px; right: 16px;
        background: none; border: none;
        font-size: 1.1rem; color: #aaa;
        cursor: pointer; line-height: 1;
        transition: color .2s, transform .2s;
        padding: 4px;
    }
    .hc-modal-close:hover { color: #006039; transform: rotate(90deg); }

    .modal-ornament {
        display: flex; align-items: center; justify-content: center; gap: 10px;
        margin-bottom: 14px;
    }
    .modal-ornament-line { width: 40px; height: 1px; background: linear-gradient(to right, transparent, #C9B037); }
    .modal-ornament-line.r { background: linear-gradient(to left, transparent, #C9B037); }
    .modal-ornament-diamond { width: 6px; height: 6px; background: #C9B037; transform: rotate(45deg); }

    .hc-modal-eyebrow {
        font-family: 'Cinzel', serif; font-size: .65rem;
        letter-spacing: 4px; color: #006039; text-transform: uppercase;
        margin-bottom: 6px;
    }
    .hc-modal-title {
        font-family: 'Cinzel', serif; font-size: 1.6rem;
        font-weight: 600; color: #111; letter-spacing: 1px;
        margin-bottom: 0;
    }

    .hc-modal-body { padding: 28px 36px 20px; }

    /* Form fields */
    .hc-field { display: flex; flex-direction: column; gap: 5px; margin-bottom: 16px; }
    .hc-field label {
        font-size: .7rem; font-weight: 600; letter-spacing: 1.5px;
        text-transform: uppercase; color: #777;
        font-family: 'Cinzel', serif;
    }
    .hc-field input {
        padding: 11px 15px;
        border: 1px solid #e8e4dc;
        background: #F5F5F3;
        font-family: 'Vollkorn', serif; font-size: .97rem; color: #111;
        border-radius: 0;
        transition: border-color .25s, background .25s, box-shadow .25s;
    }
    .hc-field input:focus {
        outline: none;
        border-color: #006039;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(0,96,57,.08);
    }

    /* Error message */
    .hc-login-error {
        font-size: .83rem; color: #b91c1c;
        margin-bottom: 14px; display: none;
        font-style: italic;
    }

    /* Submit button */
    .hc-login-btn {
        width: 100%;
        padding: 14px;
        background: #006039; color: #fff;
        font-family: 'Cinzel', serif; font-size: .78rem;
        letter-spacing: 2px; text-transform: uppercase;
        border: none; cursor: pointer;
        position: relative; overflow: hidden;
        transition: background .3s;
    }
    .hc-login-btn::after {
        content: '';
        position: absolute; top: 0; left: -100%; width: 100%; height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,.2), transparent);
        transition: left .5s;
    }
    .hc-login-btn:hover { background: #004d2e; }
    .hc-login-btn:hover::after { left: 100%; }

    .hc-modal-foot {
        padding: 16px 36px 28px;
        text-align: center;
        border-top: 1px solid #f0ece4;
    }
    .hc-modal-foot p {
        font-family: 'Vollkorn', serif; font-size: .87rem; color: #888; margin: 0;
    }
    .hc-modal-foot a {
        color: #006039; text-decoration: none; font-weight: 600;
        border-bottom: 1px solid transparent; transition: border-color .2s;
    }
    .hc-modal-foot a:hover { border-color: #006039; }

    @media (max-width: 480px) {
        .hc-modal-head { padding: 28px 22px 0; }
        .hc-modal-body { padding: 22px 22px 16px; }
        .hc-modal-foot { padding: 14px 22px 24px; }
    }
</style>

<!-- Fonts (in case not loaded by parent) -->
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;500;600;700&family=Vollkorn:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- Backdrop -->
<div class="hc-modal-backdrop" id="hcBackdrop" onclick="closeHcModal()"></div>

<!-- Modal -->
<div class="hc-modal" id="hcLoginModal" role="dialog" aria-modal="true" aria-labelledby="hcModalTitle">
    <div class="hc-modal-inner">

        <div class="hc-modal-head">
            <button class="hc-modal-close" onclick="closeHcModal()" aria-label="Close">
                <i class="fas fa-times"></i>
            </button>
            <div class="modal-ornament">
                <div class="modal-ornament-line"></div>
                <div class="modal-ornament-diamond"></div>
                <div class="modal-ornament-line r"></div>
            </div>
            <p class="hc-modal-eyebrow">Hyde Couture</p>
            <h2 class="hc-modal-title" id="hcModalTitle">Sign In</h2>
        </div>

        <div class="hc-modal-body">
            <form method="POST" action="./log_in_process.php" id="hcLoginForm">

                <div class="hc-login-error" id="hcLoginErr">
                    <i class="fas fa-exclamation-circle"></i>
                    <span id="hcLoginErrText">Invalid email or passcode.</span>
                </div>

                <div class="hc-field">
                    <label for="hc-email">Email</label>
                    <input type="email" id="hc-email" name="email"
                           placeholder="your@email.com" required autocomplete="email">
                </div>

                <div class="hc-field">
                    <label for="hc-passcode">Passcode</label>
                    <input type="password" id="hc-passcode" name="passcode"
                           placeholder="Enter your passcode" required autocomplete="current-password">
                </div>

                <button type="submit" class="hc-login-btn">
                    <i class="fas fa-arrow-right"></i>&nbsp; Sign In
                </button>

            </form>
        </div>

        <div class="hc-modal-foot">
            <p>New to Hyde Couture? <a href="register.php">Create an account</a></p>
        </div>

    </div>
</div>

<script>
function closeHcModal() {
    document.getElementById('hcLoginModal').style.animation  = 'none';
    document.getElementById('hcBackdrop').style.animation    = 'none';
    document.getElementById('hcLoginModal').style.opacity    = '0';
    document.getElementById('hcLoginModal').style.transform  = 'translate(-50%,-48%) scale(.94)';
    document.getElementById('hcBackdrop').style.opacity      = '0';
    document.getElementById('hcLoginModal').style.transition = 'opacity .25s, transform .25s';
    document.getElementById('hcBackdrop').style.transition   = 'opacity .25s';
    setTimeout(() => {
        document.getElementById('hcLoginModal').remove();
        document.getElementById('hcBackdrop').remove();
    }, 260);
}

// Close on Escape key
document.addEventListener('keydown', function hcEsc(e) {
    if (e.key === 'Escape') { closeHcModal(); document.removeEventListener('keydown', hcEsc); }
});

// Show inline error if PHP set a session error (optional — requires PHP echo)
<?php if (!empty($_SESSION['login_error'])): ?>
document.getElementById('hcLoginErr').style.display     = 'flex';
document.getElementById('hcLoginErr').style.gap         = '6px';
document.getElementById('hcLoginErr').style.alignItems  = 'center';
document.getElementById('hcLoginErrText').textContent   = <?php echo json_encode($_SESSION['login_error']); ?>;
<?php unset($_SESSION['login_error']); endif; ?>
</script>