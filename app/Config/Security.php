<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Security extends BaseConfig
{
    /**
     * CSRF Protection Method
     */
    public string $csrfProtection = 'cookie';

    /**
     * CSRF Token Randomization
     */
    public bool $tokenRandomize = false;

    /**
     * CSRF Token Name
     */
    public string $tokenName = 'csrf_test_name';

    /**
     * CSRF Header Name
     */
    public string $headerName = 'X-CSRF-TOKEN';

    /**
     * CSRF Cookie Name
     */
    public string $cookieName = 'csrf_cookie_name';

    /**
     * CSRF Expires
     */
    public int $expires = 7200;

    /**
     * Jangan regenerasi token setiap pengiriman.
     */
    public bool $regenerate = false;

    /**
     * CSRF Redirect
     */
    public bool $redirect = (ENVIRONMENT === 'production');
}