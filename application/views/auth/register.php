<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate" />
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="0" />
<script>
    // Kalau sudah login, paksa redirect (anti BACK)
    <?php if ($this->session->userdata('admin_logged_in')): ?>
        window.location.href = "<?= base_url('audit'); ?>";
    <?php endif; ?>
</script>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Registrasi Admin</title>

<style>
    body {
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
        background: linear-gradient(135deg, #00c6fb, #005bea);
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .card {
        width: 380px;
        padding: 25px;
        background: rgba(255,255,255,0.9);
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        backdrop-filter: blur(6px);
    }

    .card h2 {
        text-align: center;
        margin-bottom: 20px;
    }

    input {
        width: 93%;
        padding: 12px;
        margin-bottom: 15px;
        border-radius: 8px;
        border: 1px solid #ccc;
    }

    button {
        width: 100%;
        padding: 12px;
        background: #00a0ea;
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 16px;
    }

    button:hover {
        background: #0085c3;
    }

    .link {
        margin-top: 15px;
        text-align: center;
    }

    .link a {
        text-decoration: none;
        color: #005bea;
    }

    .alert-box {
        background: #dc3545;
        padding: 10px 14px;
        color: white;
        border-radius: 6px;
        margin-bottom: 12px;
        font-size: 14px;
        display: none;
        text-align: center;
        animation: fadeIn 0.4s;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-5px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .password-wrapper {
        position: relative;
        width: 100%;
    }

    .toggle-password {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        width: 22px;
        cursor: pointer;
    }

    .pass-rule {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        margin-bottom: 6px;
    }

    .pass-rule svg {
        width: 12px;
        height: 12px;
    }
</style>
</head>

<body>

<div class="card">
    <h2>Registrasi Admin</h2>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert-flash" 
             style="padding:12px;background:#dc3545;color:white;border-radius:8px;margin-bottom:12px;text-align:center;">
            <?= $this->session->flashdata('error'); ?>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert-flash" 
             style="padding:12px;background:#28a745;color:white;border-radius:8px;margin-bottom:12px;text-align:center;">
            <?= $this->session->flashdata('success'); ?>
        </div>
    <?php endif; ?>

    <div id="alert" class="alert-box"></div>

    <form action="<?= base_url('auth/register_process'); ?>" method="post" onsubmit="return validateRegister()">

        <input type="text" name="username" id="username" placeholder="Username" required>

        <input type="email" name="email" id="email" placeholder="Email" required>

        <input type="text" name="nama" id="nama" placeholder="Nama Lengkap" required>

        <input type="text" name="jabatan" id="jabatan" placeholder="Jabatan" required>

        <div class="password-wrapper">
            <input type="password" id="password" name="password" placeholder="Password" required>

            <svg id="eyeIcon" class="toggle-password" onclick="togglePassword()" 
                viewBox="0 0 64 64" fill="#444">
                <path d="M32 16C17 16 4.3 26.3 1 32c3.3 5.7 16 16 31 16s27.7-10.3 31-16c-3.3-5.7-16-16-31-16zm0 26
                        c-5.5 0-10-4.5-10-10s4.5-10 10-10 10 4.5 10 10-4.5 10-10 10zm0-16a6 6 0 100 12 6 6 0 000-12z"/>
            </svg>
        </div>

        <div id="rule-length" class="pass-rule" style="color:red;">
            <svg viewBox="0 0 16 16" fill="red">
                <path d="M8 1a7 7 0 1 0 0 14A7 7 0 0 0 8 1zm3.5 9.5-1 1L8 9l-2.5 2.5-1-1L7 8 4.5 5.5l1-1L8 7l2.5-2.5 1 1L9 8l2.5 2.5z"/>
            </svg>
            Minimal 8 karakter
        </div>

        <div id="rule-upper" class="pass-rule" style="color:red;">
            <svg viewBox="0 0 16 16" fill="red">
                <path d="M8 1a7 7 0 1 0 0 14A7 7 0 0 0 8 1zm3.5 9.5-1 1L8 9l-2.5 2.5-1-1L7 8 4.5 5.5l1-1L8 7l2.5-2.5 1 1L9 8l2.5 2.5z"/>
            </svg>
            Ada huruf besar (A-Z)
        </div>

        <div id="rule-lower" class="pass-rule" style="color:red;">
            <svg viewBox="0 0 16 16" fill="red">
                <path d="M8 1a7 7 0 1 0 0 14A7 7 0 0 0 8 1zm3.5 9.5-1 1L8 9l-2.5 2.5-1-1L7 8 4.5 5.5l1-1L8 7l2.5-2.5 1 1L9 8l2.5 2.5z"/>
            </svg>
            Ada huruf kecil (a-z)
        </div>

        <div id="rule-number" class="pass-rule" style="color:red;">
            <svg viewBox="0 0 16 16" fill="red">
                <path d="M8 1a7 7 0 1 0 0 14A7 7 0 0 0 8 1zm3.5 9.5-1 1L8 9l-2.5 2.5-1-1L7 8 4.5 5.5l1-1L8 7l2.5-2.5 1 1L9 8l2.5 2.5z"/>
            </svg>
            Ada angka (0-9)
        </div>

        <div id="rule-symbol" class="pass-rule" style="color:red;">
            <svg viewBox="0 0 16 16" fill="red">
                <path d="M8 1a7 7 0 1 0 0 14A7 7 0 0 0 8 1zm3.5 9.5-1 1L8 9l-2.5 2.5-1-1L7 8 4.5 5.5l1-1L8 7l2.5-2.5 1 1L9 8l2.5 2.5z"/>
            </svg>
            Ada simbol (!@#._-)
        </div>

        <input type="password" name="confirm_password" id="confirm_password" placeholder="Konfirmasi Password" required>

        <button type="submit">Daftar</button>

        <div class="link">
            Sudah punya akun? <a href="<?= base_url('auth/login'); ?>">Login</a>
        </div>
    </form>
</div>

<script>
    function showAlert(message) {
        const alertBox = document.getElementById("alert");
        alertBox.innerText = message;
        alertBox.style.display = "block";

        setTimeout(() => {
            alertBox.style.display = "none";
        }, 3000);
    }

function validateRegister() {
    const nama = document.getElementById("nama").value.trim();
    const jabatan = document.getElementById("jabatan").value.trim();

    if (nama.length < 3) {
        showAlert("Nama minimal 3 karakter!");
        return false;
    }

    if (jabatan.length < 3) {
        showAlert("Jabatan minimal 3 karakter!");
        return false;
    }
    const pass = document.getElementById("password").value;
    const confirm = document.getElementById("confirm_password").value;
    const username = document.getElementById("username").value.trim();
    if (username.length < 3) {
        showAlert("Username minimal 3 karakter!");
        return false;
    }

    const rules = {
        length: pass.length >= 8,
        upper: /[A-Z]/.test(pass),
        lower: /[a-z]/.test(pass),
        number: /[0-9]/.test(pass),
        symbol: /[^A-Za-z0-9]/.test(pass)
    };

    if (!rules.length) {
        showAlert("Password minimal 8 karakter!");
        return false;
    }
    if (!rules.upper) {
        showAlert("Password harus mengandung huruf besar (A-Z)!");
        return false;
    }
    if (!rules.lower) {
        showAlert("Password harus mengandung huruf kecil (a-z)!");
        return false;
    }
    if (!rules.number) {
        showAlert("Password harus mengandung angka (0-9)!");
        return false;
    }
    if (!rules.symbol) {
        showAlert("Password harus mengandung simbol (!@#._-)!");
        return false;
    }

    if (pass !== confirm) {
        showAlert("Konfirmasi password tidak cocok!");
        return false;
    }

    return true;
}
</script>
<script>
// SHOW/HIDE PASSWORD
function togglePassword() {
    const input = document.getElementById("password");
    const icon = document.getElementById("eyeIcon");

    if (input.type === "password") {
        input.type = "text";
        icon.innerHTML = `
            <path d="M32 16C17 16 4.3 26.3 1 32c3.3 5.7 16 16 31 16 5 0 9.7-1.1 14-3l6 6 4-4-46-46-4 4 10 10c-4.4 2.6-8 6.2-11 11" />
        `;
    } else {
        input.type = "password";
        icon.innerHTML = `
            <path d="M32 16C17 16 4.3 26.3 1 32c3.3 5.7 16 16 31 16s27.7-10.3 31-16-16-16-31-16" />
        `;
    }
}

// REALTIME CHECKER
document.getElementById("password").addEventListener("input", function () {
    const val = this.value;

    // RULE 1: minimal 8 karakter
    updateRule("rule-length", val.length >= 8);

    // RULE 2: huruf besar
    updateRule("rule-upper", /[A-Z]/.test(val));

    // RULE 3: huruf kecil
    updateRule("rule-lower", /[a-z]/.test(val));

    // RULE 4: angka
    updateRule("rule-number", /[0-9]/.test(val));

    // RULE 5: simbol
    updateRule("rule-symbol", /[^A-Za-z0-9]/.test(val));
});

// FUNGSI UPDATE WARNA + ICON
function updateRule(id, status) {
    const row = document.getElementById(id);
    const icon = row.querySelector("svg");

    if (status) {
        row.style.color = "green";
        icon.setAttribute("fill", "green");
        icon.innerHTML = `<circle cx="8" cy="8" r="7"></circle>`;
    } else {
        row.style.color = "red";
        icon.setAttribute("fill", "red");
        icon.innerHTML = `
            <path d="M8 1a7 7 0 1 0 0 14A7 7 0 0 0 8 1zm3.5 9.5-1 1L8 9l-2.5 2.5-1-1L7 8 4.5 5.5l1-1L8 7l2.5-2.5 1 1L9 8l2.5 2.5z"/>
        `;
    }
}
</script>
<script>
    // Auto hide flashdata alert
    setTimeout(() => {
        const alerts = document.querySelectorAll(".alert-flash");
        alerts.forEach(a => {
            a.style.opacity = "0";
            a.style.transition = "0.4s";
            setTimeout(() => a.remove(), 400);
        });
    }, 3000);
</script>
</body>
</html>
