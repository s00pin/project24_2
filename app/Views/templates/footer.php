    </main>

    <footer class="site-footer">
        <div class="container footer-inner">
            <p class="mb-0">Media Hub - Curated movie and TV discovery</p>
            <nav class="footer-nav">
                <a href="<?= base_url('home'); ?>">Home</a>
                <a href="<?= base_url('media'); ?>">Movies</a>
                <a href="<?= base_url('show'); ?>">Shows</a>
                <a href="<?= base_url('dashboard'); ?>">Dashboard</a>
            </nav>
        </div>
    </footer>

    <div class="consent-banner" id="consent-banner" hidden>
        <div class="consent-copy">
            <strong>Privacy choices</strong>
            <p>We use cookies for session/login and optional location access to show watch providers in your region. You can continue with essential cookies only.</p>
        </div>
        <div class="consent-actions">
            <button type="button" class="btn btn-outline-light btn-sm" id="consent-essential">Essential only</button>
            <button type="button" class="btn btn-accent btn-sm" id="consent-accept">Accept cookies</button>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="<?= base_url('assets/script/main.js'); ?>"></script>
</body>
</html>
