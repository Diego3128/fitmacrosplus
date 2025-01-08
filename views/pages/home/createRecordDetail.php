<?php require_once __DIR__ . "/../../partials/navbar.php"; ?>

<?php require_once __DIR__ . "/../../partials/alerts.php"; ?>

<div class="title-container">
    <h2>Crea un nuevo registro</h2>
</div>

<?php
require_once __DIR__ . "/../../partials/paginator.php";
?>

<div class="container stretched-container">
    <form
        action="/home/new-record-detail/create?mealid=<?php echo $mealId ?? '' ?>&recordid=<?php echo $recordId ?? '' ?>&foodid=<?php echo $foodId ?? '' ?>"
        method="post" id="portion-form"
        class="portion-form flex">
        <label for="portion">Porción:
            <?php if (isset($units)) : ?>
                <?php foreach ($units as $unit) : ?>
                    <?php if ($unit->id == $userFood->serving_unit_id) : ?>
                        <span><?php echo escapeHTML($unit->name); ?></span>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </label>
        <input
            data-portion-initial-size="<?php echo floor($userFood->serving_size); ?>"
            type="number"
            id="portion"
            name="portion"
            value="<?php echo floor($userFood->serving_size); ?>" min="1" step="1" />
        <button
            type="submit"
            class="portion-form__btn">Agregar registro
        </button>
    </form>

    <div id="food-details" class="food-details-container">

        <?php foreach ($userFood as $key => $value) : ?>
            <?php
            if (
                $key === "user_profile_id" ||
                $key === "id" ||
                $key === "serving_size" ||
                $key === "serving_unit_id" ||
                $value == 0
            ) {
                continue;
            }
            // spanish language
            $fieldName = $translations[$key]["es"] ?? $key;
            ?>

            <div class="food-details">

                <div class="food-detail flex ">
                    <p class="food-detail__name"><?php echo escapeHTML($fieldName); ?>
                        <span><?php if (isset($nutrientUnits[$key])) echo "(" . $nutrientUnits[$key] . ")"  ?? ''; ?></span>
                    </p>
                    <p
                        class=" food-detail_value"
                        <?php if ($key != "name" && $key != "brand") echo "data-initial-value={$value}" ?>>

                        <?php echo escapeHTML($value); ?>
                    </p>
                </div>
            </div>

        <?php endforeach; ?>
    </div>

    <div class="form-footer buttom-margin-lg">
        <a href="/home/editfood?id=<?php echo $userFood->id ?>" class="btn edit">Editar alimento</a>
    </div>

</div>


<?php
require_once __DIR__ . "/../../partials/footer.php";
?>
<?php
//scripts used in the home page:
$scripts = "
<script src='/build/js/pages/dashboard.min.js'></script>
<script src='/build/js/modules/notifications.min.js'></script>
<script src='/build/js/modules/navbar.min.js'></script>";
?>