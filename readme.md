TRANSLATOR
--------------------------------------
- config
- Router - pri zmene upraviť všetky routy
- BasePresenter

ORM
--------------------------------------
- config - extensions, orm
- Model/Orm/Orm.php - definicie repozitarov cez anotacie
- Pri logine a vytváraní Nette\Security\IIdentity 
	je nutné previesť relácie $usera na pole cez `$user->toArray(ToArrayConverter::RELATIONSHIP_AS_ARRAY)`

PHINX
--------------------------------------
- spušťa sa z konzoly
- vendor\bin\phinx init - vytvorí konf. súbor phinx.yml
- konfigurácia je v root dir - phinx.yml
- vendor\bin\phinx create MyNewMigration --template="migrations\template"
- v DB je tabulka phinxlog
- vendor\bin\phinx migrate -e development - spusti migracie nad development databazou
- migrácie by nemali obsahovať transakcie!!! 

LESS/CSS/JS
--------------------------------------
- settings/tools/file watchers
- file watcher - nastavit na custom = adresár
- npm packages - npm instal nette.ajax.js


MODAL COMPONENT
--------------------------------------
- Musí sa volať cez action metodu - kôli handlerom formulárov
- Presenter nastaví komponente premennú $showModal = TRUE/FALSE
- Najlepšie je volať modl ajaxom
- Nieje treba volať `$this['modal']->redrawControl()` 
		volá sa vždy tak ako $this->redrawControl('flash')
		
AJAX
---------------------------------------
- data-naja-history => "off" - zabráni ukladaniu adries do historie - form button