<?PHP
// File: $Id: class.user_perms.php 28 2008-05-11 19:18:49Z mistral $
// +----------------------------------------------------------------------+
// | Version: Sefrengo $Name:  $                                          
// +----------------------------------------------------------------------+
// | Copyright (c) 2005 - 2007 sefrengo.org <info@sefrengo.org>           |
// +----------------------------------------------------------------------+
// | This program is free software; you can redistribute it and/or modify |
// | it under the terms of the GNU General Public License                 |
// |                                                                      |
// | This program is subject to the GPL license, that is bundled with     |
// | this package in the file LICENSE.TXT.                                |
// | If you did not receive a copy of the GNU General Public License      |
// | along with this program write to the Free Software Foundation, Inc., |
// | 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA               |
// |                                                                      |
// | This program is distributed in the hope that it will be useful,      |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of       |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the        |
// | GNU General Public License for more details.                         |
// |                                                                      |
// +----------------------------------------------------------------------+
// + Autor: $Author: mistral $
// +----------------------------------------------------------------------+
// + Revision: $Revision: 28 $
// +----------------------------------------------------------------------+
// + Description:
// +----------------------------------------------------------------------+
// | Authors: Bj�rn Brockmann <bjoern@project-gooseberry.de>              |
// |          J�rgen Br�ndle <braendle@web.de>                            |
// +----------------------------------------------------------------------+
// | Changed: 20.02.2004 - J�rgen Br�ndle                                 |
// |          Erweiterung von get_rights_panel um Parent-Rechte           |
// |          23.02.2004 - Bj�rn Brockmann                                |
// |          set_group_rights erweitert um check der 2. Parent Ebene     |
// |          empty(...) ersetzt gegens strlen($gruppenrights) < 1        |
// |          �nderungen sind mit //change bb markiert                    |
// |          24.02.2004 - J�rgen Br�ndle                                 |
// |          Sperren des Rechtepanels, wenn keine konfigurierbaren Grup- |
// |          pen exitieren. Konstruktor liest hierf�r die Anzahl der     |
// |          Gruppen aus und get_right_panel pr�ft diesen Wert           |
// |          get_right_fields zu private Methode _get_right_fields       |
// |          12.03.2004 - J�rgen Br�ndle                                 |
// |          Areas ohne eigene Rechte subtituiert, betrifft zur Zeit nur |
// |          Area_SCAN und AREA_SCANCONTROL                              |
// |          12.04.2004 - J�rgen Br�ndle                                 |
// |          Rechtepanel komplett in PHP erzeugen, wegen JS-Problemen in |
// |          Mozilla bei dynamisch geschriebenen Formularen              |
// |          24.04.2004 - J�rgen Br�ndle                                 |
// |          Dokumentation, Minor Changes                                |
// |          25.04.2004 - J�rgen Br�ndle                                 |
// |          Dokumentation, Rechtepanel um Zeile zum Setzen oder L�schen |
// |          aller Rechte einer Benutzergruppe erg�nzt                   |
// |          02.05.2004 - J�rgen Br�ndle                                 |
// |          Dokumentation, Minor Changes                                |
// |          10.05.2004 - J�rgen br�ndle                                 |
// |          Neue Funktion: is_any_perm_set()                            |
// |          20.06.2006 - Bj�rn Brockmann                                |
// |          Rechtevererbung, Addonobjekte hinzugef�gt                   |
// +----------------------------------------------------------------------+


/**
 * Klasse cms_perms
 *
 * Rechtemanagement f�r Sefrengo
 *
 * Diese Klasse stellt die Funktionalit�ten f�r die Rechteverwaltung zur
 * Verf�gung. Hierbei werden verschiedenste Funtkionen f�r die Abfrage und
 * das Setzen von Rechten bereitgestellt.
 *
 * Das Rechtemanagement ist Rollenbasiert, benutzerbezogene Rechte werden
 * erst in sp�teren Versionen direkt unterst�tzt.
 * Jeder Benutzer von Sefrengo kann einer oder mehreren Gruppen bzw.
 * Rollen zugeordnet werden. Hinzukommt, das alle Rechte sprachabh�ngig fest-
 * gelegt werden, d.h. ein User kann in verschiedenen Sprache unterschiedliche
 * Rechte f�r die Arbeit im Backend erhalten.
 *
 * Alle Rechte eines Benutzers ergeben sich aus der Summe aller Einzelrechte
 * der Gruppen des Users in der jeweiligen
 * aktiven Sprache.
 *
 * Das Rechtemanagement kennt drei Ebenen: Area, Main, Detail
 * Diese drei Ebenen vereben Ihre Rechte von oben nach unten, d.h. vom Area
 * auf Main auf Detail. Eine Vererbung innerhalb einer Rechtegruppe findet
 * zur Zeit nur in Ausnahmef�llen statt, die bei den entsprechenden Bereichen
 * noch erl�utert werden.
 *
 * Als Area sind folgende Bereiche in Sefrengo festgelegt:
 * Frontend
 * Backend
 * Redaktion     : Seiten, Dateimanager
 * Style         : Layout, Stylesheet, JavaScript, Module, Templates
 * Adminsitration: Projekte, Einstellungen, Plugins
 *
 * F�r alle Areas kann man festlegen, ob eine Benutzergruppe diesen Bereich betreten
 * kann oder nicht. In der Navigation wirkt sich dies so aus, dass der entsprechende
 * Navigationspunkt nur zu sehen ist, wenn ein User den Bereich betreten darf.
 *
 * Unabh�ngig vom Betreten eines Areas ist die Grundkonfiguration f�r die Rechte
 * des Benutzers in einem Bereich. Diese k�nnen so eingestellt werden, das der Benutzer
 * sehr wohl das Recht hat Inhalte dieses Bereiches zu sehen, auch wenn er den Bereich
 * nicht betreten (und damit bearbeiten darf).
 * Ein Anwendungsfall hierf�r ist das Bearbeiten von Seiten wo Elemente einer Seite
 * mittels CSS-Stilen formatiert werden sollen. Auch wenn der Benutzer keine Zugriffsrechte
 * in das Area Style/Stylesheet hat, kann er die angelegten CSS-Stile dann z.B. in einer
 * Auswahlliste angezeigt bekommen.
 *
 * Die Grundkonfiguration eines Areas umfasst die Summe aller Main- und Detail-Rechte,
 * die sich in dem gew�hlten Schema von max. 16 Main- und 16-Detail-Rechten unterbringen
 * lassen. Details der Rechte in den verschiedenen Bereichen finden sich in den entsprechenden
 * Rechterastern, die sich in der Dokumentation finden.
 *
 * Main-Rechte sind in allen Areas anzutreffen und beziehen sich auf die erste Organisations-
 * ebene des jeweiligen Areas, z.B. Kategorien im Area Redaktion/Seiten, Verzeichnisse im
 * Area Redaktion/Dateimanager. Main-Rechte setzen sich aus den Rechten zu den Funktionalit�ten,
 * die in dieser Organisationsebene notwendig sind, und den Rechten, die auf der Detailebene
 * notwendig sind, zusammen. Diese Kombination ist f�r die Vererbung der Rechte notwendig, da
 * die Main-Ebene festlegt, welche Funktionalit�ten auf Elemente der Detailebene f�r den
 * Benutzer erreichbar sind.
 *
 * Detail-Rechte beziehen sich - wie schon angedeutet - auf die Funktionalit�ten der Elemente
 * unterhalb der Main-Ebene und finden sich derzeitig nur im Bereich "Redaktion/Seiten",
 * "Redaktion/Dateimanager" und im Bereich Plugin.
 *
 * Die Vererbung der Rechte erm�glicht eine schnelle und einfache Methode zur Rechteverwaltung,
 * die vorallem die Speicherung in der Datenbank minimiert. Es werden nur Rechte in der Datenbank
 * gespeichert, wenn sich Diffenrenzen zur Vererbung der �bergeordneten Rechte ergeben.
 * Durch die Vererbung ergibt sich auch eine klare Gewichtung im Rechtemanagement: Detailrechte
 * stehen �ber Mainrechten, die wiederum �ber Area-Rechten stehen. Vorausgesetzt die Rechte sind
 * in der Ebene oder am Element selbst anderst gesetzt als in der dar�berliegenden Ebene.
 *
 * Wichtig in diesem Zusammenhang ist die Tatsache, das sich die Vererbung nicht an den Element-
 * hierachienen orientiert, sondern nur an der Rechtehierarchie an sich. Dies bedeutet, das ein Element
 * immer von der �bergeordneten Rechte-Ebene erbt und nicht von der �bergeordneten Elemente-Ebene.
 * Ein Element A (Detal) liegt in einem Ordner B (Main), dieser liegt wiederum in einem Ordner C
 * (Main) in ein Bereich D. Element A erbt die Rechte von Ordner B, aber Ordner B erbt die Rechte
 * vom Bereich D und nicht vom Ordner C.
 *
 * Von dieser Regel gibt es zwei Abweichungen:
 * Redaktion/Seiten: Eine Kategorie erbt beim Anlegen die Rechte der �bergeordneten Elementebene,
 * d.h. die Rechte der Kategorie in der die neue Katagorie angelegt wird.
 * Redaktion/Dateimanager: Hier erbt ein Verzeichnis beim Anlegen die Rechte der �bergeordneten
 * Verzeichnisses.
 *
 * VORSICHT: Diese Aussage ist beschr�nkt auf den Zeitpunkt des Anlegens einer Kategorie oder Ver-
 * zeichnisses. Nach dem Anlegen greift immer die vorher beschriebene Art der Vererbung bezogen auf
 * die Rechtehierarchie!
 * Zuk�nftige Anpassungen werden diese Unterschiede weiter umbauen zu einer Vererbung die sich auf
 * die Elementhierarchien abbildet.
 *
 * @author	J�rgen Br�ndle, Bj�rn Brockmann
 * @since	ALPHA
 * @version 0.9 / 20060620
**/
class cms_perms {
	//Benutzereigenschaften - nur im Modus $simulate = true sichtbar
	var $simulate, $user_id, $user_nick, $user_name, $user_surname, $user_email, $user_is_active, $user_is_deletable;
	//Gruppeneigenschaften - nur im Modus $simulate = true sichtbar
	var $name, $description, $group_is_deletable, $group_is_active;
	// Flag: User ist Adminstrator. R�ckgabe des Wertes durch die Funktion is_admin()
	var $_is_admin;
	//Eigenschaften zur projekten, Sprachen und Starteinstellungen
	var $client, $lang, $lang_charset, $user_start_client, $user_start_lang;
	//3D-dimensionales Array mit Rechtedaten
	var $perms = array();
	//Liste der Rechtegruppen des aktiven Benutzers
	var $idgroup = array();
	//Liste aller schon abgefragten Rechte einer Webanfrage
	var $loaded_types = array();
	//Addonobjekte zur rekursiven Recheabfrage
	var $perm_addon;
	//Liste der Rechteabh�ngigkeiten
	var $parent_rights = array();
	//Anzahl der angelegten Gruppen der CMS-Installation
	var $_group_count;
	//Objekte f�r die Umgang mit der Datenbank und dem Debugging
	var $cms_db, $db, $deb;

	// Verschiedene Strings
	// Insert-SQL f�r neuen Rechte-Eintrag
	var $insert_perm     = "INSERT INTO %s (idgroup, idlang, type, perm, id) VALUES (%s, %s, '%s', %s, '%s')";

	// Image f�r den Aufruf des Rechtepanel
	var $security_button = 'but_edit_security.gif';
	var $security_width  = '16';
	var $security_height = '16';

