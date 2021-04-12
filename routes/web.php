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

ArieTimmerman\Laravel\URLShortener\URLShortener::routes();

Route::get('/', function () {
//    return view('auth.login');
//    session(['DB' => 'mysql']);
    return view('auth.login');
});
//Route::group(['middleware' => ['web']], function () {
//    Route::get('/login', function () {
//        return view('welcome');
//    });
//});
//Auth::routes();


//Route::get('/home', 'HomeController@index')->name('home');
//Route::get('/home', ['as' => 'home', function() {
//    return view('home');
//    
//}]);

Route::get('/province/get/{id}', 'ProvinceController@getProvince');
Route::get('/agency/get/{id}', 'AgencyController@getAgency');
Route::get('/canton/get/{id}', 'CantonController@getCantones');

Route::get('/user/create', 'UserController@create');

Route::post('/user/store','UserController@store');
Route::post('/user/type/sucre/change','UserController@typeSucreChange');
Route::patch('/user','UserController@index');

Route::get('/user','UserController@index');

Route::get('/user/update/{parameter}','UserController@update');
Route::get('/user/update/pass/{parameter}','UserController@updatePass');
Route::patch('/user/update','UserController@patch');
Route::patch('/user/update/pass','UserController@patchPass');
Route::get('/user/inactive/{parameter}','UserController@inactiveUser');

//
Route::get('massivesVinculation/create','MassivesVinculationController@create');
Route::post('massivesVinculation/store','MassivesVinculationController@store');
Route::post('/massivesVinculation/validateDocument','MassivesVinculationController@validateDocument');
Route::get('massivesVinculation','MassivesVinculationController@index');
Route::post('massivesVinculation','MassivesVinculationController@index');
Route::get('massivesVinculation/form/{parameter1}','MassivesVinculationController@vinculationForm');
Route::get('massivesVinculation/legalPerson/form/{parameter1}','MassivesVinculationController@legalPersonVinculationForm');
Route::post('/massivesVinculation/validate/lista','MassivesVinculationController@validateListaObservados');
Route::get('massivesVinculation/legalPerson/create','MassivesVinculationController@createLegalPerson');
Route::get('massivesVinculation/beneficiaryPerson/create','MassivesVinculationController@createBeneficiaryPerson');
//Route::get('massivesVinculation/tercerosPerson/create','MassivesVinculationController@createTercerosPerson');
//Route::get('massivesVinculation/beneficiaryPerson/create','MassivesVinculationController@createBeneficiaryPerson');
Route::get('vinculation/pj/tercerosPerson/create','legalTercerosVinculationController@createTercerosPerson');
Route::get('vinculation/pj/beneficiaryPerson/create','legalBeneficiaryVinculationController@createBeneficiaryPerson');

Route::post('vinculation/pj/tercerosPerson/store','legalTercerosVinculationController@storeTercerosPerson');
Route::post('vinculation/pj/beneficiaryPerson/store','legalBeneficiaryVinculationController@storeBeneficiaryPerson');
//Route::post('massivesVinculation/tercerosPerson/store','MassivesVinculationController@storeTercerosPerson');
Route::post('massivesVinculation/legalPerson/store','MassivesVinculationController@storeLegalPerson');
Route::post('/massivesVinculation/form/update','MassivesVinculationForm@update');
Route::post('/sales/modal/resumeMassives','VehiclesSales@modalResumeMassives');
Route::post('/massivesVinculation/selectProduct','MassivesVinculationController@selectProduct');

///
Route::get('legalPersonVinculation/create','LegalPersonVinculationController@create');
Route::post('legalPersonVinculation/create','LegalPersonVinculationController@create');
Route::post('/legalPersonVinculation/validateDocument','LegalPersonVinculationController@validateDocument');
Route::post('/legalPersonVinculation/validateEconomicActivity','LegalPersonVinculationController@validateEconomicActivity');
Route::post('/legalPersonVinculation/firstStepForm', 'LegalPersonVinculationController@firstStepForm');

