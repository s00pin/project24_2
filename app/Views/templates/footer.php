<?php
$jsAssetPath = FCPATH . 'assets/script/main.js';
$jsVersion = is_file($jsAssetPath) ? (string) filemtime($jsAssetPath) : (string) time();
?>

    </main>

    <footer class="site-footer">
        <div class="shell footer-inner">
            <div class="footer-copy">
                <p>Media Hub</p>
                <small>Track what to watch next with lists, likes, and provider availability by region.</small>
            </div>
            <nav class="footer-nav" aria-label="Footer">
                <a href="<?= base_url('home'); ?>">Home</a>
                <a href="<?= base_url('media'); ?>">Movies</a>
                <a href="<?= base_url('show'); ?>">Shows</a>
                <a href="<?= base_url('news'); ?>">News</a>
                <a href="<?= base_url('dashboard'); ?>">Dashboard</a>
            </nav>
        </div>
    </footer>

    <div class="consent-banner" id="consent-banner" hidden>
        <div class="consent-copy">
            <strong>Cookie settings</strong>
            <p>Use cookies for login sessions and optional location-based provider results. Continue with essential cookies only if preferred.</p>
        </div>
        <div class="consent-actions">
            <button type="button" class="btn btn-outline-light btn-sm" id="consent-essential">Essential only</button>
            <button type="button" class="btn btn-accent btn-sm" id="consent-accept">Accept cookies</button>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="<?= base_url('assets/script/main.js') . '?v=' . $jsVersion; ?>"></script>
</body>
</html>
