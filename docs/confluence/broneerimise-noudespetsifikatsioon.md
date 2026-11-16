# Ilusalong — teenuste broneerimise nõuete spetsifikatsioon

| Väli | Väärtus |
|---|---|
| Dokumendi ID | ILU-SRS-BOOKING-001 |
| Versioon | 1.0 |
| Staatus | Teostatud funktsionaalsuse baas; sidusrühmade kinnitus ootel |
| Süsteem | Ilusalong |
| Moodul | Teenuste broneerimine |
| Viimane ülevaatus | 16.11.2026 |
| Omanik | Tooteomanik määramata |

> **Confluence’i kasutamine:** kopeeri leht koos pealkirjade, tabelite ja nõuete ID-dega Confluence’i. Nõuete ID-d on püsivad viited arendusele, testimisele ja muudatuste jälgitavusele.

## 1. Eesmärk

See dokument määratleb Ilusalongi veebirakenduse teenuste broneerimise mooduli nõuded viisil, mis toetab nende üheselt mõistetavat arendamist, kontrollimist ja jälgitavust. Struktuur ja nõuete sõnastus järgivad ISO/IEC/IEEE 29148:2018 nõuete väljatöötamise põhimõtteid.

Dokument kirjeldab praegu realiseeritud käitumist. See ei tähenda, et toode või dokument oleks sõltumatult auditeeritud või standardi järgi sertifitseeritud.

## 2. Ulatus

Moodul võimaldab külastajal:

1. valida teenuse detailvaates kuupäeva ja kellaaja ning esitada broneeringu koos kontaktandmetega;
2. vaadata sama brauseriseansi kaudu tehtud broneeringuid;
3. tühistada enda tulevikus toimuva aktiivse broneeringu.

Broneeringu loomine ei nõua kliendikontot. Broneeringu omandiõigus seotakse serveripoolse brauseriseansiga.

### 2.1 Ulatusest väljas

- töötajate töögraafik, teenuse kestus ja vabade aegade kalender;
- konfliktsete või kattuvate broneeringute tuvastamine;
- maksed, automaatsed e-kirjad/SMS-id ja administraatori broneeringute haldus;
- broneeringu omandi taastamine teises brauseris või pärast seansiküpsise kaotamist.

## 3. Mõisted ja konventsioonid

| Mõiste | Tähendus |
|---|---|
| Broneering | Kliendi kontaktandmete, teenuse ja kuupäeva-kellaajaga seotud kirje. |
| Omanikuseanss | Serveri PHP-seanss, mille juhuslikust tokenist tuletatud räsi salvestatakse broneeringuga. |
| Aktiivne | Andmebaasi olek `active`; möödunud aktiivne aeg kuvatakse kliendile kui „Möödunud”, kuid kirje olekut automaatselt ei muudeta. |
| Tulevikus toimuv | Broneeringu aeg on rakenduse serveri praegusest ajast hilisem. |

**Nõudevorm:** sõna „peab” tähistab kohustuslikku nõuet. Iga nõue on ID-ga, ühe eesmärgiga ja kontrollitav.

## 4. Stakeholder’id ja kasutajad

| Roll | Eesmärk |
|---|---|
| Klient/külastaja | Broneerida teenus ja hallata enda brauseriseansi broneeringuid. |
| Salongi administraator | Hallata teenuste kataloogi; broneeringute administreerimine ei kuulu selle versiooni ulatusse. |
| Süsteemi haldur | Paigaldada rakendus ja andmebaasi migratsioon ning tagada PHP/MySQL-i töö. |

## 5. Süsteemi kontekst ja piirangud

- Veebirakendus kasutab PHP MVC struktuuri: marsruutimine failis `route/routing.php`, äriloogika mudelis `model/Booking.php`, esitlus vaadetes `view/bookings.php` ja teenuse detailvaates.
- Andmeid hoitakse MySQL-i tabelis `bookings`; teenuse olemasolu kontrollitakse tabelist `services`.
- Broneeringu loomise vorm asub teenuse detailvaates. „Minu broneeringud” on avalik leht, mille päringud piiratakse omaniku brauseriseansiga.
- Veebiliides ja staatilised teated peavad töötama vene ja inglise keeles; kasutatav keel valitakse rakenduse olemasoleva keelelülitiga.
- Kuupäeva-kellaaja sisend on `datetime-local`. Ajavahemiku ajatsoon ei ole praeguses teostuses kasutajale ega andmebaasi väärtusele eraldi määratud.

