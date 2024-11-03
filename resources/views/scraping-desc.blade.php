<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Scraping Control Panel</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            max-width: 500px;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            font-size: 1.75rem;
            text-align: center;
            margin-bottom: 1.5rem;
            color: #343a40;
        }
        h3 {
            font-size: 1.25rem;
            text-align: center;
            margin-bottom: 1.25rem;
            color: #6c757d;
        }
        .btn-custom {
            width: 100%;
            margin-top: 0.5rem;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Data Scraping Control Panel</h1>
    <h3>Processing agents data in descending order</h3>

    <!-- Navigation Links -->
    <nav class="mb-4">
        <a href="{{ url('get-first-agents') }}" class="btn btn-outline-primary btn-sm">Ascending order</a>
        <a href="{{ url('get-last-agents') }}" class="btn btn-outline-primary btn-sm">Descending order</a>
        <a href="{{ route('export.agents') }}" class="btn btn-outline-primary btn-sm">Export Agents</a>
    </nav>

    <div class="mb-3">
        <input type="text" name="extension_id" id="ext-id" class="form-control" placeholder="Enter Extension ID">
    </div>

    <!-- Email input field -->
    <div class="mb-3">
        <input type="email" name="email" id="email" class="form-control" placeholder="Enter Email" value="{{ $email ?? '' }}">
    </div>

    <button class="btn btn-primary btn-custom" onclick="startScraping()">Start Data Scraping</button>
    <button class="btn btn-danger btn-custom" onclick="stopScraping()">Stop Data Scraping</button>
    <button class="btn btn-success btn-custom" onclick="continueScraping()">Continue Data Scraping</button>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
    /**
     * Validates email and sends it to the server.
     */
    function validateAndSaveEmail() {
        const email = document.getElementById('email').value.trim();
        if (!email || !validateEmail(email)) {
            alert('Please enter a valid email.');
            return false;
        }

        fetch('/api/update-email', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ email })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Email updated successfully.');
                } else {
                    console.error('Failed to update email.');
                }
            })
            .catch(error => {
                console.error('Error updating email:', error);
            });

        return true;
    }

    /**
     * Validates email format.
     */
    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    /**
     * Starts the data scraping process by sending a message to the extension.
     */
    function startScraping() {

        if (!validateAndSaveEmail()) return;

        const extensionId = document.getElementById('ext-id').value.trim();
        if (!extensionId) {
            alert('Please enter a valid Extension ID.');
            return;
        }

        console.warn('Starting scraping with Extension ID:', extensionId);

        // Send message to the extension to start scraping
        chrome.runtime.sendMessage(extensionId, {action: 'checkExtension', order: 'desc'}, function (response) {
            if (chrome.runtime.lastError) {
                console.error('Extension not found or not active.');
                alert('Extension not found or not active.');
            } else {
                console.log(response.message);
                alert(response.message);
            }
        });
    }

    /**
     * Stops the data scraping process by sending a message to the extension.
     */
    function stopScraping() {
        const extensionId = document.getElementById('ext-id').value.trim();
        if (!extensionId) {
            alert('Please enter a valid Extension ID.');
            return;
        }

        console.warn('Stopping scraping with Extension ID:', extensionId);

        // Send message to the extension to stop scraping
        chrome.runtime.sendMessage(extensionId, {action: 'stopScraping'}, function (response) {
            if (chrome.runtime.lastError) {
                console.error('Extension not found or not active.');
                alert('Extension not found or not active.');
            } else {
                console.log(response.message);
                alert(response.message);
            }
        });
    }

    /**
     * Continues the data scraping process by fetching the last processed URL and sending it to the extension.
     */
    function continueScraping() {

        if (!validateAndSaveEmail()) return;

        const extensionId = document.getElementById('ext-id').value.trim();
        if (!extensionId) {
            alert('Please enter a valid Extension ID.');
            return;
        }

        console.warn('Continuing scraping with Extension ID:', extensionId);

        // Make an AJAX request to fetch the last processed URL
        fetch('/api/continue-scraping/desc', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'ngrok-skip-browser-warning': 'true'
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const url = data.url;
                    const regionId = data.region_id;
                    console.log('Last processed URL:', url);

                    // Send message to the extension to continue scraping
                    chrome.runtime.sendMessage(extensionId, {
                        action: 'continueScraping',
                        url: url,
                        region_id: regionId,
                        region_order: 'desc'
                    }, function (response) {
                        if (chrome.runtime.lastError) {
                            console.error('Extension not found or not active.');
                            alert('Extension not found or not active.');
                        } else {
                            console.log(response.message);
                            alert(response.message);
                        }
                    });
                } else {
                    console.error(data.message);
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error fetching last processed URL:', error);
                alert('Failed to fetch last processed URL.');
            });
    }
</script>
</body>
</html>