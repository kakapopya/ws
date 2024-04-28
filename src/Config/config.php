<?php

return [
    /**
     * 调试打开还是关闭？
     */
    'debug' => true,

    /**
     * 是否启用日志？
     */
    'log' => false,

    /**
     * whatsapp数据（如媒体、图片等）的路径。。
     * Web服务器的路径必须是可写的
     */
    'data-storage' => storage_path() . '/Ws',

    // 可广播消息的最大联系人数
    'broadcast-limit' => 20,

    'listen-events' => true,

    'listen-type' => 'echo',

    // 用于发送消息的默认帐户
    'default' => 'default',

    /**
     * 下面是伪造的凭据。甚至不用费心去使用它们。
     */
    'accounts'    => array(
        'default'   => array(
            'nickname' => 'Itnovado',
            'number'   => '5219512132132',
            'password' => '==87Vf4plh+lvOAvoURjBoKDKwciw='
        ),
        /*
        'another'    => array(
            'nickname' => '',
            'number'   => '',
            'password' => ''
        ),
        'yetanother' => array(
            'nickname' => '',
            'number'   => '',
            'password' => ''
        )
        */
    ),

    /**
     * 这是所有当前事件的列表。取消注释你想听的内容。
     */
    'events-to-listen' => [
        'onClose',
        'onCodeRegister',
        'onCodeRegisterFailed',
        'onCodeRequest',
        'onCodeRequestFailed',
        'onCodeRequestFailedTooRecent',
        'onConnect',
        'onConnectError',
        'onCredentialsBad',
        'onCredentialsGood',
        'onDisconnect',
        'onDissectPhone',
        'onDissectPhoneFailed',
        'onGetAudio',
        'onGetBroadcastLists',
        'onGetError',
        'onGetExtendAccount',
        'onGetGroupMessage',
        'onGetGroupParticipants',
        'onGetGroups',
        'onGetGroupsInfo',
        'onGetGroupsSubject',
        'onGetImage',
        'onGetGroupImage',
        'onGetLocation',
        'onGetMessage',
        'onGetNormalizedJid',
        'onGetPrivacyBlockedList',
        'onGetProfilePicture',
        'onGetReceipt',
        'onGetServerProperties',
        'onGetServicePricing',
        'onGetStatus',
        'onGetSyncResult',
        'onGetVideo',
        'onGetGroupVideo',
        'onGetGroupV2Info',
        'public',
        'onGetvCard',
        'onGroupCreate',
        'onGroupisCreated',
        'onGroupsChatCreate',
        'onGroupsChatEnd',
        'onGroupsParticipantsAdd',
        'onGroupsParticipantsRemove',
        'onLoginFailed',
        'onLoginSuccess',
        'onMediaMessageSent',
        'onMediaUploadFailed',
        'onMessageComposing',
        'onMessagePaused',
        'onMessageReceivedClient',
        'onMessageReceivedServer',
        'onPaidAccount',
        'onPaymentRecieved',
        'onPing',
        'onPresenceAvailable',
        'onPresenceUnavailable',
        'onProfilePictureChanged',
        'onProfilePictureDeleted',
        'onSendMessage',
        'onSendMessageReceived',
        'onSendPong',
        'onSendPresence',
        'onSendStatusUpdate',
        'onStreamError',
        'onUploadFile',
        'onUploadFileFailed',
    ],
];