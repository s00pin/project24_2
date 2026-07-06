(() => {
    const baseUrl = String(window.APP_BASE_URL || "").replace(/\/+$/, "");

    const menuToggle = document.getElementById("menu-toggle");
    const siteNav = document.getElementById("site-nav");
    const themeToggle = document.getElementById("theme-toggle");

    const searchInput = document.getElementById("search-input");
    const suggestions = document.getElementById("suggestions");

    const asPosterUrl = (path, size = "w92") => {
        const normalized = String(path || "").trim().replace(/^\/+/, "");
        return normalized ? `https://image.tmdb.org/t/p/${size}/${normalized}` : "";
    };

    const setText = (element, value) => {
        if (element) {
            element.textContent = value;
        }
    };

    function initAuthTabs() {
        const tabButtons = document.querySelectorAll("[data-auth-tab]");
        const panels = document.querySelectorAll("[data-auth-panel]");

        if (!tabButtons.length || !panels.length) {
            return;
        }

        const activate = (mode) => {
            tabButtons.forEach((button) => {
                button.classList.toggle("active", button.getAttribute("data-auth-tab") === mode);
            });

            panels.forEach((panel) => {
                panel.classList.toggle("active", panel.getAttribute("data-auth-panel") === mode);
            });
        };

        tabButtons.forEach((button) => {
            button.addEventListener("click", () => {
                activate(button.getAttribute("data-auth-tab") || "login");
            });
        });
    }

    function initMenuToggle() {
        if (!menuToggle || !siteNav) {
            return;
        }

        const closeMenu = () => {
            siteNav.classList.remove("open");
            menuToggle.setAttribute("aria-expanded", "false");
        };

        menuToggle.addEventListener("click", () => {
            const isOpen = siteNav.classList.toggle("open");
            menuToggle.setAttribute("aria-expanded", isOpen ? "true" : "false");
        });

        document.addEventListener("click", (event) => {
            if (!siteNav.classList.contains("open")) {
                return;
            }

            if (siteNav.contains(event.target) || menuToggle.contains(event.target)) {
                return;
            }

            closeMenu();
        });

        document.addEventListener("keydown", (event) => {
            if (event.key === "Escape") {
                closeMenu();
            }
        });

        siteNav.querySelectorAll("a").forEach((link) => {
            link.addEventListener("click", closeMenu);
        });

        window.addEventListener("resize", () => {
            if (window.innerWidth > 980) {
                closeMenu();
            }
        });

        closeMenu();
    }

    function initThemeToggle() {
        if (!themeToggle) {
            return;
        }

        const root = document.documentElement;

        const setTheme = (theme) => {
            const nextTheme = theme === "dark" ? "dark" : "light";
            root.setAttribute("data-theme", nextTheme);

            try {
                localStorage.setItem("theme", nextTheme);
            } catch (_error) {
                // Ignore localStorage write errors.
            }

            const isDark = nextTheme === "dark";
            themeToggle.textContent = isDark ? "Light mode" : "Dark mode";
            themeToggle.setAttribute("aria-pressed", isDark ? "true" : "false");
        };

        const currentTheme = root.getAttribute("data-theme");
        if (currentTheme !== "dark" && currentTheme !== "light") {
            setTheme("light");
        } else {
            const isDark = currentTheme === "dark";
            themeToggle.textContent = isDark ? "Light mode" : "Dark mode";
            themeToggle.setAttribute("aria-pressed", isDark ? "true" : "false");
        }

        themeToggle.addEventListener("click", () => {
            const activeTheme = root.getAttribute("data-theme");
            setTheme(activeTheme === "dark" ? "light" : "dark");
        });
    }

    function initConsentBanner() {
        const banner = document.getElementById("consent-banner");
        const acceptBtn = document.getElementById("consent-accept");
        const essentialBtn = document.getElementById("consent-essential");

        if (!banner || !acceptBtn || !essentialBtn) {
            return;
        }

        if (!localStorage.getItem("cookieConsent")) {
            banner.hidden = false;
        }

        acceptBtn.addEventListener("click", () => {
            localStorage.setItem("cookieConsent", "accepted");
            banner.hidden = true;
            window.dispatchEvent(new CustomEvent("consent:updated"));
        });

        essentialBtn.addEventListener("click", () => {
            localStorage.setItem("cookieConsent", "essential");
            banner.hidden = true;
            window.dispatchEvent(new CustomEvent("consent:updated"));
        });
    }

    function initSearch() {
        if (!searchInput || !suggestions) {
            return;
        }

        let debounceHandle = null;
        let activeIndex = -1;
        let activeItems = [];

        const hideSuggestions = () => {
            suggestions.innerHTML = "";
            suggestions.style.display = "none";
            activeIndex = -1;
            activeItems = [];
        };

        const goToSuggestion = (item) => {
            if (!item || !item.id) {
                return;
            }

            const target = item.type === "show"
                ? `${baseUrl}/show/${item.id}`
                : `${baseUrl}/media/${item.id}`;

            window.location.href = target;
        };

        const renderSuggestions = (items) => {
            suggestions.innerHTML = "";

            if (!Array.isArray(items) || items.length === 0) {
                hideSuggestions();
                return;
            }

            const fragment = document.createDocumentFragment();

            items.forEach((item, index) => {
                const entry = document.createElement("li");
                entry.className = "suggestion-item";
                entry.setAttribute("role", "option");
                entry.dataset.index = String(index);

                const poster = asPosterUrl(item.Poster, "w92");
                const thumb = poster
                    ? `<img src="${poster}" alt="" loading="lazy" onerror="this.style.display='none'">`
                    : "<span class=\"suggestion-thumb\"></span>";

                entry.innerHTML = `
                    ${thumb}
                    <span class="suggestion-title">${item.Title}</span>
                    <span class="suggestion-type">${item.Type === "show" ? "Show" : "Movie"}</span>
                `;

                entry.addEventListener("click", () => {
                    goToSuggestion({
                        id: item.ID,
                        type: item.Type,
                    });
                });

                fragment.appendChild(entry);
            });

            suggestions.appendChild(fragment);
            suggestions.style.display = "block";
            activeItems = items.map((item) => ({ id: item.ID, type: item.Type }));
        };

        const fetchSuggestions = async (query) => {
            const requestUrl = new URL(`${baseUrl}/search/searchSuggestions`);
            requestUrl.searchParams.set("query", query);

            try {
                const response = await fetch(requestUrl.toString(), {
                    headers: { "Accept": "application/json" },
                });

                if (!response.ok) {
                    hideSuggestions();
                    return;
                }

                const data = await response.json();
                renderSuggestions(data);
            } catch (_error) {
                hideSuggestions();
            }
        };

        searchInput.addEventListener("input", () => {
            const query = String(searchInput.value || "").trim();

            if (debounceHandle) {
                clearTimeout(debounceHandle);
            }

            if (query.length === 0) {
                hideSuggestions();
                return;
            }

            debounceHandle = window.setTimeout(() => fetchSuggestions(query), 170);
        });

        searchInput.addEventListener("keydown", (event) => {
            if (suggestions.style.display !== "block" || activeItems.length === 0) {
                if (event.key === "Escape") {
                    hideSuggestions();
                }
                return;
            }

            if (event.key === "ArrowDown") {
                event.preventDefault();
                activeIndex = Math.min(activeItems.length - 1, activeIndex + 1);
            } else if (event.key === "ArrowUp") {
                event.preventDefault();
                activeIndex = Math.max(0, activeIndex - 1);
            } else if (event.key === "Enter" && activeIndex >= 0) {
                event.preventDefault();
                goToSuggestion(activeItems[activeIndex]);
                return;
            } else if (event.key === "Escape") {
                hideSuggestions();
                return;
            }

            suggestions.querySelectorAll(".suggestion-item").forEach((item, idx) => {
                item.classList.toggle("active", idx === activeIndex);
            });
        });

        document.addEventListener("click", (event) => {
            if (!event.target.closest(".quick-search")) {
                hideSuggestions();
            }
        });
    }

    function initDetailActions() {
        const detailRoot = document.querySelector(".js-title-detail");
        if (!detailRoot) {
            return;
        }

        const providerStatus = document.getElementById("providers-status");
        const providerGrid = document.getElementById("providers-grid");

        const mediaType = detailRoot.getAttribute("data-media-type");
        const mediaId = detailRoot.getAttribute("data-media-id");

        const renderProviderGroups = (providerMap) => {
            if (!providerGrid) {
                return;
            }

            providerGrid.innerHTML = "";

            const labels = {
                flatrate: "Streaming",
                rent: "Rent",
                buy: "Buy",
                free: "Free",
                ads: "With ads",
            };

            Object.keys(labels).forEach((key) => {
                const providers = providerMap[key];

                if (!Array.isArray(providers) || providers.length === 0) {
                    return;
                }

                const logos = providers
                    .filter((provider) => provider.logo)
                    .map((provider) => `<img src="${provider.logo}" alt="${provider.name}" title="${provider.name}" loading="lazy" onerror="this.style.display='none'">`)
                    .join("");

                const names = providers.map((provider) => provider.name).join(", ");

                const card = document.createElement("article");
                card.className = "provider-card";
                card.innerHTML = `
                    <h4>${labels[key]}</h4>
                    <div class="provider-logos">${logos}</div>
                    <p>${names}</p>
                `;

                providerGrid.appendChild(card);
            });
        };

        const fetchProviders = async (country) => {
            if (!mediaType || !mediaId || !providerStatus || !providerGrid) {
                return;
            }

            setText(providerStatus, `Loading providers for ${country}...`);

            try {
                const response = await fetch(`${baseUrl}/api/watch-providers/${mediaType}/${mediaId}?country=${country}`);
                const data = await response.json();

                if (!response.ok || !data.ok) {
                    setText(providerStatus, data.message || "Could not load watch providers right now.");
                    return;
                }

                renderProviderGroups(data.providers || {});

                if (providerGrid.children.length === 0) {
                    setText(providerStatus, "No providers listed for this title in your region.");
                    return;
                }

                if (data.link) {
                    providerStatus.innerHTML = `Showing results for ${data.resolvedCountry || country}. <a href="${data.link}" target="_blank" rel="noopener noreferrer">Open provider page</a>`;
                } else {
                    setText(providerStatus, `Showing results for ${data.resolvedCountry || country}.`);
                }
            } catch (_error) {
                setText(providerStatus, "Unable to load provider details right now.");
            }
        };

        const detectCountryFromGeo = async () => {
            const position = await new Promise((resolve, reject) => {
                navigator.geolocation.getCurrentPosition(resolve, reject, {
                    timeout: 10000,
                    enableHighAccuracy: true,
                });
            });

            const { latitude, longitude } = position.coords;
            const response = await fetch(`https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=${latitude}&longitude=${longitude}&localityLanguage=en`);
            const data = await response.json();
            const countryCode = String(data.countryCode || "").toUpperCase();

            return /^[A-Z]{2}$/.test(countryCode) ? countryCode : "US";
        };

        const requestGeoAndLoad = async () => {
            if (!providerStatus) {
                return;
            }

            if (!navigator.geolocation) {
                await fetchProviders("US");
                return;
            }

            setText(providerStatus, "Requesting location permission...");

            try {
                const country = await detectCountryFromGeo();
                localStorage.setItem("geoConsent", "accepted");
                await fetchProviders(country);
            } catch (_error) {
                localStorage.setItem("geoConsent", "rejected");
                setText(providerStatus, "Location denied. Showing US providers instead.");
                await fetchProviders("US");
            }
        };

        const loadProvidersRespectingConsent = () => {
            if (!providerStatus) {
                return;
            }

            const cookieConsent = localStorage.getItem("cookieConsent");
            const geoConsent = localStorage.getItem("geoConsent");

            if (!cookieConsent) {
                setText(providerStatus, "Choose cookie preferences first to load provider availability.");
                return;
            }

            if (geoConsent === "accepted") {
                requestGeoAndLoad();
                return;
            }

            if (geoConsent === "rejected") {
                fetchProviders("US");
                return;
            }

            providerStatus.innerHTML = "Allow location for local provider results. <button class=\"btn btn-ghost btn-sm\" id=\"ask-geo-btn\" type=\"button\">Allow location</button>";
            const askGeoBtn = document.getElementById("ask-geo-btn");
            if (askGeoBtn) {
                askGeoBtn.addEventListener("click", requestGeoAndLoad, { once: true });
            }
        };

        const postForm = async (url, payload) => {
            const response = await fetch(url, {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
                },
                body: new URLSearchParams(payload).toString(),
            });

            const data = await response.json().catch(() => ({ ok: false }));
            return { response, data };
        };

        const toggleLike = async (button) => {
            const feedback = document.getElementById("list-feedback");
            const likesCount = document.getElementById("likes-count");

            button.disabled = true;

            try {
                const { response, data } = await postForm(`${baseUrl}/api/likes/toggle`, {
                    media_type: mediaType,
                    media_id: mediaId,
                });

                if (response.status === 401) {
                    window.location.href = `${baseUrl}/login`;
                    return;
                }

                if (!response.ok || !data.ok) {
                    throw new Error(data.message || "Could not update this like right now.");
                }

                const liked = !!data.liked;
                button.classList.toggle("is-active", liked);
                button.textContent = liked ? "Liked" : "Like";

                if (likesCount) {
                    likesCount.textContent = String(data.likesCount || 0);
                }

                setText(feedback, liked ? "Added to your liked titles." : "Removed from your liked titles.");
            } catch (error) {
                setText(feedback, error.message || "Could not update this like right now.");
            } finally {
                button.disabled = false;
            }
        };

        const toggleListOption = async (button) => {
            const listId = button.getAttribute("data-list-id");
            const feedback = document.getElementById("list-feedback");

            if (!listId) {
                return;
            }

            button.disabled = true;

            try {
                const { response, data } = await postForm(`${baseUrl}/api/lists/toggle-item`, {
                    list_id: listId,
                    media_type: mediaType,
                    media_id: mediaId,
                });

                if (response.status === 401) {
                    window.location.href = `${baseUrl}/login`;
                    return;
                }

                if (!response.ok || !data.ok) {
                    throw new Error(data.message || "Could not update list membership.");
                }

                const inList = !!data.inList;
                button.classList.toggle("active", inList);
                const mark = button.querySelector(".list-option-mark");
                if (mark) {
                    mark.textContent = inList ? "Added" : "Add";
                }

                setText(feedback, inList ? "Added to list." : "Removed from list.");
            } catch (error) {
                setText(feedback, error.message || "Could not update list membership.");
            } finally {
                button.disabled = false;
            }
        };

        detailRoot.querySelectorAll("[data-like-toggle='1']").forEach((button) => {
            button.addEventListener("click", () => toggleLike(button));
        });

        detailRoot.querySelectorAll("[data-list-option='1']").forEach((button) => {
            button.addEventListener("click", () => toggleListOption(button));
        });

        detailRoot.querySelectorAll("[data-list-dropdown='1']").forEach((dropdown) => {
            const toggle = dropdown.querySelector(".list-dropdown-toggle");
            if (!toggle) {
                return;
            }

            toggle.addEventListener("click", () => {
                dropdown.classList.toggle("open");
            });

            document.addEventListener("click", (event) => {
                if (!dropdown.contains(event.target)) {
                    dropdown.classList.remove("open");
                }
            });
        });

        window.addEventListener("consent:updated", loadProvidersRespectingConsent);
        loadProvidersRespectingConsent();
    }

    function initDashboardListActions() {
        const createListBtn = document.getElementById("create-list-btn");
        const feedback = document.getElementById("list-manage-feedback");

        const setFeedback = (message) => {
            if (feedback) {
                feedback.textContent = message;
            }
        };

        const postForm = async (url, payload) => {
            const response = await fetch(url, {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
                },
                body: new URLSearchParams(payload).toString(),
            });

            const data = await response.json().catch(() => ({ ok: false }));
            return { response, data };
        };

        if (createListBtn) {
            createListBtn.addEventListener("click", async () => {
                const nameInput = document.getElementById("new-list-name");
                const name = String(nameInput?.value || "").trim();

                if (!name) {
                    setFeedback("Enter a list name first.");
                    return;
                }

                createListBtn.disabled = true;

                try {
                    const { response, data } = await postForm(`${baseUrl}/api/lists/create`, { name });

                    if (!response.ok || !data.ok) {
                        throw new Error(data.message || "Could not create list.");
                    }

                    setFeedback("List created. Refreshing...");
                    setTimeout(() => window.location.reload(), 330);
                } catch (error) {
                    setFeedback(error.message || "Could not create list.");
                } finally {
                    createListBtn.disabled = false;
                }
            });
        }

        document.querySelectorAll(".rename-list-btn").forEach((button) => {
            button.addEventListener("click", async () => {
                const listId = button.getAttribute("data-list-id");
                const input = document.querySelector(`.rename-list-input[data-list-id='${listId}']`);
                const name = String(input?.value || "").trim();

                if (!listId || !name) {
                    setFeedback("Enter a new list name first.");
                    return;
                }

                button.disabled = true;

                try {
                    const { response, data } = await postForm(`${baseUrl}/api/lists/${listId}/rename`, { name });

                    if (!response.ok || !data.ok) {
                        throw new Error(data.message || "Could not rename list.");
                    }

                    setFeedback("List renamed. Refreshing...");
                    setTimeout(() => window.location.reload(), 330);
                } catch (error) {
                    setFeedback(error.message || "Could not rename list.");
                } finally {
                    button.disabled = false;
                }
            });
        });

        document.querySelectorAll(".delete-list-btn").forEach((button) => {
            button.addEventListener("click", async () => {
                const listId = button.getAttribute("data-list-id");
                if (!listId) {
                    return;
                }

                if (!window.confirm("Delete this list permanently?")) {
                    return;
                }

                button.disabled = true;

                try {
                    const response = await fetch(`${baseUrl}/api/lists/${listId}/delete`, {
                        method: "POST",
                    });

                    const data = await response.json().catch(() => ({ ok: false }));

                    if (!response.ok || !data.ok) {
                        throw new Error(data.message || "Could not delete list.");
                    }

                    setFeedback("List deleted. Refreshing...");
                    setTimeout(() => window.location.reload(), 330);
                } catch (error) {
                    setFeedback(error.message || "Could not delete list.");
                } finally {
                    button.disabled = false;
                }
            });
        });
    }

    initAuthTabs();
    initThemeToggle();
    initMenuToggle();
    initConsentBanner();
    initSearch();
    initDetailActions();
    initDashboardListActions();
})();