## 6. Funktsionaalsed nõuded

### 6.1 Broneeringu esitamine

| ID | Nõue | Kontroll |
|---|---|---|
| ILU-BKG-FR-001 | Süsteem peab kuvama olemasoleva teenuse detailvaates broneerimisvormi, milles saab esitada nime, e-posti, telefoninumbri ning kuupäeva ja kellaaja. | Ava teenuse detailvaade; kõik neli sisendit ja esitamisnupp on olemas. |
| ILU-BKG-FR-002 | Süsteem peab nõudma kõigi broneerimisvormi väljade täitmist ning kontrollima andmeid serveris ka siis, kui kliendipoolset kontrolli eiratakse. | Esita puuduvate või vigaste väljadega päring; kirjet ei looda. |
| ILU-BKG-FR-003 | Nimi peab pärast tühikute eemaldamist olema 1–100 märki; e-post peab vastama kehtivale e-posti vormingule ja olema kuni 254 märki; telefon peab olema 5–32 märki ning sisaldama ainult numbreid, tühikuid ja märke `+().-`. | Piirjuhtude ja vigase vormingu sisendtestid. |
| ILU-BKG-FR-004 | Broneeringu kuupäev-kellaaeg peab olema kehtiv kujul `YYYY-MM-DDTHH:MM` ja serveri praegusest ajast hilisem. | Esita vigane, mineviku ja tuleviku väärtus; ainult tuleviku väärtus läbib kontrolli. |
| ILU-BKG-FR-005 | Süsteem peab kontrollima, et viidatud `service_id` vastab olemasolevale teenusele; puuduva teenuse puhul broneeringut ei salvestata. | Esita kehtiva ja puuduva teenuse ID-ga päring. |
| ILU-BKG-FR-006 | Eduka esitamise korral peab süsteem salvestama broneeringu olekuga `active`, kliendi nime, e-posti, telefoni, teenuse ID ja valitud kuupäeva-kellaaja. | Kontrolli loodud andmebaasikirjet. |
| ILU-BKG-FR-007 | Süsteem peab siduma broneeringu praeguse brauseriseansi omanikutokeni räsiga; toor-tokenit ei tohi andmebaasi salvestada. `user_id` peab külalise broneeringul olema `NULL`. | Kontrolli DB kirjet ja võrdle kahe eraldi seansi nähtavust. |
| ILU-BKG-FR-008 | Eduka broneeringu järel peab süsteem suunama kasutaja lehele „Minu broneeringud” ja kuvama kinnitusteate. Vigase esitamise järel peab süsteem kuvama vea ega tohi luua broneeringut. | Esita kehtiv ja vigane vorm ning kontrolli tulemust. |

### 6.2 Broneeringute vaatamine ja tühistamine

| ID | Nõue | Kontroll |
|---|---|---|
| ILU-BKG-FR-009 | Süsteem peab kuvama „Minu broneeringud” lehel ainult selle brauseriseansi omanikutokeniga seotud kirjeid, järjestatuna kuupäeva-kellaaja järgi kahanevalt. | Loo kirjed kahes eraldi seansis ja võrdle tulemusi ning järjestust. |
| ILU-BKG-FR-010 | Iga kirje juures peab süsteem kuvama teenuse nime, kuupäeva-kellaaja ja kasutajale arusaadava oleku. Möödunud `active` kirje kuvatakse olekuna „Möödunud”; andmebaasi olekut ei muudeta automaatselt. | Kontrolli tulevase, möödunud ja tühistatud kirje kuvamist. |
| ILU-BKG-FR-011 | Tühistamisnupp peab olema saadaval ainult tulevikus toimuvale `active` kirjele. | Ava leht tulevase, möödunud ja tühistatud kirjega. |
| ILU-BKG-FR-012 | Tühistamine peab toimuma POST-päringuga ning nõudma kehtivat CSRF-tokenit. Süsteem peab muutma kirje olekuks `cancelled` ainult siis, kui kirje kuulub päringu teinud brauseriseansile, on `active` ja toimub tulevikus. | Proovi kehtivat tühistamist, puuduva/vale tokeniga päringut, võõra seansi ID-d ja möödunud broneeringut. |
| ILU-BKG-FR-013 | Kui tühistamine ei vasta omandi- või olekunõuetele, ei tohi andmebaasi kirjet muuta; kasutajale peab kuvama mittetundlik veateade. | Kontrolli DB olekut ebaõnnestunud tühistamise järel. |
| ILU-BKG-FR-014 | Kui seansil broneeringuid pole, peab leht kuvama selgesõnalise tühja oleku. | Ava leht uues brauseriseansis. |

