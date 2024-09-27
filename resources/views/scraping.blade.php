<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Scraping</title>
</head>
<body>
<h1>Data Scraping Control Panel</h1>

<button onclick="startScraping()">Start Data Scraping</button>
<button onclick="stopScraping()">Stop Data Scraping</button>

<script>
    function startScraping() {
        const extensionId = 'dohnmlpcdjljjpffojlfckhiijogihba'; // Zameni sa tvojim extension ID

        // Pokušaj slanja poruke ekstenziji
        chrome.runtime.sendMessage(extensionId, { action: 'checkExtension' }, function(response) {
            if (chrome.runtime.lastError) {
                console.error('Ekstenzija nije pronađena ili nije aktivna.');
                alert('Ekstenzija nije pronađena ili nije aktivna.');
            } else {
                console.log(response.message);
                alert(response.message);

                // Logika za početak scraping-a (pozivanje API-ja ili slanje AJAX zahteva ka serveru)
            }
        });
    }

    function stopScraping() {
        alert('Stopping data scraping...');
        // Ovdje možeš dodati logiku za stop scraping, poput AJAX poziva ka serveru
    }
</script>
</body>
</html>