	/**
	 *
	 * Konstruktor der Klasse
	 * Erstellt das Rechte-Objekt f�r die aktuelle Webanfrage
	 * Ermittelt die Rechtegruppen, Sprachen- und Projektzugeh�rigkeiten des angemeldeten Users
	 *
	 * @param	int		$client				Aktuelles Projekt
	 * @param	int		$lang				Aktuelle Sprache
	 * @param	bool	$simulate			Simulationsmodus  - Abfrage der Rechte f�r die Rechtekonfiguration
	 *										optional - Standard: false
	 * @param	int		$simulate_idgroup	Simulationsgruppe - Gruppe f�r die Rechtekonfiguration
	 *										optional - Standard: 0
	 *
	 * @global	$changeclient
	 * @global	$lang_charset
	 * @global	$val_ct
	 * @global	$cms_db
	 * @global	$db
	 * @global	$deb
	 * @global	$auth
	 *
	 * @return	keine R�ckgabe
	 *
	 * @Version: 0.7 / 20040502
	 * Change: 02.05.2004 - JB - Delete: Anzahl der konfigurierbaren Gruppen ermitteln
	 *
	**/
	function __construct($client, $lang, $simulate = false, $simulate_idgroup = 0) {
		global $changeclient, $lang_charset, $val_ct, $auth;

		$this->_is_admin = false;
		$this->cms_db   = $GLOBALS['cms_db'];
		$this->deb       = $GLOBALS['deb'];
		$this->db        = new DB_cms;

		//Userid aus phplib beziehen
		$this->user_id   = $auth->auth['uid'];

		//Wenn User 'nobody', user ist standarduser
		if ($this->user_id == 'nobody') $this->user_id = '2';

		// lang und client auf numerische Werte �berpr�fen
		// verhindern das "fremde" cookie-werte ausgelesen werden
		if (!is_numeric($client)) unset($client);
		if (!is_numeric($lang))   unset($lang);

		if ($simulate) {
			$this->idgroup[] = $simulate_idgroup;
			$this->simulate  = $simulate;
		}

		// Ben�tigte Gruppeninformationen anhand der Userid extrahieren
		$this->get_group_infos_from_user();

		// Alle f�r Gruppe m�glichen Clients und Sprachen herausfinden
		$sql  = 'SELECT ';
		$sql .= '   DISTINCT type, id, perm ';
		$sql .= 'FROM ';
		$sql .=     $this->cms_db['perms'] . ' ';
		$sql .= 'WHERE ';
		$sql .= '   type IN (\'lang\', \'intern\') ';
		$sql .= 'AND ';
		$sql .= '   idgroup IN (' . implode(',', $this->idgroup) . ',0)';
		$this->db->query($sql);
		// to: 3d-array ???? notwendig ????
		while($this->db->next_record()) {
			$this->perms[$this->db->f('type')][$this->db->f('id')][$this->db->f('perm')] = true;
		}

		// Sprache ermitteln
		$this->_find_out_lang($client, $lang, $changeclient, $lang_charset);

		// Perms suchen, wenn $this->client, $this->lang gesetzt sind
		// Diese sind nicht gesetzt, wenn eine neue Gruppe angelegt wurde, aber noch keine
		// Sprache enth�lt
		if (empty($this->client) || empty($this->lang)) {
			@$this->deb->collect('No Client or lang found. Insert a new group?');
			return;
		}

		// Hole die Abh�ngigkeiten der Zugriffsrechte und speichert diese als Array ab
		$cms_perm_parents = $val_ct->get_by_group_key('user_perms', 'key1', 'cms_access', $client = 0, $lang = 0);
		foreach($cms_perm_parents['cms_access'] AS $key => $value) {
			if (!empty($value)) {
				$parent = $key;
				$subs = explode(',', $value);
				foreach($subs as $sub => $val) {
					$this->parent_rights[$val] = $parent;
					$parent = $val;
				}
			}
		}
	}

	/**
	 * Gruppeniformationen aus der aktuellen user_id extrahieren.
	 * Die wichtigsten Daten sind idgroup is_deletable, is_admin, is_active
	**/
	function get_group_infos_from_user() {
		if($this->simulate) {
			$this->_get_group_infos_from_user_simulate();
		} else {
			$this->_get_group_infos_from_user();
		}
	}

	/**
	 * Erstellt den HTML-Code f�r die Anzeige einer Rechte-Popup-Ebene incl. des JavaScript-Aufrufs und der Buttons zum Aufruf.
	 *
	 * Die Werten der Gruppen, GruppenIDs und GruppenRechte f�r ein Element werden mit der Methode _get_right_fields in
	 * Hidden-Fields eingetragen.
	 * Die Funktion holt die Rechte, die f�r das Element festgelegt sind. Gibt es keine Eintragung wird die �bergeordnete Ebene
	 * ausgewertet, wenn der Parameter $parent_id ungleich 0 ist, ansonsten werden die Rechte des Areas verwendet.
	 *
	 * Gibt es keine konfigurierbaren Gruppen, wird nichts zur�ckgegeben, andernfalls wird ein Array mit den notwendigen
	 * HTML-Konstrukten f�r das Rechtepanel zur�ckgeliefert.
	 *
	 * Da das HTML-Konstruct in PHP aufgebaut wird, ist die Ansicht nur �ber CSS-Stile anpassbar. Hierzu dienen die folgenden
	 * Stilklassen, die in der Datei styles.css im Ordner tpl/standard/css/styles gespeichert sind:
	 *
	 * #rightsmenu                  						Rechtepanel
	 * #rightsmenu table.rightspanel						Struktur des Rechtepanel
	 * #rightsmenu tr.rightsrow  							Zeile im Rechtepanel
	 * #rightsmenu td.rightscell 							Zelle im Rechtepanel
	 * #rightsmenu td.rightsname 							Zelle mit dem Name eines Rechts
	 * #rightsmenu td.rightscellhead 						Zelle mit Spalten�berschriften im Rechtepanel
	 * #rightsmenu td.rightsnamehead 						Name der Spalten�berschriften
	 * #rightsmenu table.rightspanel input.cmsbutton 		Buttons am unteren Ende des Rechtepanels
	 * #rightsmenu table.rightspanel input 					Eingabe-Elemente im Rechtepanel
	 * #rightsmenu table.rightspanel select.groupselect 	Gruppen-Dropdown-Liste im Rechtepanel
	 *
	 * Das Array ist wie folgt aufgebaut:
	 * array("rights" => String mit allen Rechtewerten in Form von HTML-Hideen-Fields,
	 *       "call"   => String mit dem JavaScript-Link f�r den Aufruf des Rechte-Popups mittels popupmenu.js,
	 *       "scripts"=> String mit den HTML-Tags f�r das Einbinden der Javascript-Funktionen und Klassen)
	 *
	 * @param	string	$type 			Rechtetype der ben�tigt wird
	 * @param	string	$id				ID des Elements, dessen Rechteeintr�ge angezeigt werden sollen
	 * @param 	mixed	$config			Assoziatives Array mit Name-Werte-Paare f�r die Konfiguration der Popup-Ebene oder
	 *									String mit dem Namen des Formulars in dem die Anzeige stattfindet
	 *									Optional - Standardwert f�r Formularnamen: 'rights'
	 * @param	string	$view			Darstellung des Links zum Anzeigen des Popups, m�gliche Werte: 'text' oder 'img'
	 *									Optional - Standardwert: text
	 * @param	bool	$ignore_user	Flag um die Anzeigen von Benutzerrechte auszuschlie�en
	 *									Optional - Standard: false - keine Benutzerrechte einschlie�en
	 * @param 	bool	$all_users		In Zusammenhang mit $ignore_user legt dieser Wert fest, ob alle vorhandenen Einzelrechte
	 *									des Elements zur�ckgeliefert werden oder nur die Rechte des anfragenden Users
	 *									Optional - Standard: false - wenn, dann nur Rechte des anfragenden Users zur�ckliefern
	 * @param	string	@parent			ID des �bergeordneten Elementes, z.B. Kategorie einer Seite, Ordner einer Datei
	 *									Optional - Standard: '0' - �bergeordnete Rechte sind Area-Rechte
	 * @param	string 	$prefix 		Prefix f�r Formularvariablen, damit mehrere Formulare auf einer Seite dargestellt werden k�nnen
	 * 
	 * @return	array	Die Elemente des Arrays enthalten die HTML-Konstrukte zur Anzeige der Popup-Ebene. Sind keine
	 *                  konfigurierbaren Gruppen vorhanden wird das Array auf empty gesetzt.
	 *
	 * @global	$cfg_cms
	 * @global  $cms_lang
	 *
	 *
	 * @author	J�rgen Br�ndle
	 * @since	CMS 0.96.00 / Beta
	 * @version 0.4 / 20040502
	 *
	 * Change: 02.05.2004 - JB - Neu: Anzahl der konfigurierbaren Gruppen ermitteln
	**/
	function get_right_panel($type, $id, $config = '', $view = 'text', $ignore_user = false, $all_users = false, $parent = '0', $prefix = '') {
		global $cfg_cms, $cms_lang, $__CALLED_function_get_right_panel;

		// Anzahl der Gruppen ermitteln
		$this->_count_groups();

		// Wenn im System mindestens eine konfigurierbare Gruppe vorhanden ist, dann wird das Rechtepanel erstellt
		if ($this->_group_count > 0) {
			$parts = array();
			$skin_path = $cfg_cms['cms_html_path'] . 'tpl/' . $cfg_cms['skin'];
			// Erstelle Hidden_Fields - beinhalten die Rechte, die zum Element geh�ren
			$rights = $this->_get_rights_by_groups_user ($type, $id, $ignore_user, $all_users, $parent);
			$out    = '<input type="hidden" name="'.$prefix.'cms_gruppen"       value="' . join (',', $rights['groups']     ) . '" />';
			$out   .= '<input type="hidden" name="'.$prefix.'cms_gruppenrechte" value="' . join (',', $rights['grouprights']) . '" />';
			$out   .= '<input type="hidden" name="'.$prefix.'cms_gruppenids"    value="' . join (',', $rights['groupids']   ) . '" />';
			$out   .= '<input type="hidden" name="'.$prefix.'cms_gruppenrechtegeerbt"    value="' . join (',', $rights['grouprights_are_inherit']   ) . '" />';
			$out   .= '<input type="hidden" name="'.$prefix.'cms_gruppenrechteueberschreiben"    value="' . join (',', $rights['groupsoverwrite']   ) . '" />';

			$parts["rights"] = $out;
			// Erstelle notwendige JavaScript-Einbindungen und schreibe das Konfigurationsscript f�r die Popup-Ebene
			$out  = '<div class="rightsmenu" id="'.$prefix.'rightsmenu" name="'.$prefix.'rightsmenu" onmouseover="'.$prefix.'cms_rm.clearhideRP();'.$prefix.'cms_rm.highlightRP(event,\'on\')" ';
			$out .= 'onmouseout="'.$prefix.'cms_rm.highlightRP(event,\'off\');'.$prefix.'cms_rm.dynamichideRP(event)">';
			$out .= $this->_get_right_names_and_values($type, $rights, $countRights, $prefix);
			$out .= '</div>';
			if (! $__CALLED_function_get_right_panel) {
			$out .= '<script type="text/javascript" src="' . $skin_path . '/js/popupmenu.js"></script>';
			$out .= '<script type="text/javascript" src="' . $skin_path . '/js/userrights.js"></script>';
			}
			$out .= '<script type="text/javascript">';
			$out .= "\n". 'var '.$prefix.'cms_rm  = new userrights("rights", "'.$prefix.'rightsmenu");';
			if (!empty($config)) {
				if (is_array($config)) {
					foreach($config AS $key => $value) {
						$out .= "\n". $prefix."cms_rm.$key='$value';";
					}
				} else {
					$out .= "\n	".$prefix."cms_rm.formname='$config';";
				}
			}
			$out .= "\n	".$prefix."cms_rm.groupidselement     = '".$prefix."cms_gruppenids';";
			$out .= "\n	".$prefix."cms_rm.groupselement       = '".$prefix."cms_gruppen';";
			$out .= "\n	".$prefix."cms_rm.groupsrightselement = '".$prefix."cms_gruppenrechte';";
			$out .= "\n	".$prefix."cms_rm.groupsinheritelement = '".$prefix."cms_gruppenrechtegeerbt';";
			$out .= "\n	".$prefix."cms_rm.groupsoverwriteelement = '".$prefix."cms_gruppenrechteueberschreiben';";
			
			$out .= "\n	".$prefix."cms_rm.radioname       = '".$prefix."uright';";
			$out .= "\n	".$prefix."cms_rm.selectname = '".$prefix."rmgruppe';";
			$out .= "\n	".$prefix."cms_rm.checkinherit = '".$prefix."rmerben';";			
			$out .= "\n	".$prefix."cms_rm.checkueberschreiben = '".$prefix."rmueberschreiben';";		


			$out .= "\n	".$prefix."cms_rm.adjustposition = true;\n";
			$out .= "\n	".$prefix."cms_rm.createRightPanel();\n";
			$out .= "</script>\n";
			$parts["scripts"] = $out;
			// Erstelle Aufruf Link
			$out  = "\n<a href=\"javascript:void(0)\"";
			$out .= ' onclick="if (!'.$prefix.'cms_rm.panelvisible) '.$prefix.'cms_rm.showRightPanel(event, ' . $countRights . '); return false;"> ';
			switch ($view) {
				case 'img':
					$out .="\n";
					$out .= '<img src="' . $skin_path . '/img/' . $this->security_button . '" ';
					$out .= 'width="' . $this->security_width . '" height="' . $this->security_height . '" ';
					$out .= 'alt="' . $cms_lang['title_rp_popup'] . '" title="' . $cms_lang['title_rp_popup'] . '" />';
					break;
				case 'text':
					$out .= $cms_lang['title_rp_popup'];
					break;	
				default:
					$out .= $view;
					break;
			}
			$out .= '</a>';
			$parts["call"] = $out;
			$__CALLED_function_get_right_panel = true;
			return $parts;
		} else {
			return '';
		}
	}

