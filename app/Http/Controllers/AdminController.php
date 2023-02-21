<?php

namespace Paralelo28\Http\Controllers;

include_once base_path().'/vendor/redsysHMAC256_API_PHP_7.0.0/apiRedsys.php';

use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Mail;
use Paralelo28\BlogEntry;
use Paralelo28\Book;
use Paralelo28\BookCertificate;
use Paralelo28\Category;
use Paralelo28\Certificate;
use Paralelo28\GlobalStatistic;
use Paralelo28\ShoppingHistory;
use Paralelo28\Image;
use Paralelo28\Jobs\ProcessEmailQueue;
use Paralelo28\Library;
use Paralelo28\Page;
use Paralelo28\RefundTicket;
use Paralelo28\Sale;
use Paralelo28\Statistic;
use Paralelo28\Subscriber;
use Paralelo28\SurveyAnswer;
use Paralelo28\SurveyPossibleAnswer;
use Paralelo28\SurveyQuestion;
use Paralelo28\User;
use Paralelo28\UserSetting;
use Paralelo28\WishList;
use Paralelo28\Coupon;
use Paralelo28\CouponRedeemed;
use Paralelo28\Ip;
use Paralelo28\Tax;
use ConvertApi\ConvertApi;
use DOMDocument;
use DOMXPath;
use GuzzleHttp;
use RedsysApi;
use Exception;


class AdminController extends RootController
{

    //---------- A D M I N   H O M E ----------//

    function home(Request $request){
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
        
        $user = $this->validateAdmin($request['email'], $request['pass']);

        if($user && $user['admin'] > 0){
            try{
                $sessionToken = bin2hex(random_bytes(mt_rand(10, 25)));
            }catch(Exception $e){
                $sessionToken = $this->generateRandomString(mt_rand(20, 50));
            }

            $user->session_token = $sessionToken;
            $user->save();

            $userCookie = cookie()->forever('user', $user->email);
            $sessionCookie = cookie()->forever('sessionToken', $sessionToken);

            return redirect('controlPanel')
                ->withCookie($userCookie)
                ->withCookie($sessionCookie);
        }else{
            if($ip){
                $user = User::where('email', htmlspecialchars($request['email']))->first();
                
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
                 si ha olvidado su contraseña utilize la opción "¿Olvidaste tu contraseña?"en la página principal para evitar recibir un bloqueo temporal.');

            }else{
                Ip::create([
                    'ip' =>  $visitorIp,
                    'tries' => 1
                ]);
            }
            return Redirect::back()->withErrors('Revise sus datos y vuelva a intentarlo.');
        }
    }

    //---------- A D M I N   L O G I N   P A G E ----------//

    function adminLogin(Request $request){
        $user = $this->validateUser($request->cookie('user'), $request->cookie('sessionToken'));

        if($user && $user['admin'] > 0){
            return redirect('controlPanel');
        }

        return $this->viewDispatcher('admin', $request);
    }

   function controlPanel(Request $request){
        $user = $this->validateUser($request->cookie('user'), $request->cookie('sessionToken'));

        if($user && $user['admin'] > 0){
            $books = Book::all();

            $currentMonth = date('Y-m');
            $lastMonthHighestVisits = GlobalStatistic::where('created_at', 'not like', '%' . $currentMonth . '%')->orderBy('visitors', 'DESC')->first()['visitors'];
            $viewsStatistics = [];
            $salesStatistics = [];
            $last = 0;

            $currentMonthStatistics = GlobalStatistic::where('created_at', 'like', '%' . $currentMonth . '%')->orderBy('created_at', 'ASC')->get();
            $currentMonthSales = Sale::where('created_at', 'like', '%' . $currentMonth . '%')->orderBy('created_at', 'ASC')->get();
            
            for ($i = 0; $i< Count($currentMonthStatistics); $i++){
                $date = substr($currentMonthStatistics[$i]['created_at'], 0, 10);
                if($date == $last) unset($currentMonthStatistics[$i-1]);
                $last = $date;
            }
            
            foreach ($currentMonthStatistics as $currentMonthStatistic){
                $currentMonthStatistic['visitors'] -= $lastMonthHighestVisits;
            }

            $currentMonthStatistics = array_values($currentMonthStatistics->toArray());

            $auxArray = $currentMonthStatistics;

            for ($i = 0; $i< Count($currentMonthStatistics); $i++){
                if(isset($currentMonthStatistics[$i - 1])) $currentMonthStatistics[$i]['visitors'] -= $auxArray[$i - 1]['visitors'];
            }

            for($i = 0; $i < intval(date('d')); $i++){
                $match = false;
                foreach ($currentMonthStatistics as $currentMonthStatistic){
                    if($i + 1 == intval(date('d', strtotime($currentMonthStatistic['created_at'])))){
                        array_push($viewsStatistics, $currentMonthStatistic['visitors']);
                        $match = true;
                        break;
                    }
                }
                if(!$match) array_push($viewsStatistics, 0);
            }

            for($i = 0; $i < intval(date('d')); $i++){
                $value = 0;

                foreach ($currentMonthSales as $currentMonthSale){
                    if($i + 1 == intval(date('d', strtotime($currentMonthSale['created_at'])))) $value++;
                }

                array_push($salesStatistics, $value);
            }

            foreach ($books as $book){
                $book['category'] = $this->getCategory($book);

                $bookPhysicalSales = Statistic::where('bookId', $book['id'])->orderBy('physicalSales', 'DESC')->first();
                $bookDigitalSales = Statistic::where('bookId', $book['id'])->orderBy('digitalSales', 'DESC')->first();

                $book['totalSales'] = $bookPhysicalSales['physicalSales'] + $bookDigitalSales['digitalSales'];
            }

            //Get latest changes made by the administrators

            $latestChanges = [];

            $lastPageChanged = Page::orderBy('updated_at', 'DESC')->first();

            if($lastPageChanged){
                $dateAndHour = explode(' ', $lastPageChanged['updated_at']);

                array_push($latestChanges, ['Editó la página <strong>"'.$lastPageChanged['page'].'"</strong>', $this->revertDate($dateAndHour[0], '/'), $dateAndHour[1]]);
            }
            

            $lastBookChanged = Book::orderBy('updated_at', 'DESC')->first();

            if($lastBookChanged){
                $dateAndHour = explode(' ', $lastBookChanged['updated_at']);

                array_push($latestChanges, ['Editó el libro <strong>"'.$lastBookChanged['title'].'"</strong>', $this->revertDate($dateAndHour[0], '/'), $dateAndHour[1]]);
            }

            $lastBookUploaded= Book::orderBy('created_at', 'DESC')->first();

            if($lastBookUploaded){
                $dateAndHour = explode(' ', $lastBookUploaded['created_at']);

                array_push($latestChanges, ['Añadió el libro <strong>"'.$lastBookUploaded['title'].'"</strong>', $this->revertDate($dateAndHour[0], '/'), $dateAndHour[1]]);
            }


            //Add all the data to the request

            $data = [[$salesStatistics, $viewsStatistics], $books, $latestChanges];

            if(isset($_GET['page'])) array_push($data, htmlspecialchars($_GET['page']));

            //Return the view with all the gathered data

            return $this->viewDispatcher('controlPanel', $request, $data);
        }
        return redirect('admin');
    }


    //---------- A D M I N   V A L I D A T E   A C C E S S ----------//

    function validateAdmin($email, $password){
        $email = htmlspecialchars($email);
        $pass = htmlspecialchars($password);

        $user = User::where('email', $email)->first();

        if($user && $user['admin'] > 0){
            if(Hash::check($pass, $user['password'])) {
                return $user;
            }
        }
        return false;
    }

    //---------- A D M I N   V I E W   S T A T I S T I C S ----------//

    function statistics(Request $request, $parameter = null){
        if(!$parameter) return abort(404);

        $user = $this->validateUser($request->cookie('user'), $request->cookie('sessionToken'));

        if($user && $user['admin'] > 0){
            $parameter = htmlspecialchars($parameter);

            $book = Book::where('title', $parameter)->first();

            if(!$book) return abort(404);

            $monthlyStatistics = [];
            $parsedStatistics = [];

            $statistics = Statistic::where('bookId', $book['id'])->orderBy('created_at', 'ASC')->get();

            foreach ($statistics as $statistic){
                $yearAndMonth = date('Y-m', strtotime($statistic['created_at']));
                if(array_key_exists ($yearAndMonth, $monthlyStatistics)) array_push($monthlyStatistics[$yearAndMonth], $statistic);
                else $monthlyStatistics[$yearAndMonth] = [$statistic];
            }

            foreach ($monthlyStatistics as $key => $value){
               $daysInMonth = cal_days_in_month(CAL_GREGORIAN, date('m', strtotime($key)), date('Y', strtotime($key)));
               $parsedStatistics[$key] = [];

               for($i = 1; $i <= $daysInMonth; $i++){
                   $date = date('Y-m-d', strtotime($key.'-'.$i));
                   $added = false;

                   foreach ($value as $index => $currentValue){
                       if(date('Y-m-d', strtotime($currentValue['created_at'])) == $date){
                           $parsedStatistics[$key][$i] = [$currentValue['physicalSales'], $currentValue['digitalSales'], $currentValue['addedToWishList'], $currentValue['addedToCart']];
                           unset($value[$index]);
                           $added = true;
                       }
                   }

                   if(!$added) $parsedStatistics[$key][$i] = [0,0,0,0];
               }
            }

            return  $this->viewDispatcher('statisticGraphics', $request , [$book['title'], $parsedStatistics]);
        }
        return redirect('admin');
    }

