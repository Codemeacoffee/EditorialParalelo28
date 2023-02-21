<?php

namespace Paralelo28\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Paralelo28\BlogEntry;
use Paralelo28\Book;
use Paralelo28\BookCertificate;
use Paralelo28\Category;
use Paralelo28\Certificate;
use Paralelo28\Image;
use Paralelo28\Page;
use Paralelo28\Statistic;
use Paralelo28\UserSetting;
use Paralelo28\User;
use Paralelo28\WishList;
use Paralelo28\Library;
use Paralelo28\Tax;
use Exception;

class RootController extends Controller
{
    function index(Request $request, $parameter = 'index'){
        $parameter = htmlspecialchars($parameter);

        if(in_array(strtolower($parameter), $this->getNonReturnableUrls())) return abort(404);

        return $this->viewDispatcher($parameter, $request, $this->getPreviewImages());
    }

    function blog(Request $request){
        if(!isset($_GET['filter']) || htmlspecialchars($_GET['filter']) == 'ALL')  $blogEntries = BlogEntry::orderBy('Created_at', 'DESC')->get();
        else $blogEntries = BlogEntry::where('category', htmlspecialchars($_GET['filter']))->orderBy('Created_at', 'DESC')->get();

        $categories = [];

        $allEntries = BlogEntry::all();

        foreach ($allEntries as $entry){
            if(!in_array($entry['category'], $categories)) array_push($categories, $entry['category']);
        }

        return $this->viewDispatcher('blog', $request, [$blogEntries, $categories]);
    }

    function catalogue(Request $request, $parameter = 'ALL'){
        $parameter = htmlspecialchars($parameter);

        if($parameter == 'ALL'){
            $certificates = Certificate::all();
        }else {
            $category = Category::where('category', $parameter)->first();

            if(!$category) return abort(404);

            $certificates = Certificate::where('category', $parameter)->get();
        }

        return $this->viewDispatcher('catalogue', $request, [$parameter, $certificates ,$this->getPreviewImages()]);
    }

    function certificate(Request $request, $parameter = 'ALL'){
        $parameter = htmlspecialchars($parameter);

        if($parameter == 'ALL'){
            $booksOfSelectedCategory = Book::all();
        }else{
            if(!isset($_GET['key'])) return abort(404);

            $certificate = Certificate::where('id', $_GET['key'])->first();

            if($certificate['certificate'] != $parameter) return abort(404);

            $bookCertificate = BookCertificate::where('certificate', $_GET['key'])->get();

            if(!$bookCertificate) return abort(404);

            $booksOfSelectedCategory = [];

            foreach ($bookCertificate as $currentRelation){
                $book = Book::where('id', $currentRelation['bookId'])->first();

                if($book) array_push($booksOfSelectedCategory, $book);
            }
        }

        foreach ($booksOfSelectedCategory as $currentBook){
            $currentBook['category'] = $this->getCategory($currentBook);
        }
        
        $filter = intval(htmlspecialchars($request['filter']));

        if($filter && $filter != 1){
            switch ($filter){
                case 2:
                    usort($booksOfSelectedCategory, function($a, $b) { return strcmp($a->digitalPrice, $b->digitalPrice); });
                    break;
                case 3:
                    usort($booksOfSelectedCategory, function($a, $b) { return strcmp($b->digitalPrice, $a->digitalPrice); });
                    break;
                default:
                    usort($booksOfSelectedCategory, function($a, $b) {
                        return strcmp(substr(strstr($a->title," "), 1), substr(strstr($b->title," "), 1));
                    });
            }
        }else{
            usort($booksOfSelectedCategory, function($a, $b) { return strcmp($b->edition, $a->edition); });
        }
        
        foreach ($booksOfSelectedCategory as $book) {
            $book['category'] = $this->getCategory($book);
        }

        return $this->viewDispatcher('manuals', $request, [$parameter, $booksOfSelectedCategory, $this->getPreviewImages()]);
    }

