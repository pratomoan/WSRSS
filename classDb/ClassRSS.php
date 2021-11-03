<?php

class ClassRSS {

    public function GetRss($rssurl) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $rrsurl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        foreach ($xml['channel']['item'] as $response) {
            $newsheadline = $response['title'];
            $newsurl = $response['link'];
            $this->updaterss($newsheadline, $newsurl);
        }
        $msg = 'Get RSS Done';  
        return $response;
    }

    public function updaterss($newsheadline, $newsurl) {
        include 'Connection.php';
        $sql = "INSERT INTO news_tbl (newsheadline,newsurl) VALUES (??)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $newsheadline, $newsurl);
        $query = $stmt->execute();
        $stmt->close();
        $conn->close();
        $msg = 'Update RSS To DB Done';
        return $query;
    }

    public function readrss() {
        include 'Connection.php';
        $sql = "SELECT * FROM news_tbl";
        $query = $conn->query($sql);
        $conn->close();
        $msg = 'Read RSS Done';
        return $query;
    }

}

?>