<?php

abstract class Fetch_Bookmarker
{
    protected $bookmarker_url;
    protected $response_sent;
    protected $parsed_response;

    public function __construct($url)
    {
        $this->bookmarker_url = $url;
    }

    abstract public function fetch();
    abstract public function parse( $html );
}