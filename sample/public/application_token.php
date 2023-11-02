<?php
declare(strict_types=1);

require_once '../../vendor/autoload.php';

use Dotenv\Dotenv;
use Nonz250\SmaregiApiPhp\Auth\SmaregiClientCredentials;
use Nonz250\SmaregiApiPhp\Auth\SmaregiProvider;

(Dotenv::createImmutable(__DIR__))->load();

try {
    $provider = new SmaregiProvider(
        (string)$_ENV['SMAREGI_IDP_HOST'],
        (string)$_ENV['SMAREGI_CLIENT_ID'],
        (string)$_ENV['SMAREGI_CLIENT_SECRET'],
    );

    $accessToken = $provider->getAccessToken(new SmaregiClientCredentials(), [
        'contract_id' => (string)$_ENV['SMAREGI_CONTRACT_ID'],
        'scope' => ['pos.products:read', 'pos.customers:read'],
    ]);
} catch (Throwable $e) {
    echo htmlspecialchars($e->getMessage());
    exit();
}
?>

<html lang="ja">
<head>
    <title>smaregi-api-php sample.</title>
    <link rel="stylesheet" href="table.css" type="text/css"/>
</head>
<body>
<h2>Access Token.</h2>
<table>
    <thead>
    <tr>
        <th class="key">項目名</th>
        <th>値</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>token</td>
        <td><?php echo htmlspecialchars((string)$accessToken->getToken()); ?></td>
    </tr>
    <tr>
        <td>refresh_token</td>
        <td><?php echo htmlspecialchars((string)$accessToken->getRefreshToken()); ?></td>
    </tr>
    <tr>
        <td>expires_in</td>
        <td><?php echo htmlspecialchars((string)$accessToken->getExpires()); ?></td>
    </tr>
    <tr>
        <td>has_expires ( bool )</td>
        <td><?php echo $accessToken->hasExpired() ? 'true' : 'false'; ?></td>
    </tr>
    <tr>
        <td>all params ( array )</td>
        <td><?php echo htmlspecialchars(json_encode($accessToken->getValues(), JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT)); ?></td>
    </tr>
    <tr>
    </tbody>
</table>
</body>
</html>
