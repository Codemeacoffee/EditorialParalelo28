<?php

namespace Paralelo28\Http\Controllers;

include_once base_path().'/vendor/redsysHMAC256_API_PHP_7.0.0/apiRedsys.php';
                
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\File;
use Paralelo28\BlogEntry;
use Paralelo28\Book;
use Paralelo28\GlobalStatistic;
use Paralelo28\Image;
use Paralelo28\Library;
use Paralelo28\PasswordReset;
use Paralelo28\Page;
use Paralelo28\Sale;
use Paralelo28\ShoppingHistory;
use Paralelo28\Statistic;
use Paralelo28\Subscriber;
use Paralelo28\SurveyAnswer;
use Paralelo28\SurveyPossibleAnswer;
use Paralelo28\SurveyQuestion;
use Paralelo28\PersonalSurvey;
use Paralelo28\RefundTicket;
use Paralelo28\User;
use Paralelo28\UserSetting;
use Paralelo28\WishList;
use Paralelo28\Order;
use Paralelo28\Coupon;
use Paralelo28\CouponRedeemed;
use Paralelo28\Ip;
use Paralelo28\CronJob;
use RedsysApi;
use Exception;

class UserController extends RootController
{

    /*---------------------------------------- A C C O U N T   C R E A T I O N ----------------------------------------*/

    function createAccount(Request $request){
        $email = htmlspecialchars($request['email']);
        $password = htmlspecialchars($request['password']);
        $passwordRepeat = htmlspecialchars($request['passwordRepeat']);

        if(!preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^",$email)){
            return Redirect::back()->withErrors('El email es inválido.');
        }else if(strlen($password) < 6){
            return Redirect::back()->withErrors('La contraseña ha de tener al menos 6 carácteres.');
        }else if($password != $passwordRepeat){
            return Redirect::back()->withErrors('Las contraseñas no coinciden.');
        }
        
        if(strlen($email) > 100) return Redirect::back()->withErrors('El correo es demasiado largo.');

        $user = User::where('email', $email)->first();

        if($user) return Redirect::back()->withErrors('El correo introducido ya está registrado.');

        try{
            $confirmationEmailUrl = bin2hex(random_bytes(mt_rand(25, 50)));
        }catch(Exception $e){
            $confirmationEmailUrl = $this->generateRandomString(mt_rand(50, 100));
        }

        $name = mb_strimwidth(explode('@', $email)[0], 0, 25);

        try{
            $data = [
                "email" => $email,
                "name" => $name,
                "confirmationEmailUrl" => $confirmationEmailUrl
            ];

            Mail::send('emails.confirmationEmail', $data, function($message) use ($data) {
                $message->to($data['email'], $data['name'])->subject('Activa tu cuenta');
                $message->from('noreply@editorialparalelo28.com', 'EditorialParalelo28');
            });
        }catch (Exception $e){
            return Redirect::back()->withErrors('Su petición no pudo ser atendida en este momento.');
        }

        $user = User::create([
            'email' => $email,
            'password' => bcrypt($password),
            'email_verify' => $confirmationEmailUrl,
        ]);

        UserSetting::create([
            'userId' => $user['id'],
            'name' => $name,
        ]);

        return Redirect::back()->with('confirmationEmail', $email);
    }

    /*---------------------------------------- L O G I N ----------------------------------------*/

