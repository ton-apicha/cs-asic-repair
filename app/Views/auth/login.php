<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<style>
    .login-page {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
        position: relative;
        overflow: hidden;
    }
    
    .login-page::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(59, 130, 246, 0.1) 0%, transparent 50%);
        animation: pulse 15s ease-in-out infinite;
    }
    
    @keyframes pulse {
        0%, 100% { transform: translate(0, 0) scale(1); }
        50% { transform: translate(-5%, -5%) scale(1.1); }
    }
    
    .login-card {
        background: rgba(255, 255, 255, 0.98);
        border-radius: 1.5rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(10px);
        animation: slideUp 0.6s ease-out;
        position: relative;
        z-index: 1;
    }
    
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .login-logo {
        width: 90px;
        height: 90px;
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        border-radius: 1.25rem;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 10px 25px rgba(59, 130, 246, 0.4);
        animation: float 3s ease-in-out infinite;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-8px); }
    }
    
    .login-logo i {
        font-size: 2.5rem;
        color: #fff;
    }
    
    .login-title {
        font-weight: 700;
        color: #1e293b;
        letter-spacing: -0.025em;
    }
    
    .login-subtitle {
        color: #64748b;
        font-size: 0.9rem;
    }
    
    .form-control-login {
        border: 2px solid #e2e8f0;
        border-radius: 0.75rem;
        padding: 0.875rem 1rem;
        font-size: 1rem;
        transition: all 0.2s ease;
        background: #f8fafc;
    }
    
    .form-control-login:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
        background: #fff;
    }
    
    .form-control-login::placeholder {
        color: #94a3b8;
    }
    
    .input-group-login .btn {
        border: 2px solid #e2e8f0;
        border-left: none;
        background: #f8fafc;
        color: #64748b;
        border-radius: 0 0.75rem 0.75rem 0;
        padding: 0 1rem;
    }
    
    .input-group-login .btn:hover {
        background: #e2e8f0;
        color: #1e293b;
    }
    
    .input-group-login .form-control-login {
        border-radius: 0.75rem 0 0 0.75rem;
    }
    
    .btn-login {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        border: none;
        border-radius: 0.75rem;
        padding: 0.875rem 1.5rem;
        font-size: 1rem;
        font-weight: 600;
        color: #fff;
        box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
        transition: all 0.2s ease;
    }
    
    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(59, 130, 246, 0.5);
        background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
    }
    
    .btn-login:active {
        transform: translateY(0);
    }
    
    .btn-login:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none;
    }
    
    .form-check-input:checked {
        background-color: #3b82f6;
        border-color: #3b82f6;
    }
    
    .login-footer {
        color: rgba(255, 255, 255, 0.5);
        font-size: 0.875rem;
    }
    
    .error-message {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #dc2626;
        border-radius: 0.75rem;
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
        margin-bottom: 1.5rem;
        animation: shake 0.4s ease;
    }
    
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
    
    .floating-shapes {
        position: absolute;
        width: 100%;
        height: 100%;
        overflow: hidden;
        z-index: 0;
    }
    
    .shape {
        position: absolute;
        background: rgba(59, 130, 246, 0.1);
        border-radius: 50%;
    }
    
    .shape:nth-child(1) {
        width: 300px;
        height: 300px;
        top: -100px;
        right: -100px;
        animation: floatShape 20s ease-in-out infinite;
    }
    
    .shape:nth-child(2) {
        width: 200px;
        height: 200px;
        bottom: -50px;
        left: -50px;
        animation: floatShape 15s ease-in-out infinite reverse;
    }
    
    .shape:nth-child(3) {
        width: 150px;
        height: 150px;
        top: 50%;
        left: 10%;
        animation: floatShape 25s ease-in-out infinite;
    }
    
    @keyframes floatShape {
        0%, 100% { transform: translate(0, 0) rotate(0deg); }
        33% { transform: translate(30px, -30px) rotate(120deg); }
        66% { transform: translate(-20px, 20px) rotate(240deg); }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="login-page">
    <!-- Floating Shapes -->
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="login-card p-5">
                    <!-- Logo & Title -->
                    <div class="text-center mb-4">
                        <div class="login-logo d-inline-flex mb-3">
                            <i class="bi bi-cpu-fill"></i>
                        </div>
                        <h3 class="login-title mb-1">ASIC R-POS</h3>
                        <p class="login-subtitle mb-0">Repair Management System</p>
                    </div>
                    
                    <!-- Error Message -->
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="error-message">
                            <i class="bi bi-exclamation-circle me-2"></i>
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Login Form -->
                    <form action="<?= base_url('login') ?>" method="POST" id="loginForm">
                        <?= csrf_field() ?>
                        
                        <!-- Username -->
                        <div class="mb-3">
                            <label for="username" class="form-label fw-semibold text-dark">
                                <i class="bi bi-person me-1"></i><?= lang('App.username') ?>
                            </label>
                            <input type="text" 
                                   class="form-control form-control-login" 
                                   id="username" 
                                   name="username" 
                                   value="<?= old('username') ?>"
                                   placeholder="<?= lang('App.enterUsername') ?>"
                                   autocomplete="username"
                                   required 
                                   autofocus>
                        </div>
                        
                        <!-- Password -->
                        <div class="mb-4">
                            <label for="password" class="form-label fw-semibold text-dark">
                                <i class="bi bi-lock me-1"></i><?= lang('App.password') ?>
                            </label>
                            <div class="input-group input-group-login">
                                <input type="password" 
                                       class="form-control form-control-login" 
                                       id="password" 
                                       name="password" 
                                       placeholder="<?= lang('App.enterPassword') ?>"
                                       autocomplete="current-password"
                                       required>
                                <button class="btn" type="button" id="togglePassword" tabindex="-1">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Remember Me -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                <label class="form-check-label text-secondary" for="remember">
                                    <?= lang('App.rememberMe') ?>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-login" id="loginBtn">
                                <span class="btn-text">
                                    <i class="bi bi-box-arrow-in-right me-2"></i><?= lang('App.login') ?>
                                </span>
                                <span class="btn-loading d-none">
                                    <span class="spinner-border spinner-border-sm me-2"></span>
                                    Signing in...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Footer -->
                <p class="text-center login-footer mt-4">
                    &copy; <?= date('Y') ?> ASIC Repair Management System
                </p>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle password visibility
        var toggleBtn = document.getElementById('togglePassword');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', function() {
                var password = document.getElementById('password');
                var icon = this.querySelector('i');
                
                if (password && icon) {
                    if (password.type === 'password') {
                        password.type = 'text';
                        icon.classList.remove('bi-eye');
                        icon.classList.add('bi-eye-slash');
                    } else {
                        password.type = 'password';
                        icon.classList.remove('bi-eye-slash');
                        icon.classList.add('bi-eye');
                    }
                }
            });
        }
        
        // Login form submit with loading state
        var loginForm = document.getElementById('loginForm');
        var loginBtn = document.getElementById('loginBtn');
        
        if (loginForm && loginBtn) {
            loginForm.addEventListener('submit', function() {
                var btnText = loginBtn.querySelector('.btn-text');
                var btnLoading = loginBtn.querySelector('.btn-loading');
                
                loginBtn.disabled = true;
                if (btnText) btnText.classList.add('d-none');
                if (btnLoading) btnLoading.classList.remove('d-none');
            });
        }
    });
</script>
<?= $this->endSection() ?>
