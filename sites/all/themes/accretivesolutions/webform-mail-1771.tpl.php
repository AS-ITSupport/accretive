<?php

// $Id: webform-mail-1771.tpl.php,v 1.1 2009/09/29 03:14:54 juneeveek Exp $



/**

 * @file

 * Customize the e-mails sent by Webform after successful submission.

 *

 * This file may be renamed "webform-mail-[nid].tpl.php" to target a

 * specific webform e-mail on your site. Or you can leave it

 * "webform-mail.tpl.php" to affect all webform e-mails on your site.

 *

 * Available variables:

 * - $form_values: The values submitted by the user.

 * - $node: The node object for this webform.

 * - $user: The current user submitting the form.

 * - $ip_address: The IP address of the user submitting the form.

 * - $sid: The unique submission ID of this submission.

 * - $cid: The component for which this e-mail is being sent.

 *

 * The $cid can be used to send different e-mails to different users, such as

 * generating a reciept-type e-mail to send to the user that filled out the

 * form. Each form element in a webform is assigned a CID, by doing special

 * logic on CIDs you can customize various e-mails.

 */

?>

<p><?php print t('Submitted on @date', array('@date' => format_date(time(), 'small'))) ?></p>



<?php if ($user->uid): ?>

<p><?php print t('Submitted by user: @username [@ip_address]', array('@username' => $user->name, '@ip_address' => $ip_address)) ?></p>

<?php else: ?>

<p><?php print t('Submitted by anonymous user: [@ip_address]', array('@ip_address' => $ip_address)) ?></p>

<?php endif; ?>





<?php print t('Submitted values are') ?>:<br />



<?php

  // Print out all the Webform fields. This is purposely a theme function call

  // so that you may remove items from the submitted tree if you so choose.

  // unset($form_values['submitted_tree']['element_key']);

  //print theme('webform_mail_fields', 0, $form_values['submitted_tree'], $node);

	print nl2br(theme('webform_mail_fields', 0, $form_values['submitted_tree'], $node));

?>


