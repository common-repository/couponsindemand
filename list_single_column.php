<div class="CID-rounded-corner-white">
	<div id="CID-wrapper">
		<div id="cid-container">
			<?php $couponCount =0;
				if($json){
					$coupon_count = count($json);

					for ($i=0; $i <$coupon_count; $i++) {
						$couponCount= $couponCount + 1;
			?>
			<div class="CID-coupon element <?php echo str_replace(',', ' ', $json[$i]['SUBCATSEOLIST']); ?>" cidconame="<?php echo $json[$i]['COMPANYNAME'];?>" data-category="transition">
				<div class="CID-pin" couponid="<?php echo $json[$i]['COUPONID'];?>">
					<div class="CID-coupon-section">
						<div class="CID-widget CID-widget-one-<?php echo $json[$i]['COUPONID'];?> CID-rounded">
							<div style="padding: 4px 10px 0px 10px;">
								<div>
									<div align="left" style="height:-6px; width:96%; margin-top:5px; left:10px; font-size:24px; font-family:'Helvetica'; font-weight:600; color:#8fbae3; z-index:3000;">
										<?php if($show_company_name == "YES") {?>
										<p class="cidconame"><?php echo $json[$i]['COMPANYNAME'];?></p>
										<?php }?>
									</div>
									<div align="left" class="cidtitle"> <?php echo preg_replace("/\r\n|\r|\n/",'<br/>',$json[$i]['TITLE']);?> </div>
									<div align="left" class="cidsubtitle"> <?php echo preg_replace("/\r\n|\r|\n/",'<br/>',$json[$i]['SUBTITLE']);?> </div>
								</div>
								<div style="bottom:5px;">
									<div class="cid-bottom-left"> <img class="fadePic scale cid-client-logo-<?php echo $stretch; ?>" src="<?php if (count($json[$i]['IMAGES']) > 0) {echo $json[$i]['IMAGES'][0][IMAGE];}else{echo $missingImg;}?>" /> </div>
									<div align="left" class="cid-bottom-right">
										<div align="left" class="ciddetail"><BR />
											<?php echo preg_replace("/\r\n|\r|\n/",'<br/>',$json[$i]['DETAILS']);?> </div>
										<div align="left" class="ciddisclaimer"><BR />
											<?php echo preg_replace("/\r\n|\r|\n/",'<br/>',$json[$i]['DISCLAIMER']);?> </div>
										<?php if($file_type == "PDF" && $json[$i]['ISEZ'] == 0) {?>
										<BR />
										<div class="get-coupon-button">
											<?php if($isMobile == 1) {?>
											<a class="redeemBtn" couponid="<?php echo $json[$i]['COUPONID'];?>" href="#">Redeem</a>
											<?php } else { ?>
											<a href="<?php echo $json[$i]['COUPONPRINTHIGHRESURL']; ?>" target="blank">Get Coupon</a>
											<?php }?>
										</div>
										<?php } else if($file_type == "LINK") { ?>
										<BR />
										<div class="get-coupon-button"> <a href="<?php echo $json[$i]['COUPONDETAILURL']; ?>" target="blank">See Offer</a> </div>
										<?php }?>
										<?php if($social_sharing == "YES") {?>
										
											<div class="get-linkedin-button"> <a href="https://www.linkedin.com/cws/share?url=<?php the_permalink();?><?php echo $json[$i]['COUPONID'];?>/" target="blank"><img src="<?php echo $linkedinImg;?>" /></a> </div>
											<div class="get-gplus-button"> <a href="https://plus.google.com/share?url=<?php the_permalink();?><?php echo $json[$i]['COUPONID'];?>/" target="blank"><img src="<?php echo $gplusImg;?>" /></a> </div>
											<div class="get-twitter-button"> <a href="https://twitter.com/share?url=<?php the_permalink();?><?php echo $json[$i]['COUPONID'];?>/" target="_blank"><img src="<?php echo $twitterImg;?>" /></a> </div>
											<div class="get-FB-button"> <a href="http://www.facebook.com/sharer.php?u=<?php the_permalink();?><?php echo $json[$i]['COUPONID'];?>/" target="blank"><img src="<?php echo $facebookImg;?>" /></a> </div>
										<?php }?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div id="<?php echo $json[$i]['COUPONID'];?>" class="CID-location">
						<?php if(count($json[$i]['LOCATIONS']) > 0 && $location == "YES") {?>
						<ul id="CID-locations" class="mCustomScrollbar">
							<?php foreach ($json[$i]['LOCATIONS'] as $k => $v) {?>
							<li data-geo-lat="<?php echo $v['LATITUDE']?>" data-geo-long="<?php echo $v['LONGITUDE']?>">
								<h3> <?php echo $v['NAME'];?> </h3>
								<p>
									<?php if($v['ADDR1']) {
										echo $v['ADDR1'];
										?>
									<br>
									<?php
										}
										if($v['ADDR2']) {
											echo $v['ADDR2'];
											?>
									<br>
									<?php
										}
										if($v['ADDR3']) {
											echo $v['ADDR3'];
											?>
									<br>
									<?php
										}
										if($v['CITY']) {
											echo $v['CITY'];
										}
										if($v['STATEABBR']) {
											echo ', '.$v['STATEABBR'];
										}
										if($v['POSTALCODE']) {
											echo ' '.$v['POSTALCODE'];
											?>
									<?php
										}
										
										if($v['PHONEMAIN']) {
											?>
									<BR />
									<img src="<?php echo $phoneImg?>" style="height:9px; width:12px; margin:2px 3px" />
									<?php
											echo $v['PHONEMAIN'];
											?>
									<?php
										}
										
										if($v['URL']) {
											?>
									<span style="float:right;"><a target="_blank" href="http://<?php
											echo $v['URL'];
											?>">website</a></span><br>
									<?php
										}?>
								</p>
							</li>
							<?php }?>
						</ul>
						<?php } ?>
					</div>
				</div>
			</div>
			<?php }
				}else {
					?>
			<center>
				Sorry, this specific page does not currently have any discounts.<br />
				Please check back soon.
			</center>
			<?php
				}?>
		</div>
		<a href="http://www.couponsindemand.com" target="_blank" title="start making coupons for your business today"> <img src="<?php echo $powerImg?>" align="right" style="height:40px; width:115px; margin:2px 3px" /></a>
		<div class="clr"></div>
	</div>
</div>