	/**
	 * Setzt Gruppenrechte f�r einen Rechtetyp und eine Element
	 *
	 * Rechte werden nur gesetzt, wenn sich zu den �bergeordnetne Rechten Abweichungen ergeben. Bei Abweichungen wird der
	 * komplette Rechtesatz des Elementes geschrieben, eine Vererbung einzelner Rechte ist nicht vorgesehen.
	 * F�r Vererbung werden die Rechte der Rechtehierarchie des Vater-Element gepr�ft.
	 *
	 * @param	string	$type 			Rechtetype der bearbeitet wird
	 * @param	string	$id				ID des Elements, dessen Rechteeintr�ge ver�ndert werden sollen
	 * @param	string	$gruppenids		Gruppen-IDs als komma-separierte Liste
	 * @param 	string	$gruppenrights	Rechte der Gruppen als komma-separierte Liste
	 * @param   string  $gruppengeerbt  Gibt an ob Rechte geerbt werden als komma-separierte Liste
	 * @param	string	$idlang			ID der Sprache
	 *									Optional - Standard: '' - aktuelle $idlang-Wert verwenden
	 * @param	int		$bitmask		Bitmaske f�r die Festlegung welche Rechte bei der �berpr�fung der Vererbung ber�cksichtigt
	 *									werden
	 *									Optional - Standard: 0xFFFFFFFF - alle �bergeordneten Rechte ber�cksichtigen
	 * @param	string	$parent_id		ID des �bergeordneten Elements
	 *									Optional - Standard: '0'
	 * @param	int		$second_bitmask	Bitmaske, welche eingesetzt wird, wenn die Hierachie der Vererbung �ber eine 2 Ebenen geht.
	 *                                  Optional - Standard: 0xFFFFFFFF
	 *
	 * @global	$lang
	 *
	 * @author	J�rgen Br�ndle, Bj�rn Brockmann
	 * @since	CMS 0.96.00 / Beta
	 * @version 0.2 / 20040223
	**/
	function set_group_rights( $type, $id, $gruppenids, $gruppenrights, $gruppengeerbt, $gruppenueberschreiben, $idlang = '', $bitmask = 0xFFFFFFFF, $parent_id = '0', $second_bitmask = 0xFFFFFFFF) {
		global $lang;
		
		//echo '$type, $id, $gruppenids, $gruppenrights, $gruppengeerbt, $gruppenueberschreiben, $idlang = "", $bitmask = 0xFFFFFFFF, $parent_id = "0", $second_bitmask = 0xFFFFFFFF<br>';
		//echo "$type, $id, x$gruppenids x, x$gruppenrights x, x $gruppengeerbt x, x $gruppenueberschreiben x, $idlang = '', $bitmask = 0xFFFFFFFF, $parent_id = '0', $second_bitmask = 0xFFFFFFFF";
		//exit;

		// Requirements pr�fen
		if (empty($type) || empty($id) || empty($gruppenids) || strlen($gruppenrights) < 1) return false;	// missing parameter

		// Sprache festlegen ... default: Sprache in der gearbeitet wird
		$language = empty($idlang) ? $lang: $idlang;

		// Hole die Gruppen und Gruppenrechte in Arrays um diese dann in einer Schleife zu bearbeiten
		$arrGroups = explode(",", $gruppenids);
		$arrRights = explode(",", $gruppenrights);
		$arrGeerbt = explode(",", $gruppengeerbt);
		$arrUeberschreiben = explode(",", $gruppenueberschreiben);

		// Hole jede Gruppe und jedes dazugeh�rende Recht und pr�fe ob es gegen�ber dem �bergeordneten Element eine Differenz
		// gibt und trage das Recht in diesem Falle ein
		for($i = 0; $i < count($arrGroups); $i++) {
			$idgroup    = (int) $arrGroups[$i];
			$perm_value = (int) $arrRights[$i];
			$is_inherit = (bool) $arrGeerbt[$i];
			$delete_rec = (bool) $arrUeberschreiben[$i];
			
			// Addon Objekt ausf�hren, falls recursive gel�scht werden soll
			if ($delete_rec) {
				if (! is_object($this->perm_addon[$type])) {
					$addon =& sf_factoryGetObjectCache('ADMINISTRATION', 'PermAddonFactory');
					$this->perm_addon[$type] =& $addon->getAddonObject($type);
				}
				$this->perm_addon[$type]->deleteChilds($type, $language, $idgroup, $id);
			}

			// L�sche zun�chst ggf. vorhandene alte Wert ... so dass das zur�cksetzen auf den aktuellen Area-Wert funktioniert
			$this->delete_perms($id, $type, $idgroup, 0, $language, false);
			
			// Wenn Recht geerbt werden soll, abbrechen. Keine Aktion notwendig. Andernfalls Recht speichern
			if ($is_inherit) {
				continue;
			} else {
				$this->new_perm($idgroup, $type, $id, $perm_value, $language);
			}
		}
	}

	/**
	 * Setzt Eigent�merrechte f�r einen Rechtetyp und eine Menge von Elementen
	 *
	 * Eigent�merrechte werden durch einen negativen in der Spalte idgroup gekennzeichnet.
	 * Die Eigent�merrechte sind f�r sp�tere Erweiterungen ausgelegt und in Sefrengo Version 1.0 und vorher
	 * werden diese Rechte nicht gesetzt oder ausgelesen.
	 *
	 * @param	string	$type 			Rechtetype der bearbeitet wird
	 * @param	mixed	$idarray		ID des Elements (als int oder string) oder IDs der Elemente die Eigent�mer-Rechte
	 *									bekommen
	 * @param	string	$perm_value		Bitmaske der Eigent�mer-Rechte
	 * @param 	string	$userid			ID des User, der Eigent�mer ist
	 *									Optional - Standard: Aktuell eingeloggter User
	 * @param	string	$idlang			ID der Sprache
	 *									Optional - Standard: '' - aktuelle $idlang-Wert verwenden
	 *
	 * @global	$lang
	 *
	 * @author	J�rgen Br�ndle
	 * @since	CMS 0.96.00 / Beta
	 * @version 0.1
	**/
	function set_owner_rights( $type, $idarray, $perm_value, $userid = '', $idlang = '') {
		// Auskommentiert bis eigent�mer-rechte �berall implementiert und bearbeitbar sind
		return true;

		global $lang;

		// Requirements pr�fen
		if (empty($type) || empty($idarray) || empty($perm_value)) return false;	// missing or wrong parameter

		// User ermitteln
		// Keine User oder Nobody ausschliessen und mit Fehler abschliessen
		$user = $userid;
		if (empty($user)) {
			$user = $this->user_id;
			if (empty($user) || $user == 'nobody') return false;
		}
		// Systemadministratoren ... haben alle Rechte
		if (abs($user) == 1) return true;
		// Sprache festlegen ... default: Sprache in der gearbeitet wird
		$language = empty($idlang) ? $lang: $idlang;

		// Rechte eintragen ...
		if (is_string($idarray) || is_int($idarray)) {
			// Einzelnes Objekt
			// GGf. bestehende Rechte des User l�schen und danach neuen Perm-Eintrag erzeugen
			if ($this->delete_perms((string)$idarray, $type, -$user, 0, $language, false)) {
				return ($this->new_perm(-$user, $type, (string)$idarray, $perm_value, $language));
			}
			return false;
		} elseif (is_array($idarray)) {
			$_success = true;
			// Mehrere Objekte
			foreach($idarray as $id) {
				// Durch die Liste der Objekte iterieren
				// GGf. bestehende Rechte des User l�schen und danach neuen Perm-Eintrag erzeugen
				if ($this->delete_perms($id, $type, -$user, 0, $language, false)) {
					$_success =& $this->new_perm(-$user, $type, $id, $perm_value, $language);
				} else {
					$_success = false;
				}
				// Im Fehlerfall das Setzen der Rechte abbrechen
				if (!$_success) break;
			}
			return $_success;
		}
	}


	/**
	 * Zentrale Pr�froutine f�r die Rechte eines Users an einem Objekt oder Bereich
	 *
	 * Liefert als Ergebnis zur�ck, ob ein User ein nachgefragtes Recht hat oder nicht. Hierbei k�nnen verschiedene Abfragen
	 * durchgef�hrt werden, die sich anhand der Parameter�bergaben unterscheiden:
	 * 1. Zugriffsrechte auf einen Bereich - $type: 'cms_access' oder nicht angegeben
	 * 		$perm_or_area	string	Name des Areas
	 *		$type			string	'cms_access', optional
	 *		$id, $parent_id	werden ignoriert
	 * 2. Rechte auf das Element sind f�r diesen User vorhanden
	 *		$perm_or_area	string	'0'
	 *		$type			string	Name des Rechtetyps - alle ausser 'cms_access' sind m�glich
	 *		$id				string	ID des Objektes
	 *		$parent_id wird ignoriert
	 * 3. Einzelnes Recht auf das Objekt f�r den User wird gepr�ft
	 *		$perm_or_area	int		Nummer des Rechtes
	 *		$type			string	Name des Rechtetyps - alle ausser 'cms_access' sind m�glich
	 *		$id				string	ID des Objektes
	 *		$parent_id 		int		ID des �bergeordneten Objektes
	 *
	 *
	 *
	 * @param	mixed	$perm_or_area	Name einer Rechtegruppe oder ID eines Objektes
	 * @param	string	$type			Name der Rechtegruppe
	 * @param	string	$id				ID eines Objektes
	 * @param	int		$parent_id		ID des �bergeordneten Objektes
	 *
	 * @return bol true, wenn dem User das Recht zugewiesen wurde, ansonsten false
	 *
	 */
	function have_perm($perm_or_area, $type='cms_access', $id = '0', $parent_id = 0) {
		// Admin immer erlauben
		if ($this->_is_admin) return true;

		$_mask     = 0x01;
		// Abfragen der Form:
		//
		// perm->have_perm("xxx")
		// Werden immer als Abfragen einer Zugangsberechtigung gewertet:
		// und auf die Rechte Gruppe "access" abgebildet.
		//
		// Alle anderen Formen beziehen sich auf den jeweiligen Rechte-Typ.
		//
		// Lade Perms des Types f�r die Gruppen des Users und der aktuellen Sprache
		// und die Perms des Parents
		$this->_retrieve_perms((string) $type);


		// Rechte abchecken
		if ($type == 'cms_access') {
			// Pr�fungstyp 1:
			// Access-Rechte pr�fen wird nicht auf die Bitmask bezogen, es wird lediglich gepr�ft,
			// ob es im einen Rechteeintrag im Block "access" gibt, dessen zweiter Key $perm_or_area
			// entspricht und den Wert "1" besitzt.
			// jb:
			// Ersetze "area_scancontrol" mit "area_upl" wegen "get_first_allowed_area"-Problematik
			// In einer n�chsten Version sollte es m�glich sein, f�r Areas entsprechende Alias-Namen
			// festzulegen, damit die Funktion "get_first_allowed_area" nicht zuviel querschiesst.
			if ($perm_or_area == 'area_scancontrol' || $perm_or_area == 'area_scan') {
				$test_area = 'area_upl';
			} else {
				$test_area = $perm_or_area;
			}
			return ($this->perms[(string)$type][(string)$test_area] == '1');
		} else {
			// Pr�fungstyp 2:
			// Pr�ft ob f�r den User �berhaupt Rechte f�r ein Objekt zugeteilt sind.
			if ($perm_or_area == '0') {
				return ($this->perms[(string)$type][(string)$id] != '0');
			}
			// Pr�fungstyp 3: Perm-Pr�fung
			// $perm_or_area wird als Nummer des zutestenden Bit interpretiert
			// $type sagt welcher Bereich
			// $id ist die ID des Objekts das gepr�ft wird und nach Integer gecastet!
			// Der Perm-Wert f�r $type, $id wird auf das gew�nschte Bit getestet, liefert
			// der Test einen Wert ungleich 0, ist das Bit gesetzt und somit das Recht erteilt.
			$_mask <<= (--$perm_or_area);
			// Pr�fe ob es das Recht schon am Objekt selbst gibt, wenn nicht pr�fe ob die �bergeordneten Rechte entsprechend
			// f�r den User gesetzt sind.
			
			// Pr�fe ersten Block
			// Rechte k�nnen sein: slave, master oder area
			$original_id = $id;
			do {
				if (isset($this->perms[(string)$type][(string)$id])) {
					// Es existiert ein Eintrag f�r das Element
					if ($original_id != $id) {
						// Cache value
						$this->perms[(string)$type][(string)$original_id] = $this->perms[(string)$type][(string)$id];
					}
					return (((int)$this->perms[(string)$type][(string)$id] & $_mask) == $_mask);
				}
			} while ($id = $this->perm_addon[$type]->getParent($id));
			
			// Es existiert kein Eintrag f�r das Element, teste �bergeordnete Perm-Eintr�ge.
			// Hole �bergeordnete Rechtetypen aus der Parent-Liste
			if ($parent_id >= 0) {
				$child_type = $type;
				// $this->perm_addon[$type]
				//echo '<br>';
				//echo ":$parent_id <br>";
				
				// Pr�fe zweiten Block
				// Rechte k�nnen sein: master oder area
				if (isset($this->parent_rights[$child_type])) {
					
					// �bergeordnete rechtegruppe ermitteln
					$parent_type = (string) $this->parent_rights[$child_type];
					if (!isset($this->parent_rights[$parent_type])) $parent_id = '0';
					
					// Rechte ggf. nachladen
					$this->_retrieve_perms((string) $parent_type);
					
					$original_parent_id = $parent_id;
					// Rechte pr�fen
					do {
						//echo $this->perm_addon[$parent_type]->getParent($parent_id);
						if (isset($this->perms[$parent_type][(string)$parent_id])) {
							if ($original_parent_id != $parent_id) {
								//cache value
								$this->perms[(string)$parent_type][(string)$original_parent_id] = $this->perms[(string)$parent_type][(string)$parent_id];
							}
							return (((int)$this->perms[$parent_type][(string)$parent_id] & $_mask) == $_mask);
						}
					} while ($parent_id = $this->perm_addon[$parent_type]->getParent($parent_id));
					
					// Pr�fe dritten Block - es kann sich nur noch um ein area recht handeln
					// Weitere Gruppe nach oben gehen
					$child_type = $parent_type;
					if (isset($this->parent_rights[$child_type])) {
						
						// �bergeordnete rechtegruppe ermitteln
						$parent_type = (string) $this->parent_rights[$child_type];
						if (!isset($this->parent_rights[$parent_type])) $parent_id = '0';
						
						// Rechte ggf. nachladen
						$this->_retrieve_perms((string) $parent_type);
						
						// Rechte pr�fen
						if (isset($this->perms[$parent_type][(string)$parent_id])) {
							return (((int)$this->perms[$parent_type][(string)$parent_id] & $_mask) == $_mask);
						}

					}
				}
			}
		}
		return false;
	}

