<div class="full-page" style="background-image: url('https://image.tmdb.org/t/p/original/<?= esc($show['background']) ?>'); background-size: cover; background-position: center; "></div>
<?php $show_id = $show['id']; ?>
<div class="container details-container my-2">
    <div class="row align-items-center">
        <div class="col-md-4">
            <img src="https://image.tmdb.org/t/p/w300_and_h450_bestv2/<?= esc($show['poster']) ?>" class="img-fluid bi mt-4 mb-3 rounded" alt="Poster">
        </div>
        <div class="col-md-8">
            <div class="p-4  bg-body-tertiary rounded-3">
                <h1 class="text-body-emphasis text-center"><?= esc($show['title']) ?></h1>
                <p class=" mx-1 fs-5 text-muted">
                    <?= esc($show['overview']) ?>
                </p>
                
                
            </div>
            
        </div>
       
        <div class="col-lg-12 mx-auto fs-5 text-muted  bg-body-tertiary rounded-3 mt-2">
                <div class="row mt-3">
                    <div class="col-md-6">
                        <p><strong>Release Dates:</strong> <?= esc($show['begin_date']) ?> - <?= esc($show['end_date']) ?></p>
                        <p><strong>Genre:</strong> <?= esc($show['genre']) ?></p>
                        <p><strong>Language:</strong> <?= esc($show['language']) ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Runtime:</strong> <?= esc($show['runtime']) ?> minutes</p>
                        <p><strong>Seasons:</strong> <?= esc($show['seasons']) ?></p>
                        <p><strong>Episodes:</strong> <?= esc($show['episodes']) ?></p>
                        
                    </div>
                </div>
            </div>
            <div class="col-lg-12 mx-auto fs-5 text-muted  bg-body-tertiary rounded-3 mt-2 fst-italic">
                <div class="row mt-3">
                <h4>You can find this in :</h4>
                    <div id="watch-providers">Loading...</div>
                </div>
            </div>
</div>
    </div>
</div>
<script>
if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            showPosition,
            showError,
            { timeout: 10000, enableHighAccuracy: true }
        );
    } else {
        console.error("Geolocation is not supported by this browser.");
    }

    function showPosition(position) {
        const latitude = position.coords.latitude;
        const longitude = position.coords.longitude;

        fetchRegionAndWatchProviders(latitude, longitude); // Function defined previously
    }

    function showError(error) {
        console.error("Geolocation error:", error);
    }

    function fetchRegionAndWatchProviders(latitude, longitude) {
  // API endpoint for reverse geocoding
    const reverseGeocodeUrl = `https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=${latitude}&longitude=${longitude}&localityLanguage=en`;

    // Fetch the region based on geolocation
    fetch(reverseGeocodeUrl)
        .then(response => response.json())
        .then(data => {
        const countryCode = data.countryCode; // Get the country code from geocoding
        fetchWatchProviders(countryCode); // Fetch watch providers based on country code
        })
        .catch(error => {
        console.error("Error fetching region:", error);
        });
    }
    

    
    function fetchWatchProviders(countryCode) {
        
            const showId = <?= json_encode($show_id) ?>; 
            const apiUrl = `https://api.themoviedb.org/3/tv/${showId}/watch/providers`;

            // Define options for the fetch request
            const options = {
                    method: 'GET',
                    headers: {
                            accept: 'application/json',
                            Authorization: 'Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiJmM2ZkMmM1ODhmNjViMzVlYjA4ZjRkMTliYzJmYWJiMyIsInN1YiI6IjY2MjVlYmZlNjNlNmZiMDE3ZWZjOTE1MyIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.Qb_NLScHpCLLeyTLteXvhFN273YvCBESB-vRw4O44iI'
                    },
            };

            // Check if the watch providers are already in cache
            const cacheKey = `watchProviders_${showId}_${countryCode}`;
            const cachedData = localStorage.getItem(cacheKey);

            if (cachedData) {
                    const watchProvidersDiv = document.getElementById('watch-providers');
                    watchProvidersDiv.innerHTML = cachedData; // Display the cached watch providers
            } else {
                    fetch(apiUrl, options)
                            .then(response => response.json())
                            .then(data => {
                                    const watchProvidersDiv = document.getElementById('watch-providers'); // The target div to display the results

                                    if (data.results[countryCode]) {
                                            const regionData = data.results[countryCode];
                                            console.log(regionData)
                                            let content = '<ul>';

                                            if (regionData.flatrate) {
                                                    content += `<li>Flatrate: ${regionData.flatrate.map(p => p.provider_name).join(', ')}</li>`;
                                            }

                                            if (regionData.ads) {
                                                    content += `<li>Ads: ${regionData.ads.map(p => p.provider_name).join(', ')}</li>`;
                                            }

                                            if (regionData.buy) {
                                                    content += `<li>Buy: ${regionData.buy.map(p => p.provider_name).join(', ')}</li>`;
                                            }

                                            content += '</ul>';
                                            watchProvidersDiv.innerHTML = content; // Display the watch providers section

                                            // Save the watch providers in cache
                                            localStorage.setItem(cacheKey, content);
                                    } else {
                                            watchProvidersDiv.innerHTML = 'No watch providers found for this region.';
                                    }
                            })
                            .catch(error => {
                                    console.error("Error fetching watch providers:", error);
                                    watchProvidersDiv.innerText = 'Error loading watch providers.';
                            });
            }
    }
</script>
