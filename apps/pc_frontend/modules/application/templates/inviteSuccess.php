<?php use_helper('Javascript') ?>
<?php slot('invite_list_body'); ?>
<?php if ($pager->getNbResults()): ?>
<div id="member_list_box">
<?php echo __('Check the members who sends the invitation.') ?>
<table>
<tbody id="member_list">
<?php include_partial('inviteList', $sf_data->getRawValue()); ?>
</tbody>
</table>
<div class="operation">
<ul class="moreInfo button">
<li><input id="invite_button" type="button" value="<?php echo __('Invite') ?>" onclick="submit(true); false;" /></li>
<li><input id="close_button" type="button" value="<?php echo __('Close') ?>" onclick="submit(false); false;" /></li>
</ul>
</div>
</div>
<?php else: ?>
<?php echo __('You don\'t have any %friend%.', array(
    '%friend%' => $op_term['friend']->pluralize(),
)) ?>
<?php endif; ?>
<?php end_slot() ?>

<?php op_include_box('inviteListBox', get_slot('invite_list_body'), array(
  'title' => __('The invitation to this app is sent'),
)) ?>
<?php javascript_tag() ?>
var member_ids = [];
var changePage = function (page) {
  new Ajax.Request("<?php echo url_for('@application_invite_list?id='.$memberApplication->getId()) ?>?page=" + page, {
    method: 'get',
    onCreate: function() {
      $('member_list').innerHTML = '<tr><td colspan="2"><?php echo __('Loading') ?></td></tr>';
    },
    onSuccess: function(response) {
      $('member_list').innerHTML = response.responseText;
      member_ids.each(function (value, index) {
        var element = $('checkbox_' + value);
        if (element) {
          element.checked = true;
        }
      });
    },
    onFailure: function(response) {
      parent.iframeModalBox.close();
    }
  });
}
var checkAction = function(obj) {
  if (obj.checked) {
    member_ids.push(obj.name);
  } else {
    member_ids = member_ids.without(obj.name);
  }
  if (member_ids.length) {
    $('invite_button').enable();
  } else {
    $('invite_button').disable();
  }
};
var submit = function (f) {
  if (f) {
    $('invite_button').disable();
    new Ajax.Request('<?php echo url_for('@application_invite_post?id='.$memberApplication->getId()) ?>', {
      method: 'post',
      postBody: $H({
        _csrf_token: "<?php echo $form->getCSRFToken() ?>",
        "ids[]" : member_ids
      }).toQueryString(),
      onCreate: function () {
        $('member_list_box').innerHTML = '<?php echo __('Sending') ?>';
      },
      onSuccess: function (response) {
        var params = response.responseJSON;
        parent.iframeModalBox.close(params);
      },
      onFailure: function (response) {
        parent.iframeModalBox.close();
      }
    });
  } else {
    parent.iframeModalBox.close();
  }
}
$('invite_button').disable();
<?php end_javascript_tag(); ?>
