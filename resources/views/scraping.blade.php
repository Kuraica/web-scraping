<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Scraping</title>
</head>
<body>
<h1>Data Scraping Control Panel</h1>

<input type="text" name="extension_id" id="ext-id" value="">

<button onclick="startScraping()">Start Data Scraping</button>
<button onclick="stopScraping()">Stop Data Scraping</button>

<script>
    function startScraping() {
        // const extensionId = 'dohnmlpcdjljjpffojlfckhiijogihba';
        const extensionId = document.getElementById('ext-id').value;
        console.warn(extensionId);

        // Send message to the extension to start scraping
        chrome.runtime.sendMessage(extensionId, { action: 'checkExtension' }, function(response) {
            if (chrome.runtime.lastError) {
                console.error('Extension not found or not active.');
                alert('Extension not found or not active.');
            } else {
                console.log(response.message);
                alert(response.message);
            }
        });
    }

    function stopScraping() {
        // const extensionId = 'dohnmlpcdjljjpffojlfckhiijogihba';
        const extensionId = document.getElementById('ext-id').value;
        console.warn(extensionId);

        // Send message to the extension to stop scraping
        chrome.runtime.sendMessage(extensionId, { action: 'stopScraping' }, function(response) {
            if (chrome.runtime.lastError) {
                console.error('Extension not found or not active.');
                alert('Extension not found or not active.');
            } else {
                console.log(response.message);
                alert(response.message);
            }
        });
    }
</script>
</body>
</html>
