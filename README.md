# CRM Lite - Dokumentace

## Úvod

CRM Lite je webová aplikace určená pro správu vztahů se zákazníky a správu objednávek. Systém umožňuje evidenci zákazníků, správu objednávek produktů, psaní poznámek k zákazníkům a sledování jejich nákupní historie. Aplikace je postavena na PHP s Apache webovým serverem a PostgreSQL databází.

---

## Analýza problému

- **evidenci zákazníků** - všechny informace na jednom místě
- **Sledování objednávek** - přehled o koupisích jednotlivých zákazníků
- **Poznámky** - možnost přidávat a spravovat poznámky k zákazníkům
- **Autentizace** - bezpečný přístup pro různé typy uživatelů

CRM Lite řeší tyto problémy tím, že poskytuje uživatelsky přívětivé rozhraní pro správu těchto údajů a funkcionalit.

---

## Struktura projektu a souborů

```
Školní Projekt/
├── index.php                  # Hlavní vstupní bod 
├── Dockerfile                 # Docker konfiguraci
├── basicsetup.css             # Globální CSS styly
├── navbar.css                 # Styly navigační lišty
├── grid.css                   # Pomocné CSS třídy
│
├── includes/
│   ├── bootstrap.php          # Inicializace session a DB připojení
│   ├── env.php                # Funkce pro práci s proměnnými prostředí
│   └── supabase_db.php        # Konfigurace PostgreSQL databáze 
│
├── login/
│   ├── login.php              # Přihlašovací formulář
│   ├── login.css              # Styly přihlašovací stránky
│   └── logout.php             # Odhlášení a zničení session
│
├── register/
│   └── register.php           # Registrační formulář pro nové uživatele
│
├── shop/
│   ├── shop.php               # Stránka obchodu s produkty
│   ├── shop.css               # Styly obchodu
│   ├── shop.js                # JavaScript pro nákup produktů 
│   └── create_order.php       # Backend pro vytvoření objednávky
│
├── orders/
│   ├── orders.php             # Přehled všech objednávek 
│   ├── orders.css             # Styly stránky objednávek
│   ├── orders.js              # JavaScript pro načítání objednávek
│   └── list_orders.php        # API endpoint pro seznam objednávek
│
├── customers/
│   ├── customers.php          # Přehled zákazníků s filtrem 
│   ├── customers.css          # Styly stránky zákazníků
│   ├── customer.php           # Detailní stránka jednotlivého zákazníka 
│   └── customer.css           # Styly detailní stránky
│
├── poznamky/ (Notes)
│   ├── notes.php              # Přehled poznámek 
│   ├── notes.css              # Styly poznámek
│   ├── note.js                # JavaScript pro správu poznámek
│   ├── save_note.php          # Backend pro přidání/smazání poznámky
│   └── list_notes.php         # API endpoint pro seznam poznámek
│
├── profile/
│   ├── profile.php            # Uživatelský profil 
│   └── profile.css            # Styly profilu
│
├── index/
│   ├── index.php              # Admin dashboard
│   └── styles.css             # Styly dashboardu
│
└── img/
    └── contacts.svg           # Ikony pro dashboard
```

---

## Návrh databáze

Aplikace používá PostgreSQL databázi s následujícím schématem:

### Tabulka: `Users`
```
- id (INTEGER PRIMARY KEY)
- name (VARCHAR) - jméno uživatele/admina
- email (VARCHAR UNIQUE) - emailová adresa
- password (VARCHAR) - heslo (uloženo v plain textu*)
- created_at (TIMESTAMP DEFAULT NOW())
```

### Tabulka: `Orders`
```
- id (INTEGER PRIMARY KEY)
- user_id (INTEGER FOREIGN KEY → Users.id)
- product (VARCHAR) - název produktu
- price (INTEGER) - cena v Kč
- name (VARCHAR) - jméno objednavatele
- email (VARCHAR) - email objednavatele
- created_at (TIMESTAMP DEFAULT NOW())
```

### Tabulka: `Notes`
```
- id (INTEGER PRIMARY KEY)
- user_id (INTEGER FOREIGN KEY → Users.id)
- text (TEXT) - obsah poznámky
- created_at (TIMESTAMP DEFAULT NOW())
```

---

## Popis funkcionalit

### 1. **Autentizace a registrace**
- Uživatelé se mohou zaregistrovat přes formulář
- Přihlášení pomocí jména a hesla
- Session-based autentizace
- Rozdělení rolí: admin vs. běžný uživatel

### 2. **E-shop (Shop)**
- Zobrazení dostupných produktů (Počítač, Mobil, Tablet)
- Možnost objednání produktu
- Automatické přesměrování na přihlášení pro nepřihlášené uživatele
- Potvrzení objednávky

### 3. **Správa objednávek (Orders)**
- Přehled všech objednávek v systému (admin)
- Zobrazení informací: jméno, email, produkt, cena, čas objednávky
- Kliknutí na zákazníka vede na jeho detailní stránku

### 4. **Správa zákazníků (Customers)**
- Filtr podle jména/emailu
- Rozdělení na zákazníky s objednávkami a bez
- Detailní stránka jednotlivého zákazníka
- Přehled všech objednávek zákazníka

### 5. **Poznámky (Poznamky)**
- Přidávání poznámek k jednotlivým zákazníkům
- Filtrování poznámek podle zákazníka
- Smazání poznámek
- AJAX načítání pro hladší UX

### 6. **Uživatelský profil (Profile)**
- Zobrazení informací o přihlášeném uživateli
- Přehled jeho objednávek
- Přepínání viditelnosti hesla

### 7. **Admin Dashboard**
- Přehledový dashboard pro administrátory
- Odkazy na všechny části administrace
- Karty s ikonami pro jednotlivé funkce

---

## Ukázky obrazovek

Následující screenshoty dokumentují jednotlivé části aplikace:

### Screenshot 1: Přihlašovací stránka
Formulář pro přihlášení s poli pro jméno a heslo. Obsahuje odkaz na registraci.

### Screenshot 2: Registrační stránka
Formulář pro registraci nového uživatele. Zahrnuje validaci formuláře a kontrolu duplicitních emailů.

### Screenshot 3: E-shop
Hlavní e-shop se třemi produkty (Počítač, Mobil, Tablet) s cenou a tlačítkem pro koupi.

### Screenshot 4: Uživatelský profil
Stránka s informacemi přihlášeného uživatele a přehledem jeho objednávek.

### Screenshot 5: Admin Dashboard
Přehledový dashboard s kartami vedoucími na správu zákazníků, poznámek, objednávek a e-shop.

### Screenshot 6: Přehled zákazníků
Seznam všech zákazníků s možností filtrování. Rozdělení na zákazníky s objednávkami a bez.

### Screenshot 7: Detailní stránka zákazníka
Informace o jednotlivém zákazníkovi a přehled všech jeho objednávek.

### Screenshot 8: Přehled objednávek
Tabulka všech objednávek v systému s informacemi o zákazníkovi, produktu, ceně a času.

### Screenshot 9: Správa poznámek
Formulář pro přidání poznámky a seznam všech poznámek s možností filtrace a smazání.

### Screenshot 10: Navbar a UI
Navigační lišta s logem a odkazech na jednotlivé sekce aplikace.

---

## Závěr

CRM Lite je funkční webová aplikace demonstrující klíčové koncepty webového vývoje včetně:
- Frontend vývojdu (HTML, CSS, JavaScript)
- Backend logiky (PHP)
- Databázových systémů (PostgreSQL)
- Autentizace a autorizace
- REST API a AJAX komunikace
- Deployment (Docker, Apache)

---