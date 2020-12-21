<?php

namespace YouTube;

// TODO: rename FormatParser or ITagParser
class Parser
{
    public function downloadFormats()
    {
        $data = file_get_contents("https://raw.githubusercontent.com/ytdl-org/youtube-dl/master/youtube_dl/extractor/youtube.py");

        // https://github.com/ytdl-org/youtube-dl/blob/master/youtube_dl/extractor/youtube.py#L429
        if (preg_match('/_formats = ({(.*?)})\s*_/s', $data, $matches)) {

            $json = $matches[1];

            // only "double" quotes are valid in JSON
            $json = str_replace("'", "\"", $json);

            // remove comments
            $json = preg_replace('/\s*#(.*)/', '', $json);

            // remove comma from last JSON item
            $json = preg_replace('/,\s*}/', '}', $json);

            return json_decode($json, true);
        }

        return array();
    }

    public function transformFormats($formats)
    {
        $results = [];

        foreach ($formats as $itag => $format) {

            $temp = [];

            if (!empty($format['ext'])) {
                $temp[] = $format['ext'];
            }

            if (!empty($format['vcodec'])) {
                $temp[] = 'video';
            }

            if (!empty($format['height'])) {
                $temp[] = $format['height'] . 'p';
            }

            if (!empty($format['acodec']) && $format['acodec'] !== 'none') {
                $temp[] = 'audio';
            }

            $results[$itag] = implode(', ', $temp);
        }

        return $results;
    }

    public function parseItagInfo($itag)
    {
        if (isset($this->itag_detailed[$itag])) {
            return $this->itag_detailed[$itag];
        }

        return null;
    }

    public function parseItagType($itag)
    {
        if (isset($this->itag_type[$itag])) {
            return $this->itag_type[$itag];
        }

        return "";
    }

    public function parseItagQuality($itag)
    {
        if (isset($this->itag_quality[$itag])) {
            return $this->itag_quality[$itag];
        }

        return "";
    }

    // itag info does not change frequently, that is why we cache it here as a plain static array
    private $itag_detailed = array(
        18 => 'mp4',
        22 => 'mp4',
        37 => 'mp4',
        38 => 'mp4',
        43 => 'webm',
        44 => 'webm',
        45 => 'webm',
        46 => 'webm',
        59 => 'mp4',
        78 => 'mp4',
        82 => 'mp4',
        83 => 'mp4',
        84 => 'mp4',
        85 => 'mp4',
        100 => 'webm',
        101 => 'webm',
        102 => 'webm',
        91 => 'mp4',
        92 => 'mp4',
        93 => 'mp4',
        94 => 'mp4',
        95 => 'mp4',
        96 => 'mp4',
        132 => 'mp4',
        133 => 'mp4',
        134 => 'mp4',
        135 => 'mp4',
        136 => 'mp4',
        137 => 'mp4',
        138 => 'mp4',
        160 => 'mp4',
        212 => 'mp4',
        264 => 'mp4',
        298 => 'mp4',
        299 => 'mp4',
        266 => 'mp4',
        139 => 'm4a',
        140 => 'm4a',
        141 => 'm4a',
        256 => 'm4a',
        258 => 'm4a',
        325 => 'm4a',
        328 => 'm4a',
        167 => 'webm',
        168 => 'webm',
        169 => 'webm',
        170 => 'webm',
        218 => 'webm',
        219 => 'webm',
        278 => 'webm',
        242 => 'webm',
        243 => 'webm',
        244 => 'webm',
        245 => 'webm',
        246 => 'webm',
        247 => 'webm',
        248 => 'webm',
        271 => 'webm',
        272 => 'webm',
        302 => 'webm',
        303 => 'webm',
        308 => 'webm',
        313 => 'webm',
        315 => 'webm',
        171 => 'webm',
        172 => 'webm',
        249 => 'webm',
        250 => 'webm',
        251 => 'webm',
    );

    private $itag_type = array(
        18 => 'video',
        22 => 'video',
        37 => 'video',
        38 => 'video',
        43 => 'video',
        44 => 'video',
        45 => 'video',
        46 => 'video',
        59 => 'video',
        78 => 'video',
        82 => 'video',
        83 => 'video',
        84 => 'video',
        85 => 'video',
        100 => 'video',
        101 => 'video',
        102 => 'video',
        91 => 'video',
        92 => 'video',
        93 => 'video',
        94 => 'video',
        95 => 'video',
        96 => 'video',
        132 => 'video',
        133 => 'video',
        134 => 'video',
        135 => 'video',
        136 => 'video',
        137 => 'video',
        138 => 'video',
        160 => 'video',
        212 => 'video',
        264 => 'video',
        298 => 'video',
        299 => 'video',
        266 => 'video',
        139 => 'audio',
        140 => 'audio',
        141 => 'audio',
        256 => 'audio',
        258 => 'audio',
        325 => 'audio',
        328 => 'audio',
        167 => 'video',
        168 => 'video',
        169 => 'video',
        170 => 'video',
        218 => 'video',
        219 => 'video',
        278 => 'video',
        242 => 'video',
        243 => 'video',
        244 => 'video',
        245 => 'video',
        246 => 'video',
        247 => 'video',
        248 => 'video',
        271 => 'video',
        272 => 'video',
        302 => 'video',
        303 => 'video',
        308 => 'video',
        313 => 'video',
        315 => 'video',
        171 => 'audio',
        172 => 'audio',
        249 => 'audio',
        250 => 'audio',
        251 => 'audio',
    );

    private $itag_quality = array(
        18 => '360',
        22 => '720',
        37 => '1080',
        38 => '3072',
        43 => '360',
        44 => '480',
        45 => '720',
        46 => '1080',
        59 => '480',
        78 => '480',
        82 => '360',
        83 => '480',
        84 => '720',
        85 => '1080',
        100 => '360',
        101 => '480',
        102 => '720',
        91 => '144',
        92 => '240',
        93 => '360',
        94 => '480',
        95 => '720',
        96 => '1080',
        132 => '240',
        133 => '240',
        134 => '360',
        135 => '480',
        136 => '720',
        137 => '1080',
        138 => '',
        160 => '144',
        212 => '480',
        264 => '1440',
        298 => '720',
        299 => '1080',
        266 => '2160',
        139 => '',
        140 => '',
        141 => '',
        256 => '',
        258 => '',
        325 => '',
        328 => '',
        167 => '360',
        168 => '480',
        169 => '720',
        170 => '1080',
        218 => '480',
        219 => '480',
        278 => '144',
        242 => '240',
        243 => '360',
        244 => '480',
        245 => '480',
        246 => '480',
        247 => '720',
        248 => '1080',
        271 => '1440',
        272 => '2160',
        302 => '720',
        303 => '1080',
        308 => '1440',
        313 => '2160',
        315 => '2160',
        171 => '',
        172 => '',
        249 => '',
        250 => '',
        251 => '',
    );
}