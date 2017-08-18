<?php

return [
  'gcm' => [
      'priority' => 'normal',
      'dry_run' => false,
      'apiKey' => 'My_ApiKey',
  ],
  'fcm' => [
        'priority' => 'normal',
        'dry_run' => false,
        'apiKey' => 'AAAAzaxHAuU:APA91bG8W7zA6gLqDRhb0je3ibaX-OB1ezQnhlLguA47ZRShoLm-GEOKWesE-xsBg8UsuVIjLMzVrhRhQEtxBHhbwnULJROTzSxPK0Cvpu7m9n2nlG2wcju9MuC73rwJUlRFFgSktLuh',
  ],
  'apn' => [
      'certificate' => __DIR__ . '/iosCertificates/apns-dev-cert.pem',
      'passPhrase' => '1234', //Optional
      'passFile' => __DIR__ . '/iosCertificates/yourKey.pem', //Optional
      'dry_run' => true
  ]
];