<?php
/*
 *  @package j8ahmed-test-plugin-1
 */

namespace J8ahmed\TestPlugin1\Controllers\YouTube;

class Main {

    private static $yt_videos;

    /*
     * Initialize YouTube Features using stored API key
     * @return null
     */
    public static function init() {
        $dotenv = \Dotenv\Dotenv::createImmutable(PLUGIN_DIR);
        $dotenv->load();

        define("YOUTUBE_API_KEY", $_ENV["YOUTUBE_API_KEY"]);
        define("YOUTUBE_API_BASE_URL", "https://www.googleapis.com/youtube/v3/playlistItems?");

        // add styles and JS scripts
        self::register_scripts();

        // Get initial YouTube playlist from Google API
        // self::get_youtube_playlist();

        // Use sample data while testing instead of the live Google API
        $sample_data = file_get_contents(__DIR__ . "/sample_youtube_channel_videos_api_results.json");
        $json_decoded = json_decode($sample_data);
        self::$yt_videos = $json_decoded->items;

        // Add shortcodes
        add_shortcode( "j8-youtube-playlist", [self::class, "get_video_list"]);
    }

    /*
     * Get YouTube video list content
     * @return string
     */
    public static function get_video_list() {
        // sample YouTube video URL = https://www.youtube.com/watch?v=9jzVJKGx7L8
        // v=[video->resourceId->videoId]
        $video_items = self::$yt_videos;

        $code = "";
        $code .= "<ul class='j8-youtube-playlist-container'>";

        foreach($video_items as $video){
            $url = $video->snippet->thumbnails->standard->url;
            $title = $video->snippet->title;
            $videoId = $video->snippet->resourceId->videoId;

            $code .= "<li class='playlist-item'>";

            $code .= "<a href='https://www.youtube.com/watch?v=" . $videoId . "' target='_blank' rel='noreferrer noopener'>";
            $code .= "<div class='img-container'>";
            $code .= "<img class='' src='" . $url . "' alt='" . $title . "'/>";
            $code .= "</div>";

            $code .= "<div class='text-container'>";
            $code .= "<h3>" . $title . "</h3>";
            $code .= "</div>";

            $code .= "</a>";
            $code .= "</li>";
        }

        $code .= "</ul>";

        return $code;
    }

    /*
     * Get YouTube data using stored API key
     * @return null
     */
    public static function get_youtube_playlist() {
        // default playlistid is for https://www.youtube.com/@selftaughtdev
        $params = [
            "api_key"        => join("=", ["key", YOUTUBE_API_KEY]),
            "yt_max_results" => join("=", ["maxResults", "6"]),
            "yt_part"        => join("=", ["part", "snippet,status"]),
            "yt_playlist_id" => join("=", ["playlistId", "UUam3QW0KOgIbWtlfFz2z83w"]),
        ];

        $api_endpoint = YOUTUBE_API_BASE_URL . join("&", $params);

        $api_result = wp_remote_get(
            $api_endpoint,
            array(
                'timeout' => 30,
                'headers' => array('referer' => site_url())
            )
        );

        // End process if API request returns an error
        is_wp_error($api_result) && die("error in api request");

        $json_result = json_decode($api_result["body"]);

        // End process if JSON decoded API request body has an error
        isset($json_result->error) && die("error in api request body");

        // Store JSON decoded video list
        self::$yt_videos = $json_result->items;
    }

    public static function enqueue_scripts(){
        wp_enqueue_style("j8_youtube_styles", plugins_url("assets/styles/youtube/j8_youtube.css", PLUGIN_FILE));
        //wp_enqueue_script("j8_plugin_test_scripts", plugins_url("assets/js/test_script.js", PLUGIN_FILE));
    }

    private static function register_scripts(){
        add_action("wp_enqueue_scripts", [self::class, "enqueue_scripts"]);
    }

}


