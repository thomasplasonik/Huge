<div class="container">
   <h1>Chat</h1>
   <div class="box">
      <?php $this->renderFeedbackMessages(); ?>
      <div>
         <table>
            <thead>
               <tr>
                  <td>Profilbild</td>
                  <td>Benutzer-Name</td>
                  <td>Ungelesene Nachrichten</td>
                  <td>Chatten</td>
               </tr>
            </thead>
            <?php foreach ($this->users as $user) { ?>
                <?php if($user->user_id != Session::get("user_id")): ?>
                <tr class="<?= ($user->user_active == 0 ? 'inactive' : 'active'); ?>">
                    <td>
                        <?php if (isset($user->user_avatar_link)) { ?>
                        <img src="<?= $user->user_avatar_link; ?>" />
                        <?php } ?>
                    </td>
                    <td>
                        <?= $user->user_name; ?>
                    </td>
                    <td>
                        <?php 
                            $unreadMessages = MessageModel::CountUnreadMessagesForChat(Session::get("user_id"), $user->user_id);
                            if ($unreadMessages > 0) {
                                echo "<span class='unread-messages'>($unreadMessages)</span>";
                            } else {
                                echo "<span class='unread-messages'>(0)</span>";
                            }
                        ?>
                    </td>
                    <td><a href="<?= Config::get('URL') . 'messager/showchat/' . $user->user_id; ?>">Chat</a></td>
                </tr>
                <?php endif; ?>
            <?php } ?>
         </table>
      </div>
   </div>
</div>
