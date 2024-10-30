<?php
define("BASE_API_URL",'https://www.couponsindemand.com/api/v1/index.cfm/coupons?subdomain=');

add_filter('query_vars', 'query_vars');
function query_vars($public_query_vars) {
	$public_query_vars[] = "id";
	return $public_query_vars;
}

function array_sort_by_column_asc(&$array, $column, $direction = SORT_ASC) {
    $reference_array = array();
    foreach($array as $key => $row) {
        $reference_array[$key] = $row[$column];
    }
    array_multisort($reference_array, $direction, $array);
}

function array_sort_by_column_desc(&$array, $column, $direction = SORT_DESC) {
    $reference_array = array();
    foreach($array as $key => $row) {
        $reference_array[$key] = $row[$column];
    }
    array_multisort($reference_array, $direction, $array);
}

function Remove_QS_Key($url, $key) {
	$url = preg_replace('/(?:&|(\?))'.$key.'=[^&]*(?(1)&|)?/i', "$1", $url);
	return $url;
}

function load_scripts_and_styles()
{
	wp_enqueue_script('jquery');
}
add_action('wp_enqueue_scripts', 'load_scripts_and_styles');

function get_couponlist( $atts ) {
?>

<?php ob_start(); ?>
	<div id="fb-root"> </div>
	<script>
		(function(d, s, id){
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) 
			return;
			js = d.createElement(s);
			js.id = id;
			js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=489652497789023";
			fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));
	</script>
	<?php
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		$a = shortcode_atts( array(
			'group' => '',
			'client' => '',
			'subcategory' => '',
			'category' => '',
			'couponid' => 0,
			'page' => $page,
			'limit' => 20,
			'city' => '',
			'state' => '',
			'startdate' => '',
			'enddate' => ''
		), $atts );
		
		$api_data=get_data();
		$location = $api_data -> locations;
		$file_type = $api_data -> file_type;
		$conf = $api_data -> configuration_setting;
		$stretch = $api_data -> stretch;
		$cell_width = $api_data -> cell_width;
		$social_sharing = $api_data -> social;
		$show_company_name = $api_data -> companyname;
		$subdomain=$api_data->subdomain;
		$sort_by=get_sort_value($api_data->display_order);
		$url = BASE_API_URL.$subdomain;
		
		$uri = $_SERVER['REQUEST_URI'];
		$uri = trim($uri, '/');
		$array = explode('/',$uri);
		$thisCouponID = end($array);
		
		if( $a['group'] != "") {
			$url = $url.'&group='.urlencode($a['group']);
		}
		if( $a['client'] != "") {
			$url = $url.'&client='.urlencode($a['client']);
		}
		if( $a['subcategory'] != "") {
			$url = $url.'&subcategory='.urlencode($a['subcategory']);
		}
		if( $a['category'] != "") {
			$url = $url.'&category='.urlencode($a['category']);
		}
		if(is_numeric($thisCouponID)){
			$url = $url.'&couponid='.$thisCouponID;
		}
		else if( $a['couponid'] != 0) {
			$url = $url.'&couponid='.$a['couponid'];
		}
		if( $a['page'] != 1) {
			$url = $url.'&page='.$a['page'];
		}
		if( $a['limit'] != 20) {
			$url = $url.'&limit='.$a['limit'];
		}
		if( $a['city'] != "") {
			$url = $url.'&city='.urlencode($a['city']);
		}
		if( $a['state'] != "") {
			$url = $url.'&state='.urlencode($a['state']);
		}
		if( $a['startdate'] != "") {
			$url = $url.'&startdate='.urlencode($a['startdate']);
		}
		if( $a['enddate'] != "") {
			$url = $url.'&enddate='.urlencode($a['enddate']);
		}
		$thisURL = Remove_QS_Key(basename($_SERVER['REQUEST_URI']), "page");
		$json=get_url_data($url);
		
		if($conf == "teaser") {
			wp_enqueue_style('coupon-teaser', plugins_url('css/cid-teaser.css', __FILE__));
		} else {		
			wp_enqueue_style('coupon-no-map', plugins_url('css/cid-no-map.css', __FILE__));
		}
		
		wp_enqueue_script('cid-isotope', plugins_url('js/isotope.pkgd.min.js', __FILE__));
		wp_enqueue_script('cid-scripts', plugins_url('js/scripts.js', __FILE__));
			
		$uri = $_SERVER['REQUEST_URI'];
		$uri_split = explode("/",$uri);
		$uri_split = $uri_split[1];
		
		$phoneImg = plugins_url( 'images/phone.gif' , __FILE__ );
		$powerImg = plugins_url( 'images/PowerLogo.png' , __FILE__ );
		$facebookImg = plugins_url( 'images/facebook.gif' , __FILE__ );
		$twitterImg = plugins_url( 'images/twitter.gif' , __FILE__ );
		$gplusImg = plugins_url( 'images/gplus.png' , __FILE__ );
		$linkedinImg = plugins_url( 'images/linkedin.png' , __FILE__ );
		$mapImg = plugins_url( 'images/map.png' , __FILE__ );
		$missingImg = plugins_url( 'images/MissingImage.png' , __FILE__ );
		
		$mySubCatSeoList = "";
		$mySubCatist = "";
		
		if($json){
			$coupon_count = count($json);
			for($i=0; $i<$coupon_count; $i++){
				$mySubCatSeoList = $mySubCatSeoList . ',' . $json[$i]['SUBCATSEOLIST'];
				$mySubCatList = $mySubCatList . ',' . $json[$i]['SUBCATLIST'];
			}
		}
		$arrSeoList = explode(",",$mySubCatSeoList);
		sort($arrSeoList);
		$uniqueSeoList = array_unique ( $arrSeoList );
		$mySubCatSeoList = implode(",",$uniqueSeoList);						
		$arrList = explode(",",$mySubCatList);
		sort($arrList);
		$uniqueList = array_unique ( $arrList );
		$mySubCatList = implode(",",$uniqueList);
		unset($uniqueList[0]) ;
		unset($uniqueSeoList["0"]);
	?>
	<?php
		$useragent=$_SERVER['HTTP_USER_AGENT'];
		$isMobile=0;
		if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
		$isMobile=1;
		?>
	<?php if(count($uniqueSeoList)) {?>
	<div id="options" align="center">
		<div class="option-set" data-isotope-key="filter" style="display: inline-block;">
			<button class="subcat-nav-button" data-isotope-value="*">Show All</button>
			<?php foreach($uniqueSeoList as $value) //loop over values
				{
					//echo $value;// . PHP_EOL; //print value
					$idx = array_search($value, $uniqueSeoList);
			?>
			<!--<a href="#" class="subcat-nav-button" data-isotope-value=".<?php echo $value;?>"><?php echo $uniqueList[$idx]; ?></a> -->
			<button class="subcat-nav-button" data-isotope-value=".<?php echo $value;?>"><?php echo $uniqueList[$idx]; ?></button>
			<?php
			}?><BR /><BR />
		</div>
		<!--<h2>Sort</h2>
		<div class="option-set" data-isotope-key="sortBy">
			<button data-isotope-value="cidconame">name</button>
			<button data-isotope-value="ending">ending</button>
			<button data-isotope-value="new">new</button>
		</div> -->
	</div>
	<?php }?>
	
	<!--INCLUDE COUPON LIST -->
	<?php
		if($conf == "teaser") {
			require 'list_isotope.php';
		} else {
			require 'list_single_column.php';
		}
	?>
	
	<input type="hidden" name="subdomain_id" value="<?php echo $subdomain?>" id="subdomain_id"/>
	
	<?php
	$content=ob_get_clean();
	return $content;
	?>					
