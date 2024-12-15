<?php
if (!empty($alerts)):
    // every key of the alerts array (assoc) has an array (normal) of messages:
    //ej: $alerts = ["error" => ["i am an error like you!", "i was joking"], "success" => ["comida agregada correctamente"]];
    //the name of the key  (error) is the type or class of the message
?>
    <div class="alerts-container">
        <?php foreach ($alerts as $key => $messages): ?>
            <?php foreach ($messages as $message): ?>
                <div class="alert <?php echo $key; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </div>
<?php

endif;