    function login(Request $request){
        $visitorIp = $this->getIP();

        $ip = Ip::where('ip', $visitorIp)->first();

        if($ip) {
            $lastUpdate = date('Y-m-d h:i:s', strtotime($ip['updated_at'] . '+15 minutes'));
            $now = date('Y-m-d h:i:s');

            if (strtotime($now) < strtotime($lastUpdate)){
                if($ip['tries'] == 6) return Redirect::to('temporaryBlock');
            }else{
                $ip['tries'] = 0;
                $ip->save();
            }
        }

        $email = htmlspecialchars($request['email']);
        $password = htmlspecialchars($request['password']);

        $user = User::where('email', $email)->first();

        if(!$user){
            return Redirect::back()->withErrors('Email o contraseña incorrectos.');
        }

        if (Hash::check($password, $user['password'])) {
            if($user['email_verify'] != null){
                return Redirect::back()->with('notConfirmed', $user['email']);
            }

            $userSettings = UserSetting::where('userId', $user['id'])->first();

            switch($userSettings['session_expires']){
                case 0:
                    $startDate = time();
                    $expireDate = date('Y-m-d H:i:s', strtotime('+5 minutes', $startDate));
                    break;
                case 1:
                    $startDate = time();
                    $expireDate = date('Y-m-d H:i:s', strtotime('+15 minutes', $startDate));
                    break;
                case 2:
                    $startDate = time();
                    $expireDate = date('Y-m-d H:i:s', strtotime('+30 minutes', $startDate));
                    break;
                default:
                    $expireDate = null;
            }

            $user->session_expire_date = $expireDate;

            try{
                $sessionToken = bin2hex(random_bytes(mt_rand(10, 25)));
            }catch(Exception $e){
                $sessionToken = $this->generateRandomString(mt_rand(20, 50));
            }

            $user->session_token = $sessionToken;
            $user->save();

            switch($userSettings['remember_me']){
                case 0:
                    $userCookie = cookie('user', $user->email, 0);
                    $sessionCookie = cookie('sessionToken', $sessionToken, 0);
                    break;
                case 1:
                    $userCookie = cookie('user', $user->email, 1440);
                    $sessionCookie = cookie('sessionToken', $sessionToken, 1440);
                    break;
                case 2:
                    $userCookie = cookie('user', $user->email, 10080);
                    $sessionCookie = cookie('sessionToken', $sessionToken, 10080);
                    break;
                default:
                    $userCookie = cookie()->forever('user', $user->email);
                    $sessionCookie = cookie()->forever('sessionToken', $sessionToken);
            }

            return redirect('home')
                ->withCookie($userCookie)
                ->withCookie($sessionCookie);
        }else{
            if($ip){
                $lastUpdate = date('Y-m-d h:i:s', strtotime($ip['updated_at'].'+5 minutes'));
                $now = date('Y-m-d h:i:s');

                if(strtotime($now) < strtotime($lastUpdate)) $ip['tries'] = $ip['tries'] + 1;
                else $ip['tries'] = 1;

                $ip->save();

                if($ip['tries'] == 6){
                    try{
                        $userSettings = UserSetting::where('userId', $user['id'])->first();

                        $data = [
                            "email" => $user['email'],
                            "name" => $userSettings['name'],
                            "date" => date('d-m-Y')
                        ];

                        Mail::send('emails.accessWarning', $data, function($message) use ($data) {
                            $message->to($data['email'], $data['name'])->subject('Actividad sospechosa en tu cuenta');
                            $message->from('noreply@editorialparalelo28.com', 'EditorialParalelo28');
                        });
                    }catch (Exception $e){
                        unset($e);
                    }
                    return Redirect::to('temporaryBlock');
                }

                if($ip['tries'] >= 5)  return Redirect::back()->withErrors('Ha introducido una contraseña erronea repetidas veces,
                 si ha olvidado su contraseña utilize la opción "¿Olvidaste tu contraseña?" para evitar recibir un bloqueo temporal.');

                }else{
                    Ip::create([
                       'ip' =>  $visitorIp,
                        'tries' => 1
                    ]);
                }
            return Redirect::back()->withErrors('Email o contraseña incorrectos.');
        }
    }

     function home(Request $request){
        $user = $this->validateUser($request->cookie('user'), $request->cookie('sessionToken'));

        if($user){
            $userSettings = UserSetting::where('userId', $user['id'])->first();

            $userLibrary = Library::where('userId', $user['id'])->where('option', '!=', 0)->orderBy('created_at', 'desc')->get();
            $userWishList = WishList::where('userId', $user['id'])->get();
            $userShoppingHistory = ShoppingHistory::where('userId', $user['id'])->orderBy('created_at', 'DESC')->get();

            $userBooks = [];
            $userWishes = [];
            $userBookHistory = [];

            foreach ($userShoppingHistory as $shoppingHistory){
                $sales = Sale::where('shipmentCode', $shoppingHistory['shipmentCode'])->get();

                foreach ($sales as $sale){
                    $book = Book::where('id', $sale['bookId'])->first();

                    if($book) $sale['title'] = $book['title'];
                    else continue;

                    array_push($userBookHistory, $sale);
                }
            }

            foreach ($userLibrary as $relation){
                $book = Book::where('id', $relation['bookId'])->first();
                $book['category'] = $this->getCategory($book);
                array_push($userBooks, $book);
            }

            foreach ($userWishList as $relation){
                $book = Book::where('id', $relation['bookId'])->first();
                $book['category'] = $this->getCategory($book);
                array_push($userWishes, $book);
            }

            $sendShipments = ShoppingHistory::where('status', 'Enviado')->where('userId', $user['id'])->get();

            $buyRemember =[];

            foreach ($sendShipments as $sendShipment) array_push($buyRemember, $sendShipment['shipmentCode']);

            return $this->viewDispatcher('home', $request, [$userBooks, $userWishes, [$userShoppingHistory, $userBookHistory], $userSettings, $this->getPreviewImages(), $buyRemember]);
        }else{
            return redirect('/');
        }
    }

    /*---------------------------------------- C O N F I R M A T I O N   E M A I L ----------------------------------------*/

    function confirmEmail($parameter = null){
        if(!$parameter) return abort(404);

        $parameter = htmlspecialchars($parameter);

        $user = User::where('email_verify', $parameter)->first();

        if(!$user){
            return abort(404);
        }else{
            try{
                $sessionToken = bin2hex(random_bytes(mt_rand(10, 25)));
            }catch(Exception $e){
                $sessionToken = $this->generateRandomString(mt_rand(20, 50));
            }

            $user->email_verify = null;
            $user->email_verify_date = date("Y-m-d H:i:s", time());
            $user->session_token = $sessionToken;
            $user->save();

            $userCookie = cookie()->forever('user', $user->email);
            $sessionCookie = cookie()->forever('sessionToken', $sessionToken);

             return redirect('home')
                 ->withCookie($userCookie)
                 ->withCookie($sessionCookie);
       }
    }

    function resendConfirmationEmail($parameter = null){
        if(!$parameter) return abort(404);

        $parameter = htmlspecialchars($parameter);

        $user = User::where('email', $parameter)->first();

        if(!$user) return abort(404);

        if($user['email_verify'] != null){
            if($user['created_at'] != $user['updated_at']){
                $remainingTime = strtotime($user['updated_at']) - strtotime(date('Y-m-d h:i:s')) + 3600;
                if(strtotime($user['updated_at'])+3600 > time()) return Redirect::back()->with('retryEmailTime', $remainingTime)->with('retryEmail', $user['email']);
            }

            try{
                $confirmationEmailUrl = bin2hex(random_bytes(mt_rand(25, 50)));
            }catch(Exception $e){
                $confirmationEmailUrl = $this->generateRandomString(mt_rand(50, 100));
            }

            try{
                $data = [
                    "email" => $parameter,
                    "name" => explode('@', $parameter)[0],
                    "confirmationEmailUrl" => $confirmationEmailUrl
                ];

                Mail::send('emails.confirmationEmail', $data, function($message) use ($data) {
                    $message->to($data['email'], $data['name'])->subject('Activa tu cuenta');
                    $message->from('noreply@editorialparalelo28.com', 'EditorialParalelo28');
                });

            }catch (Exception $e){
                return Redirect::back()->withErrors('Su petición no pudo ser atendida en este momento.');
            }

            $user->email_verify = $confirmationEmailUrl;
            $user->save();

            return Redirect::back()->with('resendEmail', $parameter);
        }else{
            return abort(404);
        }
    }
    
     /*---------------------------------------- P A S S W O R D   R E S E T ----------------------------------------*/

    function resetPassword($parameter = null){
        $email = htmlspecialchars($parameter);

        if(!$email || strlen($email) < 1) return Redirect::back()->withErrors('Para restaurar su contraseña debe rellenar el campo "email".');

        $user = User::where('email', $email)->first();

        if($user){
            try{
                $passwordResetToken = bin2hex(random_bytes(mt_rand(25, 50)));
            }catch(Exception $e){
                $passwordResetToken = $this->generateRandomString(mt_rand(50, 100));
            }

            try{
                $userSettings = UserSetting::where('userId', $user['id'])->first();

                $data = [
                    "email" => $user['email'],
                    "name" => $userSettings['name'],
                    "passwordResetUrl" => $passwordResetToken
                ];

                Mail::send('emails.passwordReset', $data, function($message) use ($data) {
                    $message->to($data['email'], $data['name'])->subject('Olvidaste tu contraseña');
                    $message->from('noreply@editorialparalelo28.com', 'EditorialParalelo28');
                });
            }catch (Exception $e){
                return Redirect::back()->withErrors('Su petición no pudo ser atendida en este momento.');
            }

            PasswordReset::create([
                'email' => $user['email'],
                'token' => $passwordResetToken,
                'created_at' => date('Y-m-d h:i:s')
            ]);

            return Redirect::back()->with('successMessage', 'Hemos enviado un correo ha "'.$user['email'].'", recuerda que este correo caduca en 24 horas.');

        }else return Redirect::back()->withErrors('No existe una cuenta con el correo "'.$email.'".');
    }

    function validatePasswordReset($parameter){
        $token = htmlspecialchars($parameter);

        $passwordReset = PasswordReset::where('token', $token)->first();

        if($passwordReset){
            $expireDate = date('Y-m-d h:i:s', strtotime($passwordReset['created_at'].' +1 day'));
            $now = date('Y-m-d h:i:s');

           if(strtotime($expireDate) > strtotime($now)){
               $user = User::where('email', $passwordReset['email'])->first();
               $userSettings = UserSetting::where('userId', $user['id'])->first();

               if($user){
                   return $this->viewDispatcher('passwordReset', new Request(), ['user' => $userSettings['name'], 'token' => $passwordReset['token']]);
               }
           }
           else return Redirect::to('/')->withErrors('El reinicio de contraseña ha caducado, intentelo de nuevo. Recuerde que, por su seguridad, los reinicios de contraseña caducan en 24 horas.');
        }
        return abort(404);
    }

    function executePasswordReset(Request $request){
        $password = htmlspecialchars($request['password']);
        $passwordRepeat = htmlspecialchars($request['passwordRepeat']);
        $token = htmlspecialchars($request['token']);

        $passwordReset = PasswordReset::where('token', $token)->first();

        if($passwordReset){
            $expireDate = date('Y-m-d h:i:s', strtotime($passwordReset['created_at'].' +1 day'));
            $now = date('Y-m-d h:i:s');

            if(strtotime($expireDate) > strtotime($now)){
                $user = User::where('email', $passwordReset['email'])->first();

                if($user){
                    if(strlen($password) < 6){
                        return Redirect::back()->withErrors('La contraseña ha de tener al menos 6 carácteres.');
                    }else if($password != $passwordRepeat){
                        return Redirect::back()->withErrors('Las contraseñas no coinciden.');
                    }

                    $user['password'] = bcrypt($password);
                    $user->save();

                    $passwordReset->delete();

                    return Redirect::to('/')->with('successMessage', 'Su contraseña ha sido modificada con éxito.');
                }
            }
            else return Redirect::to('/')->withErrors('El reinicio de contraseña ha caducado, intentelo de nuevo. Recuerde que, por su seguridad, los reinicios de contraseña caducan en 24 horas.');
        }
        return abort(404);
    }
    
    /*---------------------------------------- U S E R   A C C E S S   B L O C K E D ----------------------------------------*/

    function accessBlocked(Request $request){
        $visitorIp = $this->getIP();

        $ip = Ip::where('ip', $visitorIp)->first();


        if($ip) {
            $lastUpdate = date('Y-m-d h:i:s', strtotime($ip['updated_at'] . '+15 minutes'));
            $now = date('Y-m-d h:i:s');

            if (strtotime($now) < strtotime($lastUpdate)){
                if($ip['tries'] == 6){
                    $remainingTime = strtotime($lastUpdate) - strtotime($now);
                    return $this->viewDispatcher('temporaryBlock', $request, $remainingTime);
                }
            }else{
                $ip['tries'] = 0;
                $ip->save();
            }
        }
        return abort(404);
    }
    
    /*---------------------------------------- U S E R   S E T T I N G S ----------------------------------------*/

    function editAccountInfo(Request $request){
        $user = $this->validateUser($request->cookie('user'), $request->cookie('sessionToken'));

        if($user){
            $userSettings = UserSetting::where('userId', $user['id'])->first();

            if($request['userName']) $userSettings->name = mb_strimwidth(htmlspecialchars($request['userName']), 0, 25);
            if($request['userSurname']) $userSettings->surnames = mb_strimwidth(htmlspecialchars($request['userSurname']), 0, 100);
            if($request['userEmail']){
                $email = htmlspecialchars($request['userEmail']);
                
                if(strlen($email) > 100) return Redirect::back()->withErrors('El correo es demasiado largo.');

                if(preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $email)){
                    $emailCoincident = User::where('email', $email)->first();

                    if(!$emailCoincident || $emailCoincident != $user){
                        if($emailCoincident && $emailCoincident != $user) return Redirect::back()->withErrors('El correo introducido ya está registrado.');

                        $user->email = $email;
                        $emailChanged = true;
                    }
                }else{
                    return Redirect::back()->withErrors('El email es inválido.');
                }
            }

            if($request['oldPass'] && $request['newPass'] && $request['newPassConfirm']){
                if(Hash::check(htmlspecialchars($request['oldPass']), $user['password'])) {
                    $newPass = htmlspecialchars($request['newPass']);
                    if(strlen($newPass) > 5){
                        if($newPass == htmlspecialchars($request['newPassConfirm'])){
                            $user->password = bcrypt($newPass);
                        }else{
                            return Redirect::back()->withErrors('Las contraseñas no coinciden.');
                        }
                    }else{
                        return Redirect::back()->withErrors('La contraseña ha de tener al menos 6 carácteres.');
                    }
                }else{
                    return Redirect::back()->withErrors('La contraseña es incorrecta.');
                }
            }

            $userSettings->save();

            if(isset($emailChanged)){
                try{
                    $confirmationEmailUrl = bin2hex(random_bytes(mt_rand(25, 50)));
                }catch(Exception $e){
                    $confirmationEmailUrl = $this->generateRandomString(mt_rand(50, 100));
                }

                try{
                    $data = [
                        "email" => $user->email,
                        "name" => $userSettings->name,
                        "confirmationEmailUrl" =>  public_path().'\\confirmationEmail\\'.$confirmationEmailUrl
                    ];

                    Mail::send('emails.confirmationEmail', $data, function($message) use ($data) {
                        $message->to($data['email'], $data['name'])->subject('Activa tu cuenta');
                        $message->from('noreply@editorialparalelo28.com', 'EditorialParalelo28');
                    });
                }catch (Exception $e){
                    return Redirect::back()->withErrors('Su petición no pudo ser atendida en este momento.');
                }

                $user->email_verify = $confirmationEmailUrl;
                $user->email_verify_date = null;
                $user->save();

                return redirect('/')->with('emailChanged', $user->email);
            }

            $user->save();

            return Redirect::back()->with('successMessage', 'Su información de cuenta ha sido modificada con éxito.');

        }else{
            return redirect('/');
        }
    }

