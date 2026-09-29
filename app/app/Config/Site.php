<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

/**
 * Settings of the site. Can be changed in .env, e.g.: site.contactEmail = contact@gsgosselies.be
 */
class Site extends BaseConfig
{
    /**
     * Address receiving the messages of the contact form (and the registration requests when no administrator
     * of the unit exists)
     */
    public string $contactEmail = 'contact@gsgosselies.be';
}
