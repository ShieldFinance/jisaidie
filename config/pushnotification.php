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
        'apiKey' => 'AAAA-I15OnI:APA91bGQz8wT_vAFei55AxtvTErGylT9l3k4NB7R5KuehxiSnhXMhQ-mxcCTME3mRxCCAz6iEeo-1yyF5MTw0kZ25MVnOUFCSdvAvGk-dm0M_pXutT_iTlMlerVt4tvgZmRGK_XlvcXF',
  ],
  'apn' => [
      'certificate' => __DIR__ . '/iosCertificates/apns-dev-cert.pem',
      'passPhrase' => '1234', //Optional
      'passFile' => __DIR__ . '/iosCertificates/yourKey.pem', //Optional
      'dry_run' => true
  ]
];