Route::post('/legalPersonVinculation/secondStepForm', 'LegalPersonVinculationController@secondStepForm');
Route::post('/legalPersonVinculation/thirdStepForm', 'LegalPersonVinculationController@thirdStepForm');
Route::post('/legalPersonVinculation/upload', 'LegalPersonVinculationController@upload');
Route::post('/legalPersonVinculation/delete', 'LegalPersonVinculationController@delete');
Route::post('/legalPersonVinculation/complete', 'LegalPersonVinculationController@complete');
Route::post('/legalPersonVinculation/token/generate', 'LegalPersonVinculationController@complete');
Route::post('/legalPersonVinculation/send/link', 'LegalPersonVinculationController@sendLink');
Route::post('/legalPersonVinculation/form/send','LegalPersonVinculationController@send');
Route::post('/legalPersonVinculation/form/update','LegalPersonVinculationController@update');
Route::post('/legalPersonVinculation/confirm/complete', 'LegalPersonVinculationController@confirmComplete');
Route::get('/legalPersonVinculation/pdf', 'LegalPersonVinculationController@pdf');
Route::get('legalPersonVinculation/document/autofill/{parameter}','LegalPersonVinculationController@documentAutoFill');
Route::post('/insertExcel', 'insertExcel@insert');
Route::get('/insertExcel', 'insertExcel@insert');

//Person Natural Beneficiary
Route::get('/beneficiary/personNatural/pdf', 'PnBeneficiaryVinculationController@pdf');

//Customer Routes
Route::get('customer/document/check/{parameter}','CustomerController@documentCheck');
Route::get('customer/document/autofill/{parameter}','CustomerController@documentAutoFill');
Route::get('company/document/autofill/{parameter}','CustomerController@documentCompanyAutoFill');
Route::get('insured/document/autofill/{parameter}','CustomerController@insuredDocumentAutoFill');
Route::get('customer/document/autofill/representative/{parameter}','CustomerController@documentRepresentativeAutoFill');
Route::get('/customer','CustomerController@index');
Route::post('/customer','CustomerController@index');
Route::post('/customer/resume','CustomerController@resume');
Route::post('/customer/fetch_data', 'CustomerController@fetch_data');
Route::get('/customer/create', 'CustomerController@create');
Route::post('/customer/store', 'CustomerController@store');
Route::post('/customer/store/data', 'CustomerController@storeData');
Route::post('/insured/store/data', 'CustomerController@storeInsuredData');
Route::post('/customer/edit', 'CustomerController@edit');
Route::post('/customer/edit/validate', 'CustomerController@editValidate');
Route::post('/customer/obtain/data/sale', 'CustomerController@obtainDataSale');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

Route::get('sendbasicemail','MailController@basic_email');
Route::get('sendhtmlemail','MailController@html_email');
Route::get('sendattachmentemail','MailController@attachment_email');

Route::post('login/recover','MailController@recover');

Route::get('login/recover','\App\Http\Controllers\Auth\LoginController@recover');

//Admin Routes 
Route::get('admin','\App\Http\Controllers\Admin\AdminController@index');
Route::get('admin/pages','\App\Http\Controllers\Admin\PagesController@index');
Route::get('admin/pages/create','\App\Http\Controllers\Admin\PagesController@create');

