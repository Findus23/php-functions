<?php

/**
 * Functions
 *
 * @author Lukas Winkler <l.winkler23@me.com>
 * @license MIT https://opensource.org/licenses/MIT
 */
namespace Findus23;

/**
 * For development: dump variable
 *
 * @param mixed $var
 */
function debug($var)
{
    // echo "<pre>";
    var_dump($var);
    // echo "</pre>";
}
/**
 * clean html created by editor
 *
 * * no inline css
 * * no unnecessary tags
 * * remove empty tags (<span></span>)
 * * remove <span> without attributes
 * * add target="_blank" to all links
 *
 * @param string $input
 *          input-HTML
 * @param string $linkify
 *          auto-link URLs
 * @return string clean HTML
 */
function clean_html($input, $linkify = true)
{
    $config = HTMLPurifier_Config::createDefault();
    $config->set('Cache.DefinitionImpl', null);
    $config->set('HTML.ForbiddenAttributes', 'style,class,size');
    $config->set('HTML.ForbiddenElements', 'font,div');
    $config->set('AutoFormat.RemoveEmpty', true);
    $config->set('AutoFormat.Linkify', $linkify);
    $config->set('AutoFormat.RemoveSpansWithoutAttributes', true);
    $config->set('HTML.TargetBlank', true);
    
    $config->set('Output.TidyFormat', true); // doesn't work without tidy
    
    $purifier = new HTMLPurifier($config);
    $clean_html = $purifier->purify($input);
    return $clean_html;
}
/**
 * shorten text for preview
 *
 * @param string $text
 *          input text
 * @param integer $lenght
 * @param integer $tolerance
 *          how much additional lenght is acceptable
 * @return string shortend string
 */
function text_preview($text, $lenght, $tolerance)
{
    $text = str_replace("<p", " <p", $text);
    $text = str_replace("<div", " <div", $text);
    $text = html2text($text); // remove HTML
    $text = substr($text, 0, $lenght + $tolerance + 20); // shorten to improve runtime
    if (strlen($text) < $lenght) { // if the text is to short, directly export it.
        return $text;
    }
    $separators = array ( // split at first at sentences
            ".",
            ",",
            " "
    );
    foreach ($separators as $separator) {
        $array = explode($separator, $text);
        $preview = "";
        $j = 0;
        do { // add parts (sentences/words) until it is longer as $length
            $preview .= $array[$j] . $separator;
            $j += 1;
        } while (strlen($preview) < $lenght);
        if (strlen($preview) < $lenght + $tolerance) {
            // If the length is within the tolerance range, exit.
            // If not try again with a different separator.
            return $preview;
        }
    }
    return substr($text, 0, $lenght); // if it doesn't work, make a hard cut
}
/**
 * generate slug from title
 *
 * @author Maerlyn
 * @link http://stackoverflow.com/a/2955878
 * @param $text input
 *          text
 * @return string slug
 */
function slugify($text)
{
    // replace non letter or digits by -
    $text = preg_replace('~[^\\pL\d]+~u', '-', $text);
    
    // trim
    $text = trim($text, '-');
    
    // transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    
    // lowercase
    $text = strtolower($text);
    
    // remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);
    
    if (empty($text)) {
        return 'n-a';
    }
    
    return $text;
}
/**
 * echo a bootstrap alert
 *
 * @param string $type
 *          {success|info|waning|danger}
 * @param string $message
 *
 */
function alert($type, $message)
{
    echo "<div class='alert alert-$type' role='alert'>$message</div>"; // TODO: -> return
}
/**
 * convert HTML-Code to text
 *
 * @param string $input
 * @param boolean $admin
 * @return string text
 */
function html2text($input, $admin = true)
{
    if ($admin) {
        $path = "../";
    } else {
        $path = "";
    }
    // require_once $path . "include/libs/html2text/src/Html2Text.php";
    if (! empty($input)) {
        // https://github.com/soundasleep/html2text/issues/6
        $input = mb_convert_encoding($input, 'HTML-ENTITIES', 'UTF-8');
        $text = Html2Text\Html2Text::convert($input);
        return $text;
    } else {
        return "";
    }
}
/**
 * Generate Date from Timestamp
 *
 * @param integer $time
 *          timestamp
 * @param string $lang
 * @return string 20. Jänner 2014
 */
function format_date($timestamp, $lang)
{
    if ($lang == "de") {
        $day = strftime("%d", $timestamp)*1; //without leading 0
        $month_number = strftime("%m", $timestamp);
        $year = strftime("%Y", $timestamp);
        $months = array (
                "Jänner",
                "Februar",
                "März",
                "April",
                "Mai",
                "Juni",
                "Juli",
                "August",
                "September",
                "Oktober",
                "November",
                "Dezember"
        );
        $month = $months[$month_number - 1];
        return "$day. $month $year";
    } else {
        return strftime("%d. %B %Y", $timestamp);
    }
}
