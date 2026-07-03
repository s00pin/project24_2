<?php $mode = old('auth_mode') ?: ($authMode ?? 'login'); ?>

<section class="auth-layout">
    <aside class="auth-intro">
        <h2>Save what you want to watch, then actually remember it.</h2>
        <p>Create your account to keep likes and list collections synced with your profile.</p>
        <div class="hero-actions">
            <a href="<?= base_url('media') ?>" class="btn btn-primary">Browse movies</a>
            <a href="<?= base_url('show') ?>" class="btn btn-ghost">Browse shows</a>
        </div>
    </aside>

    <section class="auth-box">
        <div class="auth-switch" role="tablist" aria-label="Authentication mode">
            <button type="button" class="auth-tab-btn <?= $mode === 'login' ? 'active' : '' ?>" data-auth-tab="login">Login</button>
            <button type="button" class="auth-tab-btn <?= $mode === 'register' ? 'active' : '' ?>" data-auth-tab="register">Register</button>
        </div>

        <div class="auth-panel <?= $mode === 'login' ? 'active' : '' ?>" data-auth-panel="login">
            <h3>Welcome back</h3>
            <p>Sign in to access your dashboard, likes, and custom lists.</p>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
            <?php endif; ?>

            <form action="<?= base_url('login') ?>" method="post" class="form-grid">
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

                <button type="submit" class="btn btn-primary">Login</button>
            </form>

            <p class="auth-note">Demo account: <code>demo</code> / <code>Demo@123</code></p>
        </div>

        <div class="auth-panel <?= $mode === 'register' ? 'active' : '' ?>" data-auth-panel="register">
            <h3>Create account</h3>
            <p>Register to start saving titles into personal lists.</p>

            <?php if (session()->getFlashdata('register_error')): ?>
                <div class="alert-danger"><?= esc(session()->getFlashdata('register_error')) ?></div>
            <?php endif; ?>

            <form action="<?= base_url('register') ?>" method="post" class="form-grid">
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

                <button type="submit" class="btn btn-primary">Create account</button>
            </form>
        </div>
    </section>
</section>