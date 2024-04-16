<div class="container">
   <h1>Chat</h1>
   <div class="box">
      <?php $this->renderFeedbackMessages(); ?>
      <div>
         <table id="user_table" class="overview-table">
            <thead>
               <tr>
                  <td>Avatar</td>
                  <td>Username</td>
                  <td>Chat</td>
               </tr>
            </thead>
            <?php foreach ($this->users as $user) { ?>
                <?php if($user->user_id != Session::get("user_id")): ?>
                <tr class="<?= ($user->user_active == 0 ? 'inactive' : 'active'); ?>">
                <td class="avatar">
                    <?php if (isset($user->user_avatar_link)) { ?>
                    <img src="<?= $user->user_avatar_link; ?>" />
                    <?php } ?>
                </td>
                <td <?php if(MessagerController::shouldDisplayNewMessageNotification($user->user_id, Session::get("user_id"))){
                  echo("style='color: red'");
                  } ?>><?= $user->user_name; ?></td>
                <td><a href="<?= Config::get('URL') . 'messager/showchat/' . $user->user_id; ?>">Chat</a></td>
                <?php endif; ?>
            </tr>
            <?php } ?>
         </table>
      </div>
   </div>
</div>