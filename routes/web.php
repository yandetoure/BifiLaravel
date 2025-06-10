<?php declare(strict_types=1); 

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\OcrController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\BalanceController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\SupervisorController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ClientChatController;
use App\Http\Controllers\ThirdPartyPaymentController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', function () {
    return view('about');
})->name('about');

// Routes publiques pour les factures
Route::get('/bills/create', [BillController::class, 'create'])->name('bills.create');
Route::post('/bills', [BillController::class, 'store'])->name('bills.store');
Route::get('/bills/success', [BillController::class, 'success'])->name('bills.success');

// Routes OCR
Route::post('/ocr/extract-bill', [OcrController::class, 'extractBillData'])->name('ocr.extract-bill');
Route::post('/ocr/extract-receipt', [PaymentController::class, 'extractReceiptData'])->name('ocr.extract-receipt');

// Routes d'authentification
Auth::routes();

Route::middleware('auth')->group(function () {
    // Dashboard personnalisé
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('user.dashboard');
    
    // Routes pour les factures (tous les utilisateurs connectés)
    Route::get('/bills/{bill}', [BillController::class, 'show'])->name('bills.show');
    
    // Routes pour les agents et superviseurs seulement
    Route::patch('/bills/{bill}/status', [BillController::class, 'updateStatus'])->name('bills.updateStatus');
    
    // Routes pour les paiements
    Route::get('/payments/create/{bill}', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
    Route::get('/payments/{payment}/success', [PaymentController::class, 'success'])->name('payments.success');
    
    // Routes pour les reçus
    Route::get('/receipts/generate/{payment}', [ReceiptController::class, 'generate'])->name('receipts.generate');
    Route::get('/receipts/{receipt}/download', [ReceiptController::class, 'download'])->name('receipts.download');
    Route::post('/receipts/{receipt}/send-email', [ReceiptController::class, 'sendByEmail'])->name('receipts.send-email');
    Route::post('/receipts/{receipt}/send-whatsapp', [ReceiptController::class, 'sendByWhatsapp'])->name('receipts.send-whatsapp');
    
    // Routes pour la gestion des soldes
    Route::prefix('balances')->name('balances.')->group(function () {
        Route::get('/', [BalanceController::class, 'index'])->name('index');
        Route::post('/initialize', [BalanceController::class, 'initializeDay'])->name('initialize');
        Route::patch('/update', [BalanceController::class, 'updateBalances'])->name('update');
        Route::post('/deposit', [BalanceController::class, 'deposit'])->name('deposit');
        Route::post('/supervisor-deposit', [BalanceController::class, 'supervisorDeposit'])->name('supervisor-deposit');
        Route::get('/data', [BalanceController::class, 'getBalanceData'])->name('data');
    });

    // Système de chat pour tous les utilisateurs connectés
    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/', [ChatController::class, 'index'])->name('index');
        Route::post('/store', [ChatController::class, 'store'])->name('store');
        Route::get('/messages', [ChatController::class, 'getMessages'])->name('get-messages');
        Route::post('/mark-read', [ChatController::class, 'markAsRead'])->name('mark-read');
    });
    
    // Routes pour les notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::post('/mark-all-read', [ChatController::class, 'markAllNotificationsRead'])->name('mark-all-read');
    });
    
    // Routes pour les uploads de fichiers
    Route::prefix('uploads')->name('uploads.')->group(function () {
        Route::post('/chat-file', [\App\Http\Controllers\FileUploadController::class, 'uploadChatFile'])->name('chat-file');
        Route::delete('/file', [\App\Http\Controllers\FileUploadController::class, 'deleteFile'])->name('delete-file');
    });
});

// Routes pour les agents et superviseurs
Route::middleware(['auth'])->prefix('agent')->name('agent.')->group(function () {
    // Dashboard agent
    Route::get('/dashboard', [AgentController::class, 'dashboard'])->name('dashboard');
    
    // Gestion des factures
    Route::prefix('bills')->name('bills.')->group(function () {
        Route::get('/', [AgentController::class, 'bills'])->name('index');
        Route::get('/{bill}', [BillController::class, 'show'])->name('show');
        Route::patch('/{bill}/status', [AgentController::class, 'updateBillStatus'])->name('update-status');
    });
    
    // Gestion des paiements
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/', [AgentController::class, 'payments'])->name('index');
        Route::get('/{payment}', [PaymentController::class, 'success'])->name('show');
    });
    
    // Statistiques agent
    Route::get('/statistics', [AgentController::class, 'statistics'])->name('statistics');
    
    // Gestion des balances (superviseurs seulement)
    Route::middleware('can:manage,App\Models\Balance')->prefix('balances')->name('balances.')->group(function () {
        Route::get('/', [BalanceController::class, 'index'])->name('index');
        Route::post('/initialize', [BalanceController::class, 'initializeDay'])->name('initialize');
        Route::patch('/update', [BalanceController::class, 'updateBalances'])->name('update');
        Route::post('/deposit', [BalanceController::class, 'deposit'])->name('deposit');
        Route::post('/supervisor-deposit', [BalanceController::class, 'supervisorDeposit'])->name('supervisor-deposit');
    });
});

