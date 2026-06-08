<?php

include_once plugin_dir_path( __FILE__ ) . 'fetch_bookmarker.php';

class Fetch_Subclass extends Fetch_Bookmarker
{
    public function __construct($url)
    {
        parent::__construct($url);
        $this->response_sent = $this->fetch();

        echo $this->response_sent;
        die();

        if(!empty($this->response_sent))
        {
            $this->parsed_response = $this->parse( $this->response_sent );
        } else {
            echo "response_sent_null";
            return false;
        }
    }

    public function return() {
        return $this->parsed_response;
    }

    public function fetch()
    {
        $command = 'curl -L -H "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/136.0.0.0 Safari/537.36" ' . $this->bookmarker_url;

        $html = shell_exec($command);

        return $html;
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

        $output = [];
        foreach ( $nodes as $node ) {
            $output[] = str_replace('\\n', '', str_replace('\\r', '', str_replace('\\r\\n', '', trim( $node->textContent ))));
        }

        return $output;
    }
}

$fetch = new Fetch_Subclass('https://oddspedia.com');
$parsed_output = $fetch->return();

echo "<pre>";
print_r($parsed_output);
die();