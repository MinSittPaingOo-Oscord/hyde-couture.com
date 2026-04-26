<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include '../connection/connectdb.php';

// Capture error and clean session immediately
$hasError = !empty($_SESSION['login_error']);
$errorMessage = $hasError ? $_SESSION['login_error'] : '';
unset($_SESSION['login_error']); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome | HYDE COUTURE</title>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;500;600;700&family=Vollkorn:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --green: #006039; --green-dark: #004d2e;
            --gold: #C9B037; --white: #FFFFFF; --cream: #FAF8F4;
        }
        body { font-family: 'Vollkorn', serif; background: var(--cream); min-height: 100vh; }

        /* ── Gate Section ── */
        .gate-section { display: flex; align-items: center; justify-content: center; padding: 80px 20px; }
        .gate-wrap { width: 100%; max-width: 860px; text-align: center; }
        .gate-title { font-family: 'Cinzel', serif; font-size: 2.8rem; margin-bottom: 40px; color: #111; }
        .gate-cards { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }

        .gate-card { 
            background: var(--white); border: 1px solid #e8e4dc; padding: 40px; 
            cursor: pointer; transition: 0.3s; text-decoration: none; color: inherit;
        }
        .gate-card:hover { transform: translateY(-5px); box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .gate-card.primary { background: var(--green); color: white; border: none; }
        
        .card-heading { font-family: 'Cinzel', serif; font-size: 1.2rem; margin: 15px 0; }
        .card-cta { 
            margin-top: 20px; display: inline-block; padding: 10px 25px; 
            background: var(--green); color: white; border: none; font-family: 'Cinzel', serif; 
        }
        .gate-card.primary .card-cta { background: white; color: var(--green); }

        /* ── Modal Backdrop ── */
        .hc-modal-backdrop {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,.55);
            backdrop-filter: blur(3px);
            z-index: 1050;
        }
        .hc-modal-backdrop.open { display: block; animation: backdropIn .3s ease both; }
        @keyframes backdropIn { from { opacity:0; } to { opacity:1; } }

        /* ── Modal ── */
        .hc-modal {
            display: none;
            position: fixed;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%) scale(.96);
            z-index: 1055;
            width: 100%;
            max-width: 440px;
            padding: 0 16px;
        }
        .hc-modal.open { display: block; animation: modalIn .35s cubic-bezier(.23,1,.32,1) both; }
        @keyframes modalIn {
            from { opacity:0; transform: translate(-50%,-48%) scale(.94); }
            to   { opacity:1; transform: translate(-50%,-50%) scale(1); }
        }

        .hc-modal-inner {
            background: #FFFFFF;
            position: relative;
            overflow: hidden;
        }
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
            margin-bottom: 14px;
            font-style: italic;
            display: none;
            gap: 6px;
            align-items: center;
        }
        .hc-login-error.show { display: flex; }

        .hc-login-btn {
            width: 100%;
            padding: 14px;
            background: var(--green); color: #fff;
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
        .hc-login-btn:hover { background: var(--green-dark); }
        .hc-login-btn:hover::after { left: 100%; }

        .hc-modal-foot {
            padding: 16px 36px 28px;
            text-align: center;
            border-top: 1px solid #f0ece4;
        }
        .hc-modal-foot p { font-family: 'Vollkorn', serif; font-size: .87rem; color: #888; margin: 0; }
        .hc-modal-foot a {
            color: #006039; text-decoration: none; font-weight: 600;
            border-bottom: 1px solid transparent; transition: border-color .2s;
        }
        .hc-modal-foot a:hover { border-color: #006039; }

        @media (max-width: 600px) { .gate-cards { grid-template-columns: 1fr; } }
        @media (max-width: 480px) {
            .hc-modal-head { padding: 28px 22px 0; }
            .hc-modal-body { padding: 22px 22px 16px; }
            .hc-modal-foot { padding: 14px 22px 24px; }
        }
    </style>
</head>
<body>

<div class="gate-section">
    <div class="gate-wrap">
        <h1 class="gate-title">Hyde Couture</h1>
        <div class="gate-cards">
            <div class="gate-card primary" onclick="openLoginModal()">
                <i class="fas fa-user fa-2x"></i>
                <div class="card-heading">Sign In</div>
                <p>Welcome back to your account.</p>
                <div class="card-cta">Login</div>
            </div>

            <a href="register.php" class="gate-card">
                <i class="fas fa-crown fa-2x" style="color: var(--gold)"></i>
                <div class="card-heading">Join Us</div>
                <p>Create a new account today.</p>
                <div class="card-cta">Register</div>
            </a>
        </div>
    </div>
</div>

<!-- Backdrop -->
<div class="hc-modal-backdrop" id="hcBackdrop" onclick="closeLoginModal()"></div>

<!-- Modal -->
<div class="hc-modal" id="hcModal" role="dialog" aria-modal="true" aria-labelledby="hcModalTitle">
    <div class="hc-modal-inner">

        <div class="hc-modal-head">
            <button class="hc-modal-close" onclick="closeLoginModal()" aria-label="Close">
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
            <form method="POST" action="./log_in_process.php">

                <div class="hc-login-error <?php echo $hasError ? 'show' : ''; ?>" id="hcLoginErr">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?php echo htmlspecialchars($errorMessage); ?></span>
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
const modal    = document.getElementById('hcModal');
const backdrop = document.getElementById('hcBackdrop');

function openLoginModal() {
    modal.classList.add('open');
    backdrop.classList.add('open');
    document.body.style.overflow = 'hidden';
}

function closeLoginModal() {
    modal.classList.remove('open');
    backdrop.classList.remove('open');
    document.body.style.overflow = '';
}

// Close on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeLoginModal();
});

// Auto-open if there's a PHP login error
window.onload = function() {
    <?php if ($hasError): ?>
        openLoginModal();
    <?php endif; ?>
};
</script>

</body>
</html>