	/**
	 * Neuen Perm-Eintrag in der Datenbank eintragen
	 *
	 * @param	int		$group			Usergruppe
	 * @param	string	$type			Rechtegruppe
	 * @param	int		$id				ID des Objektes, optional - Standard: 0, kein Objektrecht
	 * @param	mixed	$permission		Perm-Werte, optional - Standard: 0, keine Rechte
	 *									Ist $permission ein Array aus Integern, so wird die Summe der Array-Werte
	 *									eingetragen. Ist $permission ein Integer, wird der Wert eingetragen
	 * @param	int		$lang			ID der Sprache, optional - Standard: 0
	 *
	 * @return	bool	Wenn der Eintrag erfolgreich war true, ansonten false
	 *
	 * @author	J�rgen Br�ndle
	 * @since	CMS 0.96.00 / Beta
	 * @version 0.1
	 */
	function new_perm( $group, $type, $id = 0, $permission = 0, $lang = 0) {
		if (empty($group) || empty($type)) return false;
		if (abs($group) == 1) return true;	// keine Eintr�ge f�r User ohne Gruppenzugeh�rigkeit
		// Ermittle zu setzenden Wert f�r perm aus der �bergabe $permission
		//echo '<br /><br /><br /><br />';
		//echo $permission .'<br />';
		
		
		$perm = $this->_get_mask($permission);
		//echo $perm.'<br />';
		// SQL erstellen und ...
		$sql  = sprintf($this->insert_perm, $this->cms_db['perms'], $group, $lang, $type, $perm, $id );
		// ... ausf�hren ...
		$this->db->query($sql);
		// ... und Ergebnis zur�ckliefern
		return ($this->db->Errno == 0);
	}

	/**
	 * Aktualisiert einen Perm-Eintrag
	 *
	 * @param int		$idperm			ID des Perm-Eintrags
	 * @param int		$group			Usergruppe
	 * @param string	$type			Rechtetyp
	 * @param int		$id				Objekt-ID
	 * @param mixed		$permission		Werte f�r perm
	 *									Ist $permission ein Array aus Integern, so wird die Summe der Array-Werte
	 *									eingetragen. Ist $permission ein Integer, wird der Wert eingetragen
	 * @param int		$lang			Sprach-ID
	 *
	 * @author	J�rgen Br�ndle
	 * @since	CMS 0.96.00 / Beta
	 * @version 0.1
	 */
	 function update_perm_by_id($idperm, $group, $type, $id, $permission, $lang) {
		// Pr�fe ob idperm, $group und $type gesetzt sind
		if (empty($idperm) || empty($group) || empty($type)) return false;
		// Ermittle zu setzenden Wert f�r perm aus der �bergabe $permission
		$perm = $this->_get_mask($permission);
		// SQL erstellen und ...
		$sql  = 'UPDATE ' . $this->cms_db['perms'] . ' ';
		$sql .= 'SET ';
		$sql .= '  idgroup = '  . $group .  ', ';
		$sql .= '  idlang  = '  . $lang  .  ', ';
		$sql .= "  type    = '" . $type  . "', ";
		$sql .= "  id      = '" . $id    . "', ";
		$sql .= '  perm    = '  . $perm  .  '  ';
		$sql .= 'WHERE';
		$sql .= '  idperm  = ' . $idperm;
		// ... aktualisieren
		$this->db->query($sql);
		return ($this->db->Errno == 0);
	 }

	/**
	 * L�scht Userperms
	 *
	 * Abh�ngig von der Parameterkombination sind folgende L�schungen m�glich
	 *
	 * $idperm gesetzt:
	 * 1.	$idperm	> 0      :	L�schen des angegebenen Rechtes
	 * 2.   $idperm ist Array:  L�schen der angegebenen Rechte
	 *
	 * $idperm nicht gesetzt, daf�r $type und $id gesetzt
	 * 3.	$id, $type		 :	Gesetzte Einzelwerte -> L�scht alle Rechte die $id und $type umfassen
	 *
	 *		3a. $ignore_lang :	true  -> l�sche alle Eintr�ge unabh�ngig von der Sprache
	 *							false -> ber�cksichtige die Sprache beim L�schen
	 *							a. $lang Einzelwert:	Nur die Eintr�ge einer Sprache l�schen
	 *							b. $lang Array:			Alle Eintr�ge der Sprachen l�schen
	 *
	 *		3b. $group		 :  gesetzt: ber�cksichtige die Gruppenangabe beim L�schen
	 *							a. $group Einzelwert:	Nur die Eintr�ge einer Gruppe l�schen
	 *							b. $group Array:		Alle Eintr�ge der Gruppen l�schen
	 *
	 * 4. Ber�cksichtige entsprechend 3a. und 3b. Sprache und Usergruppen beim L�schen
	 *
	 *
	 *
	 * @param	int		$id		ID des Objektes, optional - Standard: 0, kein Objektrecht
	 * @param	string	$type	Rechtegruppe
	 * @param	int		$group	Usergruppe, optional - Standard: 0
	 * @param	mixed	$idperm	ID des Rechteeintrags, optional - Standard: 0
	 *							Ist $idperm ein Array aus Integern, so werden alle angegebenen Rechte gel�scht.
	 *							Ist $idperm ein Integer, wird das angegebene Recht gel�scht.
	 * @param	int		$lang	Sprache-ID, optional - Standard: 0
	 * @param	boolean	$ignore_lang	Sprache beim L�schen nicht ber�cksichtigen (true), optional - Standard: false
	 *
	 * @return	bool 	true, wenn Rechte gel�scht wurden, ansonsten false.
	 *
	 * @author	J�rgen Br�ndle
	 * @since	CMS 0.96.00 / Beta
	 * @version 0.1
	 */
	 function delete_perms($id = 0, $type = '', $group = 0, $idperm = 0, $lang = 0, $ignore_lang = false) {
		if (empty($idperm) && (empty($id) || empty($type))) return false;	// required parameter missing
		// create sql ...
		$sql  = 'DELETE FROM ' . $this->cms_db['perms'] . ' ';
		$sql .= 'WHERE ';
		if (!empty($idperm)) {
			// delete by idperm
			$sql .= ' idperm '  . $this->_check_array( $idperm );
		} else {
			// delete by id, type and may be group and/or language
			$sql .= ' id '      . $this->_check_array( $id  , true );
			$sql .= 'AND type ' . $this->_check_array( $type, true );
			if (!$ignore_lang ) $sql .= 'AND idlang  ' . $this->_check_array( $lang  );
			if (!empty($group)) $sql .= 'AND idgroup ' . $this->_check_array( $group );
		}
		// ... execute
		$this->db->query($sql);
		return ($this->db->Errno == 0);
	 }

	/**
	 * L�scht Perm-Eintr�ge einer oder mehrerer Gruppen
	 *
	 * Abh�ngig von der Parameterkombination sind folgende L�schungen m�glich
	 *
	 * $group gesetzt:
	 * 1.	$group	> 0     :  L�schen der Rechte der Gruppe
	 * 2.   $group ist Array:  L�schen der Rechte der Gruppen
	 * ist $idlang gesetzt, wird die Sprache beim L�schen ber�cksichtigt:
	 * 1.	$lang	> 0     :  L�schen der Rechte der Sprache
	 * 2.   $lang ist Array :  L�schen der Rechte der Sprachen
	 *
	 *
	 * @return	bool 	true, wenn Rechte gel�scht wurden, ansonsten false.
	 *
	 * @author	J�rgen Br�ndle
	 * @since	CMS 0.96.00 / Beta
	 * @version 0.1
	 */
	 function delete_perms_by_group($group = '0', $lang = '-1') {
		if (empty($group) && $lang == '-1') return false;	// required parameter missing
		// create sql ...
		$sql  = 'DELETE FROM ' . $this->cms_db['perms'] . ' ';
		$sql .= 'WHERE ';
		if (!empty($group)) $sql .= ' idgroup ' . $this->_check_array( $group );
		if ($lang != '-1') {
			if (!empty($group)) $sql .= ' AND ';
			$sql .= ' idlang ' . $this->_check_array( $lang  );
		}
		// ... execute
		$this->db->query($sql);
		return ($this->db->Errno == 0);
	 }

	/**
	 * Testet Userperms auf bestimmte Eigenschaften
	 * Drei verschiedene Test sind m�glich:
	 * $test_type
	 * 		0 		existenz pr�fen unter ausschluss des wertes 0
	 * 				($this->perms[$type][$id] && $perm->perms[$type][$id] != '0')
	 * 		8 		existenz pr�fen unter ausschluss des wertes 0
	 * 				($this->perms[$type][$id] && $perm->perms[$type][$id] != '0')
	 * 		9 		existenz pr�fen unter ausschluss des wertes 0
	 * 				($this->perms[$type][$id] && $perm->perms[$type][$id] != '0')
	 *
	 * WICHTIG: test_perm kann nicht verwendet werden, wenn gepr�ft werden soll, ob ein User in einer
	 * bestimmten Gruppe ein entsprechendes Recht besitzt. test_perm kann nur herausfinden, ob ein User
	 * durch irgendeine seiner Gruppenzuordnungen ein entsprechendes Recht hat.
	 * Werden bestimmte Gruppenrechte ben�tigt sollte die Funktion get_userperm() oder find_perm() genutzt werden.
	 *
	 * Gibt bei unbekanntem Test oder bei Fehlern false zur�ck.
	 *
	 * @param int		$type			Rechtegruppe
	 * @param int		$id				Objekt-ID
	 * @param int		$test_type		durchzuf�hrender Test, optional - Standard: 0
	 * @param int		$value			ggf. notwendiger Vergleichswert oder Rechtemaske, optional - Standard: 0
	 * @param boolean	$ignore_admin	True wenn Administratoren nicht getestet werden, sonst false. Optional - Standard: false
	 *
	 *
	 * @return	bool 	true, wenn dem User das Rechte in irgendeiner Gruppe erteilt wurde, ansonsten false.
	 *
	 * @author	J�rgen Br�ndle
	 * @since	CMS 0.96.00 / Beta
	 * @version 0.1
	 */
	 function test_perm($type, $id, $test_type = 0, $value = 0, $ignore_admin = false) {
	 	// return true if admin-account
		if (!$ignore_admin && $this->is_admin()) return true;
		// test $type is set
		if (empty($type)) return false;
		// lade ggf. die Werte des Rechtetyps
		$this->_retrieve_perms((string)$type);
		// pr�fe ob idperm gesetzt oder $group und $type gesetzt sind
		switch ((int)$test_type) {
			case 0: // existenz pr�fen unter ausschluss des wertes 0
				if ($this->perms[$type][$id] && $this->perms[$type][$id] != '0') return true;
				return ((int)$this->perms[$this->parent_rights[$type]]['0'] > 0);
			case 8: // Wertepr�fung
				return (!empty($this->perms[$type][$id]) && $this->perms[$type][$id] == $value);
			case 9: // Bitmask-Pr�fung 1 - pr�ft ob bestimmte Bitmask gesetzt ist
				return (!empty($this->perms[$type][$id]) && ((int)$this->perms[$type][$id] & (int)$value) == (int)$value);
			default:
				return false;
		}
		return false;
	 }

