<?php
use App\Core\Router;
use App\Core\CSRF;

function url(string $path): string { return Router::url($path); }
function csrf_field(): string { return '<input type="hidden" name="csrf" value="'.htmlspecialchars(CSRF::token(),ENT_QUOTES).'">'; }
function h(string $s): string { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
function flash_set(string $k,string $v){ $_SESSION['flash'][$k]=$v; }
function flash_get(string $k){ $v=$_SESSION['flash'][$k]??null; unset($_SESSION['flash'][$k]); return $v; }
