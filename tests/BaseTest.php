<?php

namespace Appstract\LushHttp\Test;

use Appstract\LushHttp\Response\LushResponse;

abstract class BaseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Run all the checks.
     *
     * @param LushResponse $response
     * @param array        $options
     */
    protected function checkAll(LushResponse $response, array $options)
    {
        $this->checkHeaders($response);
        $this->checkContent($response, isset($options['content_type']) ? $options['content_type'] : null);
    }

    /**
     * Check headers.
     *
     * @param LushResponse $response
     */
    protected function checkHeaders(LushResponse $response)
    {
        // get headers should return array
        $this->assertInternalType('array', $response->getHeaders());

        // check all individual headers
        foreach ($this->headers as $header) {
            $this->assertNotNull($response->getHeader($header));
        }

        // not existing header should return null
        $this->assertNull($response->getHeader('not_existing'));

        // shorthand checks
        $this->assertNotNull($response->getStatusCode());
        $this->assertNotNull($response->getContentType());
    }

    /**
     * Check content.
     *
     * @param LushResponse $response
     * @param null         $type
     */
    protected function checkContent(LushResponse $response, $type = null)
    {
        // we should have content
        $this->assertNotNull($response->getResult());

        // check if content is of specified type
        if ($type) {
            $fn = 'is'.ucfirst($type);
            $this->assertTrue($response->{ $fn }());

            // magic getter
            $this->assertNotNull($response->url);

            // not magic getter
            $result = $response->getResult();
            $this->assertNotNull($result->url);
        }
    }
}