	/**
	 * Testet Userperms auf bestimmte Eigenschaften und liefert " checked " oder einen leeren String zur�ck
	 * Benutzt test_perm f�r die Tests, Parameter und Testtypen siehe dort.
	 *
	 * @param int		$type			Rechtegruppe
	 * @param int		$id				Objekt-ID
	 * @param int		$test_type		durchzuf�hrender Test, optional - Standard: 0
	 * @param int		$value			ggf. notwendiger Vergleichswert oder Rechtemaske, optional - Standard: 0
	 *
	 * @return string 	' checked ' wenn Recht gesetzt ist, ansonsten leerer Strig
	 *
	 * @author	J�rgen Br�ndle
	 * @since	CMS 0.96.00 / Beta
	 * @version 0.1
	 */
	 function get_checkbox_status($type, $id, $test_type = 0, $value = 0) {
	 	return (($this->test_perm($type, $id, $test_type, $value, true)) ? ' checked ': '');
	 }

	/**
	 * Ermittelt die Rechte einer betimmten Rechtegruppe f�r ein Objekt f�r eine oder mehrere Usergruppen
	 * Benutzt get_perm, Details zur Ermittlung der Rechte siehe dort.
	 *
	 * F�r die Ermittlung der Rechte wird die aktuelle Anzeigesprache verwendet
	 *
	 * @param	string	$type	Rechtegruppe
	 * @param	string	$id		Objekt-ID
	 * @param	int		$group	Usergruppe
	 *
	 * @return	int		Bitmaske der Rechte
	 *
	 * @global	$lang
	 *
	 * @author	J�rgen Br�ndle
	 * @since	CMS 0.96.00 / Beta
	 * @version 0.1
	 */
	 function find_perm($type = '', $id = '0', $group = 0) {
	 	global $lang;

		if (empty($id) || empty($group) || empty($type)) return false;
	 	return $this->get_perm($group, $type, $id, $lang);
	 }

	/**
	 * Ermittelt die Besitzerrechte f�r ein Element in der gew�nschten Sprache
	 * Wird keine Sprache angegeben, wird die aktuelle Anzeigesprache verwendet.
	 *
	 * Benutzt get_perm, Details zur Ermittlung der Rechte siehe dort.
	 *
	 * @param	string	$type		Rechtegruppe
	 * @param	string	$id			Objekt-ID
	 * @param	int		$idlang		Sprach-ID
	 *
	 * @return	int		Bitmaske der Rechte
	 *
	 * @author	J�rgen Br�ndle
	 * @since	CMS 0.96.00 / Beta
	 * @version 0.1
	*/
	function get_userperm($type = '', $id = '0', $idlang = -1) {
		global $lang;

		$r_lang = ($idlang == -1) ? $lang: $idlang;
		return get_perm(-$this->user_id, $type, $id, $r_lang);
	}

	/**
	 * Ermittelt die Perm-Eintr�ge eines Objekts
	 * Benutzt die Methode get_perm2, Details zur Funktionsweise siehe dort.
	 *
	 * @param	mixed	$group				Array oder Integer mit Gruppen-IDs
	 * @param	string	$type				Rechtegruppe
	 * @param	int		$id					Objekt-ID, optional - Standard: 0 - Area-Rechte
	 * @param	int		$lang				Sprach-ID, optional - Standard: 0 - keine sprachspezifischen Rechte
	 * @param	bool	$apply_owner_rights	Besitzerrechte ber�cksichtigen, optional - Standard: true - Besitzerrechte
	 *										ber�cksichtigen
	 *
	 * @return	int		Bitmaske der ermittelten Rechte
	 *
	 * @author	J�rgen Br�ndle
	 * @since	CMS 0.96.00 / Beta
	 * @version 0.1
	 */
	 function get_perm($group = 0, $type = '', $id = '0', $lang = 0, $apply_owner_rights = true) {
	 	// missing requirements -> no permissions
		if (empty($group) || empty($type)) return 0;
		// get result using get_perm2(...)
		$permission = 0;
		$this->get_perm2($permission, $group, $type, $id, $lang, $apply_owner_rights);
		return $permission;
	 }

	/**
	 * Ermittelt alle Perms eines Objekts in einer bestimmten Rechtegruppe f�r eine oder mehrere Usergruppen
	 * als Summe aller Rechte dieses Objekts
	 *
	 * Wenn als Gruppenangabe eine Liste von Gruppen �bergeben wird, dann werden alle Rechte der einzelnen Gruppen als
	 * Kombinationswert aller einzelnen Rechte zur�ckgeliefert, andernfalls werden nur die Rechte einer einzelnen Gruppe
	 * als Ergebnis zur�ckgeliefert.
	 * Negative Gruppen-IDs kennzeichnen Eigent�merrechte, diese �berstimmen die allgemeinen Rechte in der Form, das die
	 * Kombination der einzelnen Rechte zun�chst um die Besitzerrechte erweitert werden und dann anschlie�end auf diese Rechte
	 * eingeschr�nkt zu werden.
	 *
	 * @param	int		$perm_return		Perm-Wert der ermittelt wurde als R�ckgabe �ber eine Referenz-Variable
	 * @param	mixed	$group				Array oder Integer mit Gruppen-IDs
	 * @param	string	$type				Rechtegruppe
	 * @param	int		$id					Objekt-ID, optional - Standard: 0 - Area-Rechte
	 * @param	int		$lang				Sprach-ID, optional - Standard: 0 - keine sprachspezifischen Rechte
	 * @param	bool	$apply_owner_rights	Besitzerrechte mit ber�cksichtigen, optional - Standard: true - Besitzerrechte
	 *										ber�cksichtigen
 	 *
	 * @return	bool	true, wenn Perm-Eintr�ge gefunden wurden, sonst false
	 *
	 * @author	J�rgen Br�ndle
	 * @since	CMS 0.96.00 / BETA
	 * @version 0.1
	**/
	function get_perm2(&$perm_return, $group = 0, $type = '', $id = '0', $lang = 0, $apply_owner_rights = true) {
	 	// missing requirements -> no permissions
		if (empty($group) || empty($type)) return false;
		// set flag
		$perm_found = false;
		// create sql ...
		$sql  = 'SELECT DISTINCT perm, idgroup ';
		$sql .= 'FROM ' . $this->cms_db['perms'] . ' ';
		$sql .= 'WHERE idgroup ' . $this->_check_array( $group, false);
		$sql .= "AND     id = '" . $id   . "' ";
		$sql .= "AND   type = '" . $type . "' ";
		$sql .= 'AND idlang = '  . $lang .  ' ';
		$sql .= 'ORDER BY ';
		$sql .= ' idgroup DESC';
		// ... execute and calculate permission value
		$this->db->query($sql);
		$permission = 0;
		while($this->db->next_record()) {
			if ($this->db->f('idgroup') < 0 && $apply_owner_rights) {// ownerrights
				$permission |= $this->db->f('perm');
				$permission =& $this->db->f('perm');
			} elseif ($this->db->f('idgroup') > 0) {			     // group rights
				$permission |= $this->db->f('perm');
			}
			$perm_found = true;
		}
		// set return value and leave
		$perm_return = $permission;
		return $perm_found;
	 }
	/**
	 * Gibt einen array mit allen Ids der Gruppen zur�ck, in der der der aktuelle Benutzer Mitglied ist
     *
	 * @return	array	alle Gruppenids in der der Benutzer Mitglied ist
	 *
	 * @author	Bj�rn Brockmann
	 * @since	CMS 0.99.00 / RC1
	 */
	 function get_group(){
	 	return $this->idgroup;
	 }


	/**
	 * Kopiert Perm-Eintr�ge innerhalb einer Rechtegruppen
	 * Benutzt xcopy_perm mit gleicher Rechtegruppe f�r Quelle und Ziel-Objekt, Details siehe dort.
	 *
	 * @param	int		$idorigin		Objekt-ID des Quell-Objekts
	 * @param	string	$type			Rechtegruppe
	 * @param	int		$idtarget		Objekt-ID des Ziel-Objekts
	 * @param	int		$group			Usergruppe deren Perm-Eintr�ge kopiert werden sollen, optional - Standard: 0-alle Gruppen
	 * @param	int		$lang			Sprache die beim Kopieren ber�cksichtigt werden soll, optional - Standard: 0-keine Sprache
	 * @param	bool	$ignore_lang	true, wenn Perm-Eintr�ge aller Sprachen kopiert werden sollen, ansonsten false.
	 *									optional - Standard: true - perm-Eintr�ge aller Sprachen kopieren
	 *
	 * @return	bool	true, wenn Kopieraktion erfolgreich war, false ansonsten
	 *
	 * @author	J�rgen Br�ndle
	 * @since	CMS 0.96.00 / BETA
	 * @version 0.1
	 */
	 function copy_perm($idorigin, $type, $idtarget, $group = 0, $lang = 0, $ignore_lang = true) {
		// pr�fe ob idperm gesetzt oder $group und $type gesetzt sind
		if (empty($idorigin) || empty($type) || empty($idtarget)) return false;

		return $this->xcopy_perm($idorigin, $type, $idtarget, $type, 0xFFFFFFFF, $group, $lang, $ignore_lang);
	 }

	/**
	 * Kopiert Perm-Eintr�ge zwischen verschiedenen Rechtegruppen
	 * Wird verwendet, um Rechte einer Rechtegruppe auf eine darunterliegende Rechtegruppe zu kopieren, z.B. folder -> file.
	 *
	 * @param	int		$idorigin		Objekt-ID des Quell-Objekts
	 * @param	string	$typeorigin		Rechtegruppe des Quell-Objekts
	 * @param	int		$idtarget		Objekt-ID des Ziel-Objekts
	 * @param	string	$typetarget		Rechtegruppe des Ziel-Objekts
	 * @param	int		$keepbitmask	Bitmaske der zu kopierenden Rechte
	 * @param	int		$group			Usergruppe deren Perm-Eintr�ge kopiert werden sollen, optional - Standard: 0-alle Gruppen
	 * @param	int		$lang			Sprache die beim Kopieren ber�cksichtigt werden soll, optional - Standard: 0-keine Sprache
	 * @param	bool	$ignore_lang	true, wenn Perm-Eintr�ge aller Sprachen kopiert werden sollen, ansonsten false.
	 *									optional - Standard: true - perm-Eintr�ge aller Sprachen kopieren
	 *
	 * @return	bool	true, wenn Kopieraktion erfolgreich war, false ansonsten
	 *
	 * @author	J�rgen Br�ndle
	 * @since	CMS 0.96.00 / BETA
	 * @version 0.2 / 20040502
	 *
	 * Change: 02052004 - jb - Aufrufparameter $id f�r _copy_perm-Aufruf hinzugef�gt, Workflow und SQL optimiert
	 */
	 function xcopy_perm($idorigin, $typeorigin, $idtarget, $typetarget, $keepbitmask = 0xFFFFFFFF, $group = 0, $lang = 0, $ignore_lang = true) {
		// pr�fe ob notwendigen angaben gesetzt sind
		if ((empty($idorigin) && !empty($typeorigin) && (strpos($typeorigin, 'area_')) === false) || empty($typeorigin) || empty($typetarget) || empty($idtarget)) return false;
		// Erstelle SQL-Statement zum Auslesen der Perm-Eintr�ge
		$sql  = "SELECT idgroup, idlang, '$typetarget', (perm & $keepbitmask) ";
		$sql .= ' FROM ' . $this->cms_db['perms'] . ' ';
		$sql .= "WHERE id = '" . $idorigin   . "' ";
		$sql .= "AND type = '" . $typeorigin . "' ";
		if (!empty($group)) $sql .= 'AND idgroup = ' . $group . ' ';
		if (!$ignore_lang ) $sql .= 'AND idlang  = ' . $lang  . ' ';
		// Programmierter Optimismus :)
		$result = true;
		// Kopieren der Rechte ausf�hren
		if (is_array($idtarget)) {
			// multiple copy actions
			foreach($idtarget as $id) {
				if (!$this->_copy_perms($sql, $id)) {
					// Fehler f�hrt zum Abbruch der Funktion
					$result = false;
					break;
				}
			}
		} else {
			// single copy action
			$result = $this->_copy_perms($sql, $idtarget);
		}
		return $result;
	 }

	/**
	 * Kopiert Perm-Eintr�ge eines Objektes
	 *
	 * Als �bergabe wird ein SQL verwendet, der die zu kopierenden Rechte ausliest. Als weiterer Parameter wird die ID des
	 * Zielobjektes �bergeben, die beim Kopieren der Recht an Stelle der urspr�nglichen Objekt-ID eingetragen wird.
	 *
	 * @param	string	$sql		SQL-Statement zum Auslesen von Perm-Eintr�gen
	 * @param	int		$targetid	Objekt-ID des Objekts, das die Perm-Eintr�ge erhalten soll
	 *
	 * @author	J�rgen Br�ndle
	 * @since	CMS 0.96.00 / BETA
	 * @version 0.2 / 20040502
	 *
	 * Change: 02052004 - jb - Aufrufparameter $targetid hinzugef�gt
	 */
	 function _copy_perms($sql, $targetid) {
		$perm_array = array();
 		// get perms to assign them the target element
		// the result of the sql must return the complete set of values
		$this->db->query($sql);
		// create the perm-array
		while($this->db->next_record()) {
			$perm_array[] = $this->db->Record;
		}
		// insert every perm into the database
		$max = count($perm_array);
		$result = true;
		for($i = 0; $i < $max; $i++) {
			$sql = sprintf($this->insert_perm,
							$this->cms_db['perms'],
							$perm_array[$i][0],
							$perm_array[$i][1],
							$perm_array[$i][2],
							$perm_array[$i][3],
							$targetid);
			$this->db->query($sql);
			// Fehler f�hrt zu Abbruch
			if ($this->db->Errno != 0) {
				$result = false;
				break;
			}
		}
		return $result;
	 }