//Sales Routes
Route::get('sales','SalesController@index')->name('salesIndex');
Route::post('sales/getData','SalesController@showData')->name('sales/getData');
Route::get('sales/showData','SalesController@showData');
Route::get('sales/create','SalesController@createR1');
Route::get('sales/product/select','SalesController@productSelect');
Route::get('sales/create/remote','SalesController@createRemote')->name('salesCreateRemote');
Route::get('sales/cancel/{parameter}','SalesController@cancel');
Route::get('sales/pdf/{parameter}','SalesController@pdf');
Route::post('sales','SalesController@index');
Route::post('/sales/annulment','SalesController@annulment');
Route::post('/sales/renew','SalesController@renew');
Route::post('/sales/resume','SalesController@resume');
Route::post('/sales/resume/new','SalesController@resumeNew');
Route::post('/sales/resume/new/ss','SalesController@resumeNewSS');
Route::post('/sales/store','SalesController@store');
Route::post('/sales/store/new','SalesController@storeNew');
Route::post('/sales/vehicles/cancel','SalesController@vehiclesCancel');
Route::post('/sales/activate','SalesController@salesActivate');
Route::post('/sales/resend/code','SalesController@salesResendCode');
Route::post('/sales/delete','SalesController@delete');
Route::post('/sales/validateDocument','SalesController@validateDocument');
Route::get('sales/emit/{parameter1}/{parameter2}/{parameter3}','SalesController@emit');
Route::post('sales/emit/r1/upload','SalesController@emitR1Upload');
Route::post('sales/emit/r1/delete','SalesController@emitR1Delete');
Route::post('sales/emit/r1/store','SalesController@emitR1Store');
Route::post('sales/emit/r2/store','SalesController@emitR2Store');
Route::post('sales/emit/r3/store','SalesController@emitR4Store');
Route::post('sales/emit/r4/store','SalesController@emitR4Store');
Route::post('sales/emit/r1/store/upload','SalesController@emitR1StoreUpload');
Route::get('sales/renovate/{parameter}','SalesController@renovate');
Route::post('sales/encrypt','SalesController@encrypt');
Route::get('sales/asset/{parameter}/{parameter2}','SalesController@assetView');
Route::get('sales/product','SalesController@productView');
Route::get('sales/branch/emit/{parameter}/{parameter2}','SalesController@emitBranch');
Route::get('sales/vehi/emit/{parameter}/{parameter2}','SalesController@emitR1');
Route::post('sales/emit/r1/check','SalesController@validateEmitR1');
Route::post('sales/emit/endDate','SalesController@endDate');
Route::get('sales/form/{parameter1}','SalesController@vinculationForm');
Route::post('sales/vehicles/check/price','SalesController@vehiCheckPrice');
Route::post('sales/vehicles/check/conditions','SalesController@vehiCheckConditions');
Route::post('/sales/emit/r1/document','SalesController@checkDocumentEndoso');
Route::get('sales/quotationMail','SalesController@quotationMail');
Route::post('/sales/validate/lista/cartera','SalesController@validateListaObservadosyCartera');
Route::get('sales/sri/{id}','SalesController@showDocuments');
Route::post('/sales/vehi/update','VehiclesController@vehiUpdate');

//// PAYMENTS BUTTON ////
Route::get('sales/payments/create','PaymentsButtonController@paymentsCreate');
Route::post('sales/payments/store','PaymentsButtonController@paymentsStore');
Route::post('sales/payments/pay','PaymentsButtonController@paymentsPay');
Route::get('sales/payments/pay/result','PaymentsButtonController@paymentsPayResult');

///R2/////
Route::get('sales/R2/create','SalesController@R2create');
Route::post('sales/R2/create','SalesController@R2createPost');
Route::post('/sales/R2/check/price','SalesController@R2CheckPrice');
Route::post('sales/R2/check/conditions','SalesController@R2CheckConditions');
Route::post('/sales/R2/resume','SalesController@resume');
Route::post('/sales/R2/resume/new','SalesController@resumeNew');
Route::post('/sales/R2/resume/new/ss','SalesController@R2ResumeNewSS');
Route::post('/sales/R2/store','SalesController@R2store');
Route::post('/sales/R2/store/new','SalesController@R2storeNew');
Route::get('sales/R2/insuranceApplication/{id}','SalesController@R2insuranceApplication');
Route::get('sales/R2/beneficiariesRequestSendLink/{id}','SalesController@R2beneficiariesRequestSendLink');
Route::get('sales/R2/beneficiariesRequestSendLink/{id}','SalesController@R2beneficiariesRequestSendLink');

Route::get('sales/R2/pdf_insuranceApplication','InsuranceApplicationController@pdf');


///R3///
Route::get('sales/R3/create','SalesController@R3create');
Route::post('/sales/R3/check/price','SalesController@R3CheckPrice');
Route::post('/sales/R3/resume/new/ss','SalesController@R3ResumeNewSS');
Route::post('/sales/R3/store','SalesController@R3store');

Route::get('sales/R4/create','SalesController@R4create');
Route::post('sales/R4/check/price','SalesController@R4CheckPrice');
Route::get('/sales/R4/getValueRubro/{id}', 'RubrosController@getValueRubro');
Route::post('/sales/R4/resume/new/ss','SalesController@R4ResumeNewSS');
Route::post('/sales/R4/store','SalesController@R4store');