    function search(Request $request){
        $query = mb_strtolower(htmlspecialchars($request['question']));

        if(strlen($query)<1) return Redirect('/')->withErrors('La búsqueda debe contener al menos 1 cáracter.');

        $highRelevance = [];
        $mediumRelevance = [];
        $lowRelevance = [];

        $certificates = Certificate::all();
        $books = Book::all();

        foreach ($certificates as $certificate){
            similar_text(mb_strtolower($certificate['certificate']), $query, $similitude);

            if($similitude >= 70){
                array_push($highRelevance, $certificate);
            }else if($similitude >= 60){
                array_push($mediumRelevance, $certificate);
            }else if($similitude >= 50){
                array_push($lowRelevance, $certificate);
            }else if(strpos(mb_strtolower($certificate['certificate']), $query) !== false){
                if((strlen($query)*100/strlen($certificate['certificate'])) >= 40){
                    array_push($highRelevance, $certificate);
                } else if((strlen($query)*100/strlen($certificate['certificate'])) >= 20){
                    array_push($mediumRelevance, $certificate);
                } else if((strlen($query)*100/strlen($certificate['certificate'])) >= 10){
                    array_push($lowRelevance, $certificate);
                }
            }

            similar_text(mb_strtolower($certificate['category']), $query, $similitude);

            if($similitude >= 70){
                array_push($highRelevance, $certificate);
            }else if($similitude >= 60){
                array_push($mediumRelevance, $certificate);
            }else if($similitude >= 50){
                array_push($lowRelevance, $certificate);
            }else if(strpos(mb_strtolower($certificate['category']), $query) !== false){
                if((strlen($query)*100/strlen($certificate['category'])) >= 40){
                    array_push($highRelevance, $certificate);
                } else if((strlen($query)*100/strlen($certificate['category'])) >= 20){
                    array_push($mediumRelevance, $certificate);
                } else if((strlen($query)*100/strlen($certificate['category'])) >= 10){
                    array_push($lowRelevance, $certificate);
                }
            }
        }

        foreach ($books as $book){
            $bookFields = [$book['title'], $book['author'], $book['description'], $book['measures'], $book['edition']];
            $categories = $this->getCategory($book, true);
            $book['category'] = $this->getCategory($book);

            foreach ($bookFields as $bookField){
                similar_text(mb_strtolower($bookField), $query, $similitude);

                if($similitude >= 70){
                    array_push($highRelevance, $book);
                }else if($similitude >= 60){
                    array_push($mediumRelevance, $book);
                }else if($similitude >= 50){
                    array_push($lowRelevance, $book);
                }else if(strpos(mb_strtolower($bookField), $query) !== false){
                    if((strlen($query)*100/strlen($bookField)) >= 40){
                        array_push($highRelevance, $book);
                    } else if((strlen($query)*100/strlen($bookField)) >= 20){
                        array_push($mediumRelevance, $book);
                    } else if((strlen($query)*100/strlen($bookField)) >= 1){
                        array_push($lowRelevance, $book);
                    }
                }
            }

            foreach ($categories as $category){
                similar_text(mb_strtolower($category), $query, $similitude);

                if($similitude >= 70){
                    array_push($highRelevance, $book);
                }else if($similitude >= 60){
                    array_push($mediumRelevance,  $book);
                }else if($similitude >= 50){
                    array_push($lowRelevance,  $book);
                }else if(strpos(mb_strtolower($category), $query) !== false){
                    if((strlen($query)*100/strlen($category)) >= 40){
                        array_push($highRelevance, $book);
                    } else if((strlen($query)*100/strlen($category)) >= 20){
                        array_push($mediumRelevance, $book);
                    } else if((strlen($query)*100/strlen($category)) >= 10){
                        array_push($lowRelevance, $book);
                    }
                }
            }
        }

        $results = [$query, array_merge($highRelevance, $mediumRelevance, $lowRelevance), $this->getPreviewImages()];

        return $this->viewDispatcher('searchResults', $request , $results);
    }

