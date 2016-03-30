<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'console\controllers',
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
		'facebook'=>array(
			'class' => '\YiiFacebook\SFacebook',
			'appId'=>'YOUR_FACEBOOK_APP_ID', // needed for JS SDK, Social Plugins and PHP SDK
			'secret'=>'YOUR_FACEBOOK_APP_SECRET', // needed for the PHP SDK
			//'version'=>'v2.2', // Facebook APi version to default to
			//'locale'=>'en_US', // override locale setting (defaults to en_US)
			//'jsSdk'=>true, // don't include JS SDK
			//'async'=>true, // load JS SDK asynchronously
			//'jsCallback'=>false, // declare if you are going to be inserting any JS callbacks to the async JS SDK loader
			//'callbackScripts'=>'', // default JS SDK init callback JavaScript
			//'status'=>true, // JS SDK - check login status
			//'cookie'=>true, // JS SDK - enable cookies to allow the server to access the session
			//'xfbml'=>true,  // JS SDK - parse XFBML / html5 Social Plugins
			//'frictionlessRequests'=>true, // JS SDK - enable frictionless requests for request dialogs
			//'hideFlashCallback'=>null, // JS SDK - A function that is called whenever it is necessary to hide Adobe Flash objects on a page.
			//'html5'=>true,  // use html5 Social Plugins instead ofolder XFBML
			//'defaultScope'=>array(), // default Facebook Login permissions to request
			//'redirectUrl'=>null, // default Facebook post-Login redirect URL
			//'expiredSessionCallback'=>null, // PHP callable method to run if expired Facebook session is detected
			//'userFbidAttribute'=>null, // if using SFacebookAuthBehavior, declare Facebook ID attribute on user model here
			//'accountLinkUrl'=>null, // if using SFacebookAuthBehavior, declare link to user account page here
			//'ogTags'=>array(  // set default OG tags
				//'og:title'=>'MY_WEBSITE_NAME',
				//'og:description'=>'MY_WEBSITE_DESCRIPTION',
				//'og:image'=>'URL_TO_WEBSITE_LOGO',
			//),
		),
    ],
    'modules'             => [
        'CronService'   => [
            'class'      => 'console\modules\cron\CronModule',
            'active'     => true,
            'cronserver' => false,
        ],
	],
    'params' => $params,
];
