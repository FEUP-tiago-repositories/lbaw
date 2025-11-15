# setup-sportshub.ps1
# Script para criar estrutura SportsHub na branch develop
# Windows PowerShell

Write-Host "======================================" -ForegroundColor Green
Write-Host "SportsHub A8 - Setup Laravel Files" -ForegroundColor Green
Write-Host "======================================" -ForegroundColor Green
Write-Host ""

# Verificar que estamos no diretório correto
if (-Not (Test-Path "artisan")) {
    Write-Host "❌ ERRO: Não estás no diretório raiz do Laravel!" -ForegroundColor Red
    Write-Host "   Execute 'cd' para o diretório onde está o ficheiro 'artisan'" -ForegroundColor Yellow
    exit
}

Write-Host "📁 Diretório atual: $PWD" -ForegroundColor Cyan
Write-Host ""

# Mudar para branch develop (criar se não existir)
Write-Host "🔀 Configurando branch develop..." -ForegroundColor Cyan
git checkout develop 2>$null
if ($LASTEXITCODE -ne 0) {
    Write-Host "   Branch develop não existe, criando..." -ForegroundColor Yellow
    git checkout -b develop
}
Write-Host "✅ Na branch develop" -ForegroundColor Green
Write-Host ""

# ===========================================
# MODELS
# ===========================================
Write-Host "📦 Criando Models..." -ForegroundColor Cyan

$models = @("User", "Customer", "BusinessOwner", "Admin", "Space", "SportType",
            "Schedule", "Media", "Discount", "Booking", "Payment", "Review",
            "Response", "Notification")

foreach ($model in $models) {
    if (Test-Path "app\Models\$model.php") {
        Write-Host "   ⏭️  $model já existe, ignorando" -ForegroundColor Yellow
    } else {
        php artisan make:model $model
        Write-Host "   ✅ $model criado" -ForegroundColor Green
    }
}
Write-Host ""

# ===========================================
# CONTROLLERS
# ===========================================
Write-Host "🎮 Criando Controllers..." -ForegroundColor Cyan

$controllers = @{
    "HomeController" = ""
    "StaticController" = ""
    "UserController" = "--resource"
    "NotificationController" = ""
    "SpaceController" = "--resource"
    "ScheduleController" = "--resource"
    "SearchController" = ""
    "BookingController" = "--resource"
    "ReviewController" = "--resource"
    "ResponseController" = ""
}

foreach ($controller in $controllers.Keys) {
    $options = $controllers[$controller]
    if (Test-Path "app\Http\Controllers\$controller.php") {
        Write-Host "   ⏭️  $controller já existe, ignorando" -ForegroundColor Yellow
    } else {
        if ($options) {
            php artisan make:controller $controller $options
        } else {
            php artisan make:controller $controller
        }
        Write-Host "   ✅ $controller criado" -ForegroundColor Green
    }
}

# Controllers Admin
Write-Host "   Criando Admin Controllers..." -ForegroundColor Cyan
$adminControllers = @("UserManagementController", "SpaceManagementController", "ReviewManagementController")

foreach ($controller in $adminControllers) {
    if (Test-Path "app\Http\Controllers\Admin\$controller.php") {
        Write-Host "   ⏭️  Admin\$controller já existe, ignorando" -ForegroundColor Yellow
    } else {
        php artisan make:controller "Admin/$controller" --resource
        Write-Host "   ✅ Admin\$controller criado" -ForegroundColor Green
    }
}
Write-Host ""

# ===========================================
# POLICIES
# ===========================================
Write-Host "🔒 Criando Policies..." -ForegroundColor Cyan

$policies = @{
    "UserPolicy" = "User"
    "SpacePolicy" = "Space"
    "BookingPolicy" = "Booking"
    "ReviewPolicy" = "Review"
}

foreach ($policy in $policies.Keys) {
    $model = $policies[$policy]
    if (Test-Path "app\Policies\$policy.php") {
        Write-Host "   ⏭️  $policy já existe, ignorando" -ForegroundColor Yellow
    } else {
        php artisan make:policy $policy --model=$model
        Write-Host "   ✅ $policy criado" -ForegroundColor Green
    }
}
Write-Host ""

# ===========================================
# MIDDLEWARE
# ===========================================
Write-Host "🛡️  Criando Middleware..." -ForegroundColor Cyan

if (Test-Path "app\Http\Middleware\CheckAdmin.php") {
    Write-Host "   ⏭️  CheckAdmin já existe, ignorando" -ForegroundColor Yellow
} else {
    php artisan make:middleware CheckAdmin
    Write-Host "   ✅ CheckAdmin criado" -ForegroundColor Green
}
Write-Host ""

# ===========================================
# DIRETÓRIOS DE VIEWS
# ===========================================
Write-Host "📂 Criando estrutura de Views..." -ForegroundColor Cyan

$viewDirs = @(
    "resources\views\layouts",
    "resources\views\partials",
    "resources\views\pages",
    "resources\views\auth",
    "resources\views\auth\passwords",
    "resources\views\users",
    "resources\views\spaces",
    "resources\views\spaces\partials",
    "resources\views\bookings",
    "resources\views\bookings\partials",
    "resources\views\reviews",
    "resources\views\reviews\partials",
    "resources\views\notifications",
    "resources\views\admin",
    "resources\views\admin\users",
    "resources\views\admin\spaces",
    "resources\views\admin\reviews"
)

