# Ilusalong — broneerimise kasutusjuhend

| Väli | Väärtus |
|---|---|
| Dokumendi ID | ILU-UG-BOOKING-001 |
| Versioon | 1.0 |
| Staatus | Koostatud teostatud funktsionaalsuse ja prototüübi põhjal |
| Süsteem | Ilusalong |
| Sihtrühm | Salongi teenuste kliendid ja veebilehe külastajad |
| Keel | Eesti |
| Viimane ülevaatus | 16.11.2026 |

> **Confluence’i kasutamine:** tõsta selle faili sisu Confluence’i lehele. Makett ja klikitav prototüüp on eraldi failis [broneerimise-prototyyp.html](./broneerimise-prototyyp.html); laadi HTML Confluence’i manusesse või ava see brauseris. Prototüüp on näidis ega kirjuta pärisandmebaasi.

## 1. Eesmärk ja ulatus

See juhend õpetab Ilusalongi kliendile teenuse broneerimist, enda broneeringute vaatamist ja tulevase broneeringu tühistamist. Juhend on üles ehitatud ISO/IEC/IEEE 26514 kasutajateabe põhimõtete järgi: sihtrühm, ülesandepõhine teave, selged protseduurid ja veateadete käsitlemine.

Juhend kirjeldab praegust rakendust. See ei tõenda ISO standardile vastavuse auditit ega sertifitseerimist.

### 1.1 Mida saab teha

- valida teenuse ja esitada broneeringu ilma kontot loomata;
- vaadata samas brauseriseansis tehtud broneeringuid;
- tühistada tulevikus toimuva aktiivse broneeringu;
- kasutada veebilehte vene või inglise keeles.

Praegune broneerimisvorm võimaldab valida kuupäeva ja kellaaja, mis on serveri praegusest ajast hilisem. Süsteem ei kontrolli salongi tööaega, töötajate saadavust ega sama aja kattuvaid broneeringuid. Broneering ei ole vaba aja kinnitusega seotud; enne lõpliku kasutuselevõtu otsustamist tuleb need tööreeglid salongiga kokku leppida.

## 2. Enne alustamist

Vaja läheb:

- veebibrauserit, milles küpsised ja seansid on lubatud;
- teenuse valimiseks sobivat kuupäeva ja kellaaega;
- kliendi nime, toimivat e-posti aadressi ja telefoninumbrit.

**Oluline:** „Minu broneeringud” on seotud sama brauseri seansiga, milles broneering tehti. Kui kustutad veebilehe seansiandmed, kasutad teist brauserit või avad lehe teises seadmes, ei pruugi sa varasemaid broneeringuid enam näha ega tühistada.

## 3. Broneerimise makett ja prototüüp

### 3.1 Ekraanide makett

```text
┌─────────────────────────────────────────────────────────────┐
│ ILUSALONG       Teenused   Registreeru   Minu broneeringud RU EN │
├─────────────────────────────────────────────────────────────┤
│ [Teenuse foto]       Naiste juukselõikus                    │
│                      Kaasaegne lõikus koos pesu ja föönisoenguga │
│                      35,00 €                                 │
│                                                             │
│ Broneeri see teenus                                        │
│ Nimi              [____________________________________]   │
│ E-post            [____________________________________]   │
│ Telefon           [____________________________________]   │
│ Kuupäev ja kellaaeg [____________________]                  │
│                      [ Broneeri ]                           │
├─────────────────────────────────────────────────────────────┤
│ Arvustused                                                  │
└─────────────────────────────────────────────────────────────┘
```

```text
┌─────────────────────────────────────────────────────────────┐
│ ILUSALONG       Teenused   Registreeru   Minu broneeringud RU EN │
├─────────────────────────────────────────────────────────────┤
│ Minu broneeringud                                           │
│ Teenus                Kuupäev ja kellaaeg  Olek     Tegevus │
│ Naiste juukselõikus   20.11.2026 15:30     Aktiivne [Tühista]│
│ Klassikaline maniküür 18.11.2026 11:00     Möödunud     —   │
└─────────────────────────────────────────────────────────────┘
```

Kuupäevad joonisel on ainult maketi näited. Tegelik leht kuvab andmebaasis olevad broneeringud.

### 3.2 Klikitav prototüüp

Ava [broneerimise-prototyyp.html](./broneerimise-prototyyp.html) brauseris. Prototüübis saab:

1. liikuda teenusevaate ja „Minu broneeringud” vaate vahel;
2. proovida tulevase kuupäeva ja kontaktandmetega broneerimist;
3. näha kinnitust ja uut rida näidisloendis;
4. proovida aktiivse näidisbroneeringu tühistamist;
5. vahetada prototüübi keelt vene ja inglise vahel.