    //---------- A D M I N   E D I T   P A G E ----------//

    function editPage(Request $request, $parameter=null){
        if(!$parameter) return abort(404);

        $user = $this->validateUser($request->cookie('user'), $request->cookie('sessionToken'));

        if($user && $user['admin'] > 0){
           $parameter = htmlspecialchars($parameter);

           if(isset($_GET['key'])) $parameter.='?key='.$_GET['key'];

            $route = route('routeDispatcher', ['parameter' => $parameter]);

            $client = new GuzzleHttp\Client();

            try{
                $connection = $client->get($route);
                $status = $connection->getStatusCode();

                if($status == 200){
                    $additionalData = [$route, $parameter];

                    if (strpos($route, 'catalogue') !== false) array_push($additionalData, Certificate::all());
                    if (strpos($route, 'certificate') !== false){
                        $books = Book::all();

                        foreach ($books as $book){
                            $bookCertificates = BookCertificate::where('bookId', $book['id'])->get();
                            $certificates = [];

                            foreach ($bookCertificates as $bookCertificate){
                                $certificate = Certificate::where('id', $bookCertificate['certificate'])->first();
                                if($certificate) array_push($certificates, $certificate['certificate']);
                            }

                            $book['certificates'] = $certificates;
                        }

                        array_push($additionalData, json_encode($books));
                    }

                    return $this->viewDispatcher('adminEditPage', $request, $additionalData);
                }
            }catch (Exception $e){
                return abort(404);
            }
        }
        return redirect('admin');
    }

    function updatePage(Request $request){
        $user = $this->validateUser($request->cookie('user'), $request->cookie('sessionToken'));

        if($user && $user['admin'] > 0){
            $requestAll = $request->all();
            $page = htmlspecialchars($request['page']);
            $pageContents = Page::where('page', $page)->get();

            if(!$pageContents) return abort(404);

            foreach ($requestAll as $key=>$value){
                $key = htmlspecialchars($key);

                if(!is_array($value)){
                    $value = $this->stripTagsAndAttributes($value, '<h1><h2><h3><h4><h5><h6><strong><b><a><u><em><li><ul><ol><p><br>');

                    foreach ($pageContents as $pageContent){
                        if($pageContent['name'] == $key){
                            if(explode('-', $key)[0] == 'img'){
                                $time = strtotime(date('d/m/y h:i:s'));
                                $random = $this->generateRandomString(20);

                                try{
                                    $file = Input::file($key);
                                }catch (Exception $e){
                                    return Redirect::back()->withErrors('Ha sucedido un error, reintentelo más tarde.');
                                }

                                if ($file != null) {
                                    $name = $random . $time . '.' . $file->getClientOriginalExtension();
                                    $destinationPath = public_path('/images/uploads');
                                    if (filesize($file) > 500000) {
                                        return Redirect::back()->withErrors('Las imágenes no pueden superar 500KB de tamaño.');
                                    } else {
                                        $file->move($destinationPath, $name);
                                        $value = $name;

                                        if(File::exists('images/uploads/'.$pageContent['value'])){
                                            File::delete('images/uploads/'.$pageContent['value']);
                                        }
                                    }
                                }
                            }
                            $pageContent['value'] = $value;
                            $pageContent->save();
                            unset($requestAll[$key]);
                            break;
                        }
                    }
                }else{
                    if($key == 'deleteField'){
                        foreach ($value as $currentValue){
                            $rowToDelete = Page::where('name', strip_tags($currentValue))->first();
                            if($rowToDelete) $rowToDelete->delete();
                        }
                    }
                }
            }

            foreach ($requestAll as $key=>$value){
                if($this->isFlexibleField(explode('-', $key)[0])){
                    if(isset(explode('-', $key)[1])) {
                        Page::create([
                            'page' => $page,
                            'name' => $key,
                            'value' => $value
                        ]);
                    }
                }
            }

            return Redirect::back()->with('successMessage', 'La página ha sido modificada con éxito.');
        }
        return abort(404);
    }

    //---------- A D M I N   A D D   C A T E G O R Y ----------//

    function addCategory(Request $request){
        $user = $this->validateUser($request->cookie('user'), $request->cookie('sessionToken'));

        if($user && $user['admin'] > 0){
            if($request['category']){
                $category = htmlspecialchars($request['category']);

                if(strlen($category) > 0){
                    try{
                        $time = strtotime(date('d/m/y h:i:s'));

                        $bannerImg = $request['bannerImg'];
                        $navBarImg = $request['navBarImg'];

                        if ($bannerImg != null && $navBarImg != null) {
                            $bannerName = $this->generateRandomString(20) . $time . '.' . $bannerImg->getClientOriginalExtension();
                            $navBarName = $this->generateRandomString(20) . $time . '.' . $navBarImg->getClientOriginalExtension();
                            $destinationPath = public_path('/images/uploads');
                            if (filesize($bannerImg) > 500000 || filesize($navBarImg) > 500000) {
                                return Redirect::back()->withErrors('Las imágenes no pueden superar 500KB de tamaño.');
                            } else {
                                $bannerImg->move($destinationPath, $bannerName);
                                $navBarImg->move($destinationPath, $navBarName);
                                Category::create([
                                    'category' => $category,
                                    'imageLink' => $bannerName,
                                    'sampleBookImage' => $navBarName
                                ]);

                                return Redirect::back()->with('successMessage', 'La categoría "'.$category.'" ha sido creada con éxito.');
                            }
                        }
                    }catch (Exception $e){
                        return Redirect::back()->withErrors('Ha sucedido un error, reintentelo más tarde.');
                    }
                }
            }
        }
        return abort(404);
    }

    //---------- A D M I N   E D I T   C A T E G O R Y ----------//

    function editCategory(Request $request){
        $user = $this->validateUser($request->cookie('user'), $request->cookie('sessionToken'));

        if($user && $user['admin'] > 0){
            $newCategory = htmlspecialchars($request['newCategory']);

            $oldCategory = Category::where('category', htmlspecialchars($request['oldCategory']))->first();

            if(!$oldCategory) return abort(404);

            try{
                $time = strtotime(date('d/m/y h:i:s'));

                $bannerImg = $request['bannerImg'];
                $navBarImg = $request['navBarImg'];

                $destinationPath = public_path('/images/uploads');

                if ($bannerImg != null) {
                    $bannerName = $this->generateRandomString(20) . $time . '.' . $bannerImg->getClientOriginalExtension();
                    if (filesize($bannerImg) > 500000) {
                        return Redirect::back()->withErrors('Las imágenes no pueden superar 500KB de tamaño.');
                    } else {
                        $bannerImg->move($destinationPath, $bannerName);
                    }
                }if($navBarImg != null){
                    $navBarName = $this->generateRandomString(20) . $time . '.' . $navBarImg->getClientOriginalExtension();
                    if (filesize($navBarImg) > 500000) {
                        return Redirect::back()->withErrors('Las imágenes no pueden superar 500KB de tamaño.');
                    } else {
                        $navBarImg->move($destinationPath, $navBarName);
                    }
                }
            }catch (Exception $e){
                return Redirect::back()->withErrors('Ha sucedido un error, reintentelo más tarde.');
            }

            if(isset($bannerName)){
                if(File::exists('images/uploads/'.$oldCategory['imageLink'])){
                    File::delete('images/uploads/'.$oldCategory['imageLink']);
                }
                $oldCategory['imageLink'] = $bannerName;
            }

            if(isset($navBarName)){
                if(File::exists('images/uploads/'.$oldCategory['sampleBookImage'])){
                    File::delete('images/uploads/'.$oldCategory['sampleBookImage']);
                }
                $oldCategory['sampleBookImage'] = $navBarName;
            }

            if($newCategory != null && strlen($newCategory) > 0 && $oldCategory['category'] != $newCategory){
                $certificates = Certificate::where('category', $oldCategory['category'])->get();

                foreach ($certificates as $certificate){
                    $certificate['category'] = $newCategory;
                    $certificate->save();
                }

                $oldCategory['category'] = $newCategory;
            }

            $oldCategory->save();

            return Redirect::back()->with('successMessage', 'La categoría "'.$oldCategory['category'].'" ha sido modificada con éxito.');
        }
        return abort(404);
    }

    //---------- A D M I N   A D D   C E R T I F I C A T E ----------//

