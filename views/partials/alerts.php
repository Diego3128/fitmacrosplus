<?php
if (!empty($alerts)):
    // every key of the alerts array (assoc) has an array (normal) of messages:
    //ej: $alerts = ["error" => ["i am an error like you!", "i was joking"]];
    //the name of the key  (error) is the type or class of the message
?>
    <div class="alerts-container">
        <?php
        foreach ($alerts as $key => $messages) :
            foreach ($messages as $message) { ?>
                <div class="alert <?php echo $key ?>"><?php echo $message ?></div>
        <?php
            }
        endforeach; ?>
    </div>
<?php

endif;
