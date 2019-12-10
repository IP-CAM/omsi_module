<?php
class BaseLoader {

    protected $curl;

    protected function load($url) {

        ini_set("display_errors", 1);

        $this->curlInit($url);
        $response = curl_exec($this->curl);
        $resArr = json_decode($response, true);

        if ($response === false) $response = curl_error($this->curl);

        curl_close($this->curl);

        return $resArr;
    }

    protected function post($url, $data) {
        $data_string = json_encode($data);
        $this->curlInit($url);
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string))
        );

        $response = curl_exec($this->curl);
        $resArr = json_decode($response, true);

        curl_close($this->curl);

        return $resArr;
    }

    private function curlInit($url) {
        $this->curl = curl_init();

        //curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($this->curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($this->curl, CURLOPT_USERPWD, "admin@maximpkochukov:90be29b5e1");
        curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($this->curl, CURLOPT_TIMEOUT, 50);

        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
    }

    private function curlInitPost($url) {

    }

    public function loadImage($url, $saveTo){
        $this->curlInit($url);
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($this->curl, CURLOPT_HEADER, 0);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->curl, CURLOPT_BINARYTRANSFER,1);
        $raw = curl_exec($this->curl);

        curl_close ($this->curl);
        if(file_exists($saveTo)){
            unlink($saveTo);
        }
        $fp = fopen($saveTo,'x');
        $res = fwrite($fp, $raw);

        fclose($fp);
    }
}
?>