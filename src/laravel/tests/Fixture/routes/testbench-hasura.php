<?php
use Illuminate\Support\Facades\Route;

Route::post('/hasura_table_event', 'Hasura\EventDispatcher\TableEventRequestHandler@handle');
Route::post('/hasura_auth_hook', 'Hasura\AuthHook\RequestHandler@handle');