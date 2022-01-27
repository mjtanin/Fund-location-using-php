// Find User Ip
function get_the_user_ip()
{

    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {

        //check ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {

        //to check ip is pass from proxy

        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {

        $ip = $_SERVER['REMOTE_ADDR'];
    }

    return apply_filters('wpb_get_ip', $ip);
}

// ip to location
function IPtoLocation($ip_)
{
    $apiURL = 'https://freegeoip.app/json/' . $ip_;

    // Make HTTP GET request using cURL 
    $ch = curl_init($apiURL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $apiResponse = curl_exec($ch);
    if ($apiResponse === FALSE) {
        $msg = curl_error($ch);
        curl_close($ch);
        return false;
    }
    curl_close($ch);

    // Retrieve IP data from API response 
    $ipData = json_decode($apiResponse, true);

    // Return geolocation data 
    return !empty($ipData) ? $ipData : false;
}




function display_ip_address()
{
    $userIP = get_the_user_ip();
    $ip_add = IPtoLocation($userIP);
    return $ip_add["country_name"];
}
add_shortcode('display_ip', 'display_ip_address');