    function addCertificate(Request $request){
        $user = $this->validateUser($request->cookie('user'), $request->cookie('sessionToken'));

        if($user && $user['admin'] > 0){
            if($request['certificate'] && $request['certificateName']) {
                $category = htmlspecialchars($request['certificate']);
                $certificate = htmlspecialchars($request['certificateName']);

                if (strlen($category) > 0 && strlen($certificate) > 0) {
                    $category = Category::where('category', $category)->first();

                    if($category){
                        try{
                            $time = strtotime(date('d/m/y h:i:s'));

                            $img = $request['certificateImg'];

                            if ($img != null) {
                                $name = $this->generateRandomString(20) . $time . '.' . $img->getClientOriginalExtension();

                                $destinationPath = public_path('/images/uploads');
                                if (filesize($img) > 500000) {
                                    return Redirect::back()->withErrors('Las imágenes no pueden superar 500KB de tamaño.');
                                } else {
                                    $img->move($destinationPath, $name);

                                    Certificate::create([
                                        'certificate' => $certificate,
                                        'category' => $category['category'],
                                        'imageLink' => $name
                                    ]);

                                    return Redirect::back()->with('successMessage', 'El certificado "'.$certificate.'" ha sido creado con éxito.');
                                }
                            }
                        }catch (Exception $e){
                            return Redirect::back()->withErrors('Ha sucedido un error, reintentelo más tarde.');
                        }
                    }
                }
            }
        }
        return abort(404);
    }

    //---------- A D M I N   E D I T   C E R T I F I C A T E ----------//

    function editCertificate(Request $request){
        $user = $this->validateUser($request->cookie('user'), $request->cookie('sessionToken'));

        if($user && $user['admin'] > 0){
            $newCertificate = htmlspecialchars($request['certificateName']);
            $category = htmlspecialchars($request['category']);

            $oldCertificate = Certificate::where('certificate', htmlspecialchars($request['certificate']))->first();

            if(!$oldCertificate) return abort(404);

            try{
                $time = strtotime(date('d/m/y h:i:s'));

                $image = $request['certificateImg'];

                $destinationPath = public_path('/images/uploads');

                if ($image != null) {
                    $imageName = $this->generateRandomString(20) . $time . '.' . $image->getClientOriginalExtension();
                    if (filesize($image) > 500000) {
                        return Redirect::back()->withErrors('Las imágenes no pueden superar 500KB de tamaño.');
                    } else {
                        $image->move($destinationPath, $imageName);
                    }
                }
            }catch (Exception $e){
                return Redirect::back()->withErrors('Ha sucedido un error, reintentelo más tarde.');
            }

            if(isset($imageName)){
                if(File::exists('images/uploads/'.$oldCertificate['imageLink'])){
                    File::delete('images/uploads/'.$oldCertificate['imageLink']);
                }
                $oldCertificate['imageLink'] = $imageName;
            }

            $category = Category::where('category', $category)->first();

            if($category != null && strlen($category['category']) > 0 && $oldCertificate['category'] != $category['category']){
                $oldCertificate['category'] = $category['category'];
            }


            if($newCertificate != null && strlen($newCertificate) > 0 && $oldCertificate['certificate'] != $newCertificate){
                $oldCertificate['certificate'] = $newCertificate;
            }

            $oldCertificate->save();

            return Redirect::back()->with('successMessage', 'La categoría "'.$oldCertificate['certificate'].'" ha sido modificada con éxito.');
        }
        return abort(404);
    }

    //---------- A D M I N   A D D   B O O K  ----------//

    function addBook(Request $request){
        $user = $this->validateUser($request->cookie('user'), $request->cookie('sessionToken'));

        if($user && $user['admin'] > 0){
            try{
                $fullRequest = $request->all();
                $parsedRequest = [];

                foreach ($fullRequest as $key=>$value){
                    if($value != null && !is_array($value)) $parsedRequest[$key] = htmlspecialchars($value);
                    else $parsedRequest[$key] = $value;
                }
              
                if(isset($parsedRequest['certificates'])){
               
                    $file = $request->file('bookFile');
                    
                    $img = $request['bookImage'];

                    if ($file != null && $img != null) {
                        
                        $time = strtotime(date('d/m/y h:i:s'));

                        $pdfName = $this->generateRandomString(20) . $time . '.' . $file->getClientOriginalExtension();

                        $destinationPath = public_path().'/temp';

                        $file->move($destinationPath, $pdfName);
                        
                        try{
                            $pdf = new \Mpdf\Mpdf();
                            $pdf->SetSourceFile($destinationPath.'/'.$pdfName);
                        }catch (Exception $e) {
                            return Redirect::back()->withErrors('El PDF no puede superar la versión 1.4.');
                        }

                        if (filesize($img) > 500000) return Redirect::back()->withErrors('Las imágenes no pueden superar 500KB de tamaño.');
                    
                        $time = strtotime(date('d/m/y h:i:s'));

                        $name = $this->generateRandomString(20) . $time . '.' . $img->getClientOriginalExtension();

                        $destinationPath = public_path('/images/uploads');

                        $img->move($destinationPath, $name);
                    }else{
                        return Redirect::back()->withErrors('Ha sucedido un error, reintentelo más tarde.');
                    }
                    
                    $promoted = 0;

                    if(isset($parsedRequest['bookPromote'])) $promoted = 1;
                    
                    $jsonData = [
                        'title' => $parsedRequest['bookTitle'],
                        'author' => $parsedRequest['bookAuthor'],
                        'description' => $parsedRequest['bookDescription'],
                        'measures' => $parsedRequest['bookMeasures'],
                        'pages' => $parsedRequest['bookPageNumber'],
                        'language' => $parsedRequest['bookLanguage'],
                        'isbn' => $parsedRequest['bookIsbn'],
                        'bookbinding' => $parsedRequest['bookBinding'],
                        'edition' => $parsedRequest['bookEdition'],
                        'physicalPrice' => $parsedRequest['bookPhysicalPrice'],
                        'digitalPrice' => $parsedRequest['bookDigitalPrice'],
                        'discount' => $parsedRequest['bookDiscount'],
                        'stock' => $parsedRequest['bookStock'],
                        'images' => $this->validateStringForFileName($parsedRequest['bookTitle']),
                        'previewImage' => $name,
                        'promoted' => $promoted,
                        'certificates' => $parsedRequest['certificates'],
                        'apiKey' => env('SLICE_KEY', null)
                        ];
                        
                    $jsonData = json_encode($jsonData);
                   
                    $data = [
                        'apiKey' => env('SLICE_KEY', null),
                        'bookData' => $jsonData,
                        'returnUrl' => 'https://editorialparalelo28.com/pdfSlicerResponse',
                        'pdf' => url('/').'/temp/'.$pdfName
                    ];
                    
                    $request = curl_init('http://pdfslicer.gesforcan.com');
                    curl_setopt($request, CURLOPT_POST, true);
                    curl_setopt($request, CURLOPT_POSTFIELDS, $data);
                    curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
                    curl_exec($request);
                    curl_close($request);
                    
                    if(File::exists(public_path().'/temp/'.$pdfName)){
                        File::delete(public_path().'/temp/'.$pdfName);
                    }
                    
                    return Redirect::back()->with('successMessage', 'El libro "'.$parsedRequest['bookTitle'].'" se subirá en unos momentos.');
                }
            }catch (Exception $e){
                return Redirect::back()->withErrors('Ha sucedido un error, reintentelo más tarde.');
            }
        }
        return abort(404);
    }

    //---------- A D M I N   E D I T   B O O K  ----------//