//Vehicles Sales Routes
Route::post('/vehicules/check/plate','VehiclesController@checkPlate');
Route::post('/vehicles/vehiPrice/modal','VehiclesController@vehiPriceModal');
Route::post('/ajax_upload/action/Front', 'VehiclesSales@actionFront')->name('ajaxupload.action');
Route::post('/ajax_upload/delete/Front', 'VehiclesSales@deleteFront')->name('ajaxupload.action');
Route::post('/ajax_upload/action/Back', 'VehiclesSales@actionBack')->name('ajaxupload.action');
Route::post('/ajax_upload/delete/Back', 'VehiclesSales@deleteBack')->name('ajaxupload.action');
Route::post('/ajax_upload/action/Right', 'VehiclesSales@actionRight')->name('ajaxupload.action');
Route::post('/ajax_upload/delete/Right', 'VehiclesSales@deleteRight')->name('ajaxupload.action');
Route::post('/ajax_upload/action/Left', 'VehiclesSales@actionLeft')->name('ajaxupload.action');
Route::post('/ajax_upload/delete/Left', 'VehiclesSales@deleteLeft')->name('ajaxupload.action');
Route::post('/ajax_upload/action/Roof', 'VehiclesSales@actionRoof')->name('ajaxupload.action');
Route::post('/ajax_upload/delete/Roof', 'VehiclesSales@deleteRoof')->name('ajaxupload.action');
Route::post('/vehicles/modal/pictures/','VehiclesSales@modalPictures');
Route::post('/sales/modal/resume/','VehiclesSales@modalResume');
Route::post('/vehicules/massive/store','VehiclesSales@massiveStore');
Route::post('/vehi/emit/modal','VehiclesController@emitEditModal');

//Payment Routes
Route::post('/payments/create','PaymentsController@create');
Route::post('/payments/store','PaymentsController@store');
Route::post('/payments/modal','PaymentsController@modal');
Route::post('/payments/modal/resume','PaymentsController@modalResume');
Route::post('/payments/modal/store','PaymentsController@modalStore');
Route::post('/payments','PaymentsController@index');
Route::get('/payments','PaymentsController@index');
Route::post('/payments/validateNumber', 'PaymentsController@validateNumber');
Route::get('/payments/refund/{parameter}','PaymentsController@refund');
Route::post('/payments/refund/store','PaymentsController@refundStore');

Route::get('/payments/create/{id}','PaymentsController@createGet');
//Route::get('/payments/create','PaymentsController@create');

//Massive Routes
Route::get('/massive/create','MassiveController@create');
Route::get('/massive/cancel','MassiveController@cancel');
Route::get('/massive/download/upload/file/{parameter}','MassiveController@downloadUploadFile');
Route::get('/massive/download/error/file/{parameter}','MassiveController@downloadErrorFile');
Route::get('/massive/download/upload/format','MassiveController@downloadUploadFormatFile');
Route::get('/massive/download/cancel/format','MassiveController@downloadCancelFormatFile');
Route::post('/massive/store','MassiveController@store')->name('massive/store');
Route::post('/massive/store/cancel','MassiveController@storeCancel')->name('massive/store/cancel');
Route::post('/massive/payment','MassiveController@payment');
Route::get('/massive','MassiveController@index');
Route::post('/massive','MassiveController@index');
Route::get('/massive/secondary','MassiveController@indexSecondary');
Route::post('/massive/secondary','MassiveController@indexSecondary');
Route::post('/massive/validate/upload/excel','MassiveController@validateUploadExcel');
Route::post('/massive/validate/cancel/excel','MassiveController@validateCancelExcel');
Route::post('/massive/vehicule/modal','MassiveController@vehiculeModal');
Route::post('/massive/cancel/ajax','MassiveController@cancelAjax');
Route::post('/massive/resume','MassiveController@resume');
Route::get('/quotation', 'SalesController@quotation');

//User Routes
Route::get('/user/password/change','UserController@passwordChange');
Route::post('/user/password/change','UserController@passwordUpdate');
Route::post('/user/password/modal','UserController@passwordUpdateModal');


