<?php
require_once 'API.php';

class MyAPI extends API
{
    protected $User;

    public function __construct($request, $origin)
    {
        parent::__construct($request);

    }

    /**
     * Example of an Endpoint
     */
    protected function login()
    {
        if ($this->method == 'POST') {
            require_once 'User.php';
            $res = array('status' => false, 'message' => 'Data error');

            if (isset($_POST['username']) && isset($_POST['password'])) {
                $username = $_POST['username'];
                $password = $_POST['password'];
                $user = new User($username, $password);

                if ($user->login()) {
                    $res['status'] = true;
                    $res['message'] = 'Login completed';
                    $res['token'] = $user->getToken();
                }
            } else {
                $res['message'] = 'Check again username and password';
            }
            return $res;
        } else {
            return "Only accepts POST requests";
        }
    }

    protected function google()
    {
        if ($this->method == 'GET') {
            require_once 'libs/Google-Plus/gpConfig.php';
            switch ($this->verb) {
                case 'link':
                    return $gClient->createAuthUrl();
                case 'callback':
                    if (isset($_GET['code'])) {
                        $gClient->authenticate($_GET['code']);
                        $access_token = $gClient->getAccessToken();
                        $access_token = json_decode($access_token, true);
                        $token = $access_token['access_token'];
                        $gUser = $google_oauthV2->userinfo->get();
                        require_once 'User.php';
                        $user = new User();

                        $username = $gUser['id'];
                        $displayname = $gUser['name'];

                        $password = $token;

                        $birthday = 0;
                        $email = $gUser['email'];

                        //check current
                        if ($user->hasUser(array('username' => $username))) {
                            //update
                            $user->getUser(array('username' => $username), true);
                            $user->user->token = $token;
                            if ($user->save()) {
                                return $token;
                            }
                        } else {
                            $user->register($username, $displayname, $password, $birthday, $email, User::TYPE_G_PLUS, $token);
                        }

                        return $token;
                    }
            }
        }
    }

    protected function facebook()
    {
        require_once 'libs/Facebook/autoload.php';
        if ($this->method == 'GET') {
            switch ($this->verb) {
                case 'link':
                    $fb = new \Facebook\Facebook([
                        'app_id' => Constants::FB_APP_ID, // Replace {app-id} with your app id
                        'default_graph_version' => Constants::FB_APP_V,
                        'app_secret' => Constants::FB_APP_SERECT,
                    ]);

                    $helper = $fb->getRedirectLoginHelper();

                    $permissions = ['email', 'public_profile']; // Optional permissions
                    $callbackUrl = Constants::HOME_URL . '/api/v1/facebook/callback';
                    $loginUrl = $helper->getLoginUrl($callbackUrl, $permissions);

                    return $loginUrl;
                case 'callback':
                    $fb = new Facebook\Facebook([
                        'app_id' => Constants::FB_APP_ID, // Replace {app-id} with your app id
                        'default_graph_version' => Constants::FB_APP_V,
                        'app_secret' => Constants::FB_APP_SERECT,
                    ]);

                    $helper = $fb->getRedirectLoginHelper();
                    if (isset($_GET['state'])) {
                        $helper->getPersistentDataHandler()->set('state', $_GET['state']);
                    }
                    try {
                        $accessToken = $helper->getAccessToken();
                    } catch (Facebook\Exceptions\FacebookResponseException $e) {
                        // When Graph returns an error
                        echo 'Graph returned an error: ' . $e->getMessage();
                        exit;
                    } catch (Facebook\Exceptions\FacebookSDKException $e) {
                        // When validation fails or other local issues
                        echo 'Facebook SDK returned an error: ' . $e->getMessage();
                        exit;
                    }

                    if (!isset($accessToken)) {
                        if ($helper->getError()) {
                            header('HTTP/1.0 401 Unauthorized');
                            echo "Error: " . $helper->getError() . "\n";
                            echo "Error Code: " . $helper->getErrorCode() . "\n";
                            echo "Error Reason: " . $helper->getErrorReason() . "\n";
                            echo "Error Description: " . $helper->getErrorDescription() . "\n";
                        } else {
                            header('HTTP/1.0 400 Bad Request');
                            echo 'Bad request';
                        }
                        exit;
                    }

                    $oAuth2Client = $fb->getOAuth2Client();
                    $tokenMetadata = $oAuth2Client->debugToken($accessToken);
                    $tokenMetadata->validateAppId(Constants::FB_APP_ID); // Replace {app-id} with your app id
                    $tokenMetadata->validateExpiration();

                    try {
                        // Returns a `Facebook\FacebookResponse` object
                        $response = $fb->get('/me?fields=id,name', $accessToken->getValue());
                    } catch (Facebook\Exceptions\FacebookResponseException $e) {
                        echo 'Graph returned an error: ' . $e->getMessage();
                        exit;
                    } catch (Facebook\Exceptions\FacebookSDKException $e) {
                        echo 'Facebook SDK returned an error: ' . $e->getMessage();
                        exit;
                    }

                    $userFb = $response->getGraphUser();

                    require_once 'User.php';
                    $user = new User();
                    $username = $userFb->getId();
                    $displayname = $userFb->getName();
                    $token = $accessToken->getValue();
                    $password = $token;
                    $birthday = $userFb->getBirthday();
                    $email = $userFb->getEmail();
                    //check current
                    if ($user->hasUser(array('username' => $username))) {
                        //update
                        $user->getUser(array('username' => $username), true);
                        $user->user->token = $token;
                        if ($user->save()) {
                            return $token;
                        }
                    } else {
                        $user->register($username, $displayname, $password, $birthday, $email, User::TYPE_FACEBOOK, $token);
                    }

                    return $accessToken->getValue();
            }

        }
        return '';
    }

