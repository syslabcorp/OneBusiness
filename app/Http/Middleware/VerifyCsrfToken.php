<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'banks/get-banks-list',
        'banks/get-branches',
        '/satellite-branch/get-branch-list',
        'checkbooks/get-checkbooks',
        'inventory/get-inventory-list'
    ];
}
