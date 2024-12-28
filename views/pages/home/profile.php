<?php require_once __DIR__ . "/../../partials/navbar.php";

require_once __DIR__ . "/../../partials/alerts.php";

?>
<?php require_once __DIR__ . "/partials/sectionTabs.php"; ?>

<!-- profile section -->
<section class="profile stretched-container container section--hide" data-section-id="1">

    <section class="user-requirements">
        <h2 class="user-requirements__title">Requerimientos Nutricionales</h2>
        <ul class="user-requirements__list">
            <li class="user-requirements__item">
                <span class="user-requirements__label">Tasa Metabólica Basal:</span>
                <span class="user-requirements__value"><?php echo escapeHTML($userRequirements->basal_metabolic_rate) ?>kcal</span>
            </li>
            <li class="user-requirements__item">
                <span class="user-requirements__label">Requerimiento Calórico:</span>
                <span class="user-requirements__value"><?php echo escapeHTML($userRequirements->caloric_requirement) ?>kcal</span>
            </li>
            <li class="user-requirements__item">
                <span class="user-requirements__label">Proteína:</span>
                <span class="user-requirements__value"><?php echo escapeHTML($userRequirements->protein_requirement) ?>g</span>
            </li>
            <li class="user-requirements__item">
                <span class="user-requirements__label">Carbohidratos:</span>
                <span class="user-requirements__value"><?php echo escapeHTML($userRequirements->carb_requirement) ?>g</span>
            </li>
            <li class="user-requirements__item">
                <span class="user-requirements__label">Grasas:</span>
                <span class="user-requirements__value"><?php echo escapeHTML($userRequirements->fat_requirement) ?>g</span>
            </li>
            <li class="user-requirements__item">
                <span class="user-requirements__label">Fibra:</span>
                <span class="user-requirements__value"><?php echo escapeHTML($userRequirements->fiber_requirement) ?>g</span>
            </li>
        </ul>
    </section>


    <div class="form-container">

        <form action="" class="form-body" method="post">

            <?php require_once __DIR__ . "/forms/userProfileForm.php"; ?>

            <div class="form-footer">
                <button type="submit" class="btn btn-submit"><span class="text">Actualizar</span></button>
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