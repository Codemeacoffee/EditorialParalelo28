<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*----------------------------User Controller Routes-----------------------------------*/


Route::post('createAccount', 'UserController@createAccount');

Route::post('login', 'UserController@login');

Route::get('home', 'UserController@home');

Route::get('passwordReset/{parameter?}', 'UserController@resetPassword');

Route::get('resetPassword/{parameter}', 'UserController@validatePasswordReset');

Route::post('finalizePasswordReset', 'UserController@executePasswordReset');

Route::get('confirmationEmail/{parameter?}', 'UserController@confirmEmail');

Route::get('closeSession', 'UserController@closeSession');

Route::get('resendConfirmationEmail/{parameter?}', 'UserController@resendConfirmationEmail');

Route::post('editAccountInfo', 'UserController@editAccountInfo');

Route::post('editAccountDirection', 'UserController@editAccountDirection');

Route::post('editTypeInfo', 'UserController@editAccountType');

Route::post('editDeepSettings', 'UserController@editDeepSettings');

Route::post('finalizePayment', 'UserController@paymentGateway');

Route::get('addToWishList/{parameter?}', 'UserController@addToWishList');

Route::get('removeFromWishList/{parameter?}', 'UserController@removeFromWishList');

Route::post('addedToCart', 'UserController@addedToCart');

Route::post('statistics/visitor/visited', 'UserController@visitor');

Route::post('newsletterSubscription', 'UserController@subscription');

Route::get('newsletterCancelSubscription/{parameter?}', 'UserController@cancelSubscription');

Route::get('blog/{parameter}', 'UserController@viewEntry');

Route::get('viewBook/{parameter?}', 'UserController@viewBook');

Route::get('downloadBook/{parameter}', 'UserController@bookDownload');

Route::get('printBook/{parameter}', 'UserController@bookPrint');

Route::post('validateInvoice', 'UserController@processPayment');

Route::post('notifyInvoice', 'UserController@notifyPayment');

Route::get('invoiceValid', 'UserController@validatePayment');

Route::get('invoiceInvalid', 'UserController@invalidatePayment');

Route::get('survey', 'UserController@survey');

Route::post('takeASurvey', 'UserController@takeASurvey');

Route::get('temporaryBlock', 'UserController@accessBlocked');

Route::get('frontalOpenLogin', 'UserController@indexOpenLogin');

Route::get('personalSurvey/{parameter}', 'UserController@personalSurvey');

Route::get('shipment/{parameter}', 'UserController@shipmentData');

Route::post('refund', 'UserController@refundSolicitude');

Route::get('confirmArrival/{parameter}', 'UserController@confirmArrival');

/*----------------------------Customer Services Controller Routes-----------------------------------*/


Route::post('contactWithUs', 'CustomerServices@contactWithUs')->middleware('honeypot');

Route::post('joinOurTeam', 'CustomerServices@workWithUs');

Route::post('AskYourQuestion', 'CustomerServices@askUs');


/*----------------------------Admin Controller Routes-----------------------------------*/

Route::get('newsletterPromotion', 'AdminController@newsletterPromotion');

Route::get('editSurvey', 'AdminController@editSurveyView');

Route::post('emailAllSubscribers', 'AdminController@emailAllSubscribers');

Route::get('expediteCoupons', 'AdminController@expediteCoupons');

Route::post('generateCoupons', 'AdminController@generateCoupons');

Route::get('deleteCoupon/{parameter}', 'AdminController@removeCoupon');

Route::get('statistics/{parameter?}', 'AdminController@statistics');

Route::get('{any}/admin', 'AdminController@adminLogin')->where('any', '.*');

Route::get('admin', 'AdminController@adminLogin');

Route::get('adminEditPage/{parameter}', 'AdminController@editPage')->where('parameter', '(.*)');;

Route::post('adminAccess', 'AdminController@home');

Route::get('controlPanel', 'AdminController@controlPanel');

Route::post('admin/updatePage', 'AdminController@updatePage');

Route::post('adminAddCategory', 'AdminController@addCategory');

Route::post('adminAddCertificate', 'AdminController@addCertificate');

Route::post('adminEditCertificate', 'AdminController@editCertificate');

Route::post('adminEditCategory', 'AdminController@editCategory');

Route::post('adminEditSurvey', 'AdminController@editSurvey');

Route::post('adminGetInnerData', 'AdminController@getInnerData');

Route::post('adminDelete', 'AdminController@deleteField');

Route::post('adminAddBook', 'AdminController@addBook');

Route::post('adminEditBook', 'AdminController@editBook');

Route::post('adminDeleteBook', 'AdminController@removeBook');

Route::post('adminUploadNew', 'AdminController@addNew');

Route::post('adminEditNew', 'AdminController@editNew');

Route::post('adminDeleteNew', 'AdminController@deleteNew');

Route::post('{any}/adminGetInnerData', 'AdminController@getInnerData')->where('any', '.*');

Route::get('administrateShipments', 'AdminController@administrateShipments');

Route::get('administrateShipment/{parameter}', 'AdminController@administrateRefundSolicitude');

Route::post('updateShipment/{parameter}', 'AdminController@updateShipment');

Route::post('notifyRefund', 'AdminController@notifyRefund');

Route::get('validRefund', 'AdminController@validRefund');

Route::get('invalidRefund', 'AdminController@invalidRefund');

Route::get('administrateTaxes', 'AdminController@administrateTaxes');

Route::post('updateTaxes', 'AdminController@updateTaxes');

Route::post('pdfSlicerResponse', 'AdminController@slicerResponse');

Route::post('pdfSlicerEditResponse', 'AdminController@slicerEditResponse');


/*----------------------------Generic Controller Routes-----------------------------------*/


Route::post('home/getValidatedDirection', 'Controller@getValidatedDirection');


/*----------------------------Root Controller Routes-----------------------------------*/

Route::get('catalogue/certificate/{parameter?}', 'RootController@certificate');

Route::get('catalogue/{parameter?}', 'RootController@catalogue');

Route::get('search', 'RootController@search');

Route::get('blog', 'RootController@blog');

Route::get('/{parameter?}', 'RootController@index')->name("routeDispatcher");
