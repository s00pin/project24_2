<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Information</title>
</head>
<body>
    <h1>Top Movies</h1>
    <div id="movies">
        <?php
        $curl = curl_init();

        curl_setopt_array($curl, [
          CURLOPT_URL => "https://api.themoviedb.org/3/discover/movie?include_adult=false&include_video=false&language=en-US&page=1&sort_by=popularity.desc",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => [
            "Authorization: Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiIyZjA1NTM3ZGE5YzBkMTY5NWMwNWE5OGFhYTk2YWY4ZiIsInN1YiI6IjY2MmFhZDQxY2FhNTA4MDEyMDFmZDYyNyIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.kwxQxS1mZhikm9ERlX-xcjuf9_8Cy6Bzj-OqCHXCU-8",
            "accept: application/json"
          ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
            $data = json_decode($response, true);

            // Check if data is available
            if(isset($data['results']) && !empty($data['results'])) {
                // Loop through each movie
                foreach($data['results'] as $movie) {
                    // Print important information
                    echo "<div>";
                    echo "<h2>" . $movie['title'] . "</h2>";
                    echo "<p><strong>Overview:</strong> " . $movie['overview'] . "</p>";
                    echo "<p><strong>Release Date:</strong> " . $movie['release_date'] . "</p>";
                    echo "</div>";
                }
            } else {
                echo "No movies found.";
            }
        }
        ?>
    </div>
</body>
</html>