    function editBook(Request $request){
        $user = $this->validateUser($request->cookie('user'), $request->cookie('sessionToken'));

        if($user && $user['admin'] > 0){
            $book = Book::where('title', htmlspecialchars($request['oldTitle']))->first();

            $fullRequest = $request->all();

            foreach ($fullRequest as $key => $value){
                if(is_array($value)) continue;
                if(strlen(htmlspecialchars($value)) < 1){
                    $fullRequest[$key] = null;
                }else{
                    $fullRequest[$key] = strip_tags($value);
                }
            }
            
            $bookOldTitle = $book['images'];

            if($book){
               if(strlen($fullRequest['bookTitle']) > 0){
                    $book['title'] = $fullRequest['bookTitle'];
                    $fileName = $this->validateStringForFileName($fullRequest['bookTitle']);
                    $oldImagesName = $book['images'];
                    $imagesToRefactor = Image::where('affiliationName', $oldImagesName)->get();
                    foreach ($imagesToRefactor as $imageToRefactor){
                        $source = $imageToRefactor['imgSrc'];
                        $sourceParts = explode('/', $source);
                        $imageToRefactor['imgSrc'] = $fileName.'/'.$sourceParts[1];
                        $imageToRefactor['affiliationName'] = $fileName;
                        $imageToRefactor->save();
                    }

                    $inputUrl = base_path().'/resources/books/';

                    if(File::exists('files/bookPreviews/'.$oldImagesName)){
                        File::move('files/bookPreviews/'.$oldImagesName, 'files/bookPreviews/'.$fileName);
                    }

                    if(File::exists($inputUrl.$oldImagesName.'.pdf')){
                        File::move($inputUrl.$oldImagesName.'.pdf',$inputUrl.$fileName.'.pdf' );
                    }
                    
                    $book['images'] = $fileName;
                }
                if(strlen($fullRequest['bookAuthor']) > 0) $book['author'] = $fullRequest['bookAuthor'];
                if(strlen($fullRequest['bookIsbn']) > 0) $book['isbn'] = $fullRequest['bookIsbn'];
                if(strlen($fullRequest['bookPhysicalPrice']) > 0)$book['physicalPrice'] = $fullRequest['bookPhysicalPrice'];
                if(strlen($fullRequest['bookDigitalPrice']) > 0)$book['digitalPrice'] = $fullRequest['bookDigitalPrice'];
                if(strlen($fullRequest['bookStock']) > 0)$book['stock'] = $fullRequest['bookStock'];
                $book['description'] = $fullRequest['bookDescription'];
                $book['measures'] = $fullRequest['bookMeasures'];
                $book['pages'] = $fullRequest['bookPageNumber'];
                $book['language'] = $fullRequest['bookLanguage'];
                $book['bookbinding'] = $fullRequest['bookBinding'];
                $book['edition'] = $fullRequest['bookEdition'];
                $book['discount'] = $fullRequest['bookDiscount'];
                
                if(isset($parsedRequest['bookPromote']))  $book['promoted'] = 1;
                else $book['promoted'] = 0;

                if(isset($request['bookImage'])){
                    $img = $request['bookImage'];

                    if ($img != null) {

                        if (filesize($img) > 500000) {
                            return Redirect::back()->withErrors('Las imágenes no pueden superar 500KB de tamaño.');
                        }

                        if(File::exists('images/uploads/'.$book['previewImage'])){
                            File::delete('images/uploads/'.$book['previewImage']);
                        }

                        $time = strtotime(date('d/m/y h:i:s'));

                        $name = $this->generateRandomString(20) . $time . '.' . $img->getClientOriginalExtension();

                        $destinationPath = public_path('/images/uploads');

                        $img->move($destinationPath, $name);

                        $book['previewImage'] = $name;
                    }
                }

                $bookCertificates =  BookCertificate::where('bookId', $book['id'])->get();

                foreach ($bookCertificates as $bookCertificate){
                    $bookCertificate->delete();
                }

                foreach ($request['certificates'] as $certificate){
                    $certificate = Certificate::where('certificate', htmlspecialchars($certificate))->first();

                    if($certificate){
                        BookCertificate::create([
                            'bookId' => $book['id'],
                            'certificate' => $certificate['id']
                        ]);
                    }
                }

                $book->save();
                
                $file = $request->file('bookFile');
                
                if ($file != null) {
                    $time = strtotime(date('d/m/y h:i:s'));

                    $pdfName = $this->generateRandomString(20) . $time . '.' . $file->getClientOriginalExtension();

                    $destinationPath = public_path().'/temp';

                    $file->move($destinationPath, $pdfName);
                    
                    try{
                        $pdf = new \Mpdf\Mpdf();
                        $pdf->SetSourceFile($destinationPath.'/'.$pdfName);
                    }catch (Exception $e) {
                        return Redirect::back()->withErrors('El PDF no puede superar la versión 1.4.');
                    }
                    
                    if(File::exists(base_path().'/resources/books/'.$bookOldTitle.'.pdf')){
                        File::delete(base_path().'/resources/books/'.$bookOldTitle.'.pdf');
                    }
                    
                    if(File::exists(public_path().'/files/bookPreviews/'.$bookOldTitle)){
                        File::deleteDirectory(public_path().'/files/bookPreviews/'.$bookOldTitle);
                    }
                    
                    $images = Image::where('affiliationName', $bookOldTitle)->get();
                    
                    foreach($images as $image){
                        $image->delete();
                    }
                    
                    $jsonData = [
                        'images' => $this->validateStringForFileName($book['images']),
                        'apiKey' => env('SLICE_KEY', null)
                    ];
                        
                    $jsonData = json_encode($jsonData);
                   
                    $data = [
                        'apiKey' => env('SLICE_KEY', null),
                        'bookData' => $jsonData,
                        'returnUrl' => 'https://editorialparalelo28.com/pdfSlicerEditResponse',
                        'pdf' => url('/').'/temp/'.$pdfName
                    ];
                    
                    $request = curl_init('http://pdfslicer.gesforcan.com');
                    curl_setopt($request, CURLOPT_POST, true);
                    curl_setopt($request, CURLOPT_POSTFIELDS, $data);
                    curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
                    curl_exec($request);
                    curl_close($request);
                    
                    if(File::exists(public_path().'/temp/'.$pdfName)){
                        File::delete(public_path().'/temp/'.$pdfName);
                    }
                    
                }else{
                    return Redirect::back()->withErrors('Ha sucedido un error, reintentelo más tarde.');
                }

                return Redirect::back()->with('successMessage', 'El libro "'.$book['title'].'" ha sido modificado con éxito.');
            }
        }
        return abort(404);
    }

    //---------- A D M I N   D E L E T E   B O O K  ----------//

    function removeBook(Request $request){
        $user = $this->validateUser($request->cookie('user'), $request->cookie('sessionToken'));

        if($user && $user['admin'] > 0){
            $book = htmlspecialchars($request['deleteBook']);

            $book = Book::where('title', $book)->first();

            if($book){
                $deleteBook = $this->deleteBook($request, $book);

                if(!$deleteBook) return Redirect::back()->withErrors('Ha sucedido un error, reintentelo más tarde.');

                return Redirect::back()->with('successMessage', 'El libro "'.$book['title'].'" ha sido borrado con éxito.');
            }else return Redirect::back()->withErrors('Ha sucedido un error, reintentelo más tarde.');
        }
        return abort(404);
    }

    //---------- A D M I N   D E L E T E   F I E L D ----------//

