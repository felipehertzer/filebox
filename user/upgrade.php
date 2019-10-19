<?php

	/*
	+--------------------------------------------------------------------------
	|   FileBox - File Hosting & Sharing Script (v1.5)
	|   =======================================================================
	|   by ScriptsApart
	|   (c) 2010 ScriptsApart
	|   http://www.scriptsapart.com
	|   =======================================================================
	|   Web: http://www.scriptsapart.com
	|   Email: support@scriptsapart.com
	+--------------------------------------------------------------------------
	|   > Developed On: 25th December 2010
	+--------------------------------------------------------------------------
	*/
 
 include("../init.php");
 include("header.php");
 subheader('Go Premium');
 
 if($sesslife == true) { 
 echo "<div class='global'><div id='main'><div id='account-header'><p>Upgrade Account</p></div>";
	if($premium != 1) { ?>
 <table id='userinfo'><tr><td>Get a premium membership for <span id='more' style='font-size:13px;'>$<?php echo $premium_cost; ?></span> a month and enjoy the following benefits:<br/><br/>
 <b><ul><li>- Unlimited uploads & downloads</li>
 <li>- Max file size of 1 GB</li>
 <li>- No wait time for downloading files</li>
 <li>- Advanced Statistics</li>
 <li>- No advertisements</li>
 </ul></b><br/>
 * Clicking the button below will take you to the PayPal payment gateway where you will pay for your membership. Once you make the payment, you will be redirected back to the website with your purchase information.
 <br/><br/>
 <input type="button" value="Go Premium for $<?php echo $premium_cost; ?>" onClick="window.location='<?php echo $website; ?>/user/order.php?s=process'" class="button">
 </form>
 </td></tr></table>
 
 <?php } else { echo "<div class='errorbox'><p>Already a Premium Member</p></div>
 <br/><small class='margin'>Our records indicate that you are already a premium member. In case you want to renew your membership then you must visit this page once your membership expires. Contact support staff for assistance.</small>"; }
 echo "</div></div>";
 
 } else { $err = "<div class='infobox'><p>Upgradation Center<br/><small>You need to be logged in for upgrading your membership.</small></p></div>";
 am_showLogin(); }
 
 include("footer.php");
 
?>