//scheduling Routes
Route::get('/scheduling/create','SchedulingController@create');
Route::get('/scheduling','SchedulingController@index');
Route::get('/scheduling/calendar/reschedule/{parameter}','SchedulingController@calendarReschedule');
Route::get('/scheduling/calendar','SchedulingController@calendar');
Route::post('/scheduling/calendar','SchedulingController@calendar');
Route::post('/scheduling','SchedulingController@index');
Route::post('/scheduling/validate/plate','SchedulingController@validatePlate');
Route::post('/scheduling/create/fill','SchedulingController@createFill');
Route::post('/scheduling/validate/damage','SchedulingController@validateDamage');
Route::post('/scheduling/validate/dateTime','SchedulingController@validateDateTime');
Route::post('/scheduling/store','SchedulingController@store');
Route::post('/scheduling/delete/temp','SchedulingController@deleteTemp');
Route::post('/scheduling/modal/resume','SchedulingController@modalResume');
Route::post('/scheduling/confirm','SchedulingController@confirm');
Route::post('/scheduling/modal/cancel','SchedulingController@modalCancel');
Route::post('/scheduling/store/cancel','SchedulingController@storeCancel');
Route::post('/scheduling/reschedule/validate','SchedulingController@rescheduleValidate');
Route::post('/scheduling/calendar/table/delete','SchedulingController@calendarTableDelete');

//Benegits Routes
Route::get('/benefits', 'BenefitsController@index');
Route::post('/benefits', 'BenefitsController@index');
Route::get('/benefits/secondary', 'BenefitsController@indexSecondary');
Route::post('/benefits/secondary', 'BenefitsController@indexSecondary');
Route::get('/benefits/create', 'BenefitsController@create');
Route::post('/benefits/store', 'BenefitsController@store');
Route::post('/benefits/edit/modal', 'BenefitsController@editModal');
Route::post('/benefits/edit/store', 'BenefitsController@editStore');
Route::post('/benefits/cancel/store', 'BenefitsController@cancelStore');
Route::post('/benefits/schedule/modal', 'BenefitsController@scheduleModal');
Route::post('/benefits/schedule/store', 'BenefitsController@scheduleStore');

//Reports Routes
Route::get('/salesReports', 'ReportsController@salesIndex');
Route::post('/salesReports', 'ReportsController@salesReports');
Route::get('/timeReports', 'ReportsController@timeIndex');
Route::post('/timeReports', 'ReportsController@timeReports');
Route::get('/cancelMotivesReports', 'ReportsController@cancelMotivesIndex');
Route::post('/cancelMotivesReports', 'ReportsController@cancelMotivesReports');
Route::get('/schedulingReports', 'ReportsController@schedulingIndex');
Route::post('/schedulingReports', 'ReportsController@schedulingReports');
Route::get('/schedulingCancelMotivesReports', 'ReportsController@schedulingCancelMotivesIndex');
Route::post('/schedulingCancelMotivesReports', 'ReportsController@schedulingCancelMotivesReports');
Route::get('/schedulingTimeReports', 'ReportsController@schedulingTimeIndex');
Route::post('/schedulingTimeReports', 'ReportsController@schedulingTimeReports');
Route::get('/schedulingDetailReports', 'ReportsController@schedulingDetailIndex');
Route::post('/schedulingDetailReports', 'ReportsController@schedulingDetailReports');
Route::get('/benefitsReports', 'ReportsController@benefitsIndex');
Route::post('/benefitsReports', 'ReportsController@benefitsReports');
Route::get('/benefitsUseReports', 'ReportsController@benefitsUseIndex');
Route::post('/benefitsUseReports', 'ReportsController@benefitsUseReports');
Route::get('/benefitsDetailReports', 'ReportsController@benefitsDetailIndex');
Route::post('/benefitsDetailReports', 'ReportsController@benefitsDetailReports');
Route::get('/customersReports', 'ReportsController@customersIndex');
Route::post('/customersReports', 'ReportsController@customersReports');
Route::get('/massivesDetailReports', 'ReportsController@massivesDetailIndex');
Route::post('/massivesDetailReports', 'ReportsController@massivesDetailReports');
Route::get('/massivesGlobalReports', 'ReportsController@massivesGlobalIndex');
Route::post('/massivesGlobalReports', 'ReportsController@massivesGlobalReports');

Route::get('/agenChannel/get/{id}', 'ChannelController@getAgentChannel');
Route::get('/ejecutivo_ss/get/{id}/{id2}', 'ChannelController@getEjecutivoChannel');
Route::get('/ramos/get/{id}', 'productsController@getRamos');