    function editAccountDirection(Request $request){
        $user = $this->validateUser($request->cookie('user'), $request->cookie('sessionToken'));

        if($user){
            if(strlen(htmlspecialchars($request['direction'])) < 1 || strlen(htmlspecialchars($request['postalCode'])) < 1) return Redirect::back()->withErrors('Lo sentimos, su dirección no ha podido ser modificada.');

            $userSettings = UserSetting::where('userId', $user['id'])->first();

            if($userSettings){
                
                $postalCode = $this->postalCodeParse(htmlspecialchars($request['postalCode']));

                if(intval($postalCode) == 0) return Redirect::back()->withErrors('Lo sentimos, su dirección no ha podido ser modificada.');

                $directionValid = $this->validateDirection(explode('. ', htmlspecialchars($request['direction']))[0], true);
                
                if(!$directionValid){
                    $directionParts = explode('. ', htmlspecialchars($request['direction']));
                    $directionValid =  $this->deepValidateDirection($directionParts[Count($directionParts)-1]);
                } 
                
                if(!$directionValid) return Redirect::back()->withErrors('Ha ocurrido un error inesperado al actualizar sus datos.');

                $userSettings->direction = strip_tags($request['direction']);
                $userSettings->taxes = $this->getTaxesByDirection($userSettings->direction);
                $userSettings->postalCode = $postalCode;
                $userSettings->save();
                return Redirect::back()->with('successMessage', 'Su dirección ha sido modificada con éxito.');
            }else{
                return abort(404);
            }
        }else{
            return redirect('/');
        }
    }
    
    function editAccountType(Request $request){
        $user = $this->validateUser($request->cookie('user'), $request->cookie('sessionToken'));

        if($user) {
            $accountType = htmlspecialchars($request['accountType']);
            $companyName = htmlspecialchars($request['companyName']);
            $companyCIF = htmlspecialchars($request['companyCIF']);

            if($accountType != 0 && $accountType != 1) return Redirect::back()->withErrors('Ha ocurrido un error inesperado al actualizar sus datos.');

            if($accountType == 1){
                if(strlen($companyName) == 0 || strlen($companyCIF) == 0) return Redirect::back()->withErrors('Para las cuentas Empresariales es obligatorio rellenar los campos "Nombre de la Empresa" y "CIF".');
            }

            $user['accountType'] = $accountType;
            $user['companyName'] = $companyName;
            $user['companyCIF'] = $companyCIF;

            $user->save();
            return Redirect::back()->with('successMessage', 'Su información de cuenta ha sido modificada con éxito.');
        }else{
            return redirect('/');
        }
    }
    
    function editDeepSettings(Request $request){
        $user = $this->validateUser($request->cookie('user'), $request->cookie('sessionToken'));

        if($user) {
            $rememberMe = intval(htmlspecialchars($request['rememberMe']));
            $maxInactiveTime = intval(htmlspecialchars($request['maxInactiveTime']));

            if($rememberMe < 0 || $rememberMe > 3 || $maxInactiveTime < 0 || $maxInactiveTime > 3) return Redirect::back()->withErrors('Ha sucedido un error, vuelva a intentarlo más tarde.');

            $userSettings = UserSetting::where('userId', $user['id'])->first();

            $userSettings['session_expires'] = $maxInactiveTime;
            $userSettings['remember_me'] = $rememberMe;
            $userSettings->save();

            return Redirect::back()->with('successMessage', 'Su información de cuenta ha sido modificada con éxito.');
        }else{
            return redirect('/');
        }
    }

    /*---------------------------------------- V I S I T O R   S T A T I S T I C S ----------------------------------------*/

    function visitor(){
        $visited = Cookie::get('visited');

        if($visited) return response('400: Bad Request');

        $lastStatistic = GlobalStatistic::orderBy('visitors', 'DESC')->first();

        if(!$lastStatistic){
            $lastStatistic = 1;
        }else{
            $lastStatistic = $lastStatistic['visitors'] + 1;
        }

        GlobalStatistic::create([
            'visitors' => $lastStatistic
        ]);

        return response('200: Valid Request')->cookie('visited',  date('d/m/y'), 1440);
    }
    
    /*----------------------------------------  S U R V E Y ----------------------------------------*/

    function survey(Request $request){
        $surveyQuestions = SurveyQuestion::where('survey', 0)->orderBy('order', 'ASC')->get();
        $surveyPossibleAnswers = SurveyPossibleAnswer::all();
        $surveyAnswers = SurveyAnswer::where('ip', $this->getIP())->get();
        $toUnset = [];

        for ($i = 0; $i < Count($surveyQuestions); $i++){
            foreach ($surveyAnswers as $surveyAnswer){
                if($surveyQuestions[$i]['question'] == $surveyAnswer['question']) array_push($toUnset, $i);
            }
        }

        foreach ($toUnset as $index){
            unset($surveyQuestions[$index]);
        }

        return $this->viewDispatcher('survey', $request, [$surveyQuestions, $surveyPossibleAnswers]);
    }

    function takeASurvey(Request $request){
        $answers = $request['answers'];

        foreach ($answers as $key => $value){
            $key = htmlspecialchars($key);
            $answer = htmlspecialchars($value['answer']);
            $question = htmlspecialchars($value['question']);

            $surveyQuestion = SurveyQuestion::where('id', $key)->first();

            if($surveyQuestion){
                $isValid = true;
                $isTagged = false;

                if(strpos($surveyQuestion['question'], '*') !== false) $isTagged = true;

                if($surveyQuestion['type'] == 3){
                    $isValid = false;
                    $possibleAnswers = SurveyPossibleAnswer::where('surveyId', $surveyQuestion['id'])->get();

                    foreach ($possibleAnswers as $possibleAnswer){
                        if($answer == $possibleAnswer['possibleAnswer']) $isValid = true;
                    }
                }

                if($isValid){
                    if(!$isTagged) $question = $surveyQuestion['question'];

                    SurveyAnswer::create([
                        'question' => $question,
                        'answer' =>$answer,
                        'ip' => $this->getIP()
                    ]);
                };
            }
        }
        return Redirect::back()->with('successMessage', 'Ha completado con éxito nuestra encuesta de satisfacción, muchas gracias por dedicarnos su tiempo.');
    }
    
    function personalSurvey(Request $request, $parameter){
        $parameter = htmlspecialchars($parameter);

        $personalSurvey = PersonalSurvey::where('surveyUrl', $parameter)->orderBy('id', 'DESC')->first();

        if($personalSurvey){
            $expireDate = date('Y-m-d h:i:s', strtotime($personalSurvey['created_at'] . '+ 7 days'));
            $now = date('Y-m-d h:i:s');

            if(strtotime($now) > strtotime($expireDate)){
                $personalSurvey->delete();
                return Redirect::to('/')->withErrors('Esta encuesta de satisfacción ha expirado.');
            }

            $userSettings = UserSetting::where('userId', $personalSurvey['userId'])->first();
            $shipmentData = Sale::where('shipmentCode', $personalSurvey['shipmentCode'])->get();

            if($userSettings && $shipmentData){
                $surveyQuestions = SurveyQuestion::where('survey', 1)->orderBy('order', 'ASC')->get();
                $surveyPossibleAnswers = SurveyPossibleAnswer::all();
                $surveyAnswers = SurveyAnswer::where('ip', $this->getIP())->get();
                $toUnset = [];
                $bookNames = [];
                $parsedSurveyQuestions = [];
                
                foreach ($shipmentData as $data){
                    $book = Book::where('id', $data['bookId'])->first();

                    if($book) array_push($bookNames, $book['title']);
                }
                
                foreach($surveyQuestions as $surveyQuestion){
                    if(strpos($surveyQuestion['question'], '*') !== false){
                        foreach($bookNames as $bookName){
                            $question = str_replace('*', $bookName, $surveyQuestion['question']);
                            array_push($parsedSurveyQuestions, ['id' => $surveyQuestion['id'], 'question' => $question, 'type' => $surveyQuestion['type']]);
                        }
                    }else{
                        array_push($parsedSurveyQuestions, $surveyQuestion);
                    }
                }
                

                for ($i = 0; $i < Count($parsedSurveyQuestions); $i++){
                    foreach ($surveyAnswers as $surveyAnswer){
                        if($parsedSurveyQuestions[$i]['question'] == $surveyAnswer['question']) array_push($toUnset, $i);
                    }
                }

                foreach ($toUnset as $index){
                    unset($parsedSurveyQuestions[$index]);
                }

                $data = [
                    'name' => $userSettings['name'],
                    'questions' => $parsedSurveyQuestions,
                    'possibleAnswers' => $surveyPossibleAnswers
                ];

                return $this->viewDispatcher('personalSurvey', $request, $data);
            }
        }
        return abort(404);
    }

    
     /*---------------------------------------- S U B S C R I P T I O N ----------------------------------------*/

