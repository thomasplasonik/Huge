<div class="container">
    <link rel="stylesheet" href="<?php echo Config::get('URL'); ?>css/chat.css" />
   <h1>Chat</h1>

   <div class="box">
      <h2>Chat mit: <?php echo($this->receiver->user_name)?></h2>
      <?php foreach ($this->messages as $message) { ?>

         <!--Links -->
      <div class="container">
         <?php if($message->sender->user_id == Session::get("user_id")){ ?>

         <div class="chat">

            <img src="<?= $message->sender->user_avatar_link; ?>" />
            <h3><?php echo($message->sender->user_name)?></h3>
            <p><?php echo($message->content)?></p>

         </div>


         <?php } ?>
         <?php if($message->receiver->user_id == Session::get("user_id")){ ?>

         <!--Rechts -->    
         <div class="chat">

            <img src="<?= $message->sender->user_avatar_link; ?>" class="right"/>
            <h3><?php echo($message->sender->user_name)?></h3>
            <p><?php echo($message->content)?></p>

         </div>
         <?php } ?>
      </div>

      <!--Nachricht senden -->
      <?php } ?>
      <form method="post" action="<?php echo Config::get('URL'); ?>messager/chat_action" method="post">
            <input type="text" name="message" required />
            <input type="hidden" name="receiver_id" value=<?php echo($this->receiver->user_id)?>/>

            <input type="submit" value="Send" />
      </form>
   </div>
</div>