// report life personal accidents
// comercial 
Route::get('CovehiclesReport', 'ReportsController@CovehiclesReportIndex');
Route::post('/CovehiclesReport', 'ReportsController@CovehiclesReport');
Route::get('CovdapReport', 'ReportsController@CovdapReportIndex');
Route::post('/CovdapReport', 'ReportsController@CovdapReport');
// técnico
Route::get('TevehiclesReport', 'ReportsController@TevehiclesReportIndex');
Route::post('/TevehiclesReport', 'ReportsController@TevehiclesReport');
Route::get('TevdapReport', 'ReportsController@TevdapReportIndex');
Route::post('/TevdapReport', 'ReportsController@TevdapReport');
Route::get('walletReport', 'ReportsController@walletReportIndex');
Route::post('/walletReport', 'ReportsController@walletReport');
Route::get('products/get/{id}', 'productsController@getProducts');


//Remote Routes 
Route::get('/remote', 'RemoteController@index')->name('remote');
Route::post('/remotePayment', 'RemoteController@remotePayment')->name('remotePayment');
Route::get('/remotePayment', 'RemoteController@index');
Route::post('/remotePaymentStore','RemoteController@storePayment')->name('remotePaymentStore');
Route::get('/remotePaymentStore','RemoteController@index');
Route::post('/remoteSendVehiLink','RemoteController@sendVehiLink')->name('remoteSendVehiLink');
Route::get('/remoteSendVehiLink','RemoteController@indexVehicles');
Route::post('/remoteVehiPictures','RemoteController@vehiPictures')->name('remoteVehiPictures');
Route::get('/remoteVehiPictures','RemoteController@indexVehicles');
Route::get('/remoteVehicles', 'RemoteController@indexVehicles');
Route::post('/remoteVehicles', 'RemoteController@remoteVehicles')->name('remoteVehicles');
Route::post('/remote/loadVehiPictures', 'RemoteController@loadVehiPictures');
Route::post('/remoteResendCodePayment', 'RemoteController@resendCodePayment')->name('resendCodePayment');
Route::get('/remoteResendCodePayment', 'RemoteController@index');
Route::post('/remoteResendCodeVehicles', 'RemoteController@resendCodeVehicles')->name('resendCodeVehicles');
Route::get('/remoteResendCodeVehicles', 'RemoteController@indexVehicles');

//Bank Account Routes
Route::get('/account', 'bankAccountController@index')->name('accountIndex');
Route::post('/account', 'bankAccountController@index');
Route::get('/account/create', 'bankAccountController@create')->name('accountCreate');
Route::post('/account/store', 'bankAccountController@store')->name('accountStore');
Route::post('/account/approve', 'bankAccountController@approve')->name('accountApprove');
Route::post('/account/deny', 'bankAccountController@deny')->name('accountDeny');
Route::post('/account/delete', 'bankAccountController@delete')->name('accountDelete');
Route::post('/account/ajax_upload/action', 'bankAccountController@storePicture');
Route::post('/account/ajax_upload/delete', 'bankAccountController@deletePicture');
Route::post('/account/sendCode', 'bankAccountController@sendCode');
Route::post('/account/validateCode', 'bankAccountController@validateCode');

//Financing Routes
Route::get('/financing', 'financingController@index')->name('financingIndex');
Route::post('/financing', 'financingController@index');
Route::get('/financing/create', 'financingController@create');
Route::post('/financing/validateCredit', 'financingController@validateCredit');
Route::post('/financing/sendCode', 'financingController@sendCode');
Route::post('/financing/validateCode', 'financingController@validateCode');
Route::post('/financing/deleteCreditRequest', 'financingController@deleteCreditRequest');
Route::post('/financing/resendCode', 'financingController@resendCode');
Route::post('/financing/delete', 'financingController@delete');
Route::post('/financing/productTable', 'financingController@productTable');

//Pagination Routes
Route::post('/benefits/fetch_data', 'BenefitsController@fetch_data');
Route::post('/benefits/secondary/fetch_data', 'BenefitsController@fetch_data_secondary');
Route::post('/individual/fetch_data', 'SalesController@fetch_data');
Route::post('/massives/fetch_data', 'MassiveController@fetch_data');
Route::post('/massives/secondary/fetch_data', 'MassiveController@fetch_dataSecondary');
Route::post('/scheduling/fetch_data', 'SchedulingController@fetch_data');
Route::post('/users/fetch_data', 'UserController@fetch_data');
Route::post('/charges/fetch_data', 'PaymentsController@fetch_data');
Route::post('/datafast/fetch_data', 'DataFastController@fetch_data');
Route::post('/insurance/fetch_data', 'InsuranceController@fetch_data');
Route::post('/massivesVinculation/fetch_data', 'MassivesVinculationController@fetch_data');

