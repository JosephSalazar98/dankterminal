<?php




app()->view('/', 'index');

app()->post('/memes/load-array', 'EmbedsController@loadFromArray');

app()->post('/memes/generate-embeddings', 'EmbedsController@generateEmbeddings');

app()->post('/memes/find-match', 'EmbedsController@matchPrompt');

app()->post('/memes/generate', 'EmbedsController@generateFinalMeme');

app()->post('/telegram/webhook', 'TelegramsController@webhook');
