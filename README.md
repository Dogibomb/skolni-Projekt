# CRM Lite
Webová aplikace pro správu vztahů se zákazníky (CRM systém).
Evidence zákazníků, objednávek, poznámek a správa uživatelů.

## Přihlašení
- `admin/admin` – plný přístup, správa zákazníků, objednávek a poznámek
- `user/user` – běžný uživatel, přístup k e-shopu a vlastnímu profilu

## Hlavní části
- `index.php` – úvodní stránka / admin dashboard
- `shop/shop.php` – e-shop s produkty
- `orders/orders.php` – přehled objednávek (admin)
- `customers/customers.php` – správa zákazníků (admin)
- `poznamky/notes.php` – správa poznámek
- `profile/profile.php` – uživatelský profil
- `login/login.php` – přihlášení
- `register/register.php` – registrace nového uživatele

## Backend
- `includes/bootstrap.php` – inicializace session a DB připojení
- `includes/supabase_db.php` – konfigurace PostgreSQL databáze
- `includes/env.php` – proměnné prostředí
- `shop/create_order.php` – API pro vytvoření objednávky
- `orders/list_orders.php` – API endpoint pro seznam objednávek
- `poznamky/save_note.php` – API pro přidání/smazání poznámky
- `poznamky/list_notes.php` – API endpoint pro seznam poznámek

## Databáze
Aplikace používá PostgreSQL se třemi tabulkami:
- `Users` – uživatelé systému
- `Orders` – objednávky (propojené s Users přes user_id)
- `Notes` – poznámky (propojené s Users přes user_id)

## Technologie
- **Backend:** PHP, Apache
- **Databáze:** PostgreSQL
- **Frontend:** HTML, CSS, JavaScript (AJAX)
- **Deployment:** Docker