	/**
	 * Ermittelt ob es f�r ein Objekt Rechteeintr�ge gibt oder nicht.
	 * Unterscheidet anhand von $ignore_user, ob Benutzerrechte mit ber�cksichtigt werden oder nicht.
	 *
	 * Liefert als Ergebnis die Anzahl der Usergruppen zur�ck, f�r die Rechteeintr�ge bestehen.
	 *
	 * @param	int		$id				Objekt-ID
	 * @param	string	$type			Rechtegruppe
	 * @param	bool	$ignore_user	Benutzerechte ignorieren, optional - Standard: true
	 *
	 * @return	int		Anzahl der eingetragenen Usergruppen
	 */
	 function perms_existing($id, $type, $ignore_user = true) {
	 	$count = 0;

		// Anzahl ermitteltn
		$sql  = 'SELECT Count(idgroup) AS Anzahl ';
		$sql .= 'FROM ' . $this->cms_db['perms'] . ' ';
		$sql .= "WHERE id = '" . $id   . "' ";
		$sql .= "AND type = '" . $type . "' ";
		if ($ignore_user) $sql .= 'AND idgroup > 2 ';

		$this->db->query($sql);
		if ($this->db->next_record()) {
			$count = $this->db->f("Anzahl");
		}
		// Ermittelte Anzahl zur�ckgeben
		return $count;
	 }

	/**
	 * Ermittelt die Anzahl von Objekten f�r ein bestimmtes Recht und seiner �bergeordneten Rechteeintr�ge
	 *
	 * Liefert als Ergebnis die Anzahl der Objekte zur�ck, f�r die Rechteeintr�ge bestehen.
	 *
	 * $group gesetzt:
	 * 1.	$group	> 0     :  Pr�fen der Rechte in der Gruppe
	 * 2.   $group ist Array:  Pr�fen der Rechte in den Gruppen
	 * ist $idlang gesetzt, wird die Sprache beim L�schen ber�cksichtigt:
	 * 1.	$lang	> 0     :  Pr�fen der Rechte in der Sprache
	 * 2.   $lang ist Array :  Pr�fen der Rechte in den Sprachen
	 *
	 *
	 * @param	mixed	$type		Rechtegruppe (Parents werden nicht ermittelt!)
	 *								Rechtegruppen als Array
	 * @param	int		$perm		Zu pr�fendes Recht oder zusammenfassung mehrerer Rechte
	 * @param	mixed	$group		Pr�fen des Rechts der Gruppe (int) oder den Gruppen (array)
	 *                              Ist kein Wert gesetzt, werden die aktuellen Gruppenparameter des perm- Objekts genommen
	 * @param	mixed	$lang		Pr�fen des Rechts der Sprache (int) oder den Sprachen (array)
	 *
	 * @return	bool	true, wenn Anzahl der gefundenen Objekte > 0, ansonsten false
	 *
	 * @author	J�rgen Br�ndle
	 * @since	RC1
	 * @version 0.2 / 20040510
	 */
	function is_any_perm_set($type, $perm, $group = '', $lang = '') {

		//return true;
		$_mask = 0x01;
		$_mask <<= (--$perm);

		// Ermittle ob es irgendein gesetztes Recht gibt
		$sql  = 'SELECT ';
		$sql .= '   idperm ';
		$sql .= 'FROM ';
		$sql .=     $this->cms_db['perms'] . ' ';
		$sql .= 'WHERE ';
		$sql .= '     type ' . $this->_check_array($type, true);
		$sql .= ' AND (perm & ' . $_mask . ') = '.$_mask.' ';
		if (!empty($group)) {
			$sql .= ' AND idgroup ' . $this->_check_array($group);
		}
		if (!empty($lang)) {
			$sql .= ' AND idlang ' . $this->_check_array($lang);
		}
		$sql .= ' limit 1';
		// ... execute
		$this->db->query($sql);
		return $this->db->next_record();
	}

	/**
	 * Gibt die ID des aktuellen Projektes zur�ck
	 *
	 * @return	string	Aktuelle Projekt-ID
	**/
	function get_client() {
		return $this->client;
	}

	/**
	 * Gibt die aktuelle Spracheeinstellung zur�ck
	 *
	 * @return	string	Aktuelle Spracheinstellung
	**/
	function get_lang() {
		return $this->lang;
	}

	/**
	 * Gibt die Zeichensatzcodierung f�r der aktuellen Spracheeinstellung zur�ck
	 *
	 * @return	string	Aktuelle Zeichensatzcodierung
	**/
	function get_lang_charset() {
		return $this->lang_charset;
	}

	/**
	 * Signalisiert, ob der aktuelle User in der Gruppe der Systemadministratoren ist oder nicht.
	 *
	 * @return	bool	true, wenn User Systemadminsitrator ist, ansonsten false
	**/
	function is_admin() {
		return $this ->_is_admin;
	}

	/**
	 * Check-Methoden
	 *
	 * Pr�fung bestimmter Rechte, die f�r die weitere Verarbeitung unabdingbar sind. Sind entsprechende Rechte nicht zugeteilt,
	 * wird die Datei 'perminvaild.php' eingebunden und das Script beendet.
	**/

	/**
	 * Pr�ft ob User in der Gruppe der Systemadministratoren ist
	 * Hat der User nicht das geforderte Rechte wird die Datei 'perminvalid.php' aus dem aktuellen Skin eingebunden und die
	 * Scriptverarbeitung beendet.
	 */
	function check_admin() {
		if (!$this->_is_admin) $this->perm_invalid();
	}

	/**
	 * Pr�ft Userperms.
	 * Hat der User nicht das geforderte Rechte wird die Datei 'perminvalid.php' aus dem aktuellen Skin eingebunden und die
	 * Scriptverarbeitung beendet.
	 * Nutzt zur Rechtepr�fung die methode have_perm, details siehe dort.
	 *
	 * @param	string		$perm_to_check	Zu pr�fendes Recht
	 * @param	string		$perm_cat		Rechtegruppe, optional - Standard: 'cms_access' - Zugriffsrechte
	 * @param	string		$id				Objekt-ID, optional - Standard: 0 - kein Objekt-Recht zur Pr�fung
	 * @param	int			$parent_id		Objekt-ID des Parent-Objekts, optional - Standard: 0 - Parent ist Area
	 */
	function check($perm_to_check, $perm_cat='cms_access', $id='0', $parent_id = 0) {
		@$this->deb -> collect('Teste Rechte: '. $perm_to_check .', cat: '.$perm_cat .', id: '. $id );
		if (!$this->have_perm($perm_to_check, $perm_cat, $id, $parent_id)) $this->perm_invalid();
	}

	/**
	 * Wird aufgerufen, wenn der User ein bestimmtes Recht nicht hat und dieses mit check() gepr�ft wird.
	 * Includiert eine Fehlerseite und beendet das Script danach.
	 *
	 * @global	$cfg_cms
	 *
	**/
	function perm_invalid() {
		global $cfg_cms;

		include $cfg_cms['cms_path'].'tpl/'.$cfg_cms['skin'].'/perminvalid.php';
		exit;
	}

	/**
	 * Gibt das erste Area zur�ck, f�r die der User das Recht hat, die Area zu betreten (Rechtegruppe cms_access)
	 *
	 * @return mixed 	string 	Name des Areas, das der User betreten darf
	 *         			bool    false, wenn kein Area gefunden wurde
	**/
	function get_first_allowed_area(){

		$this->_retrieve_perms('cms_access');

		if(is_array($this->perms['cms_access'])){
			foreach($this->perms['cms_access'] as $allowed_area=>$v)
			{
				switch ($allowed_area)
				{
					case 'area_backend':
					case 'area_frontend':
						break;
					default:
						if($v != '-1') return str_replace('area_', '', $allowed_area);
				}
			}
		}
		return false;
	}

	//
	// privat emethods
	//

	/**
	 * Ermittelt Perm-Eintr�ge f�r eine Rechtegruppe und speichert diese in einer internen Liste der Perm-Werte zwischen
	 *
	 * F�gt dabei nur Rechtegruppen in die interne Liste ein, die noch nicht ausgelesen wurden.
	 *
	 *
	 * @param	string	$type	Rechtegruppe
	 * @param	int		$lang	Sprach-ID
	 * @group	int		$group	Usergruppe
	 *
	 * @author	J�rgen Br�ndle
	 * @since	CMS 0.96.00 / BETA
	 * @version 0.1
	**/
	function _retrieve_perms($type = '', $lang = '', $group = '') {
		// Pr�ft ob der Rechteblock schon geladen ist
		if (!empty($this->loaded_types[(string)$type])) return true;
		@$this->deb->collect("Angefragter Block <strong>$type</strong> wird geladen ... ");

		// Pr�fe ob Sprache und Gruppe gesetzt sind, wenn nicht setze Default-Werte
		// $this->lang f�r Sprache und $this->idgroup f�r die Gruppenliste
		$r_lang  = (!empty($lang))  ? $lang : (empty($this->lang) ? '0': $this->lang);
		$r_group = (!empty($group)) ? $group: $this->idgroup;
		// Lade alle Perms der Rechtegruppe $type f�r die Sprache $r_lang und die Gruppen $r_group
		$sql  = 'SELECT DISTINCT type, id, perm, idgroup ';
		$sql .= 'FROM ' . $this->cms_db['perms'] . ' ';
		$sql .= 'WHERE idlang = ' . $r_lang . ' ';
		$sql .= 'AND idgroup '   . $this->_check_array( $r_group, false );
		if (!empty($type)) {
			$sql .=  'AND type ' . $this->_check_array( $type, true );
		}
		$sql .= 'ORDER BY idgroup DESC';
		$this->db->query($sql);
		// Speichere die Werte im Perm-Array
		while($this->db->next_record()) {
			$perm_type = $this->db->f('type');
			$perm_id   = $this->db->f('id');
			$perm      = $this->db->f('perm');
			$idgroup   = $this->db->f('idgroup');
			$this->_add_perm((string) $perm_type, (string) $perm_id, $perm, $idgroup);
		}
		$this->loaded_types[(string)$type] = 1;
		
		//Lade Addon Objekt
		if (!empty($type)) {
			$addon =& sf_factoryGetObjectCache('ADMINISTRATION', 'PermAddonFactory');
			$this->perm_addon[$type] =& $addon->getAddonObject($type);
		}
	}

	/**
	 * F�gt der lokalen Liste der ausgelesenen Perm-Werte einen Wert hinzu, wenn dieser noch nicht in der Liste enthalten ist.
	 *
	 * @param	string	$perm_type	Rechtegruppe
	 * @param	string	$perm_id	Objekt-ID
	 * @param	int		$perm		Rechtewert
	 * @param	int		$idgroup	Usergruppe
	 *
	 * @author	J�rgen Br�ndle
	 * @since	CMS 0.96.00 / BETA
	 * @version 0.1
	**/
	function _add_perm($perm_type, $perm_id, $perm, $idgroup) {
		$perm = (int) $perm;
		$this->perms[$perm_type][$perm_id]  = (isset($this->perms[$perm_type][$perm_id])) 
			? ( $this->perms[$perm_type][$perm_id] | $perm) : (int) $perm;
		if ($idgroup < 0) {
			$this->perms[$perm_type][$perm_id] =& $perm;
		}
	}

