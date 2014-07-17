1. pri instalacii treba vytvorit databazu s collation utf8_general_ci
        kod: CREATE DATABASE `meno_databazy` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
        v tabulkach treba premenovat prefix na potrebny
2. nainportovat tabulky zo subor tables.sql
3. nastavit pristupove prava pre zapis adresaru files, page_cache a suboru error.log
4. premenovat admin adresar
5. nastavit potrebne veci v konfiguracnom subore
6. prihlasenie do adminu admin:ecmsadmin
7. vymazat dump databazy
8. nastylovanie stranky
    - externy font sa moze vygenerovat na stranke http://www.fontsquirrel.com/fontface/generator
9. nezabudnut na styl pre editor a print