## 7. Välisliidesed ja marsruudid

| Liides | Meetod | Sisend / tulemus |
|---|---|---|
| `service?service_id={id}` | GET | Teenuse detail ja broneerimisvorm. |
| `createBooking` | POST | `service_id`, `customer_name`, `customer_email`, `customer_phone`, `booking_date`, `csrf_token`; edu korral suunab „Minu broneeringud” lehele. |
| `myBookings` | GET | Omanikuseansi broneeringute loend. |
| `cancelBooking` | POST | `booking_id`, `csrf_token`; suunab tagasi „Minu broneeringud” lehele. |

Kõik kasutajalt saadud tekstiväärtused tuleb enne HTML-is kuvamist HTML-konteksti järgi kodeerida. Andmebaasipäringud peavad kasutama parameetriseeritud päringuid.

## 8. Andmenõuded

Tabel `bookings` luuakse migratsiooniga `database/migrations/20261116_create_bookings.sql`.

| Väli | Tüüp / piirang | Otstarve |
|---|---|---|
| `booking_id` | `INT UNSIGNED`, primaarvõti, automaatselt kasvav | Broneeringu identifikaator. |
| `user_id` | `INT NULL` | Reserveeritud sisselogitud kasutaja seostamiseks; külalisbroneeringul `NULL`. |
| `session_token_hash` | `CHAR(64) NOT NULL` | Broneeringu omandi tõend brauseriseansi sees; sisaldab tokeni SHA-256 räsi. |
| `service_id` | `INT NOT NULL` | Broneeritud teenuse identifikaator. |
| `customer_name` | `VARCHAR(100) NOT NULL` | Kliendi nimi. |
| `customer_email` | `VARCHAR(254) NOT NULL` | Kliendi e-post. |
| `customer_phone` | `VARCHAR(32) NOT NULL` | Kliendi telefon. |
| `booking_date` | `DATETIME NOT NULL` | Valitud kuupäev ja kellaaeg. |
| `status` | `ENUM('active','cancelled')`, vaikimisi `active` | Broneeringu olek. |
| `created_at` | `TIMESTAMP`, vaikimisi loomisaeg | Kirje loomise aeg. |

**Märkus:** praegune migratsioon ei lisa `services`-tabelile välisvõtit. Rakenduse tasemel kontrollitakse teenuse olemasolu broneeringu loomisel; teenuse hilisem kustutamine võib jätta broneeringu teenuseta. Nime ja teenuse hetkehinna väärtust broneeringu kirjes ei talletata.

## 9. Kvaliteedi- ja turvanõuded

| ID | Nõue | Kontroll |
|---|---|---|
| ILU-BKG-NFR-001 | Süsteem peab kasutama broneeringu loomise ja tühistamise päringutes serveripoolset CSRF-kontrolli; tühistamise muutmistoiming ei tohi olla GET-päring. | Turvatest puuduva, vale ja kehtiva tokeniga. |
| ILU-BKG-NFR-002 | Süsteem peab piirama broneeringute lugemise ja muutmise omaniku brauseriseansiga, sõltumata päringus esitatud `booking_id` väärtusest. | Autoriseerimistest eraldi seanssidega. |
| ILU-BKG-NFR-003 | Süsteem peab kasutama parameetriseeritud SQL-päringuid ning kodeerima HTML-i väljastatavad kliendi- ja teenuseandmed. | Koodiülevaatus ja sisendite turvatestid. |
| ILU-BKG-NFR-004 | Broneerimise pealkirjad, väljad, olekud ja teated peavad olema kasutatavad rakenduse vene ja inglise keele valikuga. | Läbi käia broneerimis- ja tühistamisvoog mõlemas keeles. |
| ILU-BKG-NFR-005 | Broneeringu tühistamine peab muutma olekut, mitte kustutama kirjet. | Kontrolli, et pärast tühistamist säilib sama `booking_id` ja `status='cancelled'`. |

## 10. Kasutusjuhtumid

### UC-01 — Teenuse broneerimine