<?php }


if(isset($_REQUEST['action']) && $_REQUEST['action']=='my_action') {
	my_action_callback();
}

function my_action_callback() {
	$start[]=$_REQUEST['lat1'];
	$start[]=$_REQUEST['lon1'];
	$finish[]=$_REQUEST['lat2'];
	$finish[]=$_REQUEST['lon2'];
	echo $distance = Haversine($start, $finish).' miles away';
	die(); // this is required to return a proper result
}
add_action('wp_ajax_my_action', 'my_action_callback');

function get_url_data($url){
    $args = array(
    'timeout'     => 20,
    'redirection' => 20,
    'httpversion' => '1.0',
    'user-agent'  => 'WordPress/' . $wp_version . '; ' . get_bloginfo( 'url' ),
    'blocking'    => true,
    'headers'     => array(),
    'cookies'     => array(),
    'body'        => null,
    'compress'    => false,
    'decompress'  => true,
    'sslverify'   => false,
    'stream'      => false,
    'filename'    => null
  );
  $response = wp_remote_get( $url, $args );
   if(is_array($response) && array_key_exists('body', $response))
	{
		$result = preg_replace('/.+?({.+}).+/','$1',$response['body']);
		$result="[".$result."]";
		$data = json_decode($result, true);
		return $data;
	}
	return FALSE;
}

function get_sort_value($val) {
	switch($val){
		case 'Newest':
			$sort='recent';
		break;
		case 'Popular':
			$sort='popular';
		break;
		case 'Expire Soon':
			$sort='expiring';
		break;
	}
	return $sort;
}

function get_data(){
	global $wpdb;
	$id=1;
	$data=$wpdb->get_row($wpdb->prepare("SELECT * FROM ".$wpdb->prefix . "cid_configurationV2 WHERE id = %d", $id));
	return $data;
}

function Haversine($start, $finish) {
	$theta = $start[1] - $finish[1]; 
	$distance = (sin(@deg2rad($start[0])) * sin(@deg2rad($finish[0]))) + (cos(@deg2rad($start[0])) * cos(@deg2rad($finish[0])) * cos(@deg2rad($theta))); 
	$distance = acos($distance); 
	$distance = rad2deg($distance); 
	$distance = $distance * 60 * 1.1515; 
	return round($distance, 2);
}

if (!is_admin()) {
	add_shortcode('CouponsInDemand', 'get_couponlist');
}?>