<div class="container">
   <h1>Chat</h1>
   <h2>Chat mit: <?php echo($this->receiver->user_name)?></h2>
   <h3>Status: <?php echo(MessageModel::isChatRead(Session::get("user_id"), $this->receiver->user_id) ? 'Gelesen' : 'Ungelesen'); ?></h3>
   <section class="discussion">

      <?php foreach ($this->messages as $message) { ?>

         <?php if($message->sender->user_id == Session::get("user_id")){ ?>
            <div class="bubble recipient first"><?php echo($message->content)?></div>
         <?php } ?>

         <?php if($message->receiver->user_id == Session::get("user_id")){ ?>

            <div class="bubble sender first"><?php echo($message->content)?></div>
         <?php } ?>


      <?php } ?>


      <!--Nachricht senden -->
      <form method="post" action="<?php echo Config::get('URL'); ?>messager/chatting" method="post">
            <input type="text" name="message" required />
            <input type="hidden" name="receiver_id" value=<?php echo($this->receiver->user_id)?>/>

            <input type="submit" value="Send" />
      </form>
   </section>
</div>