Prototüübi andmed on ainult brauseri mälus. Lehe uuesti laadimisel taastuvad näidisandmed; päris broneeringut ei looda ega tühistata.

## 4. Teenuse broneerimine

1. Ava **Teenused** ja vali sobiv teenus, et jõuda teenuse detailvaatesse.
2. Vaata üle teenuse nimi, kirjeldus ja hind.
3. Liigu jaotiseni **Book this service / Записаться на услугу**.
4. Sisesta:
   - **Name / Имя** — 1–100 märki;
   - **Email address / Электронная почта** — kehtiv e-posti aadress;
   - **Phone number / Номер телефона** — 5–32 lubatud märki: numbrid, tühikud ja `+().-`;
   - **Date and time / Дата и время** — serveri praegusest ajast hilisem kuupäev ja kellaaeg.
5. Vali **Book now / Забронировать**.
6. Edu korral suunatakse sind lehele **My bookings / Мои бронирования**, kus kuvatakse kinnitusteade ja broneering.

Kui vormi andmed ei sobi, kuvatakse veateade ja broneeringut ei salvestata. Kontrolli välju ning proovi uuesti.

## 5. Broneeringute vaatamine

1. Ava navigeerimismenüüst **My bookings / Мои бронирования**.
2. Kontrolli teenust, kuupäeva-kellaaega ja olekut.
3. Kui loend on tühi, kuvatakse teade, et broneeringuid pole.

Olekute tähendus:

| Olek | Tähendus |
|---|---|
| Aktiivne / Active | Broneeringu aeg ei ole veel saabunud ja seda saab tühistada. |
| Möödunud / Past | Aeg on möödunud; tühistamisnuppu ei kuvata. |
| Tühistatud / Cancelled | Broneering on tühistatud. |

## 6. Broneeringu tühistamine

1. Ava samas brauseris ja seansis **My bookings / Мои бронирования**.
2. Leia tulevikus toimuv **Active / Активно** broneering.
3. Vali **Cancel booking / Отменить запись**.
4. Pärast töötlemist kuvatakse tühistamise teade ning olek muutub väärtuseks **Cancelled / Отменено**.

Tühistada saab ainult sama seansiga seotud tulevast aktiivset broneeringut. Teise brauseri või kasutaja broneeringut ei saa muuta.

## 7. Tõrkeotsing

| Teade või olukord | Mida teha |
|---|---|
| „Choose a future date and time.” | Vali kuupäev ja kellaaeg, mis on tulevikus; serveri kell määrab piiri. |
| „Enter a valid email address.” | Kontrolli e-posti aadressi kirjapilti. |
| „Enter a valid phone number.” | Kasuta numbreid ning lubatud märke `+().-` ja tühikut. |
| „The selected service is not available.” | Mine teenuste lehele ja vali olemasolev teenus uuesti. |
| „Your session expired. Please try again.” | Laadi teenuse leht uuesti ning esita vorm uuesti. |
| „Booking not found or it can no longer be cancelled.” | Kontrolli, et oled samas brauseris ja seansis ning broneering on aktiivne ja tulevikus. |
| Broneeringute loend on ootamatult tühi | Ava sama brauser/profiil, milles broneerisid, ning veendu, et seansiandmeid pole kustutatud. |
| Sobiva aja leidmine pole võimalik | Võta salongiga ühendust; veebileht ei näita veel tööaega ega vabade aegade kalendrit. |

## 8. Ligipääsetavus ja privaatsus

- Väljad on seotud tekstiliste siltidega ning vormi saab kasutada klaviatuuriga.
- Kuupäeva-kellaaja sisendi kuvamine sõltub brauserist ja seadmest.
- Sisesta ainult broneeringuks vajalikud õiged kontaktandmed. Need salvestatakse broneeringu juurde.
- Praegune kasutajavaade ei paku seansi kadumise korral broneeringu taastamist ega kontaktandmete kustutamise funktsiooni. Privaatsuse ja säilitustähtaja kohta tuleb järgida salongi kinnitatud tingimusi.

## 9. Versioon ja seotud dokumendid

| Versioon | Kuupäev | Muudatus |
|---|---|---|
| 1.0 | 16.11.2026 | Esimene kliendijuhend koos maketi kirjelduse ja klikitava prototüübi juhisega. |

Seotud dokument: [Teenuste broneerimise nõuete spetsifikatsioon](./broneerimise-noudespetsifikatsioon.md).
