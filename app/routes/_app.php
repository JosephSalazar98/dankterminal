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

app()->get('/login', ['middleware' => 'auth.guest', 'AuthController@showLoginForm']);

app()->post('/login', 'AuthController@login');

app()->get('/logout', 'AuthController@logout');

app()->get('/dashboard', ['middleware' => 'auth.required', 'DashboardController@show']);

app()->post('/upload', ['middleware' => 'auth.required', 'EmbedsController@uploadMeme']);

app()->post('/memes/creative', 'EmbedsController@generateCreative');

app()->get('/manage-memes', ['middleware' => 'auth.required', 'DashboardController@manage']);
app()->post('/memes/update-description', ['middleware' => 'auth.required', 'DashboardController@updateDescription']);
app()->post('/memes/delete', ['middleware' => 'auth.required', 'DashboardController@delete']);
