<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Publish the OpenSEO-priority civic exam / A2-B1 guide in FA, EN, FR
 * and tighten the financial-proof post titles toward the GSC head term.
 */
return new class extends Migration
{
    public function up(): void
    {
        $categoryId = DB::table('blog_category_translations')
            ->where('locale', 'en')
            ->where('slug', 'immigration-visas-france')
            ->value('blog_category_id');

        if (! $categoryId) {
            $categoryId = DB::table('blog_categories')->orderBy('id')->value('id');
        }

        $authorId = DB::table('users')->where('is_admin', true)->orderBy('id')->value('id')
            ?? DB::table('users')->orderBy('id')->value('id');

        if (! $categoryId || ! $authorId) {
            Log::warning('Civic exam blog migration skipped: missing category or author');

            return;
        }

        $existingFa = DB::table('blog_post_translations')
            ->where('locale', 'fa')
            ->where('slug', 'azmoon-madani-france-2026')
            ->exists();

        if (! $existingFa) {
            $postId = DB::table('blog_posts')->insertGetId([
                'category_id' => $categoryId,
                'author_id' => $authorId,
                'is_pinned' => true,
                'published_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $bodies = $this->bodies();

            foreach (['fa', 'en', 'fr'] as $locale) {
                DB::table('blog_post_translations')->insert([
                    'blog_post_id' => $postId,
                    'locale' => $locale,
                    'title' => $bodies[$locale]['title'],
                    'slug' => $bodies[$locale]['slug'],
                    'excerpt' => $bodies[$locale]['excerpt'],
                    'body' => $bodies[$locale]['body'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Striking-distance quick win: put head term "تمکن مالی فرانسه" early in FA title.
        DB::table('blog_post_translations')
            ->where('locale', 'fa')
            ->where('slug', 'france-student-visa-financial-proof-2026')
            ->update([
                'title' => 'تمکن مالی فرانسه ۲۰۲۶ | مبلغ، مدارک و راهنمای ویزای تحصیلی',
                'updated_at' => now(),
            ]);

        DB::table('blog_post_translations')
            ->where('locale', 'en')
            ->where('slug', 'france-student-visa-financial-proof-2026')
            ->update([
                'title' => 'France Financial Proof (تمکن مالی) 2026 | Student Visa Amounts & Documents',
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        $fa = DB::table('blog_post_translations')
            ->where('locale', 'fa')
            ->where('slug', 'azmoon-madani-france-2026')
            ->first();

        if ($fa) {
            DB::table('blog_post_translations')->where('blog_post_id', $fa->blog_post_id)->delete();
            DB::table('blog_posts')->where('id', $fa->blog_post_id)->delete();
        }
    }

    /**
     * @return array<string, array{title: string, slug: string, excerpt: string, body: string}>
     */
    private function bodies(): array
    {
        $faBody = <<<'HTML'
<p><strong>آخرین بررسی منابع رسمی:</strong> ۲ اکتبر ۲۰۲۶ — <a href="https://www.service-public.fr/particuliers/vosdroits/F39530" target="_blank" rel="noopener">Service-Public F39530</a>، <a href="https://www.service-public.fr/particuliers/vosdroits/F34501" target="_blank" rel="noopener">F34501</a>، <a href="https://formation-civique.interieur.gouv.fr/examen-civique/informations-générales-sur-lexamen-civique/" target="_blank" rel="noopener">formation-civique.interieur.gouv.fr</a>.</p>
<p>از ۱ ژانویه ۲۰۲۶، برای بسیاری از درخواست‌های <strong>اولین</strong> کارت اقامت چندساله (CSP) سطح زبان فرانسوی <strong>A2</strong> و موفقیت در <strong>آزمون مدنی (examen civique)</strong> لازم است. برای <strong>اولین</strong> کارت مقیم معمولاً سطح <strong>B1</strong> + آزمون مدنی لازم است.</p>
<h2>چه کسانی باید آزمون مدنی بدهند؟</h2>
<ul>
<li>متقاضیان اولین کارت اقامت چندساله (در موارد مشمول)</li>
<li>متقاضیان اولین کارت مقیم (در موارد مشمول)</li>
</ul>
<p><strong>تمدید کارت قبلی:</strong> اگر همین الان CSP یا کارت مقیم دارید و فقط همان را تمدید می‌کنید، طبق Service-Public معمولاً نیازی به آزمون مدنی جدید ندارید. این دقیقاً نقطه‌ای است که بسیاری از ویدیوها اشتباه منتقل می‌کنند.</p>
<p>جزئیات تمدید، مدارک و ANEF را در صفحه خدمت بخوانید: <a href="/fa/services/residence-permit">تمدید کارت اقامت فرانسه ۲۰۲۶</a>.</p>
<h2>فرمت آزمون</h2>
<ul>
<li>آزمون دیجیتال چندگزینه‌ای</li>
<li>حدود ۴۰ سؤال (دانش عمومی + موقعیت‌های کاربردی)</li>
<li>حداکثر حدود ۴۵ دقیقه</li>
<li>معمولاً نیاز به حداقل حدود ۸۰٪ پاسخ درست (حدود ۳۲ از ۴۰)</li>
</ul>
<h2>مدرک زبان A2 / B1</h2>
<p>سطح زبان معمولاً با مدرک/گواهی پذیرفته‌شده (دیپلم، گواهی حرفه‌ای یا آزمون زبان معتبر) اثبات می‌شود. فهرست دقیق مدارک قابل قبول را در صفحه رسمی Service-Public درباره سطح زبان چک کنید؛ قبل از ثبت‌نام در مرکز آزمون، همان صفحه را دوباره باز کنید.</p>
<h2>معافیت‌ها (خلاصه)</h2>
<p>برخی گروه‌ها ممکن است معاف باشند (مثلاً سن بالای ۶۵، برخی وضعیت‌های حمایتی/خانوادگی یا سوابق تحصیلی خاص در فرانسه). معافیت‌ها موردی هستند؛ قبل از فرض معافیت، پرونده را با منبع رسمی و مشاور بررسی کنید.</p>
<h2>A.V.C چه کمکی می‌کند؟</h2>
<p>ما در فرانسه پرونده تمدید/تغییر وضعیت را ممیزی می‌کنیم، مسیر ANEF را هماهنگ می‌کنیم و اگر آزمون مدنی یا مدرک زبان برای <em>اولین</em> کارت چندساله/مقیم لازم باشد، برنامه زمانی پرونده را با آن تنظیم می‌کنیم. برای اعتراض‌های پیچیده با وکیل همکار کار می‌کنیم.</p>
<p><a href="/fa/consult?service=residence-permit">رزرو مشاوره اقامت</a> · <a href="/fa/services/residence-permit">راهنمای تمدید کارت اقامت</a></p>
HTML;

        $enBody = <<<'HTML'
<p><strong>Official sources last checked:</strong> 2 October 2026 — <a href="https://www.service-public.fr/particuliers/vosdroits/F39530" target="_blank" rel="noopener">Service-Public F39530</a>, <a href="https://www.service-public.fr/particuliers/vosdroits/F34501" target="_blank" rel="noopener">F34501</a>, <a href="https://formation-civique.interieur.gouv.fr/examen-civique/informations-générales-sur-lexamen-civique/" target="_blank" rel="noopener">formation-civique.interieur.gouv.fr</a>.</p>
<p>Since 1 January 2026, many <strong>first</strong> multi-year residence cards (CSP) require French <strong>A2</strong> plus the <strong>civic exam</strong>. Many <strong>first</strong> resident cards require <strong>B1</strong> plus the civic exam.</p>
<h2>Who must take the civic exam?</h2>
<ul>
<li>Applicants for a first multi-year card (when the category is covered)</li>
<li>Applicants for a first resident card (when the category is covered)</li>
</ul>
<p><strong>Renewals:</strong> If you already hold a CSP or resident card and are renewing it, Service-Public generally says you do <em>not</em> need a new civic exam. That distinction is widely misstated on social video.</p>
<p>For renewal documents and ANEF steps, see <a href="/en/services/residence-permit">French residence permit renewal 2026</a>.</p>
<h2>Exam format</h2>
<ul>
<li>Digital multiple-choice test</li>
<li>About 40 questions</li>
<li>Up to about 45 minutes</li>
<li>Typically around 80% correct answers required</li>
</ul>
<h2>Proving A2 / B1</h2>
<p>French level is usually proven with an accepted diploma, professional certificate, or language certification. Always re-check the official Service-Public list before booking a test centre.</p>
<h2>What A.V.C Institute does</h2>
<p>We audit renewal and change-of-status dossiers in France, align ANEF timing with any required civic exam / language proof for first CSP/resident cards, and coordinate partner lawyers for complex appeals.</p>
<p><a href="/en/consult?service=residence-permit">Book a consultation</a> · <a href="/en/services/residence-permit">Residence renewal guide</a></p>
HTML;

        $frBody = <<<'HTML'
<p><strong>Sources officielles vérifiées le :</strong> 2 octobre 2026 — <a href="https://www.service-public.fr/particuliers/vosdroits/F39530" target="_blank" rel="noopener">Service-Public F39530</a>, <a href="https://www.service-public.fr/particuliers/vosdroits/F34501" target="_blank" rel="noopener">F34501</a>, <a href="https://formation-civique.interieur.gouv.fr/examen-civique/informations-générales-sur-lexamen-civique/" target="_blank" rel="noopener">formation-civique.interieur.gouv.fr</a>.</p>
<p>Depuis le 1er janvier 2026, de nombreuses <strong>premières</strong> cartes de séjour pluriannuelles exigent le niveau <strong>A2</strong> et l’<strong>examen civique</strong>. De nombreuses <strong>premières</strong> cartes de résident exigent le <strong>B1</strong> et l’examen civique.</p>
<h2>Qui doit passer l’examen ?</h2>
<ul>
<li>Demandeurs d’une première CSP (selon catégories concernées)</li>
<li>Demandeurs d’une première carte de résident (selon catégories concernées)</li>
</ul>
<p><strong>Renouvellement :</strong> si vous détenez déjà une CSP ou une carte de résident et la renouvelez, Service-Public indique en principe qu’un nouvel examen civique n’est pas requis. Beaucoup de contenus sociaux confondent première délivrance et renouvellement.</p>
<p>Pour les documents et l’ANEF, voir <a href="/fr/services/residence-permit">renouvellement titre de séjour 2026</a>.</p>
<h2>Format de l’examen</h2>
<ul>
<li>QCM numérique</li>
<li>Environ 40 questions</li>
<li>Environ 45 minutes maximum</li>
<li>Environ 80 % de bonnes réponses en général</li>
</ul>
<h2>Ce que fait A.V.C</h2>
<p>Nous auditons les dossiers de renouvellement / changement de statut en France, calons le calendrier ANEF avec l’examen civique ou la preuve linguistique si nécessaires, et coordonnons un avocat partenaire pour les recours complexes.</p>
<p><a href="/fr/consult?service=residence-permit">Réserver une consultation</a> · <a href="/fr/services/residence-permit">Guide de renouvellement</a></p>
HTML;

        return [
            'fa' => [
                'title' => 'آزمون مدنی فرانسه ۲۰۲۶ | چه کسانی A2/B1 لازم دارند؟',
                'slug' => 'azmoon-madani-france-2026',
                'excerpt' => 'راهنمای فارسی آزمون مدنی (examen civique) و قوانین زبان A2/B1 از ژانویه ۲۰۲۶؛ تفاوت اولین کارت با تمدید، با استناد به Service-Public.',
                'body' => $faBody,
            ],
            'en' => [
                'title' => 'French Civic Exam 2026 | Who Needs A2/B1?',
                'slug' => 'french-civic-exam-2026',
                'excerpt' => 'Plain-English guide to the French civic exam and A2/B1 rules since January 2026 — and why renewals usually do not require them.',
                'body' => $enBody,
            ],
            'fr' => [
                'title' => 'Examen civique 2026 | Qui doit justifier A2/B1 ?',
                'slug' => 'examen-civique-france-2026',
                'excerpt' => 'Guide clair de l’examen civique et des niveaux A2/B1 depuis janvier 2026, avec la distinction première délivrance vs renouvellement.',
                'body' => $frBody,
            ],
        ];
    }
};
