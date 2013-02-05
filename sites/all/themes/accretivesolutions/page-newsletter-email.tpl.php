
<table width="547" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td colspan="3"><img src="<?php print url($header_img, array('absolute' => true)); ?>" /></td>
  </tr>
  <tr>
    <td colspan="3"><img src="<?php print url(path_to_theme(), array('absolute' => true)) ?>/images/spacer.gif" width="100" height="12" /></td>
  </tr>
  
  <tr>
    <td valign="top">
      <!-- Email Article START -->
      <table width="332" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="347"><img src="<?php print url(path_to_theme(), array('absolute' => true)) ?>/images/spacer.gif" width="100" height="12" /></td>
        </tr>
        <tr>
          <td align="left" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:15px; font-weight:bold; color:#008530;"><?php print $newsletter_intro ?></td>
        </tr>
        <tr>
          <td><img src="<?php print url(path_to_theme(), array('absolute' => true)) ?>/images/spacer.gif" width="100" height="12" /></td>
        </tr>
        <tr>
          <td bgcolor="#e2e2e2"><img src="<?php print url(path_to_theme(), array('absolute' => true)) ?>/images/spacer.gif" height="3" width="100" /></td>
        </tr>
        <tr>
          <td><img src="<?php print url(path_to_theme(), array('absolute' => true)) ?>/images/spacer.gif" width="100" height="12" /></td>
        </tr>
        <tr>
          <td align="left" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:14px; font-weight:bold; color:#24298A;" ><?php print $title ?></td>
        </tr>
        <tr>
          <td align="left" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:11px; color:#494955;"><?php print $published_date ?></td>
        </tr>
        <tr>
          <td><img src="<?php print url(path_to_theme(), array('absolute' => true)) ?>/images/spacer.gif" width="100" height="12" /></td>
        </tr>
        <tr>
          <td align="left" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:11px; color:#494955;">
            <?php print $body ?>          </td>
        </tr>
        <tr>
          <td><img src="<?php print url(path_to_theme(), array('absolute' => true)) ?>/images/spacer.gif" width="100" height="12" /></td>
        </tr>
        <tr>
          <td style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:11px; text-decoration:none; color:#494955;">
            <a href="<?php print $node_link ?>" style="text-decoration:none; color:#008530;">Read Full Article &gt;</a>          </td>
        </tr>
        <tr>
          <td><img src="<?php print url(path_to_theme(), array('absolute' => true)) ?>/images/spacer.gif" width="100" height="12" /></td>
        </tr>
        <tr>
          <td bgcolor="#e2e2e2"><img src="<?php print url(path_to_theme(), array('absolute' => true)) ?>/images/spacer.gif" height="3" width="100" /></td>
        </tr>
      </table>
      <!-- Email Article END -->

      <!-- Past issues START -->
      <table width="332" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td colspan="2"><img src="<?php print url(path_to_theme(), array('absolute' => true)) ?>/images/spacer.gif" width="100" height="12" /></td>
        </tr>
        <tr>
          <td colspan="2" align="left" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:14px; font-weight:bold; color:#494955;">Archive of Newsletters</td>
        </tr>
        <tr>
          <td colspan="2"><img src="<?php print url(path_to_theme(), array('absolute' => true)) ?>/images/spacer.gif" height="5" width="100" /></td>
        </tr>
        <tr>
          <td colspan="2" align="left" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:11px; font-weight:bold; color:#494955;">
            <?php
               //get newsletter archive views
               $view = views_get_view('newsletter_archive');
               print $view->execute_display('defaults');
             ?>          </td>
        </tr>
        <tr>
          <td colspan="2"><img src="<?php print url(path_to_theme(), array('absolute' => true)) ?>/images/spacer.gif" height="5" width="100" /></td>
        </tr>
      </table>
      <!-- Past issues END -->

      <table width="332" border="0" cellpadding="0" cellspacing="0">
        
        <!-- Jobs START -->
        <tr>
          <td colspan="2"><img src="<?php print url(path_to_theme(), array('absolute' => true)) ?>/images/spacer.gif" width="100" height="12" /></td>
        </tr>
        <tr>
          <td colspan="2" align="left" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:14px; font-weight:bold; color:#494955;">Current Opportunities</td>
        </tr>
        <tr>
          <td colspan="2">
            <?php
               //get jobs block view
               $view = views_get_view('jobs');
               print $view->execute_display('defaults', array($taxonomy[0]->tid));
             ?>          </td>
        </tr>
        <!-- Jobs END -->

        <!-- Office Info START -->
        <tr>
          <td colspan="2"><img src="<?php print url(path_to_theme(), array('absolute' => true)) ?>/images/spacer.gif" height="15" width="100" /></td>
        </tr>
        <tr>
          <td colspan="2"><span style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:11px; font-weight:bold; color:#494955;">Office Location</span></td>
        </tr>
        <tr>
          <td colspan="2">
            <?php
                 //get office block view
                 $view = views_get_view('office');
                 print $view->execute_display('defaults', array($office_name));
             ?>          </td>
        </tr>
        <!-- Office Info END -->
    </table>    </td>
    <td width="198" valign="top">
    <?php
      //get office block view
      $view = views_get_view('author_info');
      print $view->execute_display('defaults', array($reference_author));
    ?>    </td>
  </tr>
  <tr>
      <td colspan="3" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
              <td width="39%" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:11px; color:#494955;">To unsubscribe, please reply to this email with the word &quot;UNSUBSCRIBE&quot; in the subject line.</td>
              <td width="61%" align="right" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:11px; color:#494955;">&copy; Copyright  08 Accretive Solutions. <br />All right reserved.</td>
          </tr>
      </table></td>
    </tr>
</table>