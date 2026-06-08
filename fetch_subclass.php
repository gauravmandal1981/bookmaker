<?php

include_once plugin_dir_path( __FILE__ ) . 'fetch_bookmarker.php';

class Fetch_Subclass extends Fetch_Bookmarker
{
    public function __construct($url)
    {
        parent::__construct($url);
        $this->response_sent = $this->fetch();

        if(!empty($this->response_sent))
        {
            $this->parsed_response = $this->parse( $this->response_sent );
        } else {
            echo "response_sent_null";
        }

        if(!empty($this->parsed_response))
        {
            echo $this->parsed_response;
            die();
        }
    }

    public function fetch() 
    {

        $response = wp_remote_get(
            $this->bookmarker_url,
            array(
                'timeout' => 60,
                'user-agent' => 'Mozilla/5.0',
                'headers' => [
                    'Cookie' => 'cookie_consent=true'
                ]
            )
        );

        if ( is_wp_error( $response ) ) {
            return false;
        }

        print_r(wp_remote_retrieve_body( $response ));
        die();

        return wp_remote_retrieve_body( $response );
    }

    public function parse( $html ) {
        $dom = new DOMDocument();

        libxml_use_internal_errors( true );
        $dom->loadHTML( $html );
        libxml_clear_errors();

        $xpath = new DOMXPath( $dom );

        $nodes = $xpath->query(
            "//div[contains(@class,'matchup-odds-comparison-card')]"
        );

        $output = "";
        foreach ( $nodes as $node ) {
            $output .= trim( $node->textContent );
        }

        return $output;
    }
}

$fetch = new Fetch_Subclass('https://oddsportal.com/football/h2h/athletico-pr-UoAxb1Tq/sao-paulo-QgP0oAUH/#0EJZvDKP:1X2;4');