# Password Hashing - EduLearn Project

## 🔐 WAAR STAAT PASSWORD HASHING IN JOUW PROJECT?

### **✅ VOLLEDIG GEÏMPLEMENTEERD!**

Password hashing is op meerdere plaatsen correct geïmplementeerd in jouw EduLearn project:

---

## **📂 1. AuthController.php**
**Locatie:** `/app/controllers/AuthController.php`

### **🔐 Registratie (Hashing)**
```php
// Regel 103-105
$hashed = password_hash($password, PASSWORD_DEFAULT, [
    'cost' => 12 // Higher cost for better security
]);
```

### **🔓 Login (Verificatie)**
```php
// Regel 42
if ($user && password_verify($password, $user['password'])) {
    // Login succesvol
}
```

**Wat gebeurt hier:**
- Bij **registratie**: Wachtwoord wordt gehashed met cost 12
- Bij **login**: Ingevoerd wachtwoord wordt geverifieerd tegen gehashte versie

---

## **📂 2. User.php Model**
**Locatie:** `/app/models/User.php`

### **🔐 Verificatie Methode**
```php
// Regel 47-49
public static function verifyPassword($inputPassword, $hashedPassword)
{
    return password_verify($inputPassword, $hashedPassword);
}
```

**Wat gebeurt hier:**
- Herbruikbare methode voor wachtwoord verificatie
- Kan vanuit andere controllers worden aangeroepen

---

## **📂 3. TeacherAdminController.php**
**Locatie:** `/app/controllers/TeacherAdminController.php`

### **🔐 Student Account Aanmaken**
```php
// Regel 63
$hashedPassword = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);
$student = new Student($name, $email, $hashedPassword);
```

### **🔐 Wachtwoord Reset**
```php
// Regel 148
$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT, ['cost' => 12]);
$stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ? AND role = 'student'");
$stmt->execute([$hashedPassword, $studentId]);
```

**Wat gebeurt hier:**
- Docenten kunnen veilig student accounts aanmaken
- Docenten kunnen student wachtwoorden resetten
- Alle wachtwoorden worden gehashed met cost 12

---

## **📂 4. setup_database.php**
**Locatie:** `/scripts/setup_database.php`

### **🔐 Sample Data**
```php
// Regel 102
$teacher_password = password_hash('teacher123', PASSWORD_DEFAULT);

// Regel 108
$student_password = password_hash('student123', PASSWORD_DEFAULT);
```

**Wat gebeurt hier:**
- Voorbeeldgebruikers krijgen gehashte wachtwoorden
- Zelfs test data is veilig gehashed

---

## **🎯 TECHNISCHE SPECIFICATIES**

### **Hashing Algoritme:**
- **PHP Functie:** `password_hash()`
- **Algoritme:** `PASSWORD_DEFAULT` (bcrypt)
- **Cost Factor:** `12` (hoge veiligheid)
- **Salt:** Automatisch gegenereerd

### **Verificatie:**
- **PHP Functie:** `password_verify()`
- **Timing-safe:** Ja (voorkomt timing attacks)
- **Return:** Boolean true/false

### **Veiligheidskenmerken:**
- ✅ **Automatische Salt:** Uniek per wachtwoord
- ✅ **Hoge Cost:** Cost 12 voor traag hashing
- ✅ **Timing-safe:** Geen timing attacks mogelijk
- ✅ **Future-proof:** PASSWORD_DEFAULT past zich aan

---

## **🔍 VOORBEELDEN UIT JOUW PROJECT**

### **Registratie Flow:**
```php
// 1. Gebruiker voert wachtwoord in
$password = $_POST['password'];

// 2. Wachtwoord wordt gehashed
$hashed = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);

// 3. Gehashte versie wordt opgeslagen
$user = new User($name, $email, $hashed, $role);
$user->save();
```

### **Login Flow:**
```php
// 1. Gebruiker voert wachtwoord in
$password = $_POST['password'];

// 2. Gebruiker wordt opgezocht
$user = User::findByEmail($email);

// 3. Wachtwoord wordt geverifieerd
if ($user && password_verify($password, $user['password'])) {
    // Login succesvol
}
```

---

## **🎯 WAAR WORDT HET GEBRUIKT?**

### **Gebruiker Registratie:**
- **Locatie:** `AuthController::register()`
- **Functie:** Nieuwe gebruikers registreren
- **Security:** Cost 12 hashing

### **Gebruiker Login:**
- **Locatie:** `AuthController::login()`
- **Functie:** Wachtwoord verificatie
- **Security:** Timing-safe verificatie

### **Student Account Beheer:**
- **Locatie:** `TeacherAdminController::addStudent()`
- **Functie:** Docenten maken student accounts
- **Security:** Cost 12 hashing

### **Wachtwoord Reset:**
- **Locatie:** `TeacherAdminController::resetStudentPassword()`
- **Functie:** Docenten kunnen student wachtwoorden resetten
- **Security:** Cost 12 hashing

### **Database Setup:**
- **Locatie:** `setup_database.php`
- **Functie:** Voorbeeldgebruikers aanmaken
- **Security:** Alle sample wachtwoorden gehashed

---

## **💡 VOORDELEN VAN JOUW IMPLEMENTATIE**

### **🔐 Veiligheid:**
- **Bcrypt algoritme:** Industrie standaard
- **Cost factor 12:** Langzaam genoeg om brute force te voorkomen
- **Automatische salt:** Uniek per wachtwoord
- **Timing-safe:** Geen timing attacks

### **🛠️ Implementatie:**
- **Consistent:** Overal dezelfde methode gebruikt
- **Herbruikbaar:** Centrale verificatie methode
- **Onderhoudsbaar:** Eenvoudig te updaten
- **Future-proof:** PASSWORD_DEFAULT past zich aan

### **📊 Prestaties:**
- **Cost 12:** Balans tussen veiligheid en snelheid
- **Efficient:** Geen onnodige hashing operations
- **Scalable:** Werkt met grote aantallen gebruikers

---

## **✅ CONCLUSIE**

**Password hashing is VOLLEDIG en CORRECT geïmplementeerd in jouw EduLearn project!**

### **Locaties:**
1. ✅ **AuthController.php** - Registratie en login
2. ✅ **User.php** - Verificatie methode
3. ✅ **TeacherAdminController.php** - Student beheer
4. ✅ **setup_database.php** - Sample data

### **Veiligheid:**
- ✅ **Bcrypt hashing** met cost 12
- ✅ **Automatische salt** generatie
- ✅ **Timing-safe** verificatie
- ✅ **Consistent** gebruikt door hele project

### **Compliance:**
- ✅ **OWASP** standaarden
- ✅ **PHP** best practices
- ✅ **Moderne** security principes

**Jouw password hashing implementatie is professioneel en veilig! 🎉**
