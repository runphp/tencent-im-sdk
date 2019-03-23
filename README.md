# tencent-im-sdk
腾讯云通信 php sdk

## 安装
```bash
composer require tencentsdk/im
```

## 使用
```php
use TencentSDK\Im\Client as ImClient;

$options = [
    'appId' => 'your app id',
    'privateKey' => 'your private key path',
    'adminer' => 'admin',  // your admin account
];
$imClient = new ImClient($options['appId'], $options['privateKey'], $options['adminer']);

// 添加好友
$imClient->execute('sns', 'friend_add', [
    'From_Account'  => 'test_from_account',
    'AddFriendItem' => [
        [
            'To_Account' => 'test_to_account',
                'AddSource'  => 'AddSource_Type_test',
        ],
    ],
]);

// 添加群群员
$imClient->execute('group_open_http_svc', 'add_group_member', [
    'GroupId'  => 'group_id',
    'MemberList' => [
        [
            'Member_Account' => 'test_to_account',
        ],
    ],
]);

// 示例仅供参考，以腾讯IM文档为准

```