    function deleteField(Request $request){
        $user = $this->validateUser($request->cookie('user'), $request->cookie('sessionToken'));

        if($user && $user['admin'] > 0){
            if(isset($request['deleteCategory'])){
                $category = htmlspecialchars($request['deleteCategory']);

                $category = Category::where('category', $category)->first();

                if($category){
                    if(isset($request['changeAll'])){
                        try{
                            $certificates = Certificate::where('category', $category['category'])->get();

                            $applyForAll = htmlspecialchars($request['applyForAll']);

                            if($applyForAll == 'Borrar'){
                                foreach ($certificates as $certificate){

                                    $bookRelations = BookCertificate::where('certificate', $certificate['id'])->get();

                                    $books =[];

                                    foreach ($bookRelations as $bookRelation){
                                        $book = Book::where('id', $bookRelation['bookId'])->first();

                                        if($book){
                                            $isMultiple =
                                                BookCertificate::where('bookId', $book['id'])
                                                    ->where('certificate', '!=', $certificate['id'])
                                                    ->first();
                                            if(!$isMultiple) array_push($books, $book);
                                        };
                                    }

                                    foreach ($books as $book){
                                        $deleteBook = $this->deleteBook($request, $book);

                                        if(!$deleteBook) return Redirect::back()->withErrors('Ha sucedido un error, reintentelo más tarde.');
                                    }

                                    if(File::exists('images/uploads/'.$certificate['imageLink'])){
                                        File::delete('images/uploads/'.$certificate['imageLink']);
                                    }
                                    $certificate->delete();
                                }
                            }else{
                                $categoryAfter = Category::where('category', $applyForAll)->first();

                                if($categoryAfter){
                                    foreach ($certificates as $certificate){
                                        $certificate['category'] = $categoryAfter['category'];
                                        $certificate->save();
                                    }
                                }
                            }

                        }catch (Exception $e){
                            return Redirect::back()->withErrors('Ha sucedido un error, reintentelo más tarde.');
                        }
                    }else{
                        $refactorFields = $request['refactorField'];
                        $refactorSelects = $request['refactorSelect'];

                        if(Count($refactorFields) == Count($refactorSelects)){
                            for($i = 0; $i < Count($refactorFields); $i++){
                                try{

                                    $refactorFields[$i] = htmlspecialchars($refactorFields[$i]);
                                    $refactorSelects[$i] = htmlspecialchars($refactorSelects[$i]);

                                    $certificate = Certificate::where('certificate', $refactorFields[$i])->first();

                                    if($certificate){
                                        if($refactorSelects[$i] == 'Borrar'){
                                            $bookRelations = BookCertificate::where('certificate', $certificate['id'])->get();

                                            $books =[];

                                            foreach ($bookRelations as $bookRelation){
                                                $book = Book::where('id', $bookRelation['bookId'])->first();

                                                if($book){
                                                    $isMultiple =
                                                        BookCertificate::where('bookId', $book['id'])
                                                            ->where('certificate', '!=', $certificate['id'])
                                                            ->first();
                                                    if(!$isMultiple) array_push($books, $book);
                                                };
                                            }

                                            foreach ($books as $book){
                                                $deleteBook = $this->deleteBook($request, $book);

                                                if(!$deleteBook) return Redirect::back()->withErrors('Ha sucedido un error, reintentelo más tarde.');
                                            }

                                            if(File::exists('images/uploads/'.$certificate['imageLink'])){
                                                File::delete('images/uploads/'.$certificate['imageLink']);
                                            }
                                            $certificate->delete();

                                        }else{
                                            $categoryAfter = Category::where('category', $refactorSelects[$i])->first();

                                            if($categoryAfter){
                                                $certificate['category'] = $categoryAfter['category'];
                                                $certificate->save();
                                            }
                                        }
                                    }
                                }catch (Exception $e){
                                    return Redirect::back()->withErrors('Ha sucedido un error, reintentelo más tarde.');
                                }
                            }
                        }
                    }

                    if(File::exists('images/uploads/'.$category['imageLink'])){
                        File::delete('images/uploads/'.$category['imageLink']);
                    }

                    if(File::exists('images/uploads/'.$category['sampleBookImage'])){
                        File::delete('images/uploads/'.$category['sampleBookImage']);
                    }

                    $category->delete();

                    return Redirect::back()->with('successMessage', '"'.$category['category'].'" ha sido borrado con éxito.');

                }
            }else if(isset($request['deleteCertificate'])){
                $certificate = htmlspecialchars($request['deleteCertificate']);

                $certificate = Certificate::where('certificate', $certificate)->first();

                if($certificate){
                    if(isset($request['changeAll'])){
                        try{
                            $bookRelations = BookCertificate::where('certificate', $certificate['id'])->get();

                            $books =[];

                            foreach ($bookRelations as $bookRelation){
                                $book = Book::where('id', $bookRelation['bookId'])->first();

                                if($book){
                                    $isMultiple =
                                        BookCertificate::where('bookId', $book['id'])
                                            ->where('certificate', '!=', $certificate['id'])
                                            ->first();
                                    if(!$isMultiple) array_push($books, $book);
                                };
                            }

                            $applyForAll = htmlspecialchars($request['applyForAll']);

                            if($applyForAll == 'Borrar'){
                                foreach ($books as $book){
                                    $deleteBook = $this->deleteBook($request, $book);

                                    if(!$deleteBook) return Redirect::back()->withErrors('Ha sucedido un error, reintentelo más tarde.');
                                }
                            }else{
                                $certificateAfter = Certificate::where('certificate', $applyForAll)->first();

                                if($certificateAfter){
                                    foreach ($books as $book){
                                        $bookRelation = BookCertificate::where('bookId', $book['id'])->first();

                                        if($bookRelation){
                                            $bookRelation['certificate'] = $certificateAfter['id'];
                                            $bookRelation->save();
                                        }
                                    }
                                }
                            }

                            $bookRelations = BookCertificate::where('certificate', $certificate['id'])->get();

                            foreach ($bookRelations as $bookRelation){
                                $bookRelation->delete();
                            }

                            $certificate->delete();

                            return Redirect::back()->with('successMessage', '"'.$certificate['certificate'].'" ha sido borrado con éxito.');

                        }catch (Exception $e){
                            return Redirect::back()->withErrors('Ha sucedido un error, reintentelo más tarde.');
                        }
                    }else{
                        $refactorFields = $request['refactorField'];
                        $refactorSelects = $request['refactorSelect'];

                        if(Count($refactorFields) == Count($refactorSelects)){
                            for($i = 0; $i < Count($refactorFields); $i++){
                                try{
                                    $refactorFields[$i] = htmlspecialchars($refactorFields[$i]);
                                    $refactorSelects[$i] = htmlspecialchars($refactorSelects[$i]);

                                    $book = Book::where('title', $refactorFields[$i])->first();

                                    if($book){
                                        if($refactorSelects[$i] == 'Borrar'){
                                            $deleteBook = $this->deleteBook($request, $book);

                                            if(!$deleteBook) return Redirect::back()->withErrors('Ha sucedido un error, reintentelo más tarde.');
                                        }else{
                                            $certificateAfter = Certificate::where('certificate', $refactorSelects[$i])->first();

                                            if($certificateAfter){
                                                $alreadyRelated =
                                                    BookCertificate::where('bookId', $book['id'])
                                                        ->where('certificate', $certificateAfter['id'])
                                                        ->first();
                                                if(!$alreadyRelated){
                                                    BookCertificate::create([
                                                        'bookId' => $book['id'],
                                                        'certificate' => $certificateAfter['id']
                                                    ]);
                                                }
                                            }
                                        }
                                    }
                                }catch (Exception $e){
                                    return Redirect::back()->withErrors('Ha sucedido un error, reintentelo más tarde.');
                                }
                            }

                            $bookRelations = BookCertificate::where('certificate', $certificate['id'])->get();

                            foreach ($bookRelations as $bookRelation){
                                $bookRelation->delete();
                            }

                            if(File::exists('images/uploads/'.$certificate['imageLink'])){
                                File::delete('images/uploads/'.$certificate['imageLink']);
                            }

                            $certificate->delete();

                            return Redirect::back()->with('successMessage', '"'.$certificate['certificate'].'" ha sido borrado con éxito.');
                        }
                    }
                }
            }
        }
        return abort(404);
    }

    //---------- A D M I N   D E L E T E   B O O K ----------//

    private function deleteBook(Request $request, $book){
        $user = $this->validateUser($request->cookie('user'), $request->cookie('sessionToken'));

        if($user && $user['admin'] > 0){
            $fileName = $this->validateStringForFileName($book['title']);

            if(File::exists(  base_path().'/resources/books/'.$fileName.'.pdf')){
                File::delete(base_path().'/resources/books/'.$fileName.'.pdf');
            }

            if(File::exists('images/uploads/'.$book['previewImage'])){
                File::delete('images/uploads/'.$book['previewImage']);
            }

            File::deleteDirectory('files/bookPreviews/'.$book['images']);

            $images = Image::where('affiliationName', $book['images'])->get();

            foreach ($images as $image){
                $image->delete();
            }

            $statistics = Statistic::where('bookId', $book['id'])->get();

            foreach ($statistics as $statistic){
                $statistic->delete();
            }

            $bookRelations = BookCertificate::where('bookId', $book['id'])->get();

            foreach ($bookRelations as $bookRelation){
                $bookRelation->delete();
            }

            $libraries = Library::where('bookId', $book['id'])->get();

            foreach ($libraries as $library){
                $library->delete();
            }

            $wishLists = WishList::where('bookId', $book['id'])->get();

            foreach ($wishLists as $wishList){
                $wishList->delete();
            }

            $book->delete();

            return true;
        }
        return false;
    }


    //---------- A D M I N   S L I C E   B O O K   T O   I M A G E S ----------//
    
    function slicerResponse(Request $request){
        $bookData = json_decode($request['bookData'], true);
        $pdf = htmlspecialchars($request['pdf']);
        
        $request = $request->all();
        
        $bookPreviewsDirectory = public_path().'/files/bookPreviews/';
        
        $outputDirectory = $bookPreviewsDirectory.$bookData['images'];
        
        if(trim($outputDirectory) == $bookPreviewsDirectory || $bookData['apiKey'] != env('SLICE_KEY', null)) return;
        
        if(File::exists($outputDirectory)) File::deleteDirectory($outputDirectory);
                    
        mkdir($outputDirectory);
        
        foreach($request as $key=>$value){
            $name = explode('-', $key);
            if($name[0] == 'images'){
                try {
                    $refactoredName = bin2hex(random_bytes(10));
                } catch (Exception $e) {
                    $refactoredName = $this->generateRandomString(20);
                }

                $refactoredName .= $name[1] . round(microtime(true));

                $renamedFile = $outputDirectory . '/' . $refactoredName . '.jpg';
                
                file_put_contents($renamedFile, fopen($value, 'r'));
                
                 Image::create([
                    'affiliationName' => $bookData['images'],
                    'imgSrc' => $bookData['images'] . '/' . $refactoredName . '.jpg'
                ]);
            }
        }
        
        file_put_contents(base_path().'/resources/books/'.$bookData['images'].'.pdf', fopen($pdf, 'r'));
        
         $book = Book::create([
            'title' => $bookData['title'],
            'author' => $bookData['author'],
            'description' => $bookData['description'],
            'measures' => $bookData['measures'],
            'pages' => $bookData['pages'],
            'language' => $bookData['language'],
            'isbn' => $bookData['isbn'],
            'bookbinding' => $bookData['bookbinding'],
            'edition' => $bookData['edition'],
            'physicalPrice' => $bookData['physicalPrice'],
            'digitalPrice' => $bookData['digitalPrice'],
            'discount' => $bookData['discount'],
            'stock' => $bookData['stock'],
            'images' => $bookData['images'],
            'previewImage' => $bookData['previewImage'],
            'promoted' => $bookData['promoted']
        ]);

        Statistic::create([
            'bookId' => $book['id']
        ]);

        foreach ($bookData['certificates'] as $certificate){
            $certificate = Certificate::where('certificate', $certificate)->first();

            if($certificate){
                BookCertificate::create([
                    'bookId' => $book['id'],
                    'certificate' => $certificate['id']
                ]);
            }
        }
    }
    
    function slicerEditResponse(Request $request){
        $bookData = json_decode($request['bookData'], true);
        $pdf = htmlspecialchars($request['pdf']);
        
        $request = $request->all();
        
        $bookPreviewsDirectory = public_path().'/files/bookPreviews/';
        
        $outputDirectory = $bookPreviewsDirectory.$bookData['images'];
        
        if(trim($outputDirectory) == $bookPreviewsDirectory || $bookData['apiKey'] != env('SLICE_KEY', null)) return;
        
        if(File::exists($outputDirectory)) File::deleteDirectory($outputDirectory);
                    
        mkdir($outputDirectory);
        
        foreach($request as $key=>$value){
            $name = explode('-', $key);
            if($name[0] == 'images'){
                try {
                    $refactoredName = bin2hex(random_bytes(10));
                } catch (Exception $e) {
                    $refactoredName = $this->generateRandomString(20);
                }

                $refactoredName .= $name[1] . round(microtime(true));

                $renamedFile = $outputDirectory . '/' . $refactoredName . '.jpg';
                
                file_put_contents($renamedFile, fopen($value, 'r'));
                
                 Image::create([
                    'affiliationName' => $bookData['images'],
                    'imgSrc' => $bookData['images'] . '/' . $refactoredName . '.jpg'
                ]);
            }
        }
        
        file_put_contents(base_path().'/resources/books/'.$bookData['images'].'.pdf', fopen($pdf, 'r'));
    }
    
