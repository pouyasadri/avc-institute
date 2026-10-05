# France scholarships 2026-2027: v2 (prepared 2 Oct 2026, Europe/Paris)

**Status:** draft. Nothing was published and the site admin was not touched. There are no images: no main image and no `<img>` tags.

## What this is
One blog post in three locales. The locales are written separately, not translated from each other.
- **fa**: for Iranians applying from Iran. Covers the embassy master's scholarship (€900), Eiffel, university awards, Erasmus Mundus's 10% nationality cap, ZRR checks for PhDs, EU sanctions rules on money transfers, translation, age limits and military service. Uses «کمپوس فرانسه» and gives dates in the Solar Hijri calendar.
- **en**: for a general international, English-speaking audience. Not framed around Iran.
- **fr**: for a general francophone audience (Africa, the Maghreb, the Middle East, Haiti). Not framed around Iran. AEFE France Excellence Major gets more space, and there is a France-Visas note specific to Algerian nationals.

| Locale | Title | Slug | Words* |
|---|---|---|---|
| en | Scholarships to Study in France 2026-2027: The Complete Guide for International Students | scholarships-study-france-2026-2027-international-students | 2,112 |
| fr | Bourses pour étudier en France 2026-2027 : le guide complet pour les étudiants internationaux | bourses-etudier-en-france-2026-2027-etudiants-internationaux | 2,490 |
| fa | بورسیه فرانسه برای ایرانیان ۲۰۲۶–۲۰۲۷: راهنمای کامل ورودی سپتامبر ۲۰۲۷ | france-scholarships-iranians-2026-2027 | 2,163 |

\*Whitespace-delimited words with tags stripped, FAQ included. Excerpts (156–158 characters) are in meta.json. Category: Études et Formation / Education & Study / تحصیل و آموزش (id 2 expected).

## Files
- `fa.html`, `fr.html`, `en.html`: TinyMCE bodies. They have no H1 and use only whitelisted tags (no table, figure, hr, iframe, sup or dir). Order is: last-updated line, body, official-sources line, CTA (`bg-primary` block linking to /xx/consult), then the FAQ accordion (`id="faq-accordion"`) last. en and fr have 5 FAQs; fa has 6.
- `meta.json`: titles, slugs, excerpts, keywords, audience, category, last_updated, and the old Oct 1 titles and slugs.
- `sources.md`: every source, the fact it supports, and the check date.
- `_build/`: the Python generators. Run them from inside `_build/`; they write to the current directory.

## What changed vs the Oct 1 draft
- **Audience and slugs:** en and fr were rewritten from scratch for an international audience.
  - Old en: "France Scholarships for Iranian Students 2026: Complete List" (`france-scholarships-iranian-students-2026`).
  - Old fr: "Bourses en France pour étudiants iraniens 2026 : liste complète" (`bourses-etudes-france-etudiants-iraniens-2026`).
  - Old fa slug: `france-scholarships-for-iranians-2026`.
  - The old draft was never published, so no redirects are needed.
- **Eiffel 2027 dates:** the call opened on 2 Oct 2026. The deadline is 6 Jan 2027 and results come in the week of 12 Apr 2027. The draft used the 2026 campaign dates.
- **Eiffel amounts:** the 2027 rules confirm €1,200 (master's) and €2,000 (PhD). Some third-party and Campus France country pages still say €2,100.
- **New Eiffel rules covered:**
  - One nominating institution only.
  - No renomination after an earlier rejection at the same level.
  - MSc only with the CGE label; no apprenticeship programmes.
  - Not combinable with other French state scholarships, so not with the Iran embassy scholarship either.
  - Grading grid (40 points).
  - University internal deadlines: 15 Nov (Institut Agro Dijon, EURECOM) and 6 Nov for Paris-Saclay PhDs.
- **Paris-Saclay restrictions added:** applicants must be under 30; other funding above €600/month disqualifies; not combinable with Eiffel or Erasmus Mundus; paid only after arrival.
- **Visa minimum:** now presented as 47% of the gross SMIC, indexed (€877.50 at launch on 1 Aug 2026; €10,530/year). The figure is unchanged, but the indexation is new.
- **Iran Études en France deadline:** 10 Dey 1405 (31 Dec 2026), with 24 Azar for first-year licence. The draft said "early January".
- **New sections:**
  - Erasmus Mundus 10% nationality cap and one-scholarship-per-person rule.
  - CROUS housing for internationals (complementary phase) and the myth about the CROUS grant.
  - CVEC €105 and the waiver caps (30% → 25%).
  - CIFRE (€27,600 minimum, €14,000 subsidy) and the ZRR two-month silence rule.
  - EU sanctions transfer thresholds (€10k / €40k), cash declaration and Banque de France droit au compte (fa).
  - Campus France Iran remote operation and VFS (fa).
  - Embassy-programme examples: Charpak (en), Haiti (en, fr); AEFE Major.
  - Funding stacking rules, rejection reasons, checklist and a 2027 timeline.
- **Images:** the hero image and the `<img>` placeholder were removed.

## Open uncertainties
1. The 2027 Eiffel guide still quotes the 2026 birth-date cut-offs. The text tells readers to ask their university.
2. The Eiffel "not combinable with other French state scholarships" sentence sits under the guide's doctorate heading. We apply it to all levels, which is consistent with the rules but worth keeping in mind.
3. Not yet published, so the articles reuse the 2026 dates and label them as such:
   - the 2027-28 Iran embassy scholarship call;
   - Paris-Saclay's 2027 scholarship dates;
   - the 2027 CROUS phases.
4. Campus France Iran:
   - The Études en France fee (€250) is marked "as of 2023" on its site.
   - Its news about remote operation and VFS was last updated in 2025.
5. AEFE France Excellence Major amounts come from a 2024 page. IP Paris's "about €82,800" doctoral figure comes from the university page.
6. Iran-linked bank transfers: the legal thresholds are clear, but bank practice varies. Military-service exit rules are mentioned only in general terms, with no figures.
7. Internal links: curl from the box returns **403** for applyvipconseil.com (IP block, rechecked 2 Oct).
   - Links already in the Oct 1 drafts (verified that day) were reused.
   - Five new links were confirmed only through the external WebFetch tool.
   - After pasting into TinyMCE, check that `/xx/...` links were not rewritten to relative `../../` paths.
8. The visa minimum will change whenever the SMIC is revalued, so recheck it before summer 2027.