    protected function register()
    {
        $response = Constants::RESULT;
        if ($this->method == 'POST') {
            require_once 'User.php';
            if (isset($_POST['username'])
                && isset($_POST['password'])
                && isset($_POST['birthday'])
                && isset($_POST['email'])
                && isset($_POST['displayname'])
            ) {
                $username = $_POST['username'];
                $displayname = $_POST['displayname'];
                $password = $_POST['password'];
                $birthday = $_POST['birthday'];
                $email = $_POST['email'];
                $type = User::TYPE;
                $user = new User();

                $result = $user->register($username, $displayname, $password, $birthday, $email, $type);
                if ($result == 1) {
                    $response['status'] = true;
                    $response['message'] = Constants::MSS_CREATED;
                    $response['token'] = $user->getToken();
                } else if ($result == -1) {
                    $response['message'] = Constants::MSS_DUPPLICATION_KEY;
                }
            } else {
                $response['message'] = Constants::MSS_MISS_PARAMS;
            }
        } else {
            $response['message'] = "Only accepts POST requests";
        }
        return $response;
    }

    protected function product()
    {
        require_once 'Product.php';
        $product = new Product();

        switch ($this->method) {
            case 'GET':
                //get information product -> /api/v1/product/:id [GET]
                if ( isset($_GET['id']) ) {
                    $id = $_GET['id'];
                    return $product->getProduct($id);
                } else {
                    return $product->getAllProduct();
                }
            case 'POST':
                $response = Constants::RESULT;

                //create new service -> /api/v1/service/create [POST]
                require_once 'User.php';

                if (!Helper::checkPerfomance(User::isAdmin)) {
                    $response['message'] = Constants::MSS_NOT_PERFORMANCE;
                    return $response;
                }

                if ($this->verb=='create') {
                    if (isset($_POST['title'])
                        && isset($_POST['description'])
                        && isset($_POST['content'])
                        && isset($_POST['price'])
                        && isset($_POST['author'])
                        && isset($_POST['producer'])
                        && isset($_POST['attachment'])
                    ) {
                        $title = $product->escape($_POST['title']);
                        $description = $_POST['description'];
                        $content = $_POST['content'];
                        $price = floatval($_POST['price']);
                        $author = $_POST['author'];
                        $producer = $_POST['producer'];
                        $attacment = $_POST['attachment'];

                        $id = $product->create($title, $description, $content, $price, $author, $producer, $attacment);

                        if ($id) {
                            $response['error'] = false;
                            $response['message'] = Constants::MSS_CREATED;
                            $response['id'] = $id;
                        } else {
                            $response['message'] = Constants::MSS_API_NOTWORK;
                        }
                    } else {
                        $response['message'] = Constants::MSS_MISS_PARAMS;
                    }
                    return $response;
                }
            case 'DELETE':
                $response = Constants::RESULT;

                //update service -> /api/v1/service/create [POST]
                require_once 'User.php';

                if (!Helper::checkPerfomance(User::isAdmin)) {
                    $response['message'] = Constants::MSS_NOT_PERFORMANCE;
                    return $response;
                }

                if ($this->verb=='delete') {
                    if ( isset($_GET['id'])) {
                        $id = intval($_GET['id']);
                        if ($id) {
                            $product->getService($id);
                            $result = $product->remove();
                            if ($result) {
                                $response['error'] = false;
                                $response['message'] = Constants::MSS_DELETE;
                            }
                        } else {
                            $response['message'] = Constants::MSS_API_NOTWORK;
                        }
                    } else {
                        $response['message'] = Constants::MSS_MISS_PARAMS;
                    }
                    return $response;
                }
            case 'PUT':
                $response = Constants::RESULT;

                //update service -> /api/v1/service/create [POST]
                require_once 'User.php';

                if (!Helper::checkPerfomance(User::isAdmin)) {
                    $response['message'] = Constants::MSS_NOT_PERFORMANCE;
                    return $response;
                }

                if ($this->verb=='update') {
                    if ( isset($_GET['id']) && !empty($this->file) ) {
                        $id = intval($_GET['id']);
                        $params = json_decode($this->file);
                        if ($id) {
                            $product->getProduct($id);
                            if ($product->isExists()) {
                                if (!empty($params)) {
                                    foreach ($params as $key => $value) {
                                        $product->{$key} = $value;
                                    }
                                }
                                $result = $product->save();
                                if ($result) {
                                    $response['error'] = false;
                                    $response['message'] = Constants::MSS_UPDATED;
                                    $response['data'] = $product->product;
                                }
                            }
                        } else {
                            $response['message'] = Constants::MSS_API_NOTWORK;
                        }
                    } else {
                        $response['message'] = Constants::MSS_MISS_PARAMS;
                    }
                    return $response;
                }
            default:
        }
        return Constants::MSS_NOT_SUPPORT;
    }