// Routes pour les superviseurs
Route::middleware(['auth'])->prefix('supervisor')->name('supervisor.')->group(function () {
    // Dashboard superviseur
    Route::get('/dashboard', [SupervisorController::class, 'dashboard'])->name('dashboard');
    
    // Gestion des balances
    Route::get('/balances', [SupervisorController::class, 'balances'])->name('balances');
    
    // Versements bancaires
    Route::post('/bank-deposit', [SupervisorController::class, 'bankDeposit'])->name('bank-deposit');
    
    // Calculs fin de journée
    Route::get('/end-of-day', [SupervisorController::class, 'endOfDayCalculation'])->name('end-of-day');
    
    // Détails des agents
    Route::get('/agents/{agent}', [SupervisorController::class, 'agentDetails'])->name('agent-details');
});

// Routes d'administration - Protégées par le middleware admin
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard admin
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Gestion des utilisateurs
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [AdminController::class, 'users'])->name('index');
        Route::get('/create', [AdminController::class, 'createUser'])->name('create');
        Route::post('/', [AdminController::class, 'storeUser'])->name('store');
        Route::get('/{user}/edit', [AdminController::class, 'editUser'])->name('edit');
        Route::patch('/{user}', [AdminController::class, 'updateUser'])->name('update');
        Route::patch('/{user}/archive', [AdminController::class, 'archiveUser'])->name('archive');
        Route::delete('/{user}', [AdminController::class, 'deleteUser'])->name('delete');
    });
    
    // Gestion des factures
    Route::prefix('bills')->name('bills.')->group(function () {
        Route::get('/', [AdminController::class, 'bills'])->name('index');
        Route::get('/{bill}', [BillController::class, 'show'])->name('show');
        Route::patch('/{bill}/status', [BillController::class, 'updateStatus'])->name('update-status');
        Route::delete('/{bill}', [AdminController::class, 'deleteBill'])->name('delete');
        Route::post('/bulk-update', [AdminController::class, 'bulkUpdateBills'])->name('bulk-update');
    });
    
    // Gestion des paiements
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/', [AdminController::class, 'payments'])->name('index');
        Route::get('/{payment}', [PaymentController::class, 'success'])->name('show');
        Route::patch('/{payment}/status', [AdminController::class, 'updatePaymentStatus'])->name('update-status');
        Route::delete('/{payment}', [AdminController::class, 'deletePayment'])->name('delete');
    });
    
    // Gestion des entreprises
    Route::prefix('companies')->name('companies.')->group(function () {
        Route::get('/', [AdminController::class, 'companies'])->name('index');
        Route::get('/create', [AdminController::class, 'createCompany'])->name('create');
        Route::post('/', [AdminController::class, 'storeCompany'])->name('store');
        Route::get('/{company}/edit', [AdminController::class, 'editCompany'])->name('edit');
        Route::patch('/{company}', [AdminController::class, 'updateCompany'])->name('update');
        Route::delete('/{company}', [AdminController::class, 'deleteCompany'])->name('delete');
    });
    
    // Gestion des soldes - Vue complète admin
    Route::prefix('balances')->name('balances.')->group(function () {
        Route::get('/', [AdminController::class, 'balances'])->name('index');
        Route::get('/history', [AdminController::class, 'balanceHistory'])->name('history');
        Route::post('/manual-adjustment', [AdminController::class, 'manualBalanceAdjustment'])->name('manual-adjustment');
        Route::get('/export', [AdminController::class, 'exportBalances'])->name('export');
    });
    
    // Gestion des transactions
    Route::prefix('transactions')->name('transactions.')->group(function () {
        Route::get('/', [AdminController::class, 'transactions'])->name('index');
        Route::get('/{transaction}', [AdminController::class, 'showTransaction'])->name('show');
        Route::patch('/{transaction}/status', [AdminController::class, 'updateTransactionStatus'])->name('update-status');
    });
    
    // Rapports et exports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [AdminController::class, 'reports'])->name('index');
        Route::get('/daily', [AdminController::class, 'dailyReport'])->name('daily');
        Route::get('/monthly', [AdminController::class, 'monthlyReport'])->name('monthly');
        Route::get('/agents', [AdminController::class, 'agentsReport'])->name('agents');
        Route::get('/companies', [AdminController::class, 'companiesReport'])->name('companies');
        Route::get('/export', [AdminController::class, 'exportExcel'])->name('export');
        Route::post('/export-custom', [AdminController::class, 'exportCustomReport'])->name('export-custom');
    });
    
    // Configuration système
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [AdminController::class, 'settings'])->name('index');
        Route::patch('/general', [AdminController::class, 'updateGeneralSettings'])->name('general');
        Route::patch('/payment-methods', [AdminController::class, 'updatePaymentMethods'])->name('payment-methods');
        Route::patch('/notifications', [AdminController::class, 'updateNotificationSettings'])->name('notifications');
        Route::patch('/wizall-balance', [AdminController::class, 'updateWizallBalance'])->name('wizall-balance');
    });
    
    // Mailing système
    Route::prefix('mail')->name('mail.')->group(function () {
        Route::get('/', [AdminController::class, 'mailIndex'])->name('index');
        Route::post('/send-to-clients', [AdminController::class, 'sendToAllClients'])->name('send-to-clients');
        Route::post('/send-custom', [AdminController::class, 'sendCustomEmail'])->name('send-custom');
    });
    
    // Notifications système
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [AdminController::class, 'notificationsIndex'])->name('index');
        Route::post('/send', [AdminController::class, 'sendNotification'])->name('send');
        Route::post('/mark-read', [AdminController::class, 'markNotificationRead'])->name('mark-read');
        Route::delete('/{notification}', [AdminController::class, 'deleteNotification'])->name('delete');
    });
    
    // Gestion des messages clients
    Route::prefix('client-messages')->name('client-messages.')->group(function () {
        Route::get('/', [ClientChatController::class, 'index'])->name('index');
        Route::delete('/{message}', [AdminController::class, 'deleteClientMessage'])->name('delete');
    });
});