    function viewDispatcher($view, $request, $data = []){
        //---------- D B   S E L E C T O R S ----------//

        $latestBooks = Book::orderBy('id', 'DESC')->paginate(3);
        $isPromoted = Book::where('promoted', 1)->paginate(3);
        $higherPhysicalSales = Statistic::orderBy('physicalSales', 'DESC')->get();
        $higherDigitalSales = Statistic::orderBy('digitalSales', 'DESC')->get();
        $categories = Category::all();

        //---------- U T I L S ----------//

        $statistics = [];
        $mostSold = [];
        $userData = [];
        $library = [];

        //---------- U S E R----------//

        $userCookie = htmlspecialchars($request->cookie('user'));
        $sessionCookie = htmlspecialchars($request->cookie('sessionToken'));

        if($userCookie && $sessionCookie){

            $user = User::where('email', $userCookie)->first();

            if($user){
                if($user->session_token == $sessionCookie){
                    $userSettings = UserSetting::where('userId', $user['id'])->first();
                    
                    if($userSettings['session_expires'] != 3){
                       $startDate = time();
                       if(($user['session_expire_date'] && strlen($user['session_expire_date']) > 1) && $startDate > strtotime($user['session_expire_date'])){
                           Cookie::queue(Cookie::forget('user'));
                           Cookie::queue(Cookie::forget('sessionToken'));
                           return redirect('/')->withErrors('Su sesión ha expirado por inactividad.');
                       }
                       else{
                           switch($userSettings['session_expires']){
                               case 0:
                                   $expireDate = date('Y-m-d H:i:s', strtotime('+5 minutes', $startDate));
                                   break;
                               case 1:
                                   $expireDate = date('Y-m-d H:i:s', strtotime('+15 minutes', $startDate));
                                   break;
                               default:
                                   $expireDate = date('Y-m-d H:i:s', strtotime('+30 minutes', $startDate));
                           }
                           $user->session_expire_date = $expireDate;
                           $user->save();
                       }
                   }
                    
                    $wishList = WishList::where('userId', $user['id'])->get();
                    $digitalLibrary = Library::where('userId', $user['id'])->where('option', 1)->get();
                    
                    foreach($digitalLibrary as $product){
                        array_push($library, $product['bookId']);
                    }
                    
                    $userData = [
                        'name' => $userSettings['name'],
                        'surnames' => $userSettings['surnames'],
                        'email' => $user['email'],
                        'direction' => $userSettings['direction'],
                        'postalCode' => $userSettings['postalCode'],
                        'taxName' => $userSettings['taxes'],
                        'taxPhysical' => $this->getTaxValue($userSettings['taxes'], 0),
                        'taxDigital' => $this->getTaxValue($userSettings['taxes'], 1),
                        'admin' => $user['admin'],
                        'wishList' => $wishList,
                        'library' => $library,
                        'accountType' => $user['accountType'],
                        'companyName' => $user['companyName'],
                        'companyCIF' => $user['companyCIF'],
                        'expire' => $userSettings['session_expires'],
                        'remember_me' => $userSettings['remember_me']
                    ];
                }
            }
        }

        //---------- Remove duplicates and merge arrays ----------//

        $physicalIdList = [];
        $digitalIdList = [];

        foreach ($higherPhysicalSales as $key => $physicalSale){
            if(!in_array($physicalSale['bookId'], $physicalIdList)){
                array_push($physicalIdList, $physicalSale['bookId']);
            }else{
                $higherPhysicalSales->forget($key);
            }
        }

        foreach ($higherDigitalSales as $key => $digitalSale){
            if(!in_array($digitalSale['bookId'], $digitalIdList)){
                array_push($digitalIdList, $digitalSale['bookId']);
            }else{
                $higherDigitalSales->forget($key);
            }
        }

        $higherSales = $higherPhysicalSales->merge($higherDigitalSales);

        /*-------- Order up to 8 statistics results by higher sales combining physical and digital sales --------*/

        while(Count($statistics) < 8){
            $higher = 0;
            $positionOfHigher = 0;

            for($i = 0; $i < Count($higherSales); $i++){
                $totalSales= $higherSales[$i]['physicalSales'] + $higherSales[$i]['digitalSales'];

                if($totalSales >= $higher){
                    $higher = $totalSales;
                    $positionOfHigher = $i;
                }
            }

            if(!isset($higherSales[$positionOfHigher])) break;

            array_push($statistics, $higherSales[$positionOfHigher]);
            $higherSales[$positionOfHigher] = null;
        }

        //---------- Array Filter ----------//

        $statistics = array_filter($statistics);

        /*------------ Use the ordered array to get the books with higher sales ------------*/

        foreach ($statistics as $statistic){
            array_push($mostSold, Book::where('id', $statistic['bookId'])->first());
        }

        /*------------ In case there are not enough statistics fill with other books ------------*/

        $mostSold = array_unique($mostSold);

        if(Count($mostSold) < 5){
            $books = Book::paginate(5);

            foreach ($books as $book){
                if(!in_array($book, $mostSold)) array_push($mostSold, $book);
            }
        }

        /*------------ In case there are not enough promoted books fill with other books ------------*/

        if(Count($isPromoted) < 3){
            $books = Book::paginate(3);

            foreach ($books as $book){
                if(Count($isPromoted) == 3) break;
                if(!$isPromoted->contains($book)) $isPromoted->push($book);
            }
        }

        /*------------ For each book array, get each book category and add it to that book ------------*/

        foreach ($latestBooks as $currentBook){
             $currentBook['category'] = $this->getCategory($currentBook);
        }

        foreach ($mostSold as $currentBook){
            $currentBook['category'] = $this->getCategory($currentBook);
        }

        foreach ($isPromoted as $currentBook){
            $currentBook['category'] = $this->getCategory($currentBook);
            if($currentBook['category'] == 'Transversal') $currentBook['firstCategory'] = $this->getCategory($currentBook, true)[0];
        }

        /*-------- Get all generic fields, those that can be changed by the admin in the main layout --------*/

        $genericFields = [];
        $pageGenericFields = Page::where('page', 'index')->get();

        foreach ($pageGenericFields as $pageGenericField){
            $genericFields[$pageGenericField['name']] = $pageGenericField['value'];
        }

        /*-------- Get all variable fields, those that can be changed by the admin (if any) --------*/

        $variableFields = [];
        $pageVariableFields = Page::where('page', $view)->get();

        if($pageVariableFields){
            foreach ($pageVariableFields as $pageVariableField){
                $variableFields[$pageVariableField['name']] = $pageVariableField['value'];
            }
        }

        /*-------- Try to return the view with all the data, in case an exception happens, return a 404 error code --------*/

        try{
            return view($view)
                ->with('latestBooks', $latestBooks)
                ->with('mostSold', $mostSold)
                ->with('isPromoted', $isPromoted)
                ->with('categories', $categories)
                ->with('certificate', Certificate::all())
                ->with('bookCertificate', BookCertificate::all())
                ->with('defaultPhysicalTax', $this->getTaxValue('IGIC', 0))
                ->with('defaultDigitalTax', $this->getTaxValue('IGIC', 1))
                ->with('userData', $userData)
                ->with('genericFields', $genericFields)
                ->with('variableFields', $variableFields)
                ->with('data', $data);
        }catch (Exception $e){
            return abort(404);
        }
    }

