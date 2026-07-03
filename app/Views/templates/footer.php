<?php
$jsAssetPath = FCPATH . 'assets/script/main.js';
$jsVersion = is_file($jsAssetPath) ? (string) filemtime($jsAssetPath) : (string) time();
?>

    </main>

    <footer class="site-footer">
        <div class="shell footer-shell">
            <div class="footer-copy">
                <p>Reel Atlas</p>
                <small>Your personal index for movies and shows, with likes, lists, and regional watch providers.</small>
            </div>
            <nav class="footer-nav" aria-label="Footer">
                <a href="<?= base_url('home'); ?>">Home</a>
                <a href="<?= base_url('media'); ?>">Movies</a>
                <a href="<?= base_url('show'); ?>">Shows</a>
                <a href="<?= base_url('search'); ?>">Search</a>
                <a href="<?= base_url('news'); ?>">News</a>
                <a href="<?= base_url('dashboard'); ?>">Dashboard</a>
            </nav>
        </div>
    </footer>

    <div class="consent-banner" id="consent-banner" hidden>
        <div class="consent-copy">
            <strong>Cookie and location preferences</strong>
            <p>Allow location to load region-specific streaming providers. You can continue with essential cookies only.</p>
        </div>
        <div class="consent-actions">
            <button type="button" class="btn btn-ghost btn-sm" id="consent-essential">Essential only</button>
            <button type="button" class="btn btn-primary btn-sm" id="consent-accept">Allow optional cookies</button>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/script/main.js') . '?v=' . $jsVersion; ?>"></script>
</body>
</html>