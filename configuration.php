<?php
add_action('admin_menu', 'options_add_page_fn');

global $wpdb;
$flag=0;

function custom_install() {
	global $wpdb;
	$table_name = $wpdb->prefix . "cid_configurationV2";
	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
		$sql = "CREATE TABLE ".$table_name." (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`created_at` datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		`subdomain` varchar(50) NOT NULL,
		`display_order` varchar(50) NOT NULL,
		`configuration_setting` varchar(50) NOT NULL,
		`file_type` varchar(10) NOT NULL,
		`cell_width` varchar(4) NOT NULL,
		`stretch` varchar(10) NOT NULL,
		`companyname` varchar(5) NOT NULL,
		`locations` varchar(5) NOT NULL,
		`social` varchar(5) NOT NULL,
		PRIMARY KEY (`id`)
		);";
		$wpdb->query($sql);
		$created_at = date('Y-m-d H:i:s');
		$subdomain='agencydemo';
		$order='Popular';
		$filetype='PDF';
		$cell_width='250';
		$stretch='HW';
		$location='YES';
		$companyname='YES';
		$social='YES';
		$conf = "no-map";
		$rows_affected = $wpdb->insert($table_name, array( 'subdomain' => $subdomain, 'display_order' => $order, 'file_type' => $filetype, 'cell_width' => $cell_width, 'stretch' => $stretch, 'companyname' => $companyname, 'locations' => $location, 'social' => $social, 'created_at' => $created_at, 'configuration_setting' => $conf) );
	}
}

function configuration_activate() {
	custom_install();
}

//if any operation needs to take place when deactivating the plug-in, enable this function and code it, by default the operation is to drop the configuration table
function configuration_deactivate() {
	global $wpdb;
	$sql="DROP TABLE ".$wpdb->prefix . "cid_configurationV2";
	$e = $wpdb->query($sql);
}

if($_POST['submit_config']){
	$created_at = date('Y-m-d H:i:s');
	//this file_param setting is temporary, if the file_param needs to be changed from the UI, just remove this line, so that the value that is selected in the configuration table will be updated accordingly
	//$_POST['file_param']="PDF";
	$_POST['display_order']="Newest";
	$wpdb->query("UPDATE ".$wpdb->prefix . "cid_configurationV2 SET subdomain = '".$_POST['subdomain']."',display_order='".$_POST['display_order']."',file_type='".$_POST['file_param']."',cell_width='".$_POST['cell_width']."',stretch='".$_POST['stretch_param']."',companyname='".$_POST['companyname_param']."',locations='".$_POST['location_param']."',social='".$_POST['social_param']."',created_at='".$created_at."',configuration_setting='".$_POST['conf_param']."' WHERE id = 1");
	$flag=1;
}

// Add sub page to the Settings Menu
function options_add_page_fn() {
	add_options_page('Configuration Page', 'Coupon Configuration', 'administrator', __FILE__, 'options_page_fn');
}