    //---------- A D M I N   A D D   N E W  ----------//

    function addNew(Request $request){
        $user = $this->validateUser($request->cookie('user'), $request->cookie('sessionToken'));

        if($user && $user['admin'] > 0){
            $title = htmlspecialchars($request['entryTitle']);
            $category = htmlspecialchars($request['entryCategory']);
            $file = $request['entryImage'];

            if(strlen($title) < 1  || strlen($category) < 1 || $file == null) return Redirect::back()->withErrors('Ha de completar los campos obligatorios.');

            $content = $this->stripTagsAndAttributes($request['entryContent'], '<h1><h2><h3><h4><h5><h6><strong><b><a><u><em><li><ul><ol><p><br>');

            $time = strtotime(date('d/m/y h:i:s'));
            $random = $this->generateRandomString(20);

            $name = $random . $time . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('/images/uploads');

            if (filesize($file) > 500000) {
                return Redirect::back()->withErrors('Las imágenes no pueden superar 500KB de tamaño.');
            } else {
                $file->move($destinationPath, $name);
            }

            BlogEntry::create([
                'title' => $title,
                'category' => $category,
                'content' => $content,
                'imgLink' => $name
            ]);

            return Redirect::back()->with('successMessage', 'La entrada "'.$title.'" ha sido creada con éxito.');
        }
        return abort(404);
    }

    function editNew(Request $request){
        $user = $this->validateUser($request->cookie('user'), $request->cookie('sessionToken'));

        if($user && $user['admin'] > 0){
            $blogEntry = BlogEntry::where('id', $request['entryId'])->first();

            if(!$blogEntry) return Redirect::back()->withErrors('Ha sucedido un error, reintentelo más tarde.');

            $title = htmlspecialchars($request['entryTitle']);
            $category = htmlspecialchars($request['entryCategory']);
            $content = $this->stripTagsAndAttributes($request['entryContent'], '<h1><h2><h3><h4><h5><h6><strong><b><a><u><em><li><ul><ol><p><br>');
            $file = $request['entryImage'];

            if(strlen($title) > 0) $blogEntry['title'] = $title;
            if(strlen($category) > 0) $blogEntry['category'] = $category;

            $blogEntry['content'] = $content;

            if($file != null){
                $time = strtotime(date('d/m/y h:i:s'));
                $random = $this->generateRandomString(20);

                $name = $random . $time . '.' . $file->getClientOriginalExtension();
                $destinationPath = public_path('/images/uploads');

                if (filesize($file) > 500000) {
                    return Redirect::back()->withErrors('Las imágenes no pueden superar 500KB de tamaño.');
                } else {
                    $file->move($destinationPath, $name);
                }

                if(File::exists('images/uploads/'.$blogEntry['imageLink'])){
                    File::delete('images/uploads/'.$blogEntry['imageLink']);
                }

                $blogEntry['imgLink'] = $name;
            }

            $blogEntry->save();

            return Redirect::back()->with('successMessage', 'La entrada "'.$title.'" ha sido modificada con éxito.');
        }
        return abort(404);
    }

    function deleteNew(Request $request){
        $user = $this->validateUser($request->cookie('user'), $request->cookie('sessionToken'));

        if($user && $user['admin'] > 0){
            $new = BlogEntry::where('id', htmlspecialchars($request['deleteNew']))->first();

            if(!$new) return Redirect::back()->withErrors('Ha sucedido un error, reintentelo más tarde.');

            if(File::exists('images/uploads/'.$new['imageLink'])){
                File::delete('images/uploads/'.$new['imageLink']);
            }

            $new->delete();
            return Redirect::back()->with('successMessage', 'La entrada "'.$new['title'].'" ha sido borrada con éxito.');
        }
        return abort(404);
    }
    
   /*--------------------- S U B S C R I P T I O N ---------------------*/

    function newsletterPromotion(Request $request){
        $user = $this->validateUser($request->cookie('user'), $request->cookie('sessionToken'));

        if($user && $user['admin'] > 0) return $this->viewDispatcher('newsletterPromotion', $request);

        return abort(404);
    }

    function emailAllSubscribers(Request $request){
        $user = $this->validateUser($request->cookie('user'), $request->cookie('sessionToken'));

        if($user && $user['admin'] > 0){
            $subject = htmlspecialchars($request['subject']);
            $content = $this->stripTagsAndAttributes($request['emailContent'], '<h1><h2><h3><h4><h5><h6><strong><b><a><u><em><li><ul><ol><p><br>');

            if(isset($request['imageLink']) && strlen($request['imageLink']) > 0) $imageLink = htmlspecialchars($request['imageLink']);
            else $imageLink = null;

            if(!$subject || strlen($subject) < 1
                || !$content || strlen($content) <1 ) return Redirect::back()->withErrors('Todos los campos han de tener contenido.');

            $subscribers = Subscriber::all();

            foreach ($subscribers as $subscriber){
                ProcessEmailQueue::dispatch($subscriber, $subject, $content, $imageLink);
            }
            return Redirect::back()->with('successMessage', 'La promoción con asunto "'.$subject.'" se ha realizado con exito.');
        }
        return abort(404);
    }
    
     //---------- S U R V E Y ----------//

    function editSurveyView(Request $request){
        $user = $this->validateUser($request->cookie('user'), $request->cookie('sessionToken'));

        if($user && $user['admin'] > 0){
            $surveyQuestions = SurveyQuestion::orderBy('order', 'ASC')->get();
            $surveyPossibleAnswers = SurveyPossibleAnswer::all();
            $surveyAnswers = SurveyAnswer::all();

            $surveyOrderedAnswers = [];

            foreach ($surveyAnswers as $surveyAnswer){
                if(array_key_exists($surveyAnswer['question'], $surveyOrderedAnswers)) array_push($surveyOrderedAnswers[$surveyAnswer['question']], $surveyAnswer['answer']);
                else $surveyOrderedAnswers[$surveyAnswer['question']] = [$surveyAnswer['answer']];
            }

            foreach ($surveyOrderedAnswers as $key => $surveyOrderedAnswer){
                $amountOfAnswers = [];
                foreach ($surveyOrderedAnswer as $currentAnswer){
                    if(array_key_exists($currentAnswer, $amountOfAnswers)) $amountOfAnswers[$currentAnswer] += 1;
                    else $amountOfAnswers[$currentAnswer] = 1;
                }
                $surveyOrderedAnswers[$key] = $amountOfAnswers;
            }
            
            foreach ($surveyOrderedAnswers as $key => $value){
                arsort($surveyOrderedAnswers[$key]);
            }
             
            $currentSurvey = [];
            $historicalSurvey = [];
            $personalSurvey = [];

            foreach ($surveyOrderedAnswers as $key => $value){
                $inArray = false;

                foreach ($surveyQuestions as $surveyQuestion){
                    if($key == $surveyQuestion['question']){
                        $inArray = true;
                        if($surveyQuestion['survey'] == 0) $currentSurvey[$key] = $value;
                        else $personalSurvey[$key] = $value;
                    }
                }

                if(!$inArray) $historicalSurvey[$key] = $value;
            }

            $surveyGeneral = [];
            $surveyPersonal = [];

            foreach ($surveyQuestions as $surveyQuestion){
                if($surveyQuestion['survey'] == 0) array_push($surveyGeneral, $surveyQuestion);
                else array_push($surveyPersonal, $surveyQuestion);
            }

            return $this->viewDispatcher('adminEditSurvey', $request, [[$surveyGeneral, $surveyPersonal], $surveyPossibleAnswers, [$currentSurvey, $personalSurvey, $historicalSurvey]]);
        }

        return abort(404);
    }

    function editSurvey(Request $request){
        $user = $this->validateUser($request->cookie('user'), $request->cookie('sessionToken'));

        if($user && $user['admin'] > 0){
            $surveyQuestions = json_decode($request['surveyData'], true);

            $previousSurvey = SurveyQuestion::all();
            $previousSurveyPossibleAnswers = SurveyPossibleAnswer::all();

            foreach ($surveyQuestions as $surveyQuestion){
                $question = htmlspecialchars($surveyQuestion['question']);
                $type = intval(htmlspecialchars($surveyQuestion['type']));
                $order = intval(htmlspecialchars($surveyQuestion['order']));
                $survey = intval(htmlspecialchars($surveyQuestion['survey']));

                if(!$question || strlen($question) < 1 || $type > 3 || $type < 0 || $order < 0) return abort(404);

                $generatedSurveyQuestion = SurveyQuestion::create([
                    'question' => $question,
                    'type' => $type,
                    'order' => $order,
                    'survey' => $survey
                ]);

                foreach ($surveyQuestion['answers'] as $answer){
                    $answer = htmlspecialchars($answer);

                    if(strlen($answer) < 1) continue;

                    SurveyPossibleAnswer::create([
                        'surveyId' => $generatedSurveyQuestion['id'],
                        'possibleAnswer' => $answer
                    ]);
                }
            }

            foreach ($previousSurvey as $previousSurveyQuestion){
                $previousSurveyQuestion->delete();
            }

            foreach ($previousSurveyPossibleAnswers as $previousSurveyPossibleAnswer){
                $previousSurveyPossibleAnswer->delete();
            }

            return Redirect::back()->with('successMessage', 'La encuesta de satisfacción ha sido editada con éxito.');
        }
        return abort(404);
    }

    
    //---------- E X P E D I T E   C O U P O N S ----------//

