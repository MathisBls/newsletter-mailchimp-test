<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Newsletter Subscription Form</title>
</head>
<body>
    <h2>Subscribe to Our Newsletter</h2>
    <p>Get our latest updates delivered directly to your inbox.</p>

    <?php
    // Check if the form has been submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get the form data
        $email = $_POST["email"];
        $firstName = $_POST["firstname"];
        $lastName = $_POST["surname"];

        // Replace 'YOUR_API_KEY' with your own API key
        $api_key = 'YOUR_API_KEY';

        // Replace 'YOUR_LIST_ID' with your own list ID
        $list_id = 'YOUR_LIST_ID';

        $data_center = substr($api_key, strpos($api_key, '-') + 1);

        // Build the Mailchimp submission URL
        $url = 'https://' . $data_center . '.api.mailchimp.com/3.0/lists/' . $list_id . '/members/';

        // Data to send to Mailchimp in JSON format
        $data = json_encode([
            'email_address' => $email,
            'status' => 'subscribed', // To subscribe to the list
            'merge_fields' => [
                'FNAME' => $firstName,
                'LNAME' => $lastName
            ]
        ]);

        // cURL request configuration
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Basic ' . base64_encode("anystring:$api_key")
        ]);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        // To avoid cURL SSL Peer errors (remove in production: not for localhost)
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        // Execute the cURL request
        $response = curl_exec($curl);

        // Check if the request was successful
        if ($response && curl_getinfo($curl, CURLINFO_HTTP_CODE) == 200) {
            echo '<p>Thank you for subscribing to our newsletter!</p>';
        } else {
            echo '<p>An error occurred while subscribing to the newsletter.</p>';
        }

        // Close the cURL session
        curl_close($curl);
    }
    ?>

    <!-- Subscription form -->
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <input type="text" name="firstname" placeholder="First Name" required>
        <input type="text" name="surname" placeholder="Last Name" required>
        <input type="email" name="email" placeholder="Your email address" required>
        <input type="submit" value="Subscribe">
    </form>
</body>
</html>