	/**
	 * Ermittelt die Startsprache eines Users
	**/
	function _find_out_lang($client, $lang, $changeclient, $lang_charset) {
		//Keine Sprache oder Client vorhanden, Startclient/ lang suchen
		if(empty($client) || empty($lang) || $changeclient == 1){
			if($changeclient =='1'){
				//client wurde gewechselt, startsprache suchen
				if ($this->_is_admin) {
					//admin wechselt sprache, erste verf�gbare nehmen
					$sql  = "SELECT DISTINCT cl.idclient, cl.idlang ";
					$sql .= "FROM ".$this->cms_db['clients']." c, ".$this->cms_db['clients_lang']." cl ";
					$sql .= "WHERE c.idclient = cl.idclient AND c.idclient = $client ";
					$sql .= "ORDER BY idlang LIMIT 1";
				} else {
					//normaler user, erste sprache nehmen, f�r die er ein recht hat
					$sql  = "SELECT DISTINCT cl.idclient, cl.idlang ";
					$sql .= "FROM ".$this->cms_db['clients']." c, ".$this->cms_db['clients_lang']." cl, ".$this->cms_db['perms']." p ";
					$sql .= "WHERE ";
					$sql .= "c.idclient = cl.idclient AND c.idclient = $client ";
					$sql .= "AND p.idgroup  IN (". implode(',', $this->idgroup) .") ";
					$sql .= "AND p.type = 'lang' AND p.id = cl.idlang ";
					$sql .= "ORDER BY idlang LIMIT 1";
				}
			} else {
				//beim einloggen
				// Admin, oder User?
				if ($this->_is_admin) {
					$sql  = "SELECT DISTINCT cl.idclient, cl.idlang ";
					$sql .= "FROM ".$this->cms_db['clients']." c, ".$this->cms_db['clients_lang']." cl ";
					$sql .= "WHERE c.idclient = cl.idclient ";
					$sql .= "ORDER BY c.name LIMIT 1";
				} else {
					$sql  = "SELECT DISTINCT B.idclient, B.idlang ";
					$sql .= "FROM ".$this->cms_db['clients']." A LEFT JOIN ".$this->cms_db['clients_lang']." B USING(idclient) ";
					$sql .= "LEFT JOIN ".$this->cms_db['perms']." C USING(idlang) ";
					$sql .= "WHERE C.idgroup IN (".implode(',', $this->idgroup).") ";
					$sql .= "AND (C.perm & 0x01 = '1') ";
					$sql .= "ORDER BY A.name LIMIT 1";
				}
			}
			$this->db->query($sql);
			$this->db->next_record();
			$this->client = $this->db->f('idclient');
			$this->lang = $this->db->f('idlang');
			// ermittle charset der sprache oder setze fallback
			if (empty($this->lang)) {
				$this->lang_charset = 'iso-8859-1';
			} else {
				$sql  = "SELECT charset ";
				$sql .= "FROM " . $this->cms_db['lang'] . " ";
				$sql .= "WHERE idlang = " . $this->lang;
				$this->db->query($sql);
				$this->db->next_record();
				$this->lang_charset = $this->db->f('charset');
			}
		} else {
			//Sprache schon vorhanden in Session
			$this->client       = $client;
			$this->lang         = $lang;
			$this->lang_charset = $lang_charset;
		}
	}

	/**
	 * Setzt die Liste aller Gruppen f�r den aktiven Benutzer
	 * Pr�ft gleichzeitig, ob der Benutzer zur Gruppe der Systemadministratoren geh�rt.
	**/
	function _get_group_infos_from_user() {

		$this->idgroup[] = -$this->user_id;
		$sql  = "SELECT groups.idgroup, groups.is_sys_admin ";
		$sql .= "FROM ".$this->cms_db['users']." users, ".$this->cms_db['users_groups']." usergroups, ".$this->cms_db['groups']." groups ";
		$sql .= "WHERE users.user_id    = '".$this->user_id."' ";
		$sql .= "AND usergroups.user_id = users.user_id ";
		$sql .= "AND usergroups.idgroup = groups.idgroup ";
		$sql .= "AND groups.is_active   = '1' ";
		$sql .= "AND users.is_active    = '1'";
		$this->db->query($sql);
		while($this->db->next_record())	{
			$this->idgroup[] = $this->db->f('idgroup');
			if ($this->db->f('is_sys_admin') == 1) $this->_is_admin = true;
		}
	}

	/**
	 * Liest die Gruppeninformationen f�r den zu konfigurierenden Benutzer in die entsprechenden Eigenschaften der Klasse ein
	**/
	function _get_group_infos_from_user_simulate() {
		$sql  = 'SELECT groups.* ';
		$sql .= 'FROM ' . $this->cms_db['groups'] . ' groups ';
		$sql .= 'WHERE groups.idgroup ' . $this->_check_array( $this->idgroup, false );
		$this->db->query($sql);
		if ($this->db->next_record()) {
			//group infos
			$this->idgroup['0']       =  $this->db->f('idgroup');
			$this->name               =  $this->db->f('name');
			$this->description        =  $this->db->f('description');
			$this->group_is_deletable =  $this->db->f('is_deletable');
			$this->group_is_active    =  $this->db->f('is_active');
			$this ->_is_admin         = ($this->db->f('is_sys_admin') == 1);
			$this->user_nick          = 'simulate';
			$this->user_name          = 'simulate';
			$this->user_surname       = 'simulate';
			$this->user_email         = 'simulate';
			$this->user_is_deletable  = 'simulate';
			$this->user_is_active     = 'simulate';
		} else {
			@$this->deb -> collect('keine Gruppe gefunden', 'error');
		}
	}

	/**
	 * Ermittelt alle Usergruppen und alle Rechte zu einem Objekt und liefert das Ergebnis in Form dreier CSV-Listen in einem
	 * Array zur�ck. Die Rechte werden durch die Vererbung ggf. aus verschiedenen Ebenen der Rechtehierarchie ausgelesen.
	 *
	 * WICHTIG: Access-Rechte werden nicht ausgelesen!
	 *
	 * @param	string	$type			Rechtegruppe
	 * @param	int		$id				Objekt-ID
	 * @param	bool	$include_user	True, wenn R�ckgabe Besitzerrechte umfassen soll, sonst false.
	 * @param	bool	$all_user		True, wenn R�ckgabe alle Besitzerrechte umfassen soll, sonst false. Nur in Verbindung mit
	 *									$include_user
	 * @param	int		$parent			ID der Parent-Objekts
	 *
	 * @global	$lang
	 *
	 * @return	int		Rechte-Bitmaske aus den Werten des Array oder Integer aus der �bergabe
	 *
	 * @author	J�rgen Br�ndle
	 * @since	CMS 0.96.00 / BETA
	 * @version 0.2 / 20040401
	 *
	 * Change: 01.04.04 - jb - Sortierung erfolgt alphabetisch nach Namen
	**/
	function _get_rights_by_groups_user($right_type, $id, $include_user, $all_user, $parent) {
		global $lang;

		$rights = array( 'groupids'=>array(), 'groups'=>array(), 'grouprights'=>array() );

		// Ermittle alle Usergruppen des Elements
		$sql  = 'SELECT DISTINCT idgroup, name ';
		$sql .= 'FROM ' . $this->cms_db['groups'] . ' ';
		$sql .= 'WHERE idgroup >= 3 ';
		$sql .= 'ORDER BY name';
		$this->db->query($sql);
		while ($this->db->next_record()) {
			$groupid = $this->db->f("idgroup");
			$rights['groupids'][$groupid]    = $groupid;
			$rights['groups'][$groupid]      = $this->db->f("name");
			$rights['grouprights'][$groupid] = '';
			$rights['grouprights_are_inherit'][$groupid] = true;
			$rights['groupsoverwrite'][$groupid] = false;
		}
		
		//addonobjekt laden
		if (! is_object($this->perm_addon[$right_type])) {
			$addon =& sf_factoryGetObjectCache('ADMINISTRATION', 'PermAddonFactory');
			$this->perm_addon[$right_type] =& $addon->getAddonObject($right_type);
		}
		
		$is_first_run = true;
		do {
			// Ermittle alle vergebene Rechte f�r das Element
			$sql  = 'SELECT DISTINCT P.idgroup, P.perm ';
			$sql .= 'FROM ' . $this->cms_db['perms'] . ' P ';
			$sql .= 'LEFT JOIN ' . $this->cms_db['groups'] . ' G ON P.idgroup = G.idgroup ';
			$sql .= 'WHERE P.idgroup >= 3 ';
			$sql .= " AND P.type   = '$right_type'";
			$sql .= " AND P.type  <> 'cms_access'";
			$sql .= " AND P.id     = '$id'";
			$sql .= ' AND P.idlang = ' . $lang . ' ';
			$sql .= 'ORDER BY G.name';
			$this->db->query($sql);
			while ($this->db->next_record()) {
				$groupid = $this->db->f("idgroup");
				if ($rights['grouprights'][$groupid] == '') {
					$rights['grouprights'][$groupid] = $this->db->f("perm");
				}
				if ($is_first_run) {
					$rights['grouprights_are_inherit'][$groupid] = false;
				}
				
			}
			
			$is_first_run = false;
		} while ($id = $this->perm_addon[$right_type]->getParent($id));
		// Setze Area-Rechte f�r alle ungesetzten Gruppenrechte
		// Schleife wegen drei Ebenen-Vererbung ...
		//
		// Erster Durchgang liest die Rechte aus, die der �bergeordneten Rechtegruppe (Ordner/kategorien) und einer ParentID
		// zugeordnet sind. Ist die ParentID nicht 0, wird die Schleife nochmals mit der �bergeordneten Rechtegruppe und dem
		// Wert '0' f�r die ParentID durchlaufen, erst danach geht es auf die n�chsth�here Ebene (Area-Ebene), die immer mit
		// dem Wert '0' f�r die ParentID arbeitet.
		//
		$child_type = $right_type;
		$parent_id  = $parent;
		while (isset($this->parent_rights[$child_type])) {
			//addonobjekt laden
			if (! is_object($this->perm_addon[ $this->parent_rights[$child_type] ])) {
				$addon =& sf_factoryGetObjectCache('ADMINISTRATION', 'PermAddonFactory');
				$this->perm_addon[ $this->parent_rights[$child_type] ] =& $addon->getAddonObject($this->parent_rights[$child_type]);
			}
			
			do {
				$current_parent = $parent_id;
				$sql  = 'SELECT DISTINCT P.idgroup, P.perm ';
				$sql .= 'FROM ' . $this->cms_db['perms'] . ' P ';
				$sql .= 'LEFT JOIN ' . $this->cms_db['groups'] . ' G ON P.idgroup = G.idgroup ';
				$sql .= "WHERE P.idgroup IN ('" . implode("','", $rights['groupids']) . "')";
				$sql .= " AND P.type   = '" . $this->parent_rights[$child_type] . "'";
				$sql .= " AND P.type  <> 'cms_access'";
				$sql .= " AND P.id     = '$parent_id'";
				$sql .= ' AND P.idlang = ' . $lang . ' ';
				$sql .= 'ORDER BY G.name';
				$this->db->query($sql);
				while ($this->db->next_record()) {
					$groupid = $this->db->f("idgroup");
					if ($rights['grouprights'][$groupid] == '') {
						$rights['grouprights'][$groupid] = $this->db->f("perm");
					}
				}
				
			} while ($parent_id = $this->perm_addon[ $this->parent_rights[$child_type] ]->getParent($parent_id));
			
			if ($current_parent == '0') {
				$child_type = $this->parent_rights[$child_type];
			}
			$parent_id = '0';
		}
		// Ermittle Benutzerrechte
		if ($include_user) {
			$sql  = 'SELECT DISTINCT p.idgroup, p.perm, u.username ';
			$sql .= 'FROM ' . $this->cms_db['perms'] . ' p, ' . $this->cms_db['users'] . ' u ';
			$sql .= 'WHERE abs(p.idgroup) = u.user_id';
			$sql .= ' AND p.idgroup < 0';
			$sql .= ' AND p.idgroup = -' . $this->user_id;
			$sql .= " AND p.type    = '$right_type'";
			$sql .= " AND p.type   <> 'cms_access'";
			$sql .= " AND p.id      = '$id'";
			$sql .= ' AND p.idlang  = ' . $lang . ' ';
			$sql .= 'ORDER BY u.username';
			$this->db->query($sql);
			while ($this->db->next_record()) {
				$rights['groupids'][]    = $this->db->f("idgroup");
				$rights['groups'][]      = $this->db->f("username");
				$rights['grouprights'][] = $this->db->f("perm");
			}
		}
		// Liefere ein Array mit CSV f�r Gruppen und Userrechte zur�ck
		
		//print_r($rights);echo "<br />";
		return $rights;
	}


	/**
	 * Erstellt eine Bitmask f�r die Rechte durch Addition von Array-Werten
	 *
	 * @param	mixed	$permission		Array mit den Integerwerten f�r die Rechte oder einzelner Integerwert f�r ein Recht
	 *
	 * @return	int		Rechte-Bitmaske aus den Werten des Array oder Integer aus der �bergabe
	 *
	 * @author	J�rgen Br�ndle
	 * @since	CMS 0.96.00 / BETA
	 * @version 0.1
	**/
	function _get_mask($permission) {
		// check if array ...
		if (!is_array($permission)) return $permission;
		// ... okay, sum all value ...
		$perm = 0;
		$max  = count($permission);
		for($i = 0; $i < $max; $i++) {
			$perm += $permission[$i];
		}
		// ... and return
		return $perm;
	}




	/******************************************************************
	 * Private Methoden der Klasse
	******************************************************************/