//Rol Routes
Route::get('/rol', 'RolController@index');
Route::post('/rol', 'RolController@index');
Route::get('/rol/create', 'RolController@create');
Route::post('/rol/store', 'RolController@store');
Route::post('/rol/edit', 'RolController@edit');
Route::get('/rol/edit', 'RolController@index');
Route::post('/rol/update', 'RolController@update');

//Channels Routes
Route::get('/channel', 'ChannelController@index');
Route::post('/channel', 'ChannelController@index');
Route::post('/channel/fetch_data', 'ChannelController@fetch_data');
Route::get('/channel/create', 'ChannelController@create');
Route::post('/channel/store','ChannelController@store');
Route::post('/channel/resume','ChannelController@resume');
Route::post('/channel/edit','ChannelController@edit');
Route::post('/channel/edit/validate','ChannelController@editValidate');
Route::get('/channel/product/{parameter1}','ChannelController@productChannel');
Route::post('/channel/product/store','ChannelController@productChannelStore');
Route::post('/channel/productChannelSS','ChannelController@productChannelSS');

//Agency Routes
Route::get('/agency', 'AgencyController@index');
Route::post('/agency/create', 'AgencyController@create');
Route::post('/agency/fetch_data', 'AgencyController@fetch_data');
Route::post('/agency/store', 'AgencyController@store');
Route::post('/agency/validate/upload/excel', 'AgencyController@validateUploadExcel');
Route::get('/agency/download', 'AgencyController@downloadFormat');
Route::post('/agency/edit', 'AgencyController@edit');

//Vinculation Routes
Route::get('/vinculation/create', 'VinculationController@create');
Route::get('/vinculation/payer/create', 'VinculationController@createPayer');
Route::post('/vinculation/firstStepForm', 'VinculationController@firstStepForm');
Route::post('/vinculation/secondStepForm', 'VinculationController@secondStepForm');
Route::post('/vinculation/thirdStepForm', 'VinculationController@thirdStepForm');
Route::post('/vinculation/upload', 'VinculationController@upload');
Route::post('/vinculation/delete', 'VinculationController@delete');
Route::post('/vinculation/complete', 'VinculationController@complete');
Route::post('/vinculation/send/link', 'VinculationController@sendLink');
Route::post('/vinculation/confirm/complete', 'VinculationController@confirmComplete');
Route::post('/vinculation/token/generate', 'VinculationController@complete');
Route::post('/vinculation/token/validate', 'VinculationController@tokenValidate');
Route::post('/vinculation/form/send','VinculationController@send');
Route::post('/vinculation/form/update','VinculationController@update');
Route::get('/vinculation/pdf', 'VinculationController@pdf');
Route::get('/vinculation/pdf_beneficiarios', 'VinculationController@beneficiariosPDF');
Route::get('/vinculation/pdf_terceros', 'VinculationController@tercerosPDF');
Route::post('/vinculation/validateDocument','VinculationController@validateDocument');
Route::post('/vinculation/validateEconomicActivity','VinculationController@validateEconomicActivity');


//Providers Routes
Route::get('/providers', 'ProvidersController@index');
Route::post('/providers', 'ProvidersController@index');
Route::get('/providers/create', 'ProvidersController@create');
Route::post('/providers/store', 'ProvidersController@store');
Route::post('/providers/resume', 'ProvidersController@resume');
Route::post('/providers/fetch_data', 'ProvidersController@fetch_data');

//Providers Branch Routes
Route::post('/branch/create', 'ProvidersBranchController@create');
Route::post('/branch/store', 'ProvidersBranchController@store');
Route::post('/branch/validate/upload/excel', 'ProvidersBranchController@validateUploadExcel');