// Routes pour le chat client (séparé du chat interne)
Route::middleware('auth')->prefix('client-chat')->name('client-chat.')->group(function () {
    Route::get('/', [ClientChatController::class, 'index'])->name('index');
    Route::post('/send', [ClientChatController::class, 'sendMessage'])->name('send');
    Route::get('/messages', [ClientChatController::class, 'getMessages'])->name('get-messages');
    Route::patch('/{message}/status', [ClientChatController::class, 'updateStatus'])->name('update-status');
    Route::post('/{message}/reply', [ClientChatController::class, 'replyToMessage'])->name('reply');
});

// Routes API Routes pour Ajax
Route::middleware('auth')->prefix('api')->name('api.')->group(function () {
    // Données en temps réel
    Route::get('/dashboard-stats', [AdminController::class, 'getDashboardStats'])->name('dashboard-stats');
    Route::get('/agent-stats', [AgentController::class, 'statistics'])->name('agent-stats');
    Route::get('/balance-data', [BalanceController::class, 'getBalanceData'])->name('balance-data');
    
    // Recherche et autocomplétion
    Route::get('/search/bills', [BillController::class, 'search'])->name('search.bills');
    Route::get('/search/payments', [PaymentController::class, 'search'])->name('search.payments');
    Route::get('/search/users', [AdminController::class, 'searchUsers'])->name('search.users');
    Route::get('/search/companies', [AdminController::class, 'searchCompanies'])->name('search.companies');
    
    // Notifications
    Route::get('/notifications/unread', [AdminController::class, 'getUnreadNotifications'])->name('notifications.unread');
    Route::get('/chat/unread-count', [ChatController::class, 'getUnreadCount'])->name('chat.unread-count');
    Route::get('/client-chat/unread-count', [ClientChatController::class, 'getUnreadCount'])->name('client-chat.unread-count');
});

// Paiements pour clients tiers (agents, superviseurs, admins)
Route::middleware(['auth'])->group(function () {
    Route::get('/third-party-payment', [ThirdPartyPaymentController::class, 'showThirdPartyForm'])->name('bills.third-party.form');
    Route::post('/third-party-payment/search-client', [ThirdPartyPaymentController::class, 'searchClient'])->name('bills.third-party.search-client');
    Route::post('/third-party-payment/process', [ThirdPartyPaymentController::class, 'processThirdPartyPayment'])->name('bills.third-party.process');
    // Alias pour la compatibilité
    Route::post('/third-party/process', [ThirdPartyPaymentController::class, 'processThirdPartyPayment'])->name('third-party.process');
    Route::get('/bills/{bill}/pay-for-client', [ThirdPartyPaymentController::class, 'payForClient'])->name('bills.third-party.pay');
    Route::post('/bills/{bill}/process-client-payment', [ThirdPartyPaymentController::class, 'processClientPayment'])->name('bills.third-party.pay.process');
});