    public function attachment()
    {
        require_once 'Attachment.php';
        if ($this->verb == 'upload') {
            if (!empty($_FILES)) {
                foreach ($_FILES as $filename => $file) {
                    $attachment = new Attachment();
                    $attachment_id = $attachment->upload($file);
                    if ($attachment_id) {
                        return $attachment_id;
                    }
                    return false;
                }
            }
            return '';
        } else {
            if ($this->method == 'GET') {
                if (isset($_GET['id']) && $_GET['id']) {
                    $id = $_GET['id'];
                    $attachment = new Attachment();
                    $attachment->getAttachmentById($id);
                    $dataAttachment = $attachment->attachment;
                    $dataAttachment->fullUrl = $attachment->getUrl();
                    return $dataAttachment;
                }
            }
        }

        return 'Method not support';
    }

    protected function service()
    {
        require_once 'Service.php';
        $service = new Service();

        switch ($this->method) {
            case 'GET':
                //get information service -> /api/v1/service/:id [GET]
                if ( isset($_GET['id']) ) {
                    $id = $_GET['id'];
                    return $service->getService($id);
                } else {
                    return $service->getAllService();
                }
            case 'POST':
                $response = Constants::RESULT;

                //create new service -> /api/v1/service/create [POST]
                require_once 'User.php';

                if (!Helper::checkPerfomance(User::isAdmin)) {
                    $response['message'] = Constants::MSS_NOT_PERFORMANCE;
                    return $response;
                }

                if ($this->verb=='create') {
                    if (isset($_POST['title']) && isset($_POST['price']) && isset($_POST['attachment'])) {
                        $title = $service->escape($_POST['title']);
                        $price = floatval($_POST['price']);
                        $attacment = $_POST['attachment'];
                        $id = $service->create($title, $price, $attacment);
                        if ($id) {
                            $response['error'] = false;
                            $response['message'] = Constants::MSS_CREATED;
                            $response['id'] = $id;
                        } else {
                            $response['message'] = Constants::MSS_API_NOTWORK;
                        }
                    } else {
                        $response['message'] = Constants::MSS_MISS_PARAMS;
                    }
                    return $response;
                }
            case 'DELETE':
                $response = Constants::RESULT;

                //update service -> /api/v1/service/create [POST]
                require_once 'User.php';

                if (!Helper::checkPerfomance(User::isAdmin)) {
                    $response['message'] = Constants::MSS_NOT_PERFORMANCE;
                    return $response;
                }

                if ($this->verb=='delete') {
                    if ( isset($_GET['id'])) {
                        $id = intval($_GET['id']);
                        if ($id) {
                            $service->getService($id);
                            $result = $service->remove();
                            if ($result) {
                                $response['error'] = false;
                                $response['message'] = Constants::MSS_DELETE;
                            }
                        } else {
                            $response['message'] = Constants::MSS_API_NOTWORK;
                        }
                    } else {
                        $response['message'] = Constants::MSS_MISS_PARAMS;
                    }
                    return $response;
                }
            case 'PUT':
                $response = Constants::RESULT;

                //update service -> /api/v1/service/create [POST]
                require_once 'User.php';

                if (!Helper::checkPerfomance(User::isAdmin)) {
                    $response['message'] = Constants::MSS_NOT_PERFORMANCE;
                    return $response;
                }

                if ($this->verb=='update') {
                    if ( isset($_GET['id']) && !empty($this->file) ) {
                        $id = intval($_GET['id']);
                        $params = json_decode($this->file);
                        if ($id) {
                            $service->getService($id);
                            if ($service->isExists()) {
                                if (!empty($params)) {
                                    foreach ($params as $key => $value) {
                                        $service->{$key} = $value;
                                    }
                                }
                                $result = $service->save();
                                if ($result) {
                                    $response['error'] = false;
                                    $response['message'] = Constants::MSS_UPDATED;
                                    $response['data'] = $service->service;
                                }
                            }
                        } else {
                            $response['message'] = Constants::MSS_API_NOTWORK;
                        }
                    } else {
                        $response['message'] = Constants::MSS_MISS_PARAMS;
                    }
                    return $response;
                }
            default:
        }
        return Constants::MSS_NOT_SUPPORT;

    }
}