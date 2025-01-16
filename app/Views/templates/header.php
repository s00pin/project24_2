<!doctype html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta charset="UTF-8">
<meta charset="UTF-8">
<meta name="description" content="Media info collection">
<meta name="author" content="Swopnil Sapkota">
<meta name="generator" content="Swopnil 0.0.1">
<title>Project24</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/main.css'); ?>">
<link rel="icon" type="image/x-icon" href="<?= base_url('assets/image/logo.ico'); ?>">
</head>
<style>
@media (min-width: 992px) { 
    .wide-search {
        width: 700px; 
    }

    .suggestion-box {
        width: 700px; /* Set the suggestion box width to match the search bar width */
    }
}

@media (max-width: 991.98px) { 
    .wide-search {
        width: 100%; 
    }

    .suggestion-box {
        width: calc(100% - 20px); /* Make suggestions as wide as the search input */
    }

    .suggestion-item {
        padding: .5rem;
        color: #212529;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .suggestion-item:hover {
        background-color: #f8f9fa;
        color: red;
    }
}


</style>
<body>
<header class="navbar navbar-expand-lg mb-4">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= base_url('home'); ?>">
            <img rel="icon" type="image/x-icon" src="<?= base_url('assets/image/logo.png'); ?>" alt="icon" class="me-2" style="height: 50px; width: 50px;">
            <strong class="title">Movies</strong>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Centered Search Form -->
            <div class="mx-auto position-relative">
                <form class="d-flex" action="<?= base_url('search') ?>" method="get">
                    <input id="search-input" class="form-control wide-search" type="search" name="query" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success btn-warning ms-2 bold" type="submit">Search</button>
                </form>
                <ul id="suggestions" class="dropdown-menu suggestion-box mt-1" style="display: none;"></ul>
            </div>

            <!-- Navigation items aligned to the right -->
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link top-nav" href="<?= base_url('home'); ?>">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link top-nav" href="<?= base_url('media'); ?>">Movies</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link top-nav" href="<?= base_url('show'); ?>">Shows</a>
                </li>
            </ul>
        </div>
    </div>
</header>
<h1><?= esc($title) ?></h1>
<hr>
<div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<script>
$(document).ready(function () {
    $('#search-input').on('keyup', function () {
        let query = $(this).val();

        if (query.length > 1) { // Start suggesting after 2+ characters
            $.ajax({
                url: '<?= base_url("search/searchSuggestions") ?>', // Corrected URL
                method: 'GET',
                data: { query: query },
                success: function (data) {
                    let suggestions = $('#suggestions');
                    suggestions.empty(); // Clear previous suggestions

                    if (data.length > 0) {
                        suggestions.empty().show(); // Show suggestions box

                        data.forEach(function (item) {
                            suggestions.append(
                                `<li class="suggestion-item d-flex align-items-center" data-id="${item.ID}" data-type="${item.Type}">
                                    <img src="https://image.tmdb.org/t/p/w300_and_h450_bestv2/${item.Poster}" style="width: 50px; height: 70px; margin-right: 10px;">
                                    ${item.Title}
                                </li>`

                            );
                        });

                        // Handle click events to navigate
                        $('.suggestion-item').on('click', function (e) {
                            e.preventDefault();
                            let id = $(this).data('id');
                            let type = $(this).data('type');
                            if (type === 'media') {
                                window.location.href = `<?= base_url('media/') ?>${id}`;
                            } else if (type === 'show') {
                                window.location.href = `<?= base_url('show/') ?>${id}`;
                            }
                        });
                    } else {
                        suggestions.empty().hide(); // Hide if no suggestions
                    }
                }
            });
        } else {
            $('#suggestions').empty().hide(); // Clear and hide if query is too short
        }
    });
});

</script>


