<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    HomeController,
    IndexController,
    UserController,
    RoleController,
    PermissionController,
    RolePermissionController,
    OfficeController,
    DepartmentController,
    DesignationController,
    DivisionController,
    DistrictController,
    UpazilaController,
    SettingController,
    ProjectCategoryController,
    ProjectController,
    DocumentController,
    ProjectTransactionController,
    ReportController,
    LeaveCategoryController,
    LeaveController,
    NotificationController,
    ExamController,
    ExamCategoryController,
    SubjectController,
    SubjectCategoryController,
    InstituteController,
    BoardController,
    DurationController,
    AcademicExamFormController,
    AppraisalController,
    UserCategoryController,
    AjaxController,
    ETicketTypeController,
    ETicketController,
};

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\ForgotPasswordController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// ****************************************** Essential Links *****************************************
Route::get('/update-app', function()
{
    Artisan::call('dump-autoload');

    echo 'dump-autoload complete';
});

Route::get('/clear', function() {
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('route:clear');
    $exitCode = Artisan::call('config:clear');
    $exitCode = Artisan::call('view:clear');

    return "Clean";
});

Route::get('/link-storage', function() {
    $exitCode = Artisan::call('storage:link');

    return "Linked";
});

// Auth Routes
Auth::routes();

Route::get('/', function () {
    return redirect()->route('login');
});

Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::get('login/google', [LoginController::class, 'redirectToGoogle'])->name('login.google');
Route::get('login/google/callback', [LoginController::class, 'handleGoogleCallback']);
Route::get('/register', [RegisterController::class, 'register'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
Route::get('/verification/{id}', [VerificationController::class, 'verify'])->name('verify');
Route::get('/email-verify/{id}', [VerificationController::class, 'emailVerify'])->name('emailVerify');
Route::post('/resend-mail/{id}', [VerificationController::class, 'resendMail'])->name('resendMail'); 
Route::get('/forgot-password', [ForgotPasswordController::class, 'forgotPasswordPage'])->name('forgotPasswordPage'); 
Route::post('/forgot-password', [ForgotPasswordController::class, 'forgotPassword'])->name('forgotPassword'); 
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('resetPassword'); 

// ****************************************** Back-end Links *****************************************
Route::group(['middleware' => ['AuthGates','set.locale'], 'prefix' => '/authorized-user', 'as' => 'admin.'], function() {
    Route::get('/', [IndexController::class, 'index'])->name('home');
    Route::get('/change-language', [IndexController::class, 'language_change'])->name('language_change');

    // get dynamic address AJAX
    Route::post('/get-district', [IndexController::class, 'getDistrictsAJAX'])->name('districts');
    Route::post('/get-upazila', [IndexController::class, 'getUpazilasAJAX'])->name('upazilas');
    Route::post('/get-task-purpose', [AjaxController::class, 'taskPurposesByType'])->name('taskPurposesByType');
    Route::post('/get-projects', [AjaxController::class, 'projectsByClient'])->name('projectsByClient');

    Route::group(['prefix' => '/notification'], function() {
        Route::get('/', [NotificationController::class, 'index'])->name('notification.index');
        Route::get('/read/{message}/{reference_id}', [NotificationController::class, 'read_view'])->name('notification.read_view');
        Route::get('/read-notification/{id}', [NotificationController::class, 'read_notification'])->name('notification.read_notification');
    });

    Route::get('/edit-profile', [UserController::class, 'edit_profile'])->name('edit_profile');
    Route::post('/update-profile', [UserController::class, 'update_profile'])->name('update_profile');

    Route::group(['prefix' => '/user', 'as' => 'user.'], function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/users', [UserController::class, 'researcherIndex'])->name('researcherIndex');

        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/store', [UserController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('edit');
        Route::post('/update', [UserController::class, 'update'])->name('update');
        Route::get('/edit-user-minimum/{id}', [UserController::class, 'editUserMinimum'])->name('editUserMinimum');
        Route::post('/update-user-minimum', [UserController::class, 'updateUserMinimum'])->name('updateUserMinimum');
        Route::get('/show/{id}', [UserController::class, 'show'])->name('show');
        Route::post('/update-password', [UserController::class, 'updatePassword'])->name('updatePassword');
        Route::post('/change-other-user-password', [UserController::class, 'changeOtherUserPassword'])->name('changeOtherUserPassword');

        Route::delete('/delete', [UserController::class, 'destroy'])->name('delete');
        Route::get('/active/{id}', [UserController::class, 'active'])->name('active');
        Route::get('/archive', [UserController::class, 'block'])->name('block');
        Route::get('/approve', [UserController::class, 'approve'])->name('approve');
        Route::get('/decline', [UserController::class, 'decline'])->name('decline');

        Route::get('/archive-list', [UserController::class, 'archive_list'])->name('archive_list');
        Route::get('/print-doc/{id}', [UserController::class, 'print_doc'])->name('print_doc');

        Route::get('/add-academic-info/{id}', [UserController::class, 'add_education'])->name('add_education');
        Route::post('/add-academic-info/{id}', [UserController::class, 'store_education'])->name('store_education');

        Route::get('/company-document-delete/{id}', [UserController::class, 'companyDocDelete'])->name('companyDocDelete');
    });

    Route::group(['prefix' => '/appraisal', 'as' => 'appraisal.'], function () {
        Route::get('/', [AppraisalController::class, 'index'])->name('index');
        Route::get('/create', [AppraisalController::class, 'create'])->name('create');
        Route::post('/store', [AppraisalController::class, 'store'])->name('store');
        Route::get('/view/{id}', [AppraisalController::class, 'show'])->name('view');
        Route::get('/edit/{id}', [AppraisalController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [AppraisalController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [AppraisalController::class, 'destroy'])->name('delete');
        Route::get('/history/{user_id}', [AppraisalController::class, 'view_history'])->name('view_history');
    });

    Route::group(['prefix' => '/role', 'as' => 'role.'], function() {
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::post('/store', [RoleController::class, 'store'])->name('store');
        Route::get('/active/{id}', [RoleController::class, 'active'])->name('active');
        Route::get('/disable/{id}', [RoleController::class, 'disable'])->name('disable');
        Route::post('/update/{id}', [RoleController::class, 'update'])->name('update');
    });

    Route::group(['prefix' => '/permission', 'as' => 'permission.'], function() {
        Route::get('/', [PermissionController::class, 'index'])->name('index');
        Route::post('/store', [PermissionController::class, 'store'])->name('store');
        Route::post('/update/{id}', [PermissionController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [PermissionController::class, 'delete'])->name('delete');
    });

    Route::group(['prefix' => '/role-permission', 'as' => 'rolePermission.'], function() {
        Route::get('/', [RolePermissionController::class, 'index'])->name('index');
        Route::get('/showPermission/{roleId}', [RolePermissionController::class, 'showPermission'])->name('showPermission');
        Route::post('/remove-permission', [RolePermissionController::class, 'removePermission'])->name('removePermission');
        Route::post('/give-permission', [RolePermissionController::class, 'givePermission'])->name('givePermission');
    });

    Route::group(['prefix' => '/offices', 'as' => 'office.'], function() {
        Route::get('/', [OfficeController::class, 'index'])->name('index');
        Route::get('/archived', [OfficeController::class, 'archived'])->name('archived');
        Route::get('/create', [OfficeController::class, 'create'])->name('create');
        Route::post('/store', [OfficeController::class, 'store'])->name('store');
        Route::get('/show/{id}', [OfficeController::class, 'show'])->name('show');
        Route::get('/edit/{id}', [OfficeController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [OfficeController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [OfficeController::class, 'delete'])->name('delete');
    });

    Route::group(['prefix' => '/department', 'as' => 'department.'], function () {
        Route::get('/', [DepartmentController::class, 'index'])->name('index');
        Route::post('/store', [DepartmentController::class, 'store'])->name('store');
        Route::post('/update/{id}', [DepartmentController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [DepartmentController::class, 'destroy'])->name('delete');
    });

    Route::group(['prefix' => '/designation', 'as' => 'designation.'], function() {
        Route::get('/', [DesignationController::class, 'index'])->name('index');
        Route::post('/store', [DesignationController::class, 'store'])->name('store');
        Route::post('/update/{id}', [DesignationController::class, 'update'])->name('update');
    });

    Route::group(['prefix' => '/region'], function() {
        Route::get('/', [DivisionController::class, 'index'])->name('region.index');
        Route::post('/store', [DivisionController::class, 'store'])->name('region.store');
        Route::get('/edit/{region_id}', [DivisionController::class, 'edit'])->name('region.edit');
        Route::post('/edit/{region_id}', [DivisionController::class, 'update'])->name('region.update');
        Route::get('/view/{region_id}', [DivisionController::class, 'view'])->name('region.view');
        Route::get('/delete/{region_id}', [DivisionController::class, 'destroy'])->name('region.delete');
    });

    Route::group(['prefix' => '/district'], function() {
        Route::get('/', [DistrictController::class, 'index'])->name('district.index');
        Route::post('/store', [DistrictController::class, 'store'])->name('district.store');
        Route::get('/edit/{district_id}', [DistrictController::class, 'edit'])->name('district.edit');
        Route::post('/edit/{district_id}', [DistrictController::class, 'update'])->name('district.update');
        Route::get('/view/{district_id}', [DistrictController::class, 'view'])->name('district.view');
        Route::get('/delete/{district_id}', [DistrictController::class, 'destroy'])->name('district.delete');
    });

    Route::group(['prefix' => '/upazila'], function() {
        Route::get('/', [UpazilaController::class, 'index'])->name('upazila.index');
        Route::get('/create', [UpazilaController::class, 'create'])->name('upazila.create');
        Route::post('/store', [UpazilaController::class, 'store'])->name('upazila.store');
        Route::get('/edit/{upazila_id}', [UpazilaController::class, 'edit'])->name('upazila.edit');
        Route::post('/edit/{upazila_id}', [UpazilaController::class, 'update'])->name('upazila.update');
        Route::get('/view/{upazila_id}', [UpazilaController::class, 'view'])->name('upazila.view');
        Route::get('/delete/{upazila_id}', [UpazilaController::class, 'destroy'])->name('upazila.delete');
    });

    Route::group(['prefix' => '/setting'], function() {
        Route::get('/', [SettingController::class, 'index'])->name('setting.index');
        Route::post('/update/{id}', [SettingController::class, 'update'])->name('setting.update');
    });

    Route::group(['prefix' => '/projects', 'as' => 'project.'], function() {
        Route::get('/', [ProjectController::class, 'index'])->name('index');
        Route::get('/create', [ProjectController::class, 'create'])->name('create');
        Route::post('/store', [ProjectController::class, 'store'])->name('store');
        Route::get('/show/{id}', [ProjectController::class, 'show'])->name('show');
        Route::get('/edit/{id}', [ProjectController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [ProjectController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [ProjectController::class, 'delete'])->name('delete');
    });

    Route::group(['prefix' => '/project-categories', 'as' => 'project_category.'], function() {
        Route::get('/', [ProjectCategoryController::class, 'index'])->name('index');
        Route::get('/create', [ProjectCategoryController::class, 'create'])->name('create');
        Route::post('/store', [ProjectCategoryController::class, 'store'])->name('store');
        Route::get('/show/{id}', [ProjectCategoryController::class, 'show'])->name('show');
        Route::get('/edit/{id}', [ProjectCategoryController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [ProjectCategoryController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [ProjectCategoryController::class, 'delete'])->name('delete');
    });

    Route::group(['prefix' => '/documents', 'as' => 'document.'], function() {
        Route::get('/', [DocumentController::class, 'index'])->name('index');
        Route::get('/create', [DocumentController::class, 'create'])->name('create');
        Route::post('/store', [DocumentController::class, 'store'])->name('store');
        Route::get('/show/{id}', [DocumentController::class, 'show'])->name('show');
        Route::get('/edit/{id}', [DocumentController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [DocumentController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [DocumentController::class, 'delete'])->name('delete');
        Route::post('/mass-delete', [DocumentController::class, 'massDelete'])->name('massDelete');

        Route::get('/legalIndex', [DocumentController::class, 'legalIndex'])->name('legalIndex');
        Route::get('/membershipIndex', [DocumentController::class, 'membershipIndex'])->name('membershipIndex');
        Route::get('/financialIndex', [DocumentController::class, 'financialIndex'])->name('financialIndex');

        Route::get('/legalCreate', [DocumentController::class, 'legalCreate'])->name('legalCreate');
        Route::get('/membershipCreate', [DocumentController::class, 'membershipCreate'])->name('membershipCreate');
        Route::get('/financialCreate', [DocumentController::class, 'financialCreate'])->name('financialCreate');
    });

    Route::group(['prefix' => '/project-transaction', 'as' => 'project_transaction.'], function() {
        Route::get('/', [ProjectTransactionController::class, 'index'])->name('index');
        Route::get('/create', [ProjectTransactionController::class, 'create'])->name('create');
        Route::post('/store', [ProjectTransactionController::class, 'store'])->name('store');
        Route::get('/show/{id}', [ProjectTransactionController::class, 'show'])->name('show');
        Route::get('/edit/{id}', [ProjectTransactionController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [ProjectTransactionController::class, 'update'])->name('update');
        Route::get('/destroy/{id}', [ProjectTransactionController::class, 'destroy'])->name('delete');
    });
    
    Route::group(['prefix' => '/user-category', 'as' => 'user_category.'], function() {
        Route::get('/', [UserCategoryController::class, 'index'])->name('index');
        Route::get('/create', [UserCategoryController::class, 'create'])->name('create');
        Route::post('/store', [UserCategoryController::class, 'store'])->name('store');
        Route::get('/show/{id}', [UserCategoryController::class, 'show'])->name('show');
        Route::get('/edit/{id}', [UserCategoryController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [UserCategoryController::class, 'update'])->name('update');
        Route::get('/destroy/{id}', [UserCategoryController::class, 'destroy'])->name('delete');
    });

    Route::group(['prefix' => '/leave-category', 'as' => 'leaveCategory.'], function() {
        Route::get('/', [LeaveCategoryController::class, 'index'])->name('index');
        Route::get('/create', [LeaveCategoryController::class, 'create'])->name('create');
        Route::post('/store', [LeaveCategoryController::class, 'store'])->name('store');
        Route::get('/show/{id}', [LeaveCategoryController::class, 'show'])->name('show');
        Route::get('/edit/{id}', [LeaveCategoryController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [LeaveCategoryController::class, 'update'])->name('update');
        Route::get('/destroy/{id}', [LeaveCategoryController::class, 'destroy'])->name('delete');
    });

    Route::group(['prefix' => '/leave-application', 'as' => 'leave.'], function() {
        Route::get('/', [LeaveController::class, 'index'])->name('index');
        Route::get('/leave-summary', [LeaveController::class, 'leaveSummary'])->name('leaveSummary');
        Route::get('/leave-summary-monthwise', [LeaveController::class, 'leaveSummaryMonthwise'])->name('leaveSummaryMonthwise');
        Route::get('/create', [LeaveController::class, 'create'])->name('create');
        Route::post('/store', [LeaveController::class, 'store'])->name('store');
        Route::get('/show/{id}', [LeaveController::class, 'show'])->name('show');
        Route::get('/edit/{id}', [LeaveController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [LeaveController::class, 'update'])->name('update');
        Route::get('/destroy/{id}', [LeaveController::class, 'destroy'])->name('delete');
        Route::post('/status-change/{id}', [LeaveController::class, 'statusChange'])->name('statusChange');
    });

    /* Report Management */
    Route::group(['prefix' => '/report', 'as' => 'report.'], function() {
        
        Route::get('/project-report', [ReportController::class, 'projectReport'])->name('projectReport');
        Route::get('/export-project-summary', [ReportController::class, 'exportProjectSummary'])->name('exportProjectSummary');
        Route::get('/export-doc-project-summary', [ReportController::class, 'exportDocProjectSummary'])->name('exportDocProjectSummary');

        Route::get('/view-pl-report/{id}', [ReportController::class, 'showPL'])->name('showPL');
        Route::get('/export-pl-doc-report/{id}', [ReportController::class, 'exportDocPlReport'])->name('exportDocPlReport');
        Route::get('/export-pl-excel-report/{id}', [ReportController::class, 'excelExportPlReport'])->name('excelExportPlReport');
        
        Route::get('/leave-report', [ReportController::class, 'leaveReport'])->name('leaveReport');
        Route::get('/export-leave-summary', [ReportController::class, 'excelExportLeaveReport'])->name('excelExportLeaveReport');
        Route::get('/export-doc-leave-summary', [ReportController::class, 'exportDocLeaveReport'])->name('exportDocLeaveReport');

        Route::get('/employee-category-report', [ReportController::class, 'employeeCategory'])->name('employeeCategory');
    });

    Route::group(['prefix' => '/exams', 'as' => 'exam.'], function () {
        Route::get('/', [ExamController::class, 'index'])->name('index');
        Route::get('/create', [ExamController::class, 'create'])->name('create');
        Route::post('/store', [ExamController::class, 'store'])->name('store');
        Route::post('/update/{id}', [ExamController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [ExamController::class, 'destroy'])->name('delete');
    });

    Route::group(['prefix' => '/exam-category', 'as' => 'examCategory.'], function () {
        Route::get('/', [ExamCategoryController::class, 'index'])->name('index');
        Route::get('/create', [ExamCategoryController::class, 'create'])->name('create');
        Route::post('/store', [ExamCategoryController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [ExamCategoryController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [ExamCategoryController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [ExamCategoryController::class, 'destroy'])->name('delete');
    });

    Route::group(['prefix' => '/subjects', 'as' => 'subject.'], function () {
        Route::get('/', [SubjectController::class, 'index'])->name('index');
        Route::get('/create', [SubjectController::class, 'create'])->name('create');
        Route::post('/store', [SubjectController::class, 'store'])->name('store');
        Route::post('/update/{id}', [SubjectController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [SubjectController::class, 'destroy'])->name('delete');
    });

    Route::group(['prefix' => '/subject-category', 'as' => 'subjectCategory.'], function () {
        Route::get('/', [SubjectCategoryController::class, 'index'])->name('index');
        Route::get('/create', [SubjectCategoryController::class, 'create'])->name('create');
        Route::post('/store', [SubjectCategoryController::class, 'store'])->name('store');
        Route::post('/update/{id}', [SubjectCategoryController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [SubjectCategoryController::class, 'destroy'])->name('delete');
    });

    Route::group(['prefix' => '/institute', 'as' => 'institute.'], function () {
        Route::get('/', [InstituteController::class, 'index'])->name('index');
        Route::get('/create', [InstituteController::class, 'create'])->name('create');
        Route::post('/store', [InstituteController::class, 'store'])->name('store');
        Route::post('/update/{id}', [InstituteController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [InstituteController::class, 'destroy'])->name('delete');
    });

    Route::group(['prefix' => '/board', 'as' => 'board.'], function() {
        Route::get('/', [BoardController::class, 'index'])->name('index');
        Route::get('/create', [BoardController::class, 'create'])->name('create');
        Route::post('/store', [BoardController::class, 'store'])->name('store');
        Route::post('/update/{id}', [BoardController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [BoardController::class, 'destroy'])->name('delete');
    });

    Route::group(['prefix' => '/education-form', 'as' => 'education_form.'], function () {
        Route::get('/', [AcademicExamFormController::class, 'index'])->name('index');
        Route::get('/create', [AcademicExamFormController::class, 'create'])->name('create');
        Route::post('/store', [AcademicExamFormController::class, 'store'])->name('store');
        Route::get('/view/{id}', [AcademicExamFormController::class, 'show'])->name('view');
        Route::get('/edit/{id}', [AcademicExamFormController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [AcademicExamFormController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [AcademicExamFormController::class, 'destroy'])->name('delete');
    });

    Route::group(['prefix' => 'duration', 'as' => 'duration.'], function () {
        Route::get('/', [DurationController::class, 'index'])->name('index');
        Route::get('/create', [DurationController::class, 'create'])->name('create');
        Route::post('/store', [DurationController::class, 'store'])->name('store');
        Route::get('/show/{id}', [DurationController::class, 'show'])->name('show');
        Route::post('/update/{id}', [DurationController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [DurationController::class, 'destroy'])->name('delete');
    });

    //e-ticket routes
    Route::group(['prefix' => '/e-ticket-type', 'as' => 'eTicketType.'], function() {
        Route::get('/', [ETicketTypeController::class, 'index'])->name('index');
        Route::get('/create', [ETicketTypeController::class, 'create'])->name('create');
        Route::post('/store', [ETicketTypeController::class, 'store'])->name('store');
        Route::get('/show/{id}', [ETicketTypeController::class, 'show'])->name('show');
        Route::get('/edit/{id}', [ETicketTypeController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [ETicketTypeController::class, 'update'])->name('update');
        Route::get('/destroy/{id}', [ETicketTypeController::class, 'destroy'])->name('delete');
    });

    Route::group(['prefix' => '/e-ticket', 'as' => 'eTicket.'], function() {
        Route::get('/', [ETicketController::class, 'index'])->name('index');
        Route::get('/create', [ETicketController::class, 'create'])->name('create');
        Route::post('/store', [ETicketController::class, 'store'])->name('store');
        Route::get('/show/{id}', [ETicketController::class, 'show'])->name('show');
        Route::get('/edit/{id}', [ETicketController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [ETicketController::class, 'update'])->name('update');
        Route::get('/destroy/{id}', [ETicketController::class, 'destroy'])->name('delete');
        
        Route::get('/delete-file/{id}', [ETicketController::class, 'deleteFile'])->name('deleteFile');
        Route::get('/change-status/{id}', [ETicketController::class, 'changeStatus'])->name('changeStatus');
    });
});