    function expediteCoupons(Request $request){
        $user = $this->validateUser($request->cookie('user'), $request->cookie('sessionToken'));

        if($user && $user['admin'] > 0){
            $activeCoupons = [];
            $inactiveCoupons = [];

            $allCoupons = Coupon::all();

            foreach ($allCoupons as $coupon){
                if(($coupon['valid_until'] == null || strtotime($coupon['valid_until'])> strtotime(date('Y-m-d'))) && $coupon['uses']>0) array_push($activeCoupons, $coupon);
                else array_push($inactiveCoupons, $coupon);
            };

            return $this->viewDispatcher('expediteCoupons', $request, [$activeCoupons, $inactiveCoupons]);
        }

        return abort(404);
    }

     function generateCoupons(Request $request){
        $user = $this->validateUser($request->cookie('user'), $request->cookie('sessionToken'));

        if($user && $user['admin'] > 0){
            $code = htmlspecialchars($request['code']);
            $discount = htmlspecialchars($request['discount']);
            $amount = htmlspecialchars($request['amount']);
            $uses = htmlspecialchars($request['uses']);
            $validUntil = htmlspecialchars($request['validUntil']);

            if($discount < 1 || $discount > 100 || $amount < 1 ||$amount > 50 || $uses < 1 || $uses > 500) return abort(404);

            if($code && strlen($code) > 0){
                try{
                    Coupon::create([
                        'code' => $code,
                        'discount' => $discount,
                        'uses' => $uses,
                        'valid_until' => $validUntil
                    ]);
                }catch (Exception $e){
                    return Redirect::back()->withErrors('Ya existe un cupón con ese nombre.');
                }
            }else{
                for ($i = 0; $i < $amount; $i++){
                    $random = $this->generateRandomString(10);

                    try{
                        Coupon::create([
                            'code' => $random,
                            'discount' => $discount,
                            'uses' => $uses,
                            'valid_until' => $validUntil
                        ]);
                    }catch (Exception $e){
                       $i--;
                    }
                }
            }

            return Redirect::back()->with('successMessage', 'Los cupones han sido creados con exito, puede encontrarlos en la sección "Cupones Activos".');
        }
        return abort(404);
    }

    function removeCoupon(Request $request, $parameter){
        $user = $this->validateUser($request->cookie('user'), $request->cookie('sessionToken'));

        if($user && $user['admin'] > 0){
            if($parameter){
                $parameter = htmlspecialchars($parameter);

                $coupon = Coupon::where('id', $parameter)->first();

                if($coupon){
                    $redeemed = CouponRedeemed::where('couponCode', $coupon['code'])->get();

                    foreach ($redeemed as $currentIteration){
                        $currentIteration->delete();
                    }

                    $coupon->delete();
                    return Redirect::back()->with('successMessage', 'El cupón ha sido borrado con exito.');
                }
            }
        }
        return abort(404);
    }
    
    //---------- A D M I N   T A X E S ----------//

    function administrateTaxes(Request $request){
        $user = $this->validateUser($request->cookie('user'), $request->cookie('sessionToken'));

        if($user && $user['admin'] > 0){
            $taxIgicPhysical = Tax::where('tax', 'IGIC')->where('format', 0)->first();
            $taxIgicDigital = Tax::where('tax', 'IGIC')->where('format', 1)->first();
            $taxIvaPhysical = Tax::where('tax', 'IVA')->where('format', 0)->first();
            $taxIvaDigital = Tax::where('tax', 'IVA')->where('format', 1)->first();

            return $this->viewDispatcher('administrateTaxes', $request, [$taxIgicPhysical['amount'], $taxIgicDigital['amount'], $taxIvaPhysical['amount'], $taxIvaDigital['amount']]);
        }
        return abort(404);
    }

    function updateTaxes(Request $request){
        $user = $this->validateUser($request->cookie('user'), $request->cookie('sessionToken'));

        if($user && $user['admin'] > 0){
            $taxIgicPhysical = Tax::where('tax', 'IGIC')->where('format', 0)->first();
            $taxIgicDigital = Tax::where('tax', 'IGIC')->where('format', 1)->first();
            $taxIvaPhysical = Tax::where('tax', 'IVA')->where('format', 0)->first();
            $taxIvaDigital = Tax::where('tax', 'IVA')->where('format', 1)->first();

            $taxIgicPhysical['amount'] = htmlspecialchars($request['igicPhysical']);
            $taxIgicDigital['amount'] = htmlspecialchars($request['igicDigital']);
            $taxIvaPhysical['amount'] = htmlspecialchars($request['ivaPhysical']);
            $taxIvaDigital['amount'] = htmlspecialchars($request['ivaDigital']);

            $taxIgicPhysical->save();
            $taxIgicDigital->save();
            $taxIvaPhysical->save();
            $taxIvaDigital->save();

            return Redirect::back()->with('successMessage', 'Los impuestos aplicados a los productos han sido modificados con éxito.');
        }
        return abort(404);
    }
    
    //---------- A D M I N   R E F O U N D S ----------//

    function administrateShipments(Request $request){
        $user = $this->validateUser($request->cookie('user'), $request->cookie('sessionToken'));

        if($user && $user['admin'] > 0){

            $shipmentsInProgress = ShoppingHistory::where('status', '!=', 'Entregado')->orderBy('created_at', 'DESC')->get();

            $finalizedShipments = ShoppingHistory::where('status', 'Entregado')->orderBy('created_at', 'DESC')->get();

            foreach ($shipmentsInProgress as $shipmentInProgress){
                $user = User::where('id', $shipmentInProgress['userId'])->first();
                $userSettings = UserSetting::where('userId', $user['id'])->first();
                $ticket = RefundTicket::where('shoppingHistoryId', $shipmentInProgress['id'])->where('status', 'Pendiente de revisión')->first();

                $shipmentInProgress['user'] = $userSettings['name'];
                $shipmentInProgress['email'] = $user['email'];

                if($ticket) $shipmentInProgress['ticket'] = true;
                else $shipmentInProgress['ticket'] = false;
            }

            foreach ($finalizedShipments as $finalizedShipment){
                $user = User::where('id', $finalizedShipment['userId'])->first();
                $userSettings = UserSetting::where('userId', $user['id'])->first();
                $ticket = RefundTicket::where('shoppingHistoryId', $finalizedShipment['id'])->where('status', 'Pendiente de revisión')->first();

                $finalizedShipment['user'] = $userSettings['name'];
                $finalizedShipment['email'] = $user['email'];

                if($ticket) $finalizedShipment['ticket'] = true;
                else $finalizedShipment['ticket'] = false;
            }

            return $this->viewDispatcher('administrateShipments', $request, [$shipmentsInProgress, $finalizedShipments]);
        }

        return abort(404);
    }

    function administrateRefundSolicitude(Request $request, $parameter){
        $user = $this->validateUser($request->cookie('user'), $request->cookie('sessionToken'));

        if($user && $user['admin'] > 0){
            if($parameter){
                $parameter = htmlspecialchars($parameter);

                $shoppingHistory = ShoppingHistory::where('shipmentCode', $parameter)->first();

                if(!$shoppingHistory) return abort(404);

                $sales = Sale::where('shipmentCode', $shoppingHistory['shipmentCode'])->get();

                foreach ($sales as $sale){
                    $product = Book::where('id', $sale['bookId'])->first();

                    $sale['product'] = $product['title'];
                }

                $ticket = RefundTicket::where('shoppingHistoryId', $shoppingHistory['id'])->first();

                return $this->viewDispatcher('administrateShipment', $request, [$shoppingHistory, $sales, $ticket]);
            }
        }
        return abort(404);
    }