	/**
	 * Erstellt das Rechtepanel f�r die Darstellung bei der Bearbeitung von Objekten
	 *
	 * Ermittelt alle Rechtenamen und Rechtebitmasken aus der Tabelle cms_values und gibt die Information als Panel
	 * unter Verwendung der lokalisierten Textstring aus den Language-Files und der Datenbank aus
	 *
	 * Baut aus der Liste der Rechtenamen einen Tabelle mit allen Rechten und jeweils zwei Radiobuttons
	 *
	 * �nderung der Schnittstelle, weil in Mozilla dynamische Formulare, die aus JS erstellt werden, nicht mit JS angesprochen
	 * werden k�nnen. Schnittstelle gibt jetzt den Inhalt f�r das Rechtepanel zur�ck anstatt einer Liste der Rechtenamen und
	 * Rechtewerte.
	 *
	 * @param	string 	$type	 		Anzuzeigender Rechtetyp
	 * @param	array 	$rights 		Array mit Gruppennamen, Gruppenids und Gruppenrechte
	 * @param	string 	$countRights 	Referenz zur Aufnahme der Anzahl der ausgelesenen Rechte
	 * @param	string 	$prefix 		Prefix f�r Formularvariablen, damit mehrere Formulare auf einer Seite dargestellt werden k�nnen
	 *
	 * @global  $lang_dir
	 * @global  $cms_lang
	 *
	 * @return	string	HTML-Konstrukt einer Tabelle mit Auswahlliste der Gruppen und Rechteliste mit Radiobuttons
	 *
	 * @author	J�rgen Br�ndle
	 * @since	CMS 0.96.00 / BETA
	 * @version 0.3 / 20040425
	**/
	function _get_right_names_and_values( $type, $rights, &$countRights , $prefix) {
		global $cms_lang, $lang_dir, $lang_defdir;
		include( ( file_exists( $lang_dir.'lang_user_perms.php') ? $lang_dir: $lang_defdir ) .'lang_user_perms.php');

		// Erstelle Rechteliste aus cms_values
		$sql  = 'SELECT conf_desc_langstring AS lang_desc, value AS bitmask ';
		$sql .= 'FROM ' . $this->cms_db['values'] . ' ';
		$sql .= "WHERE group_name = 'user_perms'";
		$sql .= " AND  key1       = '$type'";
		$sql .= ' AND  idclient   = 0 ';
		$sql .= ' AND  idlang     = 0 ';
		$sql .= 'ORDER BY conf_sortindex ASC, idvalues DESC';
		$countRights = 0;
		$rights_list = "";
		$this->db->query($sql);
		while ($this->db->next_record()) {
			$rights_list .= '<tr onmouseover="this.style[\'background\']=\'#FFF5CE\';" onmouseout="this.style[\'background\']=\'#FFFFFF\';">'."\n";
			$rights_list .= '  <td class="first nowrap" align="left">' . $cms_lang[$this->db->f('lang_desc')] . '</td>'."\n";
			$rights_list .= '  <td align="center" width=\'50\'>';
			$rights_list .= '<input type="radio" name="'.$prefix.'uright' . $countRights . '" value="-' . $this->db->f('bitmask') . '" /></td>'."\n";
			$rights_list .= '  <td align="center" width=\'50\'>';
			$rights_list .= '<input type="radio" name="'.$prefix.'uright' . $countRights . '" value="' . $this->db->f('bitmask') . '" /></td>'."\n";
			$rights_list .= '</tr>'."\n";
			$countRights++;
		}
		//checkbox - vorhandene rechte ueberschreiben
		if (! is_object($this->perm_addon[$type])) {
			$addon =& sf_factoryGetObjectCache('ADMINISTRATION', 'PermAddonFactory');
			$this->perm_addon[$type] =& $addon->getAddonObject();
		}
		if ( $this->perm_addon[$type]->showDeleteChildsCheckbox($type)) {
			$rights_list .= '<tr onmouseover="this.style[\'background\']=\'#FFF5CE\';" onmouseout="this.style[\'background\']=\'#FFFFFF\';">';
			$rights_list .= '  <td class="first nowrap" align="left">' . '<i>Vorhandene Rechte zur&uuml;cksetzen</i>' . '</td>';
			$rights_list .= '  <td align="center">'."\n";
			$rights_list .= '</td>';
			$rights_list .= '  <td align="center" bgcolor="#EBD5C7"><input type="checkbox" name="'.$prefix.'rmueberschreiben" id="'.$prefix.'rmueberschreiben" value="1" /></td>'."\n";
			$rights_list .= '</tr>';
		} else {
			$rights_list .= '<input type="hidden" name="'.$prefix.'rmueberschreiben" id="'.$prefix.'rmueberschreiben" value="" />'."\n";
		}
		

		// Erzeuge Kopf des Panels - �berschrift, Gruppen-DropDown
		$panel  = "\n".'<div id="rightsmenucoat">'."\n";
		// �berschrift
		$panel .= '  <h2>' . $cms_lang['panel_grouphead'] . '</h2>'."\n";
		// Dropdown der Benutzergruppen
		$panel .= '<p>'."\n";
		$panel .= '    <select class="groupselect" name="'.$prefix.'rmgruppe" id="rmgruppe" onchange="'.$prefix.'cms_rm.showRightsOfGroup(' . $countRights . ')">';
		$panel .= $this->_create_group_dropdown( $rights['groups'], $rights['groupids'] );
		$panel .= '    </select>';
		$panel .= '</p>'."\n";
		// Checkbox Rechte des Vorg�ngers benutzen
		$panel .= '<p>'."\n";
		$panel .= '    <input type="checkbox" name="'.$prefix.'rmerben" id="'.$prefix.'rmerben" value="1" onclick="'.$prefix.'cms_rm.handleRadioReadonly()" /> <label for="'.$prefix.'rmerben">Rechte vom Vorg&auml;nger erben</label>'."\n";
		$panel .= '</p>'."\n";

		// Spalten�berschriften
		$panel .= '<table>'."\n";
		$panel .= '<tr>'."\n";
		$panel .= '  <th class=\'first\'>' . $cms_lang['panel_rechte'] . '</th>'."\n";
		$panel .= '  <th width=\'50\' align="center">' . $cms_lang['panel_denied'] . '</th>'."\n";
		$panel .= '  <th width=\'50\' align="center">' . $cms_lang['panel_granted'] . '</th>'."\n";
		$panel .= '</tr>';
		$panel .= '</table>'."\n";
		$panel .= '<div class="rightsscrolldiv">'."\n";
		$panel .= '<table>'."\n";
		// Rechtezeilen
		$panel .= $rights_list;
		$panel .= '</table>'."\n";
		$panel .= '</div>'."\n";
		
		
		// Zeile f�r alle Rechte, zun�chst noch gesperrt ...
//		$panel .= '<!--tr onmouseover="this.style[\'background\']=\'#C7D5EB\';" onmouseout="this.style[\'background\']=\'#DBE3EF\';">';
//		$panel .= '  <td class="rightsname nowrap" align="left">' . $cms_lang['panel_all_rights'] . '</td>';
//		$panel .= '  <td class="rightscell" align="center">';
//		$panel .= '<a href="javascript:void(0)" onclick="'.$prefix.'cms_rm.setAllRights(-1);">' . $cms_lang['panel_all_rights_denied'] . '</a></td>';
//		$panel .= '  <td class="rightscell" align="center">';
//		$panel .= '<a href="javascript:void(0)" onclick="'.$prefix.'cms_rm.setAllRights(1);">' . $cms_lang['panel_all_rights_granted'] . '</a></td>';
//		$panel .= '</tr-->';
//		// Zeile f�r Rechte auf Unterelemente vererben, zun�chst noch gesperrt ...
//		$panel .= '<!--tr onmouseover="this.style[\'background\']=\'#C7D5EB\';" onmouseout="this.style[\'background\']=\'#DBE3EF\';">';
//		$panel .= '  <td class="rightsname nowrap" align="left">' . $cms_lang['panel_all_rights'] . '</td>';
//		$panel .= '  <td class="rightscell" align="center">';
//		$panel .= '<a href="javascript:void(0)" onclick="'.$prefix.'cms_rm.setAllRights(-1);">' . $cms_lang['panel_all_rights_denied'] . '</a></td>';
//		$panel .= '  <td class="rightscell" align="center">';
//		$panel .= '<a href="javascript:void(0)" onclick="'.$prefix.'cms_rm.setAllRights(1);">' . $cms_lang['panel_all_rights_granted'] . '</a></td>';
//		$panel .= '</tr-->';
		// Buttonzeile
		$panel .= '<div class="submit">'."\n";
		$panel .= '    <!-- input type="button" onclick="'.$prefix.'cms_rm.addUser()" name="NewUser"';
		$panel .= ' value="' . $cms_lang['panel_addbutton'] . '" --> ';
		$panel .= '<input class="sf_buttonAction" type="button" onclick="'.$prefix.'cms_rm.saveRights(true, ' . $countRights . ')" name="Okay"';
		$panel .= ' value="' . $cms_lang['panel_closebutton2'] . '" /> '."\n";
		$panel .= '<input class="sf_buttonAction" type="button" onclick="'.$prefix.'cms_rm.saveRights(false,' . $countRights . ' )" name="Apply"';
		$panel .= ' value="' . $cms_lang['panel_savebutton'] . '" /> '."\n";
		$panel .= '<input class="sf_buttonActionCancel" type="button" onclick="'.$prefix.'cms_rm.cancelRights(true)" name="Cancel"';
		$panel .= ' value="' . $cms_lang['panel_closebutton'] . '" /> '."\n";
		$panel .= '</div>'."\n".'</div>'."\n";
		return $panel;
	}

	/**
	 * Erstellt eine Dropdown-Liste der �bergebenen Gruppen f�r das Rechtepanel
	 *
	 * @param	string 	$groups 		Array mit den Gruppennamen
	 * @param	int 	$groupids		Array mit den IDs der Gruppen
	 *
	 * @global	$cms_lang
	 *
	 * @return	string	HTML-Konstrukt aus Option-Tags und Optiongroups
	 *
	 * @author	J�rgen Br�ndle
	 * @since	CMS 0.99.00 / RC1
	 * @version 0.1 / 20040411
	**/
	function _create_group_dropdown( $groups, $groupids ) {
		global $cms_lang;

		$blnBenutzer = false;
		$arrTemp = array();
		$countGroup = count($groups);
		for($i = 0; $i < $countGroup; $i++) {
			$groupname = array_shift($groups);
			$groupid   = array_shift($groupids);
			if (!$blnBenutzer) {
				if ($i == 0) {
					array_push( $arrTemp, '<optgroup label="' );
					array_push( $arrTemp, (($groupid > 0) ? $cms_lang['panel_usergroups']: $cms_lang['panel_user'] ) );
					array_push( $arrTemp, '">' );
					$blnBenutzer = ($groupid < 0);
				}
				if ($i > 0 && $groupid < 0) {
					array_push($arrTemp, '</optgroup><optgroup label="', $cms_lang['panel_user'] , '">');
					$blnBenutzer = true;
				}
			}
			array_push($arrTemp, '<option value="', $i, '">', $groupname, "</option>\n");
		}
		array_push($arrTemp, '</optgroup>');
		return implode('', $arrTemp);
	}

	/**
	 *
	 * Erstellt eine Bedingung f�r eine SQL-Qeury, die einen bestimmten Wert sucht oder einen bestimmten Wert innerhalb einer
	 * Liste von Elementen sucht. Hierbei wird unterschieden zwischen Textwerten und Zahlen.
	 *
	 * @param	mixed	$array_or_value		Wert oder Array von Werten
	 * @param	bool	$is_string			Werte sind Texte (true) oder Zahlen (false, Standardwert)
	 *										optional
	 *
	 * @return	string	Bedingung f�r SQL-Query als IN- oder =-Bedingungsfragment f�r den Zusammenbau der SQL-Query
	 *
	 * @author	J�rgen Br�ndle
	 * @since	CMS 0.96.00 / Beta
	 * @version 0.1
	**/
	function _check_array( $array_or_value, $is_string = false ) {
		if (is_array($array_or_value)) {
			$fragment = ($is_string) ? " IN ('" . implode("','", $array_or_value) . "') ":' IN (' . implode(',', $array_or_value) . ') ';
		} else {
			$fragment = ($is_string) ? " = '$array_or_value' ": " = $array_or_value ";
		}
		return $fragment;
	}

	/**
	 * Ermittelt die Anzahl der konfigurierbaren Gruppen
	 *
	 * Ber�cksichtigt die Usergruppen "keine" und "Administrator" f�r die Z�hlung
	 * nicht, das es hierbei nur um konfigurierbare Gruppen geht.
	 *
	 * @author	J�rgen Br�ndle
	 * @since	CMS 0.97.00 / Beta
	 * @version 0.1 / 20040224
	**/
	function _count_groups() {
		$this->_group_count = 0;
		// Ermittle Anzahl der Gruppen um Funktionen des Rechtemanagements zu blockieren
		$sql = 'SELECT Count(idgroup) Anzahl FROM ' . $this->cms_db['groups'] . ' WHERE idgroup > 2';
		$this->db->query($sql);
		if ($this->db->next_record()) {
			$this->_group_count = $this->db->f('Anzahl');
		}
	}
}
?>