**Eeltingimus:** klient avab olemasoleva teenuse detailvaate.  
**Põhivoog:** klient sisestab kontaktandmed ja tulevase kuupäeva-kellaaja → esitab vormi → süsteem kontrollib CSRF-tokenit, välju ja teenust → süsteem salvestab aktiivse broneeringu → süsteem kuvab kinnituse ja broneeringu loendis.  
**Erandid:** vigased või puuduvad väljad, mineviku aeg, aegunud seanss või puuduv teenus → kirjet ei looda ja kasutajale kuvatakse viga.

### UC-02 — Broneeringu tühistamine

**Eeltingimus:** sama brauseriseanss avab tulevikus toimuva aktiivse broneeringu.  
**Põhivoog:** klient valib „Tühista” → kinnitab POST-päringu CSRF-tokeniga → süsteem kontrollib omaniku tokenit, olekut ja aega → süsteem määrab olekuks `cancelled` → süsteem kuvab kinnituse.  
**Erandid:** võõras ID, vale token, möödunud aeg või juba tühistatud kirje → andmeid ei muudeta ja kuvatakse veateade.

## 11. Vastavuse kontroll ja jälgitavus

| Nõuete rühm | Kontrolli liik | Tõendus |
|---|---|---|
| FR-001–008 | Funktsionaalne/API-test | Vorm, serveripoolne valideerimine ja salvestatud kirje. |
| FR-009–014 | Funktsionaalne ja autoriseerimistest | Loendi järjestus, omandi eraldatus, olek ja tühistamise tulemus. |
| NFR-001–003 | Turvatest ja koodiülevaatus | CSRF, omandi kontroll, SQL-i parameetrid ja HTML-kodeerimine. |
| NFR-004 | Kasutajaliidese kontroll | Vene- ja ingliskeelne voog. |
| NFR-005 | Andmebaasitest | Tühistamine säilitab kirje ja muudab olekut. |

Väljalase loetakse selle spetsifikatsiooni järgi kontrollituks, kui kõik nõuded on seotud testi või ülevaatusega, ükski kohustuslik kontroll ei ebaõnnestu ning testide tulemused on lisatud väljalaske tõendusmaterjalile.

## 12. Eeldused, piirangud ja lahendamata küsimused

| ID | Küsimus / eeldus | Mõju ja vajalik otsus |
|---|---|---|
| ILU-BKG-OPEN-001 | Ajatsoon pole määratletud; HTML-i kuupäev-kellaaeg on ajatsoonita ning võrdlus kasutab PHP/MySQL-i serverikellasid. | Määrata salongi ajatsoon ja ühtlustada rakenduse ning DB seaded enne ajast sõltuvate broneeringute kasutuselevõttu. |
| ILU-BKG-OPEN-002 | Vabu aegu ega kattuvaid broneeringuid ei kontrollita. | Otsustada töögraafiku, teenuse kestuse, puhvri ja sama aja topeltbroneeringu reeglid. |
| ILU-BKG-OPEN-003 | Broneeringu loend on seotud brauseriseansiga; küpsise/seansi kadumisel klient ei pääse kirjetele ligi. | Otsustada, kas lisada kliendikonto või turvaline e-posti teel taastamine. |
| ILU-BKG-OPEN-004 | Kliendi kontaktandmed salvestatakse kirjena. Säilitustähtaega ja kustutamise protsessi pole määratud. | Kinnitada privaatsusteade, ligipääs, säilitustähtaeg ja kustutamise reeglid. |
| ILU-BKG-OPEN-005 | `service_id` ei ole migratsioonis välisvõtmega seotud; teenuse kustutamine võib muuta broneeringu viite tühiseks. | Otsustada ajaloo säilitamise poliitika ja sobiv FK/arhiivimise lahendus. |
| ILU-BKG-OPEN-006 | `user_id` on andmemudelis olemas, kuid külalisvoog seda ei kasuta. | Määrata, kas hilisem kliendikonto seostatakse olemasolevate broneeringutega. |
| ILU-BKG-OPEN-007 | Broneeringul puudub teenuse hinna hetkeseis. | Otsustada, kas kliendile kuvatav hind peab olema broneeringu loomise aja seisuga auditeeritav. |

## 13. Muudatuste ajalugu

| Versioon | Kuupäev | Muudatus |
|---|---|---|
| 1.0 | 16.11.2026 | Esimene spetsifikatsioon praegu teostatud külalisbroneeringu voo ja lahendamata küsimuste kohta. |