// Display the admin options page
function options_page_fn() {
	global $wpdb,$flag;
	$id=1;
	$post_data=$wpdb->get_row($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."cid_configurationV2 WHERE id = %d", $id));
	/*
	if($post_data->configuration_setting=='Side-By-Side') {
		wp_enqueue_style('coupon-side-by-side-style', plugins_url('css/cid-percentage.css', __FILE__));
	} else {
		wp_enqueue_style('coupon-stacked-style', plugins_url('css/cid-responsive.css', __FILE__));
	}*/
	wp_enqueue_style('coupon-teaser', plugins_url('css/cid-teaser.css', __FILE__));
	?>
	<div class="CID-wrap">
		<?php if($flag==1){?>
			<div id="setting-error-settings_updated" class="updated settings-error">
				<p>
					<strong>Configuration saved.</strong>
				</p>
			</div><br>
		<?php }?>
		<fieldset style="border:1px solid;padding:10px;">
			<legend>  <h1>Configuration</h1>  </legend>
			<form  method="post" onsubmit="return validate_configuration();">
				<table class="form-table">
					<tbody>
						<tr valign="top">
							<th scope="row"><b>Subdomain :</b></th>
							<td>http://  <input type="text"  size="20" name="subdomain" id="subdomain" value="<?php echo $post_data->subdomain;?>"> .CouponsInDemand.com</td>
						</tr>
						<!-- <tr valign="top">
							<th scope="row"><b>Display Order :</b></th>
							<td>
								<select name="display_order" id="CID-display_order">
									<option value="Newest" <?php if(trim($post_data->display_order)=='Newest'){ echo "selected";  }?>>Newest</option>
									<option value="Popular" <?php if(trim($post_data->display_order)=='Popular'){ echo "selected";  }?>>Popular</option>
									<option value="Expire" <?php if(trim($post_data->display_order)=='Expire'){ echo "selected";  }?>>Expire Soon</option>
									<option value="Alpha" <?php if(trim($post_data->display_order)=='Alpha'){ echo "selected";  }?>>Alphabet</option>
								</select>
							</td>
						</tr> -->
						<tr valign="top">
							<th scope="row"><b>Coupon List Style  :</b></th>
							<td>
								<img title="Select List Style below" src="<?php echo plugins_url( 'images/ListStyle.png' , __FILE__ );?>" width="285" />
								<BR />
								<div id="CID-conf_setting_div">
									<div id="CID-conf-wrapper1">
										<input type="radio" id="conf1" name="conf_param" value="no-map" <?php if($post_data->configuration_setting=='no-map'){ echo "checked";}?>> 
										<label for="conf1">Single Wide Column</label>
									</div>
									<div id="CID-conf-wrapper2">
										<input type="radio" id="conf2" name="conf_param" value="teaser" <?php if($post_data->configuration_setting=='teaser'){ echo "checked";}?>> 
										<label for="conf2">Isotope with Hover</label>
									</div>
								</div>
							</td>
						</tr>
						
						<tr valign="top">
							<th scope="row"><b>Isotope Cell Width :</b></th>
							<td>
								<select name="cell_width" id="CID-display_order">
									<option value="225" <?php if(trim($post_data->cell_width)=='225'){ echo "selected";  }?>>Narrow</option>
									<option value="250" <?php if(trim($post_data->cell_width)=='250'){ echo "selected";  }?>>Normal</option>
									<option value="275" <?php if(trim($post_data->cell_width)=='275'){ echo "selected";  }?>>Wide</option>
								</select>
							</td>
						</tr>
						
						<tr valign="top">
							<th scope="row"><b>Stretch Logo to Fit  :</b></th>
							<td>
								<div id="CID-stretch_div">
									<div id="CID-stretch-wrapper1">
										<input type="radio" id="stretch1" name="stretch_param" value="HW" <?php if($post_data->stretch=='HW'){ echo "checked";}?>> 
										<label for="stretch1">Height &amp; Width</label>
									</div>
									<div id="CID-stretch-wrapper2">
										<input type="radio" id="stretch2" name="stretch_param" value="W" <?php if($post_data->stretch=='W'){ echo "checked";}?>> 
										<label for="stretch2">Width Only</label>
									</div>
									<div id="CID-stretch-wrapper3">
										<input type="radio" id="stretch3" name="stretch_param" value="NO" <?php if($post_data->stretch=='NO'){ echo "checked";}?>> 
										<label for="stretch3">No Stretch</label>
									</div>
								</div>
							</td>
						</tr>
						
						<tr valign="top">
							<th scope="row"><b>Coupon Accessibility  :</b></th>
							<td>
								<div id="CID-download_file_div">
									<div id="CID-file-wrapper1">
										<input type="radio" id="file1" name="file_param" value="PDF" <?php if($post_data->file_type=='PDF'){ echo "checked";}?>> 
										<label for="file1">Download PDF Coupon</label>
									</div>
									<div id="CID-file-wrapper2">
										<input type="radio" id="file2" name="file_param" value="LINK" <?php if($post_data->file_type=='LINK'){ echo "checked";}?>> 
										<label for="file2">Link to Coupon Portal</label>
									</div>
									<div id="CID-file-wrapper3">
										<input type="radio" id="file3" name="file_param" value="NONE" <?php if($post_data->file_type=='NONE'){ echo "checked";}?>> 
										<label for="file3">No Access</label>
									</div>
								</div>
							</td>
						</tr>
						
						<tr valign="top">
							<th scope="row"><b>Show Company Name  :</b></th>
							<td>
								<div id="CID-show_companyname_div">
									<div id="CID-companyname-wrapper1">
										<input type="radio" id="companyname1" name="companyname_param" value="YES" <?php if($post_data->companyname=='YES'){ echo "checked";}?>>
										<label for="companyname1"> YES</label>
									</div>
									<div id="CID-companyname-wrapper2">
										<input type="radio" id="companyname2" name="companyname_param" value="NO" <?php if($post_data->companyname=='NO'){ echo "checked";}?>>
										<label for="companyname2"> NO</label>
									</div>
								</div>
							</td>
						</tr>
						
						<tr valign="top">
							<th scope="row"><b>Show Accepted Locations  :</b></th>
							<td>
								<div id="CID-show_location_div">
									<div id="CID-location-wrapper1">
										<input type="radio" id="loc1" name="location_param" value="YES" <?php if($post_data->locations=='YES'){ echo "checked";}?>>
										<label for="loc1"> YES</label>
									</div>
									<div id="CID-location-wrapper2">
										<input type="radio" id="loc2" name="location_param" value="NO" <?php if($post_data->locations=='NO'){ echo "checked";}?>>
										<label for="loc2"> NO</label>
									</div>
								</div>
							</td>
						</tr>
						
						<tr valign="top">
							<th scope="row"><b>Show Social Sharing  :</b></th>
							<td>
								<div id="CID-show_social_div">
									<div id="CID-social-wrapper1">
										<input type="radio" id="soc1" name="social_param" value="YES" <?php if($post_data->social=='YES'){ echo "checked";}?>> 
										<label for="soc1">YES</label>
									</div>
									<div id="CID-social-wrapper2">
										<input type="radio" id="soc2" name="social_param" value="NO" <?php if($post_data->social=='NO'){ echo "checked";}?>> 
										<label for="soc2">NO</label>
									</div>
								</div>
							</td>
						</tr>
						
					</tbody>
				</table>
			</fieldset>
			<p class="submit" align="center">
				<input name="submit_config" type="submit" class="button-primary" value="<?php esc_attr_e('Save'); ?>"/>
			</p>
		</form>
		
		<fieldset style="border:1px solid;padding:10px;">
			<legend>  <h1>Shortcode Examples</h1>  </legend>
				[CouponsInDemand]<br />
				[CouponsInDemand category="restaurant"]<br />
				[CouponsInDemand subcategory="restaurant"]<br />
				[CouponsInDemand couponid="1000634"]<br />
				[CouponsInDemand group="myAgencyGroup"]<br />
				[CouponsInDemand city="jacksonville"]<br />
				[CouponsInDemand state="fl"]<br />
				[CouponsInDemand limit="100" startdate="11-29-2014" enddate="12-05-2014"]<br /> 
		</fieldset>
	</div>
<?php }