    function updateShipment(Request $request, $parameter){
        $user = $this->validateUser($request->cookie('user'), $request->cookie('sessionToken'));

        if($user && $user['admin'] > 0){
            if($parameter){
                $parameter = htmlspecialchars($parameter);

                $shoppingHistory = ShoppingHistory::where('shipmentCode', $parameter)->first();

                if(!$shoppingHistory) return abort(404);

                $shoppingHistory['status'] = htmlspecialchars($request['status']);
                $shoppingHistory['details'] = htmlspecialchars($request['shipmentCode']);
                $shoppingHistory->save();

                $ticket = RefundTicket::where('shoppingHistoryId', $shoppingHistory['id'])->first();
                
                if($ticket){
                    if($ticket['status'] != htmlspecialchars($request['ticketStatus']) || $ticket['statusMessage'] != htmlspecialchars($request['response'])){
                        $ticket['statusMessage'] = htmlspecialchars($request['response']);
                    
                        $ticketStatus = htmlspecialchars($request['ticketStatus']);
                        
                        $customer = User::where('id', $shoppingHistory['userId'])->first();
                        
                        if(!$customer) return abort(404);
                        
                        $userSettings = UserSetting::where('userId', $customer['id'])->first();
                        
                        try{
                            $data = [
                                "name" => $userSettings['name'],
                                "email" => $customer['email'],
                                "shipmentCode" => $shoppingHistory['shipmentCode'],
                                "ticket" => $ticket,
                                "ticketStatus" => $ticketStatus
                            ];
                            
                            Mail::send('emails.refundResponse', $data, function($message) use ($data) {
                                    $message->to($data['email'], $data['name'])->subject('Devolución del pedido número '.$data['shipmentCode']);
                                    $message->from('noreply@editorialparalelo28.com', 'EditorialParalelo28');
                                });
                        }catch (Exception $e){
                            return Redirect::back()->withErrors('Ha ocurrido un error durante la devolución del pedido, vuelva a intentarlo más tarde. Si el error perdura contacte con nuestro sistema de asistencia técnica.');
                        }
                        
                    
                        if($ticketStatus == 'Aceptada' && $ticket['status'] != 'Aceptada'){
                            $invoiceDescription = 'Devolución del pedido número '.$shoppingHistory['shipmentCode'];
    
                            $merchantOrder = $shoppingHistory['shipmentCode'];
    
                            $totalPrice = str_replace(".", "", number_format($shoppingHistory['price'], 2));
    
                            //TPV Data
    
                            $tpvUrl = 'https://sis.redsys.es/sis/realizarPago';
                            $tpvKey = decrypt(env('SPECIAL_KEY', null));
    
                            $redsysRequestObject = new RedsysAPI;
    
                            $redsysRequestObject->setParameter("Ds_Merchant_MerchantCode", 66789215);
                            $redsysRequestObject->setParameter("Ds_Merchant_Terminal", 001);
                            $redsysRequestObject->setParameter("Ds_Merchant_Currency", 978);
                            $redsysRequestObject->setParameter("Ds_Merchant_TransactionType", 3);
                            $redsysRequestObject->setParameter("Ds_Merchant_Amount",$totalPrice);
                            $redsysRequestObject->setParameter("Ds_Merchant_Order", $merchantOrder);
                            $redsysRequestObject->setParameter("Ds_Merchant_ProductDescription", $invoiceDescription);
                            $redsysRequestObject->setParameter("Ds_Merchant_MerchantURL", "https://editorialparalelo28.com/notifyRefund");
                            $redsysRequestObject->setParameter("Ds_Merchant_UrlOK", "https://editorialparalelo28.com/validRefund");
                            $redsysRequestObject->setParameter("Ds_Merchant_UrlKO", "https://editorialparalelo28.com/invalidRefund");
                            $redsysRequestObject->setParameter("Ds_Merchant_MerchantName", "Editorial Paralelo28");
                            $redsysRequestObject->setParameter("Ds_Merchant_TransactionDate", date('Y-m-d'));
    
                            $parameters = $redsysRequestObject->createMerchantParameters();
                            $signature = $redsysRequestObject->createMerchantSignature($tpvKey);
    
                            $ticket->save();
                            
                            return Redirect::back()->with('redirectTPV', [$tpvUrl, 'HMAC_SHA256_V1', $parameters, $signature]);
                        }else{
                            $ticket['status'] = $ticketStatus;
                            $ticket->save();
                        }
                    }
                }
                return Redirect::back()->with('successMessage', 'El ticket ha sido modificada con éxito.');
            }
        }
        return abort(404);
    }
    
    function notifyRefund(Request $request){
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
                $currency = $redsysRequestObject->getParameter("Ds_Currency");
                $order = $redsysRequestObject->getParameter("Ds_Order");
                $merchantCode = $redsysRequestObject->getParameter("Ds_MerchantCode");
                $terminal = $redsysRequestObject->getParameter("Ds_Terminal");
                $transactionType = $redsysRequestObject->getParameter("Ds_TransactionType");

                if($response == 900 && $merchantCode == 66789215 && $terminal == '001' && $currency == 978 && $transactionType == 3){

                    $shoppingHistory = ShoppingHistory::where('shipmentCode', $order)->first();

                    if(!$shoppingHistory) return http_response_code(400);

                    $ticket = RefundTicket::where('shoppingHistoryId', $shoppingHistory['id'])->first();

                    if(!$ticket) return http_response_code(400);

                    $ticket['status'] = 'Aceptada';
                    
                    $ticket->save();

                    return http_response_code(200);
                }
            }
        }
        return abort(403);
    }

    function validRefund(Request $request){
        $user = $this->validateUser($request->cookie('user'), $request->cookie('sessionToken'));

        if ($user && $user['admin'] > 0) {
            $ds_SignatureVersion = htmlspecialchars($request['Ds_SignatureVersion']);
            $ds_MerchantParameters = htmlspecialchars($request['Ds_MerchantParameters']);
            $ds_Signature = htmlspecialchars($request['Ds_Signature']);

            if ($ds_SignatureVersion == 'HMAC_SHA256_V1') {
                $redsysRequestObject = new RedsysAPI;

                //TPV Data

                $tpvKey = decrypt(env('SPECIAL_KEY', null));

                $signatureValidation = $redsysRequestObject->createMerchantSignatureNotif($tpvKey, $ds_MerchantParameters);

                if ($signatureValidation == $ds_Signature) {
                    $response = intval($redsysRequestObject->getParameter("Ds_Response"));
                    $currency = $redsysRequestObject->getParameter("Ds_Currency");
                    $order = $redsysRequestObject->getParameter("Ds_Order");
                    $merchantCode = $redsysRequestObject->getParameter("Ds_MerchantCode");
                    $terminal = $redsysRequestObject->getParameter("Ds_Terminal");
                    $transactionType = $redsysRequestObject->getParameter("Ds_TransactionType");

                    if ($response == 900 && $merchantCode == 66789215 && $terminal == '001' && $currency == 978 && $transactionType == 3) {
                        return redirect('administrateShipment/' . $order)->with('successMessage', 'La devolución del pedido se ha realizado con exito.');
                    }
                }
            }
        }
        return abort(404);
    }

    function invalidRefund(Request $request){
        $user = $this->validateUser($request->cookie('user'), $request->cookie('sessionToken'));

        if($user && $user['admin'] > 0){
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
                    $currency = $redsysRequestObject->getParameter("Ds_Currency");
                    $order = $redsysRequestObject->getParameter("Ds_Order");
                    $merchantCode = $redsysRequestObject->getParameter("Ds_MerchantCode");
                    $terminal = $redsysRequestObject->getParameter("Ds_Terminal");
                    $transactionType = $redsysRequestObject->getParameter("Ds_TransactionType");
                    
                    if($response != 900 && $merchantCode == 66789215 && $terminal == '001' && $currency == 978 && $transactionType == 3){
                        return redirect('administrateShipment/'.$order)->withErrors('Ha ocurrido un error durante la devolución del pedido, vuelva a intentarlo más tarde. Si el error perdura contacte con nuestro sistema de asistencia técnica.');
                    }
                }
            }
        }
        return abort(404);
    }


    //---------- A D M I N   U T I L S ----------//

    function isFlexibleField($field){
        $flexibleFields = ['faqTitle', 'faqText'];

        if(in_array($field, $flexibleFields)) return true;
        return false;
    }
    
     function stripTagsAndAttributes($html, $allowedTags){
        if(strlen($html) < 1) return ' ';

        libxml_use_internal_errors(true);

        $dom = new DOMDocument;
        $dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8"), LIBXML_HTML_NOIMPLIED|LIBXML_HTML_NODEFDTD);
        $xpath = new DOMXPath($dom);
        $nodes = $xpath->query('//@*');

        foreach ($nodes as $node) {
            if(strtolower($node->nodeName != 'href') && strtolower($node->nodeName != 'class')) $node->parentNode->removeAttribute($node->nodeName);
        }

        return strip_tags($dom->saveHTML(), $allowedTags);
    }

    function getInnerData(Request $request){
        $user = $this->validateUser($request->cookie('user'), $request->cookie('sessionToken'));

        if($user && $user['admin'] > 0){
            if(isset($request['category'])){
                $category = htmlspecialchars($request['category']);

                if(strlen($category) > 0){
                    $category = Category::where('category', $category)->first();

                    if($category){
                        $certificates = Certificate::where('category', $category['category'])->get();

                        return json_encode($certificates);
                    }
                }
            }else if(isset($request['certificate'])){
                $certificate = htmlspecialchars($request['certificate']);

                if(strlen($certificate) > 0){
                    $certificate = Certificate::where('certificate', $certificate)->first();

                    if($certificate){
                        $books = [];
                        $bookRelations = BookCertificate::where('certificate', $certificate['id'])->get();

                        foreach ($bookRelations as $bookRelation){
                            $book = Book::where('id', $bookRelation['bookId'])->first();

                            if($book){
                                $isMultiple =
                                    BookCertificate::where('bookId', $book['id'])
                                    ->where('certificate', '!=', $certificate['id'])
                                    ->first();
                                if(!$isMultiple) array_push($books, $book);
                            };
                        }
                        return json_encode($books);
                    }
                }
            }
        }
        return abort(404);
    }
}
