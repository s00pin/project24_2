$(function () {
    const $searchInput = $('#search-input');
    const $suggestions = $('#suggestions');

    function normalizePosterUrl(path, size) {
        if (!path) {
            return '';
        }

        const cleanPath = String(path).trim().replace(/^\/+/, '');
        return cleanPath ? `https://image.tmdb.org/t/p/${size}/${cleanPath}` : '';
    }

    function initAuthTabs() {
        const tabButtons = document.querySelectorAll('[data-auth-tab]');
        const panels = document.querySelectorAll('[data-auth-panel]');
        if (!tabButtons.length || !panels.length) {
            return;
        }

        function activate(mode) {
            tabButtons.forEach((btn) => btn.classList.toggle('active', btn.getAttribute('data-auth-tab') === mode));
            panels.forEach((panel) => panel.classList.toggle('active', panel.getAttribute('data-auth-panel') === mode));
        }

        tabButtons.forEach((btn) => {
            btn.addEventListener('click', () => activate(btn.getAttribute('data-auth-tab')));
        });
    }

    function initConsentBanner() {
        const banner = document.getElementById('consent-banner');
        const acceptBtn = document.getElementById('consent-accept');
        const essentialBtn = document.getElementById('consent-essential');
        if (!banner || !acceptBtn || !essentialBtn) {
            return;
        }

        const consent = localStorage.getItem('cookieConsent');
        if (!consent) {
            banner.hidden = false;
        }

        acceptBtn.addEventListener('click', () => {
            localStorage.setItem('cookieConsent', 'accepted');
            banner.hidden = true;
            window.dispatchEvent(new CustomEvent('consent:updated'));
        });

        essentialBtn.addEventListener('click', () => {
            localStorage.setItem('cookieConsent', 'essential');
            banner.hidden = true;
            window.dispatchEvent(new CustomEvent('consent:updated'));
        });
    }

    function initSearch() {
        if ($searchInput.length === 0 || $suggestions.length === 0) {
            return;
        }

        function hideSuggestions() {
            $suggestions.empty().hide();
        }

        $searchInput.on('keyup', function () {
            const query = String($(this).val() || '').trim();

            $.ajax({
                url: `${window.APP_BASE_URL}/search/searchSuggestions`,
                method: 'GET',
                data: { query },
                success: function (data) {
                    $suggestions.empty();

                    if (!Array.isArray(data) || data.length === 0) {
                        hideSuggestions();
                        return;
                    }

                    data.forEach(function (item) {
                        const posterPath = normalizePosterUrl(item.Poster, 'w92');
                        const posterMarkup = posterPath
                            ? `<img src="${posterPath}" alt="" onerror="this.style.display='none'">`
                            : '<div style="width:40px;height:56px;border-radius:8px;background:rgba(255,255,255,.15)"></div>';

                        $suggestions.append(`
                            <li class="suggestion-item" data-id="${item.ID}" data-type="${item.Type}">
                                ${posterMarkup}
                                <span>${item.Title}</span>
                            </li>
                        `);
                    });

                    $suggestions.show();
                },
                error: hideSuggestions,
            });
        });

        $suggestions.on('click', '.suggestion-item', function () {
            const id = $(this).data('id');
            const type = $(this).data('type');
            const target = type === 'show' ? `${window.APP_BASE_URL}/show/${id}` : `${window.APP_BASE_URL}/media/${id}`;
            window.location.href = target;
        });

        $(document).on('click', function (event) {
            if (!$(event.target).closest('.search-wrap').length) {
                hideSuggestions();
            }
        });
    }

    function initDetailActions() {
        const detailRoot = document.querySelector('.js-title-detail');
        const providerStatus = document.getElementById('providers-status');
        const providerGrid = document.getElementById('providers-grid');
        if (!detailRoot) {
            return;
        }

        async function detectCountryFromGeo() {
            const position = await new Promise((resolve, reject) => {
                navigator.geolocation.getCurrentPosition(resolve, reject, {
                    timeout: 10000,
                    enableHighAccuracy: true,
                });
            });

            const { latitude, longitude } = position.coords;
            const response = await fetch(
                `https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=${latitude}&longitude=${longitude}&localityLanguage=en`
            );
            const data = await response.json();
            const countryCode = String(data.countryCode || '').toUpperCase();
            return /^[A-Z]{2}$/.test(countryCode) ? countryCode : 'US';
        }

        function renderProviderGroups(providerMap) {
            if (!providerGrid) {
                return;
            }

            providerGrid.innerHTML = '';

            const labels = {
                flatrate: 'Streaming',
                rent: 'Rent',
                buy: 'Buy',
                free: 'Free',
                ads: 'With Ads',
            };

            Object.keys(labels).forEach((key) => {
                const items = providerMap[key];
                if (!Array.isArray(items) || items.length === 0) {
                    return;
                }

                const names = items.map((p) => p.name).join(', ');
                const logos = items
                    .filter((p) => p.logo)
                    .map((p) => `<img src="${p.logo}" alt="${p.name}" title="${p.name}" onerror="this.style.display='none'">`)
                    .join('');

                const card = document.createElement('div');
                card.className = 'provider-card';
                card.innerHTML = `
                    <h4>${labels[key]}</h4>
                    <div class="provider-logos">${logos}</div>
                    <p>${names}</p>
                `;
                providerGrid.appendChild(card);
            });
        }

        async function fetchProviders(country) {
            const mediaType = detailRoot.getAttribute('data-media-type');
            const mediaId = detailRoot.getAttribute('data-media-id');
            if (!mediaType || !mediaId || !providerStatus || !providerGrid) {
                return;
            }

            providerStatus.textContent = `Loading providers for ${country}...`;

            try {
                const response = await fetch(`${window.APP_BASE_URL}/api/watch-providers/${mediaType}/${mediaId}?country=${country}`);
                const data = await response.json();

                if (!data.ok) {
                    providerStatus.textContent = data.message || 'Could not load providers right now.';
                    return;
                }

                renderProviderGroups(data.providers || {});
                if (providerGrid.children.length === 0) {
                    providerStatus.textContent = 'No providers found for this title in your region yet.';
                    return;
                }

                const regionShown = data.resolvedCountry || country;
                const linkMarkup = data.link
                    ? ` <a href="${data.link}" target="_blank" rel="noopener noreferrer">Open provider page</a>`
                    : '';
                providerStatus.innerHTML = `Showing results for ${regionShown}.${linkMarkup}`;
            } catch (_error) {
                providerStatus.textContent = 'Unable to load watch providers right now.';
            }
        }

        async function askAndLoadGeoProviders() {
            if (!navigator.geolocation) {
                fetchProviders('US');
                return;
            }

            if (!providerStatus) {
                return;
            }

            providerStatus.textContent = 'Requesting location permission...';

            try {
                const country = await detectCountryFromGeo();
                localStorage.setItem('geoConsent', 'accepted');
                await fetchProviders(country);
            } catch (_error) {
                localStorage.setItem('geoConsent', 'rejected');
                providerStatus.innerHTML = 'Location access denied. Showing US providers instead.';
                await fetchProviders('US');
            }
        }

        function loadProvidersRespectingConsent() {
            if (!providerStatus) {
                return;
            }

            const cookieConsent = localStorage.getItem('cookieConsent');
            const geoConsent = localStorage.getItem('geoConsent');

            if (!cookieConsent) {
                providerStatus.textContent = 'Please choose your cookie preferences to continue.';
                return;
            }

            if (geoConsent === 'accepted') {
                askAndLoadGeoProviders();
                return;
            }

            if (geoConsent === 'rejected') {
                providerStatus.textContent = 'Using US providers (location permission denied earlier).';
                fetchProviders('US');
                return;
            }

            providerStatus.innerHTML = 'Allow location to see providers in your country. <button class="btn btn-outline-light btn-sm ms-2" id="ask-geo-btn" type="button">Allow location</button>';
            const askBtn = document.getElementById('ask-geo-btn');
            if (askBtn) {
                askBtn.addEventListener('click', askAndLoadGeoProviders, { once: true });
            }
        }

        async function toggleLike(button) {
            const mediaType = button.getAttribute('data-media-type');
            const mediaId = button.getAttribute('data-media-id');
            const feedback = document.getElementById('list-feedback');
            const likesCountEl = document.getElementById('likes-count');

            if (!mediaType || !mediaId) {
                return;
            }

            button.disabled = true;
            try {
                const body = new URLSearchParams();
                body.append('media_type', mediaType);
                body.append('media_id', mediaId);

                const response = await fetch(`${window.APP_BASE_URL}/api/likes/toggle`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
                    body: body.toString(),
                });

                const data = await response.json().catch(() => ({ ok: false }));
                if (response.status === 401) {
                    window.location.href = `${window.APP_BASE_URL}/login`;
                    return;
                }

                if (!response.ok || !data.ok) {
                    throw new Error(data.message || 'Unable to update like right now.');
                }

                const liked = !!data.liked;
                button.classList.toggle('is-active', liked);
                button.textContent = liked ? 'Liked' : 'Like';
                if (likesCountEl) {
                    likesCountEl.textContent = String(data.likesCount || 0);
                }
                if (feedback) {
                    feedback.textContent = liked ? 'Added to liked titles.' : 'Removed from liked titles.';
                }
            } catch (error) {
                if (feedback) {
                    feedback.textContent = error.message || 'Unable to update like right now.';
                }
            } finally {
                button.disabled = false;
            }
        }

        async function toggleListOption(button) {
            const listId = button.getAttribute('data-list-id');
            const mediaType = button.getAttribute('data-media-type');
            const mediaId = button.getAttribute('data-media-id');
            const feedback = document.getElementById('list-feedback');

            if (!listId || !mediaType || !mediaId) {
                return;
            }

            button.disabled = true;
            try {
                const body = new URLSearchParams();
                body.append('list_id', listId);
                body.append('media_type', mediaType);
                body.append('media_id', mediaId);

                const response = await fetch(`${window.APP_BASE_URL}/api/lists/toggle-item`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
                    body: body.toString(),
                });

                const data = await response.json().catch(() => ({ ok: false }));
                if (response.status === 401) {
                    window.location.href = `${window.APP_BASE_URL}/login`;
                    return;
                }

                if (!response.ok || !data.ok) {
                    throw new Error(data.message || 'Unable to update list right now.');
                }

                const inList = !!data.inList;
                button.classList.toggle('active', inList);
                const mark = button.querySelector('.list-option-mark');
                if (mark) {
                    mark.textContent = inList ? 'Added' : 'Add';
                }

                if (feedback) {
                    feedback.textContent = inList ? 'Added to list.' : 'Removed from list.';
                }
            } catch (error) {
                if (feedback) {
                    feedback.textContent = error.message || 'Unable to update list right now.';
                }
            } finally {
                button.disabled = false;
            }
        }

        document.querySelectorAll('[data-like-toggle="1"]').forEach((button) => {
            button.addEventListener('click', () => toggleLike(button));
        });

        document.querySelectorAll('[data-list-option="1"]').forEach((button) => {
            button.addEventListener('click', () => toggleListOption(button));
        });

        document.querySelectorAll('[data-list-dropdown="1"]').forEach((dropdown) => {
            const toggle = dropdown.querySelector('.list-dropdown-toggle');
            const menu = dropdown.querySelector('.list-dropdown-menu');
            if (!toggle || !menu) {
                return;
            }

            toggle.addEventListener('click', () => {
                dropdown.classList.toggle('open');
            });

            document.addEventListener('click', (event) => {
                if (!dropdown.contains(event.target)) {
                    dropdown.classList.remove('open');
                }
            });
        });

        window.addEventListener('consent:updated', loadProvidersRespectingConsent);
        loadProvidersRespectingConsent();
    }

    function initDashboardListActions() {
        const createListBtn = document.getElementById('create-list-btn');
        const feedback = document.getElementById('list-manage-feedback');

        if (createListBtn) {
            createListBtn.addEventListener('click', async () => {
                const input = document.getElementById('new-list-name');
                const name = String(input?.value || '').trim();

                if (!name) {
                    if (feedback) feedback.textContent = 'Enter a list name first.';
                    return;
                }

                createListBtn.disabled = true;
                try {
                    const body = new URLSearchParams();
                    body.append('name', name);

                    const response = await fetch(`${window.APP_BASE_URL}/api/lists/create`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
                        body: body.toString(),
                    });

                    const data = await response.json().catch(() => ({ ok: false }));
                    if (!response.ok || !data.ok) {
                        throw new Error(data.message || 'Could not create list.');
                    }

                    if (feedback) feedback.textContent = 'List created. Refreshing...';
                    setTimeout(() => window.location.reload(), 450);
                } catch (error) {
                    if (feedback) feedback.textContent = error.message || 'Could not create list.';
                } finally {
                    createListBtn.disabled = false;
                }
            });
        }

        document.querySelectorAll('.rename-list-btn').forEach((btn) => {
            btn.addEventListener('click', async () => {
                const listId = btn.getAttribute('data-list-id');
                const input = document.querySelector(`.rename-list-input[data-list-id="${listId}"]`);
                const name = String(input?.value || '').trim();

                if (!name) {
                    if (feedback) feedback.textContent = 'Enter a new name first.';
                    return;
                }

                btn.disabled = true;
                try {
                    const body = new URLSearchParams();
                    body.append('name', name);

                    const response = await fetch(`${window.APP_BASE_URL}/api/lists/${listId}/rename`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
                        body: body.toString(),
                    });

                    const data = await response.json().catch(() => ({ ok: false }));
                    if (!response.ok || !data.ok) {
                        throw new Error(data.message || 'Could not rename list.');
                    }

                    if (feedback) feedback.textContent = 'List renamed. Refreshing...';
                    setTimeout(() => window.location.reload(), 450);
                } catch (error) {
                    if (feedback) feedback.textContent = error.message || 'Could not rename list.';
                } finally {
                    btn.disabled = false;
                }
            });
        });

        document.querySelectorAll('.delete-list-btn').forEach((btn) => {
            btn.addEventListener('click', async () => {
                const listId = btn.getAttribute('data-list-id');
                if (!listId) {
                    return;
                }

                const shouldDelete = window.confirm('Delete this list permanently?');
                if (!shouldDelete) {
                    return;
                }

                btn.disabled = true;
                try {
                    const response = await fetch(`${window.APP_BASE_URL}/api/lists/${listId}/delete`, {
                        method: 'POST',
                    });

                    const data = await response.json().catch(() => ({ ok: false }));
                    if (!response.ok || !data.ok) {
                        throw new Error(data.message || 'Could not delete list.');
                    }

                    if (feedback) feedback.textContent = 'List deleted. Refreshing...';
                    setTimeout(() => window.location.reload(), 450);
                } catch (error) {
                    if (feedback) feedback.textContent = error.message || 'Could not delete list.';
                } finally {
                    btn.disabled = false;
                }
            });
        });
    }

    initAuthTabs();
    initConsentBanner();
    initSearch();
    initDetailActions();
    initDashboardListActions();
});