//Ticket Routes
Route::get('/ticket', 'TicketsController@index');
Route::post('/ticket', 'TicketsController@index');
Route::get('/ticket/create', 'TicketsController@create');
Route::post('/ticket/type/detail', 'TicketsController@ticketTypeDetail');
Route::post('/ticket/store', 'TicketsController@store');
Route::post('/ticket/ajax_upload/action', 'TicketsController@upload');
Route::post('/ticket/ajax_upload/delete', 'TicketsController@delete');
Route::get('/ticket/detail/{parameter}', 'TicketsController@detail');
Route::get('/ticket/detail', 'TicketsController@index');
Route::post('/ticket/fetch_data', 'TicketsController@fetch_data');
Route::get('/ticket/create', 'TicketsController@create');
Route::get('/ticket/create', 'TicketsController@create');
Route::get('/ticket/picture/{picture}/{id}', 'TicketsController@downloadPicture');
Route::post('/ticket/store/comment', 'TicketsController@storeComment');

//Inspection Routes
Route::post('/inspection/create', 'InspectionController@create');
Route::get('/inspection', 'InspectionController@index');
Route::post('/inspection', 'InspectionController@index');
Route::post('/inspection/fetch_data', 'InspectionController@fetch_data');
Route::post('/inspection/upload', 'InspectionController@upload');
Route::post('/inspection/confirm', 'InspectionController@confirm');
Route::post('/inspection/vehi/form', 'InspectionController@vehiForm');
Route::post('/inspection/vehi/update', 'InspectionController@vehiUpdate');
Route::get('/inspection/pdf/R2/{salId}', 'InspectionController@pdfR2');
Route::post('/inspection/datecreate/{inspectionId}', 'InspectionController@dateInspection');


//Insurance Apllication Routes
Route::post('/insurance/application/firstStepStore', 'InsuranceApplicationController@firstStepStore');
Route::post('/insurance/application/secondStepStore', 'InsuranceApplicationController@secondStepStore');
Route::post('/insurance/application/thirdStepStore', 'InsuranceApplicationController@thirdStepStore');
Route::post('/insurance/application/fourthStepStore', 'InsuranceApplicationController@fourthStepStore');
Route::post('/insurance/application/fifthStepStore', 'InsuranceApplicationController@fifthStepStore');
Route::get('sales/R2/insuranceApplication/{id}','InsuranceApplicationController@R2insuranceApplication');
Route::get('sales/R3/insuranceApplication/{id}','InsuranceApplicationController@R3declarationBeneficiareies');
Route::post('/insurance/application/R3/firstStepStore', 'InsuranceApplicationController@r3FirstStepStore');


//Insurance Routes
Route::get('/insurance', 'InsuranceController@index');
Route::post('/insurance', 'InsuranceController@index');
Route::get('/insurance/create', 'InsuranceController@create');
Route::post('/insurance/store', 'InsuranceController@store');
Route::post('/insurance/resend/code', 'InsuranceController@resendCode');
Route::post('/insurance/validate/code', 'InsuranceController@validateCode');
Route::post('/insurance/cancel', 'InsuranceController@cancel');

//Datafast Routes
Route::get('/datafast', 'DataFastController@index');
Route::post('/datafast', 'DataFastController@index');

//City Routes
Route::post('/city/get', 'CityController@getByCountry');
Route::get('/city/get/{id}', 'CityController@getCity');
Route::get('/city/get/country/{id}', 'CityController@getCityByCountry');

//Assets Routes
Route::post('/assets/get', 'AssetsController@getAssets');

//Passives Routes
Route::post('/passives/get', 'PassivesController@getPassives');

//Test
Route::get('/test', function() {
    processFtpCoris();
});

//FTP
Route::get('/ftp/uploadFiles/{id}', 'FtpController@uploadFiles');
Route::get('/ftp/receivingPayments', 'FtpController@receivingPayments');


//SHORT URL
Route::get('/su/{short}', 'ShortUrlController@redirect');

//TUTORIALS ROUTES
Route::get('/tutorials', 'TutorialsController@index');

//Clear Cache
Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});

//Vehicles Routes
//Route::get('vehicles/tempJson/{parameter}','VehiclesController@tempJson');
Route::post('vehicles/tempJson/','VehiclesController@tempJson');

//Massives Mortgage Routes
Route::get('massives/mortgage','MassivesMortgageController@storeExcel');

//Sucre Massives Routes
Route::get('/sucre/biess', 'SucreMassivesController@validateExcelBiess');
Route::get('/sucre/pacifico', 'SucreMassivesController@validateExcelPacifico');
Route::post('/sales/massives/update/agency', 'SucreMassivesController@updateAgency');
Route::post('/sales/massives/update/city', 'SucreMassivesController@updateCity');