    function getPreviewImages(){
        $images = Image::orderBy('id', 'ASC')->get();
        $preselectedImages = [];
        $previewImages = [];

        foreach ($images as $image){
            if(!isset($preselectedImages[$image['affiliationName']])) $preselectedImages[$image['affiliationName']] = [];
            array_push($preselectedImages[$image['affiliationName']], $image);
        }

        foreach($preselectedImages as $preselectedImage){
            $max = Count($preselectedImage);

            if($max > 30) $max = 30;

            $min = $max - 10;

            if($min < 0) $min = 0;

            for($i = $min; $i < $max; $i++){
                array_push($previewImages, $preselectedImage[$i]);
            }
        };

        return $previewImages;
    }

    function getCategory($book, $multiple = false){
        $amountOfCategories = 0;
        $categories = [];
        $category = 'Transversal';

        foreach (BookCertificate::all() as $currentBookCertificate){
            if($currentBookCertificate['bookId'] == $book['id']){
                foreach (Certificate::all() as $currentCertificate){
                    if($currentCertificate['id'] == $currentBookCertificate['certificate']){
                        foreach(Category::all() as $currentCategory){
                            if ($currentCertificate['category'] == $currentCategory['category']){
                                if($multiple){
                                    array_push($categories, $currentCategory['category']);
                                }else{
                                    $category = $currentCategory['category'];
                                    $amountOfCategories++;
                                }
                            }
                        }
                    }
                }
            }
        }
        if($multiple){
            return $categories;
        }else{
            if($amountOfCategories > 1) $category = 'Transversal';
            return $category;
        }
    }

     function getTaxValue($taxName, $format){
        $tax = Tax::where('tax', $taxName)->where('format', $format)->first();

        if($tax) return $tax['amount'];
        else return false;
    }
}
