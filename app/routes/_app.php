<?php

app()->get('/', 'DashboardController@show');

app()->post('/memes/generate', 'EmbedsController@generateFinalMeme');

app()->post('/telegram/webhook', 'TelegramsController@webhook');

app()->get('/login', ['middleware' => 'auth.guest', 'AuthController@showLoginForm']);

app()->post('/login', 'AuthController@login');

app()->get('/logout', 'AuthController@logout');

app()->get('/dashboard', ['middleware' => 'auth.required', 'DashboardController@upload']);

app()->post('/upload', ['middleware' => 'auth.required', 'EmbedsController@uploadMeme']);

app()->post('/memes/creative', 'EmbedsController@generateCreative');

app()->get('/manage-memes', ['middleware' => 'auth.required', 'DashboardController@manage']);
app()->post('/memes/update-description', ['middleware' => 'auth.required', 'DashboardController@updateDescription']);
app()->post('/memes/delete', ['middleware' => 'auth.required', 'DashboardController@delete']);

app()->get('/twitter/login',    'TwitterAuthController@login');
app()->get('/twitter/callback', 'TwitterAuthController@callback');

app()->get('/cron', 'EmbedsController@generateFromRandomCron');
