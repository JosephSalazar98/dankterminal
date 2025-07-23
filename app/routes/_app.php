<?php





app()->view('/', 'index');

app()->post('/memes/load-array', [
    'middleware' => 'auth.required',
    'EmbedsController@loadFromArray'
]);

app()->post('/memes/generate-embeddings', [
    'middleware' => 'auth.required',
    'EmbedsController@generateEmbeddings'
]);

app()->post('/memes/find-match', 'EmbedsController@matchPrompt');

app()->post('/memes/generate', 'EmbedsController@generateFinalMeme');

app()->post('/telegram/webhook', 'TelegramsController@webhook');

app()->get('/gallery', 'GalleriesController@showGallery');

app()->get('/meme/{id}', 'EmbedsController@showMeme');

app()->get('/login', ['middleware' => 'auth.guest', function () {
    return render('login');
}]);

// Proceso de login
app()->post('/login', function () {
    $success = auth()->login([
        'email' => request()->get('email'),
        'password' => request()->get('password')
    ]);

    if ($success) {
        response()->redirect('/dashboard');
    } else {
        echo "Incorrect: " . json_encode(auth()->errors());
    }
});


// Logout
app()->get('/logout', function () {
    auth()->logout('/login');
});

app()->get('/dashboard', ['middleware' => 'auth.required', function () {
    echo <<<HTML
    <h2>Upload new meme</h2>
    <form method="POST" action="/upload" enctype="multipart/form-data">
      <input type="file" name="image" required /><br>
      <textarea name="description" placeholder="description" required></textarea><br>
      <button>Upload</button>
    </form>
    <a href="/logout">Log out</a>
  HTML;
}]);

app()->post('/upload', ['middleware' => 'auth.required', function () {
    $file = request()->files('image');
    $desc = request()->get('description');

    if ($file && $file['error'] === 0) {
        $filename = uniqid() . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
        $path = __DIR__ . '/../../public/assets/img/' . $filename;
        move_uploaded_file($file['tmp_name'], $path);

        $meme = new \App\Models\Meme();
        $meme->title = $filename;
        $meme->category = "";
        $meme->image_path = "/assets/img/$filename";
        $meme->description = $desc;
        $meme->save();

        $client = new \GuzzleHttp\Client([
            'base_uri' => 'https://api.openai.com/v1/',
            'verify'   => false,
            'headers'  => [
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                'Content-Type'  => 'application/json',
            ]
        ]);


        $res = $client->post('embeddings', [
            'json' => [
                'input' => $desc,
                'model' => 'text-embedding-3-small'
            ]
        ]);

        $embedding = json_decode($res->getBody(), true)['data'][0]['embedding'];
        $meme->embedding = json_encode($embedding);
        $meme->save();

        echo "Uploaded successfully. <a href='/dashboard'>Return to dashboard</a>";
    } else {
        echo "There was an error.";
    }
}]);

app()->post('/memes/creative', 'EmbedsController@generateCreative');
