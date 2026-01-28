<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= $pageTitle ?? 'Programmar' ?></title>
        <link rel="stylesheet" href="public/styles/main.css">
        <link rel="stylesheet" href="public/styles/dashboard.css">
        <link rel="icon" type="image/svg+xml" href="public/resources/logo.svg">
        <script src="public/scripts/main.js" defer></script>
        <?php if(isset($extraStyles)) echo $extraStyles; ?>
        <?php if(isset($extraScripts)) echo $extraScripts; ?>
</head>
