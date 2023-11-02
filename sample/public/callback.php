<?php
declare(strict_types=1);

require_once '../../vendor/autoload.php';

use Dotenv\Dotenv;
use Nonz250\SmaregiApiPhp\Auth\SmaregiProvider;

session_start();

(Dotenv::createImmutable(__DIR__))->load();

try {
    $error = $_GET['error'] ?? '';

    if ($error) {
        throw new RuntimeException(implode('<br>', [
            'error: ' . ($_GET['error'] ?? ''),
            'error_description: ' . ($_GET['error_description'] ?? ''),
            'hint: ' . ($_GET['hint'] ?? ''),
            'message: ' . ($_GET['message'] ?? ''),
        ]));
    }
} catch (Throwable $e) {
    echo htmlspecialchars($e->getMessage());
    exit();
}

try {
    $sessionState = (string)($_SESSION['sampleState'] ?? '');

    if ($sessionState === '') {
        throw new RuntimeException('Session state is empty.');
    }
    $sampleState = $_GET['state'] ?? '';

    if ($sessionState !== $sampleState) {
        throw new RuntimeException('Invalid state.');
    }
} catch (Throwable $e) {
    echo htmlspecialchars($e->getMessage());
    exit();
}

try {
    $provider = new SmaregiProvider(
        (string)$_ENV['SMAREGI_IDP_HOST'],
        (string)$_ENV['SMAREGI_CLIENT_ID'],
        (string)$_ENV['SMAREGI_CLIENT_SECRET'],
        'http://localhost/callback.php',
    );

    $code = $_GET['code'] ?? '';

    $provider->setPkceCode($_SESSION['samplePkceCode'] ?? '');
    $accessToken = $provider->getAccessToken('authorization_code', [
        'code' => $code,
    ]);

    $resourceOwner = $provider->getResourceOwner($accessToken);

    $accessToken = $provider->getAccessToken('refresh_token', [
        'refresh_token' => $accessToken->getRefreshToken(),
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
<h2>Resource Owner.</h2>
<table>
    <thead>
    <tr>
        <th class="key">項目名</th>
        <th>値</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>sub</td>
        <td><?php echo htmlspecialchars($resourceOwner->getId()); ?></td>
    </tr>
    <tr>
        <td>contract.id</td>
        <td><?php echo htmlspecialchars($resourceOwner->getContractId()); ?></td>
    </tr>
    <tr>
        <td>contract.user_id</td>
        <td><?php echo htmlspecialchars($resourceOwner->getUserId()); ?></td>
    </tr>
    <tr>
        <td>contract.is_owner ( bool )</td>
        <td><?php echo $resourceOwner->getIsOwner() ? 'true' : 'false'; ?></td>
    </tr>
    <tr>
        <td>name</td>
        <td><?php echo htmlspecialchars($resourceOwner->getName()); ?></td>
    </tr>
    <tr>
        <td>email</td>
        <td><?php echo htmlspecialchars($resourceOwner->getEmail()); ?></td>
    </tr>
    <tr>
        <td>email_verified ( bool )</td>
        <td><?php echo $resourceOwner->getEmailVerified() ? 'true' : 'false'; ?></td>
    </tr>
    <tr>
        <td>all params ( array )</td>
        <td><?php echo htmlspecialchars(json_encode($resourceOwner->toArray(), JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT)); ?></td>
    </tr>
    </tbody>
</table>

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