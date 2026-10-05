from faq_tpl import faq, cta
def ext(url, text): return f'<a href="{url}" target="_blank" rel="noopener">{text}</a>'

SP_EXAM = "https://www.service-public.gouv.fr/particuliers/vosdroits/F39530"
SP_LANG = "https://www.service-public.gouv.fr/particuliers/vosdroits/F34501"
SP_FEES = "https://www.service-public.gouv.fr/particuliers/actualites/A18881"
FC = "https://formation-civique.interieur.gouv.fr/examen-civique/informations-g%C3%A9n%C3%A9rales-sur-lexamen-civique/"
ARR = "https://www.legifrance.gouv.fr/loda/id/JORFTEXT000052381620/2026-01-01"
BASRHIN = "https://www.bas-rhin.gouv.fr/Demarches/Etranger-en-France/ACTU-01-01-2026-nouvel-examen-civique-et-niveau-de-francais-1ers-titres-pluriannuels-CSP-CR"

body = f'''<p class="text-muted small"><strong>Last updated:</strong> 5 October 2026. Rules and fees checked that day against Service-Public, the Ministère de l'Intérieur's civic-training website, Légifrance, France Éducation international and prefecture pages (Bas-Rhin, Haute-Vienne, Seine-Maritime, Oise).</p>

<p><strong>Short answer.</strong> <strong>Who:</strong> since 1 January 2026, most non-EU nationals applying for their <em>first</em> multi-year residence permit (carte de séjour pluriannuelle) or their <em>first</em> 10-year resident card (carte de résident) must pass the <strong>France civic exam</strong> before they file. <strong>Which French level:</strong> A2 for a first multi-year card, B1 for a first resident card. <strong>Renewals:</strong> renewing a multi-year or resident card you already hold needs neither the exam nor a new language certificate.</p>

<p>The details are where people lose time: who is exempt, which certificates count, what the 40 questions cover, how to book a centre, what a card costs since 1 May 2026, and how to time it all before your card expires. We are A.V.C Institute (Apply VIP Conseil), based in Schiltigheim next to Strasbourg, and this is the briefing we give clients before they book anything.</p>

<h2 id="what-is-the-civic-exam">What is the French civic exam?</h2>
<p>The <em>examen civique</em> was created by the immigration law of 26 January 2024. It checks your knowledge of the Republic's values, its institutions and everyday life in France. It has three versions (<em>mentions</em>): "carte de séjour pluriannuelle" (CSP), "carte de résident" (CR) and "naturalisation". The CR version is harder and pitched at B1 French; the CSP version at A2. Three points matter for planning:</p>
<ul>
<li><strong>You pass it before you apply.</strong> Without the pass certificate, your file is not complete.</li>
<li><strong>A CR pass also covers a CSP</strong> when you file on ANEF, according to the Ministère de l'Intérieur.</li>
<li><strong>It never expires,</strong> and you can retake it as often as you need.</li>
</ul>
<p>The Haute-Vienne prefecture adds a detail many people miss: the new rules apply to cards whose validity starts on or after 1 January 2026, whatever date the application was filed.</p>

<h2 id="who-must-pass">Who has to pass the civic exam in 2026?</h2>
<p>According to Service-Public and the Bas-Rhin prefecture, the main groups concerned are:</p>
<ul>
<li><strong>Salaried workers, entrepreneurs and self-employed professionals</strong> moving from a one-year temporary card (carte de séjour temporaire, CST) to their first multi-year card.</li>
<li><strong>Family routes:</strong> spouses of French nationals, parents of French children, family-reunification beneficiaries, and people with strong personal ties to France.</li>
<li><strong>EU long-term resident applicants</strong> (carte de résident de longue durée-UE) after five years of continuous legal residence. This includes Talent Passport and EU Blue Card holders and their families, refugees and subsidiary-protection holders asking for long-term resident status.</li>
<li><strong>First permanent resident card</strong> (carte de résident permanent) applicants.</li>
</ul>

<h2 id="who-is-exempt">Who is exempt?</h2>
<p>The exemptions are broader than most online summaries suggest. You do not need the exam (or, for a CSP, the A2 proof) if:</p>
<ul>
<li><strong>You are over 65.</strong></li>
<li><strong>You hold international protection</strong> (refugee or subsidiary protection) or are stateless, for the cards issued on those grounds, together with your family members.</li>
<li><strong>Your nationality is covered by certain bilateral agreements.</strong> The Haute-Vienne prefecture cites Algeria and Tunisia.</li>
<li><strong>Your card is outside the republican integration contract (CIR).</strong> Under article L413-5 of the immigration code (CESEDA), this covers students, interns, au pairs, temporary and seasonal workers, Talent Passport holders and their families asking for a multi-year card of less than 10 years, and some private and family life grounds.</li>
<li><strong>You studied in France.</strong> Article L413-5 also exempts from the CIR anyone who completed at least three school years in a French secondary school, or at least one academic year of higher education in France. The Bas-Rhin prefecture lists both groups as not subject to the new conditions.</li>
<li><strong>Health or disability.</strong> Adapted conditions or a full exemption are possible with a medical certificate on the official model form.</li>
</ul>
<h3>The catch for international graduates</h3>
<p>Graduates of French universities who switch to a Talent Passport or salaried card are usually outside the civic requirement for that multi-year card. Five years later, though, a first 10-year or EU long-term resident card will require B1 and the CR exam. Your French degree probably already proves B1, so the exam is usually the only new step. Plan for it early.</p>

<h2 id="renewals">Do you need the civic exam to renew your residence permit?</h2>
<p>No, if you are renewing a multi-year card or a resident card you already hold. Service-Public, the Ministère de l'Intérieur and every prefecture page we checked say the same: the exam and the language level apply only to the <em>first</em> card of each type. The Haute-Vienne prefecture gives the clearest example: if you already have a two-year card, you do not need A2 to renew it, but you will need B1 when you ask for your first resident card.</p>
<p>The confusion comes from the word "renewal". Moving from a one-year card to a four-year card feels like a renewal, but legally it is your first multi-year card, so the exam applies unless you are exempt. Moving from a multi-year card to a 10-year card is likewise a first resident card. Renewing a one-year temporary card for another year is not concerned.</p>

<h2 id="a2-or-b1">A2 or B1: which French level for which card?</h2>
<ul>
<li><strong>First multi-year card (CSP): A2.</strong> Simple, routine exchanges: shopping, work basics, describing your background.</li>
<li><strong>First resident card (CR, 10 years), EU long-term resident or permanent resident card: B1.</strong> You follow clear standard speech, cope with most everyday situations and can briefly explain a plan or opinion.</li>
<li><strong>Naturalisation</strong> (for context): B2, plus the "naturalisation" version of the civic exam.</li>
</ul>
<p>The exam itself is written to match those levels. It is taken in French, with no translation.</p>

<h2 id="accepted-certificates">Which language certificates and diplomas are accepted?</h2>
<p>The list comes from the arrêté of 22 December 2025 and Service-Public's language page. You have three ways to prove your level:</p>
<ol>
<li><strong>A recognised test.</strong> Only two are accepted: the TCF from France Éducation international and the TEF from the CCI Paris Île-de-France. The test must assess all four skills (listening, reading, writing, speaking) in one in-person session with ID checks, and the results certificate is valid for <strong>two years</strong>. In practice, the version to book is the <strong>TCF IRN</strong> (Intégration, Résidence et Nationalité) or the TEF IRN.</li>
<li><strong>A French-language diploma, valid for life:</strong> DELF, DALF or DELF Pro (France Éducation international), DUEF (university French diplomas), DFP (CCI Paris) or DCL, at A2 or above for a CSP and B1 or above for a CR.</li>
<li><strong>A French diploma:</strong> the diplôme national du brevet, any diploma issued in the name of the French State at level 3 or above (CAP, BEP and upwards, so the baccalauréat, a licence or a master's), or a professional certification registered in the RNCP at level 3 or above. The Seine-Maritime prefecture confirms that French diplomas earned in France or abroad count, and suggests checking the RNCP number on the France compétences website.</li>
</ol>
<p>Diplomas issued by a foreign authority do not count, even from a French-speaking country.</p>
<h3>TCF IRN: what to expect</h3>
<p>France Éducation international describes four mandatory tests lasting about 1 h 35. Results arrive in your TCF-Candidat account: France Éducation international aims for 15 working days after it receives the session papers, while a Strasbourg centre (CIEL, CCI Campus) warns of at least four weeks. You must wait 20 days between attempts. And for sessions from 1 September 2026, re-marking of the speaking and writing papers is suspended until around autumn 2027, so aim comfortably above your target level.</p>

<h2 id="exam-format">What is on the exam, and how is it marked?</h2>
<p>The format is set by the arrêté of 10 October 2025 and detailed by the Ministère de l'Intérieur:</p>
<ul>
<li><strong>40 multiple-choice questions in French</strong>: 28 knowledge questions and 12 situational questions, each with four options and one correct answer.</li>
<li><strong>45 minutes maximum</strong>, on a computer or tablet. Extra time is only given for a justified medical reason.</li>
<li><strong>Pass mark: 32 correct answers (80%).</strong> A wrong answer and a blank answer both score zero, so answer every question.</li>
</ul>
<p>Every candidate gets the same mix: 11 questions on the Republic's principles and values (motto, symbols, laïcité, plus 6 situations), 6 on institutions (voting, how the Republic is organised, one on the EU), 11 on rights and duties (including 6 situations), 8 on history, geography and culture, and 4 on daily life (settling in, healthcare, work, parenting and school).</p>
<p>The official list of knowledge questions for the CSP and CR versions is published free on the {ext(FC, "Ministère de l'Intérieur's civic-training site")}; the situational questions are not. The ministry warns that paying for question banks is unnecessary and that paid sites are not official and may contain errors.</p>
<h3>On the day</h3>
<p>Bring a valid residence permit, the ID document the arrêté lists for these versions; if you only hold a récépissé, ask your centre in writing first. A photo is taken for your certificate. Late arrivals are turned away, phones must be off, and attempted fraud brings a two-year ban.</p>

<h2 id="registration">How to register at an approved centre</h2>
<p>The ministry has approved two bodies, and each runs a network of centres across France:</p>
<ul>
<li><strong>CCI Paris Île-de-France</strong> (the "Le français des affaires" network). Have your AGDREF foreign-national number ready when you register.</li>
<li><strong>France Éducation international.</strong> You pre-register on the test-civique.fr platform, choose the right version, then pay and confirm with the centre.</li>
</ul>
<p>Both centre maps are linked from {ext(SP_EXAM, "Service-Public's civic exam page")}. Each centre sets its own fee: in Strasbourg, the Institut Stralang charges €70 for weekday afternoon sessions (checked 5 October 2026). Registrations are generally non-refundable, so double-check you selected the CSP or CR version.</p>
<p>If you signed an integration contract (CIR) with OFII, you also attend the free four-day civic training (24 hours); its attendance certificate goes into your file.</p>

<h2 id="timeline">How to time it with your card's expiry date</h2>
<p>According to Service-Public, a salaried or entrepreneur multi-year card must be requested within the two months before your card expires; other categories have their own windows. Since the exam certificate must be in your file on the day you apply, work backwards:</p>
<ol>
<li><strong>Six months before expiry:</strong> check whether you are exempt and gather your diplomas. Book a TCF IRN only if no diploma covers you.</li>
<li><strong>Five months before:</strong> sit the TCF IRN, then allow three to four weeks for results, and 20 days before any retake.</li>
<li><strong>Four months before:</strong> take the civic exam. Some centres release results within a day.</li>
<li><strong>Filing window:</strong> upload everything on ANEF. A renewal filed after the deadline costs an extra €180, unless you can show force majeure or a valid visa.</li>
</ol>

<h2 id="fees-2026">France residence permit fees in 2026</h2>
<p>The 2026 Finance Act raised fees from 1 May 2026 (Service-Public). You pay by tax stamp (timbre fiscal) once your application is accepted; the text message announcing your card states the amount.</p>
<ul>
<li><strong>First issue</strong> of a temporary card, multi-year card or resident card: <strong>€350</strong> (€300 tax + €50 stamp), up from €225.</li>
<li><strong>Reduced first-issue rate</strong> (students, interns, au pairs, job-seekers and business creators, family reunification, seasonal workers): €150, up from €75.</li>
<li><strong>Renewal:</strong> <strong>€250</strong> (€200 tax + €50 stamp), up from €225.</li>
<li><strong>Reduced renewal rate:</strong> €100, up from €75.</li>
<li><strong>Late renewal:</strong> an extra €180, unchanged.</li>
<li><strong>Exams and tests:</strong> set by each centre. In Strasbourg, the civic exam costs about €70 and the TCF IRN €150 to €155.</li>
</ul>
<p>One subtlety: the Oise prefecture explains that switching from a temporary card to a multi-year card, or from a multi-year card to a resident card, is charged at the renewal rate, even though it counts as a "first" card for the exam. Go by the amount ANEF or the prefecture asks you for.</p>

<h2 id="common-situations">Three common situations</h2>
<h3>"I'm finishing my master's in France and have a job offer."</h3>
<p>If you move to a Talent Passport card, or to a salaried card after at least one academic year of French higher education, you are normally outside the civic requirement for that card. Keep your enrolment certificates and diploma: they prove the exemption now and your French level later. Still a student? Our guide to {'<a href="/en/blog/how-much-money-french-student-visa-2026">how much money you need as a student</a>'} covers the funding side.</p>
<h3>"I'm married to a French citizen."</h3>
<p>Your first multi-year "private and family life" card needs A2 and the CSP exam; your later 10-year card, B1 and the CR exam. If your French is close to B1, take the CR version once for both.</p>
<h3>"I've lived in France for five years on work cards."</h3>
<p>The EU long-term resident card needs B1, the CR exam and sufficient, stable resources. Start the TCF IRN early: with re-marking suspended, a narrow miss means another session at least 20 days later.</p>

<h2 id="checklist">Your short checklist</h2>
<ul>
<li>Confirm which card you are asking for: first CSP, first CR, or a true renewal</li>
<li>Check the exemptions: age, protection status, nationality agreement, CIR-exempt card, studies in France</li>
<li>Language proof: a French diploma (with RNCP level), a DELF, DALF or DUEF, or a TCF IRN or TEF IRN less than two years old</li>
<li>Civic exam: the right version, booked at an approved centre, certificate downloaded</li>
<li>CIR training attendance certificate, if you signed a CIR</li>
<li>Filing window noted in your calendar, and the fee budgeted</li>
</ul>
<p>Our <a href="/en/services/residence-permit">residence permit service</a> covers document audits before ANEF, timing and prefecture follow-up. No one can promise a prefecture's decision, but we make sure your file is complete and on time.</p>

<p class="small text-muted"><strong>Official sources:</strong> {ext(SP_EXAM, "Service-Public – civic exam (F39530)")}, {ext(SP_LANG, "Service-Public – French level (F34501)")}, {ext(SP_FEES, "Service-Public – fees from 1 May 2026")}, {ext(ARR, "Arrêté of 10 October 2025")}, {ext(FC, "Ministère de l'Intérieur – civic exam")}, {ext(BASRHIN, "Bas-Rhin prefecture")}.</p>

{cta("Not sure whether the civic exam applies to you?", "Tell us your current card, its expiry date and your diplomas. Our advisers will check whether you are exempt, which proof of French to use and when to file.", "Request a Free Consultation", "/en/consult", "View All Our Services", "/en/services")}

{faq("FAQ: the France civic exam 2026", [
("Do I need the civic exam to renew my residence permit?", "No, if you are renewing a multi-year card or resident card you already hold. Moving from a one-year card to a multi-year card, however, counts as a first card."),
("Can I take the civic exam in English?", "No. All 40 questions are in French, with no translation. The CSP version is written for A2 learners and the CR version for B1."),
("How long are the civic exam pass and the TCF IRN valid?", "The civic exam certificate has no expiry date. A TCF or TEF certificate is valid for two years. DELF, DALF, DUEF, DFP and DCL diplomas and French national diplomas never expire."),
("Does my French master's degree prove my French level?", "Usually yes. Under the arrêté of 22 December 2025, any diploma issued in the name of the French State at level 3 or above counts, and a master's is level 7. Diplomas from foreign authorities do not count."),
("How much does a first multi-year card cost in 2026?", "Since 1 May 2026, a first issue costs €350 (€300 tax plus €50 stamp), and a renewal €250. The Oise prefecture says moving from a temporary to a multi-year card is charged at the renewal rate, so check the amount ANEF asks for."),
])}'''
open("en.html","w",encoding="utf-8").write(body+"\n")
