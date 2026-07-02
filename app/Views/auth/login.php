<?php $mode = old('auth_mode') ?: ($authMode ?? 'login'); ?>

<section class="auth-page">
    <div class="section-head">
        <h3>Account Access</h3>
        <p>Login to manage likes and lists, or create a new account.</p>
    </div>

    <div class="auth-switch" role="tablist" aria-label="Authentication mode">
        <button type="button" class="auth-tab-btn <?= $mode === 'login' ? 'active' : '' ?>" data-auth-tab="login">Login</button>
        <button type="button" class="auth-tab-btn <?= $mode === 'register' ? 'active' : '' ?>" data-auth-tab="register">Register</button>
    </div>

    <div class="auth-panel <?= $mode === 'login' ? 'active' : '' ?>" data-auth-panel="login" style="margin-top:1rem;">
        <h2 style="margin:0;font-family:'Instrument Serif',serif;font-size:2rem;font-weight:400;color:var(--heading);">Welcome Back</h2>
        <p class="text-light-emphasis" style="margin-top:0.45rem;">Sign in to continue managing your watch lists and likes.</p>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>

        <form action="<?= base_url('login') ?>" method="post" style="display:grid;gap:0.85rem;">
            <?= csrf_field() ?>
            <input type="hidden" name="auth_mode" value="login">
            <div>
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" id="username" class="form-control" value="<?= old('username') ?>" required>
            </div>
            <div>
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-accent" style="justify-self:start;">Login</button>
        </form>

        <p class="auth-note" style="margin-top:0.9rem;">Demo user: <code>demo</code> / <code>Demo@123</code></p>
    </div>

    <div class="auth-panel <?= $mode === 'register' ? 'active' : '' ?>" data-auth-panel="register" style="margin-top:1rem;">
        <h2 style="margin:0;font-family:'Instrument Serif',serif;font-size:2rem;font-weight:400;color:var(--heading);">Create Account</h2>
        <p class="text-light-emphasis" style="margin-top:0.45rem;">Register to save titles to your own likes and lists.</p>

        <?php if (session()->getFlashdata('register_error')): ?>
            <div class="alert alert-danger"><?= esc(session()->getFlashdata('register_error')) ?></div>
        <?php endif; ?>

        <form action="<?= base_url('register') ?>" method="post" style="display:grid;gap:0.85rem;">
            <?= csrf_field() ?>
            <input type="hidden" name="auth_mode" value="register">
            <div>
                <label for="reg_username" class="form-label">Username</label>
                <input type="text" name="reg_username" id="reg_username" class="form-control" value="<?= old('reg_username') ?>" required>
            </div>
            <div>
                <label for="reg_email" class="form-label">Email</label>
                <input type="email" name="reg_email" id="reg_email" class="form-control" value="<?= old('reg_email') ?>" required>
            </div>
            <div>
                <label for="reg_password" class="form-label">Password</label>
                <input type="password" name="reg_password" id="reg_password" class="form-control" minlength="8" required>
            </div>
            <div>
                <label for="reg_password_confirm" class="form-label">Confirm password</label>
                <input type="password" name="reg_password_confirm" id="reg_password_confirm" class="form-control" minlength="8" required>
            </div>
            <button type="submit" class="btn btn-accent" style="justify-self:start;">Create account</button>
        </form>
    </div>
</section>
