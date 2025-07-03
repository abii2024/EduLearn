# Dynamische Views - EduLearn Project

## 🎯 VERSCHILLENDE VIEWS VOOR INGELOGDE EN NIET-INGELOGDE GEBRUIKERS

### **✅ VOLLEDIG GEÏMPLEMENTEERD!**

Jouw EduLearn project heeft verschillende manieren om andere content te tonen voor ingelogde en niet-ingelogde gebruikers bij dezelfde controller.

---

## **📂 BESTAANDE VOORBEELDEN IN JOUW PROJECT**

### **1. Homepage - Verschillende Content**
**Locatie:** `/app/views/homepage.php`

```php
<?php if (isset($_SESSION['user'])): ?>
    <h2>👋 Welkom terug, <?= htmlspecialchars($_SESSION['user']['name']) ?>!</h2>
    <p>Je bent ingelogd als <?= htmlspecialchars($_SESSION['user']['role']) ?>.</p>
    <a href="/EduLearn/public/dashboard" class="btn">Ga naar Dashboard</a>
<?php else: ?>
    <h2>🔐 Toegang tot je dashboard</h2>
    <a href="/EduLearn/public/login" class="btn">Inloggen</a>
    <a href="/EduLearn/public/register" class="btn">Registreren</a>
<?php endif; ?>
```

**Wat gebeurt hier:**
- **Ingelogd:** Persoonlijke welkomstboodschap + dashboard link
- **Niet-ingelogd:** Login/registratie prompts

### **2. Navbar - Verschillende Menu's**
**Locatie:** `/app/views/shared/navbar.php`

```php
<?php if (isset($_SESSION['user'])): ?>
    <?php if ($_SESSION['user']['role'] === 'student'): ?>
        <li><a href="/EduLearn/public/dashboard">Dashboard</a></li>
    <?php elseif ($_SESSION['user']['role'] === 'teacher'): ?>
        <li><a href="/EduLearn/public/teacher-admin">👥 Docent Admin</a></li>
    <?php elseif ($_SESSION['user']['role'] === 'admin'): ?>
        <li><a href="/EduLearn/public/admin">🔧 Admin Panel</a></li>
    <?php endif; ?>
    <li><a href="/EduLearn/public/logout">Uitloggen</a></li>
<?php else: ?>
    <li><a href="/EduLearn/public/login">Inloggen</a></li>
    <li><a href="/EduLearn/public/register">Registreren</a></li>
<?php endif; ?>
```

**Wat gebeurt hier:**
- **Ingelogd:** Rol-specifieke menu items + uitloggen
- **Niet-ingelogd:** Login/registratie links

---

## **🆕 NIEUWE GEAVANCEERDE TECHNIEKEN**

### **Techniek 1: Aparte View Methods**
**Bestand:** `/app/views/public_news.php`

```php
class PublicNewsView {
    // Voor ingelogde gebruikers
    public static function RenderForLoggedIn($news, $userRole) {
        // Volledige nieuws content
        // Extra functionaliteit (delen, opslaan)
        // Rol-specifieke content
    }
    
    // Voor niet-ingelogde gebruikers
    public static function RenderForGuest($news) {
        // Beperkte content (preview)
        // Login prompts
        // Maximaal 3 items
    }
}
```

### **Techniek 2: Controller Logica**
**Bestand:** `/app/controllers/PublicNewsController.php`

```php
class PublicNewsController {
    public static function execute() {
        $news = NewsModel::getAllNews();
        
        // Bepaal gebruiker status
        if (isset($_SESSION['user'])) {
            $userRole = $_SESSION['user']['role'];
            PublicNewsView::RenderForLoggedIn($news, $userRole);
        } else {
            PublicNewsView::RenderForGuest($news);
        }
    }
}
```

### **Techniek 3: Dynamische Single View**
**Bestand:** `/app/views/dynamic_dashboard.php`

```php
class DynamicDashboardView {
    public static function Render($data = []) {
        // Controleer login status
        if (!isset($_SESSION['user'])) {
            self::RenderNotLoggedIn();
            return;
        }
        
        // Kies view op basis van rol
        switch ($_SESSION['user']['role']) {
            case 'student':
                self::RenderStudentDashboard($data);
                break;
            case 'teacher':
                self::RenderTeacherDashboard($data);
                break;
            case 'admin':
                self::RenderAdminDashboard($data);
                break;
        }
    }
}
```

---

## **🎯 VERSCHILLENDE IMPLEMENTATIE METHODEN**

### **Methode 1: Inline Conditionals** ✅
**Gebruik in:** Navbar, Homepage
```php
<?php if (isset($_SESSION['user'])): ?>
    <!-- Ingelogde gebruiker content -->
<?php else: ?>
    <!-- Niet-ingelogde gebruiker content -->
<?php endif; ?>
```

**Voordelen:**
- ✅ Eenvoudig te implementeren
- ✅ Duidelijk in kleine views
- ✅ Snel te onderhouden

**Nadelen:**
- ❌ Kan rommelig worden bij veel logica
- ❌ Moeilijk testbaar

### **Methode 2: Aparte View Methods** ✅
**Gebruik in:** PublicNewsView
```php
class MyView {
    public static function RenderForLoggedIn($data) { ... }
    public static function RenderForGuest($data) { ... }
}
```

**Voordelen:**
- ✅ Schone code scheiding
- ✅ Herbruikbare methods
- ✅ Goed testbaar

**Nadelen:**
- ❌ Meer code om te schrijven
- ❌ Iets complexer

