<?php
// endpoint: /youtubei/v1/player
function requestPlayer($videoId)
{
    include("includes/config.inc.php");
    $req_arr = json_encode(
        array(
            'context' =>
            array(
                'client' =>
                array(
                    'hl' => 'en',
                    'clientName' => 'WEB',
                    'clientVersion' => '2.20220420.01.00',
                    'mainAppWebInfo' =>
                    array(
                        'graftUrl' => '/watch?v=' . $videoId,
                    ),
                ),
            ),
            'videoId' => $videoId,
        )
    );
    $ch = curl_init();
    // otherwise unsupported browser
    $ua = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.14; rv:100.0) Gecko/20100101 Firefox/100.0";
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // so that the 1 doesnt show
    curl_setopt($ch, CURLOPT_COOKIEFILE, "cookies.txt");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Authorization: ", //sapisidhash,
        "Content-Type: application/json",
        // "Referer: https://youtube.com/watch?v=" . $videoId,
        "X-Goog-AuthUser: 0",
        "X-Origin: https://www.youtube.com",
    ));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $req_arr);
    curl_setopt($ch, CURLOPT_USERAGENT, $ua);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_URL, "https://www.youtube.com/youtubei/v1/player?key=AIzaSyAO_FJ2SlqU8Q4STEHLGCilw_Y9_11qcW8");

    $result = curl_exec($ch);
    return $result;
}
function requestVideoSrc($videoId)
{
    include("includes/config.inc.php");
    $req_arr = json_encode(
        array(
            'context' =>
            array(
                'client' =>
                array(
                    'hl' => 'en',
                    'clientName' => 'ANDROID',
                    'clientVersion' => '15.12.11',
                    'mainAppWebInfo' =>
                    array(
                        'graftUrl' => '/watch?v=' . $videoId,
                    ),
                ),
            ),
            'videoId' => $videoId,
        )
    );
    $ch = curl_init();
    // otherwise unsupported browser
    $ua = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.14; rv:100.0) Gecko/20100101 Firefox/100.0";
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // so that the 1 doesnt show
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Authorization: ", //sapisidhash,
        "Content-Type: application/json",
        "X-Goog-AuthUser: 0",
        "X-Origin: https://www.youtube.com",
        "X-YouTube-Client-Name: ANDROID",
        "X-YouTube-Client-Version: 15.12.11",
    ));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $req_arr);
    curl_setopt($ch, CURLOPT_USERAGENT, $ua);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_URL, "https://www.youtube.com/youtubei/v1/player?key=AIzaSyAO_FJ2SlqU8Q4STEHLGCilw_Y9_11qcW8");

    $result = curl_exec($ch);
    $mainResponseObject = json_decode($result);
    # check if there's a video src or something or something or SOMETHING
    if (isset($mainResponseObject->streamingData->formats[1]->url)) {
        $videoLink = $mainResponseObject->streamingData->formats[1]->url;
        $videoHtml = sprintf('<video controls class="video-player googlevideo-player" style="width: 427px; height: margin:center;" src="%s"></video>', $videoLink);
        return $videoHtml;
    } else {
        $videoHtml = sprintf('<span class="noVideoError">Video unavailable for playback. <a href="https://youtube.com/watch?v=%s">Watch on YouTube</a></span>', $videoId);
    }
}
