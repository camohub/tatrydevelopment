TRANSLATOR
--------------------------------------
- config
- Router - pri zmene upraviť všetky routy
- BasePresenter

ORM
--------------------------------------
- config - extensions, orm
- Model/Orm/Orm.php - definicie repozitarov cez anotacie

PHINX
--------------------------------------
- spušťa sa z konzoly
- vendor\bin\phinx init - vytvorí konf. súbor phinx.yml
- konfigurácia je v root dir - phinx.yml
- vendor\bin\phinx create MyNewMigration --template="migrations\template"
- v DB je tabulka phinxlog
- vendor\bin\phinx migrate -e development - spusti migracie nad development databazou

LESS/CSS/JS
--------------------------------------
- settings/tools/file watchers
- file watcher - nastavit na custom = adresár
- npm packages - npm instal nette.ajax.js