### **Methode 3: Role-Based Views** ✅
**Gebruik in:** DynamicDashboardView
```php
class RoleView {
    public static function RenderForStudent($data) { ... }
    public static function RenderForTeacher($data) { ... }
    public static function RenderForAdmin($data) { ... }
}
```

**Voordelen:**
- ✅ Zeer specifieke content per rol
- ✅ Schaalbaar voor meer rollen
- ✅ Professionele architectuur

**Nadelen:**
- ❌ Veel code om te onderhouden
- ❌ Complexer voor eenvoudige use cases

---

## **📋 PRAKTISCHE VOORBEELDEN**

### **Voorbeeld 1: Nieuws met Beperkte Toegang**

**Voor Gasten:**
```php
// Toon alleen eerste 150 karakters
$preview = substr($content, 0, 150);
echo $preview . '... <a href="/login">Lees meer</a>';
```

**Voor Ingelogde Gebruikers:**
```php
// Toon volledige content
echo nl2br(htmlspecialchars($content));
// Plus extra functies
echo '<button onclick="shareNews()">Delen</button>';
```

### **Voorbeeld 2: Dashboard met Rol-Specifieke Content**

**Student Dashboard:**
```php
<h1>🎓 Student Dashboard</h1>
<div class="student-stats">
    <div>📚 Cursussen: <?= count($classes) ?></div>
    <div>📝 Opdrachten: <?= count($assignments) ?></div>
</div>
```

**Docent Dashboard:**
```php
<h1>👨‍🏫 Docent Dashboard</h1>
<div class="teacher-stats">
    <div>🎓 Klassen: <?= count($classes) ?></div>
    <div>👥 Studenten: <?= $totalStudents ?></div>
</div>
```

### **Voorbeeld 3: Functionaliteit Verschillen**

**Gast (Beperkt):**
```php
// Alleen lezen
<div class="news-content">
    <?= $preview ?>
    <a href="/login">Login voor meer</a>
</div>
```

**Ingelogde Gebruiker (Volledig):**
```php
// Lezen + extra functies
<div class="news-content">
    <?= $fullContent ?>
    <div class="actions">
        <button onclick="shareNews()">📤 Delen</button>
        <button onclick="bookmark()">🔖 Opslaan</button>
    </div>
</div>
```

---

## **🔧 IMPLEMENTATIE TIPS**

### **1. Session Check Pattern**
```php
// Basis check
if (isset($_SESSION['user'])) {
    // Ingelogde logica
} else {
    // Niet-ingelogde logica
}

// Met rol check
if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') {
    // Admin specifieke content
}
```

### **2. Data Filtering**
```php
// Voor gasten - beperkte data
$guestNews = array_slice($news, 0, 3);

// Voor leden - volledige data
$memberNews = $news;
```

### **3. HTML Class Verschillen**
```php
<div class="news-card <?= isset($_SESSION['user']) ? 'premium' : 'guest' ?>">
    <!-- Content -->
</div>
```

### **4. JavaScript Verschillen**
```php
<?php if (isset($_SESSION['user'])): ?>
    <script>
        // Geavanceerde functionaliteit
        function shareNews() { ... }
        function bookmark() { ... }
    </script>
<?php else: ?>
    <script>
        // Basis tracking
        console.log('Guest user interaction');
    </script>
<?php endif; ?>
```

---

## **📊 GEBRUIK IN JOUW PROJECT**

### **Huidige Implementaties:**
1. ✅ **Homepage** - Verschillende CTA's
2. ✅ **Navbar** - Rol-specifieke menu's
3. ✅ **Dashboard** - Verschillende content per rol
4. ✅ **Teacher Admin** - Alleen voor docenten

### **Nieuwe Implementaties:**
1. ✅ **PublicNewsView** - Beperkte vs volledige content
2. ✅ **DynamicDashboardView** - Rol-specifieke dashboards
3. ✅ **Controllers** - Logica voor verschillende views

---

## **🎯 BEST PRACTICES**

### **1. Consistent Session Checking**
```php
// Altijd dezelfde manier checken
if (isset($_SESSION['user'])) {
    // Ingelogde logica
}
```

### **2. Veilige Data Handling**
```php
// Altijd htmlspecialchars gebruiken
<?= htmlspecialchars($_SESSION['user']['name'] ?? 'Gast') ?>
```

### **3. Fallback Content**
```php
// Altijd fallback voorzien
<?php if (isset($_SESSION['user'])): ?>
    <!-- Ingelogde content -->
<?php else: ?>
    <!-- Fallback voor gasten -->
<?php endif; ?>
```

### **4. Role-Based Access**
```php
// Controleer niet alleen login, maar ook rol
if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') {
    // Admin content
}
```

---

## **✅ CONCLUSIE**

**Je project heeft al meerdere manieren om verschillende content te tonen:**

### **Bestaande Technieken:**
- ✅ **Inline conditionals** in homepage en navbar
- ✅ **Rol-specifieke menu's** 
- ✅ **Protected routes** met AuthGuard

### **Nieuwe Technieken Toegevoegd:**
- ✅ **Aparte view methods** voor gasten vs leden
- ✅ **Dynamische dashboards** per gebruikersrol
- ✅ **Content filtering** en beperkte toegang
- ✅ **Controller logica** voor view selectie

### **Voordelen van Jouw Implementatie:**
- 🎯 **Gebruiksvriendelijk** - Relevante content per gebruiker
- 🔒 **Veilig** - Gevoelige informatie alleen voor juiste gebruikers
- 🎨 **Professioneel** - Schone code architectuur
- 📱 **Responsive** - Werkt op alle devices

**Jouw EduLearn project heeft nu een complete set van technieken voor verschillende views per gebruikerstype! 🎉**