foreach ($dir in $viewDirs) {
    if (Test-Path $dir) {
        Write-Host "   ⏭️  $dir já existe" -ForegroundColor Yellow
    } else {
        New-Item -ItemType Directory -Path $dir -Force | Out-Null
        Write-Host "   ✅ $dir criado" -ForegroundColor Green
    }
}
Write-Host ""

# ===========================================
# FICHEIROS DE VIEWS
# ===========================================
Write-Host "📄 Criando ficheiros de Views..." -ForegroundColor Cyan

$viewFiles = @(
    # Layouts
    "resources\views\layouts\app.blade.php",
    "resources\views\layouts\admin.blade.php",
    "resources\views\layouts\guest.blade.php",

    # Partials
    "resources\views\partials\header.blade.php",
    "resources\views\partials\footer.blade.php",
    "resources\views\partials\nav.blade.php",
    "resources\views\partials\flash-messages.blade.php",

    # Pages
    "resources\views\pages\home.blade.php",
    "resources\views\pages\about.blade.php",
    "resources\views\pages\faq.blade.php",
    "resources\views\pages\terms.blade.php",
    "resources\views\pages\contact.blade.php",

    # Auth
    "resources\views\auth\login.blade.php",
    "resources\views\auth\register.blade.php",
    "resources\views\auth\passwords\email.blade.php",

    # Users
    "resources\views\users\profile.blade.php",
    "resources\views\users\edit.blade.php",
    "resources\views\users\favorites.blade.php",

    # Spaces
    "resources\views\spaces\index.blade.php",
    "resources\views\spaces\show.blade.php",
    "resources\views\spaces\create.blade.php",
    "resources\views\spaces\edit.blade.php",
    "resources\views\spaces\partials\space-card.blade.php",
    "resources\views\spaces\partials\filters.blade.php",
    "resources\views\spaces\partials\schedule-form.blade.php",

    # Bookings
    "resources\views\bookings\index.blade.php",
    "resources\views\bookings\create.blade.php",
    "resources\views\bookings\show.blade.php",
    "resources\views\bookings\edit.blade.php",
    "resources\views\bookings\calendar.blade.php",
    "resources\views\bookings\partials\booking-card.blade.php",
    "resources\views\bookings\partials\schedule-picker.blade.php",

    # Reviews
    "resources\views\reviews\partials\review-card.blade.php",
    "resources\views\reviews\partials\review-form.blade.php",
    "resources\views\reviews\partials\response-form.blade.php",

    # Notifications
    "resources\views\notifications\index.blade.php",

    # Admin
    "resources\views\admin\dashboard.blade.php",
    "resources\views\admin\users\index.blade.php",
    "resources\views\admin\users\show.blade.php",
    "resources\views\admin\users\edit.blade.php",
    "resources\views\admin\spaces\index.blade.php",
    "resources\views\admin\spaces\show.blade.php",
    "resources\views\admin\reviews\index.blade.php"
)

foreach ($file in $viewFiles) {
    if (Test-Path $file) {
        Write-Host "   ⏭️  $(Split-Path $file -Leaf) já existe" -ForegroundColor Yellow
    } else {
        New-Item -ItemType File -Path $file -Force | Out-Null
        Write-Host "   ✅ $(Split-Path $file -Leaf) criado" -ForegroundColor Green
    }
}
Write-Host ""

# ===========================================
# DIRETÓRIOS DE ASSETS
# ===========================================
Write-Host "🎨 Criando estrutura de Assets..." -ForegroundColor Cyan

$assetDirs = @(
    "public\css",
    "public\js",
    "public\images"
)

foreach ($dir in $assetDirs) {
    if (Test-Path $dir) {
        Write-Host "   ⏭️  $dir já existe" -ForegroundColor Yellow
    } else {
        New-Item -ItemType Directory -Path $dir -Force | Out-Null
        Write-Host "   ✅ $dir criado" -ForegroundColor Green
    }
}
Write-Host ""

# ===========================================
# FICHEIROS CSS
# ===========================================
Write-Host "💅 Criando ficheiros CSS..." -ForegroundColor Cyan

$cssFiles = @(
    "public\css\app.css",
    "public\css\variables.css",
    "public\css\home.css",
    "public\css\static.css",
    "public\css\auth.css",
    "public\css\users.css",
    "public\css\spaces.css",
    "public\css\bookings.css",
    "public\css\reviews.css",
    "public\css\favorites.css",
    "public\css\admin.css"
)

foreach ($file in $cssFiles) {
    if (Test-Path $file) {
        Write-Host "   ⏭️  $(Split-Path $file -Leaf) já existe" -ForegroundColor Yellow
    } else {
        New-Item -ItemType File -Path $file -Force | Out-Null
        Write-Host "   ✅ $(Split-Path $file -Leaf) criado" -ForegroundColor Green
    }
}
Write-Host ""

# ===========================================
# FICHEIROS JAVASCRIPT
# ===========================================
Write-Host "⚡ Criando ficheiros JavaScript..." -ForegroundColor Cyan

$jsFiles = @(
    "public\js\app.js",
    "public\js\csrf.js",
    "public\js\search.js",
    "public\js\space-form.js",
    "public\js\booking.js",
    "public\js\calendar.js",
    "public\js\favorites.js",
    "public\js\admin.js"
)

