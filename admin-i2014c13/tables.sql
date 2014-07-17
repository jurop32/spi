-- phpMyAdmin SQL Dump
-- version 3.3.10
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 03, 2013 at 12:06 AM
-- Server version: 5.5.31
-- PHP Version: 5.3.10-1ubuntu3.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `penoveizolacie`
--

-- --------------------------------------------------------

--
-- Table structure for table `SPI_ARTICLES`
--

CREATE TABLE IF NOT EXISTS `SPI_ARTICLES` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alias` varchar(250) NOT NULL,
  `article_title` varchar(250) NOT NULL,
  `article_createDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `article_changeDate` datetime NOT NULL,
  `article_prologue` varchar(3000) NOT NULL,
  `article_content` text NOT NULL,
  `keywords` varchar(500) NOT NULL,
  `id_menucategory` int(11) NOT NULL,
  `author` varchar(200) NOT NULL,
  `layout` char(1) NOT NULL,
  `lang` int(3) NOT NULL,
  `added_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `showsocials` tinyint(1) NOT NULL,
  `publish_date` datetime NOT NULL,
  `published` tinyint(1) NOT NULL,
  `topped` tinyint(1) NOT NULL,
  `homepage` tinyint(1) NOT NULL,
  `viewcount` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28 ;

--
-- Dumping data for table `SPI_ARTICLES`
--

INSERT INTO `SPI_ARTICLES` (`id`, `alias`, `article_title`, `article_createDate`, `article_changeDate`, `article_prologue`, `article_content`, `keywords`, `id_menucategory`, `author`, `layout`, `lang`, `added_by`, `updated_by`, `showsocials`, `publish_date`, `published`, `topped`, `homepage`, `viewcount`) VALUES
(1, 'Kontakt', 'Kontakt', '2013-05-28 09:21:15', '2013-06-21 15:29:51', 'Kontaktné informácie.', '<h2>SaveEnergy s.r.o.</h2>\n<p><strong>Distribučné centrum pre Slovensko a Českú republiku.</strong><br /> Púchovská 12<br /> 831 06 Bratislava<br /> Slovensko<br />Tel: +421 902 286 776, +421 903 673 346<br /> <br /><strong>IČO</strong>: 43 803 725<br /><strong>DIČ</strong>: 2022 4758 73<br /><strong>IČ DPH</strong>: SK2022475873<br /><strong>Číslo účtu</strong>: SLSP: 0634544747/0900<br /><br /><strong>Office</strong>: +421 2 44 63 07 46<br /><strong>Fax</strong>: +421 2 44 88 77 29<br /><strong>E-mail</strong>: info@saveenergy.sk</p>', 'kontakt', 9, '', 's', 1, 1, 1, 0, '2013-05-28 00:00:00', 1, 0, 0, 7),
(2, 'O-Saveenergy', 'O Saveenergy', '2013-05-28 11:45:24', '2013-06-11 16:19:16', 'Saveenergy je autorizovaný distribútor pre striekané penové izolácie a izolačné materiály Demilec pre Slovensko a Česko a hydroizolačné materiály Bitum pre Slovensko. Distribúcia, predaj, odborná pomoc, školenia aplikátorov, semináre, poradenstvo.', '<p>Spoločnosť SaveEnergy s.r.o. je výhradný distribútor pre striekané penové izolácie a izolačné materiály kanadsko - amerického konzorcia DEMILEC GROUP ® pre slovenský a český trh. Je to taktiež výhradný distribútor pre hydroizolačné materiály izraelskej spoločnosti Bitum pre Slovenskú republiku.</p>\n<p>Predmetom činnosti tejto spoločnosti je:</p>\n<ul>\n<li>Distribúcia a predaj moderných hydroizolačných materiálov na zatepľovanie a izoláciu rôznych budov, priemyselných skladov, silážnych priestorov, potrubí, automobilov a pod.</li>\n<li>Predaj aplikačnej technológie GRACO ® s plným záručným a pozáručným servisom</li>\n<li>Školenia pre certifikovaných aplikátorov autorizovaných na zatepľovanie a vykonávanie hydroizolačných nástrekov</li>\n<li>Odborná pomoc, technický dozor a podpora pri aplikácii hydroizolačných a termoizolačných materiálov</li>\n<li>Odborné poradenstvo pri stavbách</li>\n<li>Architektonická, projekčná a kompletná inžinierska činnosť</li>\n<li>Odborné a prezenčné semináre pre projektantov, architektov a odbornú verejnosť</li>\n<li>Osveta, vzdelávanie a navrhovanie nových budov, stavieb a materiálov v spolupráci so slovenskou radou pre zelené budovy - www.skgbc.org.</li>\n</ul>', 'striekané penové izolácie, izolačné materiály, hydroizolačné materiály, izolácia budov, izolácia skladov, izolácia podtrubí, odborné školenia', 11, '', 's', 1, 2, 1, 0, '2013-05-28 00:00:00', 1, 0, 0, 2),
(3, 'HEATLOK-SOY-200', 'HEATLOK SOY® 200', '2013-05-31 12:37:58', '2013-07-02 15:22:45', 'Heatlok Soy 200 je striekaná penová izolácia s uzavretou bunkovou štruktúrou. Je to energeticky úsporný izolačný materiál, tepelná izolácia, hydroizolácia, vzduchová bariéra a parozábrana v jednom.', '<p><img style="display: block; margin-left: auto; margin-right: auto;" title="penove izolacie heatlok soy logo" src="files/articles/3/penove-izolacie-heatlok-soy-logo.jpg" alt="penove izolacie heatlok soy" width="660" height="120" /></p>\n<p style="text-align: justify;"><img style="float: right; margin-top: 0px; margin-bottom: 0px; margin-left: 20px; margin-right: 20px;" title="heatlok soy 200 aplikátor" src="files/articles/3/hetaloksoy_200_001.png" alt="heatlok soy 200 aplikátor" width="250" height="300" />Tvrdá striekaná penová izolácia s uzavretou bunkovou štruktúrou disponujúca veľmi nízkym súčiniteľom tepelnej vodivosti. Vďaka tejto uzavretej štruktúre nepreniká voda a zabraňuje sa aj kondenzácii. Jej hydroizolačné vlastnosti zabezpečujú, že tieto penové izolácie sú vhodné ako izolácia plochých striech a to s polovičnou hrúbkou oproti bežným izolačným systémom. Sú tiež plne pôchodzne a svojou priľnavosťou a štruktúrou niekoľkonásobne spevnia konštrukciu.</p>\n<p><strong>Výhody:</strong></p>\n<ul>\n<li>Polovičná hrúbka oproti bežným izolačným materiálom</li>\n<li>Znižovanie prašnosti a znečistenia vzduchu</li>\n<li>Energetická úspora - zabraňuje úniku vzduchu z objektov</li>\n<li>Eliminácia tvorby tepelných mostov a tepelných strát vďaka bezškárovému homogénnemu nástreku</li>\n<li>Je to hydroizolácia, tepelná izolácia a parozábrana v jednom</li>\n<li>Životnosť a dlhotrvajúce tepelnoizolačné vlastnosti</li>\n<li>Zvýšenie torznej odolnosti a štruktúry stavby</li>\n<li>Enormná rýchlosť práce - až 400m² za deň</li>\n</ul>\n<p><iframe style="display: block; margin-left: auto; margin-right: auto;" src="http://www.youtube.com/embed/N-YGwLW2fm4" frameborder="0" width="425" height="350"></iframe></p>\n<p><strong>Technické informácie</strong></p>\n<p style="text-align: justify;"><img style="float: right; margin-top: 0px; margin-bottom: 0px; margin-left: 20px; margin-right: 20px;" title="heatlok soy 200 tepelná izolácia" src="files/articles/3/hetaloksoy_200_002.png" alt="heatlok soy 200 tepelná izolácia" width="250" height="300" /><strong>Tepelná izolácia</strong> - Heatlok Soy sa absolútne chemicky aj mechanicky viaže na podklad (betón, drevo, tehla, kov, sklo) a to tak, že nevytvára žiadny vzduchový priestor medzi materiálom a podkladom.</p>\n<p style="text-align: justify;"><strong>Vzduchová bariéra</strong> - Vďaka nej Heatlok Soy predchádza kondenzácii vody, ktorá je častým dôvodom vzniku plesní a zničenia stien. Takmer 100% -ný podiel uzavretých buniek vytvára vzduchovú bariéru, ktorá je efektívna, pevná, bezškárová a odolná.</p>\n<p style="text-align: justify;"><strong>Parozábrana </strong>- Rýchlosť, akou vodné pary prenikajú cez väčšiu časť materiálu, sa nazýva permeancia. Normou je 1 perm, pričom nezávislé testy ukázali, že Heatlok Soy prekračuje štandardy o viac ako 38%.</p>\n<p style="text-align: justify;"><br /><strong>Technické špecifiká</strong></p>\n<table style="width: 90%;" border="0" rules="all" cellpadding="5">\n<tbody>\n<tr>\n<td>Hustota (v jadre)</td>\n<td>34-37 kg/m3</td>\n</tr>\n<tr>\n<td>Tepelná odolnosť, odpor (R)</td>\n<td>1.17 m2 K/W pri 25 mm</td>\n</tr>\n<tr>\n<td>Stlačiteľnosť (deformácia)</td>\n<td>195 kPa</td>\n</tr>\n<tr>\n<td>Odtrhnuteľnosť (poškodenie)</td>\n<td>195 kPa</td>\n</tr>\n<tr>\n<td>Absorbcia vody</td>\n<td>0.8% z celkového objemu</td>\n</tr>\n<tr>\n<td>Faktor difúzneho odporu</td>\n<td>Min. 60</td>\n</tr>\n<tr>\n<td>Vzduchopriepustnosť pri 75Pa</td>\n<td>0.00004L/s.m2 pri 25 mm</td>\n</tr>\n</tbody>\n</table>\n<p><a class="button" title="Certifikáty" href="c/6/O-nas.html#a/27/Certifikaty.html">Certifikáty</a></p>', 'striekaná penová izolácia, izolácia, izolácia plochých striech, hydroizolácia, tepelná izolácia, parozábrana, vzduchová bariéra, Heatlok Soy 200', 40, '', 's', 1, 2, 1, 0, '2013-05-31 00:00:00', 1, 0, 0, 30),
(4, 'SEALECTION-500', 'SEALECTION® 500', '2013-05-31 12:38:52', '2013-07-02 15:19:03', 'Striekaná penová izolácia Sealection 500 je izolačný materiál s otvorenou bunkovou štruktúrou. Je to tepelná izolácia, ktorá sa využíva ako izolácia stropov, podkroví, suterénov, pivníc, dutín a drevených a kovových konštrukcií.', '<p><img style="display: block; margin-left: auto; margin-right: auto;" title="penove izolacie sealection 500 logo" src="files/articles/4/penove-izolacie-sealection-500-logo.jpg" alt="penove izolacie sealection 500" width="660" height="120" /></p>\n<p style="text-align: justify;"><img style="margin-top: 0px; margin-bottom: 0px; margin-left: 20px; margin-right: 20px; float: right;" title="striekané penové izolácie sealection 500" src="files/articles/4/sealection_500_002.png" alt="striekané penové izolácie sealection 500" width="250" height="300" />Táto mäkká striekaná pena s otvorenou bunkovou štruktúrou poskytuje výrazne väčšie množstvo výhod ako bežne používané izolačné materiály (minerálna vlna alebo celulóza). Jednou z hlavných výhod je jej schopnosť dokonale uzavrieť a zaplniť všetky medzery a otvory, vďaka čomu dokonale zateplí a utesní v jednom kroku. Nástreková izolačná pena SEALECTION® 500 je vzduchotesná a teda vykurovaný vzduch neuniká von, čoho výsledkom je, že kúrenie funguje omnoho efektívnejšie. Konvenčné materiály majú často tendenciu sadať a meniť svoju veľkosť a tvar, zatiaľ čo naša striekaná penová izolácia si zachová svoj tvar a kvalitatívne ostane nezmenená odo dňa aplikácie po celú životnosť stavby.<br />Striekaná penová izolácia SEALECTION® 500 má vďaka vysokej pohltivosti zvuku aj výborné zvukovoizolačné vlastnosti. Tiež nie je negatívne ovplyvňovaná vlhkosťou, nepodporuje tvorbu plesní a je odolná voči hlodavcom, vtákom a hmyzu.<br /><br /><strong>Výhody v porovnaní s konvenčnými izoláciami:</strong></p>\n<ul>\n<li><strong>Zníženie nákladov na energie</strong> až o 50%</li>\n<li><strong>Zatepľuje, utesňuje a neprepúšťa vzduch </strong></li>\n<li><strong>Neusádza sa v medzerách a štrbinách</strong>, ale dokonale ich vypĺňa</li>\n<li>Vekom sa <strong>neznižuje jej kvalita</strong></li>\n<li>Zlepšuje <strong>akustické vlastnosti</strong> objektov</li>\n<li>Vytvára zdravé prostredie a znižuje výskyt alergických reakcií</li>\n</ul>\n<p><iframe style="display: block; margin-left: auto; margin-right: auto;" src="http://www.youtube.com/embed/NhyV7gChjP8" frameborder="0" width="560" height="315"></iframe><br /><strong>Využíva sa predovšetkým ako:</strong></p>\n<ul>\n<li>Izolácia stropov a podkroví</li>\n<li>Izolácia suterénov a pivníc</li>\n<li>Izolácia stavebných vypuklín, dutín a štrbín</li>\n<li>Izolácia kovových a drevených konštrukcií</li>\n</ul>\n<p><br /><strong>Technické informácie </strong></p>\n<p>SEALECTION® 500 je tepelná striekaná penová izolácia, ktorá je priateľská k životnému prostrediu, neobsahuje vlákna ani azbest a tiež nenarušuje ozónovú vrstvu. Kladne ovplyvňuje tieto faktory:</p>\n<ul>\n<li style="text-align: justify;"><img style="float: right; margin-top: 0px; margin-bottom: 0px; margin-left: 20px; margin-right: 20px;" title="striekané penové izolácie sealection 500 tepelná izolácia" src="files/articles/4/sealection_500_001.png" alt="striekané penové izolácie sealection 500 tepelná izolácia" width="250" height="300" /><strong>Energetická úspora</strong> - prúdenie vzduchu a strata tepla sú eliminované vďaka vzduchotesnému materiálu. Ten tiež udržuje stálu a pohodlnú teplotu</li>\n<li style="text-align: justify;"><strong>Nižšie stavebné náklady</strong> - použitím tejto izolácie môžete dosiahnuť úsporu pri nákupe menších tepelných a chladiacich systémov. Táto úspora na nákladoch a priestore dosahuje až 40%</li>\n<li style="text-align: justify;"><strong>Nižšie náklady pre majiteľov objektu</strong> - celkové náklady na zatepľovanie a chladenie môžu klesnúť až o 40%</li>\n<li style="text-align: justify;"><strong>Hlučnosť</strong> - zvuk nie je schopný prechádzať cez zateplené steny a tým sa hlučnosť znižuje.</li>\n<li style="text-align: justify;"><strong>Zdravie</strong> - znižuje sa prašnosť, znečistenie a eliminujú sa baktérie v objektoch. Vzduch sa stáva zdravším ako vzduch zvonka</li>\n<li style="text-align: justify;"><strong>Spoľahlivá kvalita</strong> - Sprejová penová izolácia nepodlieha znehodnocovaniu ani hnitiu a preto udržuje svoju špičkovú kvalitu po celú dobu používania</li>\n</ul>\n<table style="width: 90%;" border="0" rules="all" cellpadding="5">\n<tbody>\n<tr>\n<td>Hustota (v jadre)</td>\n<td>7.37 kg/m3</td>\n</tr>\n<tr>\n<td>Tepelná odolnosť, odpor (R)</td>\n<td>0.671m2˚C/W pri 25 mm</td>\n</tr>\n<tr>\n<td>Stlačiteľnosť (deformácia)</td>\n<td>5 kPa</td>\n</tr>\n<tr>\n<td>Odtrhnuteľnosť (poškodenie)</td>\n<td>17 kPa</td>\n</tr>\n<tr>\n<td>Absorbcia vody</td>\n<td>47.9% celkového objemu</td>\n</tr>\n<tr>\n<td>Faktor dufúzneho odporu</td>\n<td>5575 ng/oa.s.m2 pri 25 mm</td>\n</tr>\n<tr>\n<td>Vzduchopriepustnosť pri 75Pa</td>\n<td>0.04L/s.m2 pri 25mm</td>\n</tr>\n</tbody>\n</table>', 'Sealection 500, striekaná pena, izolácie, izolačné materiály, nástreková izolačná pena, striekaná penová izolácia, izolácia stropov, izolácia pivníc, izolácia podkroví, tepelná izolácia', 42, '', 's', 1, 2, 1, 0, '2013-05-31 00:00:00', 1, 0, 0, 7),
(5, 'MULTIGUM', 'MULTIGUM', '2013-05-31 12:41:17', '2013-07-02 13:44:24', 'Multigum je hydroizolačný materiál, ktorý sa používa ako izolácia a hydroizolácia strechy, plochej strechy, balkónov, kúpeľní, kolektorov a bazénov.', '<p><img style="display: block; margin-left: auto; margin-right: auto;" title="penove izolacie multigum logo" src="files/articles/5/penove-izolacie-multigum-logo.jpg" alt="penove izolacie multigum" width="660" height="120" /></p>\n<p style="text-align: justify;"><img style="float: right; margin-top: 0px; margin-bottom: 0px; margin-left: 20px; margin-right: 20px;" title="striekané penové izolácie multigum aplikácia" src="files/articles/5/multigum_002.png" alt="striekané penové izolácie multigum aplikácia" width="250" height="300" />Je to hydroizolačný materiál, ktorý funguje na polymérnej báze. Používa sa na ako izolácia a hydroizolácia strechy, plochej strechy, balkónov, kúpeľní, kolektorov a bazénov.</p>\n<p style="text-align: justify;">Multigum je taktiež vhodný ako ochrana pred ultrafialovým žiarením a na ochranu PUR striech. Je aplikovaný pomocou nástrekovej technológie bezvzduchovým nástrekom, štetcom alebo valčekom.</p>\n<p style="text-align: justify;"> </p>\n<p style="text-align: justify;"> </p>\n<p style="text-align: justify;"> </p>\n<p style="text-align: justify;"><strong>Základné vlastnosti MULTIGUMU:</strong></p>\n<ul>\n<li style="text-align: justify;"><img style="float: right; margin-top: 0px; margin-bottom: 0px; margin-left: 20px; margin-right: 20px;" title="striekané penové izolácie multigum vzorka" src="files/articles/5/multigum_001.png" alt="striekané penové izolácie multigum vzorka" width="250" height="300" /><strong>Vyrovnanosť</strong> - výsledkom je homogénna membrána</li>\n<li style="text-align: justify;"><strong>Pružnosť</strong> - má neobvyklú pružnosť vďaka obsahu latexu</li>\n<li style="text-align: justify;"><strong>Tepelná odolnosť</strong> - vlastnosti sú zachované pri širokom tepelnom rozmedzí </li>\n<li style="text-align: justify;"><strong>Vysoká produktivita</strong>- možno pokryť až 800m2 za 8 hodín</li>\n<li style="text-align: justify;"><strong>Bezpečnosť</strong> - netoxický, ekologicky nezávadný izolačný materiál </li>\n<li style="text-align: justify;"><strong>Ekologickosť</strong> - zdraviu absolútne nezávadný materiál, riediteľný vodou</li>\n</ul>\n<p><br /><strong>Technické informácie</strong></p>\n<table style="width: 90%;" border="0" rules="all" cellpadding="5">\n<tbody>\n<tr>\n<td>Merná hmotnosť</td>\n<td>1350 kg/ m3</td>\n</tr>\n<tr>\n<td>Pevnosť ťahu pri Fmax</td>\n<td>3,0 MPa</td>\n</tr>\n<tr>\n<td>Priľnavosť k podkladu</td>\n<td>0,9 MPa</td>\n</tr>\n<tr>\n<td>Zachovanie flexibility</td>\n<td>Pri -30 ˚C</td>\n</tr>\n<tr>\n<td>Trieda reakcie na oheň</td>\n<td>E</td>\n</tr>\n<tr>\n<td>Vodotesnosť</td>\n<td>0,0 l/m2</td>\n</tr>\n<tr>\n<td>Faktor difúzneho odporu</td>\n<td>4517</td>\n</tr>\n</tbody>\n</table>\n<p><a class="button" title="Certifikáty" href="c/6/O-nas.html#a/27/Certifikaty.html">Certifikáty</a></p>', 'hydroizolačný materiál, Multigum, hydroizolácia, izolácia, izolácia strechy, izolácia balkónov, izolácia kúpeľní, izolácia bazénov, izolácia plochej strechy, izolačná membrána, izolačný materiál', 48, '', 's', 1, 2, 1, 0, '2013-05-31 00:00:00', 1, 0, 0, 4),
(6, 'Architekti-a-projektanti', 'Architekti a projektanti', '2013-05-31 12:47:05', '2013-06-11 17:25:45', 'Striekané penové izolácie a ich využitie pri komerčných stavbách a projektoch. Striekaná penová izolácia je izolačný materiál vhodný pre projektantov a architektov ako izolácia komerčných aj súkromných stavieb.', '<p>Pre odbornú verejnosť máme v tejto sekcii pripravené rôzne vzorové rezy, pri ktorých boli použité naše striekané penové izolácie ako aj rôzne tepelno-technické prepočty s potrebnými údajmi. Pre urýchlenie výberu, ktorá striekaná penová izolácia pre pre Vás najvýhodnejšia, si dobre prezrite náš intuitívny domček (sekcia Výber izolácie), ktorý Vám poskytne viac informácií a pomôže tak vybrať správnu nástrekovú izoláciu pre Váš pripravovaný projekt prípadne už realizovanú stavbu.</p>\n<p>Pre dodatočné odborné informácie nás prosím kontaktujte telefonicky alebo formou emailu. Naši odborní pracovníci Vám vždy radi pomôžu.</p>', 'izolácie, striekané penové izolácie, striekaná penová izolácia, najvýhodnejšia izolácia', 3, '', 's', 1, 2, 1, 0, '2013-05-31 00:00:00', 1, 0, 0, 58),
(7, 'Sukromni-investori', 'Súkromní investori', '2013-05-31 13:15:47', '2013-06-11 17:24:33', 'Striekané penové izolácie a ich využitie a výhody pre súkromných investorov.', '<p>Staviate dom, chatu prípadne rekonštruujete? Záleží Vám na najlepšom výbere izolácie a hľadáte potrebné informácie? Zaujímate sa o striekané penové izolácie? Táto sekcia je určená práve Vám. Náš intuitívny domček Vás navedie na správne zvolenie a druh izolácie, tak aby Vaša stavba bola správne izolovaná a vy, aby ste sa mohli tešiť z komfortu bývania a ušetrených peňazí na energiách.</p>\n<p>Pre dodatočné informácie nás prosím kontaktujte telefonicky alebo emailom v priloženom formulári.</p>', 'izolácie, striekané penové izolácie, penové izolácie', 2, '', 's', 1, 2, 1, 0, '2013-05-31 00:00:00', 1, 0, 0, 43),
(8, 'Aplikatori-a-dealeri', 'Aplikátori a dealeri', '2013-05-31 13:16:11', '2013-06-11 17:26:40', 'Striekané penové izolácie a ich výhody pre aplikátorov a dealerov. Podrobné informácie a propagačné materiály pre produkt striekaná penová izolácia.', '<p>Táto sekcia je určená existujúcim aplikátorom a obchodným zástupcom pre striekané penové izolácie. Nájdete tu potrebné informácie a podklady pre správne fungovanie Vášho aplikačného zariadenia, podklady pre správnu aplikáciu pien, marketingové a propagačné materiály, v ktorých je podrobne a prehľadne vysvetlené, aké výhody Vám môže priniesť naša striekaná penová izolácia a podnikanie v tomto odvetví. Záujemcom o prípadnú spoluprácu ponúkame možnosť spolupráce formou certifikovaného aplikátora, prípadne certifikovaného obchodného zástupcu. Pre bližšie informácie nás prosím kontaktujte.</p>', 'izolácie, striekané penové izolácie, striekaná penová izolácia, izolácia, penová izolácia, penové izolácie', 4, '', 's', 1, 2, 1, 0, '2013-05-31 00:00:00', 1, 0, 0, 21),
(9, 'O-Demilec-USA', 'O Demilec USA', '2013-05-31 13:24:45', '2013-06-12 14:26:23', 'Spoločnosť Demilec USA pôsobí na svetovom trhu pre polyuretánové striekané penové izolácie a polyureu bezmála 30 rokov. Hlavným produktom je striekaná penová izolácia. ', '<p>Spoločnosť Demilec USA pôsobí na svetovom trhu pre polyuretánové striekané penové izolácie a polyureu bezmála 30 rokov. Táto spoločnosť si za túto dobu vybudovala silné trhové postavenie na trhu v USA a Kanade. Za všetko hovoria čísla: Trhový podiel 40%, čo ju radí na prvé miesto spomedzi 33 výrobcov v USA, ktorých hlavným produktom je striekaná penová izolácia. Viac ako 65 odberných miest v rámci celého sveta, viac ako 2000 certifikovaných aplikátorských spoločností a cez 30 exkluzívnych distribútorov mimo USA. Za 30 rokov praxe Demilec USA uspokojilo viac ako 2,5 milióna zákazníkov na celom svete. Poslaním Demilecu USA je neustále inovovať a prinášať zákazníkom perfektný produkt s hlavným zreteľom na znižovanie energetickej náročnosti budov.</p>', 'izolácie, striekané penové izolácie, striekaná penová izolácia, izolácia, penové izolácie, penová izolácia, polyuretánové izolácie', 34, '', 's', 1, 2, 1, 0, '2013-05-31 00:00:00', 1, 0, 0, 2),
(10, 'O-nas', 'O nás', '2013-06-03 11:04:22', '2013-06-12 14:22:37', 'Saveenergy s.r.o. distribuuje a aplikuje striekané penové izolácie na Slovensku a v Čechách, pričom používa svetovo najkvalitnejšie izolačné materiály a striekané peny od spoločnosti Demilec. Kvalitná striekaná penová izolácia a úzka spolupráca s viacerými spoločnosťami sú našim veľkým benefitom pre Vás.', '<p>Sme už viac ako 6 rokov úspešne pôsobiaca spoločnosť, ktorá distribuuje a aplikuje striekané penové izolácie na Slovensku a v Čechách a snaží sa tak svojim zákazníkom prinášať reálnu úsporu na energiách bez kompromisov. Toto poslanie sme schopní plniť do bodky vďaka tomu, že používame svetovo najkvalitnejšie izolačné materiály a striekané peny od spoločnosti Demilec USA (LLC). Oproti ostatným firmám na trhu sa snažíme neustále vylepšovať, inovovať a prinášať pridaný benefit pre zákazníka. Výsledkom týchto benefitov nie je len kvalitná striekaná penová izolácia, ale tiež úzka spolupráca s viacerými spoločnosťami, ktoré toto motto dopĺňajú.</p>\n<p>Energo Zóna – Magna E.A. – Rýchle, jednoduché a celistvé nastavenie spotreby energií pri porovnaní taríf konkurencie, výberom alternatívneho dodávateľa elektriny či plynu. Ušetríte až 500 EUR ročne.</p>\n<p>Prvá stavebná sporiteľna – vybavenie stavebného úveru na počkanie! Spracovateľský poplatok zaplatíme za Vás!<br />Termovízie a termovízne merania únikov tepla z budov.</p>', 'izolácie, striekané penové izolácie, striekaná penová izolácia, izolácia, izolačné materiály, striekané peny, penové izolácie, penová izolácia', 6, '', 's', 1, 2, 1, 0, '2013-06-03 00:00:00', 1, 0, 0, 24),
(11, 'Referencie', 'Referencie', '2013-06-03 11:05:42', '2013-06-12 10:25:19', 'Za šesť rokov pôsobenia sme my a naši partneri úspešne zaizolovali viac ako 1000 projektov na Slovensku a v Čechách. Nižšie je výber z niekoľkých projektov realizovaných našou spoločnosťou.', '<p>Referencie</p>', 'projektov, referencie', 35, '', 's', 1, 2, 1, 0, '2013-06-03 00:00:00', 1, 0, 0, 12),
(12, 'V-com-sme-lepsi-', 'V čom sme lepší?', '2013-06-03 14:35:43', '2013-06-03 14:36:03', 'Saveenergy je autorizovaný distribútor pre striekané penové izolácie a izolačné materiály od Demilec USA. Ekologické penové izolácie, ochranné nátery a kvalitné izolačné materiály.', '<p>Spoločnosť <strong>Demilec</strong> je už vyše štvrť storočia svetovým priekopníkom pre striekané penové izolácie a ekologické izolačné materiály. Vyvíja striekané peny, ktorých cieľom je uspokojiť rastúci dopyt po energetickej úspornosti. Výrobné závody v Texase neustále zdokonaľujú nové produkty, ktoré by mali znížiť dopyt po fosílnych palivách.</p>\n<p><strong>SaveEnergy® s.r.o.</strong> je výhradným licencovaným zástupcom spoločnosti Demilec pre Slovenskú a Českú republiku. Súčasťou nášho tímu sú kvalifikovaní aplikátori, lektori a školitelia, ktorí boli školení priamo v spoločnosti Demilec.</p>\n<p><strong>Prečo sa teda rozhodnúť pre nás?</strong></p>\n<ul>\n<li>Máme viac ako 25-ročné skúsenosti a profesionálny tím, takže s nami neriskujete nekvalitnú izoláciu!</li>\n<li>Naši špeciálne školení aplikátori a dealeri sú Vám vždy nablízku – nájdete ich takmer v každom kraji na Slovensku aj v Čechách!</li>\n<li>Počas našich aplikácií sú vykonávané inšpekcie, pri ktorých sa kontroluje kvalita izolácie, čo zamedzuje prípadným zbytočným dodatočným vybavovaniam reklamácií.</li>\n<li>Naše striekané penové izolácie sú vysoko kvalitné, energeticky úsporné a zároveň ekologické, čo dokazujú aj mnohé ocenenia.</li>\n<li>Pre našich partnerov a aplikátorov poskytujeme profesionálne školenia.</li>\n<li>Svojich partnerov v plnej miere podporujeme aj v oblasti marketingu a reklamy.</li>\n<li>Poskytujeme aj projekčnú a architektonickú pomoc pri návrhoch zatepľovacích riešení.</li>\n<li>Naša energeticky úsporná striekaná izolácia výrazne šetrí Váš čas, životné prostredie a aj Vaše peniaze, ktoré sa Vám po jej aplikácií čoskoro vrátia, pretože dokáže znížiť náklady za energie až o 50%.</li>\n<li>Sme spoločnosť, ktorej na Vás záleži a preto robíme všetko pre Vašu spokojnosť.</li>\n</ul>', 'Demilec, striekané penové izolácie, izolačné materiály, penové izolácie, ekologické izolácie, striekaná izolácia', 0, '', 's', 1, 2, 1, 0, '2013-06-03 00:00:00', 1, 0, 0, 29),
(13, 'Striekane-penove-izolacie', 'Striekané penové izolácie', '2013-06-03 14:38:54', '2013-06-25 12:21:50', 'Striekané penové izolácie sú mäkké a tvrdé. Striekaná penová izolácia pôsobí ako hydroizolácia, vzduchová bariéra, zastrešenie, izolácia stien, stropov, podkroví, suterénov, potrubí, nádrží, chladiacich zariadení, mrazničiek, budov, lodí', '<p>Striekané penové izolácie sú plastické hmoty aplikované nástrekom. Sú nanášané v kvapalnej forme pomocou vysokotlakovej vyhrievanej hadice a nástrekovej pištole. Zmiešavajú sa v nich dve zložky, ktoré na povrchu pri určitej teplote okamžite reagujú, rozpínajú sa a tak tvoria výslednú hmotu. Striekané peny sa aplikujú technológiou, ktorú obsluhuje našou firmou odborne vyškolený personál. Polyuretánové peny majú rôzne fyzikálne vlastnosti, ktoré závisia od požadovaného použitia. Základné delenie je na mäkké (otvorená bunková štruktúra) a tvrdé, resp. stredné (uzavretá bunková štruktúra).</p>\n<p><img style="display: block; margin-left: auto; margin-right: auto;" src="files/articles/13/striekane_penove_izolacie.png" alt="" width="600" height="300" /></p>\n<p>Ich hlavné <span style="text-decoration: underline;">využitie</span> je ako:</p>\n<ul>\n<li>vzduchová bariéra,</li>\n<li>zastrešenie,</li>\n<li>rezidenčná a komerčná izolácia stien, stropov, suterénov a podkroví,</li>\n<li>priemyselná izolácia potrubí a nádrží, chladiacich zariadení, mrazničiek alebo klimatizovaných budov, zvýšenie pevnosti konštrukcie krídel v lietadlách, lodí, člnov, plávajúcich dokov, atď.</li>\n</ul>\n<p><span style="text-decoration: underline;">Výhody</span>, ktoré poskytuje striekaná penová izolácia:</p>\n<ul>\n<li>je <strong>šetrná k životnému prostrediu</strong>, pretože neobsahuje chemické látky, ktoré poškodzujú ozónovú vrstvu,</li>\n<li>zabezpečuje dobrú <strong>kvalitu vzduchu</strong>,</li>\n<li>vyžaduje <strong>málo energie</strong> v porovnaní s akoukoľvek inou izoláciou,</li>\n<li>je <strong>odolná</strong>, udržuje a <strong>nemení fyzikálne vlastnosti</strong>,</li>\n<li>v jednom produkte produkuje len <strong>málo odpadu</strong>,</li>\n<li><strong>znižuje spotrebu energie</strong> vďaka vysokej R hodnote, eliminácii infiltrácie vzduchu, kontrole vlhkosti a kondenzácie a účinnosti pri vysokých aj nízkych teplotách,</li>\n<li>uzavretá bunková štruktúra zabezpečuje <strong>pevnosť</strong>,</li>\n<li>nevyžaduje stavebné lepidlá a funguje tak ako <strong>výborná hydroizolácia</strong>,</li>\n<li>zvyšuje úroveň <strong>komfortu a zdravia</strong> a <strong>znižuje hluk</strong> z okolia.</li>\n</ul>', 'striekané penové izolácie, striekaná penová izolácia, striekané peny, polyuretánové peny, hydroizolácia, izolácia stien, izolácia stropov, izolácia podkroví, izolácia suterénov, izolácia potrubí, izolácia nádrží', 0, '', 's', 1, 2, 3, 0, '2013-06-03 00:00:00', 1, 0, 0, 19),
(14, 'Produkty-Demilec', 'Produkty Demilec', '2013-06-03 14:40:16', '2013-06-12 14:18:10', 'Demilec polyuretánové striekané penové izolácie sú: izolačná nástreková pena rady Heatlok Soy, Sealection 500 a tesniaci izolačný materiál Multigum. Demilec tepelné izolácie sú energeticky úsporné a zároveň ekologické.', '<p>Demilec USA vyrába celosvetovo najlepšie polyuretánové striekané penové izolácie. Sortiment výrobkov je neustále rozširovaný, pričom sa nezabúda na ochranu životného prostredia a na obnoviteľné a recyklované zdroje. Sú to ekologické izolácie, ktoré zabezpečujú pohodlie a šetria energiu v dome či budove.</p>\n<ul>\n<li><strong>Heatlok Soy®200</strong>- vysokoúčinná polyuretánová izolačná pena, ktorá má uzavretú bunkovú štruktúru. Dokáže znížiť náklady na energie o viac ako 50%. Vlajková loď medzi penami Demilec USA s ekologickým benefitom 1000 recyklovaných plastových fliaš na 200 m2 izolovanej plochy, pridaním obnoviteľných zdrojov vo forme sójových olejov a vody. <strong>Žiaden materiál na svete nedokáže to, čo Heatlok SOY 200 – 88,6 % redukcia prestupu/úniku tepla už pri 2,5 cm a až 94,0 % už pri 5 cm hrúbky izolácie.</strong>V jedinej aplikácií sa tu spája tepelná izolácia, vzduchová izolácia, hydroizolácia a parozábrana.<br /><a class="button" title="Heatlok Soy®200" href="c/7/Produkty.html#a/3/Heatlok-Soy-200.html">Viac</a></li>\n</ul>\n<p><img style="display: block; margin-left: auto; margin-right: auto; width: 90%; height: auto;" title="Redukcia úniku tepla - Heatlok Soy 200" src="files/articles/14/redukcia-uniku-tepla-heatlok-soy.jpg" alt="Redukcia úniku tepla - Heatlok Soy 200" width="800" height="458" /></p>\n<ul>\n<li><strong>Heatlok Soy Roofing foam</strong>- špeciálna nástreková pena na ploché strechy. Na izoláciu postačuje vrstva, ktorá je neporovnateľne tenšia ako pri napr. polystyréne alebo kamennej vlne.<br /><a class="button" title="Heatlok Soy Roofing foam" href="c/7/Produkty.html#a/21/Heatlok-Soy-Roofing-foam.html">Viac</a></li>\n<li><strong>SEALECTION® 500</strong>- jedna z najpokrokovejších tepelných izolácií určená pre interiér stavieb. Je to striekaná penová izolácia s otvorenou bunkovou štruktúrou a nízkou hustotou. Šetrí energiu a zabezpečuje zdravšie a tichšie prostredie. 90% redukcia úniku tepla už pri 5 cm hrúbky a až 94 % pri 10 cm. Nami odporúčaná minimálna hrúbka 15 cm nástreku zabezpečí až 96,4 % redukciu úniku tepla!<br /><a class="button" title="SEALECTION® 500" href="c/7/Produkty.html#a/4/SEALECTION-500.html">Viac</a></li>\n</ul>\n<p><img style="display: block; margin-left: auto; margin-right: auto; width: 90%; height: auto;" title="Redukcia úniku tepla - Sealection 500" src="files/articles/14/redukcia-uniku-tepla-sealection-500.jpg" alt="Redukcia úniku tepla - Sealection 500" width="800" height="451" /></p>\n<ul>\n<li><strong>SEALECTION Agribalance</strong> – Pokiaľ zvažujete použitie peny Sealection 500 pre Vašu stavbu a chcete mať dobrý pocit z ekologického benefitu voči Zemi, potom Sealection Agribalance s 10%-ným podielom obnoviteľného ricínového oleja je tá správna voľba pre Vás. Tepelnoizolačné vlastnosti sú rovnaké ako pri striekanej pene Sealection 500.<br /><a class="button" title="SEALECTION Agribalance" href="c/7/Produkty.html#a/22/SEALECTION-Agribalance.html">Viac</a></li>\n<li><strong>DEMILEC APX</strong> – najnovšia mäkká striekaná penová izolácia od Demilec, evolučný vrchol 15 rokov overeného Sealection 500 s viac ako trojročným laboratórnym výskumom Demilec USA. Táto striekaná pena spája vzduchotesnú bariéru a nízku horľavosť bez použitia protipožiarneho nástreku v jedinej aplikácií.<br /><a class="button" title="DEMILEC APX" href="c/7/Produkty.html#a/23/DEMILEC-APX.html">Viac</a></li>\n<li><strong>Sealection 500 PIP</strong> – mäkká dutinová izolačná pena je vhodná do priestorov, kde sú už vykonané stavebné práce a kde majiteľ nechce „otvárať“ stenu prípadne strop, ale chce mať perfektne fungujúcu izoláciu. Perfektná a 100%ne fungujúca alternatíva ku klasickým fúkaným celulózam, prípadne minerálnym vatám. Pre viac informácií ohľadom tohto typu zateplenia nás prosím kontaktujte.<br /><a class="button" title="Sealection 500 PIP" href="c/7/Produkty.html#a/24/Sealection-500-PIP.html">Viac</a></li>\n<li><strong>ECO-PUR PIP</strong> – tvrdá dutinová nástreková pena použiteľná tam, kde ostatné izolácie nie sú vhodné alebo použiteľné. Táto penová izolácia sa používa ako izolácia dutín, panelov a priestorov s komplikovaným prístupom.<br /><a class="button" title="ECO-PUR PIP" href="c/7/Produkty.html#a/25/ECO-PUR-PIP.html">Viac</a></li>\n<li><strong>SLAM 210</strong> – vysokoúčinná polyurea aplikovateľná tam, kde klasické „lepenky“ a fólie zlyhávajú. Jednoliaty celistvý nástrek, bez akýchkoľvek spojov alebo prestupov. Odolný voči vode, kyselinám, chemikáliám, radónu. Chráni všetky zaizolované povrchy od dreva, betónu, cez železo, meď, zinok až po penu. Výborná izolácia proti podzemnej vode, na základy a základové dosky, fermentory, mostné konštrukcie. SLAM 210 spája povrch, vypĺňa škáry a nevadí mu ani špinavý, mokrý či hrdzavý povrch.<br /><a class="button" title="SLAM 210" href="c/7/Produkty.html#a/26/SLAM-210.html">Viac</a></li>\n<li><strong>Multigum</strong>- tesniaci izolačný materiál proti vode na polymérnej báze. Je aplikovaný pomocou nástrekovej technológie bezvzduchovým nástrekom, štetcom alebo valčekom.<br /><a class="button" title="Multigum" href="c/7/Produkty.html#a/5/Multigum.html">Viac</a></li>\n</ul>\n<p>Viac o produktoch si môžete prečítať v sekcií produktov <a class="button" title="Produkty" href="c/7/Produkty.html">Produkty</a></p>', 'demilec, striekané penové izolácie, izolačná pena, polyuretánová pena, nástreková pena, striekaná penová izolácia, tepelná izolácia, izolačný materiál, polyuretánové izolácie, Multigum, Heatlok Soy, Sealection', 0, '', 's', 1, 2, 1, 0, '2013-06-03 00:00:00', 1, 0, 0, 47),
(15, 'Otazky-a-odpovede', 'Otázky a odpovede', '2013-06-03 15:42:43', '2013-06-03 15:42:43', 'Striekané polyuretánové penové izolácie môžu byť mäkké a tvrdé. Penová izolácia rady Heatlok Soy a Sealection pôsobí ako dokonalá hydroizolácia, je energeticky úsporná a ekologická.', '<ul>\n<li><strong>Čo je polyuretánová striekaná penová izolácia? Ako funguje?</strong></li>\n</ul>\n<p>Striekané penové izolácie sú plastické hmoty aplikované nástrekovou aplikačnou technológiu, ktorá je obsluhovaná profesionálne školeným personálom. Polyuretánové striekané peny majú fyzikálne vlastnosti, ktoré závisia od požadovaného použitia. Rozširujú tak sortiment pien, a vytvárajú širokú škálu ich využitia. Delia sa na mäkké (otvorená bunková štruktúra) a tvrdé, resp. stredne tvrdé peny (uzavretá bunková štruktúra).</p>\n<ul>\n<li><strong>Je lepšia tvrdá alebo mäkká pena?</strong></li>\n</ul>\n<p>Pre obidve existuje množstvo výhod v porovnaní s bežne používanými izoláciami: dokonale tesnia, sú schopné vyplniť každú škáru a kopírovať povrch. Tvrdá pena však poskytuje takmer raz také tepelnoizolačné vlastnosti a dá sa používať aj v exteriéroch, pretože výrazne zvyšuje torznú odolnosť konštrukcií a pôsobí ako dokonalá hydroizolácia.</p>\n<ul>\n<li><strong>Dokážu budovy „dýchať" aj napriek tomu že tvrdé peny neprepúšťajú vlhkosť?</strong></li>\n</ul>\n<p>Áno, ich štruktúra na rozdiel od celulózy a minerálnej vlny umožňuje difúziu vodných pár, vďaka čomu budova môže „dýchať". Voda v kvapalnom skupenstve má väčšie častice a cez štruktúru tvrdej peny sa nedokáže dostať.</p>\n<ul>\n<li><strong>Nezníži sa mi kvalita vzduchu?</strong></li>\n</ul>\n<p>Pokiaľ budete správne vetrať, tak nie. Pred zaizolovaním vetranie často nie je potrebné,keďže okná, steny, konštrukcie a krovy netesnia a aj zatvorené prepúšťajú vzduch.</p>\n<ul>\n<li><strong>Ušetrím financie?</strong></li>\n</ul>\n<p>Áno, dokonca dvakrát. Najprv pri samotnej realizácii a druhý krát pri prevádzke objektu. Striekaná izolácia Sealection 500 a Heatlok Soy dokáže znížiť náklady na energiu až o 50%.</p>\n<ul>\n<li><strong>Môže sa polyuretánova pena aplikovať priamo na elektrické rozvody alebo bodové osvetlenie?</strong></li>\n</ul>\n<p>Áno. Pri zapustených svetlách je nutná aspon 5-7 cm medzera.</p>\n<ul>\n<li><strong>Môžem si izoláciu aplikovať sám?</strong></li>\n</ul>\n<p>Nie. Nástreková izolácia vyžaduje komplexné technologické vybavenie a personál, ktorí je odborne zaškolený. To je dôvod, prečo máme široké zastúpenie po celom Slovensku aj po celých Čechách.</p>\n<ul>\n<li><strong>Môže sa polyuretánova striekaná pena aplikovať aj v zime?</strong></li>\n</ul>\n<p>So špecifickými zložením peny Heatlok Soy Winter Foam áno. Je to možné až do -10 ˚C. V prípade, že teplota klesne pod -10 ˚C je možná aplikácia nástrekovej peny zo zakrytého lešenia, do ktorého sa vháňa teplý vzduch z vyhrievacieho dela. Je to však náročnejšie na čas aj financie.</p>', 'striekaná penová izolácia,striekané penové izolácie,hydroizolácia,tepelná izolácia,polyuretánová striekaná pena,nástrekove peny,demilec izolácia,nástreková izolácia', 14, '', 's', 1, 2, 1, 0, '2013-06-03 00:00:00', 1, 0, 0, 5),
(16, 'Sluzby', 'Služby', '2013-06-10 12:57:28', '2013-06-11 17:35:04', 'Saveenergy Vám ponúka striekané penové izolácie a služby ako: školenia pre partnerov, technická podpora a medzinárodná doprava v oblasti podnikania s polyuretánovými izolačnými systémami.', '<p><strong>Architektonicko-projekčné</strong><br />Saveenergy spolupracuje s autorizovanými architektmi a projektantmi. Vďaka tomu umožňujeme poskytovanie poradenstva a služieb aj v týchto oblastiach. Dokážeme Vám vypracovať optimálne riešenie pre Váš projekt a skonzultujeme s Vami technické problémy, s ktorými si neviete poradiť. Zabezpečujeme aj projekčné práce.</p>\n<p><strong>Školenia pre certifikovaných partnerov</strong><br />Naše školenia pomôžu pripraviť na zahájenie podnikania v oblasti polyuretánových izolačných systémov.</p>\n<p><strong>Technická podpora a konzultácie</strong><br />Poskytujeme partnerom plnú technickú podporu ako aj pomoc pri aplikácii našimi skúsenými pracovníkmi.</p>\n<p><strong>Medzinárodná doprava</strong><br />Zabezpečujeme dopravu v rámci ČR a SR.<br /><br />Pre viac informácií o našich školeniach a službách nás neváhajte kontaktovať buď telefonicky alebo mailom.</p>', 'saveenergy, striekaná penová izolácia, poradenstvo, školenia, polyuretánové systémy, technická podpora, izolácia stavieb, izolácia budov', 0, '', 's', 1, 2, 1, 0, '2013-06-10 00:00:00', 1, 0, 0, 4),
(17, 'Produkty', 'Produkty', '2013-06-10 14:47:40', '2013-06-10 14:47:40', 'Spoločnosť Demilec vyrába ekologické a energeticky úsporné striekané penové izolácie a izolačné materiály rady Sealection a Heatlok Soy, ktoré pôsobia ako hydroizolácia a tepelná izolácia a rovnako ako izolačné materiály Multigum a Slam sa využívajú ako izolácia podlahy, strechy, podkrovia, základov a pod.', '<p>Spoločnosť Demilec USA je celosvetovo známa a uznávaná pre svoje ekologické a energeticky úsporné striekané penové izolácie a izolačné materiály. Striekané peny rady Sealection a Heatlok Soy sa vyrábajú s recyklovaných materiálov, sú vysoko kvalitné a pôsobia ako hydroizolácia, tepelná izolácia a parozábrana v jednom. Polyurea SLAM U210 a izolačný ochranný náter Multigum od spoločnosti Bitum sa tiež využívajú na izoláciu stavieb a budov a sú vysoko kvalitné, preto ich nájdete aj v našej ponuke. Aplikáciu striekanej penovej izolácie od spoločnosti Demilec a Bitum na Slovensku a v Čechách vykonávajú iba spoločnosťou Saveenergy s.r.o. špecialne školení aplikátori s použitím našich špeciálnych aplikačných zariadení.<br /><br />Striekaná penová izolácia má využitie na viacerých miestach. Bežne sa využíva ako izolácia základov, podlahy, stien, obvodového plášťa, potrubí, podkrovia, terás, balkónov, strechy, plochej strechy, bazénov, ale aj áut, chladiacich zariadení či dokonca lodí a lietadiel.</p>', 'striekaná penová izolácia, striekané penové izolácie, izolačné materiály, Sealection, Heatlok Soy, Demilec, Multigum, Slam, polyurea, hydroizolácia, tepelná izolácia, striekané peny', 7, '', 's', 1, 2, 1, 0, '2013-06-10 00:00:00', 1, 0, 0, 28),
(18, 'Druh-stavby', 'Druh stavby', '2013-06-10 14:53:00', '2013-06-10 14:53:00', 'Striekané penové izolácie  Heatlok Soy a Sealection od Demilec a izolačné materiály Multigum a Slam sa využívajú ako izolácia strechy, základov, podlahy, stien, obvodového plášťa, podkrovia, potrubí, lodí, áut. Striekaná penová izolácia je hydroizolácia, tepelná izolácia a barozábrana v jednom.', '<p>Základné delenie na :<br /><br /><strong>Obytné</strong></p>\n<ul>\n<li>Rodinné domy</li>\n<li>Vily</li>\n<li>Bytové domy</li>\n<li>„Hausbóty“</li>\n</ul>\n<p><strong>Obchodné/Priemyselné</strong></p>\n<ul>\n<li>Polyfunkčné budovy</li>\n<li>Administratívne budovy</li>\n<li>Haly</li>\n<li>Sklady</li>\n</ul>\n<p><strong>Poľnohospodárske budovy</strong></p>\n<ul>\n<li>Hydinárske farmy</li>\n<li>Kravské farmy</li>\n<li>Prasacie farmy</li>\n<li>Fermentory na kompost prípadne iné kyseliny na výrobu plynov a tepla</li>\n<li>Sklenníky</li>\n<li>Hubové farmy</li>\n</ul>', 'striekané penové izolácie, striekaná penová izolácia, izolačné materiály demilec, izolácia Sealection, izolácia Heatlok Soy, Multigum, Slam, striekaná pena, izolácia stavieb, izolácia komerčných stavieb', 22, '', 's', 1, 2, 1, 0, '2013-06-10 00:00:00', 1, 0, 0, 5),
(19, 'Ponuka-spoluprace', 'Ponuka spolupráce', '2013-06-11 16:02:44', '2013-06-11 16:04:38', 'Saveenergy Vám ponúka kvalitné izolačné materiály a striekané penové izolácie, certifikované školenia, zákaznícku podporu a kompletné inžinierske a architektonické služby.', '<p><strong>STAŇTE SA AJ VY NAŠÍM PARTNEROM</strong></p>\n<p>Neustále zvyšovanie cien za energie vedie mnohých ľudí k hľadaniu znižovania energetickej náročnosti. To ich často privádza k zatepľovaniu a izolácií stavieb a budov. Ktorá izolácia je však najefektívnejšia? Riešenie tejto otázky Vám prináša naša striekaná penová izolácia. Ako nášmu certifikovanému partnerovi Vám ponúkame systémové a vysokoefektívne riešenia. S vyškoleným tímom pracovníkov Vám pomôžeme pri prvých krokoch v modernom a progresívnom projekte.<br /><br /><strong>Čo ponúkame?</strong></p>\n<ul>\n<li>Certifikované školenia</li>\n<li>Nástrekovú technológiu za zvýhodnené podmienky</li>\n<li>Podporu pri získavaní zákazníkov</li>\n<li>Vysoko kvalitné izolačné materiály a striekané penové izolácie</li>\n<li>Servisnú a tiež technickú podporu</li>\n<li>Marketingovú podporu</li>\n<li>Architektonický a projekčný tím</li>\n<li>Kompletné inžinierske služby</li>\n</ul>\n<p><strong>Čo môžete očakávať?</strong></p>\n<ul>\n<li>Rozvíjajúce a expandujúce odvetvie</li>\n<li>Stále sa zvyšujúci dopyt po striekaných izoláciách</li>\n<li>Izolačné materiály budúcnosti</li>\n<li>Rýchlu tvorbu zisku</li>\n</ul>\n<p>Zaujala Vás naša ponuka a chcete vedieť viac? Neváhajte nás kontaktovať buď telefonicky alebo mailom. <a class="button" title="Kontakty" href="contact/kontaktny-formular.html">Kontakty</a></p>', 'izolácia, školenia, izolačné materiály, striekané penové izolácie, striekaná penová izolácia', 20, '', 's', 1, 2, 1, 0, '2013-06-11 00:00:00', 1, 0, 0, 3),
(20, 'Dodavatelia-a-aplikatori', 'Dodávatelia a aplikátori', '2013-06-11 16:11:17', '2013-06-11 16:11:17', 'Aplikátori pre striekané penové izolácie a izolačné materiály od spoločnosti Demilec na Slovensku a v Čechách. Striekaná penová izolácia a jej dodávatelia.', '<p>Aplikátori školení spoločnosťou Saveenergy s.r.o. pre striekané penové izolácie a izolačné materiály od spoločnosti Demilec na Slovensku a v Čechách. Striekaná penová izolácia a jej dodávatelia. Kliknite na konkrétny kraj a nájdite najbližšieho kvalifikovaného dodávateľa či aplikátora.</p>', 'striekané penové izolácie, striekaná penová izolácia, izolačné materiály demilec, saveenergy, aplikátori izolácie', 10, '', 's', 1, 2, 1, 0, '2013-06-11 00:00:00', 1, 0, 0, 8);
INSERT INTO `SPI_ARTICLES` (`id`, `alias`, `article_title`, `article_createDate`, `article_changeDate`, `article_prologue`, `article_content`, `keywords`, `id_menucategory`, `author`, `layout`, `lang`, `added_by`, `updated_by`, `showsocials`, `publish_date`, `published`, `topped`, `homepage`, `viewcount`) VALUES
(21, 'Heatlok-Soy-Roofing-foam', 'Heatlok Soy Roofing foam', '2013-06-11 17:08:08', '2013-06-13 11:58:32', 'Striekaná izolačná pena vhodná na ploché a šikmé strechy, neprepúšťa vodu, perfektne tesní a rýchlo sa aplikuje.', '<p><img style="display: block; margin-left: auto; margin-right: auto;" title="penove izolacie heatlok soy roofing foam logo" src="files/articles/21/penove-izolacie-heatlok-soy-roofing-foam-logo.jpg" alt="penove izolacie heatlok soy roofing foam" width="660" height="120" /></p>\n<p>Jedným z mnohých využití pre nástrekovú izoláciu <strong>Heatlok Soy</strong> od americkej spoločnosti <strong>Demilec USA</strong> sú ploché strechy v špecifikácii nástrekovej peny <strong>Heatlok Soy Roofing foam</strong>. Izolácia sa aplikuje na akýkoľvek suchý odprašnený povrch pomocou nástrekovej pištole napojenej cez hadicu k špeciálnej aplikačnej technológii. Pena po dopade na povrch ihneď reaguje, expanduje a po chvíli zatvrdne. <strong>Pena je po zatvrdnutí plne pochôdzna, preto je ideálna na ploché strechy.</strong></p>\n<p><img style="display: block; margin-left: auto; margin-right: auto;" title="Heatlok Soy Roofing Foam" src="files/articles/21/heatlok-soy-roofing-foam.png" alt="Heatlok Soy Roofing Foam" width="578" height="233" /></p>\n<p>Má uzavretú bunkovú štruktúru, ktorá ju činí <strong>hydroizolačnou a nenasiakavou</strong>. Po nástreku sa povrch upraví náterom proti UV žiareniu, príp. mechanickému poškodeniu. Je vhodná na rekonštrukcie plochých striech, ako aj na novostavby. <strong>Na izoláciu je postačujúca vrstva takmer o polovicu tenšia ako v prípade napr. polystyrénu alebo kamennej vlny</strong>. Je to možné vďaka nízkemu súčiniteľu tepelnej vodivosti (λ) polyuretánov, iba 0,02 (W/m K).</p>\n<p>Náš strešný systém pozostáva z dvoch komponentov, ktoré sú aplikované na mieste. Tvorí ho polyuretánová pena so zvýšenou hustotou, ktorá je opatrená ochranným náterom proti UV. Tento systém má množstvo výhod oproti konvenčným strešným systémom.<br /><br /></p>\n<ul>\n<li>Tepelnoizolačné vlastnosti sú <strong>dva až trikrát vyššie</strong> ako má väčšina štandardne používaných materiálov pri rovnakej hrúbke</li>\n<li>Jednoduchý, bezspojový, monolitický obal prikryje aj strechy s nepravidelným, príp. zakriveným povrchom, ktorých izolácia je s bežnými materiálmi nemysliteľná</li>\n<li><strong>Rýchla aplikácia</strong>, tri až štyrikrát rýchlejšia ako iné strešné systémy</li>\n<li>Nízka hmotnosť, staticky nezaťažuje strešnú konštrukciu</li>\n<li>Nie je nutné odstrániť pôvodnú skladbu strechy pri rekonštrukciách a opravách, v závislosti od stavu existujúcej strechy</li>\n<li><strong>Perfektne kopíruje</strong> spádové vrstvy</li>\n</ul>\n<p><br /> <strong>Ukážka detailov v špecifických prípadoch</strong></p>\n<p><img style="display: block; margin-left: auto; margin-right: auto;" title="Ukážka detailov v špecifických prípadoch" src="files/articles/21/detaily-rekonstrukcie-plochej-strechy.png" alt="Ukážka detailov v špecifických prípadoch" width="568" height="457" /><br /><br /> <strong>Technické informácie</strong></p>\n<p>Či už rekonštrukcia alebo stavba novej plochej strechy je vždy háklivá záležitosť, a preto vyžaduje zvýšenú pozornosť a technickú zdatnosť.<br /><br /> Pred rekonštrukciou existujucich striech:</p>\n<ul>\n<li>Je nutné vykonať inšpekciu, termografiu, sondy, zistiť príp. zabudovanú vlhkosť a posúdiť skutkový stav strechy</li>\n<li>Povrch musí byť odprašnený a vyčistený od štrku príp. machu, aby sa dosiahla čo najvyšsia priľnavosť</li>\n</ul>\n<p><br /> <strong>Vietor</strong></p>\n<p>Pri spávnej aplikácii polyuretánovej peny je podfúknutie vylúčené, dokonca priľnavosť peny je jednou z jej najväčších výhod, v porovnaní s inými materiálmi, ktoré treba pracne ukotvovať, prípadne lepiť.</p>\n<p><strong>Príprava povrchu</strong></p>\n<p>Strešný systém <strong>Heatlok Soy Roofing Foam</strong> môže byť aplikovaný na množstvo povrchov, či už sa jedná o novú konštrukciu alebo rekonštrukciu.<br /><br /> Pri novostavbách može byť pena aplikovaná na liaty betón, prefabrikovaný betón, plech alebo drevo.</p>\n<p>Pri rekonštrukcii je možné penu aplikovať na pôvodné vrstvy, alebo na strechu vyčistenú od pôvodných skladieb. V oboch prípadoch musí byť povrch dokonale suchý.</p>\n<p><strong>Ochranná vrstva</strong></p>\n<p>Polyuretánová pena je druh izolácie, ktorý pri priamom vystavení slnečnému UV žiareniu na povrchu degraduje, preto je nutné opatriť ju ochranným náterom. <strong>Dobrý ochranný náter má nasledovné vlastnosti:</strong></p>\n<ul>\n<li>Dobrá priľnavosť</li>\n<li>Nízka citlivosť na teplo</li>\n<li>Odolnosť proti mechanickému poškodeniu</li>\n<li>Odolnosť proti poveternostným vplyvom</li>\n<li>Jednoduchá údržba</li>\n<li>Pevnosť a pružnosť</li>\n</ul>\n<p>Ochranná vrstva polyuretánovej peny je aplikovaná v tekutej forme, čo je veľmi výhodné v porovnaní s prefabrikovanými plášťami. Nie je nutné lepenie ani prekladanie vrstiev. Dokonale kopíruje povrch izolácie a umožnuje plynulé zakončenia na krajoch strechy.</p>\n<p><strong>Najčastejšie používané nátery, v závislosti od konkrétnych podmienok sú:</strong></p>\n<ul>\n<li>Silikónové syntetické gumy</li>\n<li>Polyuretány</li>\n<li>Neoprén</li>\n<li>Akryláty</li>\n</ul>', 'povrch, pena, strechy, ploché strechy', 41, '', 's', 1, 2, 3, 0, '2013-06-11 00:00:00', 1, 0, 0, 9),
(22, 'SEALECTION-Agribalance', 'SEALECTION Agribalance', '2013-06-11 17:08:46', '2013-06-13 11:59:30', 'SEALECTION Agribalance', '<p><img style="display: block; margin-left: auto; margin-right: auto;" title="penove izolacie agribalance logo" src="files/articles/22/penove-izolacie-agribalance-logo.jpg" alt="penove izolacie agribalance" width="660" height="120" /></p>\n<p>SEALECTION Agribalance</p>', 'agribalance, sealection, sealection agribalance, agribalance sealection', 43, '', 's', 1, 2, 3, 0, '2013-06-11 00:00:00', 1, 0, 0, 6),
(23, 'DEMILEC-APX', 'DEMILEC APX', '2013-06-11 17:09:32', '2013-06-13 12:00:16', 'DEMILEC APX', '<p><img style="display: block; margin-left: auto; margin-right: auto;" title="penove izolacie demilec apx logo" src="files/articles/23/penove-izolacie-demilec-apx-logo.jpg" alt="penove izolacie demilec apx" width="660" height="120" /></p>\n<p>DEMILEC APX</p>', 'demilec', 44, '', 's', 1, 2, 3, 0, '2013-06-11 00:00:00', 1, 0, 0, 5),
(24, 'Sealection-500-PIP', 'Sealection 500 PIP', '2013-06-11 17:10:07', '2013-06-13 12:01:18', 'Sealection 500 PIP', '<p><img style="display: block; margin-left: auto; margin-right: auto;" title="penove izolacie sealection 500 pip logo" src="files/articles/24/penove-izolacie-sealection-500-PIP-logo.jpg" alt="penove izolacie sealection 500 pip" width="660" height="120" /></p>\n<p>Sealection 500 PIP</p>', 'sealection', 45, '', 's', 1, 2, 3, 0, '2013-06-11 00:00:00', 1, 0, 0, 5),
(25, 'ECO-PUR-PIP', 'ECO-PUR PIP', '2013-06-11 17:10:32', '2013-06-13 12:02:15', 'ECO-PUR PIP', '<p><img style="display: block; margin-left: auto; margin-right: auto;" title="penove izolacie eco-pur pip logo" src="files/articles/25/penove-izolacie-eco-pur-PIP-logo.jpg" alt="penove izolacie eco-pur pip" width="660" height="120" /></p>\n<p>ECO-PUR PIP</p>', '0', 46, '', 's', 1, 2, 3, 0, '2013-06-11 00:00:00', 1, 0, 0, 5),
(26, 'SLAM-210', 'SLAM 210', '2013-06-11 17:11:04', '2013-06-25 14:02:13', 'SLAM 210', '<p><img style="display: block; margin-left: auto; margin-right: auto;" title="penove izolacie slam logo" src="files/articles/26/penove-izolacie-slam-logo.jpg" alt="penove izolacie slam" width="660" height="120" /></p>\n<p>SLAM 210</p>\n<p><img style="margin-left: 20px; margin-right: 20px;" title="striekane penove izolacie slam 210 aplikácia" src="files/articles/26/striekane_penove_izolacie_slam.png" alt="striekane penove izolacie slam 210 aplikácia" width="250" height="300" /><img title="striekané penové izolácie slam 210 aplikované" src="files/articles/26/striekane_penove_izolacie_slam002.png" alt="striekané penové izolácie slam 210 aplikované" width="250" height="300" /></p>', 'slam', 47, '', 's', 1, 2, 3, 0, '2013-06-11 00:00:00', 1, 0, 0, 7),
(27, 'Certifikaty', 'Certifikáty', '2013-06-12 10:31:12', '2013-06-12 11:29:31', 'Všetky z ponúkaných produktov sú plne certifikované pre stavebné použitie v EÚ, SR a ČR. Certifikáciu vykonali skúšobné laborátoriá a stavebné inštitúty ako TSUS, VTT, PAVUS, FIRES', '', 'certifikáty', 49, '', 'g', 1, 2, 1, 0, '2013-06-12 00:00:00', 1, 0, 0, 13);

-- --------------------------------------------------------

--
-- Table structure for table `SPI_BANNERS`
--

CREATE TABLE IF NOT EXISTS `SPI_BANNERS` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(300) NOT NULL,
  `link` varchar(500) NOT NULL,
  `openin` tinyint(1) NOT NULL,
  `description` varchar(50) NOT NULL,
  `location` varchar(10) NOT NULL COMMENT 'the category id in content or the other location like pagetop',
  `position` varchar(10) NOT NULL COMMENT 'the position in content or elsewhere',
  `active` tinyint(1) NOT NULL,
  `type` char(1) NOT NULL,
  `lang` int(3) NOT NULL,
  `viewcount` int(11) NOT NULL,
  `maxviewcount` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `SPI_BANNERS`
--

INSERT INTO `SPI_BANNERS` (`id`, `filename`, `link`, `openin`, `description`, `location`, `position`, `active`, `type`, `lang`, `viewcount`, `maxviewcount`) VALUES
(1, 'vyber-izolacie.png', 'page=vyber-izolacie', 0, 'Výber izolácie', '2', 'first', 1, 'a', 1, 43, 2147483647),
(2, 'vyber-izolacie.png', 'page=vyber-izolacie', 0, 'Výber izolácie', '3', 'first', 1, 'a', 1, 58, 2147483647),
(3, 'vyber-izolacie.png', 'page=vyber-izolacie', 0, 'Výber izolácie', '4', 'first', 1, 'a', 1, 21, 2147483647);

-- --------------------------------------------------------

--
-- Table structure for table `SPI_DAYOFFER`
--

CREATE TABLE IF NOT EXISTS `SPI_DAYOFFER` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `day` date NOT NULL,
  `soup` varchar(1000) NOT NULL,
  `main_food` varchar(1000) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `SPI_DAYOFFER`
--


-- --------------------------------------------------------

--
-- Table structure for table `SPI_EVENTSCALENDAR`
--

CREATE TABLE IF NOT EXISTS `SPI_EVENTSCALENDAR` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` varchar(2000) NOT NULL,
  `date_from` datetime NOT NULL,
  `date_to` datetime NOT NULL,
  `added_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `SPI_EVENTSCALENDAR`
--


-- --------------------------------------------------------

--
-- Table structure for table `SPI_LANGUAGES`
--

CREATE TABLE IF NOT EXISTS `SPI_LANGUAGES` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `shortcode` varchar(2) NOT NULL,
  `longcode` varchar(6) NOT NULL,
  `defaultlang` tinyint(1) NOT NULL,
  `published` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `SPI_LANGUAGES`
--

INSERT INTO `SPI_LANGUAGES` (`id`, `name`, `shortcode`, `longcode`, `defaultlang`, `published`) VALUES
(1, 'Slovenský (SK)', 'sk', 'sk-SK', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `SPI_MENU`
--

CREATE TABLE IF NOT EXISTS `SPI_MENU` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `alias` varchar(100) NOT NULL,
  `description` varchar(300) NOT NULL,
  `keywords` varchar(200) NOT NULL,
  `categoryimage` varchar(300) NOT NULL,
  `parentid` int(11) NOT NULL,
  `orderno` int(11) NOT NULL,
  `link` varchar(500) NOT NULL,
  `type` char(1) NOT NULL,
  `layout` char(1) NOT NULL,
  `lang` int(11) NOT NULL,
  `published` tinyint(1) NOT NULL,
  `display_new_articles` tinyint(1) NOT NULL,
  `show_in_menu` tinyint(1) NOT NULL,
  `show_in_footer` tinyint(1) NOT NULL,
  `viewcount` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=50 ;

--
-- Dumping data for table `SPI_MENU`
--

INSERT INTO `SPI_MENU` (`id`, `name`, `alias`, `description`, `keywords`, `categoryimage`, `parentid`, `orderno`, `link`, `type`, `layout`, `lang`, `published`, `display_new_articles`, `show_in_menu`, `show_in_footer`, `viewcount`) VALUES
(1, 'Domov', 'Domov', 'Domovská stránka', 'membrány, striekané, izolácie, penové izolácie, striekané penové', '', 0, 1, 'http://striekanepenoveizolacie.sk/', 'e', 's', 1, 1, 0, 1, 0, 1),
(2, 'Súkromní investori', 'Sukromni-investori', 'Staviate dom, chatu prípadne rekonštruujete a ak Vám záleží na najlepšom výbere izolácie tak potrebné informácie nájdete v tejto sekcii.', 'investori, membrány, izolácie, penové izolácie, striekané penové', '', 0, 2, '', 's', 's', 1, 1, 0, 1, 0, 44),
(3, 'Architekt / projektant', 'Architekt-projektant', 'Pre odbornú verejnosť máme v tejto sekcii pripravené rôzne vzorové rezy, pri ktorých boli použité naše striekané penové izolácie ako aj rôzne tepelno-technické prepočty s potrebnými údajmi.', 'projektanti, membrány, izolácie, penové izolácie, striekané penové', '', 0, 3, '', 's', 's', 1, 1, 0, 1, 0, 59),
(4, 'Aplikátor / dealer', 'Aplikator-dealer', 'Táto sekcia je určená existujúcim aplikátorom a obchodným zástupcom pre striekané penové izolácie.', 'dealeri, membrány, izolácie, penové izolácie, striekané penové', '', 0, 4, '', 's', 's', 1, 1, 0, 1, 0, 22),
(5, 'Novinky', 'Novinky', 'Striekané penové izolácie  Heatlok Soy a Sealection od Demilec a izolačné materiály Multigum a Slam sa využívajú ako izolácia strechy, základov, podlahy, stien, obvodového plášťa, podkrovia, potrubí, lodí, áut. Striekaná penová izolácia je hydroizolácia, tepelná izolácia a barozábrana v jednom.', 'membrány, striekané, izolácie, penové izolácie, striekané penové', '', 0, 5, '', 's', 's', 1, 1, 0, 0, 1, 3),
(6, 'O nás', 'O-nas', 'Spoločnosť SaveEnergy s.r.o. je výhradný distribútor pre striekané penové izolácie a izolačné materiály kanadsko - amerického konzorcia DEMILEC GROUP ® pre slovenský a český trh. Je to taktiež výhradný distribútor pre hydroizolačné materiály izraelskej spoločnosti Bitum pre Slovenskú republiku.', 'membrány, izolácie, penové, penové izolácie, striekané penové', '', 0, 6, '', 's', 's', 1, 1, 0, 0, 1, 25),
(7, 'Produkty', 'Produkty', 'Demilec polyuretánové striekané penové izolácie sú: izolačná nástreková pena rady Heatlok Soy, Sealection 500 a tesniaci izolačný materiál Multigum. Demilec tepelné izolácie sú energeticky úsporné a zároveň ekologické.', 'membrány, izolácie, penové, penové izolácie, striekané penové', '', 0, 7, '', 's', 's', 1, 1, 0, 0, 1, 30),
(8, 'Photo/video', 'Photo-video', 'Photo/video', 'video, membrány, izolácie, penové izolácie, striekané penové', '', 0, 8, '', 's', 's', 1, 1, 0, 0, 1, 17),
(9, 'Kontakty', 'Kontakty', 'V prípade akýchkoľvek otázok nás neváhajte kontaktovať', 'kotakt, kontakty, penové izolácie, striekané penové', '', 0, 9, '', 's', 's', 1, 1, 0, 0, 1, 5),
(10, 'Partneri', 'Partneri', 'Partneri', 'membrány, izolácie, penové, penové izolácie, striekané penové', '', 0, 10, '', 's', 's', 1, 1, 0, 0, 1, 9),
(11, 'O Saveenergy', 'O-Saveenergy', 'Saveenergy je autorizovaný distribútor pre striekané penové izolácie a izolačné materiály Demilec pre Slovensko a Česko a hydroizolačné materiály Bitum pre Slovensko. Distribúcia, predaj, odborná pomoc, školenia aplikátorov, semináre, poradenstvo.', 'striekané penové izolácie, izolačné materiály, hydroizolačné materiály, izolácia budov, izolácia skladov, izolácia podtrubí, odborné školenia', '', 6, 1, '2', 'a', 's', 1, 1, 0, 0, 0, 0),
(12, 'Prečo striekaná penová izolácia', 'Preco-striekana-penova-izolacia', 'Prečo striekaná penová izolácia', 'striekaná, penová, striekané, striekaná penová, penové izolácie', '', 2, 1, '13', 'a', 's', 1, 1, 0, 0, 0, 1),
(13, 'Prečo Demilec', 'Preco-Demilec', 'Prečo Demilec', 'membrány, striekané, izolácie, penové izolácie, striekané penové', '', 2, 2, '12', 'a', 's', 1, 1, 0, 0, 0, 1),
(14, 'Otázky a odpovede', 'Otazky-a-odpovede', 'Otázky a odpovede', 'membrány, striekané, izolácie, penové izolácie, striekané penové', '', 2, 3, '15', 'a', 's', 1, 1, 0, 0, 0, 1),
(15, 'Produkty', 'Produkty', 'Produkty', 'membrány, izolácie, penové, penové izolácie, striekané penové', '', 2, 4, '14', 'a', 's', 1, 1, 0, 0, 0, 1),
(16, 'Nájsť dodávateľa/aplikátora', 'Najst-dodavatela-aplikatora', 'Nájsť dodávateľa/aplikátora', 'dodávateľa, striekané, membrány, striekané penové, penové izolácie', '', 2, 5, '10', 'c', 's', 1, 1, 0, 0, 0, 1),
(17, 'Galéria projektov', 'Galeria-projektov', 'Galéria projektov', 'membrány, striekané, izolácie, penové izolácie, striekané penové', '', 2, 6, '39', 'c', 's', 1, 1, 0, 0, 0, 1),
(18, 'Prečo striekaná penová izolácia', 'Preco-striekana-penova-izolacia', 'Prečo striekaná penová izolácia', 'striekaná, penová, striekané, striekaná penová, penové izolácie', '', 3, 1, '13', 'a', 's', 1, 1, 0, 0, 0, 1),
(19, 'Prečo Demilec', 'Preco-Demilec', 'Prečo Demilec', 'membrány, striekané, izolácie, penové izolácie, striekané penové', '', 3, 2, '12', 'a', 's', 1, 1, 0, 0, 0, 1),
(20, 'Ponuka spolupráce', 'Ponuka-spoluprace', 'Ponuka spolupráce', 'membrány, striekané, izolácie, penové izolácie, striekané penové', '', 3, 3, '19', 'a', 's', 1, 1, 0, 0, 0, 6),
(21, 'Produkty', 'Produkty', 'Produkty', 'membrány, izolácie, penové, penové izolácie, striekané penové', '', 3, 4, '14', 'a', 's', 1, 1, 0, 0, 0, 1),
(22, 'Druh stavby', 'Druh-stavby', 'Druh stavby', 'membrány, striekané, izolácie, penové izolácie, striekané penové', '', 3, 5, '18', 'a', 's', 1, 1, 0, 0, 0, 1),
(23, 'Odborné články', 'Odborne-clanky', 'Odborné články', 'membrány, striekané, izolácie, penové izolácie, striekané penové', '', 3, 6, '', 's', 's', 1, 1, 0, 0, 0, 3),
(24, 'Služby', 'Sluzby', 'Služby', 'membrány, izolácie, penové, penové izolácie, striekané penové', '', 3, 7, '16', 'a', 's', 1, 1, 0, 0, 0, 1),
(25, 'Galéria projektov', 'Galeria-projektov', 'Galéria projektov', 'membrány, striekané, izolácie, penové izolácie, striekané penové', '', 3, 9, '39', 'c', 's', 1, 1, 0, 0, 0, 1),
(26, 'Nájsť dodávateľa/aplikátora', 'Najst-dodavatela-aplikatora', 'Nájsť dodávateľa/aplikátora', 'dodávateľa, striekané, membrány, striekané penové, penové izolácie', '', 3, 8, '10', 'c', 's', 1, 1, 0, 0, 0, 1),
(28, 'Prečo striekaná penová izolácia', 'Preco-striekana-penova-izolacia', 'Prečo striekaná penová izolácia', 'striekaná, penová, striekané, striekaná penová, penové izolácie', '', 4, 1, '13', 'a', 's', 1, 1, 0, 0, 0, 1),
(29, 'Prečo Demilec', 'Preco-Demilec', 'Prečo Demilec', 'membrány, striekané, izolácie, penové izolácie, striekané penové', '', 4, 2, '12', 'a', 's', 1, 1, 0, 0, 0, 1),
(30, 'Prečo sa stať aplikátorom', 'Preco-sa-stat-aplikatorom', 'Prečo sa stať aplikátorom', 'stať, striekané, membrány, penové izolácie, striekané penové', '', 4, 3, '', 's', 's', 1, 1, 0, 0, 0, 4),
(31, 'Produkty', 'Produkty', 'Produkty', 'membrány, izolácie, penové, penové izolácie, striekané penové', '', 4, 4, '14', 'a', 's', 1, 1, 0, 0, 0, 1),
(32, 'Služby', 'Sluzby', 'Služby', 'membrány, izolácie, penové, penové izolácie, striekané penové', '', 4, 5, '16', 'a', 's', 1, 1, 0, 0, 0, 1),
(33, 'Login', 'Login', 'Login', 'membrány, izolácie, penové, penové izolácie, striekané penové', '', 4, 6, 'http://www.saveenergy.sk/zona', 'e', 's', 1, 1, 0, 0, 0, 1),
(34, 'O Demilec USA', 'O-Demilec-USA', 'O Demilec USA', 'membrány, striekané, izolácie, penové izolácie, striekané penové', '', 6, 2, '9', 'a', 's', 1, 1, 0, 0, 0, 1),
(35, 'Referencie', 'Referencie', 'Za šesť rokov pôsobenia sme my a naši partneri úspešne zaizolovali viac ako 1000 projektov na Slovensku a v Čechách. Nižšie je výber z niekoľkých projektov realizovaných našou spoločnosťou.', 'membrány, izolácie, penové, penové izolácie, striekané penové, referncie', '', 6, 3, '', 's', 's', 1, 1, 0, 0, 0, 3),
(36, 'Na stiahnutie', 'Na-stiahnutie', 'Na stiahnutie', 'membrány, striekané, izolácie, penové izolácie, striekané penové', '', 6, 5, '', 's', 's', 1, 1, 0, 0, 0, 1),
(37, 'Video podľa produktov', 'Video-podla-produktov', 'Video podľa produktov', 'podľa, striekané, membrány, penové izolácie, striekané penové', '', 8, 1, '', 's', 's', 1, 1, 0, 0, 0, 2),
(38, 'Photo podľa produktov', 'Photo-podla-produktov', 'Photo podľa produktov', 'podľa, striekané, membrány, penové izolácie, striekané penové', '', 8, 2, '', 's', 's', 1, 1, 0, 0, 0, 1),
(39, 'Galéria projektov', 'Galeria-projektov', 'Striekané penové izolácie  Heatlok Soy a Sealection od Demilec a izolačné materiály Multigum a Slam sa využívajú ako izolácia strechy, základov, podlahy, stien, obvodového plášťa, podkrovia, potrubí, lodí, áut. Striekaná penová izolácia je hydroizolácia, tepelná izolácia a barozábrana v jednom.', 'striekané penové izolácie, striekaná penová izolácia, izolačné materiály demilec, striekaná pena', '', 8, 3, '', 's', 's', 1, 1, 0, 0, 0, 8),
(40, 'Heatlok Soy®200', 'Heatlok-Soy-200', 'Heatlok Soy®200', 'membrány, striekané, izolácie, penové izolácie, striekané penové', '', 7, 1, '3', 'a', 's', 1, 1, 0, 0, 0, 1),
(41, 'Heatlok Soy Roofing foam', 'Heatlok-Soy-Roofing-foam', 'Heatlok Soy Roofing foam', 'roofing, striekané, membrány, penové izolácie, striekané penové', '', 7, 2, '21', 'a', 's', 1, 1, 0, 0, 0, 1),
(42, 'SEALECTION® 500', 'SEALECTION-500', 'SEALECTION® 500', 'membrány, striekané, izolácie, penové izolácie, striekané penové', '', 7, 3, '4', 'a', 's', 1, 1, 0, 0, 0, 1),
(43, 'SEALECTION Agribalance', 'SEALECTION-Agribalance', 'SEALECTION Agribalance', 'membrány, striekané, izolácie, penové izolácie, striekané penové', '', 7, 4, '22', 'a', 's', 1, 1, 0, 0, 0, 1),
(44, 'DEMILEC APX', 'DEMILEC-APX', 'DEMILEC APX', 'membrány, striekané, izolácie, penové izolácie, striekané penové', '', 7, 5, '23', 'a', 's', 1, 1, 0, 0, 0, 1),
(45, 'Sealection 500 PIP', 'Sealection-500-PIP', 'Sealection 500 PIP', 'membrány, striekané, izolácie, penové izolácie, striekané penové', '', 7, 6, '24', 'a', 's', 1, 1, 0, 0, 0, 1),
(46, 'ECO-PUR PIP', 'ECO-PUR-PIP', 'ECO-PUR PIP', 'membrány, izolácie, penové, penové izolácie, striekané penové', '', 7, 7, '25', 'a', 's', 1, 1, 0, 0, 0, 1),
(47, 'SLAM 210', 'SLAM-210', 'SLAM 210', 'membrány, striekané, izolácie, penové izolácie, striekané penové', '', 7, 8, '26', 'a', 's', 1, 1, 0, 0, 0, 1),
(48, 'Multigum', 'Multigum', 'Multigum', 'membrány, izolácie, penové, penové izolácie, striekané penové', '', 7, 9, '5', 'a', 's', 1, 1, 0, 0, 0, 1),
(49, 'Certifikáty', 'Certifikaty', 'Všetky z ponúkaných produktov sú plne certifikované pre stavebné použitie v EÚ, SR a ČR. Certifikáciu vykonali skúšobné laborátoriá a stavebné inštitúty ako TSUS, VTT, PAVUS, FIRES', 'membrány, izolácie, penové, penové izolácie, striekané penové', '', 6, 4, '27', 'a', 's', 1, 1, 0, 0, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `SPI_MINISLIDER`
--

CREATE TABLE IF NOT EXISTS `SPI_MINISLIDER` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `text` varchar(300) NOT NULL,
  `filename` varchar(300) NOT NULL,
  `url` varchar(1000) NOT NULL,
  `lang` int(3) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `orderno` int(11) NOT NULL,
  `x` int(5) NOT NULL,
  `y` int(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `SPI_MINISLIDER`
--

INSERT INTO `SPI_MINISLIDER` (`id`, `name`, `text`, `filename`, `url`, `lang`, `active`, `orderno`, `x`, `y`) VALUES
(1, 'Izolácia podkrovia', 'Medzi krokvy sa ako izolácia využívajú striekané penové izolácie s otvorenou aj uzavretou štruktúrou buniek. Striekaná pena dokonale utesní všetky škáry a medzery a vytvorí tak súvislú vrstvu a eliminuje akékoľvek tepelné mosty.', 'podkrovia.jpg', 'Viac o Heatlok Soy||c/7/Produkty.html#a/3/Heatlok-Soy-200.html\nViac o Sealection 500||c/7/Produkty.html#a/4/SEALECTION-500.html', 1, 1, 1, 521, 90),
(2, 'Izolácia podlahy', 'Vďaka súvislej vrstve nástreku striekaná penová izolácia plynule prekrýva všetky rozvody bez nutnosti rezania. Takýto izolačný materiál stačí prekryť vrstvou betónového poteru. Striekané penové izolácie sú vhodné aj ako podklad pod podlahové kúrenie.', 'podlahy.jpg', 'Viac o Heatlok Soy||c/7/Produkty.html#a/3/Heatlok-Soy-200.html', 1, 1, 2, 420, 178),
(3, 'Izolácia podhľadov / priečok', 'Na aplikáciu medzi stropné trámy je vhodná tak mäkká ako aj tvrdá izolačná pena. V podhľadoch a priečkach pôsobí striekaná penová izolácia Sealection aj ako výborná zvuková izolácia s Rw indexom 50 dB.', 'podhlady_priecky.jpg', 'Viac o Heatlok Soy||c/7/Produkty.html#a/3/Heatlok-Soy-200.html\nViac o Sealection 500||c/7/Produkty.html#a/4/SEALECTION-500.html', 1, 1, 3, 400, 126),
(4, 'Izolácia základov', 'Tvrdá striekaná penová izolácia Heatlok Soy 200 sa môže využiť aj ako izolácia spodnej stavby proti vlhkosti. Je vhodná ako izolácia základových pásov namiesto dosiek EPS, prípadne pod základovú platňu, kde slúži aj ako izolácia proti radónu.', 'zaklady.jpg', 'Viac o Heatlok Soy||c/7/Produkty.html#a/3/Heatlok-Soy-200.html\nViac o SLAM 210||c/7/Produkty.html#a/26/SLAM-210.html', 1, 1, 4, 508, 232),
(5, 'Izolácia plochej strechy / terasy', 'Sem je vhodná striekaná penová izolácia so zvýšenou hustotou Heatlok Soy roofing foam a následne hydroizolačný náter Multigum, prípadne ultra odolná polyurea SLAM U210.', 'ploche-strechy_terasy.jpg', 'Viac o Heatlok Soy||c/7/Produkty.html#a/3/Heatlok-Soy-200.html\nViac o Multigum||c/7/Produkty.html#a/5/Multigum.html\nViac o SLAM 210||c/7/Produkty.html#a/26/SLAM-210.html', 1, 1, 5, 300, 97),
(6, 'Izolácia fasády', 'Striekaná penová izolácia Heatlok Soy 200 je ideálná ako izolácia prevetrávaných a predsadených fasád. Rýchlosť práce, súvislá vrstva penovej izolácie a jej nižšia hrúbka z nej činia tú najlepšiu voľbu pre Vašu fasádu.', 'fasady.jpg', 'Viac o Heatlok Soy||c/7/Produkty.html#a/3/Heatlok-Soy-200.html', 1, 1, 6, 144, 97),
(7, 'Izolácia bazénov / hydroizolácia', 'Izolačný náter Multigum slúži ako vysoko účinná hydroizolácia bazénov či plochých striech. Polyurea SLAM U210 odolá vodnému tlaku a chemickým látkam.', 'bazeny_hydroizolacia.jpg', 'Viac o Multigum||c/7/Produkty.html#a/5/Multigum.html\nViac o SLAM 210||c/7/Produkty.html#a/26/SLAM-210.html', 1, 1, 7, 312, 151),
(8, 'Izolácia potrubí / nádrží', 'Striekaná penová izolácia Heatlok soy je vhodná ako izolácia potrubí proti premŕzaniu. Izolačný nástrek SLAM U210 je ideálny ako izolácia vonkajšej steny podzemných aj nadzemných potrubí.', 'potrubia_nadrze.jpg', 'Viac o Heatlok Soy||c/7/Produkty.html#a/3/Heatlok-Soy-200.html\nViac o SLAM 210||c/7/Produkty.html#a/26/SLAM-210.html', 1, 1, 8, 425, 253),
(9, 'Izolácia áut / lodí', 'Striekané penové izolácie rady Heatlok soy sú vhodné aj ako izolácia nákladných priestorov úžitkových vozidiel, prípadne ako izolácia člnov a lodí. Izolačný náter SLAM U210 je výborný ako izolácia podláh v úžitkovom vozidle.', 'automobily_lode.jpg', 'Viac o Heatlok Soy||c/7/Produkty.html#a/3/Heatlok-Soy-200.html\nViac o SLAM 210||c/7/Produkty.html#a/26/SLAM-210.html', 1, 1, 9, 657, 144);

-- --------------------------------------------------------

--
-- Table structure for table `SPI_NEWSLETTER`
--

CREATE TABLE IF NOT EXISTS `SPI_NEWSLETTER` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(256) NOT NULL,
  `firstsubscribe_date` datetime NOT NULL,
  `newsletter` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `SPI_NEWSLETTER`
--


-- --------------------------------------------------------

--
-- Table structure for table `SPI_OPENINGHOURS`
--

CREATE TABLE IF NOT EXISTS `SPI_OPENINGHOURS` (
  `id` int(1) NOT NULL,
  `od` varchar(10) NOT NULL,
  `do` varchar(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `SPI_OPENINGHOURS`
--

INSERT INTO `SPI_OPENINGHOURS` (`id`, `od`, `do`) VALUES
(1, '00:00', '00:00'),
(2, '00:00', '00:00'),
(3, '00:00', '00:00'),
(4, '00:00', '00:00'),
(5, '00:00', '00:00'),
(6, '00:00', '00:00'),
(7, '00:00', '00:00');

-- --------------------------------------------------------

--
-- Table structure for table `SPI_ORDERS`
--

CREATE TABLE IF NOT EXISTS `SPI_ORDERS` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `meno` varchar(50) NOT NULL,
  `priezvisko` varchar(50) NOT NULL,
  `miesto` varchar(100) NOT NULL,
  `typ_zateplenia` int(2) NOT NULL,
  `plocha` int(8) NOT NULL,
  `hrubka` int(3) NOT NULL,
  `konecna_cena_m2` float NOT NULL,
  `datum_pridania` datetime NOT NULL,
  `datum_zmeny` date NOT NULL,
  `datum_platnosti` date NOT NULL,
  `datum_ukoncenia` date NOT NULL,
  `stav` int(1) NOT NULL,
  `vlastnik` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `SPI_ORDERS`
--


-- --------------------------------------------------------

--
-- Table structure for table `SPI_POLLANSVERS`
--

CREATE TABLE IF NOT EXISTS `SPI_POLLANSVERS` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `poll` int(11) NOT NULL,
  `answer` varchar(200) NOT NULL,
  `votecount` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `SPI_POLLANSVERS`
--


-- --------------------------------------------------------

--
-- Table structure for table `SPI_POLLS`
--

CREATE TABLE IF NOT EXISTS `SPI_POLLS` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` varchar(500) NOT NULL,
  `date_from` datetime NOT NULL,
  `date_to` datetime NOT NULL,
  `added_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `SPI_POLLS`
--


-- --------------------------------------------------------

--
-- Table structure for table `SPI_SENTMAILS`
--

CREATE TABLE IF NOT EXISTS `SPI_SENTMAILS` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `recipients` text NOT NULL,
  `recipientscount` int(11) NOT NULL,
  `subject` varchar(70) NOT NULL,
  `mailbody` text NOT NULL,
  `sendingdate` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `SPI_SENTMAILS`
--


-- --------------------------------------------------------

--
-- Table structure for table `SPI_SETTINGS`
--

CREATE TABLE IF NOT EXISTS `SPI_SETTINGS` (
  `id` varchar(50) NOT NULL,
  `value` varchar(1000) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `SPI_SETTINGS`
--

INSERT INTO `SPI_SETTINGS` (`id`, `value`) VALUES
('PAGEOFFLINE', 'false'),
('PRETTYURLS', 'true'),
('PAGINATEDARTICLECOUNT', '10'),
('PAGINATEDSWITCHCOUNT', '10'),
('SUBCATNEWARTICLECOUNT', '3'),
('FEEDARTICLECOUNT', '30'),
('GA_TRACKINGCODE', 'UA-37714819-1'),
('AUTH_SINGLEPCSIGNIN', 'false'),
('ADMINLANGCODE', 'sk'),
('PAGE_TITLEPREFIX', 'Striekané Penové Izolácie (SPI) a membrány'),
('PAGE_DESCRIPTION', 'Sme autorizovaný distribútor pre striekané penové izolácie od Demilec USA. Penové izolácie, ochranné nátery a izolačné materiály.'),
('PAGE_KEYWORDS', 'demilec,striekané penové izolácie,penové izolácie,izolačná pena,nástreková pena,striekaná penová izolácia,tepelná izolácia,izolačný materiál,polyuretánové izolácie'),
('PAGE_COPYRIGHT', 'Saveenergy s.r.o.'),
('CACHING', 'false'),
('CACHEEXPIRE', '500'),
('PRINTFBMETA', 'false'),
('FBADMINID', ''),
('SLIDESHOW_TRANSITION', 'fade'),
('SLIDESHOW_EASING', 'linear'),
('SLIDESHOW_TRANSITIONSPEED', '800'),
('SLIDESHOW_SLIDEDELAY', '1000'),
('SLIDESHOW_USESLIDESWITCH', 'false'),
('SLIDESHOW_RANDOMIZE', 'false'),
('MINISLIDER_RANDOMIZE', 'false'),
('MINISLIDER_SLIDEWIDTH', '440'),
('MINISLIDER_EASING', 'easeInOutExpo'),
('MINISLIDER_SLIDINGSPEED', '200'),
('MINISLIDER_SLIDINGSTEP', '220'),
('FEEDPARSER_URLS', 'https://news.google.com/news/feeds?pz=1&cf=all&ned=us&hl=en&q=NASA&output=rss'),
('USE_FEEDPARSER', 'false'),
('USE_BANNERS', 'true'),
('USE_VIDEOS', 'false'),
('USE_SLIDESHOW', 'true'),
('USE_EVENTSCALENDAR', 'false'),
('USE_OPENINGHOURS', 'false'),
('USE_DAYOFFER', 'false'),
('USE_NEWSLETTER', 'false'),
('USE_MINISLIDER', 'true'),
('USE_SEARCH', 'true'),
('SEARCH_DEFAULTSTRING', 'vyhľadávanie'),
('PUBLISH_FEEDS', 'true'),
('PAGEOFFLINE_TEXT', 'Stránka je dočasne nedostupná.'),
('EVENTSCALENDARWIDGET_COUNT', '3'),
('FEEDPARSER_COUNT', '3'),
('USE_NEWARTICLES', 'true'),
('NEWARTICLES_COUNT', '3'),
('USE_MOSTREADARTICLES', 'false'),
('MOSTREADARTICLES_COUNT', '3'),
('VIDEOSWIDGET_COUNT', '3'),
('USE_CONTACT', 'true'),
('CONTACT_TOMAIL', 'suscak@saveenergy.sk, hyranek@saveenergy.sk'),
('PAGETITLE_SUFFIX', 'true'),
('PAGE_SLOGAN', 'Šetríme energiu od základov po strechu'),
('USE_DUBLINCORE', 'true'),
('LINK_FACEBOOK', 'https://www.facebook.com/StrikanePenoveIzolace'),
('LINK_GOOGLEP', 'https://plus.google.com/100334557026204951115'),
('LINK_TWITTER', 'https://twitter.com/penoveizolace'),
('USE_CONTACTVCARD', 'true'),
('CONTACT_NAME', ''),
('CONTACT_STREET', 'Púchovská 12'),
('CONTACT_PSC', '831 06'),
('CONTACT_CITY', 'Bratislava'),
('CONTACT_STATE', 'Slovakia'),
('CONTACT_LATITUDE', '48.21395'),
('CONTACT_LONGITUDE', '17.16289'),
('CONTACT_WORK', '+421 2 44 63 07 46'),
('CONTACT_CELL', '+421 902 286 776'),
('SLIDESHOW_USENEXTPREV', 'true'),
('LINK_YOUTUBE', 'http://www.youtube.com/channel/UCc_r2QgDn1qD9yveBxrZkpA/feed'),
('ORDERS_USERGROUP', ''),
('MIN_PASSWORDLENGTH', '1');

-- --------------------------------------------------------

--
-- Table structure for table `SPI_SLIDESHOW`
--

CREATE TABLE IF NOT EXISTS `SPI_SLIDESHOW` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orderno` int(11) NOT NULL,
  `file` varchar(256) NOT NULL,
  `heading` varchar(200) NOT NULL,
  `description` varchar(300) NOT NULL,
  `textposition` varchar(10) NOT NULL,
  `link` varchar(500) NOT NULL,
  `publish_from` date NOT NULL,
  `publish_to` date NOT NULL,
  `lang` int(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `SPI_SLIDESHOW`
--

INSERT INTO `SPI_SLIDESHOW` (`id`, `orderno`, `file`, `heading`, `description`, `textposition`, `link`, `publish_from`, `publish_to`, `lang`) VALUES
(7, 9, 'slide002.png', 'Dokonalá tesnosť', 'Pena ihneď po nástreku expanduje a vypĺňa okolo seba všetky škáry a dutiny.', 'right', '', '2013-06-25', '2013-07-25', 1),
(5, 5, 'slide03.png', 'U nás máte izoláciu s doživotnou zárukou', '', 'right', '', '2013-06-25', '2013-07-25', 1),
(6, 7, 'slide04.png', 'Rýchla aplikácia penových izolácií bez akýchkoľvek medzier', '', 'right', '', '2013-06-25', '2013-07-25', 1),
(8, 3, 'slide05.png', 'Freedom tower v New Yorku izolovaná penou Heatlok Soy 200', '', 'right', '', '2013-06-25', '2013-07-25', 1),
(3, 8, 'slide01.png', 'Stálosť vlastností', 'Tak ako v prvý deň, tak po celú životnosť budovy', 'right', '', '2013-06-25', '2013-09-01', 1),
(9, 6, 'slide06.png', 'Harvardská Univerzita – Všetky podkrovia izolované penou Sealection 500', '', 'right', '', '2013-06-25', '2013-07-25', 1);

-- --------------------------------------------------------

--
-- Table structure for table `SPI_USERGROUPS`
--

CREATE TABLE IF NOT EXISTS `SPI_USERGROUPS` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `default` varchar(10) NOT NULL,
  `home` varchar(10) NOT NULL,
  `articles` varchar(10) NOT NULL,
  `menu` varchar(10) NOT NULL,
  `videos` varchar(10) NOT NULL,
  `banners` varchar(10) NOT NULL,
  `filemanager` varchar(10) NOT NULL,
  `slideshow` varchar(10) NOT NULL,
  `eventscalendar` varchar(10) NOT NULL,
  `polls` varchar(10) NOT NULL,
  `users` varchar(10) NOT NULL,
  `profile` varchar(10) NOT NULL,
  `usergroups` varchar(10) NOT NULL,
  `tools` varchar(10) NOT NULL,
  `openinghours` varchar(10) NOT NULL,
  `dayoffer` varchar(10) NOT NULL,
  `newsletter` varchar(10) NOT NULL,
  `languages` varchar(10) NOT NULL,
  `docs` varchar(10) NOT NULL,
  `stats` varchar(10) NOT NULL,
  `settings` varchar(10) NOT NULL,
  `minislider` varchar(10) NOT NULL,
  `orders` varchar(10) NOT NULL,
  `denylogin` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `SPI_USERGROUPS`
--

INSERT INTO `SPI_USERGROUPS` (`id`, `name`, `default`, `home`, `articles`, `menu`, `videos`, `banners`, `filemanager`, `slideshow`, `eventscalendar`, `polls`, `users`, `profile`, `usergroups`, `tools`, `openinghours`, `dayoffer`, `newsletter`, `languages`, `docs`, `stats`, `settings`, `minislider`, `orders`, `denylogin`) VALUES
(1, 'root', '1', '1', '11111', '1111', '11', '11', '1', '11', '11', '11', '11111', '1', '11111', '1', '11', '11', '111', '11', '1', '1', '11', '11', '1111', 0),
(2, 'Admins', '1', '1', '11111', '1111', '00', '11', '0', '11', '10', '10', '00000', '1', '00000', '0', '10', '10', '100', '00', '1', '0', '11', '11', '', 0),
(3, 'Moderators', '', '1', '11111', '1111', '00', '11', '1', '11', '', '', '00000', '1', '00000', '1', '', '', '', '00', '0', '0', '11', '11', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `SPI_USERS`
--

CREATE TABLE IF NOT EXISTS `SPI_USERS` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `fronttitles` varchar(20) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `surename` varchar(50) NOT NULL,
  `endtitles` varchar(20) NOT NULL,
  `company` varchar(50) NOT NULL,
  `company_id` varchar(20) NOT NULL,
  `tax_id` varchar(20) NOT NULL,
  `vat_id` varchar(20) NOT NULL,
  `address` varchar(100) NOT NULL,
  `country` char(2) NOT NULL,
  `cookiesecret` varchar(32) NOT NULL,
  `password` varchar(32) NOT NULL,
  `email` varchar(256) NOT NULL,
  `phone` varchar(25) NOT NULL,
  `loggedin` tinyint(1) NOT NULL,
  `usergroup` int(11) NOT NULL,
  `denylogin` tinyint(1) NOT NULL,
  `categoryaccess` int(11) NOT NULL,
  `newsletter` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `SPI_USERS`
--

INSERT INTO `SPI_USERS` (`id`, `username`, `fronttitles`, `firstname`, `surename`, `endtitles`, `company`, `company_id`, `tax_id`, `vat_id`, `address`, `country`, `cookiesecret`, `password`, `email`, `phone`, `loggedin`, `usergroup`, `denylogin`, `categoryaccess`, `newsletter`) VALUES
(1, 'root', '', 'Root', 'Exetra', '', '', '', '', '', '', '', '63a9f0ea7bb98050796b649e85481845', 'e40cd151c90ca43b545d0632ab7818ad', 'info@exetra.sk', '', 1, 1, 0, 0, 0),
(2, 'jaroslav.suscak', 'Ing.', 'Jaroslav', 'Šuščák', '', '', '', '', '', '', '', '95c1619f3aad4049ce8580175a77d043', 'd40257bbd305d910b0cba8aa6807f5d3', 'suscak@saveenergy.sk', '', 1, 2, 0, 0, 0),
(3, 'matus.mraz', 'riaditeľ zemegule', 'Matúš', 'Mráz', '', '', '', '', '', '', 'sk', '405c8c83bf43703acea0168c3e3980a6', '47e62b8048a232f8f7dc79e1ead49e4e', 'matus.mraz@exetra.sk', '', 1, 3, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `SPI_VIDEOS`
--

CREATE TABLE IF NOT EXISTS `SPI_VIDEOS` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alias` varchar(200) NOT NULL,
  `filename` varchar(300) NOT NULL,
  `videoimage` varchar(300) NOT NULL,
  `commercial` varchar(300) NOT NULL,
  `commercialurl` varchar(300) NOT NULL,
  `location` varchar(10) NOT NULL COMMENT 'the category id in content or the other location like pagetop',
  `position` varchar(10) NOT NULL COMMENT 'the position in specified location 1,2,3 ...',
  `name` varchar(200) NOT NULL,
  `description` varchar(500) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `published` tinyint(1) NOT NULL,
  `viewcount` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `SPI_VIDEOS`
--

