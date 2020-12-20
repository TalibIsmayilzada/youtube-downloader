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

    // itag info does not change frequently, that is why we cache it here as a plain static array
    private $itag_detailed = array(
        18 => 'mp4, video, 360p, audio',
        22 => 'mp4, video, 720p, audio',
        37 => 'mp4, video, 1080p, audio',
        38 => 'mp4, video, 3072p, audio',
        59 => 'mp4, video, 480p, audio',
        78 => 'mp4, video, 480p, audio',
        82 => 'mp4, video, 360p, audio',
        83 => 'mp4, video, 480p, audio',
        84 => 'mp4, video, 720p, audio',
        85 => 'mp4, video, 1080p, audio',
        91 => 'mp4, video, 144p, audio',
        92 => 'mp4, video, 240p, audio',
        93 => 'mp4, video, 360p, audio',
        94 => 'mp4, video, 480p, audio',
        95 => 'mp4, video, 720p, audio',
        96 => 'mp4, video, 1080p, audio',
        132 => 'mp4, video, 240p, audio',
        151 => 'mp4, video, 72p, audio',
        133 => 'mp4, video, 240p',
        134 => 'mp4, video, 360p',
        135 => 'mp4, video, 480p',
        136 => 'mp4, video, 720p',
        137 => 'mp4, video, 1080p',
        138 => 'mp4, video',
        160 => 'mp4, video, 144p',
        212 => 'mp4, video, 480p',
        264 => 'mp4, video, 1440p',
        298 => 'mp4, video, 720p',
        299 => 'mp4, video, 1080p',
        266 => 'mp4, video, 2160p',
        394 => 'video',
        395 => 'video',
        396 => 'video',
        397 => 'video',
    );
}