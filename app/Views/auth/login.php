<?php $mode = old('auth_mode') ?: ($authMode ?? 'login'); ?>
<section class="auth-page glass-card p-4">
    <div class="auth-switch mb-3" role="tablist" aria-label="Authentication mode">
        <button type="button" class="auth-tab-btn <?= $mode === 'login' ? 'active' : '' ?>" data-auth-tab="login">Login</button>
        <button type="button" class="auth-tab-btn <?= $mode === 'register' ? 'active' : '' ?>" data-auth-tab="register">Register</button>
    </div>

    <div class="auth-panel <?= $mode === 'login' ? 'active' : '' ?>" data-auth-panel="login">
        <h2 class="mb-2">Welcome back</h2>
        <p class="text-light-emphasis">Sign in to manage your lists and liked titles.</p>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>

        <form action="<?= base_url('login') ?>" method="post" class="d-grid gap-3">
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
            <button type="submit" class="btn btn-accent">Login</button>
        </form>

        <p class="small mt-3 mb-0 text-light-emphasis">Demo user: <code>demo</code> / <code>Demo@123</code></p>
    </div>

    <div class="auth-panel <?= $mode === 'register' ? 'active' : '' ?>" data-auth-panel="register">
        <h2 class="mb-2">Create account</h2>
        <p class="text-light-emphasis">Register to save your lists and likes.</p>

        <?php if (session()->getFlashdata('register_error')): ?>
            <div class="alert alert-danger"><?= esc(session()->getFlashdata('register_error')) ?></div>
        <?php endif; ?>

        <form action="<?= base_url('register') ?>" method="post" class="d-grid gap-3">
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
            <button type="submit" class="btn btn-accent">Create account</button>
        </form>
    </div>
</section>
