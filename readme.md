TRANSLATOR
--------------------------------------
- config
- Router - pri zmene upraviť všetky routy ktoré obsahujú locale
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
- Flash messages a Modal sa automaticky invalidujú v BasePresenter::beforeRender
		nieje treba vôbec volať `$this['modal']->redrawControl()`
- data-naja-history => "off" - zabráni ukladaniu adries do historie - form button
- data-naja-append - pridáva ajaxový obsah k predošlému
- data-naja-preppend - pridáva ajaxový obsah pred predošlý
- redirect po uložení formulára sa robí cez `$presenter->payload->forceRedirect = TRUE`