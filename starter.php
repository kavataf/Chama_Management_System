<?php
echo "Welcome To GoMC Applications Section Scaffold Builder\n";

$asset_name = readline("Enter a scaffold name to proceed: ");

/* Sleep */
sleep(1);

/* Helper Files */
$helpers = fopen("helpers/$asset_name.php", "w") or die("Unable to open file!");
echo "Crafted $asset_name.php Helper ✅ \n";

/* Sleep */
sleep(1);

/* Modals File */
$modals = fopen("modals/$asset_name.php", "w") or die("Unable to open file!");
echo "Crafted $asset_name.php Modal ✅ \n";

/* Sleep */
sleep(1);
/* Views File */
$views = fopen("views/$asset_name.php", "w") or die("Unable to open file!");
echo "Crafted $asset_name.php View ✅ \n";

/* Sleep */
sleep(1);
echo "All your scaffold files have been crafted, Happy coding 😉!\n";