    function subscription(Request $request){
        if(isset($request['subscribe'])){
            $email = htmlspecialchars($request['mail']);

            if(strlen($email) > 0){
                if(!preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^",$email)){
                    return Redirect::back()->withErrors('El email es inválido.');
                }

                $subscriber = Subscriber::where('email', $email)->first();

                if($subscriber) return Redirect::back()->withErrors('El correo introducido ya está registrado.');

                Subscriber::create([
                    'email' => $email
                ]);

                return Redirect::back()->with('successMessage', 'Se ha suscrito con éxito, ha partir de ahora recibirá correos sobre 
                noticias, promociones y ofertas.');
            }
        }
        return Redirect::back()->withErrors('Debe rellenar todos los campos obligatorios.');
    }

    function cancelSubscription($parameter = null){
        if(!$parameter) return redirect('/');

        $subscriber = Subscriber::where('email', htmlspecialchars($parameter))->first();

        if(!$subscriber) return redirect('/');

        $subscriber->delete();

         return redirect('/')->with('successMessage', 'Ha cancelado su subscripción, recuerde que puede volver a subscribirse en cualquier momento.');
    }


    /*---------------------------------------- P A Y M E N T   G A T E W A Y ----------------------------------------*/

    function paymentGateway(Request $request){
        return 'Temporalmente desactivado';
        $user = $this->validateUser($request->cookie('user'), $request->cookie('sessionToken'));

        if($user){
            $couponDiscount = 0;
            $coupon = null;
            $couponCode = htmlspecialchars($request['coupon']);

            if($couponCode){
                $coupon = Coupon::where('code', $couponCode)->first();

                if($coupon && ($coupon['valid_until'] == null || strtotime($coupon['valid_until'])> strtotime(date('Y-m-d'))) && $coupon['uses']>0){
                    $couponDiscount = $coupon['discount'];
                }else{
                    return Redirect::back()->withErrors('El cupón introducido no es válido.');
                }
            }

            $purchaseData = json_decode($request["purchaseData"]);

            if(Count($purchaseData) < 1) return Redirect::back()->withErrors('Debe añadir al menos 1 artículo a su carrito antes de finalizar su compra.');

            $totalPrice = 0;

            $userSettings = UserSetting::where('userId', $user['id'])->first();

            $physicalTaxPercent = $this->getTaxValue($userSettings->taxes, 0);

            $digitalTaxPercent = $this->getTaxValue($userSettings->taxes, 1);

            $invoice = [];

            foreach ($purchaseData as $purchase){
                $product = [];

                $name = htmlspecialchars($purchase->name);
                $amount = htmlspecialchars($purchase->quant);
                $format = htmlspecialchars($purchase->format);

                $book = Book::where('title', $name)->first();

                if($amount > $book['stock'] && $format == 1){
                    if($book['stock'] == 0)   return Redirect::back()->withErrors('Lo sentimos, actualmente no tenemos stock del siguiente artículo: "'.$book['title'].'".');
                    $product['warning'] = "Actualmente solo disponemos de ".$book['stock']." unidades de este producto.";
                    $amount = $book['stock'];
                }

                if($book){
                    switch($format){
                        case 0:
                            $price = $book['digitalPrice'];
                            $product['format'] = "Digital";
                            break;
                        case 1:
                            $price = $book['physicalPrice'];
                            $product['format'] = "Físico";
                            break;
                        default:
                            return Redirect::back()->withErrors('Lo sentimos, su compra no ha podido ser realizada.');
                    }

                    $discount = $book['discount'];

                    if($product['format'] == "Digital"){
                        $library = Library::where('userId', $user['id'])
                            ->where('bookId', $book['id'])
                            ->where('option', 1)
                            ->first();

                        if($library) return Redirect::back()->withErrors('Ya posee el libro "'.$book['title'].'" en formato digital.');
                        $amount = 1;
                    }

                    $product['name'] = $name;
                    
                    $product['amount'] = $amount;
                    
                    $product['price'] = $price;

                    $productDiscount = ($product['price']*$discount)/100;

                    $product['price'] = $product['price']-$productDiscount;

                    $productCouponDiscount = ($product['price']*$couponDiscount)/100;

                    $product['price'] = $product['price']-$productCouponDiscount;
                    
                    if($product['format'] == "Físico") $taxAmount = ($product['price']*$physicalTaxPercent)/100;
                    else $taxAmount = ($product['price']*$digitalTaxPercent)/100;

                    $product['price'] = number_format($product['price']+ $taxAmount, 2);

                    $product['totalPrice'] = number_format($product['price'] * $amount, 2);

                    $totalPrice += $product['price'] * $amount;

                    array_push($invoice, $product);
                }
            };

            $userData= [$userSettings->name, $userSettings->surnames, $userSettings->direction, $userSettings->postalCode, $userSettings->taxes];

            return Redirect::back()->with('paymentModal', [$invoice, $userData, number_format($totalPrice, 2), $coupon]);

        }else{
            return Redirect::back()->with('loginOpen', true);
        }
    }

    function processPayment(Request $request){
        $visitorIp = $this->getIP();

        $ip = Ip::where('ip', $visitorIp)->first();

        if($ip) {
            $lastUpdate = date('Y-m-d h:i:s', strtotime($ip['updated_at'] . '+15 minutes'));
            $now = date('Y-m-d h:i:s');

            if (strtotime($now) < strtotime($lastUpdate)){
                if($ip['tries'] == 6) return Redirect::to('temporaryBlock');
            }else{
                $ip['tries'] = 0;
                $ip->save();
            }
        }
        
        $user = $this->validateUser($request->cookie('user'), $request->cookie('sessionToken'));

        if($user){
            if(isset($request['acceptTermsAndConditions'])){
              
                //Shipment Address Data

                $shipmentName = htmlspecialchars($request['shipmentName']);
                $shipmentSurnames = htmlspecialchars($request['shipmentSurnames']);
                $shipmentAddress = htmlspecialchars($request['shipmentAddress']);
                $shipmentPostCode = htmlspecialchars($request['shipmentPostCode']);

                //Billing Address Data

                $billingName = htmlspecialchars($request['billingName']);
                $billingSurnames = htmlspecialchars($request['billingSurnames']);
                $billingAddress = htmlspecialchars($request['billingAddress']);
                $billingPostCode = htmlspecialchars($request['billingPostCode']);
                
                //Discounts
                
                $couponDiscount = 0;
                $couponCode = htmlspecialchars($request['coupon']);
                
                if($couponCode){
                    $coupon = Coupon::where('code', $couponCode)->first();
    
                    if($coupon && ($coupon['valid_until'] == null || strtotime($coupon['valid_until'])> strtotime(date('Y-m-d'))) && $coupon['uses']>0){
                        $couponDiscount = $coupon['discount'];
                    }else{
                        return Redirect::back()->withErrors('El cupón introducido no es válido.');
                    }
                }

                //User Data

                $password = htmlspecialchars($request['password']);

                if(!Hash::check($password, $user['password'])){
                    if($ip){
                        $lastUpdate = date('Y-m-d h:i:s', strtotime($ip['updated_at'].'+5 minutes'));
                        $now = date('Y-m-d h:i:s');
        
                        if(strtotime($now) < strtotime($lastUpdate)) $ip['tries'] = $ip['tries'] + 1;
                        else $ip['tries'] = 1;
        
                        $ip->save();
        
                        if($ip['tries'] == 6){
                            try{
                                $userSettings = UserSetting::where('userId', $user['id'])->first();
        
                                $data = [
                                    "email" => $user['email'],
                                    "name" => $userSettings['name'],
                                    "date" => date('d-m-Y')
                                ];
        
                                Mail::send('emails.accessWarning', $data, function($message) use ($data) {
                                    $message->to($data['email'], $data['name'])->subject('Actividad sospechosa en tu cuenta');
                                    $message->from('noreply@editorialparalelo28.com', 'EditorialParalelo28');
                                });
                            }catch (Exception $e){
                                unset($e);
                            }
                            Cookie::queue(Cookie::forget('user'));
                            Cookie::queue(Cookie::forget('sessionToken'));
                            return Redirect::to('temporaryBlock');
                        }
        
                        if($ip['tries'] >= 5)  return Redirect::back()->withErrors('Ha introducido una contraseña erronea repetidas veces,
                         si ha olvidado su contraseña utilize la opción "¿Olvidaste tu contraseña?" para evitar recibir un bloqueo temporal.');
        
                    }else{
                        Ip::create([
                            'ip' =>  $visitorIp,
                            'tries' => 1
                        ]);
                    }
                    return Redirect::back()->withErrors('Ha introducido una contraseña incorrecta.');
                } 
                
                $userSettings = UserSetting::where('userId', $user['id'])->first();

                $physicalTaxPercent = $this->getTaxValue($userSettings->taxes, 0);

                $digitalTaxPercent = $this->getTaxValue($userSettings->taxes, 1);

                //Invoice Data

                $invoice = json_decode($request['invoiceData']);
                
                $validItems = [];
                $totalPrice = 0;
                $invoiceDescription = '';

                foreach ($invoice as $product){
                    $name = htmlspecialchars($product->name);
                    $format = htmlspecialchars($product->format);
                    $amount = htmlspecialchars($product->amount);
                    $discount = 0;
                    
                    $book = Book::where('title', $name)->first();
                    
                    if(!$book) continue; 
                    
                    if($amount > $book['stock'] && $format == 'Físico'){
                        if($book['stock'] == 0)   return Redirect::back()->withErrors('Lo sentimos, actualmente no tenemos stock del siguiente artículo: "'.$book['title'].'".');
                        return Redirect::back()->withErrors('Lo sentimos, actualmente solo disponemos de '.$book['stock'].' unidades del siguiente artículo: "'.$book['title'].'".');
                    }
                    
                    if($format == "Digital"){
                        $library = Library::where('userId', $user['id'])
                            ->where('bookId', $book['id'])
                            ->where('option', 1)
                            ->first();

                        if($library) return Redirect::back()->withErrors('Ya posee el libro "'.$book['title'].'" en formato digital.');
                        $amount = 1;
                    }
                    
                    $discount = $book['discount'];
                    
                    $invoiceDescription .= $amount.' x '.$book['title'].', ';
                    
                    if($format == 'Físico'){
                        $price = $book['physicalPrice'];
                        $format = 0;
                    } 
                    else{
                        $price = $book['digitalPrice'];
                        $format = 1;
                    } 
                    
                    $productDiscount = ($price*$discount)/100;

                    $price = $price-$productDiscount;

                    $productCouponDiscount = ($price*$couponDiscount)/100;

                    $price = $price-$productCouponDiscount;

                    if($format == 0) $taxAmount = ($price*$physicalTaxPercent)/100;
                    else $taxAmount = ($price*$digitalTaxPercent)/100;

                    $price = number_format($price + $taxAmount, 2);
                    
                    array_push($validItems, [$book['id'], $price, $format, $amount]);
                    
                    $productsPrice = $price * $amount;
                    
                    $totalPrice+=$productsPrice;
                }
                
                $invoiceDescription = mb_strimwidth($invoiceDescription, 0, 125, '...');
                
                if(Count($validItems) < 1) return Redirect::back()->withErrors('Ha sucedido un error inesperado, vuelva a intentarlo más tarde.');
                
                $merchantOrder = Order::orderBy('order', 'DESC')->first();
                
                if(!$merchantOrder) $merchantOrder = 1000;
                else $merchantOrder = $merchantOrder['order'];
                
                $merchantOrder+=1;
                
                Order::create(['order' => $merchantOrder]);
                
                $invoiceCrossPlatformData = json_encode([$user['id'], $shipmentName, $shipmentSurnames, $shipmentAddress, $shipmentPostCode, $billingName, $billingSurnames, $billingAddress, $billingPostCode, $validItems, $couponCode]);
                
                if(strlen($invoiceCrossPlatformData) > 1024) return Redirect::back()->withErrors('Ha sucedido un error con su pedido, vuelva a intentarlo más tarde, si sigue teniendo problemas contacte con el servicio técnico en la página de contacto.');
                    
                $totalPrice = str_replace(".", "", number_format($totalPrice, 2));
                
                //TPV Data

                $tpvUrl = 'https://sis.redsys.es/sis/realizarPago';
                $tpvKey = decrypt(env('SPECIAL_KEY', null));
                
                $redsysRequestObject = new RedsysAPI;
                
                $redsysRequestObject->setParameter("Ds_Merchant_MerchantCode", 66789215);
                $redsysRequestObject->setParameter("Ds_Merchant_Terminal", 001);
                $redsysRequestObject->setParameter("Ds_Merchant_Currency", 978);
                $redsysRequestObject->setParameter("Ds_Merchant_TransactionType", 0);
                $redsysRequestObject->setParameter("Ds_Merchant_Amount",$totalPrice);
                $redsysRequestObject->setParameter("Ds_Merchant_Order", $merchantOrder);
                $redsysRequestObject->setParameter("Ds_Merchant_ProductDescription", $invoiceDescription);
                $redsysRequestObject->setParameter("Ds_Merchant_MerchantURL", "https://editorialparalelo28.com/notifyInvoice");
                $redsysRequestObject->setParameter("Ds_Merchant_UrlOK", "https://editorialparalelo28.com/invoiceValid");
                $redsysRequestObject->setParameter("Ds_Merchant_UrlKO", "https://editorialparalelo28.com/invoiceInvalid");
                $redsysRequestObject->setParameter("Ds_Merchant_MerchantName", "Editorial Paralelo28");
                $redsysRequestObject->setParameter("Ds_Merchant_TransactionDate", date('Y-m-d'));
                $redsysRequestObject->setParameter("Ds_Merchant_MerchantData", $invoiceCrossPlatformData);
                
                $parameters = $redsysRequestObject->createMerchantParameters();
                $signature = $redsysRequestObject->createMerchantSignature($tpvKey);
                
                return Redirect::back()->with('redirectTPV', [$tpvUrl, 'HMAC_SHA256_V1', $parameters, $signature]);
            }else{
                return Redirect::back()->withErrors('Debe aceptar nuestros terminos y condiciones para confirmar su compra.');
            }
        }
        return abort(404);
    }
    
    function notifyPayment(Request $request){
        $ds_SignatureVersion = htmlspecialchars($request['Ds_SignatureVersion']);
        $ds_MerchantParameters = htmlspecialchars($request['Ds_MerchantParameters']);
        $ds_Signature = htmlspecialchars($request['Ds_Signature']);
        
        if($ds_SignatureVersion == 'HMAC_SHA256_V1'){
            $redsysRequestObject = new RedsysAPI;
             
            //TPV Data
            
            $tpvKey = decrypt(env('SPECIAL_KEY', null));
            
            $signatureValidation = $redsysRequestObject->createMerchantSignatureNotif($tpvKey, $ds_MerchantParameters);
            
            if($signatureValidation == $ds_Signature){
                $response = intval($redsysRequestObject->getParameter("Ds_Response"));
                $securePayment = $redsysRequestObject->getParameter("Ds_SecurePayment");
                $amount = $redsysRequestObject->getParameter("Ds_Amount");
                $currency = $redsysRequestObject->getParameter("Ds_Currency");
                $order = $redsysRequestObject->getParameter("Ds_Order");
                $merchantCode = $redsysRequestObject->getParameter("Ds_MerchantCode");
                $terminal = $redsysRequestObject->getParameter("Ds_Terminal");
                $transactionType = $redsysRequestObject->getParameter("Ds_TransactionType");
                $merchantData = json_decode(urldecode($redsysRequestObject->getParameter("Ds_MerchantData")));
                $authorisationCode = $redsysRequestObject->getParameter("Ds_AuthorisationCode");
                
                if($response >= 0 && $response < 100 && $merchantCode == 66789215 && $terminal == '001' && $currency == 978 && $transactionType == 0 && $securePayment == 1){
                    
                    $user = User::where('id', $merchantData[0])->first();
                    
                    if($user){
                        $userSettings = UserSetting::where('userId',$user['id'])->first();
                        $physicalTaxPercent = $this->getTaxValue($userSettings->taxes, 0);
                        $digitalTaxPercent = $this->getTaxValue($userSettings->taxes, 1);
                        $couponDiscount = 0;
                        $status = 'Entregado';
                        $totalPrice = 0;
                        $sales = [];
                        $wishList = [];
                        $coupon = null;
                        $couponCode = null;
                        
                        if($merchantData[10]){
                            $coupon = Coupon::where('code', $merchantData[10])->first();
            
                            if($coupon && ($coupon['valid_until'] == null || strtotime($coupon['valid_until'])> strtotime(date('Y-m-d'))) && $coupon['uses']>0){
                                $couponDiscount = $coupon['discount'];
                                $couponCode = $coupon['code'];
                            }
                        }
                        
                        
                        foreach($merchantData[9] as $validItem){
                            $book = Book::where('id', $validItem[0])->first();
                            
                            if($book){
                                $price = $validItem[1];
                                $format = $validItem[2];
                                $amountOfProduct = $validItem[3];
                                $discount = $book['discount'];
                                $wishedBook = WishList::where('userId', $user['id'])->where('bookId', $book['id'])->first();
                                
                                if($wishedBook) array_push($wishList, $wishedBook);
                                
                                if($format == 0) $bookPrice = $book['physicalPrice'];
                                else $bookPrice = $book['digitalPrice'];
                                
                                $productDiscount = ($bookPrice*$discount)/100;

                                $bookPrice = $bookPrice-$productDiscount;
            
                                $productCouponDiscount = ($bookPrice*$couponDiscount)/100;
            
                                $bookPrice = $bookPrice-$productCouponDiscount;
                                
                                if($format == 0) $taxAmount = ($bookPrice*$physicalTaxPercent)/100;
                                else $taxAmount = ($bookPrice*$digitalTaxPercent)/100;
            
                                $bookPrice = number_format($bookPrice + $taxAmount, 2);
                                
                                if($bookPrice != $price) return http_response_code(403);
                                
                                $totalPrice += ($bookPrice * $amountOfProduct);
                                
                                $sale = Sale::create([
                                        'shipmentCode' => $order,
                                        'bookId' => $validItem[0],
                                        'price' => $validItem[1],
                                        'option' => $validItem[2],
                                        'amount' => $validItem[3],
                                        'couponUsed' =>$couponCode
                                ]);
                                    
                                if($sale['option'] == 0){
                                    $status = 'Pagado';
                                    $book['stock'] = $book['stock'] -1;
                                    $book->save();
                                } 
                                
                                $sale['title'] = $book['title'];
                                
                                array_push($sales, $sale);
                            }
                        }
                        
                        $totalPrice = number_format($totalPrice, 2);
                        
                        if($totalPrice != number_format($amount/100, 2)) return http_response_code(403);
                        
                        $shoppingHistory = ShoppingHistory::create([
                                            'userId' => $user['id'],
                                            'shipmentCode' => $order,
                                            'price' => $totalPrice,
                                            'status' => $status,
                                            'details' => '',
                                            'authorisationCode' => $authorisationCode,
                                            'shipmentName' => $merchantData[1],
                                            'shipmentSurnames' => $merchantData[2],
                                            'shipmentAddress' => $merchantData[3],
                                            'shipmentPostCode' => $merchantData[4],
                                            'billingName' => $merchantData[5],
                                            'billingSurnames' => $merchantData[6],
                                            'billingAddress' => $merchantData[7],
                                            'billingPostCode' => $merchantData[8]
                        ]);
                                        
                        foreach($sales as $sale){
                            Library::create([
                                'userId' => $user['id'],
                                'bookId' => $sale['bookId'],
                                'option' => $sale['option']
                            ]);
                            
                            $statistics = Statistic::where('bookId', $sale['bookId'])->first();
                            
                            if($statistics){
                                if($sale['option'] == 0){
                                   $statistics['physicalSales'] = $statistics['physicalSales'] + 1;
                                }else{
                                    $statistics['digitalSales'] = $statistics['digitalSales'] + 1;
                                }
                                $statistics->save();
                            }
                        }
                        
                        foreach($wishList as $wishedBook){
                            $wishedBook->delete();
                        }
                        
                        if($coupon){
                            $coupon['uses'] = $coupon['uses'] - 1;
                            $coupon->save();
                        }
                        
                        
                        $company = [$user['accountType'], $user['companyName'], $user['companyCIF']];
                    
                        $mpdf = new \Mpdf\Mpdf([
                        	'default_font_size' => 9,
                        	'default_font' => 'dejavusans'
                        ]);

                        $mpdf->Addpage();
                        $mpdf->Image(asset('images/paraleloLogo.png'), 80, 8, 56, 16, 'png', 'https://editorialparalelo28.com', true, true);
                        $mpdf->WriteHTML('<br/><br/><br/><h1 style="text-align:center;">Pedido número '.$order.'</h1>');
                        
                        if($user['accountType'] == 1){
                            $mpdf->WriteHTML('
                            <h3>
                                <b>
                                    <u>Detalles Empresariales:</u>
                                </b>
                            </h3>
                            <p>
                                <b>Compañía: </b>'.$user['companyName'].'
                            </p>
                            <p>
                                <b>CIF: </b>'.$user['companyCIF'].'
                            </p>
                            <br/>');
                        }
                        
                        $mpdf->WriteHTML('
                        <h3>
                            <b>
                                <u>Detalles de Facturación:</u>
                            </b>
                        </h3>
                        <p>
                            <b>Nombre: </b>'.$shoppingHistory['shipmentName'].'
                        </p>
                        <p>
                            <b>Apellidos: </b>'.$shoppingHistory['shipmentSurnames'].'
                        </p>
                        <p>
                            <b>Dirección: </b>'.$shoppingHistory['shipmentAddress'].'
                        </p>
                        <p>
                            <b>Código Postal: </b>'.$shoppingHistory['shipmentPostCode'].'
                        </p>
                        <br/>
                        <h3>
                            <b>
                                <u>Detalles de Envio:</u>
                            </b>
                        </h3>
                        <p>
                            <b>Nombre: </b>'.$shoppingHistory['billingName'].'
                        </p>
                        <p>
                            <b>Apellidos: </b>'.$shoppingHistory['billingSurnames'].'
                        </p>
                        <p>
                            <b>Dirección: </b>'.$shoppingHistory['billingAddress'].'
                        </p>
                        <p>
                            <b>Código Postal: </b>'.$shoppingHistory['billingPostCode'].'
                        </p>
                        <br/>
                        <table border="1" bordercolor="#707070" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <td style="padding: 5px 5px 5px 5px; font-size: 14px;"><b>Producto</b></td>
                            <td style="padding: 5px 5px 5px 5px; font-size: 14px;"><b>Formato</b></td>
                            <td style="padding: 5px 5px 5px 5px; font-size: 14px;"><b>Cantidad</b></td>
                            <td style="padding: 5px 5px 5px 5px; font-size: 14px;"><b>Precio</b></td>
                        </tr>');
                        
                        foreach($sales as $sale){
                            
                            $mpdf->WriteHTML('<tr><td style="padding: 5px 5px 5px 5px; font-size: 14px;">'.$sale['title'].'</td><td style="padding: 5px 5px 5px 5px; font-size: 14px;">');
                            
                            if($sale['option'] == 0) $mpdf->WriteHTML('Físico');
                            else $mpdf->WriteHTML('Digital');
                        
                            $mpdf->WriteHTML('</td><td style="padding: 5px 5px 5px 5px; font-size: 14px;">'.$sale['amount'].'</td><td>'.$sale['price'].'€</td></tr>');
                           
                        }
                        
                        if($coupon){
                             $mpdf->WriteHTML('</table><br/><p style="font-size: 16px; font-family: Arial, sans-serif;">
                                    Se ha utilizado el cupón <b>'.$coupon['code'].'</b> que ha aplicado un descuento del '.$coupon['discount'].'% al pedido.</b>
                                </p>');
                        }else{
                            $mpdf->WriteHTML('</table><br/>');
                        }
                
                        $mpdf->WriteHTML('<p style="font-size: 16px; font-family: Arial, sans-serif;">
                                Total: <b>'.$shoppingHistory['price'].'€</b>
                            </p>
                            <br/>
                            <p style="text-align:center; padding: 20px 0 30px 0; font-family: Arial, sans-serif; font-size: 16px;line-height: 20px;">
                                <b>
                                De nuevo, muchas gracias por confiar en nosotros.<br/>
                                <br/>

                                Un saludo,
                                Equipo Editorial Paralelo 28.
                                </b>
                            </p>');
                            
                        $mpdf->SetProtection(array('print'));
                        
                        try{
                            $namePrepend = bin2hex(random_bytes(mt_rand(5, 10)));
                        }catch(Exception $e){
                            $namePrepend = $this->generateRandomString(mt_rand(10, 20));
                        }
                            
                        $pdfUrl = base_path().'/resources/'.$namePrepend.$order.'.pdf';
                        
                        $mpdf->Output($pdfUrl, 'F');
                    
                        try{
                            $data = [
                                "user" => $userSettings,
                                "company" => $company,
                                "name" => $userSettings['name'],
                                "email" => $user['email'],
                                "shoppingHistory" => $shoppingHistory,
                                "sales" => $sales,
                                "coupon" => $coupon,
                                "pdf" => $pdfUrl
                            ];
            
                            Mail::send('emails.invoice', $data, function($message) use ($data) {
                                $message->attach($data['pdf'], ['as' => 'Pedido número '.$data['shoppingHistory']['shipmentCode'].'.pdf', 'mime' => 'application/pdf']);
                                $message->to('jacobo@editorialparalelo28.com', 'Distribución')->subject('Pedido número '.$data['shoppingHistory']['shipmentCode']);
                                $message->from('noreply@editorialparalelo28.com', 'EditorialParalelo28');
                            });
                            
                            Mail::send('emails.invoice', $data, function($message) use ($data) {
                                $message->attach($data['pdf'], ['as' => 'Pedido número '.$data['shoppingHistory']['shipmentCode'].'.pdf', 'mime' => 'application/pdf']);
                                $message->to($data['email'], $data['name'])->subject('Pedido número '.$data['shoppingHistory']['shipmentCode']);
                                $message->from('noreply@editorialparalelo28.com', 'EditorialParalelo28');
                            });
                            
                            if(File::exists($pdfUrl)){
                                File::delete($pdfUrl);
                            }
                            
                            CronJob::create([
                                'userId'=> $user['id'],
                                'shipmentCode' => $shoppingHistory['shipmentCode']
                            ]);
                        
                            return http_response_code(200);
                        }catch (Exception $e){
                            return http_response_code(403);
                        }
                    }
                }
            }
        }
        return abort(403);
    }
    
    function validatePayment(Request $request){
         $user = $this->validateUser($request->cookie('user'), $request->cookie('sessionToken'));

        if($user){
            $ds_SignatureVersion = htmlspecialchars($request['Ds_SignatureVersion']);
            $ds_MerchantParameters = htmlspecialchars($request['Ds_MerchantParameters']);
            $ds_Signature = htmlspecialchars($request['Ds_Signature']);
            
            if($ds_SignatureVersion == 'HMAC_SHA256_V1'){
                $redsysRequestObject = new RedsysAPI;
                 
                //TPV Data
                
                $tpvKey = decrypt(env('SPECIAL_KEY', null));
                
                $signatureValidation = $redsysRequestObject->createMerchantSignatureNotif($tpvKey, $ds_MerchantParameters);
                
                if($signatureValidation == $ds_Signature){
                    $response = intval($redsysRequestObject->getParameter("Ds_Response"));
                    $securePayment = $redsysRequestObject->getParameter("Ds_SecurePayment");
                    $currency = $redsysRequestObject->getParameter("Ds_Currency");
                    $order = $redsysRequestObject->getParameter("Ds_Order");
                    $merchantCode = $redsysRequestObject->getParameter("Ds_MerchantCode");
                    $terminal = $redsysRequestObject->getParameter("Ds_Terminal");
                    $transactionType = $redsysRequestObject->getParameter("Ds_TransactionType");
                    
                    if($response >= 0 && $response < 100 && $merchantCode == 66789215 && $terminal == '001' && $currency == 978 && $transactionType == 0 && $securePayment == 1){
                        return redirect('home')->with('emptyCart', true)->with('successMessage', ' El pedido número '.$order.' se ha finalizado con exito, encontrará sus productos digitales en el apartado "Biblioteca", puede encontrar el seguimiento de sus pedidos en el apartado "Historial de compras".');
                    } 
                }
            }
        }
        return abort(404);
    }
    
    function invalidatePayment(Request $request){
         $user = $this->validateUser($request->cookie('user'), $request->cookie('sessionToken'));

        if($user){
            $ds_SignatureVersion = htmlspecialchars($request['Ds_SignatureVersion']);
            $ds_MerchantParameters = htmlspecialchars($request['Ds_MerchantParameters']);
            $ds_Signature = htmlspecialchars($request['Ds_Signature']);
            
            if($ds_SignatureVersion == 'HMAC_SHA256_V1'){
                $redsysRequestObject = new RedsysAPI;
                 
                //TPV Data
                
                $tpvKey = decrypt(env('SPECIAL_KEY', null));
                
                $signatureValidation = $redsysRequestObject->createMerchantSignatureNotif($tpvKey, $ds_MerchantParameters);
                
                if($signatureValidation == $ds_Signature){
                    $response = intval($redsysRequestObject->getParameter("Ds_Response"));
                    $securePayment = $redsysRequestObject->getParameter("Ds_SecurePayment");
                    $currency = $redsysRequestObject->getParameter("Ds_Currency");
                    $order = $redsysRequestObject->getParameter("Ds_Order");
                    $merchantCode = $redsysRequestObject->getParameter("Ds_MerchantCode");
                    $terminal = $redsysRequestObject->getParameter("Ds_Terminal");
                    $transactionType = $redsysRequestObject->getParameter("Ds_TransactionType");
                    
                    if($response >= 100 && $merchantCode == 66789215 && $terminal == '001' && $currency == 978 && $transactionType == 0 && $securePayment == 0){
                        return redirect('shoppingCart')->withErrors('Ha ocurrido un error durante el proceso de pago del pedido número '.$order.', inténtelo de nuevo, si el error persiste, pongase en contacto con nuestro equipo técnico.');
                    } 
                }
            }
        }
        return abort(404);
    }
    
     /*---------------------------------------- C O N F I R M   A R R I V A L ----------------------------------------*/

    function confirmArrival(Request $request, $parameter = null){
        $user = $this->validateUser($request->cookie('user'), $request->cookie('sessionToken'));

        if($user){
            if(!$parameter) return abort(404);

            $parameter = htmlspecialchars($parameter);

            $shoppingHistory = ShoppingHistory::where('shipmentCode', $parameter)->where('userId', $user['id'])->first();

            if(!$shoppingHistory || $shoppingHistory['status'] != 'Enviado') return abort(404);

            $shoppingHistory['status'] = 'Entregado';

            $shoppingHistory->save();

            return Redirect::back()->with('successMessage', 'Ha confirmado la llegada del pedido número '.$shoppingHistory['shipmentCode'].'.');
        }
        return abort(404);
    }
    
    /*---------------------------------------- R E F U N D ----------------------------------------*/

    function refundSolicitude(Request $request){
        $user = $this->validateUser($request->cookie('user'), $request->cookie('sessionToken'));

        if($user){
            $shipment = htmlspecialchars($request['shipment']);
            $reason = htmlspecialchars($request['reasons']);

            $shoppingHistory = ShoppingHistory::where('shipmentCode', $shipment)->where('userId', $user['id'])->first();

            if(!$shoppingHistory) return abort(404);

            $expireDate = date('Y-m-d h:i:s', strtotime($shoppingHistory['created_at'] . '+14 days'));
            $now = date('Y-m-d h:i:s');

            if (strtotime($now) > strtotime($expireDate)) return Redirect::back()->withErrors('Han pasado más de 14 días desde la compra de este pedido, no puede solicitar una devolución.');

            $ticked = RefundTicket::where('shoppingHistoryId', $shoppingHistory['id'])->first();

            if($ticked) return Redirect::back()->withErrors('Ya ha solicitado una devolución para este pedido.');

            $userSettings = UserSetting::where('userId', $user['id'])->first();

            $data = [
                'name' => $userSettings['name'],
                'shipmentCode' => $shoppingHistory['shipmentCode'],
                'reason' => $reason
            ];

            try{
                Mail::send('emails.refundSolicitude', $data, function($message) use ($data) {
                    $message->to('jacobo@editorialparalelo28.com', 'Devolución')->subject($data['name'].' ha solicitado una devolución de su pedido.');
                    $message->from('noreply@editorialparalelo28.com', 'EditorialParalelo28');
                });
            }catch (Exception $exception){
                return Redirect::back()->withErrors('Ha sucedido un error durante su petición, vuelva a intentarlo más tarde. Si el error persiste pongase en contacto con nuestro sistema de atención al cliente.');
            }

            RefundTicket::create([
                'shoppingHistoryId' => $shoppingHistory['id'],
                'reason' => $reason,
                'status' => 'Pendiente de revisión',
                'statusMessage' => ''
            ]);

            return Redirect::back()->with('successMessage', 'Ha solicitado la devolución del pedido número '.$shoppingHistory['shipmentCode'].', revisaremos su solicitud y la responderemos tan pronto como nos sea posible.');
        }
        return abort(404);
    }
    
    /*---------------------------------------- W I S H   L I S T ----------------------------------------*/

    function addToWishList(Request $request, $parameter = null){
        $user = $this->validateUser($request->cookie('user'), $request->cookie('sessionToken'));

        if(!$user){
            return Redirect::back()->with('loginOpen', true);
        }else{
            if(!$parameter) return abort(404);

            $parameter = htmlspecialchars($parameter);

            $book = Book::where('id', $parameter)->first();

            if(!$book) return abort(404);

            $wishes = WishList::where('userId', $user['id'])->get();

            $alreadyWish = false;

            foreach ($wishes as $wish){
                if($wish['bookId'] == $book['id']){
                    $alreadyWish = true;
                    break;
                }
            }

            if($alreadyWish) return Redirect::back()->withErrors('El producto ya está en su lista de deseos.');

            WishList::create([
                'userId' => $user['id'],
                'bookId' => $book['id']
            ]);

            $lastStatistic = Statistic::where('bookId', $book['id'])->orderBy('created_at', 'DESC')->first();

            Statistic::create([
                'bookId' => $book['id'],
                'physicalSales' => $lastStatistic['physicalSales'],
                'digitalSales' => $lastStatistic['digitalSales'],
                'addedToWishList' => $lastStatistic['addedToWishList'] + 1,
                'addedToCart' => $lastStatistic['addedToCart']
            ]);

            return Redirect::back()->with('successMessage', $book['title'].' ha sido añadido a su lista de deseos.');
        }
    }
    
    function removeFromWishList(Request $request, $parameter = null){
        $user = $this->validateUser($request->cookie('user'), $request->cookie('sessionToken'));

        if(!$user){
            return Redirect::back()->with('loginOpen', true);
        }else{
            if(!$parameter) return abort(404);

            $parameter = htmlspecialchars($parameter);

            $book = Book::where('id', $parameter)->first();

            if(!$book) return abort(404);

            $wish = WishList::where('bookId', $parameter)
                ->where('userId', $user['id'])
                ->first();

            if($wish) $wish->delete();

            return Redirect::back()->with('successMessage', $book['title'].' ha sido eliminado de su lista de deseos.');
        }
    }

    function addedToCart(Request $request){
        $book = Book::where('title', htmlspecialchars($request['name']))->first();

        if($book){
            $lastStatistic = Statistic::where('bookId', $book['id'])->orderBy('created_at', 'DESC')->first();

            Statistic::create([
                'bookId' => $book['id'],
                'physicalSales' => $lastStatistic['physicalSales'],
                'digitalSales' => $lastStatistic['digitalSales'],
                'addedToWishList' => $lastStatistic['addedToWishList'],
                'addedToCart' => $lastStatistic['addedToCart'] + 1
            ]);
        }else{
            return abort(400);
        }
    }
    
    /*---------------------------------------- V I E W   S H I P M E N T   D A T A ----------------------------------------*/

     function shipmentData(Request $request, $parameter){
        $user = $this->validateUser($request->cookie('user'), $request->cookie('sessionToken'));

        if($user){
            if(!$parameter) return abort(404);

            $parameter = htmlspecialchars($parameter);

            $shoppingHistory = ShoppingHistory::where('shipmentCode', $parameter)->where('userId', $user['id'])->first();

            if(!$shoppingHistory) return abort(404);

            $sales = Sale::where('shipmentCode', $shoppingHistory['shipmentCode'])->get();

            foreach ($sales as $sale){
                $product = Book::where('id', $sale['bookId'])->first();

                $sale['product'] = $product['title'];
            }

            $ticket = RefundTicket::where('shoppingHistoryId', $shoppingHistory['id'])->first();

            return $this->viewDispatcher('shipmentInfo', $request, [$shoppingHistory, $sales, $ticket]);
        }
        return abort(404);
    }
    
    /*---------------------------------------- B L O G   V I E W   E N T R Y ----------------------------------------*/

    function viewEntry(Request $request, $parameter=null){
        if(!$parameter) return redirect('blog');

        $parameter = htmlspecialchars($parameter);

        $blogEntry = BlogEntry::where('title', $parameter)->first();

        if(isset($_GET['action'])){
            $action = $_GET['action'];
            $allBlogEntries = BlogEntry::all()->toArray();

            if($action == 'before'){
                for($i = 0; $i < Count($allBlogEntries); $i++){
                    if($allBlogEntries[$i]['id'] == $blogEntry['id']){

                        $j = $i - 1;

                        if($j < 0) $j = Count($allBlogEntries) - 1;

                        return redirect('blog/'.$allBlogEntries[$j]['title']);
                    }
                }
            }else {
                for($i = 0; $i < Count($allBlogEntries); $i++){
                    if($allBlogEntries[$i]['id'] == $blogEntry['id']){

                        $j = $i + 1;

                        if($j > Count($allBlogEntries) - 1) $j = 0;

                        return redirect('blog/'.$allBlogEntries[$j]['title']);
                    }
                }
            }
        }

        if(!$blogEntry) return abort(404);

        $otherNews = BlogEntry::where('category', $blogEntry['category'])
            ->where('id', '!=', $blogEntry['id'])
            ->paginate(3);

        if(Count($otherNews) < 3){
            $fillNews = BlogEntry::where('category', '!=', $blogEntry['category'])->paginate(3 - Count($otherNews));

            $otherNews = $otherNews->merge($fillNews);
        }

        return $this->viewDispatcher('blogEntry', $request, [$blogEntry, $otherNews]);
    }
    
     /*---------------------------------------- V I E W   B O O K   A N D   P R I N T   B O O K ----------------------------------------*/

    function viewBook(Request $request, $parameter = 'NONE'){
        $user = $this->validateUser($request->cookie('user'), $request->cookie('sessionToken'));

        if($user){
            $parameter = htmlspecialchars($parameter);

            $book = Book::where('title', $parameter)->first();

            if($book){
                $userLibrary = Library::where('userId', $user['id'])->where('bookId', $book['id'])->first();

                if($userLibrary){
                    $images = Image::where('affiliationName', $book['images'])->orderBy('id', 'ASC')->get();

                    return $this->viewDispatcher('pdfViewer', $request, [$book['title'], $images]);
                }
            }
        }
        return abort(404);
    }

    function bookDownload(Request $request, $parameter){
        $user = $this->validateUser($request->cookie('user'), $request->cookie('sessionToken'));

        if($user){
            $parameter = htmlspecialchars($parameter);

            $book = Book::where('title', $parameter)->first();

            if($book){
                $userLibrary = Library::where('userId', $user['id'])->where('bookId', $book['id'])->first();
                $userSettings = UserSetting::where('userId', $user['id'])->first();

                if($userLibrary){
                    $fileName = $this->validateStringForFileName($book['title']).'.pdf';

                    $fileUrl =  base_path().'/resources/books/'.$fileName;

                    try{
                        $pdf = new \Mpdf\Mpdf();

                        $pdf->SetWatermarkText($user['id'].' - '.$userSettings['name'].' '.$userSettings['surnames'].' - '.$user['email'], 0.1);
                        $pdf->showWatermarkText = true;

                        $pagecount = $pdf->SetSourceFile($fileUrl);
                        for ($i=1; $i<=$pagecount; $i++) {
                            $import_page = $pdf->ImportPage($i);

                            $pdf->UseTemplate($import_page, null, null, null, null, true);

                            if ($i < $pagecount)
                                $pdf->AddPage();
                        }

                        $pdf->SetProtection(['print']);

                       return $pdf->Output($this->validateStringForFileName($book['title']).'.pdf', 'D');
                    }catch (Exception $e) {
                        return $e->getMessage();
                        return Redirect::back()->withErrors('Ha sucedido un error, vuelva a intentarlo más tarde. Si el error perdura, pongase en contacto con nuestro soporte técnico.');
                    }

                }
            }
        }
        return abort(404);
    }

    function bookPrint(Request $request, $parameter){
        $user = $this->validateUser($request->cookie('user'), $request->cookie('sessionToken'));

        if($user){
            $parameter = htmlspecialchars($parameter);

            $book = Book::where('title', $parameter)->first();

            if($book){
                $userLibrary = Library::where('userId', $user['id'])->where('bookId', $book['id'])->first();
                $userSettings = UserSetting::where('userId', $user['id'])->first();

                if($userLibrary){
                    $fileName = $this->validateStringForFileName($book['title']).'.pdf';

                    $fileUrl =  base_path().'/resources/books/'.$fileName;

                    try{
                        $pdf = new \Mpdf\Mpdf();

                        $pdf->SetWatermarkText($user['id'].' - '.$userSettings['name'].' '.$userSettings['surnames'].' - '.$user['email'], 0.1);
                        $pdf->showWatermarkText = true;

                        $pagecount = $pdf->SetSourceFile($fileUrl);
                        for ($i=1; $i<=$pagecount; $i++) {
                            $import_page = $pdf->ImportPage($i);

                            $pdf->UseTemplate($import_page, null, null, null, null, true);

                            if ($i < $pagecount)
                                $pdf->AddPage();
                        }

                        $pdf->SetProtection(['print']);

                        $pdf->SetJS('this.print();');

                        return $pdf->Output();
                    }catch (Exception $e) {
                        return Redirect::back()->withErrors('Ha sucedido un error, vuelva a intentarlo más tarde. Si el error perdura, pongase en contacto con nuestro soporte técnico.');
                    }
                }
            }
        }
        return abort(404);
    }
    
    /*---------------------------------------- I N D E X   O P E N   L O G I N ----------------------------------------*/

    function indexOpenLogin(Request $request){
        $user = $this->validateUser($request->cookie('user'), $request->cookie('sessionToken'));

        if(!$user) return Redirect::to('/')->with('loginOpen', true);
        else return abort(404);
    }

    /*---------------------------------------- L O G O U T ----------------------------------------*/

    function closeSession(){
        Cookie::queue(Cookie::forget('user'));
        Cookie::queue(Cookie::forget('sessionToken'));
        return redirect('/');
    }
}
