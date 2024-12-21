<?php require_once __DIR__ . "/../../partials/navbar.php";

require_once __DIR__ . "/../../partials/alerts.php";

?>
<?php require_once __DIR__ . "/partials/sectionTabs.php"; ?>

<!-- profile section -->
<section class="profile stretched-container container section--hide" data-section-id="1">

    <div class="form-container">

        <form action="" class="form-body" method="post">

            <?php require_once __DIR__ . "/forms/userProfileForm.php"; ?>

            <div class="form-footer">
                <button type="submit" class="btn btn-submit"><span class="text">Guardar</span></button>
            </div>

        </form>

    </div>

</section>

<?php
require_once __DIR__ . "/../../partials/footer.php";
?>
<?php
//scripts used in the home page:
$scripts = "
<script src='/build/js/modules/navbar.min.js'></script>
<script src='/build/js/modules/tabs.min.js'></script>
<script src='/build/js/modules/notifications.min.js'></script>";
?>