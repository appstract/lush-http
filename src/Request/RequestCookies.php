<?php

namespace Appstract\LushHttp\Request;

use Appstract\LushHttp\Lush;
use Appstract\LushHttp\Exception\LushException;

trait RequestCookies
{

    protected $cookieFile;
    protected $cookieJar;

    /**
     * Use cookie file and cookiejar
     *
     * @param null $cookieFile
     * @param null $cookieJar
     * @return $this
     */
    public function withCookies($cookieFile = null, $cookieJar = null)
    {
        $this->setCookieFile($cookieFile);
        $this->setCookieJar($cookieJar);

        return $this;
    }

    /**
     * Set array of cookies
     *
     * @param array $cookies
     *
     * @return $this
     */
    public function cookies(array $cookies)
    {
        $this->addOption('cookies', http_build_query($cookies, null, '; '));

        return $this;
    }

    /**
     * Clear the cookie jar
     * @todo
     */
    public function clearCookies()
    {

    }

    /**
     * @param $cookieFile
     */
    protected function setCookieFile($cookieFile)
    {
        $this->cookieFile = $cookieFile ? $cookieFile : realpath(__DIR__.'/../../'.Lush::COOKIE_FILE);

        if (!file_exists($this->cookieFile)) {
            file_put_contents($this->cookieFile, '');
        }

        if (!fopen($this->cookieFile, 'r')) {
            throw new LushException(sprintf('Cookie file %s is not readable', $this->cookieFile));
        }

        $this->addOption('cookie_file', $this->cookieFile);
    }

    /**
     * @param $cookieJar
     */
    protected function setCookieJar($cookieJar)
    {
        $this->cookieJar = $cookieJar ? $cookieJar : realpath(__DIR__.'/../../'.Lush::COOKIE_FILE);

        if (!file_exists($this->cookieJar)) {
            file_put_contents($this->cookieJar, '');
        }

        if (!fopen($this->cookieJar, 'w')) {
            throw new LushException(sprintf('Cookie jar %s is not writable', $this->cookieJar));
        }

        $this->addOption('cookiejar', $this->cookieJar);
    }
}
